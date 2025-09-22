<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'color_id',
        'price',
        'stock',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        
        // Return placeholder image
        return 'https://via.placeholder.com/600x400/000000/FFFFFF?text=' . urlencode($this->product->name);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function decrementStock($quantity = 1)
    {
        return $this->decrement('stock', $quantity);
    }

    public function incrementStock($quantity = 1)
    {
        return $this->increment('stock', $quantity);
    }
}