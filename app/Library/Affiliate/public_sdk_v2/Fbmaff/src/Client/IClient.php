<?php

namespace Fbmaff\Client;
use Fbmaff\Transport;
use Fbmaff\Auth;
use Fbmaff\Response;
use Fbmaff\Request;

/**
 * Interface for clients
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
interface IClient
{
    /**
     * Set transport
     *
     * @param Transport\ITransport $transport Transporter
     */
    public function setTransport(Transport\ITransport $transport);
    /**
     * Returns the response
     *
     * @return Response\Response
     */
    public function getResponse();
    /**
     * Instructs the 'transporter' to send the data
     *
     * @param Request\Request $request  Request
     * @param string          $endpoint Target URL
     */
    public function send(Request\Request $request, $endpoint);
    /**
     * Instructs the 'transporter' to fetch the data
     *
     * @param Request\Request $request  Request
     * @param string          $endpoint Target URL
     *
     */
    public function fetch(Request\Request $request, $endpoint);
    /**
     * Set auth
     *
     * @param Auth\IAuth $auth Auth
     */
    public function setAuth(Auth\IAuth $auth);
}