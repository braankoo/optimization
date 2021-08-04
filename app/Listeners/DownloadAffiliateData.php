<?php

namespace App\Listeners;

use App\Events\AdPlatformDataDownloaded;
use App\Events\AffiliateDataDownloaded;
use App\Jobs\GetAffiliateData;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

/**
 *
 */
class DownloadAffiliateData implements ShouldQueue {

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';
    /**
     * The time (seconds) before the job should be processed.
     *
     * @var string
     */
    public string $startDate;
    /**
     * @var string
     */
    public string $endDate;
    /**
     * @var int
     */
    public int $timeout = 600;

    /**
     * @var bool
     */
    public bool $failOnTimeout = true;

    public $delay = 60;
    /**
     * @var \Illuminate\Bus\Batch
     */
    public Batch $batch;


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param AdPlatformDataDownloaded $event
     * @return void
     * @throws \Throwable
     */
    public function handle(AdPlatformDataDownloaded $event)
    {


        $this->batch = Bus::batch([])
            ->then(
                function (Batch $batch) use ($event) {
                    event(new AffiliateDataDownloaded($event->adPlatform, $event->startDate, $event->endDate));
                })
            ->catch(function (Batch $batch) use ($event) {

            })->onQueue('affiliate')->dispatch();


        Campaign::on($event->adPlatform)
            ->with([ 'webmasters', 'client.platforms' ])
            ->get()
            ->groupBy([ 'webmasters.*.name', 'client.platforms.*.url' ])->each(function ($groups, $webmaster) use ($event) {

                $platforms = $groups->keys();

                $platforms->each(function ($platform) use ($webmaster, $event) {

                    foreach ( $this->getDateRange($event) as $date )
                    {
                        $this->batch->add([ new GetAffiliateData($date, $platform, $webmaster, $event->adPlatform) ]);
                    }
                });

            });


    }


    /**
     * @param \App\Events\AdPlatformDataDownloaded $event
     * @return array
     */
    private function getDateRange(AdPlatformDataDownloaded $event): array
    {
        $range = [];
        for ( $i = 0; $i <= Carbon::parse($event->startDate)->diffInDays(Carbon::parse($event->endDate)); $i ++ )
        {
            $range[] = Carbon::parse($event->startDate)->addDays($i)->format('Y-m-d');
        }

        return $range;
    }
}
