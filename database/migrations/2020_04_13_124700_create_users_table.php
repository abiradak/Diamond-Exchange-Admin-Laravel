<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0)->nullable();
            $table->string('username',30)->nullable();
            $table->string('name',100)->nullable();
            $table->string('mobile',20)->nullable();
            $table->string('password',100)->nullable();
            $table->string('orginal_password',100)->nullable();
            $table->string('reference',100)->nullable();
            $table->tinyInteger('role')->nullable();
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
        Schema::dropIfExists('users');
    }
}
