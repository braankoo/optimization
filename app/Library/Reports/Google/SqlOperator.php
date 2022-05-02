<?php

namespace App\Library\Reports\Google;

use App\Library\Reports\SqlOperatorInterface;
use Illuminate\Support\Facades\DB;

class SqlOperator implements SqlOperatorInterface {


    /**
     * @param string $reportPath
     */
    public function loadToTemporaryTable(string $reportPath)
    {
        DB::connection('google')->statement("
            LOAD DATA LOCAL INFILE '{$reportPath}'
               INTO TABLE Temp_platform_stats
               FIELDS
                   TERMINATED BY ','
                   ENCLOSED BY '\"'
               LINES
                   TERMINATED BY '\n'
               IGNORE 1 ROWS
               (@Ad_group_ID, @Impressions, @Clicks, @Cost, @Day)

               SET ad_group_id = @Ad_group_ID,
                   impressions = @Impressions,
                   clicks = @Clicks,
                   cost = @Cost,
                   created_at = @Day
                   ");
    }


}
