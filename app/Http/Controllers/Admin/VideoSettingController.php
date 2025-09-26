<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoSettingController extends Controller
{
    public function index()
    {
        $videoSetting = VideoSetting::first();
        return view('admin.video-settings.index', compact('videoSetting'));
    }

    public function create()
    {
        return view('admin.video-settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video_type' => 'required|in:upload,youtube',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:102400', // 100MB max
            'youtube_url' => 'nullable|url',
            'button_text' => 'required|string|max:255',
            'button_url' => 'nullable|url',
            'is_active' => 'boolean',
            'autoplay' => 'boolean',
            'muted' => 'boolean',
            'loop' => 'boolean'
        ]);

        // Handle video file upload
        if ($request->hasFile('video_file')) {
            $validated['video_file'] = $request->file('video_file')->store('videos', 'public');
        }

        // If YouTube URL is provided, clear video_file
        if ($validated['video_type'] === 'youtube') {
            $validated['video_file'] = null;
        }

        // If video file is uploaded, clear YouTube URL
        if ($validated['video_type'] === 'upload' && isset($validated['video_file'])) {
            $validated['youtube_url'] = null;
        }

        VideoSetting::create($validated);

        return redirect()->route('admin.video-settings.index')
            ->with('success', 'Video settings created successfully.');
    }

    public function edit(VideoSetting $videoSetting)
    {
        return view('admin.video-settings.edit', compact('videoSetting'));
    }

    public function update(Request $request, VideoSetting $videoSetting)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video_type' => 'required|in:upload,youtube',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:102400',
            'youtube_url' => 'nullable|url',
            'button_text' => 'required|string|max:255',
            'button_url' => 'nullable|url',
            'is_active' => 'boolean',
            'autoplay' => 'boolean',
            'muted' => 'boolean',
            'loop' => 'boolean'
        ]);

        // Handle video file upload
        if ($request->hasFile('video_file')) {
            // Delete old video file
            if ($videoSetting->video_file) {
                Storage::disk('public')->delete($videoSetting->video_file);
            }
            $validated['video_file'] = $request->file('video_file')->store('videos', 'public');
        }

        // If YouTube URL is provided, clear video_file
        if ($validated['video_type'] === 'youtube') {
            if ($videoSetting->video_file) {
                Storage::disk('public')->delete($videoSetting->video_file);
            }
            $validated['video_file'] = null;
        }

        // If video file is uploaded, clear YouTube URL
        if ($validated['video_type'] === 'upload' && isset($validated['video_file'])) {
            $validated['youtube_url'] = null;
        }

        $videoSetting->update($validated);

        return redirect()->route('admin.video-settings.index')
            ->with('success', 'Video settings updated successfully.');
    }

    public function destroy(VideoSetting $videoSetting)
    {
        if ($videoSetting->video_file) {
            Storage::disk('public')->delete($videoSetting->video_file);
        }

        $videoSetting->delete();

        return redirect()->route('admin.video-settings.index')
            ->with('success', 'Video settings deleted successfully.');
    }
}
