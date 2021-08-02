<?php

namespace Fbmaff\Transport;
use Fbmaff\Auth;

/**
 * Curl Transport class
 * 
 * @package   Fbmaff\Core
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class Curl implements ITransport
{
    const CONNECTION_TIMEOUT = 30000;

    /** @var string Hostname of Affiliates platform */
    protected $url;

    /**
     * Constructor
     *
     * @param string $hostname Hostname of Affiliates platform
     *
     * @throws Exceptions\InvalidHostnameException
     */
    public function __construct($hostname)
    {
        if (!is_string($hostname) || empty($hostname))
        {
            throw new Exceptions\InvalidHostnameException();
        }
        $this->url = rtrim($hostname, '/');
    }

    /**
     * Send data (perform POST)
     * 
     * @param array      $data     The JSON encoded data
     * @param string     $endpoint The endpoint to append to the URL
     * @param Auth\IAuth $auth     An Base auth instance
     *
     * @throws Exceptions\CurlErrorException
     * @return array
     */
    public function send(array $data, $endpoint, $auth)
    {
        $url = $this->url . $endpoint;
        if (count($data['endpoint']))
        {
            $url .= '/' . implode('/', $data['endpoint']);
        }

        $data = json_encode($data['regular']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(utf8_decode((string) $data))
            ]
        );
        curl_setopt($ch, /* CURLOPT_TIMEOUT_MS fix for some servers */ 156, self::CONNECTION_TIMEOUT);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        switch ($auth->getAuthType())
        {
            case 'basic':
                curl_setopt($ch, CURLOPT_USERPWD, $auth->getUsername() . ':' . $auth->getPassword());
                break;
            default:
                break;
        }
        $response = curl_exec($ch);

        $error = curl_error($ch);

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!empty($error))
        {
            throw new Exceptions\CurlErrorException($this->url . $endpoint. ' ' . $error);
        }
        return [$http_status, $response];
    }

    /**
     * Fetch data (perform GET)
     *
     * @param array      $data     The JSON encoded data
     * @param string     $endpoint The endpoint to append to the URL
     * @param Auth\IAuth $auth     An Base auth instance
     *
     * @throws Exceptions\CurlErrorException
     * @return array
     */
    public function fetch(array $data, $endpoint, $auth)
    {
        $url = $this->url . $endpoint;
        if (count($data['endpoint']))
        {
            $url .= '/' . implode('/', $data['endpoint']);
        }
        if (count($data['regular']))
        {
            $query = http_build_query($data['regular']);
            $query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $query);
            $url .= '/?' . $query;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: 0'
            ]
        );
        curl_setopt($ch, /* CURLOPT_TIMEOUT_MS fix for some servers */ 156, self::CONNECTION_TIMEOUT);

        switch ($auth->getAuthType())
        {
            case 'basic':
                curl_setopt($ch, CURLOPT_USERPWD, $auth->getUsername() . ':' . $auth->getPassword());
                break;
            default:
                break;
        }
        $response = curl_exec($ch);

        $error = curl_error($ch);

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!empty($error))
        {
            throw new Exceptions\CurlErrorException($this->url . $endpoint. ' ' . $error);
        }
        return [$http_status, $response];
    }
}