<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'shape_id',
        'gender',
        'name',
        'slug',
        'model_no',
        'description',
        'base_price'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function shape(): BelongsTo
    {
        return $this->belongsTo(Shape::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function highlights(): HasMany
    {
        return $this->hasMany(ProductHighlight::class)->orderBy('position');
    }

    public function defaultVariant()
    {
        return $this->variants()->with('images')->first();
    }

    public function availableVariants()
    {
        return $this->variants()->where('stock', '>', 0);
    }

    public function getMinPriceAttribute()
    {
        return $this->variants()->min('price') ?? $this->base_price;
    }

    public function getMaxPriceAttribute()
    {
        return $this->variants()->max('price') ?? $this->base_price;
    }

    public function getPriceRangeAttribute()
    {
        $min = $this->min_price;
        $max = $this->max_price;

        if ($min == $max) {
            return '₹' . number_format($min, 2);
        }

        return '₹' . number_format($min, 2) . ' - ₹' . number_format($max, 2);
    }

    public static function getGenderOptions()
    {
        return [
            'men' => 'Men',
            'women' => 'Women',
            'unisex' => 'Unisex'
        ];
    }

    public function getGenderLabelAttribute()
    {
        return self::getGenderOptions()[$this->gender] ?? $this->gender;
    }

    public function getMainImageAttribute()
    {
        $defaultVariant = $this->defaultVariant();
        if ($defaultVariant) {
            return $defaultVariant->image_url;
        }

        // Fallback to placeholder
        return 'https://via.placeholder.com/600x400/000000/FFFFFF?text=' . urlencode($this->name);
    }
}
