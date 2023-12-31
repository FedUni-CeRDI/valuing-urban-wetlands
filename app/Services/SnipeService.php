<?php

namespace App\Services;

use App\Traits\PrefixedLogger;
use Illuminate\Support\Facades\DB;

class SnipeService
{
    use PrefixedLogger;

    public function __construct()
    {
        $this->setLoggerPrefix(basename(__CLASS__));
    }

    public function getMaxSeasonCountsByLocation(string $wkt): array
    {
        $sql = sprintf(<<<'SQL'
            WITH season_counts AS (
                SELECT
                    MAX("count") AS "count",
                    "season"
                FROM "snipe_all_site_data_cleaned"
                WHERE ST_CONTAINS(
                    ST_GeomFromText(?, 7844),
                    ST_Transform("geom", 7844)
                )
                GROUP BY "season"
            )
            SELECT
                DISTINCT snipe_all_site_data_cleaned."season",
                season_counts."count"
            FROM snipe_all_site_data_cleaned
            LEFT JOIN season_counts ON snipe_all_site_data_cleaned.season = season_counts."season"
            WHERE
                "date" >= to_date('2014-07-01', 'YYYY-MM-DD') and "date" <= to_date('2023-06-30', 'YYYY-MM-DD')
            ORDER BY snipe_all_site_data_cleaned."season"
    SQL
        );

        return DB::select($sql, [$wkt]);
    }

    public function getAlaMaxSeasonCountsByLocation(string $wkt): array
    {
        $sql = sprintf(<<<'SQL'
            WITH season_counts AS (
                SELECT
                    MAX("individualCount") AS "count",
                    "season"
                FROM "ala_snipe_records_cleaned"
                WHERE ST_CONTAINS(
                    ST_GeomFromText(?, 7844),
                    "geom"
                )
                GROUP BY "season"
            )
            SELECT
                DISTINCT ala_snipe_records_cleaned."season",
                season_counts."count"
            FROM ala_snipe_records_cleaned
            LEFT JOIN season_counts ON ala_snipe_records_cleaned.season = season_counts."season"

            WHERE
                "eventDate" >= to_date('2014-07-01', 'YYYY-MM-DD') and "eventDate" <= to_date('2023-06-30', 'YYYY-MM-DD')
            ORDER BY ala_snipe_records_cleaned."season"
    SQL
        );

        // TODO: WHERE "eventDate" filter shouldn't be hardcoded. This will cause issues if data sources become dynamic

        return DB::select($sql, [$wkt]);
    }
}
