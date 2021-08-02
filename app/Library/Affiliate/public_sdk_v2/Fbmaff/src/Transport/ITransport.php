<?php

namespace Fbmaff\Transport;
use Fbmaff\Auth;

/**
 * Interface for transport
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
interface ITransport
{
    /**
     * Constructor
     *
     * @param string $hostname Hostname of affiliates platform
     */
    public function __construct($hostname);
    /**
     * Send data (do POST)
     *
     * @param array      $data     Data
     * @param string     $endpoint Api endpoint
     * @param Auth\IAuth $auth     Auth
     *
     * @return array
     */
    public function send(array $data, $endpoint, $auth);
    /**
     * Fetch data (do GET)
     *
     * @param array      $data     Data
     * @param string     $endpoint Api endpoint
     * @param Auth\IAuth $auth     Auth
     *
     * @return array
     */
    public function fetch(array $data, $endpoint, $auth);
}