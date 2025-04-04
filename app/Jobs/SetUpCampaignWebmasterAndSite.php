<?php

namespace App\Jobs;

use App\Library\Ads\DetectorFactory;
use App\Models\Campaign;
use App\Models\Site;
use App\Models\Webmaster;
use FirstBeatMedia\AdWordManagement\Facades\AdWordManagement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetUpCampaignWebmasterAndSite implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\Campaign
     */
    public Campaign $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public int $tries = 1;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $this->campaign->adGroups()->chunk(3, function ($adGroups) {

            $ads = AdWordManagement::client($this->campaign->client)->getAds($adGroups->map->id->toArray());

            if (!empty($ads))
            {

                $detector = DetectorFactory::make($this->campaign->getConnectionName(), $ads[0]);

                $site = $detector->getSite();

                $site = Site::on($this->campaign->getConnectionName())->firstOrCreate([ 'url' => $site ]);

                foreach ( $detector->getWebmaster() as $webmasterData )
                {
                    $webmaster = Webmaster::on($this->campaign->getConnectionName())->where('name', '=', $webmasterData['name'])->where('device', '=', $webmasterData['device'])->first();

                    if (is_null($webmaster))
                    {
                        $webmaster = Webmaster::on($this->campaign->getConnectionName())->create([ 'name' => $webmasterData['name'], 'device' => $webmasterData['device'] ]);
                    }

                    if ($this->campaign->webmasters()->where('id', '=', $webmaster->id)->doesntExist())
                    {
                        $this->campaign->webmasters()->attach($webmaster);
                    }


                }


                $this->campaign->site_id = $site->id;

                $this->campaign->save();

                return false;
            }
        });

    }
}
