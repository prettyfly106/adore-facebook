<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('facebook_pages')) {
            Schema::create('facebook_pages', function(Blueprint $table) {
                $table->integer('user_id');
                $table->string('page_id');
                $table->integer('status');
                $table->timestamps();
                $table->primary(['user_id', 'page_id']);
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('facebook_pages');
    }
}
