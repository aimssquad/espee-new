@extends('layouts.admin')

@section('title', 'Subcategories')
@section('page-title', 'Subcategories')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Subcategories</h2>
        <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Subcategory
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($subcategories->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subcategories as $subcategory)
                        <tr>
                            <td>
                                @if($subcategory->image)
                                    <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name }}"
                                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $subcategory->name }}</td>
                            <td>{{ $subcategory->category->name }}</td>
                            <td><code>{{ $subcategory->slug }}</code></td>
                            <td>{{ $subcategory->products_count }}</td>
                            <td>{{ $subcategory->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.subcategories.destroy', $subcategory) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h5>No subcategories found</h5>
                <p class="text-muted">Create your first subcategory to organize products better.</p>
                <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary">Create Subcategory</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
