<?php

namespace App\Console\Commands;

use App\Jobs\SetUpCampaignWebmasterAndSite;
use App\Library\Reports\SedItFactory;
use App\Library\Reports\SqlOperatorFactory;
use App\Library\SQL\Operator;
use App\Models\Ad;
use App\Models\AdGroup;
use App\Models\Campaign;
use App\Models\Client;
use Carbon\Carbon;
use FirstBeatMedia\AdWordManagement\Facades\AdWordManagement;
use Illuminate\Console\Command;

class GetCampaigns extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Operator::generateTemporaryTables('bing');
        Client::on('bing')->take(1)->get()->each(function ($client) {

            $report = AdWordManagement::report($client)->get('2021-07-01', '2021-07-10');

            $preparedCVS = SedItFactory::make($client->getConnectionName())->prepareForSql($report);

            SqlOperatorFactory::make($client->getConnectionName())->loadToTemporaryTable($preparedCVS);
            Operator::insertToStatsTable('bing', '2021-07-01', '2021-07-10');
        });


        return 0;
    }
}
