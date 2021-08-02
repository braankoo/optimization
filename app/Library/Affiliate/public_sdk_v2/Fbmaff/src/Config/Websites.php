<?php

namespace Fbmaff\Config;

/**
 * WebsitesApi config
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class Websites extends Base
{
    /**
     * @var array Default mandatory fields
     */
    protected $default_mandatory_request_fields = [
        2 => [],
    ];

    /**
     * @var array Endpoint params
     */
    protected $endpoint_params = ['platform'];

    /**
     * @var array $optional_request_fields Each child may define various optional fields
     */
    protected $optional_request_fields = [
        2 => ['platform', 'query', 'with_aliases'],
    ];


}