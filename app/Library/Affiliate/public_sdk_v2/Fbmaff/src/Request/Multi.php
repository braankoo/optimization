<?php

namespace Fbmaff\Request;
use Fbmaff\Api\Exceptions\ApiException;

/**
 * Multi Request class
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class Multi extends Request
{
    /** @var int Index in data */
    protected $index = 0;
    /**
     * Check if request is ready
     *
     * @return bool
     */
    public function isReady()
    {
        foreach ($this->data as $entry)
        {
            foreach ($this->mandatory_fields as $field)
            {
                if (empty($entry[$field]))
                {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Is multi request
     *
     * @return bool
     */
    public function isMulti()
    {
        return true;
    }

    /**
     * Advance to next element
     *
     * @return $this
     */
    public function nextEntry()
    {
        $this->index++;
        return $this;
    }
    /**
     * Magic setter
     *
     * @param string $name Name
     * @param mixed $value Value
     */
    public function __set($name, $value)
    {
        $this->data[$this->index][$name] = $value;
    }
    /**
     * Set data for 1 event from array
     *
     * @throws ApiException
     *
     * @param array $data Event data
     *
     * @return void
     */
    public function setDataFromArray(array $data)
    {
        foreach ($this->mandatory_fields as $field)
        {
            if (empty($data[$field]))
            {
                throw new ApiException('Missing mandatory event field (' . $field . ')');
            }
        }
        foreach ($data as $key => $value)
        {
            $this->data[$this->index][$key] = $value;
        }
        $this->nextEntry();
    }
    /**
     * Set data from multi array (many events in one go). This replaces previously set values.
     *
     * @throws ApiException
     *
     * @param array $data Events data array (multi)
     *
     * @return $this
     */
    public function setDataFromArrayMulti(array $data)
    {
        $this->data = [];
        $this->index = 0;
        foreach ($data as $event)
        {
            if (!is_array($event))
            {
                throw new ApiException('Invalid multi array value');
            }
            $this->setDataFromArray($event);
        }
        return $this;
    }
    /**
     * Get element count in request
     *
     * @return int
     */
    public function getCount()
    {
        return count($this->data);
    }
}