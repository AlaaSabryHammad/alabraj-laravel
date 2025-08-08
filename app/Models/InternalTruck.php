<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalTruck extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'brand',
        'model',
        'year',

        'load_capacity',
        'engine_number',
        'chassis_number',
        'fuel_type',
        'status',
        'purchase_date',
        'purchase_price',
        'warranty_expiry',
        'last_maintenance',
        'license_expiry',
        'insurance_expiry',
        'description',
        'driver_id',
        'location_id',
        'user_id',
        'images'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'last_maintenance' => 'date',
        'license_expiry' => 'date',
        'insurance_expiry' => 'date',
        'purchase_price' => 'decimal:2',
        'load_capacity' => 'decimal:2',
        'images' => 'array',
    ];

    // علاقة مع المستخدم الذي أضاف الشاحنة
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع السائق المسؤول
    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driver_id');
    }

    // علاقة مع الموقع
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // دالة لإضافة الشاحنة تلقائياً إلى جدول المعدات
    protected static function boot()
    {
        parent::boot();

        static::created(function ($truck) {
            // تحقق من عدم وجود معدة مرتبطة بهذه الشاحنة
            $existingEquipment = Equipment::where('truck_id', $truck->id)->first();
            if (!$existingEquipment) {
                Equipment::create([
                    'name' => $truck->brand . ' ' . $truck->model . ' - ' . $truck->plate_number,
                    'category' => 'شاحنات',
                    'description' => 'شاحنة داخلية - رقم اللوحة: ' . $truck->plate_number,
                    'serial_number' => $truck->chassis_number ?? 'INT-' . $truck->id,
                    'purchase_date' => $truck->purchase_date ?? now()->toDateString(),
                    'purchase_price' => $truck->purchase_price ?? 0.00,
                    'status' => $truck->driver_id ? 'in_use' : 'available',
                    'notes' => 'شاحنة داخلية مضافة تلقائياً',
                    'user_id' => $truck->user_id,
                    'truck_id' => $truck->id, // لربط المعدة بالشاحنة
                    'driver_id' => $truck->driver_id, // ربط السائق
                ]);
            }
        });

        static::updated(function ($truck) {
            // تحديث المعدة المرتبطة عند تعديل الشاحنة
            $equipment = Equipment::where('truck_id', $truck->id)->first();
            if ($equipment) {
                // التحقق من أن الرقم التسلسلي الجديد غير مستخدم
                $newSerialNumber = $truck->chassis_number ?? 'INT-' . $truck->id;
                $existingEquipment = Equipment::where('serial_number', $newSerialNumber)
                    ->where('id', '!=', $equipment->id)
                    ->first();

                $updateData = [
                    'name' => $truck->brand . ' ' . $truck->model . ' - ' . $truck->plate_number,
                    'description' => 'شاحنة داخلية - رقم اللوحة: ' . $truck->plate_number,
                    'status' => $truck->driver_id ? 'in_use' : 'available',
                    'driver_id' => $truck->driver_id, // تحديث السائق
                ];

                // إضافة الرقم التسلسلي فقط إذا لم يكن مستخدماً
                if (!$existingEquipment) {
                    $updateData['serial_number'] = $newSerialNumber;
                }

                $equipment->update($updateData);
            }
        });

        static::deleted(function ($truck) {
            // حذف المعدة المرتبطة عند حذف الشاحنة
            Equipment::where('truck_id', $truck->id)->delete();
        });
    }

    // علاقة مع المعدة المرتبطة
    public function equipment()
    {
        return $this->hasOne(Equipment::class, 'truck_id');
    }
}
