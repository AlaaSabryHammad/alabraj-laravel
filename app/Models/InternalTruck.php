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
        'user_id'
    ];

    // علاقة مع المستخدم الذي أضاف الشاحنة
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع السائق المسؤول
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
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
            Equipment::create([
                'name' => $truck->brand . ' ' . $truck->model . ' - ' . $truck->plate_number,
                'category' => 'شاحنات',
                'description' => 'شاحنة داخلية - رقم اللوحة: ' . $truck->plate_number,
                'serial_number' => $truck->chassis_number ?? 'INT-' . $truck->id,
                'purchase_date' => now()->toDateString(),
                'purchase_price' => 0.00,
                'status' => 'available',
                'notes' => 'شاحنة داخلية مضافة تلقائياً',
                'user_id' => $truck->user_id,
                'truck_id' => $truck->id // لربط المعدة بالشاحنة
            ]);
        });

        static::updated(function ($truck) {
            // تحديث المعدة المرتبطة عند تعديل الشاحنة
            $equipment = Equipment::where('truck_id', $truck->id)->first();
            if ($equipment) {
                $equipment->update([
                    'name' => $truck->brand . ' ' . $truck->model . ' - ' . $truck->plate_number,
                    'description' => 'شاحنة داخلية - رقم اللوحة: ' . $truck->plate_number,
                    'serial_number' => $truck->chassis_number ?? 'INT-' . $truck->id,
                    'status' => $truck->status == 'متاح' ? 'available' : 'in_use'
                ]);
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
