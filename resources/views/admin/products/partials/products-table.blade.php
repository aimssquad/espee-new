@if($products->count() > 0)
<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Model No</th>
                <th>Category</th>
                <th>Gender</th>
                <th>Shape</th>
                <th>SKUs</th>
                <th>Base Price</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    <span class="badge bg-secondary">#{{ $product->id }}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            @if($product->variants->first() && $product->variants->first()->image)
                                <img src="{{ asset('storage/' . $product->variants->first()->image) }}"
                                     alt="{{ $product->name }}"
                                     class="rounded"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h6 class="mb-0">{{ Str::limit($product->name, 30) }}</h6>
                            @if($product->description)
                                <small class="text-muted">{{ Str::limit($product->description, 40) }}</small>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <code class="text-primary">{{ $product->model_no }}</code>
                </td>
                <td>
                    <span class="badge bg-info">{{ $product->category->name }}</span>
                    @if($product->subcategory)
                        <br><small class="text-muted">{{ $product->subcategory->name }}</small>
                    @endif
                </td>
                <td>
                    @if($product->gender)
                        <span class="badge bg-{{ $product->gender == 'men' ? 'primary' : ($product->gender == 'women' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($product->gender) }}
                        </span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if($product->shape)
                        <span class="badge bg-warning">{{ $product->shape->name }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-success">{{ $product->variants_count }} SKUs</span>
                </td>
                <td>
                    <strong class="text-success">${{ number_format($product->base_price, 2) }}</strong>
                </td>
                <td>
                    <small class="text-muted">{{ $product->created_at->format('M d, Y') }}</small>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.products.variants', $product) }}"
                           class="btn btn-sm btn-outline-primary"
                           title="Manage SKUs">
                            <i class="fas fa-boxes"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="btn btn-sm btn-outline-dark"
                           title="Edit Product">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure? This will delete the product and all its variants.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Delete Product">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-5">
    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
    <h5>No products found</h5>
    <p class="text-muted">Start by adding your first product.</p>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Product
    </a>
</div>
@endif
