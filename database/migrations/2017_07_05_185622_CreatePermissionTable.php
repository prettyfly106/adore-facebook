<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Take simplest way to remove table if exists here
        Schema::dropIfExists('permission');
        Schema::create('permission', function (Blueprint $table) {
            $table->increments('idPermission');
            $table->string('permissionDescription', 100);
            $table->string('permissionName', 45);
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
        Schema::dropIfExists('permission');
    }
}
