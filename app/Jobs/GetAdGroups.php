<?php

namespace App\Jobs;

use App\Models\AdGroup;
use App\Models\Campaign;
use App\Models\Client;
use FirstBeatMedia\AdWordManagement\Facades\AdWordManagement;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetAdGroups implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;


    public int $tries = 1;


    public array $backoff = [ 30, 60, 120, 360 ];

    /**
     * @var \App\Models\Client
     */
    public Client $client;

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
     */
    public function handle()
    {

        switch ( $this->client->getConnectionName() )
        {

            case 'gemini':
                $this->fetchAndStore([]);
                break;
            case 'google':
                $this->client->campaigns()->chunk(1, function ($campaigns) {
                    $this->fetchAndStore($campaigns->map->id->toArray());
                });
                break;
            case 'bing':
                $this->client->campaigns()->chunk(500, function ($campaigns) {
                    $this->fetchAndStore($campaigns->map->id->toArray());
                });
        }

    }

    /**
     * @param array $ids
     */
    private function fetchAndStore(array $ids)
    {

        foreach ( array_chunk(AdWordManagement::client($this->client)->getAdGroups($ids), 1000) as $chunk )
        {

            $data = [];

            for ( $i = 0; $i < count($chunk); $i ++ )
            {
                $data[$i] = AdWordManagement::response($this->client->getConnectionName())->adGroup($chunk[$i]);
            }

            AdGroup::on($this->client->getConnectionName())->upsert($data, [ 'id' ], array_keys($data[0]));
        }

    }
}
