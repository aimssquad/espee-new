@extends('layouts.admin')

@section('title', 'Video Settings')
@section('page-title', 'Video Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Video Banner Settings</h2>
    @if($videoSetting)
        <a href="{{ route('admin.video-settings.edit', $videoSetting) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Edit Settings
        </a>
    @else
        <a href="{{ route('admin.video-settings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Video Banner
        </a>
    @endif
</div>

@if($videoSetting)
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Current Video Banner</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Title:</h6>
                        <p>{{ $videoSetting->title ?: 'No title set' }}</p>

                        <h6>Description:</h6>
                        <p>{{ $videoSetting->description ?: 'No description set' }}</p>

                        <h6>Video Type:</h6>
                        <span class="badge bg-{{ $videoSetting->video_type === 'youtube' ? 'danger' : 'primary' }}">
                            {{ ucfirst($videoSetting->video_type) }}
                        </span>

                        <h6 class="mt-3">Button Settings:</h6>
                        <p><strong>Text:</strong> {{ $videoSetting->button_text }}</p>
                        <p><strong>URL:</strong> {{ $videoSetting->button_url ?: 'No URL set' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Video Settings:</h6>
                        <div class="mb-2">
                            <span class="badge bg-{{ $videoSetting->is_active ? 'success' : 'danger' }}">
                                {{ $videoSetting->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-{{ $videoSetting->autoplay ? 'info' : 'secondary' }}">
                                Autoplay: {{ $videoSetting->autoplay ? 'On' : 'Off' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-{{ $videoSetting->muted ? 'info' : 'secondary' }}">
                                Muted: {{ $videoSetting->muted ? 'On' : 'Off' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-{{ $videoSetting->loop ? 'info' : 'secondary' }}">
                                Loop: {{ $videoSetting->loop ? 'On' : 'Off' }}
                            </span>
                        </div>

                        @if($videoSetting->video_type === 'youtube')
                            <h6 class="mt-3">YouTube URL:</h6>
                            <p><a href="{{ $videoSetting->youtube_url }}" target="_blank">{{ $videoSetting->youtube_url }}</a></p>
                        @elseif($videoSetting->video_file)
                            <h6 class="mt-3">Video File:</h6>
                            <p>{{ $videoSetting->video_file }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Preview</h6>
            </div>
            <div class="card-body">
                @if($videoSetting->is_active)
                    <div class="video-preview" style="width: 100%; height: 200px; background: #000; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                        @if($videoSetting->video_type === 'youtube')
                            <i class="fab fa-youtube fa-3x"></i>
                        @else
                            <i class="fas fa-play-circle fa-3x"></i>
                        @endif
                    </div>
                    <p class="text-center mt-2 small text-muted">Video will be displayed here on homepage</p>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Video banner is currently inactive
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.video-settings.edit', $videoSetting) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Settings
                    </a>
                    <form action="{{ route('admin.video-settings.destroy', $videoSetting) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return confirm('Are you sure you want to delete the video banner?')">
                            <i class="fas fa-trash me-2"></i>Delete Banner
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="text-center py-5">
    <i class="fas fa-video fa-4x text-muted mb-4"></i>
    <h4>No Video Banner Set</h4>
    <p class="text-muted mb-4">Create a video banner to display on your homepage</p>
    <a href="{{ route('admin.video-settings.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-plus me-2"></i>Create Video Banner
    </a>
</div>
@endif
@endsection
