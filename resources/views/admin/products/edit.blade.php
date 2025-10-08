@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Product</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Products
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="model_no" class="form-label">Model Number *</label>
                            <input type="text" class="form-control @error('model_no') is-invalid @enderror"
                                   id="model_no" name="model_no" value="{{ old('model_no', $product->model_no) }}" required>
                            @error('model_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category *</label>
                            <select class="form-control @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender *</label>
                            <select class="form-control @error('gender') is-invalid @enderror"
                                    id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                @foreach($genderOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('gender', $product->gender) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="subcategory_id" class="form-label">Subcategory</label>
                            <select class="form-control @error('subcategory_id') is-invalid @enderror"
                                    id="subcategory_id" name="subcategory_id">
                                <option value="">Select Subcategory</option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subcategory_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="shape_id" class="form-label">Shape</label>
                            <select class="form-control @error('shape_id') is-invalid @enderror"
                                    id="shape_id" name="shape_id">
                                <option value="">Select Shape</option>
                                @foreach($shapes as $shape)
                                    <option value="{{ $shape->id }}" {{ old('shape_id', $product->shape_id) == $shape->id ? 'selected' : '' }}>
                                        {{ $shape->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shape_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="base_price" class="form-label">Base Price *</label>
                    <input type="number" step="0.01" class="form-control @error('base_price') is-invalid @enderror"
                           id="base_price" name="base_price" value="{{ old('base_price', $product->base_price) }}" required>
                    @error('base_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Highlights Section (Separate Card) -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Product Highlights (Zigzag Sections)</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.products.highlights.store', $product) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Premium Build Quality" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Position</label>
                        <input type="number" name="position" class="form-control" value="{{ ($product->highlights->max('position') ?? -1) + 1 }}" min="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Text</label>
                        <textarea name="text" class="form-control" rows="3" placeholder="Short description..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-text">Recommended: 900x600px, JPG/PNG/WebP, max 3MB</div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Highlight</button>
                    </div>
                </div>
            </form>

            @if($product->highlights && $product->highlights->count())
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>Preview</th>
                            <th>Title</th>
                            <th>Text</th>
                            <th>Active</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->highlights->sortBy('position') as $hl)
                        <tr>
                            <td>{{ $hl->position }}</td>
                            <td style="width: 140px;">
                                <img src="{{ $hl->image_url }}" class="img-fluid rounded" alt="hl" style="width: 120px; height: 80px; object-fit: cover;">
                            </td>
                            <td>{{ $hl->title }}</td>
                            <td class="text-muted" style="max-width: 360px;">{{ Str::limit($hl->text, 120) }}</td>
                            <td>
                                @if($hl->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.products.highlights.destroy', $hl) }}" method="POST" onsubmit="return confirm('Delete this highlight?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
        </div>
    </div>
@endsection
