<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdPlatformDataDownloaded {

    use Dispatchable, SerializesModels;

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
