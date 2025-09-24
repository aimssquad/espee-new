<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class UpdateProductsGenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing products with random gender values
        $genders = ['men', 'women', 'unisex'];

        Product::whereNull('gender')->orWhere('gender', '')->get()->each(function ($product) use ($genders) {
            $product->update([
                'gender' => $genders[array_rand($genders)]
            ]);
        });

        $this->command->info('Updated existing products with gender values.');
    }
}
