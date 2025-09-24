<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            ShapeSeeder::class,
            ColorSeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            PaymentMethodSeeder::class,
        ]);
    }
}
