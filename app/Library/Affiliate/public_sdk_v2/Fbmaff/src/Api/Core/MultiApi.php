<?php

namespace Fbmaff\Api\Core;

use Fbmaff\Api\Exceptions;
use Fbmaff\Client;
use Fbmaff\Request;

/**
 * Multi API abstract
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
abstract class MultiApi extends PostApi
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
        $this->request = new Request\Multi($this->config);
    }

    /**
     * Advance to next entry
     *
     * @return $this
     */
    public function nextEntry()
    {
        $this->request->nextEntry();
        return $this;
    }

    /**
     * Get event count
     *
     * @return int
     */
    public function getEventCount()
    {
        return $this->request->getCount();
    }
    /**
     * Set multiple events from array
     *
     * @param array $data
     *
     * @return $this
     */
    public function setDataFromArrayMulti(array $data)
    {
        $this->request->setDataFromArrayMulti($data);
        return $this;
    }
}