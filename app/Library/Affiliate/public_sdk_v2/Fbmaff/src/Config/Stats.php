<?php

namespace Fbmaff\Config;

/**
 * ProgramApi config
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class Stats extends Base
{
    /**
     * @var array Default mandatory fields
     */
    protected $default_mandatory_request_fields = [
        2 => [],
    ];
    /** @var array Additionally mandatory fields */
    protected $mandatory_request_fields = [
        2 => ['program', ],
    ];
    /**
     * @var array Endpoint params
     */
    protected $endpoint_params = [];
    /**
     * @var array Each child may define various optional fields
     */
    protected $optional_request_fields = [
        2 => ['start_date', 'end_date', 'compare_start_date', 'compare_end_date',
            'limit', 'offset', 'platform', 'group_by', 'webmaster', 'sort_by',],
    ];

    /** @var array Allowed filter keys */
    protected $allowed_filters = [
        'website', 'adgroup', 'campaign', 'source', 'keywords', 'lander', 'country', 'age', 'age_bucket', 'gender',
    ];

    /**
     * Get allowed filters
     *
     * @return array
     */
    public function getAllowedFilters()
    {
        return $this->allowed_filters;
    }
}