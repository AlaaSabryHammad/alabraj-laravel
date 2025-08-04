<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transport_type',
        'internal_vehicle_id',
        'external_truck_id',
        'material_id',
        'quantity',
        'loading_location_id',
        'unloading_location_id',
        'vehicle_type',
        'vehicle_number',
        'driver_name',
        'destination',
        'departure_time',
        'estimated_arrival',
        'arrival_time',
        'cargo_description',
        'fuel_cost',
        'notes'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'estimated_arrival' => 'datetime',
        'arrival_time' => 'datetime',
        'quantity' => 'decimal:2',
        'fuel_cost' => 'decimal:2'
    ];

    // Status constants - keeping for potential future use
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function internalVehicle()
    {
        return $this->belongsTo(Equipment::class, 'internal_vehicle_id');
    }

    public function externalTruck()
    {
        return $this->belongsTo(ExternalTruck::class, 'external_truck_id');
    }

    public function loadingLocation()
    {
        return $this->belongsTo(Location::class, 'loading_location_id');
    }

    public function unloadingLocation()
    {
        return $this->belongsTo(Location::class, 'unloading_location_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    // Get transport type label in Arabic
    public function getTransportTypeLabelAttribute()
    {
        return match($this->transport_type) {
            'internal' => 'نقل داخلي',
            'external' => 'نقل خارجي',
            default => 'غير محدد'
        };
    }

    // Get the vehicle name based on transport type
    public function getVehicleNameAttribute()
    {
        if ($this->transport_type === 'internal' && $this->internalVehicle) {
            return $this->internalVehicle->name;
        } elseif ($this->transport_type === 'external' && $this->externalTruck) {
            return $this->externalTruck->plate_number;
        }
        return $this->vehicle_number;
    }

    // Get the actual vehicle object
    public function getVehicleAttribute()
    {
        if ($this->transport_type === 'internal') {
            return $this->internalVehicle;
        } elseif ($this->transport_type === 'external') {
            return $this->externalTruck;
        }
        return null;
    }

    // Calculate duration if completed
    public function getDurationAttribute()
    {
        if ($this->arrival_time && $this->departure_time) {
            return $this->departure_time->diffInHours($this->arrival_time);
        }
        return null;
    }
}
