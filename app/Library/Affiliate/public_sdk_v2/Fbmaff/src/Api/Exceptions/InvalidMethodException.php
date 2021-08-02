<?php

namespace Fbmaff\Api\Exceptions;

/**
 * Invalid method exception class
 *
 * PHP version 5.4
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2014 FirstBeatMedia All rights reserved.
 */
class InvalidMethodException extends \Exception
{
    /**
     * @var string The exception message 
     */
    protected $message = 'Trying to call invalid method: ';
    /**
     * Constructor
     * 
     * @param string     $method_name The invalid method name
     * @param int        $code        Exception code
     * @param \Exception $previous    Previous
     */
    public function __construct($method_name, $code = 0, $previous = null)
    {
        parent::__construct($this->message . $method_name, $code, $previous);
    }
    
}