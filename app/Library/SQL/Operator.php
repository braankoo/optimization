<?php

namespace App\Library\SQL;

use Illuminate\Support\Facades\DB;

class Operator {


    /**
     * @param string $adPlatform
     */
    public static function generateTemporaryTables(string $adPlatform)
    {
        self::dropTemporaryTable($adPlatform);

        DB::connection($adPlatform)->statement('
                    CREATE TABLE Temp_platform_stats(
                        impressions int not null,
                        clicks int not null,
                        cost bigint not null,
                        avg_position double(3, 1) not null,
                        profile bigint default 0,
                        upgrade bigint default 0,
                        earned bigint default 0,
                        created_at date not null,
                        ad_group_id bigint unsigned not null,
                        primary key (ad_group_id, created_at)
                    ) ENGINE=InnoDB');

    }

    /**
     * @param string $adPlatform
     */
    public
    static function insertToStatsTable(string $adPlatform, string $startDate, string $endDate)
    {

        self::deleteData($adPlatform, $startDate, $endDate);

        self::insertToStatsAdGroups($adPlatform);

        self::insertToStatsCampaigns($adPlatform, $startDate, $endDate);

        self::insertToStatsClient($adPlatform, $startDate, $endDate);

    }

    /**
     * @param string $platform
     */
    public
    static function dropTemporaryTable(string $platform)
    {
        DB::connection($platform)->statement('DROP TABLE IF EXISTS Temp_platform_stats');
    }

    /**
     * @param string $adPlatform
     * @param string $startDate
     * @param string $endDate
     */
    private
    static function deleteData(string $adPlatform, string $startDate, string $endDate): void
    {
        foreach ( [ 'stats_ad_groups', 'stats_campaigns', 'stats_clients' ] as $table )
        {
            while ( DB::connection($adPlatform)->table($table)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->count() > 0 )
            {
                DB::connection($adPlatform)->table($table)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->take(10000)->delete();
            }
        }
    }

    /**
     * @param string $adPlatform
     * @param string $startDate
     * @param string $endDate
     */
    private
    static function insertToStatsCampaigns(string $adPlatform, string $startDate, string $endDate): void
    {
        DB::connection($adPlatform)->statement("
            INSERT INTO stats_campaigns

            SELECT SUM(impressions) as impressions,
                    SUM(clicks) as clicks,
                    SUM(cost) as cost,
                    AVG(avg_position) as avg_postion,
                    SUM(profile) as profile,
                   SUM(upgrade) as upgrade,
                   SUM(earned) as earned,
                   created_at,
                    ag.campaign_id as campaign_id
            FROM stats_ad_groups

            JOIN ad_groups ag
                    ON stats_ad_groups.ad_group_id = ag.id
            WHERE created_at between ? and ?
                GROUP BY campaign_id, created_at;
                ",
            [ $startDate, $endDate ]
        );
    }

    /**
     * @param string $adPlatform
     * @param string $startDate
     * @param string $endDate
     */
    private
    static function insertToStatsClient(string $adPlatform, string $startDate, string $endDate): void
    {
        DB::connection($adPlatform)->statement("
            INSERT INTO stats_clients

             SELECT SUM(impressions) as impressions,
                    SUM(clicks) as clicks,
                    SUM(cost) as cost,
                    AVG(avg_position) as avg_postion,
                    SUM(profile) as profile,
                   SUM(upgrade) as upgrade,
                   SUM(earned) as earned,
                   created_at,
                cm.client_id as client_id

            FROM stats_campaigns

                JOIN campaigns cm
                    ON stats_campaigns.campaign_id = cm.id
                where created_at between ? and ?
            GROUP BY client_id, created_at;
                ",
            [ $startDate, $endDate ]
        );
    }

    /**
     * @param string $adPlatform
     */
    private
    static function insertToStatsAdGroups(string $adPlatform): void
    {

        DB::connection($adPlatform)->statement("
            INSERT INTO stats_ad_groups
                SELECT Temp_platform_stats.* FROM Temp_platform_stats
                JOIN ad_groups ON Temp_platform_stats.ad_group_id = ad_groups.id
                "
        );

    }

}
