<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('payouts');

        Schema::create('payouts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('order_detail_id')->unsigned();
            $table->foreign('order_detail_id')->references('id')->on('orders_details');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('user_type',['buyer', 'merchant']);
            $table->string('account');
            $table->string('correlation_id',255)->nullable();
            $table->float('amount', 8, 2)->nullable();
            $table->float('subtotal', 8, 2)->nullable();
            $table->float('service', 8, 2)->nullable();
            $table->float('shipping', 8, 2)->nullable();
            $table->float('merchant_fee', 8, 2)->nullable();
            $table->float('applied_owe_amount', 8, 2)->nullable();
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
            $table->enum('status',['Completed', 'Future','Processing']);
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
        Schema::dropIfExists('payouts');
    }
}