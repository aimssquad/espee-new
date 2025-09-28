@extends('layouts.admin')

@section('title', 'Coupons')
@section('page-title', 'Coupons')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Coupons</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Coupon
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($coupons->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Usage</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                        <tr>
                            <td><code>{{ $coupon->code }}</code></td>
                            <td>{{ $coupon->name }}</td>
                            <td>
                                <span class="badge bg-{{ $coupon->type === 'percentage' ? 'info' : 'success' }}">
                                    {{ ucfirst($coupon->type) }}
                                </span>
                            </td>
                            <td>
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                @else
                                    ${{ number_format($coupon->value, 2) }}
                                @endif
                            </td>
                            <td>{{ $coupon->used_count }}/{{ $coupon->usage_limit ?? '∞' }}</td>
                            <td>
                                <span class="badge bg-{{ $coupon->is_active ? 'success' : 'danger' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline">
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
                <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                <h5>No coupons found</h5>
                <p class="text-muted">Create your first coupon to start offering discounts.</p>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">Create Coupon</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
