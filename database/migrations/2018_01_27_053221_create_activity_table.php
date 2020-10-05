<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('activity');
        
        Schema::create('activity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id');            
            $table->enum('source_type', ['user', 'store']);
            $table->enum('activity_type', ['add_product','like_product','following_store','following_user'])->nullable();
            $table->integer('target_id')->nullable();            
            $table->enum('read', ['0', '1'])->default('0');
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
        Schema::drop('activity');
    }
}
