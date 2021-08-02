<?php

namespace Fbmaff\Auth;

/**
 * Interface for Auth
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
interface IAuth
{
    /**
     * Constructor
     */
    public function __construct($username, $password);
    /**
     * Get username
     *
     * @return string
     */
    public function getUsername();

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword();

    /**
     * Get Authorization type
     *
     * @return string
     */
    public function getAuthType();
}