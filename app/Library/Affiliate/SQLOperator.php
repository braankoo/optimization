<?php

namespace App\Library\Affiliate;

use Illuminate\Support\Facades\DB;

class SQLOperator {


    /**
     * @param string $adPlatform
     * @param array $data
     */
    public static function insertData(string $adPlatform, array $data)
    {
        DB::connection($adPlatform)->table('Temp_platform_stats')
            ->upsert($data, [ 'ad_group_id', 'created_at' ],
                [
                    'profile' => DB::raw('VALUES(profile) + profile'),
                    'upgrade' => DB::raw('VALUES(upgrade) + upgrade'),
                    'earned'  => DB::raw('VALUES(earned) + earned')
                ]
            );
    }

}
