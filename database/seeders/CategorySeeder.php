<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Sunglasses' => ['Men\'s Sunglasses', 'Women\'s Sunglasses', 'Unisex Sunglasses', 'Sports Sunglasses', 'Polarized Sunglasses'],
            'Frames' => ['Men\'s Frames', 'Women\'s Frames', 'Unisex Frames', 'Reading Glasses', 'Computer Glasses'],
            'Accessories' => ['Cases', 'Cleaning Kits', 'Straps', 'Lens Cloths'],
            'Premium Collection' => ['Limited Edition', 'Designer Collaboration', 'Luxury Line'],
            'Kids Collection' => ['Boys Sunglasses', 'Girls Sunglasses', 'Teen Collection']
        ];

        foreach ($categories as $categoryName => $subcategories) {
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName)
            ]);

            foreach ($subcategories as $subcategoryName) {
                Subcategory::create([
                    'category_id' => $category->id,
                    'name' => $subcategoryName,
                    'slug' => Str::slug($subcategoryName)
                ]);
            }
        }
    }
}