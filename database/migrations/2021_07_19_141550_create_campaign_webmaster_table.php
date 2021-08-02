<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignWebmasterTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        foreach ( [ 'gemini', 'bing', 'google' ] as $adPlatform )
        {
            Schema::connection($adPlatform)->create('campaign_webmasters', function (Blueprint $table) {
                $table->bigInteger('campaign_id', false, true);
                $table->integer('webmaster_id', false, true);
                $table->unique([ 'campaign_id', 'webmaster_id' ]);

            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ( [ 'gemini', 'bing', 'google' ] as $adPlatform )
        {
            Schema::connection($adPlatform)->dropIfExists('campaign_webmasters');
        }
    }
}
