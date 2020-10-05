<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::dropIfExists('orders');

		Schema::create('orders', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('buyer_id')->unsigned();
			$table->foreign('buyer_id')->references('id')->on('users');
			$table->float('subtotal', 8, 2)->nullable();
			$table->float('shipping_fee', 8, 2)->nullable();
			$table->float('incremental_fee', 8, 2)->nullable();
			$table->float('service_fee', 8, 2)->nullable();
			$table->float('merchant_fee', 8, 2)->nullable();
			$table->string('coupon_code', 50);
			$table->float('coupon_amount', 8, 2)->nullable();
			$table->float('total', 8, 2)->nullable();
			$table->string('currency_code', 10);
			$table->foreign('currency_code')->references('code')->on('currency');
			$table->string('transaction_id', 50)->nullable();
			$table->string('customer_id', 50)->nullable();
			$table->string('card_id', 50)->nullable();
			$table->string('paymode', 255)->nullable();
			$table->timestamps();
		});

		$statement = "ALTER TABLE orders AUTO_INCREMENT = 10001;";

		DB::unprepared($statement);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('orders');
	}
}
