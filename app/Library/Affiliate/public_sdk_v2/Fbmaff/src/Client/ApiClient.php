<?php

namespace Fbmaff\Client;
use Fbmaff\Transport;
use Fbmaff\Auth;
use Fbmaff\Response;
use Fbmaff\Request;

/**
 * API client class for SDK
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class ApiClient implements IClient
{
    /**
     * @var Transport\ITransport $transport Transport
     */
    public $transport = null;
    /**
     * @var Response\Response $response Response
     */
    public $response = null;
    /**
     * @var Auth\IAuth $auth Auth
     */
    public $auth;
    
    /**
     * Constructor
     * 
     * @param Transport\ITransport $transport Transport
     * @param Auth\IAuth           $auth      Auth
     */
    public function __construct(Transport\ITransport $transport, Auth\IAuth $auth)
    {
        $this->setTransport($transport);
        $this->setAuth($auth);
    }
    /**
     * Send the request
     * 
     * @param Request\Request $request Request
     * @param string $endpoint The URL endpoint
     * 
     * @return void
     */
    public function send(Request\Request $request, $endpoint)
    {
        list($http_status, $raw_response) = $this->transport->send($request->prepareData(), $endpoint, $this->auth);
        if ($request->isMulti())
        {
            $this->response = new Response\Multi($http_status, $raw_response);
        }
        else
        {
            $this->response = new Response\Single($http_status, $raw_response);
        }
    }
    /**
     * Fetch the request
     *
     * @param Request\Request $request Request
     * @param string $endpoint The URL endpoint
     *
     * @return void
     */
    public function fetch(Request\Request $request, $endpoint)
    {
        list($http_status, $raw_response) = $this->transport->fetch($request->prepareData(), $endpoint, $this->auth);
        $this->response = new Response\Single($http_status, $raw_response);
    }
    /**
     * Was the request successful
     * 
     * @return Response\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
    /**
     * Set auth
     *
     * @param Auth\IAuth $auth Auth
     */
    public function setAuth(Auth\IAuth $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Set transport
     *
     * @param Transport\ITransport $transport Transporter
     */
    public function setTransport(Transport\ITransport $transport)
    {
        $this->transport = $transport;
    }
}