<?php

namespace App\Library\Affiliate;


use App\Models\Ad;
use App\Models\AdGroup;
use Fbmaff\Api\Exceptions\ApiException;
use Fbmaff\Client\ApiClient;
use Fbmaff\Api;

use Fbmaff\Transport;
use Fbmaff\Auth;

class Stats {

    /**
     * @var \Fbmaff\Client\ApiClient
     */
    public ApiClient $client;
    /**
     * @var \Fbmaff\Api\StatsApi
     */
    public Api\StatsApi $api;
    /**
     * @var string
     */
    public string $regexp;
    /**
     * @var string
     */
    public string $adPlatform;

    /**
     * @throws \Fbmaff\Auth\Exceptions\MissingUsernameException
     * @throws \Fbmaff\Transport\Exceptions\InvalidHostnameException
     * @throws \Fbmaff\Api\Exceptions\ApiException
     */
    public function __construct(string $adPlatform)
    {
        $this->client = new ApiClient(new Transport\Curl(config('affiliate.hostname')), new Auth\Basic(config('affiliate.username'), config('affiliate.api_key')));
        $this->api = new Api\StatsApi($this->client, 2);
        $this->api->setProgram('PPU');
        $this->adPlatform = $adPlatform;
        $this->setRegexp($adPlatform);
    }

    /**
     * @param string $host Host name
     * @param string $webmaster Webmaster Name
     * @param int $offset Offset
     * @param int $limit
     * @return array|null
     * @throws \Fbmaff\Api\Exceptions\ApiException
     * @throws \Exception
     */
    public function fetch(string $host, string $webmaster, int $offset, int $limit): ?array
    {
        $this->api
            ->setPlatform($host)
            ->setWebmaster($webmaster)
            ->setGroupBy(
                [
                    'adgroup',
                    'campaign',
                    'date'
                ]
            )
            ->setOffset($offset)
            ->setLimit($limit)
            ->setSortBy([ 'adgroup' => 'DESC', 'date' => 'DESC' ])
            ->fetch();
        $data = $this->api->getData();

        if (is_null($data))
        {
            return [];
        }

        if (array_key_exists('total_records', $data))
        {
            return [];
        }

        return $data;
    }

    /**
     * @param array $stats
     * @return array
     */
    public function filter(array $stats): array
    {

        $data = array_map(function ($row) {
            if (!array_key_exists('profile', $row))
            {
                $row['profile'] = 0;
            }

            return $row;
        }, $stats);

        $data = array_filter($data, function ($row) {
            return (
                    $row['profile'] > 0
                    || $row['earned'] > 0
                    || $row['upgrade'] > 0
                )
                && (
                    preg_match("/(?<=$this->regexp:).\d+/", $row['adgroup'])
                    || (
                        $this->adPlatform =='google' && preg_match('/(Exact|Broad|Phrase) - Search/', $row['adgroup'])
                    )
                );
        });

        $data = array_map(function ($row) {

            if (preg_match("/(?<=$this->regexp:).\d+/", $row['adgroup'], $adGroup))
            {
                $adGroup = $this->findAdGroupId($adGroup[0]);
            } else
            {
                $adGroup = $this->findAdGroupByName($row['adgroup']);
            }

            return [
                'created_at'  => $row['date'],
                'profile'     => $row['profile'],
                'upgrade'     => $row['upgrade'],
                'earned'      => (int) $row['earned'] * 1000000,
                'ad_group_id' => $this->findAdGroupId($adGroup)
            ];
        }, $data);


        return array_values($data);
    }


    /**
     * @param string $adPlatform
     */
    private function setRegexp(string $adPlatform): void
    {
        switch ( $adPlatform )
        {
            case 'bing':
            case 'google':
                $this->regexp = 'aid';
                break;
            default:
                $this->regexp = 'adid';
                break;
        }
    }

    /**
     * @param int $adGroupId
     * @return int
     */
    private function findAdGroupId(int $adGroupId): int
    {
        switch ( $this->adPlatform )
        {
            case 'gemini':
                $ad = Ad::on('gemini')->where('id', '=', $adGroupId)->first();

                if (!is_null($ad))
                {
                    return $ad->ad_group_id;
                }

                return $adGroupId;
            default:
                return $adGroupId;
        }
    }

    /**
     * @param string $name
     * @return int
     */
    private function findAdGroupByName(string $name): int
    {
        return AdGroup::on($this->adPlatform)->where('name', 'LIKE', $name . '%')->first()->id;
    }

}
