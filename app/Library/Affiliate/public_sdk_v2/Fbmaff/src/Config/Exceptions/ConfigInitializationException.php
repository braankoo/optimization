<?php

namespace Fbmaff\Config\Exceptions;

/**
 * Config initialization exception
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class ConfigInitializationException extends \Exception
{
    /**
     * @var string The exception message
     */
    protected $message = 'Cant load config file!';
    /**
     * Constructor
     *
     * @param string     $message  Exception message
     * @param int        $code     Exception code
     * @param \Exception $previous Previous
     *
     */
    public function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct($this->message . $message, $code, $previous);
    }
}