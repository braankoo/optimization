<?php

namespace Fbmaff\Config;

/**
 * CustomPayoutRatesApi config
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class CustomPayoutRates extends Base
{
    /**
     * @var array Default mandatory fields
     */
    protected $default_mandatory_request_fields = [
        2 => [],
    ];
    /** @var array Additionally mandatory fields */
    protected $mandatory_request_fields = [
        2 => ['platform', ],
    ];
    /**
     * @var array Endpoint params
     */
    protected $endpoint_params = ['platform'];
}