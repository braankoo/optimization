<?php

namespace App\Jobs;

use App\Events\AdPlatformDataDownloaded;
use App\Library\SQL\Operator;
use App\Models\Client;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class PrepareAdPlatformData implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var \App\Models\Client
     */
    public Client $client;
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
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        if ($this->client->getConnectionName() == 'gemini')
        {
            $jobs = [
                new \App\Jobs\GetCampaigns($this->client),
                new \App\Jobs\GetAdGroups($this->client),
                new GetAds($this->client)
            ];
        } else
        {
            $jobs = [
                new \App\Jobs\GetCampaigns($this->client),
                new \App\Jobs\GetAdGroups($this->client)
            ];
        }

        Bus::batch($jobs)
            ->allowFailures(false)
            ->dispatch();

    }
}

