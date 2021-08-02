<?php

namespace App\Library\Affiliate;

use Illuminate\Support\Facades\DB;

class SQLOperator {


    /**
     * @param array $data
     */
    public static function insertData(string $adPlatform, array $data)
    {
        DB::connection($adPlatform)->table('Temp_platform_stats')->upsert($data, [ 'ad_group_id', 'day' ], [ 'profile', 'upgrade', 'earned' ]);
    }

}
