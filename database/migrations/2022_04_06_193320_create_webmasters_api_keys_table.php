<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebmastersApiKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('google')->create('webmasters_api_keys', function (Blueprint $table) {
            $table->integer('webmaster_id')->unsigned();
            $table->char('api_key', 32)->unique();
            $table->integer('platform_id');
            $table->unique(['webmaster_id', 'platform_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('google')->dropIfExists('webmasters_api_keys');
    }
}
