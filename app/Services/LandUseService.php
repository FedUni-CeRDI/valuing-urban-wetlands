<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Query\Builder;

class LandUseService
{
    /**
     * @param string $wkt
     * @param string $table_name
     * @param string $description_field
     * @param int $wkt_srid
     * @param int $table_srid
     * @return string
     */
    private function buildPercentageIntersectionQuery(string $wkt, string $table_name, string $description_field, int $wkt_srid, int $table_srid): string
    {
        return sprintf(<<<SQL
            WITH selected_wetland AS (
                SELECT ST_Transform(
                    ST_GeomFromText(
                        %s,
                        $wkt_srid
                    ),
                    $table_srid
                ) AS geom
            )
            SELECT
                ROUND(CAST(area AS NUMERIC), 2) AS "area",
                "usage",
                ROUND (
                    CAST(
                        area / (SUM(area) OVER ()) * 100
                        AS NUMERIC
                    ),
                    2
                ) AS percentage
            FROM (
                SELECT
                    SUM(ST_Area(ST_Intersection(selected_wetland.geom, land_use."geom"))) AS area,
                    COALESCE(land_use.$description_field, 'Unknown') AS "usage"
                FROM $table_name AS "land_use"
                RIGHT JOIN selected_wetland ON st_intersects(selected_wetland."geom", land_use."geom")
                GROUP BY "usage"
            ) AS areas
            GROUP BY area, "usage"
            ORDER BY "percentage" DESC
SQL,
            DB::getPdo()->quote($wkt),
        );
    }

    private function getIntersectingLandUsePercentages(string $wkt, string $land_use_table_name, string $land_use_description_field, int $wkt_srid = 4326, int $land_use_srid = 3111): array
    {
        return DB::select($this->buildPercentageIntersectionQuery($wkt, $land_use_table_name, $land_use_description_field, $wkt_srid, $land_use_srid));
    }

    public function getVicmapPlanningZones(string $wkt)
    {
        return $this->getIntersectingLandUsePercentages($wkt, 'aurin_plan_zone_3111', 'zone_desc');
    }

    public function getVicmapPlanningOverlays(string $wkt)
    {
        return $this->getIntersectingLandUsePercentages($wkt, 'aurin_plan_overlay_3111', 'zone_desc');
    }

    public function getCatchmentLandUse(string $wkt)
    {
        return $this->getIntersectingLandUsePercentages($wkt, 'aurin_landuse18_3111', 'land_use');
    }

    public function getVluisPropertyClassification(string $wkt)
    {
        return $this->getIntersectingLandUsePercentages($wkt, 'aurin_vluis2017_7899', 'lu_desc', land_use_srid: 7899);
    }

    public function getVluisLandUse(string $wkt)
    {
        return $this->getIntersectingLandUsePercentages($wkt, 'aurin_vluis2017_7899', 'lu_description_a', land_use_srid: 7899);
    }


    public function getVluisLandCover(string $wkt)
    {
        return $this->getIntersectingLandUsePercentages($wkt, 'aurin_vluis2017_7899', 'lc_desc_17', land_use_srid: 7899);
    }
}
