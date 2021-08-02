<?php

namespace App\Providers;

use App\Events\AdPlatformDataDownloaded;
use App\Events\AffiliateDataDownloaded;
use App\Listeners\DownloadAffiliateData;
use App\Listeners\InsertPreparedDataInStatsTables;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class               => [
            SendEmailVerificationNotification::class,
        ],
        AdPlatformDataDownloaded::class => [
            DownloadAffiliateData::class
        ],
        AffiliateDataDownloaded::class  => [
            InsertPreparedDataInStatsTables::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
