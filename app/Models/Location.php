<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $manager_id
 */
class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'city',
        'region',
        'coordinates',
        'description',
        'status',
        'manager_id',
        'manager_name',
        'contact_phone',
        'area_size',
        'location_type_id',
        'project_id'
    ];

    protected $casts = [
        'area_size' => 'decimal:2',
        'manager_id' => 'integer'
    ];

    // Equipment relationship
    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'location_id');
    }

    // Manager relationship
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    // Location type relationship
    public function locationType()
    {
        return $this->belongsTo(LocationType::class, 'location_type_id');
    }

    // Project relationship
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Employees relationship
    public function employees()
    {
        return $this->hasMany(Employee::class, 'location_id');
    }

    // Warehouse inventory relationship
    public function inventories()
    {
        return $this->hasMany(WarehouseInventory::class, 'location_id');
    }

    // Spare part transactions relationship
    public function sparePartTransactions()
    {
        return $this->hasMany(SparePartTransaction::class, 'location_id');
    }
}
