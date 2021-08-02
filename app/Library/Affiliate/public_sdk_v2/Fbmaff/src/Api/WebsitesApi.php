<?php

namespace Fbmaff\Api;
use Fbmaff\Client;

/**
 * WebsitesApi class
 *
 * @method WebsitesApi setQuery($query)       Sets search query
 * @method WebsitesApi setPlatform($platform) Sets affiliates platform
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class WebsitesApi extends Core\GetApi
{
    /** @var string Api type */
    protected $api_type = 'Websites';

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
        $this->endpoint = '/v' . (int)$version . '/websites';
    }

    /**
     * Send data to API
     *
     * @throws Exceptions\ApiException
     *
     * @return void
     */
    public function fetchWithDomainAliases()
    {
        if (!$this->isReady())
        {
            throw new Exceptions\ApiException(' Request not ready.');
        }
        $with_aliases = isset($this->request->with_aliases);
        $this->request->with_aliases = 1;
        $this->client->fetch($this->request, $this->endpoint);
        if (!$with_aliases)
        {
            unset($this->request->with_aliases);
        }
    }
}