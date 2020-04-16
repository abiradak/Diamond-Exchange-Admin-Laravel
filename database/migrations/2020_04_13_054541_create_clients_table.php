<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('parent_id')->default(0);
            $table->string('username',30);
            $table->string('name',100)->nullable();
            $table->string('mobile',15)->nullable();
            $table->string('password',100);
            $table->string('orginal_password',30);
            $table->boolean('active')->default(1)->nullable();
            $table->boolean('is_multisign')->default(0)->nullable();
            $table->boolean('deleted')->default(0)->nullable();
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
        Schema::dropIfExists('clients');
    }
}
