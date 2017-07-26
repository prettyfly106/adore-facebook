<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Take simplest way to remove table if exists here
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 45)->unique();
            $table->string('password')->nullable();
            $table->string('image', 500)->nullable();
            $table->string('name', 10);
            $table->string('phone', 200)->nullable();
            $table->unsignedInteger('client_id');
            $table->dateTime('dateOfBith')->nullable();
            $table->string('email', 100)->unique();
            $table->string('address', 250)->nullable();
            $table->string('fb_id',50)->nullable();
            // $table->rememberToken();
            $table->timestamps();

            // Foreign key
            // $table->foreign('client_id')->references('id')->on('clients');
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
