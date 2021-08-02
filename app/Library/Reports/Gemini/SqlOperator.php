<?php

namespace App\Library\Reports\Gemini;

use App\Library\Reports\SqlOperatorFactory;
use App\Library\Reports\SqlOperatorInterface;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class SqlOperator implements SqlOperatorInterface {


    public
    function loadToTemporaryTable(string $reportPath)
    {
        DB::connection('gemini')->statement("
            LOAD DATA LOCAL INFILE '{$reportPath}'
               INTO TABLE Temp_platform_stats
               FIELDS
                   TERMINATED BY ','
                   ENCLOSED BY '\"'
               LINES
                   TERMINATED BY '\n'
               IGNORE 1 ROWS
               (@Ad_group_ID, @Impressions, @Average_Position, @Clicks, @Spent, @Day)

               SET  ad_group_id            = @Ad_Group_ID,
                    impressions   = @Impressions,
                    avg_position  = @Average_Position,
                    clicks        = @Clicks,
                    cost         = @Spend * 1000000,
                    created_at           = @Day

            ");
    }

}
