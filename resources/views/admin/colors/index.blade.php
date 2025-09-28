@extends('layouts.admin')

@section('title', 'Colors')
@section('page-title', 'Colors')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Colors</h2>
        <a href="{{ route('admin.colors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Color
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($colors->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Color</th>
                            <th>Hex Code</th>
                            <th>Variants</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($colors as $color)
                        <tr>
                            <td>{{ $color->name }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="color-preview me-2" style="width: 20px; height: 20px; background-color: {{ $color->hex_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                    {{ $color->name }}
                                </div>
                            </td>
                            <td><code>{{ $color->hex_code }}</code></td>
                            <td>{{ $color->product_variants_count }}</td>
                            <td>{{ $color->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.colors.edit', $color) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.colors.destroy', $color) }}" class="d-inline">
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
                <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                <h5>No colors found</h5>
                <p class="text-muted">Create your first color to add variety to your products.</p>
                <a href="{{ route('admin.colors.create') }}" class="btn btn-primary">Create Color</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
