@extends('layouts.admin')

@section('title', 'Shapes')
@section('page-title', 'Shapes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Shapes</h2>
        <a href="{{ route('admin.shapes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Shape
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($shapes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Products</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shapes as $shape)
                        <tr>
                            <td>
                                @if($shape->image)
                                    <img src="{{ asset('storage/' . $shape->image) }}" alt="{{ $shape->name }}"
                                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $shape->name }}</td>
                            <td>{{ $shape->products_count }}</td>
                            <td>{{ $shape->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.shapes.edit', $shape) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.shapes.destroy', $shape) }}" class="d-inline">
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
                <i class="fas fa-shapes fa-3x text-muted mb-3"></i>
                <h5>No shapes found</h5>
                <p class="text-muted">Create your first shape to categorize your products.</p>
                <a href="{{ route('admin.shapes.create') }}" class="btn btn-primary">Create Shape</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
