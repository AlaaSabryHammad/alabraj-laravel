<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Scope for active revenue types
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
