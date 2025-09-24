<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'config',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getConfigValue($key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    public function setConfigValue($key, $value)
    {
        $config = $this->config ?? [];
        data_set($config, $key, $value);
        $this->config = $config;
    }
}
