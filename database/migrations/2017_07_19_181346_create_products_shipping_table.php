<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsShippingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('products_shipping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->enum('shipping_type', ['Free Shipping', 'Flat Rates'])->default('Flat Rates');
            $table->string('ships_from', 255);
            $table->string('ships_to', 255);
            $table->float('charge', 8, 2)->nullable();
            $table->float('incremental_fee', 8, 2)->nullable();
            $table->integer('start_window');
            $table->integer('end_window');
            $table->string('manufacture_country', 255);
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
        Schema::dropIfExists('products_shipping');
    }
}
