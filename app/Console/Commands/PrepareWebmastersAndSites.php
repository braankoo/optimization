<?php

namespace App\Console\Commands;

use App\Jobs\SetUpCampaignWebmasterAndSite;
use App\Models\Campaign;
use Illuminate\Console\Command;

class PrepareWebmastersAndSites extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepare:sites-webmasters {adPlatform}';

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
        Campaign::on($this->argument('adPlatform'))->whereIn('status', [ 'ENABLED', 'ACTIVE', 'PAUSED' ])->each(function ($campaign) {
            SetUpCampaignWebmasterAndSite::dispatch($campaign);
        });

        return 0;
    }
}
