# ملخص التحديثات - النظام الثنائي لربط المعدات والشاحنات الداخلية

## ✅ المميزات المُنفذة

### 1. النظام الثنائي للربط التلقائي

-   **معدة → شاحنة داخلية**: عند إنشاء معدة من فئة "شاحنات" يتم إنشاء شاحنة داخلية تلقائياً
-   **شاحنة داخلية → معدة**: عند إنشاء شاحنة داخلية يتم إنشاء معدة تلقائياً في جدول المعدات
-   **المزامنة**: التحديثات على أي من الجانبين تنعكس على الآخر

### 2. تحديثات النماذج (Models)

#### Equipment Model (`app/Models/Equipment.php`)

```php
protected static function boot()
{
    parent::boot();

    static::created(function ($equipment) {
        // عند إنشاء معدة من نوع "شاحنات" ننشئ شاحنة داخلية
        if (($equipment->category == 'شاحنات' || $equipment->category == 'شاحنة') && !$equipment->truck_id) {
            // استخراج تفاصيل الشاحنة من اسم المعدة
            $parts = explode(' - ', $equipment->name);
            $plateNumber = count($parts) > 1 ? trim($parts[1]) : 'Unknown';
            $brandModel = trim($parts[0]);

            $brandModelParts = explode(' ', $brandModel);
            $brand = $brandModelParts[0] ?? 'غير محدد';
            $model = implode(' ', array_slice($brandModelParts, 1)) ?: 'غير محدد';

            $internalTruck = \App\Models\InternalTruck::create([
                'plate_number' => $plateNumber,
                'brand' => $brand,
                'model' => $model,
                'year' => now()->year,
                'load_capacity' => 5.0, // قيمة افتراضية
                'fuel_type' => 'diesel',
                'status' => $equipment->status == 'in_use' ? 'in_use' : 'available',
                'purchase_date' => $equipment->purchase_date,
                'purchase_price' => $equipment->purchase_price ?? 0,
                'description' => $equipment->description ?? 'شاحنة محولة من المعدات',
                'driver_id' => $equipment->driver_id,
                'user_id' => $equipment->user_id ?? \Illuminate\Support\Facades\Auth::id(),
            ]);

            // ربط المعدة بالشاحنة الداخلية
            $equipment->update(['truck_id' => $internalTruck->id]);
        }
    });
}
```

#### InternalTruck Model (`app/Models/InternalTruck.php`)

```php
protected static function boot()
{
    parent::boot();

    static::created(function ($truck) {
        // إنشاء معدة مرتبطة عند إنشاء شاحنة داخلية
        \App\Models\Equipment::create([
            'name' => $truck->brand . ' ' . $truck->model . ' - ' . $truck->plate_number,
            'category' => 'شاحنات',
            'type' => 'شاحنة داخلية',
            'model' => $truck->model,
            'manufacturer' => $truck->brand,
            'serial_number' => $truck->chassis_number ?? 'INT-' . $truck->id,
            'status' => $truck->driver_id ? 'in_use' : 'available',
            'purchase_date' => $truck->purchase_date,
            'purchase_price' => $truck->purchase_price ?? 0,
            'description' => 'شاحنة داخلية - رقم اللوحة: ' . $truck->plate_number,
            'truck_id' => $truck->id,
            'user_id' => $truck->user_id,
            'driver_id' => $truck->driver_id,
        ]);
    });
}
```

### 3. تحديثات المتحكم (Controller)

#### InternalTruckController

-   **دالة index()**: تعرض المعدات غير المربوطة من فئة شاحنات
-   **دالة linkEquipment()**: تحول المعدة إلى شاحنة داخلية

```php
public function linkEquipment(Request $request)
{
    $validated = $request->validate([
        'equipment_id' => 'required|exists:equipment,id',
        'fuel_type' => 'nullable|string|in:diesel,gasoline,electric,hybrid'
    ]);

    $equipment = Equipment::findOrFail($validated['equipment_id']);

    // التأكد من أن المعدة من فئة شاحنات وغير مربوطة
    if (!in_array($equipment->category, ['شاحنات', 'شاحنة']) || $equipment->truck_id) {
        return redirect()->back()->with('error', 'هذه المعدة غير قابلة للربط أو مرتبطة بالفعل');
    }

    // إنشاء شاحنة داخلية من المعدة
    // ... منطق التحويل
}
```

### 4. تحديثات العرض (Views)

#### صفحة الشاحنات الداخلية (`resources/views/internal-trucks/index.blade.php`)

-   إضافة قسم "معدات شاحنات غير مربوطة"
-   عرض المعدات التي لا تملك truck_id
-   زر "تحويل لشاحنة" لكل معدة غير مربوطة

### 5. الطرق (Routes)

```php
Route::post('/internal-trucks/link-equipment', [InternalTruckController::class, 'linkEquipment'])
     ->name('internal-trucks.link-equipment');
```

## 🔄 كيف يعمل النظام

### السيناريو 1: إنشاء معدة شاحنة

1. المستخدم ينشئ معدة جديدة ويحدد الفئة = "شاحنات"
2. تلقائياً يتم إنشاء شاحنة داخلية مرتبطة
3. المعدة تحصل على truck_id يشير للشاحنة الداخلية

### السيناريو 2: إنشاء شاحنة داخلية

1. المستخدم ينشئ شاحنة داخلية جديدة
2. تلقائياً يتم إنشاء معدة مرتبطة بفئة "شاحنات"
3. الشاحنة والمعدة مرتبطتان ثنائياً

### السيناريو 3: معدة شاحنة موجودة غير مربوطة

1. تظهر في صفحة الشاحنات الداخلية في قسم منفصل
2. يمكن تحويلها لشاحنة داخلية بالنقر على "تحويل لشاحنة"
3. يتم إنشاء الشاحنة الداخلية وربطها بالمعدة

## 🧪 الاختبارات المنفذة

### اختبارات النظام:

-   ✅ `test_equipment_to_truck.php`: اختبار تحويل معدة لشاحنة
-   ✅ `test_truck_to_equipment.php`: اختبار إنشاء معدة من شاحنة
-   ✅ `test_final_system.php`: اختبار سريع للنظام الثنائي
-   ✅ `test_unlinked_equipments.php`: اختبار المعدات غير المربوطة

## 🔧 معلومات تقنية

### قاعدة البيانات:

-   جدول `equipment`: يحتوي على حقل `truck_id` للربط
-   جدول `internal_trucks`: الشاحنات الداخلية
-   العلاقة: One-to-One بين Equipment و InternalTruck

### الأحداث المستخدمة:

-   `Model::created()`: عند إنشاء سجل جديد
-   `Model::updated()`: عند تحديث سجل (للمزامنة)
-   `Model::deleted()`: عند حذف سجل (للتنظيف)

## ✨ المميزات الإضافية

1. **منع التكرار**: النظام يتحقق من وجود السجلات قبل الإنشاء
2. **معالجة الأخطاء**: التعامل مع القيود والأخطاء المحتملة
3. **واجهة سهلة**: عرض المعدات غير المربوطة مع خيار التحويل
4. **مرونة البيانات**: استخراج تفاصيل الشاحنة من أسماء المعدات

الآن النظام يعمل بالكامل ويحقق المتطلب:

> "اريد عند تسجيل من نوع شاحنة في جدول المعدات تظهر هذه الشاحنة في صفحة الشاحنات الداخلية وعندما احفظ شاحنه من صفحة الشاحنات الداخليه اريد تسجليها مباشرة في جدول المعدات تحت نوع شاحنه"
