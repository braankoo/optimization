<?php

namespace App\Library\Ads\Google;

use App\Library\Ads\DetectorInterface;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAd;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;
use function GuzzleHttp\Psr7\parse_query;


class Detector implements DetectorInterface {


    /**
     * @var \Google\AdsApi\AdWords\v201809\cm\AdGroupAd
     */
    public $ad;


    /**
     * @param \Google\AdsApi\AdWords\v201809\cm\AdGroupAd $ad
     */
    public function __construct(AdGroupAd $ad)
    {
        $this->ad = $ad;
    }


    /**
     * @return array
     */
    public function getWebmaster(): array
    {

        $finalUrl = parse_url($this->ad->getAd()->getFinalUrls()[0]);
        if (array_key_exists('query', $finalUrl))
        {
            $query = parse_query($finalUrl['query']);

            return $this->prepareWebmasters($query['aff_id']);
        }

        if (filter_var($this->ad->getAd()->getTrackingUrlTemplate(), FILTER_VALIDATE_URL))
        {
            $url = parse_url($this->ad->getAd()->getTrackingUrlTemplate());

            if (array_key_exists('query', $url))
            {
                $query = parse_query($url['query']);

                return $this->prepareWebmasters($query['aff_id']);
            }
        }

        if (!is_null($this->ad->getAd()->getUrlCustomParameters()))
        {
            $params = (array) $this->ad->getAd()->getUrlCustomParameters()->getParameters();
            dd($params);
            $webmaster = array_filter($params, function ($param) {
                return $param['key'] == 'id';
            });

            return $this->prepareWebmasters(array_values($webmaster[0]));
        }

    }


    /**
     * @return string
     */
    public function getSite(): string
    {

        $url = parse_url($this->ad->getAd()->getFinalUrls()[0]);

        return str_replace('www.', '', strtolower($url['host']));
    }

    /**
     * @param $webmaster
     * @return string
     */
    private function detectDevice($webmaster): string
    {
        preg_match('(googlemob|googletab|google)', $webmaster, $matches);
        switch ( $matches[0] )
        {
            case 'googlemob':
                $device = 'MOBILE';
                break;
            case 'googletab':
                $device = 'TABLET';
                break;
            default:
                $device = 'DESKTOP';
                break;
        }

        return $device;
    }

    /**
     * @param $webmaster
     * @return array[]
     */
    private function prepareWebmasters($webmaster): array
    {
        if (Str::contains($webmaster, 'ifmobile'))
        {
            return [
                [
                    'name' => Str::replace('{ifmobile:mob}', '', $webmaster), 'device' => $this->detectDevice($webmaster)
                ],
                [
                    'name' => Str::replace('{ifmobile:mob}', 'mob', $webmaster), 'device' => 'mobile'
                ]
            ];
        } else
        {
            return [

                [
                    'name' => $webmaster, 'device' => $this->detectDevice($webmaster)
                ]
            ];

        }
    }

}
