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
            'title' => 'ELEVATE YOUR STYLE',
            'description' => 'Discover our premium collection of designer sunglasses and optical frames. Crafted with precision and style for the modern lifestyle.',
            'video_type' => 'youtube',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Sample YouTube URL - replace with actual video
            'button_text' => 'EXPLORE COLLECTION',
            'button_url' => '/products', // Will redirect to products page
            'is_active' => true,
            'autoplay' => true,
            'muted' => true,
            'loop' => true,
        ]);
    }
}
