<?php

namespace App\Jobs;

use App\Models\Ad;
use App\Models\Client;
use FirstBeatMedia\AdWordManagement\Facades\AdWordManagement;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetAds implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * @var \App\Models\Client
     */
    public Client $client;
    /**
     * @var string|null
     */
    public ?string $adPlatform;

    /**
     * @var int
     */
    public int $tries = 5;


    /**
     * @var array|int[]
     */
    public array $backoff = [ 30, 60, 120, 360 ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->adPlatform = $client->getConnectionName();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        switch ( $this->adPlatform )
        {
            case 'google':
                $this->client->campaigns()->chunk(500, function ($campaigns) {
                    $this->fetchAndStore($campaigns->map->id);
                });
            case 'bing':
                $this->client->campaigns()->each(function ($campaign) {
                    $campaign->adGroups()->chunk(500, function ($adGroups) {
                        $this->fetchAndStore($adGroups->map->id);
                    });
                });
            case 'gemini':
                $this->fetchAndStore([]);


        }


    }


    /**
     * @param array $ids
     */
    private function fetchAndStore(array $ids)
    {
        foreach ( array_chunk(AdWordManagement::client($this->client)->getAds($ids), 1000) as $chunk )
        {
            $data = [];
            for ( $i = 0; $i < count($chunk); $i ++ )
            {
                $data[$i] = AdWordManagement::response($this->client->getConnectionName())->ad($chunk[$i]);

            }
            Ad::on($this->adPlatform)->upsert($data, [ 'id' ], array_keys($data[0]));
        }
    }
}
