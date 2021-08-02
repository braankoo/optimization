<?php

namespace Fbmaff\Request;

/**
 * Single Request class
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class Single extends Request
{
    /**
     * Whether or not is the request ready for sending out
     * 
     * @return bool
     */
    public function isReady()
    {
        foreach ($this->mandatory_fields as $field)
        {
            if (empty($this->data[$field]))
            {
                return false;
            }
        }
        return true;
    }
    /**
     * Set data for 1 event from array
     *
     * @param array $data Event data
     *
     * @return void
     */
    public function setDataFromArray(array $data)
    {
        foreach ($data as $key => $value)
        {
            $this->data[$key] = $value;
        }
    }
    /**
     * Is multi request
     *
     * @return bool
     */
    public function isMulti()
    {
        return false;
    }
}