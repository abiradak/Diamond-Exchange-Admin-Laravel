<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market', function (Blueprint $table) {
            $table->increments('id');         
            $table->string('market_id',15)->nullable();
            $table->integer('event_id')->nullable();
            $table->string('name',150)->nullable();
            $table->integer('result')->nullable();
            $table->integer('bet_max')->nullable();
            $table->integer('bet_min')->nullable();
            $table->boolean('commission')->default(0)->nullable();
            $table->boolean('declared')->default(0)->nullable();
            $table->boolean('completed')->default(0)->nullable();
            $table->boolean('locked')->default(0)->nullable();
            $table->boolean('active')->default(1)->nullable();
            $table->boolean('delete')->default(0)->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market');
    }
}
