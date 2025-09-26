@extends('layouts.admin')

@section('title', 'Edit Payment Method')
@section('page-title', 'Edit Payment Method')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0">Edit Payment Method</h2>
        <p class="text-muted mb-0">Update payment method details</p>
    </div>
    <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Payment Methods
    </a>
</div>

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
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Payment Method Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name *</label>
                                <input type="text" class="form-control @error('display_name') is-invalid @enderror"
                                       id="display_name" name="display_name"
                                       value="{{ old('display_name', $paymentMethod->display_name) }}" required>
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon Class</label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                       id="icon" name="icon"
                                       value="{{ old('icon', $paymentMethod->icon) }}"
                                       placeholder="e.g., fas fa-credit-card">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">FontAwesome icon class (e.g., fas fa-credit-card)</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $paymentMethod->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                       id="sort_order" name="sort_order"
                                       value="{{ old('sort_order', $paymentMethod->sort_order) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Lower numbers appear first</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                           {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                                <div class="form-text">Only active payment methods are shown to customers</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Payment Method
                        </button>
                        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Payment Method Info</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Name:</strong><br>
                    <span class="text-muted">{{ $paymentMethod->name }}</span>
                </div>

                <div class="mb-3">
                    <strong>Slug:</strong><br>
                    <code>{{ $paymentMethod->slug }}</code>
                </div>

                <div class="mb-3">
                    <strong>Type:</strong><br>
                    <span class="badge bg-info">{{ ucfirst($paymentMethod->slug) }}</span>
                </div>

                <div class="mb-3">
                    <strong>Status:</strong><br>
                    <span class="badge {{ $paymentMethod->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Created:</strong><br>
                    <span class="text-muted">{{ $paymentMethod->created_at->format('M d, Y H:i') }}</span>
                </div>

                <div class="mb-3">
                    <strong>Last Updated:</strong><br>
                    <span class="text-muted">{{ $paymentMethod->updated_at->format('M d, Y H:i') }}</span>
                </div>

                @if($paymentMethod->isRazorpay() || $paymentMethod->isPayU() || $paymentMethod->isCOD())
                <div class="mt-4">
                    <a href="{{ route('admin.payment-methods.credentials', $paymentMethod) }}"
                       class="btn btn-outline-primary w-100"
                       onclick="editCredentials({{ $paymentMethod->id }})">
                        <i class="fas fa-key me-2"></i>Manage Credentials
                    </a>
                </div>
                @endif
            </div>
        </div>

        @if($paymentMethod->isRazorpay() || $paymentMethod->isPayU() || $paymentMethod->isCOD())
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-info btn-sm" onclick="editCredentials({{ $paymentMethod->id }})">
                        <i class="fas fa-key me-1"></i>Update Credentials
                    </button>

                    @if($paymentMethod->is_active)
                    <form action="{{ route('admin.payment-methods.toggle-status', $paymentMethod) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                            <i class="fas fa-pause me-1"></i>Deactivate
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.payment-methods.toggle-status', $paymentMethod) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-play me-1"></i>Activate
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Credentials Modal -->
<div class="modal fade" id="credentialsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Method Credentials</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="credentialsForm" method="POST">
                @csrf
                <div class="modal-body" id="credentialsContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Credentials</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editCredentials(paymentMethodId) {
    fetch(`/admin/payment-methods/${paymentMethodId}/credentials`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('credentialsContent').innerHTML = html;
            document.getElementById('credentialsForm').action = `/admin/payment-methods/${paymentMethodId}/credentials`;
            new bootstrap.Modal(document.getElementById('credentialsModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading credentials form');
        });
}
</script>
@endpush
