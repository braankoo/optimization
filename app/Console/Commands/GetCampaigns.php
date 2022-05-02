<?php

namespace App\Console\Commands;

use App\Jobs\GetAdGroups;
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
    protected $signature = 'get:campaigns {adPlatform}';

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

        Client::on($this->argument('adPlatform'))->each(function($client) {
//            \App\Jobs\GetCampaigns::dispatch($client);

            GetAdGroups::dispatch($client);

        });


    }
}
