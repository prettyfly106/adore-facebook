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
            $table->string('password');
            $table->string('image', 100);
            $table->string('name', 10);
            $table->string('phone', 200);
            $table->unsignedInteger('client_id');
            $table->dateTime('dateOfBith');
            $table->string('email', 100)->unique();
            $table->string('address', 250);
            $table->string('fb_id',50);
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
