<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxMaster extends Model
{
    use HasFactory;

    protected $table = 'tax_master';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'tax_rate',
        'tax_type',
        'is_active',
        'sort_order',
        'category_id',
        'subcategory_id',
        'hsn_code',
        'sac_code',
        'cgst_rate',
        'sgst_rate',
        'igst_rate',
        'cess_rate',
        'company_state',
        'company_state_code',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'cgst_rate' => 'decimal:2',
        'sgst_rate' => 'decimal:2',
        'igst_rate' => 'decimal:2',
        'cess_rate' => 'decimal:2',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Tax calculation methods
    public function calculateTax($amount, $customerState = null)
    {
        $taxAmount = ($amount * $this->tax_rate) / 100;

        $breakdown = [
            'tax_rate' => $this->tax_rate,
            'tax_amount' => $taxAmount,
            'total_amount' => $amount + $taxAmount,
            'is_same_state' => $this->isSameState($customerState),
        ];

        if ($this->isSameState($customerState)) {
            // Same state - use CGST + SGST
            $cgstAmount = $taxAmount / 2;
            $sgstAmount = $taxAmount / 2;

            $breakdown['cgst_rate'] = $this->tax_rate / 2;
            $breakdown['sgst_rate'] = $this->tax_rate / 2;
            $breakdown['cgst_amount'] = $cgstAmount;
            $breakdown['sgst_amount'] = $sgstAmount;
            $breakdown['igst_rate'] = 0;
            $breakdown['igst_amount'] = 0;
        } else {
            // Different state - use IGST
            $breakdown['cgst_rate'] = 0;
            $breakdown['sgst_rate'] = 0;
            $breakdown['cgst_amount'] = 0;
            $breakdown['sgst_amount'] = 0;
            $breakdown['igst_rate'] = $this->tax_rate;
            $breakdown['igst_amount'] = $taxAmount;
        }

        // Add CESS if applicable
        if ($this->cess_rate > 0) {
            $cessAmount = ($amount * $this->cess_rate) / 100;
            $breakdown['cess_amount'] = $cessAmount;
            $breakdown['total_amount'] += $cessAmount;
        } else {
            $breakdown['cess_amount'] = 0;
        }

        return $breakdown;
    }

    public function isSameState($customerState = null)
    {
        if (!$customerState) {
            return true; // Default to same state if not specified
        }

        return strtolower($customerState) === strtolower($this->company_state);
    }

    // Static methods for easy access
    public static function getTaxForProduct($product, $customerState = null)
    {
        // First try to find tax by subcategory
        if ($product->subcategory_id) {
            $tax = self::active()
                ->where('subcategory_id', $product->subcategory_id)
                ->first();

            if ($tax) {
                return $tax;
            }
        }

        // Then try by category
        if ($product->category_id) {
            $tax = self::active()
                ->where('category_id', $product->category_id)
                ->first();

            if ($tax) {
                return $tax;
            }
        }

        // Return default tax (you can set this)
        return self::active()->where('slug', 'default-tax')->first();
    }

    public static function getTaxBreakdown($product, $amount, $customerState = null)
    {
        $tax = self::getTaxForProduct($product, $customerState);

        if (!$tax) {
            return [
                'tax_rate' => 0,
                'tax_amount' => 0,
                'total_amount' => $amount,
                'is_same_state' => true,
                'cgst_rate' => 0,
                'sgst_rate' => 0,
                'igst_rate' => 0,
                'cgst_amount' => 0,
                'sgst_amount' => 0,
                'igst_amount' => 0,
                'cess_amount' => 0,
            ];
        }

        return $tax->calculateTax($amount, $customerState);
    }

    // Helper methods
    public function getFormattedTaxRate()
    {
        return $this->tax_rate . '%';
    }

    public function getTaxTypeLabel()
    {
        return match($this->tax_type) {
            'gst' => 'GST',
            'vat' => 'VAT',
            default => strtoupper($this->tax_type)
        };
    }
}
