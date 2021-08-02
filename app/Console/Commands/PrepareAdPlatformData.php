<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class PrepareAdPlatformData extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepare:ad-platform-data {adPlatform} {--startDate=yesterday} {--endDate=today}';

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

        \App\Jobs\PrepareAdPlatformData::dispatch(
            $this->argument('adPlatform'),
            Carbon::parse($this->option('startDate'))->format('Y-m-d'),
            Carbon::parse($this->option('endDate'))->format('Y-m-d'),
        );

        return 0;

    }
}
