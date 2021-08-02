<?php

namespace App\Library\Ads\Bing;

use App\Library\Ads\DetectorInterface;
use Illuminate\Support\Str;
use function GuzzleHttp\Psr7\parse_query;

class Detector implements DetectorInterface {

    public object $ad;

    public function __construct(object $ad)
    {
        $this->ad = $ad;
    }

    public function getWebmaster()
    {


        if (!is_null($this->ad->TrackingUrlTemplate))
        {
            dd($this->ad->TrackingUrlTemplate);
        }

        $finalUrl = parse_url($this->ad->FinalUrls->string[0]);

        if (array_key_exists('query', $finalUrl))
        {
            $query = parse_query($finalUrl['query']);

            if (array_key_exists('aff_id', $query))
            {
                return $this->prepareWebmasters($query['aff_id']);
            } else
            {
                return [];
            }


        } else
        {
            dd($this->ad);
        }

        return [];


    }

    public function getSite()
    {
        return str_replace('www.', '', $this->ad->Domain);
    }

    /**
     * @param $webmaster
     * @return array[]
     */
    private function prepareWebmasters($webmaster): array
    {

        echo PHP_EOL;

        if (Str::contains($webmaster, 'IfMobile'))
        {
            return [
                [
                    'name' => Str::replace('{IfMobile:mob}', '', $webmaster), 'device' => 'DESKTOP'
                ],
                [
                    'name' => Str::replace('{IfMobile:mob}', 'mob', $webmaster), 'device' => 'MOBILE'
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

    /**
     * @param $webmaster
     * @return string
     */
    private function detectDevice($webmaster): string
    {

        echo PHP_EOL;
        preg_match('(bingmob|bingtab|bing|msnadsmob|msnadstab|mnsads|JacksonM)', $webmaster, $matches);
        switch ( $matches[0] )
        {
            case 'msnadsmob':
            case 'bingmob':
                $device = 'MOBILE';
                break;
            case 'bingtab':
            case 'msnadstab':
                $device = 'TABLET';
                break;
            default:
                $device = 'DESKTOP';
                break;
        }

        return $device;
    }
}
