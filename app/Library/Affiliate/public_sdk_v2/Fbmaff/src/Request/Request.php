<?php

namespace Fbmaff\Request;
use Fbmaff\Config;

/**
 * Abstract request class
 * 
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
abstract class Request
{
    /**
     * @var array A list of the mandatory fields
     */
    protected $mandatory_fields = [];
    /** @var array Optional fields */
    protected $optional_fields = [];
    /** @var array Endpoint params */
    protected $endpoint_params = [];
    /**
     * @var array Request data
     */
    protected $data = [];

    /**
     * Constructor
     *
     * @param Config\Base $config Config
     */
    public function __construct(Config\Base $config)
    {
        $this->mandatory_fields = $config->getMandatoryRequestFields();
        $this->optional_fields = $config->getOptionalRequestFields();
        $this->endpoint_params = $config->getEndpointParams();
    }

    /**
     * Magic setter
     *
     * @param string $name Name
     * @param mixed $value Value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Magic unset
     *
     * @param string $name param name
     *
     * @return void
     */
    public function __unset($name)
    {
        if (isset($this->data[$name]))
        {
            unset($this->data[$name]);
        }
    }
    /**
     * Prepare the data for sending
     * 
     * @return array
     */
    public function prepareData()
    {
        $temp = ['endpoint' => [], 'regular' => []];
        foreach ($this->endpoint_params as $param)
        {
            if (array_key_exists($param, $this->data))
            {
                $temp['endpoint'][] = $this->data[$param];
            }
        }
        foreach ($this->data as $key => $value)
        {
            if (!in_array($key, $this->endpoint_params))
            {
                $temp['regular'][$key] = $value;
            }
        }
        return $temp;
    }

    /**
     * Get request data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Check if request is ready
     *
     * @return bool
     */
    abstract public function isReady();

    /**
     * Is multi request or not
     *
     * @return bool
     */
    abstract public function isMulti();
}