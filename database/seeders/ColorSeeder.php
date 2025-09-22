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
            ['name' => 'Grey', 'hex_code' => '#808080']
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}