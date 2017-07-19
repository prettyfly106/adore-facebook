<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('facebook_messages')) {
            Schema::create('facebook_messages', function (Blueprint $table) {
                $table->string('page_id');
                $table->string('message_id');
                $table->string('sender_id');
                $table->integer('status');
                $table->unique(['message_id']);
                $table->foreign('page_id')
          ->references('page_id')->on('facebook_pages');
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
        Schema::dropIfExists('facebook_messages');
    }
}
