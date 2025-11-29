<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'spare_part_id',
        'location_id',
        'current_stock',
        'reserved_stock',
        'available_stock',
        'damaged_stock',
        'average_cost',
        'total_value',
        'last_transaction_date',
        'location_shelf',
        'status',
    ];

    protected $casts = [
        'average_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
        'last_transaction_date' => 'date',
    ];

    // العلاقات
    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // تحديث المخزون المتاح
    public function updateAvailableStock()
    {
        $this->available_stock = $this->current_stock - $this->reserved_stock;
        $this->save();
    }

    // تحديث القيمة الإجمالية
    public function updateTotalValue()
    {
        $this->total_value = $this->current_stock * $this->average_cost;
        $this->save();
    }

    // فحص إذا كان المخزون منخفض
    public function getIsLowStockAttribute()
    {
        return $this->current_stock <= $this->sparePart->minimum_stock;
    }
}
