<?php

namespace Fbmaff\Transport\Exceptions;

/**
 * Invalid hostname exception class
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class InvalidHostnameException extends \Exception
{
    /**
     * @var string The exception message 
     */
    protected $message = 'Invalid hostname!';
    /**
     * Constructor
     *
     * @param string     $message  Exception message
     * @param int        $code     Exception code
     * @param \Exception $previous Previous
     */
    public function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct($this->message . $message, $code, $previous);
    }
}