<?php

namespace App\Services;

use App\Traits\PrefixedLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LandUseService
{

    use PrefixedLogger;

    public function __construct()
    {
        $this->setLoggerPrefix(basename(__CLASS__));
    }

    private function buildBufferedWetlandCTE(string $feature, int $destination_srid): string
    {
        return sprintf(<<<CTE
            WITH wetland_buffer AS (
                SELECT
                    ST_MakeValid(
                        ST_Transform(
                            ST_Difference(
		                        ST_Buffer(
                                    ST_Transform(geom, 7844),
                                    (%d / 111.1 / 1000) -- Convert metres to decimal degrees
                                ),
		                        ST_Transform(wetlands.geom, 7844)
	                        ),
                            $destination_srid
                        )
                    ) AS geom
                FROM "wetlands"
                WHERE "id" = %s
            )
CTE,
            config('aurin.wetland_buffer'),
            DB::getPdo()->quote($feature)
        );

    }

    private function buildPercentageIntersectionQuery(string $feature, string $table_name, string $description_field, int $table_srid): string
    {
        $query = sprintf(
            <<<SQL
            %s
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
                    SUM(ST_Area(ST_Intersection(wetland_buffer.geom, land_use."geom"))) AS area,
                    COALESCE(land_use.$description_field, 'Unknown') AS "usage"
                FROM $table_name AS "land_use"
                RIGHT JOIN wetland_buffer ON st_intersects(wetland_buffer."geom", land_use."geom")
                GROUP BY "usage"
            ) AS areas
            GROUP BY area, "usage"
            ORDER BY "percentage" DESC
SQL,
            $this->buildBufferedWetlandCTE($feature, $table_srid)
        );

        return $query;
    }

    private function getIntersectingLandUsePercentages(string $feature, string $land_use_table_name, string $land_use_description_field, int $land_use_srid = 3111): array
    {
        return DB::select($this->buildPercentageIntersectionQuery($feature, $land_use_table_name, $land_use_description_field, $land_use_srid));
    }

    public function getVicmapPlanningZones(string $feature)
    {
        return $this->getIntersectingLandUsePercentages($feature, 'aurin_plan_zone_3111', 'zone_desc');
    }

    public function getVicmapPlanningOverlays(string $feature)
    {
        return $this->getIntersectingLandUsePercentages($feature, 'aurin_plan_overlay_3111', 'zone_desc');
    }

    public function getCatchmentLandUse(string $feature)
    {
        return $this->getIntersectingLandUsePercentages($feature, 'aurin_landuse18_3111', 'land_use');
    }

    public function getVluisPropertyClassification(string $feature)
    {
        return $this->getIntersectingLandUsePercentages($feature, 'aurin_vluis2017_7899', 'lu_desc', land_use_srid: 7899);
    }

    public function getVluisLandUse(string $feature)
    {
        return $this->getIntersectingLandUsePercentages($feature, 'aurin_vluis2017_7899', 'lu_description_a', land_use_srid: 7899);
    }

    public function getVluisLandCover(string $feature)
    {
        return $this->getIntersectingLandUsePercentages($feature, 'aurin_vluis2017_7899', 'lc_desc_17', land_use_srid: 7899);
    }

}
