<?php

namespace Fbmaff\Api\Exceptions;

/**
 * Api exceptin
 *
 * PHP version 5.4
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2014 FirstBeatMedia All rights reserved.
 */
class ApiException extends \Exception
{
    /**
     * @var string The exception message
     */
    protected $message = 'Api exception!';
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