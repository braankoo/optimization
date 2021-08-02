<?php

namespace Fbmaff\Api;
use Fbmaff\Client;

/**
 * CustomPayoutRatesApi class
 *
 * @method WebsitesApi setPlatform($platform) Sets affiliates platform
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class CustomPayoutRatesApi extends Core\GetApi
{
    /** @var string Api type */
    protected $api_type = 'CustomPayoutRates';

    /**
     * Constructor
     *
     * @param Client\IClient $client  Client
     * @param int            $version Api version
     *
     * @throws Exceptions\ApiException
     */
    public function __construct($client, $version)
    {
        parent::__construct($client, $version);
        $this->endpoint = '/v' . (int)$version . '/custom_payout_rates';
    }
}