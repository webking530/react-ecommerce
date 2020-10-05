<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponcodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('coupon_code');
        
        Schema::create('coupon_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_code',50);
            $table->integer('amount');     
            $table->string('currency_code',10);
            $table->date('expired_at');
            $table->enum('status', ['Active','Inactive'])->default('Active');
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
        Schema::drop('coupon_code');
    }
}
