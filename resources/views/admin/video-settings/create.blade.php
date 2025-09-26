@extends('layouts.admin')

@section('title', 'Create Video Banner')
@section('page-title', 'Create Video Banner')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Video Banner Settings</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.video-settings.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="button_text" class="form-label">Button Text *</label>
                                <input type="text" class="form-control @error('button_text') is-invalid @enderror"
                                       id="button_text" name="button_text" value="{{ old('button_text', 'Shop Now') }}" required>
                                @error('button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="button_url" class="form-label">Button URL</label>
                        <input type="url" class="form-control @error('button_url') is-invalid @enderror"
                               id="button_url" name="button_url" value="{{ old('button_url') }}"
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
                                           value="upload" {{ old('video_type', 'upload') === 'upload' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="upload">
                                        <i class="fas fa-upload me-2"></i>Upload Video File
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="video_type" id="youtube"
                                           value="youtube" {{ old('video_type') === 'youtube' ? 'checked' : '' }}>
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
                        <div class="form-text">Supported formats: MP4, WebM, OGG. Max size: 100MB</div>
                        @error('video_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="youtube-section" class="mb-3" style="display: none;">
                        <label for="youtube_url" class="form-label">YouTube URL</label>
                        <input type="url" class="form-control @error('youtube_url') is-invalid @enderror"
                               id="youtube_url" name="youtube_url" value="{{ old('youtube_url') }}"
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
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
                                           value="1" {{ old('autoplay', true) ? 'checked' : '' }}>
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
                                           value="1" {{ old('muted', true) ? 'checked' : '' }}>
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
                                           value="1" {{ old('loop', true) ? 'checked' : '' }}>
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
                            <i class="fas fa-save me-2"></i>Create Video Banner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Preview</h6>
            </div>
            <div class="card-body">
                <div class="video-preview" style="width: 100%; height: 200px; background: #000; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                    <i class="fas fa-video fa-3x"></i>
                </div>
                <p class="text-center mt-2 small text-muted">Video preview will appear here</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Tips</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Use high-quality videos for best results</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Keep videos under 30 seconds for better engagement</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>YouTube videos load faster than uploaded files</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Muted autoplay works better on mobile devices</li>
                </ul>
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
