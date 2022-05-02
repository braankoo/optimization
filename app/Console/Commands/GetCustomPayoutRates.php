<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Platform;
use App\Models\Site;
use App\Models\Webmaster;
use Illuminate\Console\Command;
use Fbmaff\Client\ApiClient;
use Fbmaff\Auth\Basic;
use Fbmaff\Transport\Curl;
use Fbmaff\Api\CustomPayoutRatesApi;
use Illuminate\Support\Arr;

class GetCustomPayoutRates extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:payout-rates {platform}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {


        Webmaster::on($this->argument('platform'))->has('apiKeys')
            ->with('apiKeys')
            ->each(function ($webmaster) {
                $webmaster->apiKeys->each(function ($apiKey) use ($webmaster) {


                    $data = $this->getData($webmaster, $apiKey);
                    if (!empty($data))
                    {
                        $allWebsitesData = array_values(
                            array_filter($data, function ($row) {
                                return $row['website'] == 'All websites';
                            })
                        );

                        $data = $this->prepareData($webmaster, $data);

                        $updatedCampaigns = [];

                        foreach ( $data as $single )
                        {
                            $updatedCampaigns[] = $this->getUpdatedCampaigns($single, $webmaster);
                        }

                        if (!empty($allWebsitesData))
                        {
                            Campaign::on($this->argument('platform'))
                                ->whereHas('webmasters', function ($query) use ($webmaster) {
                                    $query->where('id', '=', $webmaster->id);
                                })
                                ->whereNotIn('id', Arr::flatten($updatedCampaigns))
                                ->update([ 'payout_rate' => (int) $allWebsitesData[0]['payout'] * 1000000 ]);

                            foreach ( [ 'tab', 'mob' ] as $device )
                            {

                                $webmasterWithDevice = str_replace('google', 'google' . $device, $webmaster->name);
                                if ($webmasterWithDevice = Webmaster::on($this->argument('platform'))->where('name', '=', $webmasterWithDevice)->first())
                                {
                                    Campaign::on($this->argument('platform'))
                                        ->whereHas('webmasters', function ($query) use ($webmasterWithDevice) {
                                            $query->where('id', '=', $webmasterWithDevice->id);
                                        })
                                        ->whereNotIn('id', Arr::flatten($updatedCampaigns))
                                        ->update([ 'payout_rate' => (int) $allWebsitesData[0]['payout'] * 1000000 ]);
                                }

                            }
                        }
                    }
                });

            });

        return 1;
    }

    /**
     * @param $webmaster
     * @param $apiKey
     * @return array|null
     * @throws \Fbmaff\Api\Exceptions\ApiException
     * @throws \Fbmaff\Auth\Exceptions\MissingUsernameException
     * @throws \Fbmaff\Transport\Exceptions\InvalidHostnameException
     */
    private function getData($webmaster, $apiKey): ?array
    {
        $client = new ApiClient(new Curl(config('affiliate.hostname')), new Basic($webmaster->name, $apiKey->api_key));
        $api = new CustomPayoutRatesApi($client, 2);
        $api->setPlatform(Platform::find($apiKey->platform_id)->url);
        $api->fetch();

        return $api->getData();
    }

    /**
     * @param $webmaster
     * @param array $data
     * @return array|array[]
     */
    private function prepareData($webmaster, array $data): array
    {
        return array_map(
            function ($row) use ($webmaster) {
                return [
                    'site_id'      => Site::on($this->argument('platform'))->where('url', '=', $row['website'])->exists() ? Site::on($this->argument('platform'))->where('url', '=', $row['website'])->first()->id : $row['website'],
                    'payout'       => (int) $row['payout'] * 1000000,
                    'webmaster_id' => $webmaster->id
                ];
            },
            array_filter($data, function ($row) {
                return $row['website'] != 'All websites';
            })
        );
    }

    /**
     * @param $site_id
     * @param $webmaster
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getWhereHas($site_id, $webmaster): \Illuminate\Database\Eloquent\Builder
    {
        return Campaign::on($this->argument('platform'))
            ->where('site_id', '=', $site_id)
            ->whereHas('webmasters', function ($query) use ($webmaster) {
                $query->where('id', '=', $webmaster->id);
            });
    }

    /**
     * @param $single
     * @param $webmaster
     * @return array
     */
    private function getUpdatedCampaigns($single, $webmaster): array
    {
        $this->getWhereHas($single['site_id'], $webmaster)->update([ 'payout_rate' => $single['payout'] ]);

        return $this->getWhereHas($single['site_id'], $webmaster)->get('id')->toArray();
    }
}
