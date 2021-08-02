<?php

namespace App\Console;

use App\Console\Commands\GetCampaigns;
use App\Console\Commands\GetClients;
use App\Console\Commands\PrepareData;
use App\Console\Commands\PrepareWebmastersAndSites;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        PrepareData::class,
        PrepareWebmastersAndSites::class,
        GetClients::class,
        GetCampaigns::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
