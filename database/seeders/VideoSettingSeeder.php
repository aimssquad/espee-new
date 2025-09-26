<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VideoSetting;

class VideoSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VideoSetting::create([
            'title' => 'Discover Premium Eyewear',
            'description' => 'Explore our exclusive collection of premium sunglasses and frames designed for the modern lifestyle.',
            'video_type' => 'youtube',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Sample YouTube URL
            'button_text' => 'Shop Now',
            'button_url' => null, // Will redirect to products page
            'is_active' => true,
            'autoplay' => true,
            'muted' => true,
            'loop' => true,
        ]);
    }
}
