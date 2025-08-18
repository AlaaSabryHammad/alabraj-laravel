<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'address',
        'manager',
        'phone',
        'status'
    ];

    // العلاقات
    public function inventories(): HasMany
    {
        return $this->hasMany(WarehouseInventory::class);
    }

    public function damagedPartsReceipts(): HasMany
    {
        return $this->hasMany(DamagedPartsReceipt::class);
    }
}
