<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethodMaster;

class PaymentMethodMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Razorpay',
                'slug' => 'razorpay',
                'display_name' => 'Razorpay',
                'description' => 'Pay securely with Razorpay - UPI, Cards, Net Banking, Wallets',
                'icon' => 'fab fa-cc-razorpay',
                'is_active' => true,
                'sort_order' => 1,
                'razorpay_test_mode' => true,
            ],
            [
                'name' => 'PayU',
                'slug' => 'payu',
                'display_name' => 'PayU',
                'description' => 'Pay with PayU - Cards, UPI, Net Banking, Wallets',
                'icon' => 'fas fa-credit-card',
                'is_active' => true,
                'sort_order' => 2,
                'payu_test_mode' => true,
            ],
            [
                'name' => 'Cash on Delivery',
                'slug' => 'cod',
                'display_name' => 'Cash on Delivery',
                'description' => 'Pay when your order is delivered to your doorstep',
                'icon' => 'fas fa-money-bill-wave',
                'is_active' => true,
                'sort_order' => 3,
                'cod_minimum_amount' => 0,
                'cod_maximum_amount' => 5000,
                'cod_instructions' => 'Please keep exact change ready. Our delivery partner will collect the payment upon delivery.',
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethodMaster::updateOrCreate(
                ['slug' => $method['slug']],
                $method
            );
        }
    }
}
