<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Black', 'hex_code' => '#000000'],
            ['name' => 'Brown', 'hex_code' => '#8B4513'],
            ['name' => 'Gold', 'hex_code' => '#FFD700'],
            ['name' => 'Silver', 'hex_code' => '#C0C0C0'],
            ['name' => 'Blue', 'hex_code' => '#0000FF'],
            ['name' => 'Grey', 'hex_code' => '#808080'],
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
            ['name' => 'Red', 'hex_code' => '#FF0000'],
            ['name' => 'Green', 'hex_code' => '#008000'],
            ['name' => 'Purple', 'hex_code' => '#800080'],
            ['name' => 'Pink', 'hex_code' => '#FFC0CB'],
            ['name' => 'Orange', 'hex_code' => '#FFA500'],
            ['name' => 'Rose Gold', 'hex_code' => '#E8B4B8'],
            ['name' => 'Tortoise', 'hex_code' => '#8B4513'],
            ['name' => 'Crystal', 'hex_code' => '#F0F8FF']
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
