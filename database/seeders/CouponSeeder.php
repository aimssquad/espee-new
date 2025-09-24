<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => '10% off for new customers',
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 100,
                'maximum_discount' => 50,
                'usage_limit' => 100,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(3),
                'is_active' => true
            ],
            [
                'code' => 'SAVE20',
                'name' => 'Save 20%',
                'description' => '20% off on orders above $200',
                'type' => 'percentage',
                'value' => 20,
                'minimum_amount' => 200,
                'maximum_discount' => 100,
                'usage_limit' => 50,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(2),
                'is_active' => true
            ],
            [
                'code' => 'FLAT50',
                'name' => 'Flat $50 Off',
                'description' => 'Get $50 off on orders above $300',
                'type' => 'fixed',
                'value' => 50,
                'minimum_amount' => 300,
                'usage_limit' => 25,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonth(),
                'is_active' => true
            ],
            [
                'code' => 'FIRST15',
                'name' => 'First Order',
                'description' => '15% off on your first order',
                'type' => 'percentage',
                'value' => 15,
                'minimum_amount' => 50,
                'maximum_discount' => 75,
                'usage_limit' => 200,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(6),
                'is_active' => true
            ],
            [
                'code' => 'SUMMER25',
                'name' => 'Summer Sale',
                'description' => '25% off on summer collection',
                'type' => 'percentage',
                'value' => 25,
                'minimum_amount' => 150,
                'maximum_discount' => 150,
                'usage_limit' => 75,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(4),
                'is_active' => true
            ],
            [
                'code' => 'VIP30',
                'name' => 'VIP Member',
                'description' => '30% off for VIP members',
                'type' => 'percentage',
                'value' => 30,
                'minimum_amount' => 300,
                'maximum_discount' => 200,
                'usage_limit' => null,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addYear(),
                'is_active' => true
            ],
            [
                'code' => 'STUDENT20',
                'name' => 'Student Discount',
                'description' => '20% off for students',
                'type' => 'percentage',
                'value' => 20,
                'minimum_amount' => 80,
                'maximum_discount' => 40,
                'usage_limit' => 300,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(6),
                'is_active' => true
            ],
            [
                'code' => 'BULK100',
                'name' => 'Bulk Purchase',
                'description' => '$100 off on orders above $500',
                'type' => 'fixed',
                'value' => 100,
                'minimum_amount' => 500,
                'usage_limit' => 25,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(2),
                'is_active' => true
            ],
            [
                'code' => 'NEWYEAR35',
                'name' => 'New Year Special',
                'description' => '35% off to celebrate new year',
                'type' => 'percentage',
                'value' => 35,
                'minimum_amount' => 250,
                'maximum_discount' => 175,
                'usage_limit' => 500,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addDays(15),
                'is_active' => true
            ],
            [
                'code' => 'FLASH40',
                'name' => 'Flash Sale',
                'description' => '40% off for limited time',
                'type' => 'percentage',
                'value' => 40,
                'minimum_amount' => 200,
                'maximum_discount' => 300,
                'usage_limit' => 1000,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addDays(3),
                'is_active' => true
            ],
            [
                'code' => 'EXPIRED10',
                'name' => 'Expired Coupon',
                'description' => 'This coupon has expired',
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 100,
                'maximum_discount' => 20,
                'usage_limit' => 50,
                'starts_at' => Carbon::now()->subDays(30),
                'expires_at' => Carbon::now()->subDays(1),
                'is_active' => false
            ]
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}
