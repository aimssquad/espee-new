@extends('layouts.admin')

@section('title', 'Create Shape')
@section('page-title', 'Create Shape')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Create Shape</h2>
        <a href="{{ route('admin.shapes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Shapes
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.shapes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <strong>Image Specifications:</strong><br>
                        • <strong>Recommended size:</strong> 400x400 pixels (1:1 aspect ratio)<br>
                        • <strong>Supported formats:</strong> JPG, PNG, WebP<br>
                        • <strong>Max file size:</strong> 2MB<br>
                        • <strong>Background:</strong> Transparent or white background recommended
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Shape
                    </button>
                    <a href="{{ route('admin.shapes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
