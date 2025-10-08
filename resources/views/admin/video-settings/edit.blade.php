@extends('layouts.admin')

@section('title', 'Edit Video Banner')
@section('page-title', 'Edit Video Banner')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Video Banner Settings</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.video-settings.update', $videoSetting) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $videoSetting->title) }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="button_text" class="form-label">Button Text *</label>
                                <input type="text" class="form-control @error('button_text') is-invalid @enderror"
                                       id="button_text" name="button_text" value="{{ old('button_text', $videoSetting->button_text) }}" required>
                                @error('button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $videoSetting->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="button_url" class="form-label">Button URL</label>
                        <input type="url" class="form-control @error('button_url') is-invalid @enderror"
                               id="button_url" name="button_url" value="{{ old('button_url', $videoSetting->button_url) }}"
                               placeholder="https://example.com">
                        @error('button_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Video Type *</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="video_type" id="upload"
                                           value="upload" {{ old('video_type', $videoSetting->video_type) === 'upload' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="upload">
                                        <i class="fas fa-upload me-2"></i>Upload Video File
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="video_type" id="youtube"
                                           value="youtube" {{ old('video_type', $videoSetting->video_type) === 'youtube' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="youtube">
                                        <i class="fab fa-youtube me-2"></i>YouTube URL
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="upload-section" class="mb-3">
                        <label for="video_file" class="form-label">Video File</label>
                        <input type="file" class="form-control @error('video_file') is-invalid @enderror"
                               id="video_file" name="video_file" accept="video/mp4,video/webm,video/ogg">
                        <div class="form-text">
                            <strong>Supported formats:</strong> MP4, WebM, OGG<br>
                            <strong>Max file size:</strong> 100MB<br>
                            <strong>Recommended dimensions:</strong> 1920x1080 (16:9 aspect ratio)<br>
                            <strong>Recommended duration:</strong> 15-30 seconds for best engagement
                        </div>
                        @if($videoSetting->video_file)
                            <div class="mt-2">
                                <small class="text-muted">Current file: {{ basename($videoSetting->video_file) }}</small>
                            </div>
                        @endif
                        @error('video_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="youtube-section" class="mb-3" style="display: none;">
                        <label for="youtube_url" class="form-label">YouTube URL</label>
                        <input type="url" class="form-control @error('youtube_url') is-invalid @enderror"
                               id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $videoSetting->youtube_url) }}"
                               placeholder="https://www.youtube.com/watch?v=...">
                        <div class="form-text">Enter the full YouTube video URL</div>
                        @error('youtube_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                           value="1" {{ old('is_active', $videoSetting->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="autoplay" name="autoplay"
                                           value="1" {{ old('autoplay', $videoSetting->autoplay) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="autoplay">
                                        Autoplay
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="muted" name="muted"
                                           value="1" {{ old('muted', $videoSetting->muted) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="muted">
                                        Muted
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="loop" name="loop"
                                           value="1" {{ old('loop', $videoSetting->loop) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="loop">
                                        Loop
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.video-settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Video Banner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Current Video</h6>
            </div>
            <div class="card-body">
                @if($videoSetting->video_type === 'youtube' && $videoSetting->youtube_url)
                    <div class="video-preview" style="width: 100%; height: 200px; background: #000; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fab fa-youtube fa-3x"></i>
                    </div>
                    <p class="text-center mt-2 small text-muted">YouTube Video</p>
                @elseif($videoSetting->video_file)
                    <div class="video-preview" style="width: 100%; height: 200px; background: #000; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-play-circle fa-3x"></i>
                    </div>
                    <p class="text-center mt-2 small text-muted">Uploaded Video</p>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No video set
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Settings Status</h6>
            </div>
            <div class="card-body">
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
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadRadio = document.getElementById('upload');
    const youtubeRadio = document.getElementById('youtube');
    const uploadSection = document.getElementById('upload-section');
    const youtubeSection = document.getElementById('youtube-section');

    function toggleSections() {
        if (uploadRadio.checked) {
            uploadSection.style.display = 'block';
            youtubeSection.style.display = 'none';
        } else if (youtubeRadio.checked) {
            uploadSection.style.display = 'none';
            youtubeSection.style.display = 'block';
        }
    }

    uploadRadio.addEventListener('change', toggleSections);
    youtubeRadio.addEventListener('change', toggleSections);

    // Initialize on page load
    toggleSections();
});
</script>
@endpush
@endsection
