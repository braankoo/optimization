<?php

namespace Fbmaff\Api\Core;

use Fbmaff\Client;
use Fbmaff\Response;
use Fbmaff\Request;

/**
 * Single Api class
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
abstract class SingleApi extends PostApi
{
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
        $this->request = new Request\Single($this->config);
    }
}