<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('return_policy')->unsigned();
            $table->foreign('return_policy')->references('id')->on('return_policy');
            $table->string('category_path', 255)->default('1,15');
            $table->string('title', 255);
            $table->text('description');
            $table->string('total_quantity', 255)->nullable();
            $table->string('sold', 255)->nullable();
            $table->integer('exchange_policy')->nullable();
            $table->text('policy_description');
            $table->string('video_mp4', 255)->nullable();
            $table->string('video_webm', 255)->nullable();
            $table->string('video_thumb', 255)->nullable();
            $table->integer('views_count');
            $table->integer('likes_count')->default('0');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('admin_status',255)->default('Waiting');
            $table->enum('sold_out', ['Yes', 'No'])->default('No');
            $table->enum('cash_on_store', ['Yes', 'No'])->default('No');
            $table->enum('cash_on_delivery', ['Yes', 'No'])->default('No');
            $table->enum('is_featured', ['Yes', 'No'])->default('No');
            $table->enum('is_popular', ['Yes', 'No'])->default('No');
            $table->enum('is_recommend', ['Yes', 'No'])->default('No');
            $table->enum('is_editor', ['Yes', 'No'])->default('No');
            $table->enum('is_header', ['Yes', 'No'])->default('No');
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
        Schema::dropIfExists('products');
    }
}
