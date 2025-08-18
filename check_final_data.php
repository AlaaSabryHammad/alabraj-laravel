<?php

require 'vendor/autoload.php';

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== البيانات المنشأة ===\n";

    // فحص المستخدمين
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM users');
    $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "المستخدمين: $userCount\n";

    if ($userCount > 0) {
        $stmt = $pdo->query('SELECT name, email FROM users LIMIT 1');
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "اسم المستخدم: {$user['name']}\n";
        echo "الإيميل: {$user['email']}\n";
    }

    // فحص الأدوار
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM roles');
    $roleCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\nالأدوار: $roleCount\n";

    if ($roleCount > 0) {
        echo "أسماء الأدوار:\n";
        $stmt = $pdo->query('SELECT name FROM roles');
        while ($role = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- {$role['name']}\n";
        }
    }

    // فحص الصلاحيات
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM permissions');
    $permissionCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\nالصلاحيات: $permissionCount\n";

    // فحص أنواع المواقع
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM location_types');
    $locationTypeCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\nأنواع المواقع: $locationTypeCount\n";

    if ($locationTypeCount > 0) {
        echo "أسماء أنواع المواقع:\n";
        $stmt = $pdo->query('SELECT name FROM location_types');
        while ($type = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- {$type['name']}\n";
        }
    }

    // فحص الموظفين
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM employees');
    $employeeCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\nالموظفين: $employeeCount\n";

    if ($employeeCount > 0) {
        $stmt = $pdo->query('SELECT name, role FROM employees LIMIT 1');
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "اسم الموظف: {$employee['name']}\n";
        echo "دور الموظف: {$employee['role']}\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
