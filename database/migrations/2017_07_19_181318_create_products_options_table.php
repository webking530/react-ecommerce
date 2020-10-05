<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('sku');
            $table->string('option_name');
            $table->integer('total_quantity')->nullable();
            $table->integer('sold')->nullable();
            $table->integer('price');
            $table->integer('retail_price')->nullable();
            $table->float('discount', 6, 2)->nullable();
            $table->float('length', 6, 2)->nullable();
            $table->float('width', 6, 2)->nullable();
            $table->float('height', 6, 2)->nullable();
            $table->float('weight', 6, 2)->nullable();
            $table->enum('sold_out', ['Yes', 'No'])->default('No');
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
        Schema::dropIfExists('products_options');
    }
}
