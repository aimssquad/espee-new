<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodMaster extends Model
{
    use HasFactory;

    protected $table = 'payment_methods_master';

    protected $fillable = [
        'name',
        'slug',
        'display_name',
        'description',
        'icon',
        'is_active',
        'sort_order',
        'credentials',
        'settings',
        'razorpay_key_id',
        'razorpay_key_secret',
        'razorpay_test_mode',
        'payu_merchant_key',
        'payu_merchant_salt',
        'payu_authorization_header',
        'payu_test_mode',
        'cod_minimum_amount',
        'cod_maximum_amount',
        'cod_instructions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credentials' => 'array',
        'settings' => 'array',
        'razorpay_test_mode' => 'boolean',
        'payu_test_mode' => 'boolean',
        'cod_minimum_amount' => 'decimal:2',
        'cod_maximum_amount' => 'decimal:2',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getFormattedCredentialsAttribute()
    {
        if (!$this->credentials) {
            return [];
        }

        $formatted = [];
        foreach ($this->credentials as $key => $value) {
            if (str_contains(strtolower($key), 'secret') || str_contains(strtolower($key), 'password')) {
                $formatted[$key] = str_repeat('*', strlen($value));
            } else {
                $formatted[$key] = $value;
            }
        }

        return $formatted;
    }

    // Methods
    public function isRazorpay()
    {
        return $this->slug === 'razorpay';
    }

    public function isPayU()
    {
        return $this->slug === 'payu';
    }

    public function isCOD()
    {
        return $this->slug === 'cod';
    }

    public function getCredentialsForGateway()
    {
        if ($this->isRazorpay()) {
            return [
                'key_id' => $this->razorpay_key_id,
                'key_secret' => $this->razorpay_key_secret,
                'test_mode' => $this->razorpay_test_mode,
            ];
        }

        if ($this->isPayU()) {
            return [
                'merchant_key' => $this->payu_merchant_key,
                'merchant_salt' => $this->payu_merchant_salt,
                'authorization_header' => $this->payu_authorization_header,
                'test_mode' => $this->payu_test_mode,
            ];
        }

        if ($this->isCOD()) {
            return [
                'minimum_amount' => $this->cod_minimum_amount,
                'maximum_amount' => $this->cod_maximum_amount,
                'instructions' => $this->cod_instructions,
            ];
        }

        return [];
    }

    public function updateCredentials(array $credentials)
    {
        if ($this->isRazorpay()) {
            $this->update([
                'razorpay_key_id' => $credentials['key_id'] ?? $this->razorpay_key_id,
                'razorpay_key_secret' => $credentials['key_secret'] ?? $this->razorpay_key_secret,
                'razorpay_test_mode' => $credentials['test_mode'] ?? $this->razorpay_test_mode,
            ]);
        }

        if ($this->isPayU()) {
            $this->update([
                'payu_merchant_key' => $credentials['merchant_key'] ?? $this->payu_merchant_key,
                'payu_merchant_salt' => $credentials['merchant_salt'] ?? $this->payu_merchant_salt,
                'payu_authorization_header' => $credentials['authorization_header'] ?? $this->payu_authorization_header,
                'payu_test_mode' => $credentials['test_mode'] ?? $this->payu_test_mode,
            ]);
        }

        if ($this->isCOD()) {
            $this->update([
                'cod_minimum_amount' => $credentials['minimum_amount'] ?? $this->cod_minimum_amount,
                'cod_maximum_amount' => $credentials['maximum_amount'] ?? $this->cod_maximum_amount,
                'cod_instructions' => $credentials['instructions'] ?? $this->cod_instructions,
            ]);
        }
    }

    // Static methods for frontend
    public static function getActivePaymentMethods()
    {
        return self::active()->ordered()->get();
    }

    public static function getActivePaymentMethodsForOrder($orderAmount = 0)
    {
        $methods = self::active()->ordered()->get();

        return $methods->filter(function ($method) use ($orderAmount) {
            if ($method->isCOD()) {
                // Check COD amount limits
                if ($method->cod_minimum_amount && $orderAmount < $method->cod_minimum_amount) {
                    return false;
                }
                if ($method->cod_maximum_amount && $orderAmount > $method->cod_maximum_amount) {
                    return false;
                }
            }
            return true;
        });
    }
}
