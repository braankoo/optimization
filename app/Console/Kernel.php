<?php

namespace App\Console;

use App\Console\Commands\GetCampaigns;
use App\Console\Commands\GetClients;
use App\Console\Commands\PrepareAdPlatformData;
use App\Console\Commands\PrepareData;
use App\Console\Commands\PrepareWebmastersAndSites;
use Carbon\Carbon;
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
        GetCampaigns::class,
        PrepareAdPlatformData::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        foreach ( [ 'google', 'bing', 'gemini' ] as $platform )
        {
            $schedule->command('prepare:date', [ $platform, '--startDate' => Carbon::now()->format('Y-m-d') ])->everyFourHours();
            $schedule->command('inspire')->everyMinute();
        }

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
