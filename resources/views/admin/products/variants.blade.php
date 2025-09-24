@extends('layouts.admin')

@section('title', 'Product Variants')
@section('page-title', 'Product Variants - ' . $product->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Variants - {{ $product->name }}</h2>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                <i class="fas fa-plus me-2"></i>Add Variant
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($product->variants->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Images</th>
                            <th>SKU</th>
                            <th>Color</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                        <tr>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @if($variant->images->count() > 0)
                                        @foreach($variant->images->take(3) as $image)
                                            <img src="{{ $image->image_url }}"
                                                 alt="{{ $variant->color->name }}"
                                                 class="img-thumbnail"
                                                 style="width: 40px; height: 40px; object-fit: cover;"
                                                 title="{{ $image->is_primary ? 'Primary Image' : 'Image ' . $image->sort_order + 1 }}">
                                        @endforeach
                                        @if($variant->images->count() > 3)
                                            <div class="bg-secondary d-flex align-items-center justify-content-center rounded"
                                                 style="width: 40px; height: 40px;">
                                                <small class="text-white">+{{ $variant->images->count() - 3 }}</small>
                                            </div>
                                        @endif
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td><code>{{ $variant->sku }}</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="color-preview me-2" style="width: 20px; height: 20px; background-color: {{ $variant->color->hex_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                    {{ $variant->color->name }}
                                </div>
                            </td>
                            <td>${{ number_format($variant->price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $variant->stock > 10 ? 'success' : ($variant->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ $variant->stock }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="editVariant({{ $variant->id }}, '{{ $variant->sku }}', {{ $variant->color_id }}, {{ $variant->price }}, {{ $variant->stock }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.products.delete-variant', [$product, $variant]) }}" class="d-inline">
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
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5>No variants found</h5>
                <p class="text-muted">Add your first product variant to get started.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                    Add Variant
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Add Variant Modal -->
    <div class="modal fade" id="addVariantModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.products.add-variant', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU *</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   id="sku" name="sku" value="{{ old('sku') }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="color_id" class="form-label">Color *</label>
                            <select class="form-control @error('color_id') is-invalid @enderror"
                                    id="color_id" name="color_id" required>
                                <option value="">Select Color</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('color_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price *</label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock *</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                           id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Images (Multiple)</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                   id="images" name="images[]" accept="image/*" multiple>
                            <div class="form-text">You can select multiple images. The first image will be set as primary.</div>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Variant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Variant Modal -->
    <div class="modal fade" id="editVariantModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editVariantForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_sku" class="form-label">SKU *</label>
                            <input type="text" class="form-control" id="edit_sku" name="sku" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_color_id" class="form-label">Color *</label>
                            <select class="form-control" id="edit_color_id" name="color_id" required>
                                <option value="">Select Color</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_price" class="form-label">Price *</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_price" name="price" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_stock" class="form-label">Stock *</label>
                                    <input type="number" class="form-control" id="edit_stock" name="stock" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_images" class="form-label">Images (Multiple)</label>
                            <input type="file" class="form-control" id="edit_images" name="images[]" accept="image/*" multiple>
                            <div class="form-text">You can select multiple images. The first image will be set as primary.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Variant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function editVariant(variantId, sku, colorId, price, stock) {
    document.getElementById('editVariantForm').action = '{{ route("admin.products.update-variant", [$product, ":variant"]) }}'.replace(':variant', variantId);
    document.getElementById('edit_sku').value = sku;
    document.getElementById('edit_color_id').value = colorId;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_stock').value = stock;

    new bootstrap.Modal(document.getElementById('editVariantModal')).show();
}
</script>
@endpush
