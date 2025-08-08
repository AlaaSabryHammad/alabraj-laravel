<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExternalTruck extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'plate_number',
        'driver_name',
        'driver_phone',
        'contract_number',
        'daily_rate',
        'contract_start_date',
        'contract_end_date',
        'notes',
        'photos',
        'status'
    ];

    protected $casts = [
        'photos' => 'array',
        'daily_rate' => 'decimal:2',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date'
    ];

    /**
     * Get the status in Arabic
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'active' => 'نشطة',
            'inactive' => 'غير نشطة',
            'maintenance' => 'قيد الصيانة',
            default => 'غير محدد'
        };
    }

    /**
     * Check if contract is active
     */
    public function getContractStatusAttribute(): string
    {
        if (!$this->contract_start_date || !$this->contract_end_date) {
            return 'غير محدد';
        }

        $today = now()->startOfDay();
        $startDate = $this->contract_start_date->startOfDay();
        $endDate = $this->contract_end_date->startOfDay();

        if ($today < $startDate) {
            return 'لم يبدأ';
        } elseif ($today > $endDate) {
            return 'منتهي';
        } else {
            return 'نشط';
        }
    }

    /**
     * Get contract status with color class
     */
    public function getContractStatusColorAttribute(): string
    {
        return match ($this->contract_status) {
            'نشط' => 'text-green-600 bg-green-100',
            'منتهي' => 'text-red-600 bg-red-100',
            'لم يبدأ' => 'text-yellow-600 bg-yellow-100',
            default => 'text-gray-600 bg-gray-100'
        };
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

        return array_map(function ($photo) {
            return asset('storage/' . $photo);
        }, $this->photos);
    }
}
