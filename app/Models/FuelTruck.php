<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuelTruck extends Model
{
    protected $fillable = [
        'equipment_id',
        'fuel_type',
        'capacity',
        'current_quantity',
        'notes'
    ];

    protected $casts = [
        'capacity' => 'decimal:2',
        'current_quantity' => 'decimal:2'
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(FuelDistribution::class);
    }

    public function approvedDistributions(): HasMany
    {
        return $this->hasMany(FuelDistribution::class)->where('approval_status', 'approved');
    }

    // Calculate remaining quantity after approved distributions
    public function getRemainingQuantityAttribute()
    {
        $totalDistributed = $this->approvedDistributions()->sum('quantity');
        return $this->current_quantity - $totalDistributed;
    }

    // Get fuel type in Arabic
    public function getFuelTypeTextAttribute()
    {
        $types = [
            'diesel' => 'ديزل',
            'gasoline' => 'بنزين',
            'engine_oil' => 'زيت ماكينة',
            'hydraulic_oil' => 'زيت هيدروليك',
            'radiator_water' => 'ماء ردياتير',
            'brake_oil' => 'زيت فرامل',
            'other' => 'أخرى'
        ];

        return $types[$this->fuel_type] ?? $this->fuel_type;
    }
}
