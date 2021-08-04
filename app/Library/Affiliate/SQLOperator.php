<?php

namespace App\Library\Affiliate;

use Illuminate\Support\Facades\DB;

class SQLOperator {


    /**
     * @param array $data
     */
    public static function insertData(string $adPlatform, array $data)
    {
        DB::connection($adPlatform)->table('Temp_platform_stats')->upsert(
            $data,
            [ 'ad_group_id', 'day' ],
            [
                'profile' => DB::raw('Temp_platform_stats.profile + profile'),
                'upgrade' => DB::raw('Temp_platform_stats.upgrade + upgrade'),
                'earned'  => DB::raw('Temp_platform_stats.earned + earned')
            ]);
    }

}
