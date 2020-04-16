<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match', function (Blueprint $table) {
            $table->increments('id');
            $table->string('market_id',15)->nullable(); 
            $table->integer('event_id')->nullable(); 
            $table->integer('sport_id')->nullable(); 
            $table->integer('sport_type')->nullable(); 
            $table->integer('competition_id')->nullable(); 
            $table->boolean('complete')->default(0)->nullable();
            $table->boolean('inplay')->default(0)->nullable();
            $table->string('name',255)->nullable(); 
            $table->string('shortname',255)->nullable(); 
            $table->dateTime('date')->nullable();  
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
        Schema::dropIfExists('match');
    }
}
