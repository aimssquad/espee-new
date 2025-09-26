@extends('layouts.admin')

@section('title', 'Tax Master - Admin Panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tax Master</li>
                    </ol>
                </div>
                <h4 class="page-title">Tax Master</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Tax Rates Management</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.tax-master.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add Tax Rate
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Category/Subcategory</th>
                                    <th>Tax Rate</th>
                                    <th>Type</th>
                                    <th>HSN/SAC Code</th>
                                    <th>Status</th>
                                    <th>Sort Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxRates as $tax)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $tax->name }}</strong>
                                            @if($tax->description)
                                                <br><small class="text-muted">{{ Str::limit($tax->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($tax->subcategory)
                                            <span class="badge bg-info">{{ $tax->subcategory->name }}</span>
                                            <br><small class="text-muted">{{ $tax->category->name }}</small>
                                        @elseif($tax->category)
                                            <span class="badge bg-primary">{{ $tax->category->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success fs-6">{{ $tax->getFormattedTaxRate() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $tax->getTaxTypeLabel() }}</span>
                                    </td>
                                    <td>
                                        @if($tax->hsn_code)
                                            <small class="text-muted">HSN: {{ $tax->hsn_code }}</small>
                                        @endif
                                        @if($tax->sac_code)
                                            <br><small class="text-muted">SAC: {{ $tax->sac_code }}</small>
                                        @endif
                                        @if(!$tax->hsn_code && !$tax->sac_code)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-status"
                                                   type="checkbox"
                                                   data-id="{{ $tax->id }}"
                                                   {{ $tax->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $tax->sort_order }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.tax-master.edit', $tax) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-tax"
                                                    data-id="{{ $tax->id }}"
                                                    data-name="{{ $tax->name }}"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-receipt fa-3x mb-3"></i>
                                            <p>No tax rates found. <a href="{{ route('admin.tax-master.create') }}">Create your first tax rate</a></p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tax Calculator -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tax Calculator</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="calc-amount" class="form-label">Amount (₹)</label>
                                <input type="number" class="form-control" id="calc-amount" placeholder="Enter amount" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="calc-state" class="form-label">Customer State</label>
                                <select class="form-select" id="calc-state">
                                    <option value="">Same State (Gujarat)</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Karnataka">Karnataka</option>
                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                    <option value="Other">Other State</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary d-block" id="calculate-tax">
                                    <i class="fas fa-calculator me-1"></i> Calculate Tax
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="tax-calculations" class="mt-3" style="display: none;">
                        <!-- Tax calculations will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the tax rate "<span id="delete-tax-name"></span>"?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle status
    $('.toggle-status').on('change', function() {
        const taxId = $(this).data('id');
        const isActive = $(this).is(':checked');

        $.ajax({
            url: `{{ url('admin/tax-master') }}/${taxId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                showToast('success', response.success);
            },
            error: function(xhr) {
                showToast('error', 'Error updating tax rate status');
                // Revert the toggle
                $(this).prop('checked', !isActive);
            }.bind(this)
        });
    });

    // Delete tax rate
    $('.delete-tax').on('click', function() {
        const taxId = $(this).data('id');
        const taxName = $(this).data('name');

        $('#delete-tax-name').text(taxName);
        $('#delete-form').attr('action', `{{ url('admin/tax-master') }}/${taxId}`);
        $('#deleteModal').modal('show');
    });

    // Tax calculator
    $('#calculate-tax').on('click', function() {
        const amount = $('#calc-amount').val();
        const customerState = $('#calc-state').val();

        if (!amount || amount <= 0) {
            showToast('error', 'Please enter a valid amount');
            return;
        }

        $.ajax({
            url: '{{ route("admin.tax-master.test-calculation") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                amount: amount,
                customer_state: customerState
            },
            success: function(response) {
                displayTaxCalculations(response);
            },
            error: function(xhr) {
                showToast('error', 'Error calculating tax');
            }
        });
    });

    function displayTaxCalculations(response) {
        let html = `
            <div class="row">
                <div class="col-12">
                    <h6>Tax Calculations for ₹${parseFloat(response.amount).toFixed(2)}</h6>
                    <p class="text-muted small">
                        Customer State: ${response.customer_state || 'Same State (Gujarat)'} |
                        Company State: ${response.company_state}
                    </p>
                </div>
            </div>
            <div class="row">
        `;

        response.calculations.forEach(function(calc) {
            const breakdown = calc.breakdown;
            const tax = calc.tax;

            html += `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="card-title">${tax.name}</h6>
                            <p class="text-muted small mb-2">${tax.category?.name || ''} ${tax.subcategory?.name ? ' - ' + tax.subcategory.name : ''}</p>

                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted">Base Amount:</small><br>
                                    <strong>₹${parseFloat(response.amount).toFixed(2)}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Tax Rate:</small><br>
                                    <strong>${breakdown.tax_rate}%</strong>
                                </div>
                            </div>

                            <hr class="my-2">

                            ${breakdown.is_same_state ? `
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="text-muted">CGST (${breakdown.cgst_rate}%):</small><br>
                                        <strong>₹${breakdown.cgst_amount.toFixed(2)}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">SGST (${breakdown.sgst_rate}%):</small><br>
                                        <strong>₹${breakdown.sgst_amount.toFixed(2)}</strong>
                                    </div>
                                </div>
                            ` : `
                                <div class="row g-2">
                                    <div class="col-12">
                                        <small class="text-muted">IGST (${breakdown.igst_rate}%):</small><br>
                                        <strong>₹${breakdown.igst_amount.toFixed(2)}</strong>
                                    </div>
                                </div>
                            `}

                            ${breakdown.cess_amount > 0 ? `
                                <div class="row g-2 mt-1">
                                    <div class="col-12">
                                        <small class="text-muted">CESS:</small><br>
                                        <strong>₹${breakdown.cess_amount.toFixed(2)}</strong>
                                    </div>
                                </div>
                            ` : ''}

                            <hr class="my-2">

                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted">Total Tax:</small><br>
                                    <strong>₹${breakdown.tax_amount.toFixed(2)}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Total Amount:</small><br>
                                    <strong class="text-success">₹${breakdown.total_amount.toFixed(2)}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        $('#tax-calculations').html(html).show();
    }
});
</script>
@endpush
