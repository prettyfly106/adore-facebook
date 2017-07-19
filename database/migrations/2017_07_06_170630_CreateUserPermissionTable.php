<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_has_permission', function (Blueprint $table) {
            $table->unsignedInteger('idUser');
            $table->unsignedInteger('idPermission');
            $table->primary(['idUser', 'idPermission']);
            // Foreign key
            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('idPermission')->references('idPermission')->on('permission')->onDelete('cascade');;
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
        Schema::dropIfExists('user_has_permission');
    }
}
