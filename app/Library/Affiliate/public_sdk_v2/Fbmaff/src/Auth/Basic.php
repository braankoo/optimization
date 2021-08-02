<?php

namespace Fbmaff\Auth;

/**
 * Basic authentication class for Fbmaff SDK
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class Basic implements IAuth
{
    /** @var string Password */
    protected $password;
    /** @var string Username */
    protected $username;
    /** @var string Auth type */
    private $_auth_type = 'basic';
    /**
     * Constructor
     *
     * @param string $username Username to use for authentication
     * @param string $password Password to authenticate
     *
     * @throws Exceptions\MissingUsernameException
     */
    public function __construct($username, $password = '')
    {
        if (empty($username))
        {
            throw new Exceptions\MissingUsernameException;
        }

        $this->username = $username;
        $this->password = $password;
    }
    /**
     * Get the auth username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Get the auth password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get Auth type
     *
     * @return string
     */
    public function getAuthType()
    {
        return $this->_auth_type;
    }
}