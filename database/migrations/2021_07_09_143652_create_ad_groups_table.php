<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdGroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ( [ 'google', 'bing', 'gemini' ] as $adPlatform )
        {
            Schema::connection($adPlatform)->create('ad_groups', function (Blueprint $table) {
                $table->bigInteger('id', false, true)->unique();
                $table->char('name', 255);
                $table->integer('bid');
                $table->enum('status', [ 'ENABLED', 'ACTIVE', 'PAUSED', 'REMOVED', 'DISABLED', 'DELETED' ]);
                $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
                $table->primary('id');

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
        foreach ( [ 'google', 'bing', 'gemini' ] as $adPlatform )
        {
            Schema::connection($adPlatform)->dropIfExists('ad_groups');
        }
    }
}
