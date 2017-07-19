<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('facebook_comments')) {
            Schema::create('facebook_comments', function(Blueprint $table) {
                $table->string('post_id');
                $table->string('comment_id');
                $table->integer('poster_id');
                $table->integer('status');
               $table->unique(['comment_id']);
               $table->foreign('post_id')
          ->references('post_id')->on('facebook_posts');
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
        Schema::dropIfExists('facebook_comments');
    }
}
