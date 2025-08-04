<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExternalTruck extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'driver_name',
        'driver_phone',
        'supplier_id',
        'contract_number',
        'daily_rate',
        'contract_start_date',
        'contract_end_date',
        'loading_type',
        'capacity_volume',
        'capacity_weight',
        'notes',
        'photos',
        'status'
    ];

    protected $casts = [
        'photos' => 'array',
        'capacity_volume' => 'decimal:2',
        'capacity_weight' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date'
    ];

    /**
     * Get the loading type in Arabic
     */
    public function getLoadingTypeTextAttribute(): string
    {
        return match($this->loading_type) {
            'box' => 'صندوق',
            'tank' => 'تانك',
            default => 'غير محدد'
        };
    }

    /**
     * Get the status in Arabic
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشطة',
            'inactive' => 'غير نشطة',
            'maintenance' => 'قيد الصيانة',
            default => 'غير محدد'
        };
    }

    /**
     * Get capacity with unit
     */
    public function getCapacityWithUnitAttribute(): string
    {
        if ($this->loading_type === 'box' && $this->capacity_volume) {
            return number_format((float) $this->capacity_volume, 2) . ' م³';
        } elseif ($this->loading_type === 'tank' && $this->capacity_weight) {
            return number_format((float) $this->capacity_weight, 2) . ' طن';
        }
        return 'غير محدد';
    }

    /**
     * Get the supplier that owns the truck
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Scope for active trucks
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get photo URLs
     */
    public function getPhotoUrlsAttribute(): array
    {
        if (!$this->photos) {
            return [];
        }

        return array_map(function($photo) {
            return asset('storage/' . $photo);
        }, $this->photos);
    }
}
