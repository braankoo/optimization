<?php

namespace App\Jobs;

use App\Library\Reports\SedItFactory;
use App\Library\Reports\SqlOperatorFactory;
use App\Models\Client;
use Carbon\Carbon;
use FirstBeatMedia\AdWordManagement\Facades\AdWordManagement;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GetReport implements ShouldQueue {


    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * @var int
     */
    public int $tries = 1;

    /**
     * @var int
     */
    public int $backoff = 0;
    /**
     * @var string
     */
    public string $startDate;
    /**
     * @var \App\Models\Client
     */
    public Client $client;
    /**
     * @var string
     */
    public string $endDate;

    /**
     * @var int
     */
    public int $timeout = 360;

    /**
     * @var bool
     */
    public bool $failOnTimeout = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Client $client, string $startDate, string $endDate)
    {
        $this->client = $client;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {

        $report = AdWordManagement::report($this->client)->get($this->startDate, $this->endDate);
        if ($report)
        {
            $preparedCVS = SedItFactory::make($this->client->getConnectionName())->prepareForSql($report);
            SqlOperatorFactory::make($this->client->getConnectionName())->loadToTemporaryTable($preparedCVS);
            Storage::delete($preparedCVS);
            Storage::delete($report);
        }

    }
}
