<?php

namespace App\Jobs;

use App\Models\Campaign;
use FirstBeatMedia\AdWordManagement\Facades\AdWordManagement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateBids implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $adPlatform;
    public int $campaignId;
    public float $bid;
    /**
     * @var \Illuminate\Support\Collection
     */
    public Collection $adGroups;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $adPlatform, int $campaignId, float $bid, Collection $adGroups)
    {
        $this->adPlatform = $adPlatform;
        $this->campaignId = $campaignId;
        $this->bid = $bid;
        $this->adGroups = $adGroups;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AdWordManagement::adGroup(Campaign::on($this->adPlatform)->find($this->campaignId)->client)->setBid($this->bid, $this->campaignId, $this->adGroups->map->id->toArray());
    }
}
