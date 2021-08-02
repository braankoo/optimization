<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\MccAccount;
use FirstBeatMedia\AdWordManagement\Facades\AdWordManagement;
use Illuminate\Console\Command;

class GetClients extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:clients';

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

        foreach ( [ 'bing', 'google', 'gemini' ] as $adPlatform )
        {
            MccAccount::on($adPlatform)->each(function ($mcc) use ($adPlatform) {
                $clients = AdWordManagement::mcc($mcc)->getClients();

                Client::on($adPlatform)->upsert($clients, [ 'name' ], [ 'name', 'id', 'mcc_account_id' ]);
            });
        }


        return 0;
    }
}
