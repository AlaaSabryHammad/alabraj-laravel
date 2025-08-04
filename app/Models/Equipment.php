<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'type_id',
        'model',
        'manufacturer',
        'serial_number',
        'status',
        'location_id',
        'driver_id',
        'purchase_date',
        'purchase_price',
        'warranty_expiry',
        'last_maintenance',
        'description',
        'images',
        'truck_id',
        'category',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'warranty_expiry' => 'datetime',
        'last_maintenance' => 'datetime',
        'purchase_price' => 'decimal:2',
        'images' => 'array'
    ];

    // Automatically fill type field when type_id is set
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->type_id && $model->isDirty(['type_id'])) {
                $equipmentType = EquipmentType::find($model->type_id);
                $model->type = $equipmentType ? $equipmentType->name : null;
            }
        });
    }

    public function files()
    {
        return $this->hasMany(EquipmentFile::class);
    }

    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driver_id');
    }

    public function locationDetail()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class, 'type_id');
    }

    // History relationships
    public function driverHistory()
    {
        return $this->hasMany(EquipmentDriverHistory::class)->orderBy('assigned_at', 'desc');
    }

    public function movementHistory()
    {
        return $this->hasMany(EquipmentMovementHistory::class)->orderBy('moved_at', 'desc');
    }

    // Get current active driver assignment
    public function currentDriverAssignment()
    {
        return $this->hasOne(EquipmentDriverHistory::class)->where('status', 'active')->latest('assigned_at');
    }

    // Get latest movement
    public function latestMovement()
    {
        return $this->hasOne(EquipmentMovementHistory::class)->latest('moved_at');
    }

    // علاقة مع الشاحنة الداخلية (إذا كانت المعدة شاحنة)
    public function internalTruck()
    {
        return $this->belongsTo(InternalTruck::class, 'truck_id');
    }
}
