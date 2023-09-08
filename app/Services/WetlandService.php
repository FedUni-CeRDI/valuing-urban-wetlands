<?php

namespace App\Services;

use App\Traits\PrefixedLogger;
use Illuminate\Support\Facades\DB;

class WetlandService
{
    use PrefixedLogger;

    public function __construct()
    {
        $this->setLoggerPrefix(basename(__CLASS__));
    }

    public function getWetlandNames(): array
    {
        $result = DB::select(<<<SQL
    SELECT
        DISTINCT "name",
                 "id"
    FROM "wetlands"
    WHERE "name" != 'UNNAMED'
SQL
    );

        return $result;
    }
}
