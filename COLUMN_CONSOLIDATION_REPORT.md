# تقرير دمج أعمدة قاعدة البيانات - Column Consolidation Report

## نظرة عامة

تم دمج جميع ملفات إضافة الأعمدة المنفصلة في الجداول الأساسية لقاعدة البيانات بدلاً من الاحتفاظ بملفات تعديل منفصلة.

## الملفات المدموجة والمحذوفة

### 1. جدول المستخدمين (Users Table)

-   **الملف المحذوف**: `2025_09_09_100016_add_phone_to_users_table.php`
-   **المدموج في**: `0001_01_01_000000_create_users_table.php`
-   **العمود المضاف**: `phone` (nullable)

### 2. جدول الشاحنات الداخلية (Internal Trucks Table)

-   **الملف المحذوف**: `2025_09_09_100010_add_missing_columns_to_internal_trucks_table.php`
-   **المدموج في**: `2025_08_04_011013_create_internal_trucks_table.php`
-   **الأعمدة المضافة**:
    -   `warranty_expiry` (date, nullable)
    -   `last_maintenance` (date, nullable)

### 3. جدول الموظفين (Employees Table)

-   **الملفات المحذوفة**:
    -   `2025_09_09_100013_add_additional_documents_to_employees_table.php`
    -   `2025_09_09_100012_add_driving_license_issue_date_to_employees_table.php`
-   **المدموج في**: `2025_08_01_070000_create_employees_table.php`
-   **الأعمدة المضافة**:
    -   `additional_documents` (text, nullable)
    -   `driving_license_issue_date` (date, nullable)

### 4. جدول استهلاك الوقود للمعدات (Equipment Fuel Consumptions Table)

-   **الملف المحذوف**: `2025_09_09_100014_add_approval_fields_to_equipment_fuel_consumptions_table.php`
-   **المدموج في**: `2025_08_10_005005_create_equipment_fuel_consumptions_table.php`
-   **الأعمدة المضافة**:
    -   `approval_status` (enum: pending, approved, rejected)
    -   `approved_by` (foreign key to users)
    -   `approved_at` (timestamp, nullable)
    -   `approval_notes` (text, nullable)

### 5. جدول قطع الغيار (Spare Parts Table)

-   **الملف المحذوف**: `2025_09_09_100017_add_source_to_spare_parts_table.php`
-   **المدموج في**: `2025_08_05_193725_create_spare_parts_table.php`
-   **العمود المضاف**: `source` (enum: new, returned, damaged_replacement)

### 6. جدول العهد (Custodies Table)

-   **الملف المحذوف**: `2025_09_09_100025_add_approval_columns_to_custodies_table.php`
-   **المدموج في**: `2025_08_15_221031_create_custodies_table.php`
-   **الأعمدة المضافة**:
    -   `approved_at` (timestamp, nullable)
    -   `approved_by` (foreign key to users)

### 7. جدول سندات الصرف (Expense Vouchers Table)

-   **الملف المحذوف**: `2025_09_09_100029_add_tax_fields_to_expense_vouchers.php`
-   **المدموج في**: `2025_08_15_225952_create_expense_vouchers_table.php`
-   **الأعمدة المضافة**:
    -   `tax_rate` (decimal, default 15.00)
    -   `tax_amount` (decimal, default 0.00)
    -   `amount_without_tax` (decimal, default 0.00)

### 8. جدول سندات الإيراد (Revenue Vouchers Table)

-   **الملف المحذوف**: `2025_09_09_100031_add_tax_fields_to_revenue_vouchers.php`
-   **المدموج في**: `2025_08_15_090535_create_revenue_vouchers_table.php`
-   **الأعمدة المضافة**:
    -   `tax_rate` (decimal, default 15.00)
    -   `tax_amount` (decimal, default 0.00)
    -   `amount_without_tax` (decimal, default 0.00)

### 9. جدول مستخلصات المشاريع (Project Extracts Table)

-   **الملفات المحذوفة**:
    -   `2025_09_09_100030_add_tax_fields_to_project_extracts.php`
    -   `2025_09_09_100028_add_revenue_voucher_id_to_project_extracts.php`
-   **المدموج في**: `2025_08_04_160020_create_project_extracts_table.php`
-   **الأعمدة المضافة**:
    -   `tax_rate` (decimal, default 15.00)
    -   `tax_amount` (decimal, default 0.00)
    -   `total_with_tax` (decimal, default 0.00)
    -   `revenue_voucher_id` (foreign key to revenue_vouchers)

### 10. جدول الشاحنات الخارجية (External Trucks Table)

-   **الملف المحذوف**: `2025_09_09_100032_add_daily_rate_to_external_trucks_table.php`
-   **المدموج في**: `2025_08_01_191525_create_external_trucks_table.php`
-   **العمود المضاف**: `daily_rate` (decimal, nullable)

### 11. ملفات محذوفة (فارغة أو مكررة)

-   **الملف المحذوف**: `2025_09_09_100015_add_category_to_roles_table.php` (ملف فارغ)
-   **الملف المحذوف**: `2025_09_09_100024_add_fields_to_project_visits_table.php` (العمود موجود بالفعل)

## الفوائد المحققة

### 1. تبسيط بنية قاعدة البيانات

-   تقليل عدد ملفات التهجير من 91 إلى حوالي 78 ملف
-   إزالة التعقيد الناتج عن ملفات التعديل المنفصلة

### 2. تحسين الصيانة

-   سهولة فهم بنية الجداول من خلال ملف واحد لكل جدول
-   تقليل احتمالية الأخطاء في التهجير

### 3. تحسين الأداء

-   تقليل عدد عمليات التهجير المطلوبة
-   تحسين سرعة إعداد قاعدة البيانات

### 4. تحسين قابلية القراءة

-   كل جدول يحتوي على جميع أعمدته في مكان واحد
-   سهولة مراجعة وفهم بنية الجداول

## التوصيات للمستقبل

1. **إنشاء أعمدة جديدة**: يُفضل إضافتها مباشرة في ملف إنشاء الجدول إذا كان المشروع في مرحلة التطوير
2. **التعديلات على الإنتاج**: استخدام ملفات تهجير منفصلة للتعديلات على قواعد البيانات في الإنتاج
3. **التوثيق**: توثيق أي تغييرات في بنية الجداول بوضوح

## تاريخ الإنجاز

-   **تاريخ البداية**: سبتمبر 4, 2025
-   **تاريخ الانتهاء**: سبتمبر 4, 2025
-   **إجمالي الملفات المدموجة**: 13 ملف
-   **إجمالي الأعمدة المدموجة**: 25+ عمود

---

_تم إنشاء هذا التقرير تلقائياً كجزء من عملية تنظيف وتحسين مشروع Laravel_
