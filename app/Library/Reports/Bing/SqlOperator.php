<?php

namespace App\Library\Reports\Bing;

use App\Library\Reports\SqlOperatorInterface;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class SqlOperator implements SqlOperatorInterface {

    public function loadToTemporaryTable(string $reportPath)
    {
        DB::connection('bing')->statement("
            LOAD DATA LOCAL INFILE '{$reportPath}'
               INTO TABLE Temp_platform_stats
               FIELDS
                   TERMINATED BY ','
                   ENCLOSED BY '\"'
               LINES
                   TERMINATED BY '\r\n'
               IGNORE 1 ROWS
               (@AdGroupId, @Impressions, @AveragePosition, @Clicks, @Spend, @TimePeriod)

               SET ad_group_id = @AdGroupId,
                   impressions = @Impressions,
                   avg_position = @AveragePosition,
                   clicks = @Clicks,
                   cost = ROUND(@Spend*1000000),
                   created_at = @TimePeriod"
        );

    }

}
