<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Client;
use FirstBeatMedia\AdWordManagement\Facades\AdWordManagement;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class GetCampaigns implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * @var \App\Models\Client
     */
    public Client $client;

    public ?string $adPlatform;


    public int $tries = 5;


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
        foreach ( array_chunk(AdWordManagement::client($this->client)->getCampaigns(), 1000) as $campaigns )
        {
            $data = [];
            for ( $i = 0; $i < count($campaigns); $i ++ )
            {
                $data[$i] = AdWordManagement::response($this->adPlatform)->campaign($campaigns[$i]);
                $data[$i]['client_id'] = $this->client->id;
            }
            Campaign::on($this->adPlatform)->upsert($data, [ 'id' ], array_keys($data[0]));
        }
    }
}
