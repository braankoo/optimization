<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class PrepareData extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepare:data {adPlatform} {--startDate=yesterday} {--endDate=today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare Data';

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
     * @throws \Throwable
     */
    public function handle(): int
    {
        \App\Jobs\PrepareData::dispatch(
            $this->argument('adPlatform'),
            Carbon::parse($this->option('startDate'))->format('Y-m-d'),
            Carbon::parse($this->option('endDate'))->format('Y-m-d'),
        );

        return 0;
    }
}
