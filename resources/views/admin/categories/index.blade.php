@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="admin-header d-flex justify-content-between align-items-center">
    <h1 class="h3 mb-0">Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-dark">
        <i class="fas fa-plus me-2"></i>Add Category
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($categories->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td>{{ $category->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-dark">
                                Edit
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $categories->links() }}
        @else
        <p class="text-center text-muted mb-0">No categories found</p>
        @endif
    </div>
</div>
@endsection