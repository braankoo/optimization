<?php

namespace App\Listeners;

use App\Events\AffiliateDataDownloaded;
use App\Library\SQL\Operator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;


class InsertPreparedDataInStatsTables implements ShouldQueue {


    public  $queue = 'listeners';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }


    /**
     * @param \App\Events\AffiliateDataDownloaded $event
     * @throws \Throwable
     */
    public function handle(AffiliateDataDownloaded $event)
    {
        try
        {
            DB::connection($event->adPlatform)->beginTransaction();
            Operator::insertToStatsTable($event->adPlatform, $event->startDate, $event->endDate);
            DB::connection($event->adPlatform)->commit();

            Operator::dropTemporaryTable($event->adPlatform);
        } catch ( \Exception $e )
        {
            DB::connection($event->adPlatform)->rollBack();
            throw $e;
        }


    }
}
