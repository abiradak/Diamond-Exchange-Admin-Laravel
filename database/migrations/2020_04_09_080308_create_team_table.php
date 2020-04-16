<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->nullable();
            $table->string('short_name',50)->nullable();
            $table->bigInteger('selection_id')->nullable();
            $table->integer('sport_id')->default(0)->nullable();
            $table->integer('sport_type')->nullable();
            $table->boolean('active')->default(1)->nullable();
            $table->boolean('delete')->default(0)->nullable();
            $table->string('image',50)->nullable();
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
        Schema::dropIfExists('teams');
    }
}
