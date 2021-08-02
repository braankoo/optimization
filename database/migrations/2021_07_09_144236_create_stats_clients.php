<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsClients extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ( [ 'google', 'bing', 'gemini' ] as $adPlatform )
        {
            Schema::connection($adPlatform)->create('stats_clients', function (Blueprint $table) {
                $table->integer('impressions');
                $table->integer('clicks');
                $table->bigInteger('cost');
                $table->double('avg_position', 3, 1);
                $table->bigInteger('profile')->default(0);
                $table->bigInteger('upgrade')->default(0);
                $table->bigInteger('earned')->default(0);
                $table->date('created_at');
                $table->foreignId('client_id')->constrained()->onDelete('CASCADE');
                $table->primary([ 'client_id', 'created_at' ]);

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
        Schema::dropIfExists('stats_clients');
    }
}
