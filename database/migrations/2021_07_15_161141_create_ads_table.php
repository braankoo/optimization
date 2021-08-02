<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('gemini')->create('ads', function (Blueprint $table) {
            $table->bigInteger('id', false, true);
            $table->bigInteger('ad_group_id', false, true);
            $table->primary([ 'id' ]);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('gemini')->dropIfExists('ads');
    }
}
