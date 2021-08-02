<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebmastersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ( [ 'google', 'bing', 'gemini' ] as $adPlatform )
        {

            Schema::connection($adPlatform)->create('webmasters', function (Blueprint $table) {
                $table->id();
                $table->char('name')->unique();
                $table->enum('device', [ 'MOBILE', 'TABLET', 'DESKTOP' ]);
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
            Schema::connection($adPlatform)->dropIfExists('webmasters');
        }
    }
}
