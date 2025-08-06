<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'brand',
        'model',
        'unit_price',
        'unit_type',
        'minimum_stock',
        'supplier',
        'location_shelf',
        'specifications',
        'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    // العلاقات
    public function transactions()
    {
        return $this->hasMany(SparePartTransaction::class);
    }

    public function inventories()
    {
        return $this->hasMany(WarehouseInventory::class);
    }

    // الحصول على إجمالي المخزون في جميع المستودعات
    public function getTotalStockAttribute()
    {
        return $this->inventories()->sum('current_stock');
    }

    // الحصول على إجمالي القيمة
    public function getTotalValueAttribute()
    {
        return $this->inventories()->sum('total_value');
    }

    // فحص إذا كان المخزون أقل من الحد الأدنى
    public function isLowStockAttribute()
    {
        return $this->total_stock <= $this->minimum_stock;
    }

    // الحصول على مخزون مستودع معين
    public function getWarehouseStock($locationId)
    {
        return $this->inventories()->where('location_id', $locationId)->first();
    }
}
