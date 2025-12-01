<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EquipmentFuelConsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'fuel_truck_id',
        'user_id',
        'fuel_type',
        'quantity',
        'consumption_date',
        'notes',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    protected $casts = [
        'consumption_date' => 'date',
        'quantity' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // العلاقات
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function fuelTruck()
    {
        return $this->belongsTo(Equipment::class, 'fuel_truck_id');
    }

    // خصائص محسوبة
    public function getFuelTypeTextAttribute()
    {
        return match ($this->fuel_type) {
            'diesel' => 'ديزل',
            'engine_oil' => 'زيت ماكينة',
            'hydraulic_oil' => 'زيت هيدروليك',
            'radiator_water' => 'ماء ردياتير',
            default => $this->fuel_type,
        };
    }

    public function getFuelTypeColorAttribute()
    {
        return match ($this->fuel_type) {
            'diesel' => 'bg-blue-100 text-blue-800',
            'engine_oil' => 'bg-yellow-100 text-yellow-800',
            'hydraulic_oil' => 'bg-purple-100 text-purple-800',
            'radiator_water' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getApprovalStatusTextAttribute()
    {
        return match ($this->approval_status) {
            'pending' => 'في الانتظار',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
            default => $this->approval_status,
        };
    }

    public function getApprovalStatusColorAttribute()
    {
        return match ($this->approval_status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Scopes
    public function scopeByEquipment($query, $equipmentId)
    {
        return $query->where('equipment_id', $equipmentId);
    }

    public function scopeByFuelType($query, $fuelType)
    {
        return $query->where('fuel_type', $fuelType);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('consumption_date', [$startDate, $endDate]);
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }
}
