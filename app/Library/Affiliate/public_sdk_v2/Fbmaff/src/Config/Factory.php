<?php

namespace Fbmaff\Config;

/**
 * Config factory
 *
 * @package   Fbmaff
 * @author    Affiliates Team <aff@firstbeatmedia.com>
 * @copyright 2016 FirstBeatMedia All rights reserved.
 */
class Factory
{
    /**
     * Get config class
     *
     * @param string $api_type Api type
     * @param int    $version  Api version
     *
     * @throws Exceptions\ConfigInitializationException
     *
     * @return Base
     */
    static public function get($api_type, $version)
    {
        $class = '\\Fbmaff\\Config\\' . $api_type;
        if (class_exists($class))
        {
            return new $class($version);
        }
        throw new Exceptions\ConfigInitializationException(' Config class not found.');
    }
}