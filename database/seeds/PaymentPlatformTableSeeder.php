<?php

use App\PaymentPlatform;
use Illuminate\Database\Seeder;

class PaymentPlatformTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentPlatform::create([
            'name' => 'paypal',
            'image' => 'img/payment-platforms/paypal.svg',
        ]);

        PaymentPlatform::create([
            'name' => 'stripe',
            'image' => 'img/payment-platforms/stripe.svg',
        ]);
    }
}
