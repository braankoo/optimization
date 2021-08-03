<?php

namespace App\Listeners;

use App\Events\AdPlatformDataDownloaded;
use App\Events\AffiliateDataDownloaded;
use App\Jobs\GetAffiliateData;
use App\Library\SQL\Operator;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;

class DownloadAffiliateData implements ShouldQueue {

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public ?string $queue = 'listeners';

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public int $delay = 5;
    public string $startDate;
    public string $endDate;

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

        $jobs = Campaign::on($event->adPlatform)
            ->with([ 'webmasters', 'client.platforms' ])
            ->get()
            ->groupBy([ 'webmasters.*.name', 'client.platforms.*.url' ])->map(function ($groups, $webmaster) use ($event) {

                $platforms = $groups->keys();

                return $platforms->map(function ($platform) use ($webmaster, $event) {

                    $jobs = [];
                    foreach ( $this->getDateRange($event) as $date )
                    {
                        $jobs[] = new GetAffiliateData(
                            $event->startDate,
                            $platform,
                            $webmaster,
                            $event->adPlatform
                        );
                    }

                    return $jobs;

                })->toArray();

            })->values()->flatten()->toArray();

        Bus::batch($jobs)->then(function (Batch $batch) use ($event) {

            event(new AffiliateDataDownloaded($event->adPlatform, $event->startDate, $event->endDate));

        })->allowFailures(false)
            ->catch(function (Batch $batch) use ($event) {

            })->dispatch();
    }

    /**
     * @param \App\Events\AdPlatformDataDownloaded $event
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
