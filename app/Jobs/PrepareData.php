<?php

namespace App\Jobs;

use App\Events\AdPlatformDataDownloaded;
use App\Library\SQL\Operator;
use App\Models\Client;
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
class PrepareData implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public string $adPlatform;
    /**
     * @var string
     */
    public string $startDate;
    /**
     * @var string
     */
    public string $endDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $adPlatform, string $startDate, string $endDate)
    {
        $this->adPlatform = $adPlatform;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        $adPlatform = $this->adPlatform;
        $startDate = $this->startDate;
        $endDate = $this->endDate;

        Operator::generateTemporaryTables($adPlatform);

        $jobs = Client::on($this->adPlatform)->get()->map(function ($client) {

            return [
                new GetReport($client, $this->startDate, $this->endDate)
            ];

        });

        Bus::batch($jobs)
            ->then(function (Batch $batch) use ($adPlatform, $startDate, $endDate) {
                event(new AdPlatformDataDownloaded($adPlatform, $startDate, $endDate));
            })
            ->catch(function (Batch $batch) use ($adPlatform) {
                $batch->cancel();
            })
            ->dispatch();
    }
}

