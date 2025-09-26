<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoSetting extends Model
{
    protected $fillable = [
        'title',
        'description',
        'video_type',
        'video_file',
        'youtube_url',
        'button_text',
        'button_url',
        'is_active',
        'autoplay',
        'muted',
        'loop'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'autoplay' => 'boolean',
        'muted' => 'boolean',
        'loop' => 'boolean'
    ];

    public function getVideoUrlAttribute()
    {
        if ($this->video_type === 'youtube') {
            return $this->youtube_url;
        }

        return $this->video_file ? asset('storage/' . $this->video_file) : null;
    }

    public function getYoutubeEmbedUrlAttribute()
    {
        if ($this->video_type === 'youtube' && $this->youtube_url) {
            // Extract video ID from YouTube URL
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_url, $matches);
            $videoId = $matches[1] ?? null;

            if ($videoId) {
                $params = 'autoplay=' . ($this->autoplay ? '1' : '0') .
                         '&mute=' . ($this->muted ? '1' : '0') .
                         '&loop=' . ($this->loop ? '1' : '0') .
                         '&controls=0&showinfo=0&rel=0&modestbranding=1';
                return "https://www.youtube.com/embed/{$videoId}?{$params}";
            }
        }

        return null;
    }
}
