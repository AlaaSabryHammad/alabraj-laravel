<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== تقرير شامل للبيانات المنشأة ===\n\n";

    // المستخدمين
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM users');
    $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "👥 المستخدمين: $userCount\n";

    if ($userCount > 0) {
        $stmt = $pdo->query('
            SELECT u.name, u.email, r.display_name as role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id 
            ORDER BY u.id DESC LIMIT 3
        ');
        echo "أحدث المستخدمين:\n";
        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roleName = $user['role_name'] ?? 'بدون دور';
            echo "  - {$user['name']} ({$user['email']}) - الدور: {$roleName}\n";
        }
    }

    // الأدوار
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM roles');
    $roleCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\n👔 الأدوار: $roleCount\n";

    if ($roleCount > 0) {
        $stmt = $pdo->query('SELECT name, display_name FROM roles ORDER BY id');
        echo "قائمة الأدوار:\n";
        while ($role = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$role['display_name']} ({$role['name']})\n";
        }
    }

    // الصلاحيات
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM permissions');
    $permissionCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\n🔐 الصلاحيات: $permissionCount\n";

    // أنواع المواقع
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM location_types');
    $locationTypeCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\n📍 أنواع المواقع: $locationTypeCount\n";

    if ($locationTypeCount > 0) {
        $stmt = $pdo->query('SELECT name FROM location_types ORDER BY id');
        echo "قائمة أنواع المواقع:\n";
        while ($type = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$type['name']}\n";
        }
    }

    // الموظفين
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM employees');
    $employeeCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\n👨‍💼 الموظفين: $employeeCount\n";

    if ($employeeCount > 0) {
        $stmt = $pdo->query('SELECT name, role FROM employees ORDER BY id DESC LIMIT 3');
        echo "أحدث الموظفين:\n";
        while ($employee = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$employee['name']} - الدور: {$employee['role']}\n";
        }
    }

    echo "\n🎉 تم إنشاء جميع البيانات المطلوبة بنجاح!\n";
    echo "=============================================\n";
    echo "بيانات الدخول:\n";
    echo "الإيميل: mohamed@abraj.com\n";
    echo "كلمة المرور: mohamed123\n";
    echo "=============================================\n";
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
