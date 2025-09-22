<?php

namespace Database\Seeders;

use App\Models\Shape;
use Illuminate\Database\Seeder;

class ShapeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shapes = [
            'Round',
            'Square',
            'Aviator',
            'Cat Eye',
            'Rectangle',
            'Oval'
        ];

        foreach ($shapes as $shape) {
            Shape::create(['name' => $shape]);
        }
    }
}