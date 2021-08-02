<?php

namespace Fbmaff\Api\Core;

use Fbmaff\Api\Exceptions;
use Fbmaff\Client;
use Fbmaff\Request;
use Fbmaff\Config;

/**
 * Abstract PostApi class
 *
 * @method PostApi setProgram($program)     Sets the program field
 * @method PostApi setDomain($domain)       Sets the domain field
 * @method PostApi setWebmaster($webmaster) Sets the webmaster field
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 *
 */
abstract class PostApi extends BaseApi
{
    /** @var array To fixate data before send */
    protected $sent_data = [];
    /**
     * Get failed events
     *
     * @return array
     */
    public function getFailedEvents()
    {
        $errors = $this->client->getResponse()->getApiErrors();
        if (empty($errors)) {
            return [];
        }
        $return = [];
        foreach ($errors as $key => $error)
        {
            $return[] = [
                'index' => (int)$key,
                'data'  => $this->sent_data[(int)$key],
                'message' => $error,
            ];
        }
        return $return;
    }

    /**
     * Set event data from array
     *
     * @param array $data
     *
     * @return $this
     */
    public function setDataFromArray(array $data)
    {
        $this->request->setDataFromArray($data);
        return $this;
    }

    /**
     * Send data to API
     *
     * @throws Exceptions\ApiException
     *
     * @return void
     */
    public function send()
    {
        if (!$this->isReady())
        {
            throw new Exceptions\ApiException(' Request not ready.');
        }
        $this->sent_data = $this->request->getData();
        $this->client->send($this->request, $this->endpoint);
    }
}    