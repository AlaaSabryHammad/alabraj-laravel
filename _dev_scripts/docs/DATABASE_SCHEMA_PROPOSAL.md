# 🗄️ مخطط قاعدة البيانات المقترح
## نظام إدارة النقل المتكامل

---

## 📋 الجداول المطلوبة

### 1. `transport_orders` (جدول الأساس المشترك)
```sql
CREATE TABLE transport_orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE,              -- EXP-TRUCK-20251119-1001
    transport_type ENUM('internal', 'external'),
    loading_location_id BIGINT UNSIGNED NOT NULL,
    unloading_location_id BIGINT UNSIGNED NOT NULL,
    load_description TEXT,
    status ENUM(
        'pending',           -- قيد الانتظار
        'loading',           -- قيد التحميل
        'in_transit',        -- قيد النقل
        'unloading',         -- قيد التفريغ
        'completed',         -- مكتمل
        'cancelled',         -- ملغى
        'on_hold'            -- مؤجل
    ) DEFAULT 'pending',

    requested_at TIMESTAMP,
    notes LONGTEXT,
    photos JSON,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- الفهارس
    FOREIGN KEY (loading_location_id) REFERENCES locations(id),
    FOREIGN KEY (unloading_location_id) REFERENCES locations(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX (transport_type),
    INDEX (status),
    INDEX (requested_at)
);
```

---

### 2. `internal_transport_orders` (طلبات النقل الداخلي)
```sql
CREATE TABLE internal_transport_orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    transport_order_id BIGINT UNSIGNED NOT NULL UNIQUE,
    internal_truck_id BIGINT UNSIGNED NOT NULL,
    driver_id BIGINT UNSIGNED,                   -- Employee ID

    -- التوقيت
    loading_scheduled_at TIMESTAMP,
    loading_started_at TIMESTAMP,
    loading_completed_at TIMESTAMP,
    departure_at TIMESTAMP,
    estimated_arrival_at TIMESTAMP,
    arrived_at TIMESTAMP,
    unloading_started_at TIMESTAMP,
    unloading_completed_at TIMESTAMP,

    -- التفاصيل
    estimated_distance DECIMAL(10, 2),          -- بالكم
    estimated_duration INT,                     -- بالدقائق
    actual_distance DECIMAL(10, 2),
    actual_duration INT,

    -- التكاليف
    fuel_cost DECIMAL(10, 2) DEFAULT 0,
    toll_cost DECIMAL(10, 2) DEFAULT 0,
    labor_cost DECIMAL(10, 2) DEFAULT 0,
    other_cost DECIMAL(10, 2) DEFAULT 0,
    total_cost DECIMAL(10, 2) DEFAULT 0,

    -- إضافي
    issues LONGTEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- العلاقات والفهارس
    FOREIGN KEY (transport_order_id) REFERENCES transport_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (internal_truck_id) REFERENCES internal_trucks(id),
    FOREIGN KEY (driver_id) REFERENCES employees(id),
    INDEX (internal_truck_id),
    INDEX (driver_id)
);
```

---

### 3. `external_transport_orders` (طلبات النقل الخارجي)
```sql
CREATE TABLE external_transport_orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    transport_order_id BIGINT UNSIGNED NOT NULL UNIQUE,
    external_truck_id BIGINT UNSIGNED NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    driver_name VARCHAR(100),
    driver_phone VARCHAR(20),

    -- العقد والتسعير
    contract_type ENUM(
        'per_trip',          -- لكل رحلة
        'daily',             -- يومي
        'weekly',            -- أسبوعي
        'monthly',           -- شهري
        'annual'             -- سنوي
    ),
    contract_price DECIMAL(10, 2),
    contract_start_date DATE,
    contract_end_date DATE,

    -- التوقيت
    loading_scheduled_at TIMESTAMP,
    loading_started_at TIMESTAMP,
    loading_completed_at TIMESTAMP,
    departure_at TIMESTAMP,
    estimated_arrival_at TIMESTAMP,
    arrived_at TIMESTAMP,
    unloading_started_at TIMESTAMP,
    unloading_completed_at TIMESTAMP,

    -- التكاليف والدفع
    contract_price DECIMAL(10, 2),
    additional_cost DECIMAL(10, 2) DEFAULT 0,
    discount DECIMAL(10, 2) DEFAULT 0,
    final_price DECIMAL(10, 2),
    payment_status ENUM(
        'unpaid',            -- غير مدفوع
        'partial',           -- دفع جزئي
        'paid'               -- مدفوع
    ) DEFAULT 'unpaid',
    paid_amount DECIMAL(10, 2) DEFAULT 0,
    payment_date TIMESTAMP NULL,
    payment_method VARCHAR(50),

    -- إضافي
    estimated_distance DECIMAL(10, 2),
    actual_distance DECIMAL(10, 2),
    estimated_duration INT,
    actual_duration INT,
    issues LONGTEXT,
    invoice_id BIGINT UNSIGNED NULL,

    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- العلاقات والفهارس
    FOREIGN KEY (transport_order_id) REFERENCES transport_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (external_truck_id) REFERENCES external_trucks(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) SET NULL,
    INDEX (external_truck_id),
    INDEX (supplier_id),
    INDEX (payment_status)
);
```

---

### 4. `transport_order_items` (تفاصيل الحمولة)
```sql
CREATE TABLE transport_order_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    transport_order_id BIGINT UNSIGNED NOT NULL,

    -- ماذا يتم نقله
    item_id BIGINT UNSIGNED,                    -- معرّف الشيء (قطعة غيار أو مادة)
    item_type ENUM(
        'spare_part',
        'material',
        'equipment',
        'other'
    ),
    item_name VARCHAR(255),                     -- اسم السلعة للتوضيح

    -- الكميات
    quantity INT NOT NULL,
    unit VARCHAR(50),                           -- وحدة (كيس، قطعة، كيلو)
    loaded_quantity INT DEFAULT 0,              -- ما تم تحميله فعلياً
    unloaded_quantity INT DEFAULT 0,            -- ما تم تفريغه فعلياً

    -- الأماكن
    warehouse_location VARCHAR(255),            -- مكان التخزين في المستودع
    shelf_location VARCHAR(255),                -- الرف

    -- الحالة
    status ENUM(
        'pending',
        'loaded',
        'in_transit',
        'unloaded',
        'damaged',
        'missing'
    ) DEFAULT 'pending',

    -- ملاحظات
    notes TEXT,
    damage_description TEXT,

    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- العلاقات والفهارس
    FOREIGN KEY (transport_order_id) REFERENCES transport_orders(id) ON DELETE CASCADE,
    INDEX (transport_order_id),
    INDEX (item_type)
);
```

---

### 5. `internal_trucks` (الشاحنات الداخلية)
#### ملاحظة: هذا الجدول موجود بالفعل، سنقوم بإضافة حقول جديدة

```sql
ALTER TABLE internal_trucks ADD COLUMN (
    total_trips INT DEFAULT 0,                  -- إجمالي الرحلات
    total_distance DECIMAL(12, 2) DEFAULT 0,    -- إجمالي المسافات المقطوعة
    total_operating_cost DECIMAL(15, 2) DEFAULT 0,
    average_cost_per_km DECIMAL(10, 2) DEFAULT 0,

    -- الحالة الحالية
    current_status ENUM('available', 'loading', 'in_transit', 'unloading', 'maintenance', 'unavailable') DEFAULT 'available',
    current_location_id BIGINT UNSIGNED,

    -- آخر صيانة
    last_maintenance_date DATE,
    next_maintenance_date DATE,
    last_maintenance_cost DECIMAL(10, 2),

    -- الفحوصات الدورية
    last_inspection_date DATE,
    next_inspection_date DATE,
    inspection_status ENUM('passed', 'failed', 'pending') DEFAULT 'pending',

    -- إضافي
    average_fuel_consumption DECIMAL(5, 2),     -- بالكم/لتر
    notes LONGTEXT,

    FOREIGN KEY (current_location_id) REFERENCES locations(id),
    INDEX (current_status),
    INDEX (last_maintenance_date)
);
```

---

### 6. `external_trucks` (الشاحنات الخارجية)
#### ملاحظة: هذا الجدول موجود بالفعل، سنقوم بإضافة حقول جديدة

```sql
ALTER TABLE external_trucks ADD COLUMN (
    total_trips INT DEFAULT 0,
    total_distance DECIMAL(12, 2) DEFAULT 0,
    total_rental_cost DECIMAL(15, 2) DEFAULT 0,

    -- آخر استخدام
    last_used_date DATE,
    last_trip_end_date DATE,

    -- التقييم
    condition ENUM('excellent', 'good', 'fair', 'poor') DEFAULT 'good',
    reliability_rating DECIMAL(3, 2),           -- من 1 إلى 5

    -- إضافي
    notes LONGTEXT,
    documents JSON,

    INDEX (last_used_date)
);
```

---

### 7. `truck_maintenance_logs` (سجل الصيانة)

```sql
CREATE TABLE truck_maintenance_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    truck_type ENUM('internal', 'external'),
    truck_id BIGINT UNSIGNED NOT NULL,

    -- نوع الصيانة
    maintenance_type ENUM(
        'routine',               -- روتينية
        'preventive',            -- وقائية
        'corrective',            -- إصلاح
        'emergency',             -- طارئة
        'inspection',            -- فحص
        'parts_replacement'      -- استبدال قطع
    ),

    -- التفاصيل
    description TEXT,
    performed_by VARCHAR(100),
    maintenance_date DATE,
    completion_date DATE,

    -- التكاليف
    parts_cost DECIMAL(10, 2) DEFAULT 0,
    labor_cost DECIMAL(10, 2) DEFAULT 0,
    other_cost DECIMAL(10, 2) DEFAULT 0,
    total_cost DECIMAL(10, 2),

    -- الملاحظات
    notes LONGTEXT,
    next_maintenance_date DATE,

    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- الفهارس
    INDEX (truck_type),
    INDEX (truck_id),
    INDEX (maintenance_date)
);
```

---

### 8. `transport_tracking` (تتبع الشحنات)

```sql
CREATE TABLE transport_tracking (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    transport_order_id BIGINT UNSIGNED NOT NULL,

    -- الموقع
    current_latitude DECIMAL(10, 8),
    current_longitude DECIMAL(11, 8),
    current_location_name VARCHAR(255),

    -- الحالة
    status ENUM(
        'loading',
        'in_transit',
        'stopped',
        'arrived',
        'unloading',
        'completed'
    ),

    -- التوقيت
    last_update TIMESTAMP,
    estimated_arrival TIMESTAMP,

    -- إضافي
    speed DECIMAL(5, 2),                        -- سرعة (كم/ساعة)
    temperature DECIMAL(5, 2) NULL,             -- لشاحنات التبريد
    notes TEXT,

    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- العلاقات والفهارس
    FOREIGN KEY (transport_order_id) REFERENCES transport_orders(id) ON DELETE CASCADE,
    INDEX (transport_order_id),
    INDEX (last_update)
);
```

---

### 9. `transport_reports` (التقارير المحفوظة)

```sql
CREATE TABLE transport_reports (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    report_type ENUM(
        'daily',
        'weekly',
        'monthly',
        'summary',
        'detailed'
    ),
    transport_type ENUM('internal', 'external', 'both'),

    -- التاريخ
    period_start DATE,
    period_end DATE,

    -- الإحصائيات
    total_orders INT,
    completed_orders INT,
    pending_orders INT,
    cancelled_orders INT,

    total_distance DECIMAL(12, 2),
    total_items INT,
    total_cost DECIMAL(15, 2),
    average_cost_per_order DECIMAL(10, 2),

    -- الأداء
    on_time_delivery_percentage DECIMAL(5, 2),
    average_delivery_time INT,

    -- التفاصيل
    report_data JSON,
    generated_by BIGINT UNSIGNED,
    created_at TIMESTAMP,

    -- الفهارس
    FOREIGN KEY (generated_by) REFERENCES users(id),
    INDEX (period_start, period_end),
    INDEX (transport_type)
);
```

---

## 🔄 العلاقات بين الجداول

```
┌────────────────────────────────────────────────────┐
│          transport_orders (الأساس)                  │
│  ✓ order_number, status, locations, dates         │
└────────────────────────────────────────────────────┘
                    /            \
                   /              \
    ┌──────────────────┐    ┌──────────────────────┐
    │ internal_        │    │ external_transport_  │
    │ transport_orders │    │ orders               │
    └──────────────────┘    └──────────────────────┘
         |                           |
         ├──→ internal_truck        ├──→ external_truck
         ├──→ driver (employee)     ├──→ supplier
         └──→ costs                 ├──→ invoice
                                    └──→ contract details

┌────────────────────────────────────────────────────┐
│      transport_order_items (تفاصيل الحمولة)       │
│  يربط كل شيء يتم نقله مع الطلب                     │
└────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────┐
│      transport_tracking (التتبع الحي)              │
│  يتم تحديثه مع كل حركة للشاحنة                    │
└────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────┐
│    truck_maintenance_logs (سجل الصيانة)           │
│  يتم تسجيل كل صيانة للشاحنات                     │
└────────────────────────────────────────────────────┘
```

---

## 🔍 الفهارس الموصى بها (Indexes)

```sql
-- transport_orders
CREATE INDEX idx_transport_orders_type_status ON transport_orders(transport_type, status);
CREATE INDEX idx_transport_orders_date ON transport_orders(requested_at);
CREATE INDEX idx_transport_orders_locations ON transport_orders(loading_location_id, unloading_location_id);

-- internal_transport_orders
CREATE INDEX idx_internal_orders_truck ON internal_transport_orders(internal_truck_id);
CREATE INDEX idx_internal_orders_driver ON internal_transport_orders(driver_id);
CREATE INDEX idx_internal_orders_dates ON internal_transport_orders(loading_started_at, unloading_completed_at);

-- external_transport_orders
CREATE INDEX idx_external_orders_truck ON external_transport_orders(external_truck_id);
CREATE INDEX idx_external_orders_supplier ON external_transport_orders(supplier_id);
CREATE INDEX idx_external_orders_payment ON external_transport_orders(payment_status);

-- transport_order_items
CREATE INDEX idx_order_items_order ON transport_order_items(transport_order_id);
CREATE INDEX idx_order_items_type ON transport_order_items(item_type);
CREATE INDEX idx_order_items_status ON transport_order_items(status);

-- transport_tracking
CREATE INDEX idx_tracking_order ON transport_tracking(transport_order_id);
CREATE INDEX idx_tracking_status ON transport_tracking(status);

-- truck_maintenance_logs
CREATE INDEX idx_maintenance_truck ON truck_maintenance_logs(truck_type, truck_id);
CREATE INDEX idx_maintenance_date ON truck_maintenance_logs(maintenance_date);
CREATE INDEX idx_maintenance_type ON truck_maintenance_logs(maintenance_type);
```

---

## 📊 مثال على بيانات

### مثال 1: طلب نقل داخلي

```
transport_orders:
- id: 1
- order_number: INT-TRUCK-20251119-0001
- transport_type: internal
- loading_location_id: 1 (الموقع الرئيسي - الرياض)
- unloading_location_id: 5 (موقع الاختبار)
- load_description: نقل قطع غيار لموقع المشروع
- status: completed
- requested_at: 2025-11-19 08:00:00

internal_transport_orders:
- id: 1
- transport_order_id: 1
- internal_truck_id: 1 (الشاحنة رقم المميزة ABC-123)
- driver_id: 5 (العامل الثاني)
- loading_started_at: 2025-11-19 09:00:00
- loading_completed_at: 2025-11-19 10:30:00
- departure_at: 2025-11-19 10:45:00
- arrived_at: 2025-11-19 13:15:00
- unloading_completed_at: 2025-11-19 14:45:00
- estimated_distance: 450.50
- actual_distance: 452.30
- fuel_cost: 125.00
- total_cost: 250.00

transport_order_items:
- item_id: 1, item_type: spare_part (جوان مطاطي)
  quantity: 50, loaded_quantity: 50, unloaded_quantity: 50
- item_id: 2, item_type: spare_part (مضخة زيت)
  quantity: 25, loaded_quantity: 25, unloaded_quantity: 25
```

### مثال 2: طلب نقل خارجي

```
transport_orders:
- id: 2
- order_number: EXT-TRUCK-20251119-0001
- transport_type: external
- loading_location_id: 1 (المستودع)
- unloading_location_id: 7 (موقع ثالث)
- status: completed

external_transport_orders:
- id: 1
- transport_order_id: 2
- external_truck_id: 2 (شاحنة المورد)
- supplier_id: 5 (نقل الجزيرة)
- driver_name: محمد علي
- driver_phone: 0551234567
- contract_type: per_trip
- contract_price: 500.00
- payment_status: paid
- paid_amount: 500.00
- total_cost: 500.00
```

---

## ✨ الميزات المتقدمة

### تكامل تلقائي مع الأنظمة الأخرى:
1. **تحديث المخزون التلقائي** عند انتهاء التفريغ
2. **الفواتير التلقائية** للنقل الخارجي
3. **التنبيهات والإشعارات** عند تأخر الشحنة
4. **الربط مع نظام الموارد البشرية** لتتبع أداء السائقين
5. **التقارير الذكية** بتحليلات عميقة

---

تم إعداد هذا المخطط بعناية لضمان:
✅ عدم التداخل بين الأنظمة
✅ مرونة عالية للتوسع
✅ أداء عالي للقواعد الكبيرة
✅ سهولة الاستعلامات والتقارير