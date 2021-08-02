<?php

namespace Fbmaff\Api\Exceptions;

/**
 * Invalid field value exception class
 *
 * PHP version 5.4
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2014 FirstBeatMedia All rights reserved.
 */
class InvalidFieldValueException extends \Exception
{
    /**
     * @var string The exception message 
     */
    protected $message = 'Invalid field value for field: ';
    /**
     * Constructor
     * 
     * @param string     $field_name Field name
     * @param int        $code       Exception code
     * @param \Exception $previous   Previous
     */
    public function __construct($field_name, $code = 0, $previous = null)
    {
        parent::__construct($this->message . $field_name, $code, $previous);
    }
    
}