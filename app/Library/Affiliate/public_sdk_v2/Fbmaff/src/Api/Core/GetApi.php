<?php

namespace Fbmaff\Api\Core;

use Fbmaff\Api\Exceptions;
use Fbmaff\Client;
use Fbmaff\Response;
use Fbmaff\Request;

/**
 * Get Api class
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
abstract class GetApi extends BaseApi
{
    /**
     * Send data to API
     *
     * @throws Exceptions\ApiException
     *
     * @return void
     */
    public function fetch()
    {
        if (!$this->isReady())
        {
            throw new Exceptions\ApiException(' Request not ready.');
        }
        $this->client->fetch($this->request, $this->endpoint);
    }
}
