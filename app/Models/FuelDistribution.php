<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class FuelDistribution extends Model
{
    protected $fillable = [
        'fuel_truck_id',
        'target_equipment_id',
        'distributed_by',
        'fuel_type',
        'quantity',
        'distribution_date',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'distribution_date' => 'date',
        'approved_at' => 'datetime'
    ];

    public function fuelTruck(): BelongsTo
    {
        return $this->belongsTo(FuelTruck::class);
    }

    public function targetEquipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'target_equipment_id');
    }

    public function distributedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'distributed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
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

    // Get status in Arabic
    public function getApprovalStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'في انتظار الموافقة',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض'
        ];

        return $statuses[$this->approval_status] ?? $this->approval_status;
    }

    // Get status color class
    public function getApprovalStatusColorAttribute()
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800'
        ];

        return $colors[$this->approval_status] ?? 'bg-gray-100 text-gray-800';
    }

    // Format distribution date in Arabic
    public function getDistributionDateFormattedAttribute()
    {
        return Carbon::parse($this->distribution_date)->locale('ar')->isoFormat('dddd، D MMMM YYYY');
    }
}
