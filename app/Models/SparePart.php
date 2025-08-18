<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'spare_part_type_id',
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
        'serial_number',
        'barcode',
        'source',
        'returned_by_employee_id',
        'return_notes',
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
    public function getIsLowStockAttribute()
    {
        return $this->total_stock <= $this->minimum_stock;
    }

    // الحصول على مخزون مستودع معين
    public function getWarehouseStock($locationId)
    {
        return $this->inventories()->where('location_id', $locationId)->first();
    }

    /**
     * Get spare part type
     */
    public function sparePartType()
    {
        return $this->belongsTo(SparePartType::class);
    }

    /**
     * Get employee who returned this part (if returned)
     */
    public function returnedByEmployee()
    {
        return $this->belongsTo(Employee::class, 'returned_by_employee_id');
    }

    /**
     * Get serial numbers for this spare part
     */
    public function serialNumbers()
    {
        return $this->hasMany(SparePartSerial::class);
    }

    /**
     * Get available serial numbers
     */
    public function availableSerialNumbers()
    {
        return $this->serialNumbers()->where('status', 'available');
    }

    /**
     * Generate barcode for specific spare part instance
     */
    public function generateBarcode()
    {
        $timestamp = date('Ymd');

        // استخدام microtime للحصول على رقم فريد
        $microtime = (int)(\microtime(true) * 1000);
        $lastDigits = $microtime % 10000; // آخر 4 أرقام

        // في حالة التطابق، إضافة رقم عشوائي بناءً على الوقت
        $randomSuffix = ($microtime % 900) + 100; // سيعطي رقم بين 100-999

        return "BC-{$this->id}-{$timestamp}-{$lastDigits}{$randomSuffix}";
    }

    /**
     * Generate serial number for specific spare part instance
     */
    public function generateSerialNumber()
    {
        $year = date('Y');

        // استخدام microtime + الرقم التعريفي + رقم عشوائي للحصول على رقم فريد
        $microtime = (int)(\microtime(true) * 1000);
        $uniqueNumber = ($microtime + $this->id + (($microtime % 999) + 1)) % 1000000;

        return "SP-{$year}-" . str_pad($uniqueNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique spare part code
     */
    public static function generateCode()
    {
        do {
            $lastSparePart = self::orderBy('id', 'desc')->first();
            $nextNumber = $lastSparePart ? $lastSparePart->id + 1 : 1;
            $code = 'SP-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            // إضافة microtime للتأكد من الفرادة في حالة الطلبات المتزامنة
            $microSuffix = (int)(microtime(true) * 1000) % 1000;
            $code .= '-' . str_pad($microSuffix, 3, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
