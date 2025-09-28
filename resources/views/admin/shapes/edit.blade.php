@extends('layouts.admin')

@section('title', 'Edit Shape')
@section('page-title', 'Edit Shape')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Shape</h2>
        <a href="{{ route('admin.shapes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Shapes
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.shapes.update', $shape) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $shape->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    @if($shape->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $shape->image) }}" alt="{{ $shape->name }}"
                                 class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            <div class="form-text">Current image</div>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Upload a new image for this shape (optional, max 2MB)</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Shape
                    </button>
                    <a href="{{ route('admin.shapes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
