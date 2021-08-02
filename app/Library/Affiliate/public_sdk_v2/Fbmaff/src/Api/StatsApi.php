<?php

namespace Fbmaff\Api;
use Fbmaff\Api\Exceptions\ApiException;
use Fbmaff\Client;

/**
 * StatsApi class
 *
 * @method StatsApi setProgram($program)             Set the program
 * @method StatsApi setWebmaster($webmaster)         Set webmaster
 * @method StatsApi setGroupBy(array $group)         Set group by
 * @method StatsApi setSortBy(array $sort)             Set sort by
 * @method StatsApi setOffset($offset)               Set offset
 * @method StatsApi setLimit($limit)                 Set limit
 * @method StatsApi setPlatform($platform)           Set platform
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class StatsApi extends Core\PostApi
{
    /** @var string Api type */
    protected $api_type = 'Stats';
    /** @var array Filters storage */
    protected $filters = [];

    /**
     * Constructor
     *
     * @param Client\IClient $client  Client
     * @param int            $version Api version
     *
     * @throws Exceptions\ApiException
     */
    public function __construct($client, $version)
    {
        parent::__construct($client, $version);
        $this->endpoint = '/v' . $version . '/stats';
    }

    /**
     * Fetch stats
     *
     * @throws ApiException
     * @return void
     */
    public function fetch()
    {
        if (!$this->isReady())
        {
            throw new Exceptions\ApiException(' Request not ready.');
        }
        $this->request->filters = $this->filters;
        $this->client->send($this->request, $this->endpoint);
    }

    /**
     * Fetch stats with decorated columns
     *
     * @throws ApiException
     * @return void
     */
    public function fetchDecorated()
    {
        if (!$this->isReady())
        {
            throw new Exceptions\ApiException(' Request not ready.');
        }
        $this->request->filters = $this->filters;
        $this->client->send($this->request, $this->endpoint . '/decorated');
    }

    /**
     * Fetch stats with paging info and totals
     *
     * @throws ApiException
     * @return void
     */
    public function fetchFull()
    {
        if (!$this->isReady())
        {
            throw new Exceptions\ApiException(' Request not ready.');
        }
        $this->request->filters = $this->filters;
        $this->client->send($this->request, $this->endpoint . '/full');
    }
    /**
     * Fetch stats data with compared periods
     *
     * @throws ApiException
     * @return void
     */
    public function fetchWithCompare()
    {
        if (!$this->isReady())
        {
            throw new Exceptions\ApiException(' Request not ready.');
        }
        $this->request->filters = $this->filters;
        $this->client->send($this->request, $this->endpoint . '/compared');
    }
    /**
     * Helper to set start and end dates
     *
     * @param string      $start_date Start date (yyyy-mm-dd)
     * @param null|string $end_date Period end date (yyyy-mm-dd)
     *
     * @return $this
     */
    public function setDates($start_date, $end_date = null)
    {
        $this->setStartDate($start_date);
        if ($end_date)
        {
            $this->setEndDate($end_date);
        }
        return $this;
    }
    /**
     * Helper to set start and end dates for compare period
     *
     * @param string      $start_date Start date (yyyy-mm-dd)
     * @param null|string $end_date Period end date (yyyy-mm-dd)
     *
     * @return $this
     */
    public function setCompareDates($start_date, $end_date = null)
    {
        $this->setCompareStartDate($start_date);
        if ($end_date)
        {
            $this->setCompareEndDate($end_date);
        }
        return $this;
    }

    /**
     * Set website
     *
     * @param string       $filter_key   Filter key
     * @param string|array $filter_value Filter value
     *
     * @return $this
     */
    protected function setFilter($filter_key, $filter_value)
    {
        $this->filters[$filter_key] = $filter_value;
        return $this;
    }

    /**
     * Set filters from array
     *
     * @param array $filters Array of filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        foreach ($filters as $key => $values)
        {
            if (in_array($key, $this->config->getAllowedFilters()))
            {
                $this->setFilter($key, $values);
            }
        }
        return $this;
    }

    /**
     * Magic call
     *
     * @param string $name Name
     * @param array $args
     *
     * @return $this
     *
     * @throws Exceptions\InvalidFieldException
     * @throws Exceptions\InvalidMethodException
     * @throws Exceptions\InvalidFieldValueException
     */
    public function __call($name, $args)
    {
        // If called for another non-existing method
        if (strpos($name, self::MAGIC_SETTER_PREFIX) === false)
        {
            throw new Exceptions\InvalidMethodException($name);
        }
        if (empty($args))
        {
            return;
        }

        $field_name = substr($name, strlen(self::MAGIC_SETTER_PREFIX));

        // Split by capital letters
        $split = preg_split('/(?<=[a-z])(?![a-z2])/', $field_name, -1, PREG_SPLIT_NO_EMPTY);

        $field_name = strtolower(join('_', $split));

        if (in_array($field_name, $this->config->getAllowedFilters()))
        {
            $this->setFilter($field_name, $args[0]);
            return $this;
        }
        return parent::__call($name, $args);
    }
}