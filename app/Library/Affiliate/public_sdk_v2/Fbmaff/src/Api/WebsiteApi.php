<?php

namespace Fbmaff\Api;
use Fbmaff\Client;

/**
 * WebsiteApi class
 *
 * @method WebsiteApi setDomain($domain) Sets website
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class WebsiteApi extends Core\GetApi
{
    /** @var string Api type */
    protected $api_type = 'Website';

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
        $this->endpoint = '/v' . $version . '/website';
    }
}