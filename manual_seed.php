<?php

require 'vendor/autoload.php';

// إنشاء البيئة
putenv('APP_ENV=local');
$app = require_once 'bootstrap/app.php';

try {
    echo "إنشاء الأدوار والصلاحيات يدوياً...\n\n";

    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // الأدوار
    $roles = [
        ['name' => 'general_manager', 'display_name' => 'مدير عام', 'category' => 'executive'],
        ['name' => 'project_manager', 'display_name' => 'مدير مشاريع', 'category' => 'management'],
        ['name' => 'financial_manager', 'display_name' => 'مدير مالي', 'category' => 'management'],
        ['name' => 'hr_manager', 'display_name' => 'مدير موارد بشرية', 'category' => 'management'],
        ['name' => 'operations_manager', 'display_name' => 'مدير عمليات', 'category' => 'management'],
        ['name' => 'engineer', 'display_name' => 'مهندس', 'category' => 'technical'],
        ['name' => 'equipment_operator', 'display_name' => 'عامل تشغيل معدات', 'category' => 'operational'],
        ['name' => 'driver', 'display_name' => 'سائق', 'category' => 'operational'],
        ['name' => 'security', 'display_name' => 'أمن', 'category' => 'operational'],
        ['name' => 'accountant', 'display_name' => 'محاسب', 'category' => 'administrative'],
        ['name' => 'admin_assistant', 'display_name' => 'مساعد إداري', 'category' => 'administrative'],
    ];

    echo "إنشاء الأدوار:\n";
    foreach ($roles as $role) {
        $stmt = $pdo->prepare("
            INSERT OR REPLACE INTO roles (name, display_name, category, is_active, created_at, updated_at) 
            VALUES (?, ?, ?, 1, datetime('now'), datetime('now'))
        ");
        $stmt->execute([$role['name'], $role['display_name'], $role['category']]);
        echo "✅ {$role['display_name']}\n";
    }

    // الصلاحيات الأساسية
    $permissions = [
        ['name' => 'employees.view', 'display_name' => 'عرض الموظفين', 'category' => 'الموظفين'],
        ['name' => 'employees.create', 'display_name' => 'إنشاء موظف', 'category' => 'الموظفين'],
        ['name' => 'employees.edit', 'display_name' => 'تعديل موظف', 'category' => 'الموظفين'],
        ['name' => 'employees.delete', 'display_name' => 'حذف موظف', 'category' => 'الموظفين'],
        ['name' => 'employees.activate', 'display_name' => 'تفعيل موظف', 'category' => 'الموظفين'],
        ['name' => 'equipment.view', 'display_name' => 'عرض المعدات', 'category' => 'المعدات'],
        ['name' => 'equipment.create', 'display_name' => 'إنشاء معدة', 'category' => 'المعدات'],
        ['name' => 'equipment.edit', 'display_name' => 'تعديل معدة', 'category' => 'المعدات'],
        ['name' => 'projects.view', 'display_name' => 'عرض المشاريع', 'category' => 'المشاريع'],
        ['name' => 'projects.manage', 'display_name' => 'إدارة المشاريع', 'category' => 'المشاريع'],
        ['name' => 'finance.view', 'display_name' => 'عرض المالية', 'category' => 'المالية'],
        ['name' => 'finance.manage', 'display_name' => 'إدارة المالية', 'category' => 'المالية'],
        ['name' => 'reports.view', 'display_name' => 'عرض التقارير', 'category' => 'التقارير'],
        ['name' => 'system.admin', 'display_name' => 'إدارة النظام', 'category' => 'النظام'],
    ];

    echo "\nإنشاء الصلاحيات:\n";
    foreach ($permissions as $permission) {
        $stmt = $pdo->prepare("
            INSERT OR REPLACE INTO permissions (name, display_name, category, is_active, created_at, updated_at) 
            VALUES (?, ?, ?, 1, datetime('now'), datetime('now'))
        ");
        $stmt->execute([$permission['name'], $permission['display_name'], $permission['category']]);
        echo "✅ {$permission['display_name']}\n";
    }

    // إنشاء جدول location_types
    $sql = "CREATE TABLE IF NOT EXISTS location_types (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        is_active BOOLEAN DEFAULT 1,
        created_at DATETIME,
        updated_at DATETIME
    )";
    $pdo->exec($sql);
    echo "\n✅ تم إنشاء جدول location_types\n";

    // إنشاء أنواع المواقع
    $locationTypes = [
        'مكتب رئيسي',
        'موقع مشروع',
        'مستودع',
        'ورشة صيانة',
        'محطة وقود',
        'مكتب فرعي',
        'منطقة سكنية',
        'منطقة صناعية'
    ];

    echo "\nإنشاء أنواع المواقع:\n";
    foreach ($locationTypes as $type) {
        $stmt = $pdo->prepare("
            INSERT OR REPLACE INTO location_types (name, is_active, created_at, updated_at) 
            VALUES (?, 1, datetime('now'), datetime('now'))
        ");
        $stmt->execute([$type]);
        echo "✅ $type\n";
    }

    // تحديث محمد الشهراني ليكون مدير عام
    $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'general_manager'");
    $generalManagerRole = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($generalManagerRole) {
        $stmt = $pdo->prepare("UPDATE users SET role_id = ? WHERE email = 'mohamed@abraj.com'");
        $stmt->execute([$generalManagerRole['id']]);
        echo "\n✅ تم تعيين محمد الشهراني كمدير عام\n";
    }

    echo "\n🎉 تمت العملية بنجاح!\n";
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
