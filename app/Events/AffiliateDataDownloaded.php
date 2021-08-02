<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AffiliateDataDownloaded {

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $adPlatform;
    public string $startDate;
    public string $endDate;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $adPlatform, string $startDate, string $endDate)
    {
        $this->adPlatform = $adPlatform;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


}
