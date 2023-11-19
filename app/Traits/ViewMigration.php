<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait ViewMigration
{

    public static function runViews(string|array $views): void
    {
        $views = is_string($views) ? [$views] : $views;

        foreach ($views as $view) {
            DB::unprepared(file_get_contents(database_path(sprintf('sql/views/%s.sql', $view))));
        }
    }

    public static function refreshViews(string|array $views): void
    {
        $views = is_string($views) ? [$views] : $views;
        foreach ($views as $view) {
            $view = preg_replace('/^[\d_]+/', '', $view);
            DB::statement(
                <<<SQL
    REFRESH MATERIALIZED VIEW "$view";
SQL
            );
        }
    }

}
