<?php

namespace Fbmaff\Response;

/**
 * Abstract base response class
 * 
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
abstract class Response
{
    /** @var array Http status codes */
    protected $http_status_codes = [
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        409 => 'Conflict',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    ];

    /** @var array Api specific response codes */
    protected $api_response_codes = [
        200 => 'OK',
        201 => 'Resource created',
        202 => 'Data accepted for further processing',
        207 => 'Partial data accepted for further processing',
        400 => 'Validation error',
        401 => 'Unauthorized',
        409 => 'Duplicate request detected',
        500 => 'Server error',
        999 => 'Unknown error',
    ];

    /**
     * @var int $http_status HTTP status
     */
    protected $http_status;
    /**
     * @var string $http_title HTTP title
     */
    protected $http_title;
    /**
     * @var int $api_code API code
     */
    protected $api_code;
    /**
     * @var string $api_description API description
     */
    protected $api_description;
    /**
     * @var array $api_errors API errors
     */
    protected $api_errors = [];
    /** @var array|null Data from API responses */
    protected $data;
    /**
     * Constructor
     *
     * @param int    $http_status  Http status code
     * @param string $raw_response Api response
     *
     * @throws Exceptions\ParseResponseException
     */
    public function __construct($http_status, $raw_response)
    {
        $data = (array)json_decode($raw_response, true);

        if (!array_key_exists('apiDescription', $data) || !array_key_exists('apiCode', $data))
        {
            throw new Exceptions\ParseResponseException();
        }
        $this->setData($data);
    }
    /**
     * Set response properties values
     * 
     * @param array $data Response data
     * 
     * @return void
     */
    public function setData($data)
    {
        $this->http_status     = !empty($data['httpStatus']) ? $data['httpStatus'] : null;
        $this->http_title      = !empty($data['httpTitle']) ? $data['httpTitle'] : null;
        $this->api_code        = !empty($data['apiCode']) ? $data['apiCode'] : null;
        $this->api_description = !empty($data['apiDescription']) ? $data['apiDescription'] : null;
        $this->data            = isset($data['data']) ? $data['data'] : null;
        if (!empty($data['apiErrors']))
        {
            $this->parseErrors($data['apiErrors']);
        }
    }
    /**
     * Return the HTTP status as returned by the API
     * 
     * @return int
     */
    public function getHttpStatus()
    {
        return $this->http_status;
    }
    /**
     * Return the http title as returned by the API
     * 
     * @return string
     */
    public function getHttpTitle()
    {
        return $this->http_title;
    }
    /**
     * Return the API code as returned by the API
     * 
     * @return int
     */
    public function getApiCode()
    {
        return $this->api_code;
    }
    /**
     * Return the API description as returned by the API
     * 
     * @return string
     */
    public function getApiDescription()
    {
        return $this->api_description;
    }
    /**
     * Get API errors
     * 
     * @return array
     */
    public function getApiErrors()
    {
        return $this->api_errors;
    }

    /**
     * Get data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * Is successful request
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return in_array($this->getApiCode(), array_keys($this->api_response_codes)) && $this->getApiCode() < 299;
    }
    /**
     * Parse errors
     *
     * @param array $api_errors Errors
     *
     * @return void
     */
    public function parseErrors($api_errors)
    {
        $this->api_errors = $api_errors;
    }
}