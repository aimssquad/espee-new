<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitorAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_url',
        'referrer',
        'country',
        'city',
        'visit_date',
        'visit_time',
        'session_duration',
        'is_unique_visitor'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'visit_time' => 'datetime:H:i:s',
        'session_duration' => 'integer',
        'is_unique_visitor' => 'boolean'
    ];

    public function scopeToday($query)
    {
        return $query->where('visit_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visit_date', now()->month)
                    ->whereYear('visit_date', now()->year);
    }

    public function scopeUniqueVisitors($query)
    {
        return $query->where('is_unique_visitor', true);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('visit_date', [$startDate, $endDate]);
    }
}
