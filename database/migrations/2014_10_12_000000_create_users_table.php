<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->string('store_name')->nullable();
            $table->enum('already_selling',['not_currently_selling','amazon','ebay','magento','etsy','woocommerce','other'])->nullable();
            $table->string('product_categories')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender',['Male', 'Female', 'Other'])->nullable();
            $table->string('location')->nullable();
            $table->text('bio')->nullable();
            $table->text('website')->nullable();
            $table->string('google_id', 50)->unique()->nullable();
            $table->string('twitter_id', 50)->unique()->nullable();
            $table->string('facebook_id', 50)->unique()->nullable();
            $table->string('apple_id', 50)->unique()->nullable();
            $table->enum('device_type',['1', '2'])->nullable();
            $table->text('device_id')->nullable();
            $table->string('timezone')->default('UTC');
            $table->enum('timeformat',['0', '1'])->default('0');
            $table->string('languages')->nullable();
            $table->string('currency_code',10)->nullable();
            $table->enum('status',['Null','Active', 'Inactive'])->default('Null');
            $table->enum('featured',['Yes', 'No'])->default('No');
            $table->enum('is_header', ['Yes', 'No'])->default('No');
            $table->enum('type',['buyer', 'merchant'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $statement = "ALTER TABLE users AUTO_INCREMENT = 10001;";

        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
