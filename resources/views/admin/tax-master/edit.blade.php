@extends('layouts.admin')

@section('title', 'Edit Tax Rate - Admin Panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tax-master.index') }}">Tax Master</a></li>
                        <li class="breadcrumb-item active">Edit Tax Rate</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Tax Rate</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Tax Rate: {{ $taxMaster->name }}</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.tax-master.update', $taxMaster) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tax Rate Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name', $taxMaster->name) }}" required>
                                    <div class="form-text">e.g., "Sunglasses Tax", "Frames Tax"</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_rate" class="form-label">Tax Rate (%) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="tax_rate" name="tax_rate"
                                           value="{{ old('tax_rate', $taxMaster->tax_rate) }}" step="0.01" min="0" max="100" required>
                                    <div class="form-text">e.g., 18.00 for 18%, 5.00 for 5%</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $taxMaster->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_type" class="form-label">Tax Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="tax_type" name="tax_type" required>
                                        <option value="">Select Tax Type</option>
                                        <option value="gst" {{ old('tax_type', $taxMaster->tax_type) == 'gst' ? 'selected' : '' }}>GST</option>
                                        <option value="vat" {{ old('tax_type', $taxMaster->tax_type) == 'vat' ? 'selected' : '' }}>VAT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order"
                                           value="{{ old('sort_order', $taxMaster->sort_order) }}" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $taxMaster->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subcategory_id" class="form-label">Subcategory</label>
                                    <select class="form-select" id="subcategory_id" name="subcategory_id">
                                        <option value="">Select Subcategory</option>
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}"
                                                    data-category="{{ $subcategory->category_id }}"
                                                    {{ old('subcategory_id', $taxMaster->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hsn_code" class="form-label">HSN Code</label>
                                    <input type="text" class="form-control" id="hsn_code" name="hsn_code"
                                           value="{{ old('hsn_code', $taxMaster->hsn_code) }}" maxlength="20">
                                    <div class="form-text">Harmonized System of Nomenclature</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sac_code" class="form-label">SAC Code</label>
                                    <input type="text" class="form-control" id="sac_code" name="sac_code"
                                           value="{{ old('sac_code', $taxMaster->sac_code) }}" maxlength="20">
                                    <div class="form-text">Service Accounting Code</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       {{ old('is_active', $taxMaster->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Tax Rate
                            </button>
                            <a href="{{ route('admin.tax-master.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tax Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Current Tax Rate</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $taxMaster->name }}</p>
                        <p class="mb-1"><strong>Rate:</strong> {{ $taxMaster->getFormattedTaxRate() }}</p>
                        <p class="mb-1"><strong>Type:</strong> {{ $taxMaster->getTaxTypeLabel() }}</p>
                        <p class="mb-1"><strong>Status:</strong>
                            <span class="badge bg-{{ $taxMaster->is_active ? 'success' : 'danger' }}">
                                {{ $taxMaster->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading">GST Tax Structure</h6>
                        <p class="mb-2">For same state transactions:</p>
                        <ul class="mb-2">
                            <li><strong>CGST:</strong> Central GST (50% of total tax)</li>
                            <li><strong>SGST:</strong> State GST (50% of total tax)</li>
                        </ul>
                        <p class="mb-0">For different state transactions:</p>
                        <ul class="mb-0">
                            <li><strong>IGST:</strong> Integrated GST (100% of total tax)</li>
                        </ul>
                    </div>

                    <div class="alert alert-success">
                        <h6 class="alert-heading">Company Details</h6>
                        <p class="mb-1"><strong>State:</strong> {{ $taxMaster->company_state }}</p>
                        <p class="mb-0"><strong>State Code:</strong> {{ $taxMaster->company_state_code }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filter subcategories based on selected category
    $('#category_id').on('change', function() {
        const selectedCategoryId = $(this).val();
        const subcategorySelect = $('#subcategory_id');

        // Show/hide subcategories based on selected category
        subcategorySelect.find('option').each(function() {
            const categoryId = $(this).data('category');
            if (categoryId == selectedCategoryId || $(this).val() === '') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Auto-calculate CGST/SGST/IGST rates
    $('#tax_rate').on('input', function() {
        const taxRate = parseFloat($(this).val()) || 0;
        const cgstRate = taxRate / 2;
        const sgstRate = taxRate / 2;

        // Update the info panel
        updateTaxBreakdown(taxRate, cgstRate, sgstRate);
    });

    function updateTaxBreakdown(taxRate, cgstRate, sgstRate) {
        let infoHtml = `
            <div class="alert alert-info">
                <h6 class="alert-heading">Tax Breakdown</h6>
                <p class="mb-1"><strong>Total Tax Rate:</strong> ${taxRate}%</p>
                <p class="mb-1"><strong>CGST Rate:</strong> ${cgstRate.toFixed(2)}%</p>
                <p class="mb-1"><strong>SGST Rate:</strong> ${sgstRate.toFixed(2)}%</p>
                <p class="mb-0"><strong>IGST Rate:</strong> ${taxRate}% (for different states)</p>
            </div>
        `;

        // Update or create the breakdown info
        let breakdownDiv = $('#tax-breakdown');
        if (breakdownDiv.length === 0) {
            $('#tax_rate').after('<div id="tax-breakdown"></div>');
            breakdownDiv = $('#tax-breakdown');
        }
        breakdownDiv.html(infoHtml);
    }

    // Initialize breakdown if there's a value
    if ($('#tax_rate').val()) {
        const taxRate = parseFloat($('#tax_rate').val());
        const cgstRate = taxRate / 2;
        const sgstRate = taxRate / 2;
        updateTaxBreakdown(taxRate, cgstRate, sgstRate);
    }
});
</script>
@endpush
