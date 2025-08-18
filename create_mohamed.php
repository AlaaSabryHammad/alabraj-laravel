<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "إنشاء محمد الشهراني يدوياً...\n\n";

    // حذف المستخدم إذا كان موجوداً
    $pdo->exec("DELETE FROM users WHERE email = 'mohamed@abraj.com'");
    $pdo->exec("DELETE FROM employees WHERE email = 'mohamed@abraj.com'");

    // الحصول على role_id للمدير العام
    $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'general_manager'");
    $roleId = $stmt->fetchColumn();

    if (!$roleId) {
        echo "❌ خطأ: دور المدير العام غير موجود\n";
        exit;
    }

    // إنشاء المستخدم
    $hashedPassword = password_hash('mohamed123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, email_verified_at, role_id, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, datetime('now'), datetime('now'))
    ");
    $stmt->execute([
        'محمد الشهراني',
        'mohamed@abraj.com',
        $hashedPassword,
        date('Y-m-d H:i:s'),
        $roleId
    ]);

    $userId = $pdo->lastInsertId();
    echo "✅ تم إنشاء المستخدم: محمد الشهراني (ID: $userId)\n";

    // إنشاء الموظف
    $stmt = $pdo->prepare("
        INSERT INTO employees (name, position, department, hire_date, email, phone, status, national_id, salary, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))
    ");
    $stmt->execute([
        'محمد الشهراني',
        'مدير عام',
        'الإدارة العليا',
        date('Y-m-d'),
        'mohamed@abraj.com',
        '0501234567',
        'active',
        '1234567890',
        50000
    ]);

    $employeeId = $pdo->lastInsertId();
    echo "✅ تم إنشاء الموظف: محمد الشهراني (ID: $employeeId)\n";

    // التحقق من النتيجة
    $stmt = $pdo->query("
        SELECT u.name, u.email, r.display_name as role_name 
        FROM users u 
        LEFT JOIN roles r ON u.role_id = r.id 
        WHERE u.email = 'mohamed@abraj.com'
    ");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "\n🎉 تم الإنشاء بنجاح!\n";
        echo "====================\n";
        echo "الاسم: {$user['name']}\n";
        echo "الإيميل: {$user['email']}\n";
        echo "الدور: {$user['role_name']}\n";
        echo "كلمة المرور: mohamed123\n";
        echo "الرابط: http://127.0.0.1:8000\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
