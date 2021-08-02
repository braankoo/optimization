<?php

namespace App\Library\Ads;

use App\Library\Ads\Google\Detector;

class DetectorFactory {


    /**
     * @param string $adPlatform
     * @param $ad \Google\AdsApi\AdWords\v201809\cm\AdGroupAd|\Microsoft\BingAds\V13\CampaignManagement\Ad|array;
     * @return \App\Library\Ads\DetectorInterface
     */
    public static function make(string $adPlatform, $ad)
    {
        switch ( $adPlatform )
        {
            case 'google':
                return new Detector($ad);
            case 'bing':
                return new \App\Library\Ads\Bing\Detector($ad);
            case 'gemini':
                return new \App\Library\Ads\Gemini\Detector($ad);

        }
    }
}
