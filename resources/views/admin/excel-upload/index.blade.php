@extends('layouts.admin')

@section('title', 'CSV Upload')
@section('page-title', 'Bulk Product Upload')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Upload Products via CSV</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Upload Form -->
                    <div class="col-lg-8">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Upload CSV File</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.excel-upload.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="excel_file" class="form-label">Select CSV File</label>
                                        <input type="file" class="form-control @error('excel_file') is-invalid @enderror"
                                               id="excel_file" name="excel_file"
                                               accept=".csv,text/csv,application/csv" required>
                                        @error('excel_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Supported format: .csv (Max size: 10MB)
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="uploadBtn">
                                            <i class="fas fa-upload me-2"></i>Upload Products
                                        </button>
                                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Back to Products
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="col-lg-4">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Instructions</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-primary">Step 1: Download Template</h6>
                                    <p class="small text-muted mb-2">Download the CSV template to see the required format.</p>
                                    <a href="{{ route('admin.excel-upload.template') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>Download Template
                                    </a>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-primary">Step 2: Fill Data</h6>
                                    <ul class="small text-muted mb-0">
                                        <li>Fill in product information</li>
                                        <li>Add variant details (SKU, color, price, stock)</li>
                                        <li>Include image URLs (comma separated)</li>
                                    </ul>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-primary">Step 3: Upload</h6>
                                    <p class="small text-muted mb-0">Upload the filled CSV file to create products and variants.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Required Fields -->
                        <div class="card border mt-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Required Fields</h6>
                            </div>
                            <div class="card-body">
                                <div class="small">
                                    <strong>Product:</strong>
                                    <ul class="mb-2">
                                        <li>Product Name</li>
                                        <li>Model Number</li>
                                        <li>Category</li>
                                        <li>Color</li>
                                        <li>SKU</li>
                                    </ul>

                                    <strong>Optional:</strong>
                                    <ul class="mb-0">
                                        <li>Description</li>
                                        <li>Subcategory</li>
                                        <li>Shape</li>
                                        <li>Gender (men/women/unisex)</li>
                                        <li>Base Price</li>
                                        <li>Price</li>
                                        <li>Stock</li>
                                        <li>Image URLs</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Features</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="small">Automatic image downloading</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="small">Category/Subcategory creation</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="small">Color/Shape management</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="small">Product update or create</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="small">Variant management</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="small">Multiple image support</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="small">Error handling & logging</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="small">Transaction safety</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="small">Progress tracking</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    const uploadBtn = document.getElementById('uploadBtn');
    const fileInput = document.getElementById('excel_file');

    uploadForm.addEventListener('submit', function(e) {
        if (fileInput.files.length === 0) {
            e.preventDefault();
            alert('Please select a file to upload.');
            return;
        }

        // Show loading state
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
    });

    // File size validation
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                alert('File size must be less than 10MB.');
                this.value = '';
                return;
            }

            // Show file info
            const fileInfo = document.createElement('div');
            fileInfo.className = 'form-text text-success';
            fileInfo.innerHTML = `<i class="fas fa-file me-1"></i>Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;

            // Remove previous file info
            const existingInfo = this.parentNode.querySelector('.form-text.text-success');
            if (existingInfo) {
                existingInfo.remove();
            }

            this.parentNode.appendChild(fileInfo);
        }
    });
});
</script>
@endpush
