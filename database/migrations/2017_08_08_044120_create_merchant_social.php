<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantSocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('merchant_social');

        Schema::create('merchant_social', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_store_id')->unsigned();
            $table->foreign('merchant_store_id')->references('id')->on('merchant_store');
            $table->string('link_name',100);
            $table->string('link_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('merchant_social');
    }
}
