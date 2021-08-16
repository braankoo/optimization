<?php

namespace App\Library\Ads\Gemini;

use App\Library\Ads\DetectorInterface;
use Illuminate\Support\Str;
use function GuzzleHttp\Psr7\parse_query;

class Detector implements DetectorInterface {

    /**
     * @var array
     */
    public array $ad;

    /**
     * @param array $ad
     */
    public function __construct(array $ad)
    {
        $this->ad = $ad;

    }

    /**
     * @return array[]|void
     */
    public function getWebmaster()
    {


        $url = parse_url($this->ad['landingUrl']);
        if (array_key_exists('query', $url))
        {
            $query = parse_query($url['query']);

            if (array_key_exists('aff_id', $query))
            {

                return $this->prepareWebmasters($query['aff_id']);
            }


        } else
        {
            dd($this->ad);
        }
    }

    /**
     * @return array|string|string[]
     */
    public function getSite()
    {


        return str_replace('www.', '', strtolower($this->ad['displayUrl']));
    }

    /**
     * @param $webmaster
     * @return array[]
     */
    private function prepareWebmasters($webmaster): array
    {

        if (Str::contains($webmaster, 'ifphone'))
        {
            return [
                [
                    'name' => Str::replace('{ifphone:mob}', '', $webmaster), 'device' => 'DESKTOP'
                ],
                [
                    'name' => Str::replace('{ifphone:mob}', 'mob', $webmaster), 'device' => 'MOBILE'
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

        preg_match('(yahoomob|yahootab|yahoo|yahgem|msnads|bingppc|bingppcmob)', $webmaster, $matches);
        switch ( $matches[0] )
        {
            case 'yahoomob':
                $device = 'MOBILE';
                break;
            case 'yahgem':
            case 'bingppcmob':
                $device = 'MOBILE';
                break;
            case 'yahootab':
                $device = 'TABLET';
                break;
            default:
                $device = 'DESKTOP';
                break;
        }

        return $device;
    }


}
