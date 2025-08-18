<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "🎯 البحث عن محمد الشهراني:\n\n";

    // البحث في المستخدمين
    $stmt = $pdo->query("
        SELECT u.id, u.name, u.email, r.display_name as role_name, u.created_at
        FROM users u 
        LEFT JOIN roles r ON u.role_id = r.id 
        WHERE u.name LIKE '%محمد%' OR u.email LIKE '%mohamed%'
        ORDER BY u.created_at DESC
    ");

    echo "المستخدمين:\n";
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $roleName = $user['role_name'] ?? 'بدون دور';
        echo "  ✅ {$user['name']} ({$user['email']}) - الدور: {$roleName}\n";
        echo "     تاريخ الإنشاء: {$user['created_at']}\n\n";
    }

    // البحث في الموظفين
    $stmt = $pdo->query("
        SELECT name, employee_id, position, status, created_at
        FROM employees 
        WHERE name LIKE '%محمد%'
        ORDER BY created_at DESC
    ");

    echo "الموظفين:\n";
    while ($employee = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  ✅ {$employee['name']} (ID: {$employee['employee_id']}) - المنصب: {$employee['position']}\n";
        echo "     الحالة: {$employee['status']}\n";
        echo "     تاريخ الإنشاء: {$employee['created_at']}\n\n";
    }

    echo "📊 إحصائيات شاملة:\n";
    echo "====================\n";

    $stats = [
        'المستخدمين' => $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
        'الموظفين' => $pdo->query('SELECT COUNT(*) FROM employees')->fetchColumn(),
        'الأدوار' => $pdo->query('SELECT COUNT(*) FROM roles')->fetchColumn(),
        'الصلاحيات' => $pdo->query('SELECT COUNT(*) FROM permissions')->fetchColumn(),
        'أنواع المواقع' => $pdo->query('SELECT COUNT(*) FROM location_types')->fetchColumn(),
    ];

    foreach ($stats as $name => $count) {
        echo "📈 $name: $count\n";
    }

    echo "\n🔑 بيانات الدخول:\n";
    echo "==================\n";
    echo "الإيميل: mohamed@abraj.com\n";
    echo "كلمة المرور: mohamed123\n";
    echo "الرابط: http://127.0.0.1:8000\n";
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
