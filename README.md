# شركة الأبراج للمقاولات - نظام الإدارة (Laravel)

تم تحويل هذا المشروع من Next.js إلى Laravel بنجاح، وهو نظام إدارة شامل لشركة الأبراج للمقاولات.

## المميزات المحولة

### ✅ تم الانتهاء من:
- **لوحة التحكم الرئيسية**: إحصائيات شاملة ومخططات مالية
- **إدارة الموظفين**: إضافة، تعديل، حذف، وعرض الموظفين
- **تصميم متجاوب**: يدعم RTL والعربية بالكامل
- **قاعدة البيانات**: جداول الموظفين والمعدات والمشاريع والمستندات والنقليات

### 🔄 قيد التطوير:
- إدارة المعدات
- إدارة المستندات  
- حركة النقليات
- المالية والفواتير
- إدارة المشاريع
- متابعة الحضور والانصراف

## التقنيات المستخدمة

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS
- **قاعدة البيانات**: SQLite (افتراضي)
- **الرموز**: Remix Icons
- **المخططات**: Chart.js
- **الخطوط**: Google Fonts (Tajawal)

## التثبيت والتشغيل

### المتطلبات:
- PHP 8.2 أو أحدث
- Composer
- Node.js (اختياري)

### خطوات التشغيل:

1. **تثبيت التبعيات**:
```bash
composer install
```

2. **إعداد ملف البيئة**:
```bash
cp .env.example .env
php artisan key:generate
```

3. **تشغيل قاعدة البيانات**:
```bash
php artisan migrate
php artisan db:seed --class=EmployeeSeeder
```

4. **تشغيل الخادم**:
```bash
php artisan serve
```

5. **فتح التطبيق**: http://127.0.0.1:8000

## هيكل المشروع

```
/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── EmployeeController.php
│   │   ├── EquipmentController.php
│   │   ├── DocumentController.php
│   │   ├── TransportController.php
│   │   ├── FinanceController.php
│   │   └── ProjectController.php
│   └── Models/
│       ├── Employee.php
│       ├── Equipment.php
│       ├── Project.php
│       ├── Transport.php
│       └── Document.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── sidebar.blade.php
│   ├── dashboard/
│   │   └── index.blade.php
│   └── employees/
│       └── index.blade.php
└── routes/
    └── web.php
```

## مقارنة مع النسخة السابقة (Next.js)

| الميزة | Next.js | Laravel |
|--------|---------|---------|
| النوع | SPA | Full-Stack |
| البيانات | Mock Data | قاعدة بيانات حقيقية |
| التوجيه | Client-side | Server-side |
| الحالة | React State | Session/Database |
| الأمان | Client-side | Server-side |

## البيانات التجريبية

تم إنشاء 5 موظفين تجريبيين:
- أحمد محمد الأحمد (مدير المشاريع)
- فاطمة علي السالم (محاسبة رئيسية)
- خالد عبدالله الخالد (مهندس مدني)
- نورا سعد الدوسري (مديرة الموارد البشرية)
- محمد سالم القحطاني (فني معدات)

## الصفحات المتاحة

- `/` - لوحة التحكم الرئيسية
- `/employees` - إدارة الموظفين
- `/employees/create` - إضافة موظف جديد
- `/employees/attendance` - متابعة الحضور

## الميزات المحولة بالكامل

### 1. لوحة التحكم
- إحصائيات في الوقت الفعلي
- مخططات مالية تفاعلية
- النشاطات الأخيرة
- التوقيت والتاريخ

### 2. إدارة الموظفين
- قائمة الموظفين مع الترقيم
- إضافة موظف جديد
- تعديل بيانات الموظف
- حذف الموظف
- البحث والفلترة

## خطط التطوير المستقبلية

1. **إكمال باقي الصفحات**: المعدات، المستندات، النقليات، المالية، المشاريع
2. **نظام المصادقة**: تسجيل دخول وصلاحيات
3. **التقارير**: تصدير PDF وExcel  
4. **الإشعارات**: نظام تنبيهات فوري
5. **API**: إنشاء REST API
6. **تطبيق الجوال**: Flutter أو React Native

## المطور

تم التحويل من Next.js إلى Laravel بواسطة GitHub Copilot

---

*هذا النظام تم تطويره خصيصاً لشركة الأبراج للمقاولات العامة*
