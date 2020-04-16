<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_metadata', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable();
            $table->boolean('role')->length(1)->nullable();
            $table->string('city',50)->nullable();
            $table->string('credit_reference')->nullable();
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
        Schema::dropIfExists('clients_metadata');
    }
}
