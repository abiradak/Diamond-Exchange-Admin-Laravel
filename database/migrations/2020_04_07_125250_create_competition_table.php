<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sport_id')->nullable();
            $table->tinyInteger('sport_type')->nullable();
            $table->integer('event_id')->default(0)->nullable();
            $table->string('name',100)->nullable()->nullable();
            $table->string('image',50)->nullable()->nullable();
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
        Schema::dropIfExists('competition');
    }
}
