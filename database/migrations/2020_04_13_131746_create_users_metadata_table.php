<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_metadata', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->tinyInteger('role')->nullable();
            $table->string('city',100)->nullable();
            $table->string('credit_reference',100)->nullable();
            $table->float('exposure_limit',12,2)->nullable();
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
        Schema::dropIfExists('users_metadata');
    }
}
