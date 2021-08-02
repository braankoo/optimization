<?php

namespace Fbmaff\Api\Core;

use Fbmaff\Api\Exceptions;
use Fbmaff\Client;
use Fbmaff\Request;
use Fbmaff\Config;

/**
 * Abstract BaseApi class
 * 
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 * 
 */
abstract class BaseApi
{
    /** Constant for magic methods call */
    const MAGIC_SETTER_PREFIX = 'set';
    /**
     * @var Client\IClient $client Client
     */
    protected $client;
    /**
     * @var int $version Api version
     */
    protected $version;
    /**
     * @var string $endpoint The endpoint for the API version
     */
    protected $endpoint = '';

    /** @var Request\Request $request Request */
    protected $request;
    /**
     * @var array Mandatory fields for the API version
     */
    protected $mandatory_fields = [];
    /**
     * @var array Optional fields for the API version
     */
    protected $optional_fields = [];

    /** @var string Api type */
    protected $api_type = '';
    /** @var Config\Base Config */
    protected $config;
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
        if ($this->api_type == '')
        {
            throw new Exceptions\ApiException('Api type not specified.');
        }
        $this->client = $client;
        $this->version = $version;

        $this->config = Config\Factory::get($this->api_type, $version);

        $this->mandatory_fields = $this->config->getMandatoryRequestFields();
        $this->optional_fields = $this->config->getOptionalRequestFields();

        $this->request = new Request\Single($this->config);
    }
    /**
     * Get the version
     * 
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
    /**
     * Was the request successful or not
     * 
     * @return bool
     */
    public function success()
    {
        return $this->client->getResponse()->isSuccessful();
    }

    /**
     * Get response status message
     *
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->client->getResponse()->getHttpTitle();
    }

    /**
     * Get response status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->client->getResponse()->getHttpStatus();
    }

    /**
     * Get response errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->client->getResponse()->getApiErrors();
    }

    /**
     * Get response data
     *
     * @return array|null
     */
    public function getData()
    {
        return $this->client->getResponse()->getData();
    }
    /**
     * Get Api message
     *
     * @return string
     */
    public function getApiMessage()
    {
        return $this->client->getResponse()->getApiDescription();
    }

    /**
     * Get Api code
     *
     * @return int
     */
    public function getApiCode()
    {
        return $this->client->getResponse()->getApiCode();
    }
    /**
     * Check if Api request ready
     *
     * @return bool
     */
    public function isReady()
    {
        return $this->request->isReady();
    }

    /**
     * Get request data
     *
     * @return array
     */
    public function getRequestData()
    {
        return $this->request->getData();
    }
    /**
     * Magic call
     *
     * @param string $name Name
     * @param array $args
     *
     * @return $this
     *
     * @throws Exceptions\InvalidFieldException
     * @throws Exceptions\InvalidMethodException
     */
    public function __call($name, $args)
    {
        // If called for another non-existing method
        if (strpos($name, self::MAGIC_SETTER_PREFIX) === false)
        {
            throw new Exceptions\InvalidMethodException($name);
        }
        if (empty($args))
        {
            return;
        }
        
        $field_name = substr($name, strlen(self::MAGIC_SETTER_PREFIX));
        
        // Split by capital letters
        $split = preg_split('/(?<=[a-z])(?![a-z2])/', $field_name, -1, PREG_SPLIT_NO_EMPTY);
        
        $field_name = strtolower(join('_', $split));

        if (!$this->isValidField($field_name))
        {
            throw new Exceptions\InvalidFieldException($field_name);
        }
        if (!$this->isValidFieldValue($field_name, $args[0]))
        {
            throw new Exceptions\InvalidFieldValueException($field_name);
        }
        $this->request->$field_name = $args[0];
        return $this;
    }

    /**
     * Check if field value is valid
     *
     * @param string $field_name  Field name
     * @param mixed  $field_value Field value
     *
     * @return bool
     */
    public function isValidFieldValue($field_name, $field_value)
    {
        // by default we do not validate. If any of APIs need to validate, they should add rule on their own
        return true;
    }
    /**
     * Check if a field name is valid
     * 
     * @param string $field Field name to be checked
     * @return bool
     */
    public function isValidField($field)
    {
        return in_array($field, $this->mandatory_fields) || in_array($field, $this->optional_fields);
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
        $this->client->send($this->request, $this->endpoint);
    }
}    