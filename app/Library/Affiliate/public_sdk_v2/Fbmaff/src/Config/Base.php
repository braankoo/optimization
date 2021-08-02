<?php

namespace Fbmaff\Config;

/**
 * Abstract config Base class
 * 
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
abstract class Base
{
    /**
     * @var array Default settings
     */
    protected $default_mandatory_request_fields = [
        2 => ['webmaster', 'program', 'domain', ],
    ];
    /**
     * @var array Mandatory fields
     */
    protected $mandatory_request_fields = [];
    /**
     * @var array Each child may define various optional fields
     */
    protected $optional_request_fields = [];
    /**
     * @var array Endpoint params
     */
    protected $endpoint_params = [];
    /** @var int Version */
    protected $version;

    /**
     * Constructor
     *
     * @param int $version Version
     *
     * @throws Exceptions\ConfigInitializationException
     */
    public function __construct($version)
    {
        if (!$this->isValidVersion($version))
        {
            throw new Exceptions\ConfigInitializationException('Cant load config for this Api version');
        }
        $this->version = $version;
    }
    
    /**
     * Get the mandatory fields for each request according to the provided API version
     * 
     * @param int $api_version Api version
     * @return array
     */
    public function getMandatoryRequestFields($api_version = null)
    {
        $version = ($api_version === null) ? $this->version : $api_version;
        if (!empty($this->mandatory_request_fields[$version]))
        {
            return array_merge(
                $this->default_mandatory_request_fields[$version],
                $this->mandatory_request_fields[$version]);
        }
        return $this->default_mandatory_request_fields[$version];
    }

    /**
     * Get endpoint params
     *
     * @return array
     */
    public function getEndpointParams()
    {
        return $this->endpoint_params;
    }
    /**
     * Get the optional fields for each request according to the provided API version
     * 
     * @param int $api_version Api version
     * @return array
     */
    public function getOptionalRequestFields($api_version = null)
    {
        $version = ($api_version === null) ? $this->version : $api_version;
        if (!empty($this->optional_request_fields[$version]))
        {
            return $this->optional_request_fields[$version];
        }
        return [];
    }
    /**
     * Determine if $version is a valid one
     * 
     * @param int $version Version
     * @return bool
     */
    public function isValidVersion($version)
    {
        return in_array($version, array_keys($this->default_mandatory_request_fields));
    }
    /**
     * Get the current version
     * 
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
}