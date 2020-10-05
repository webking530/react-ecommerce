<?php

use Illuminate\Database\Seeder;

class PaymentGatewayTableSeeder extends Seeder
{
	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('payment_gateway')->delete();

    	DB::table('payment_gateway')->insert([
    		array('name' => 'username','value' => 'spiffy_api1.trioangle.com','site' => 'PayPal'),
			array('name' => 'password','value' => '6LWJY7Q6KKNUDFQM','site' => 'PayPal'),
			array('name' => 'signature','value' => 'AVR4tiu9sPLW6S-JkHozBjcunZ.RA0djF4QdASgff65SdIwwXKdCj2Ua','site' => 'PayPal'),
			array('name' => 'mode','value' => 'sandbox','site' => 'PayPal'),
			array('name' => 'client','value' => 'ASirEwWozg2-Ywj_9p-jkTebkB4GWTlwLc6ZnB9ed6clxoRZurvPwQ30Vb4lX8mTHnutbA7IBVsIJXXS','site' => 'PayPal'),
			array('name' => 'secret','value' => 'EGw5iZtlVf2YcHCSetGWD1uu0wzT61l-pAZLXVcC-nM9ctx64VuBCv0hLcoXGOQQajqNmT6s055bcSnE','site' => 'PayPal'),
			array('name' => 'secret','value' => 'sk_test_SrX0ZinAVXAuA5vNPnPAK4lV00KadlD10f','site' => 'Stripe'),
			array('name' => 'publish','value' => 'pk_test_9aik1kDQTkYGf3uRF4SzSLJq00KZtoYsxV','site' => 'Stripe'),
			array('name' => 'client','value' => 'ca_GrO51W6KULVKOXtf3uzEZtBUTE0LlnxO','site' => 'Stripe'),
			array('name' => 'api_version','value' => '2020-03-02','site' => 'Stripe'),
			array('name' => 'paypal_enabled','value' => 'Yes','site' => 'PaymentMethod'),
			array('name' => 'stripe_enabled','value' => 'Yes','site' => 'PaymentMethod'),
			array('name' => 'cod_enabled','value' => 'No','site' => 'PaymentMethod'),
			array('name' => 'cos_enabled','value' => 'No','site' => 'PaymentMethod'),
    	]);
    }
}