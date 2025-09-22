@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="admin-header d-flex justify-content-between align-items-center">
    <h1 class="h3 mb-0">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-dark">
        <i class="fas fa-plus me-2"></i>Add Product
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Model No</th>
                        <th>Category</th>
                        <th>Shape</th>
                        <th>SKUs</th>
                        <th>Base Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->model_no }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->shape ? $product->shape->name : '-' }}</td>
                        <td>{{ $product->variants_count }}</td>
                        <td>${{ number_format($product->base_price, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.products.variants', $product) }}" class="btn btn-sm btn-outline-info">
                                SKUs
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-dark">
                                Edit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure? This will delete all variants.')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
        @else
        <p class="text-center text-muted mb-0">No products found</p>
        @endif
    </div>
</div>
@endsection