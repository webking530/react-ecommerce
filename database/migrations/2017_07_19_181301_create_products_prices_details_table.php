<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsPricesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_prices_details', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('sku')->nullable();
            $table->integer('price')->nullable();
            $table->integer('retail_price')->nullable();
            $table->float('discount', 6, 2)->nullable();
            $table->float('length', 6, 2)->nullable();
            $table->float('width', 6, 2)->nullable();
            $table->float('height', 6, 2)->nullable();
            $table->float('weight', 6, 2)->nullable();
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
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
        Schema::dropIfExists('products_prices_details');
    }
}
