@extends('layouts.admin')

@section('title', 'Payment Methods')
@section('page-title', 'Payment Methods Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0">Payment Methods</h2>
        <p class="text-muted mb-0">Manage payment gateways and methods</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Payment Method
        </a>
    </div>
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

<!-- Payment Methods Cards -->
<div class="row" id="paymentMethodsContainer">
    @foreach($paymentMethods as $paymentMethod)
    <div class="col-lg-4 col-md-6 mb-4" data-id="{{ $paymentMethod->id }}">
        <div class="card payment-method-card h-100 {{ $paymentMethod->is_active ? 'border-success' : 'border-secondary' }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    @if($paymentMethod->icon)
                        <i class="{{ $paymentMethod->icon }} me-2 text-primary"></i>
                    @endif
                    <h6 class="mb-0">{{ $paymentMethod->display_name }}</h6>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox"
                           {{ $paymentMethod->is_active ? 'checked' : '' }}
                           onchange="toggleStatus({{ $paymentMethod->id }})">
                </div>
            </div>

            <div class="card-body">
                <p class="text-muted small mb-3">{{ $paymentMethod->description }}</p>

                <!-- Status Badge -->
                <div class="mb-3">
                    <span class="badge {{ $paymentMethod->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="badge bg-info ms-1">{{ ucfirst($paymentMethod->slug) }}</span>
                </div>

                <!-- Credentials Status -->
                <div class="mb-3">
                    @if($paymentMethod->isRazorpay())
                        <small class="text-muted">
                            <i class="fas fa-key me-1"></i>
                            @if($paymentMethod->razorpay_key_id)
                                Key ID: {{ substr($paymentMethod->razorpay_key_id, 0, 8) }}...
                            @else
                                <span class="text-danger">Credentials not set</span>
                            @endif
                        </small>
                    @elseif($paymentMethod->isPayU())
                        <small class="text-muted">
                            <i class="fas fa-key me-1"></i>
                            @if($paymentMethod->payu_merchant_key)
                                Merchant Key: {{ substr($paymentMethod->payu_merchant_key, 0, 8) }}...
                            @else
                                <span class="text-danger">Credentials not set</span>
                            @endif
                        </small>
                    @elseif($paymentMethod->isCOD())
                        <small class="text-muted">
                            <i class="fas fa-money-bill-wave me-1"></i>
                            @if($paymentMethod->cod_minimum_amount || $paymentMethod->cod_maximum_amount)
                                Amount: ₹{{ $paymentMethod->cod_minimum_amount ?? 0 }} - ₹{{ $paymentMethod->cod_maximum_amount ?? '∞' }}
                            @else
                                No amount limits set
                            @endif
                        </small>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm flex-fill"
                            onclick="editCredentials({{ $paymentMethod->id }})">
                        <i class="fas fa-key me-1"></i>Credentials
                    </button>
                    <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if(!in_array($paymentMethod->slug, ['razorpay', 'payu', 'cod']))
                    <form action="{{ route('admin.payment-methods.destroy', $paymentMethod) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this payment method?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
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

@push('styles')
<style>
.payment-method-card {
    transition: all 0.3s ease;
    border-left: 4px solid #dee2e6;
}

.payment-method-card.border-success {
    border-left-color: #28a745;
}

.payment-method-card.border-secondary {
    border-left-color: #6c757d;
}

.payment-method-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}
</style>
@endpush

@push('scripts')
<script>
function toggleStatus(paymentMethodId) {
    fetch(`/admin/payment-methods/${paymentMethodId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating payment method status');
    });
}

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

// Make payment methods sortable
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('paymentMethodsContainer');
    if (container) {
        new Sortable(container, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const order = Array.from(container.children).map(card => card.dataset.id);
                fetch('/admin/payment-methods/reorder', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ order: order })
                });
            }
        });
    }
});
</script>
@endpush
