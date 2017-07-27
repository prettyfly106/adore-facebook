<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('facebook_posts')) {
            Schema::create('facebook_posts', function (Blueprint $table) {
                $table->string('page_id');
                $table->string('post_id')->unique();
                $table->integer('creator_id',50);
                $table->integer('product_id')->nullable();
                $table->foreign('page_id')->references('page_id')->on('facebook_pages');
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
        Schema::dropIfExists('facebook_posts');
    }
}
