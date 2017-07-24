<?php

use Illuminate\Database\Migrations\Migration;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('conversations', function ($tbl) {
            $tbl->increments('id');
            $tbl->integer('user_one')->unsigned();
            $tbl->integer('user_two')->unsigned();
            $tbl->integer('thread_id')->unsigned();
            $tbl->foreign('thread_id')->references('id')->on(config('talk.user.thread.table'))->onDelete('cascade');
            $tbl->boolean('status');
            $tbl->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('conversations');
    }
}
