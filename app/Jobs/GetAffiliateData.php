<?php

namespace App\Jobs;

use App\Library\Affiliate\SQLOperator;
use App\Library\Affiliate\Stats;
use Fbmaff\Api\Exceptions\ApiException;
use Fbmaff\Auth\Exceptions\MissingUsernameException;
use Fbmaff\Transport\Exceptions\InvalidHostnameException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 *
 */
class GetAffiliateData implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * @var \App\Library\Affiliate\Stats
     */
    public Stats $stats;
    /**
     * @var string
     */
    public string $webmaster;
    /**
     * @var string
     */
    public string $host;
    /**
     * @var string
     */
    public string $startDate;
    /**
     * @var string
     */
    public string $endDate;

    public int $tries = 3;

    public int $backoff = 0;

    public string $adPlatform;

    public $maxExceptions = 1;

    public $timeout = 60;

    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $statDate, string $host, string $webmaster, string $adPlatform)
    {
        $this->adPlatform = $adPlatform;
        $this->startDate = $statDate;
        $this->host = $host;
        try
        {
            $this->stats = new Stats($adPlatform);
        } catch ( ApiException | MissingUsernameException | InvalidHostnameException $e )
        {
            $this->fail($e);
        }
        $this->webmaster = $webmaster;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Fbmaff\Api\Exceptions\ApiException
     */
    public function handle()
    {
        $this->stats->api->setProgram('PPU')->setDates($this->startDate);

        $offset = 0;
        do
        {
            $response = $this->stats->fetch($this->host, $this->webmaster, $offset, 300);

            if (!empty($response))
            {
                $data = $this->stats->filter($response);
                $offset += 300;
                SQLOperator::insertData($this->adPlatform, $data);
            }


        } while ( !empty($response) );
    }
}
