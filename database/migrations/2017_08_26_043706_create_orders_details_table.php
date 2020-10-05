<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('orders_details');

        Schema::create('orders_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('merchant_id')->unsigned();
            $table->foreign('merchant_id')->references('id')->on('users');
            $table->integer('option_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->float('price', 8, 2)->nullable();
            $table->float('shipping', 8, 2)->nullable();
            $table->float('incremental', 8, 2)->nullable();
            $table->float('service', 8, 2)->nullable();
            $table->float('merchant_fee', 8, 2)->nullable();
            $table->float('owe_amount', 8, 2)->nullable();
            $table->float('applied_owe_amount', 8, 2)->nullable();
            $table->float('remaining_owe_amount', 8, 2)->nullable();
            $table->enum('status', ['Pending', 'Processing', 'Delivered', 'Completed','Cancelled','Returned','Exchanged'])->nullable();
            $table->enum('cancelled_by', ['Buyer', 'Merchant'])->nullable();
            $table->enum('return_status',['Requested','Approved','Awaiting','Received','Completed','Canceled','Rejected'])->nullable();
            $table->enum('exchange_status',['Requested','Approved','Awaiting','Received','Completed','Canceled','Rejected'])->nullable();
            $table->integer('return_policy');
            $table->integer('exchange_policy');
            $table->date('order_return_date');
            $table->timestamp('completed_at');
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
        Schema::dropIfExists('orders_details');
    }
}
