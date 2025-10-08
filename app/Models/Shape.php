<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shape extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shape) {
            if (empty($shape->slug)) {
                $shape->slug = Str::slug($shape->name);
            }
        });

        static::updating(function ($shape) {
            if ($shape->isDirty('name') && empty($shape->slug)) {
                $shape->slug = Str::slug($shape->name);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
