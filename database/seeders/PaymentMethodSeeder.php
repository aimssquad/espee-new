<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Cash on Delivery',
                'code' => 'cod',
                'description' => 'Pay when your order is delivered',
                'config' => [
                    'enabled' => true,
                    'processing_fee' => 0,
                    'min_order_amount' => 0,
                    'max_order_amount' => 1000
                ],
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Razorpay',
                'code' => 'razorpay',
                'description' => 'Secure online payment via Razorpay',
                'config' => [
                    'enabled' => true,
                    'key_id' => 'rzp_test_1234567890',
                    'key_secret' => 'your_razorpay_secret',
                    'processing_fee' => 2.5,
                    'min_order_amount' => 1,
                    'max_order_amount' => 50000
                ],
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'PayU',
                'code' => 'payu',
                'description' => 'Secure online payment via PayU',
                'config' => [
                    'enabled' => true,
                    'merchant_key' => 'your_payu_merchant_key',
                    'merchant_salt' => 'your_payu_salt',
                    'processing_fee' => 2.0,
                    'min_order_amount' => 1,
                    'max_order_amount' => 100000
                ],
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
