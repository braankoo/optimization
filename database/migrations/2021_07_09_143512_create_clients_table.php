<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ( [ 'google', 'bing', 'gemini' ] as $adPlatform )
        {
            Schema::connection($adPlatform)->create('clients',
                function (Blueprint $table) use ($adPlatform) {
                    $table->bigInteger('id', false, true);
                    $table->bigInteger('mcc_account_id', false, true);
                    $table->char('name', 255)->unique();
                    $table->enum('STATUS', [ 'ACTIVE', 'PAUSED', 'REMOVED', 'ENABLED', 'DISABLED' ])->nullable();
                    if ($adPlatform == 'google')
                    {
                        $table->foreign('mcc_account_id')->references('id')->on('mcc_accounts')->onDelete('cascade');
                    }

                    $table->primary('id');
                }
            );
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
            Schema::connection($adPlatform)->dropIfExists('clients');
        }
    }
}
