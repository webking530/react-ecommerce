<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('notifications');
        
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->integer('order_details_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('notify_id')->nullable();
            $table->integer('follower_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('store_id')->nullable();
            $table->enum('notification_type', ['order', 'store_follow','user_follow','like_product','wishlist','featured']);
            $table->enum('notification_type_status', ['cancelled_buyer','process','finished','returned','cancelled','payout','refund','pending','return_accept','return_reject'])->nullable();
            $table->text('notification_message')->nullable();
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
        Schema::drop('notifications');
    }
}
