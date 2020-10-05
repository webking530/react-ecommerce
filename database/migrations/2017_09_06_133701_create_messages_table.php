<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('messages');
        
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_from')->unsigned();
            $table->foreign('user_from')->references('id')->on('users');
            $table->integer('user_to')->unsigned();
            $table->foreign('user_to')->references('id')->on('users');
            $table->text('message')->nullable();
            $table->integer('group_id');
            $table->enum('read', ['0', '1']);
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
        Schema::drop('messages');
    }
}
