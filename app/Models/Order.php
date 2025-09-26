<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'subtotal',
        'tax_amount',
        'tax_rate',
        'tax_type',
        'cgst_rate',
        'sgst_rate',
        'igst_rate',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'coupon_id',
        'status',
        'notes',
        'tracking_number',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'cgst_rate' => 'decimal:2',
        'sgst_rate' => 'decimal:2',
        'igst_rate' => 'decimal:2',
        'cgst_amount' => 'decimal:2',
        'sgst_amount' => 'decimal:2',
        'igst_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'confirmed' => '<span class="badge bg-info">Confirmed</span>',
            'shipped' => '<span class="badge bg-primary">Shipped</span>',
            'delivered' => '<span class="badge bg-success">Delivered</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    public function getTrackingStatusAttribute()
    {
        return match($this->status) {
            'pending' => 'Order received and being processed',
            'confirmed' => 'Order confirmed and being prepared for shipment',
            'shipped' => 'Order shipped and in transit',
            'delivered' => 'Order delivered successfully',
            'cancelled' => 'Order has been cancelled',
            default => 'Unknown status',
        };
    }

    public function getTrackingProgressAttribute()
    {
        return match($this->status) {
            'pending' => 25,
            'confirmed' => 50,
            'shipped' => 75,
            'delivered' => 100,
            'cancelled' => 0,
            default => 0,
        };
    }

    public function updateStatus($status, $trackingNumber = null)
    {
        $this->status = $status;

        if ($status === 'shipped' && $trackingNumber) {
            $this->tracking_number = $trackingNumber;
            $this->shipped_at = now();
        }

        if ($status === 'delivered') {
            $this->delivered_at = now();
        }

        $this->save();
    }
}
