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
        'code',
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

        // عند إنشاء معدة من نوع "شاحنات" وليس لها truck_id، ننشئ شاحنة داخلية
        static::created(function ($equipment) {
            if (($equipment->category == 'شاحنات' || $equipment->category == 'شاحنة') && !$equipment->truck_id) {
                // استخراج معلومات الشاحنة من اسم المعدة
                $equipmentName = $equipment->name;
                $parts = explode(' - ', $equipmentName);

                $brand = 'غير محدد';
                $model = 'غير محدد';
                $plateNumber = 'غير محدد';

                if (count($parts) >= 2) {
                    $brandModel = trim($parts[0]);
                    $plateNumber = trim($parts[1]);

                    // محاولة فصل العلامة التجارية والموديل
                    $brandModelParts = explode(' ', $brandModel, 2);
                    $brand = $brandModelParts[0];
                    $model = isset($brandModelParts[1]) ? $brandModelParts[1] : $brand;
                }

                $internalTruck = \App\Models\InternalTruck::create([
                    'plate_number' => $plateNumber,
                    'brand' => $brand,
                    'model' => $model,
                    'year' => date('Y'), // السنة الحالية كقيمة افتراضية
                    'load_capacity' => 5.0, // قيمة افتراضية
                    'fuel_type' => 'diesel', // ديزل كقيمة افتراضية
                    'status' => $equipment->status == 'in_use' ? 'in_use' : 'available',
                    'purchase_date' => $equipment->purchase_date ?? now(),
                    'purchase_price' => $equipment->purchase_price ?? 0,
                    'description' => $equipment->description ?? 'شاحنة مضافة من المعدات',
                    'driver_id' => $equipment->driver_id,
                    'user_id' => $equipment->user_id ?? (\Illuminate\Support\Facades\Auth::id() ?? 1),
                ]);

                // ربط المعدة بالشاحنة المنشأة حديثاً
                $equipment->update(['truck_id' => $internalTruck->id]);
            }
        });

        // عند تحديث معدة من نوع شاحنات، نحدث الشاحنة المرتبطة
        static::updated(function ($equipment) {
            if (($equipment->category == 'شاحنات' || $equipment->category == 'شاحنة') && $equipment->truck_id) {
                $truck = \App\Models\InternalTruck::find($equipment->truck_id);
                if ($truck) {
                    $truck->update([
                        'status' => $equipment->status == 'in_use' ? 'in_use' : 'available',
                        'driver_id' => $equipment->driver_id,
                    ]);
                }
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

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class, 'type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    // علاقة مع استهلاك المحروقات
    public function fuelConsumptions()
    {
        return $this->hasMany(EquipmentFuelConsumption::class);
    }

    // علاقة مع الصيانة
    public function maintenances()
    {
        return $this->hasMany(EquipmentMaintenance::class);
    }

    // علاقة مع سيارة المحروقات (إذا كانت المعدة من نوع تانكر)
    public function fuelTruck()
    {
        return $this->hasOne(FuelTruck::class);
    }

    // علاقة مع توزيعات المحروقات (كمعدة مستقبلة)
    public function receivedFuelDistributions()
    {
        return $this->hasMany(FuelDistribution::class, 'target_equipment_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['available', 'in_use', 'maintenance']);
    }
}
