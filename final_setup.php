<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "تنظيف البيانات وإنشاء محمد الشهراني...\n\n";

    // حذف جميع الموظفين والمستخدمين الموجودين
    echo "حذف البيانات الموجودة...\n";
    $pdo->exec("DELETE FROM employees");
    $pdo->exec("DELETE FROM users");

    // الحصول على role_id للمدير العام
    $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'general_manager'");
    $roleId = $stmt->fetchColumn();

    if (!$roleId) {
        echo "❌ خطأ: دور المدير العام غير موجود\n";
        exit;
    }

    // إنشاء المستخدم محمد الشهراني
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
        '1111111111',
        50000
    ]);

    $employeeId = $pdo->lastInsertId();
    echo "✅ تم إنشاء الموظف: محمد الشهراني (ID: $employeeId)\n";

    // التحقق من النتيجة
    $stmt = $pdo->query("
        SELECT u.name, u.email, r.display_name as role_name, e.position
        FROM users u 
        LEFT JOIN roles r ON u.role_id = r.id 
        LEFT JOIN employees e ON u.email = e.email
        WHERE u.email = 'mohamed@abraj.com'
    ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo "\n🎉 تم الإنشاء بنجاح!\n";
        echo "========================\n";
        echo "👤 الاسم: {$result['name']}\n";
        echo "📧 الإيميل: {$result['email']}\n";
        echo "👔 الدور: {$result['role_name']}\n";
        echo "🏢 المنصب: {$result['position']}\n";
        echo "🔑 كلمة المرور: mohamed123\n";
        echo "🌐 الرابط: http://127.0.0.1:8000\n";
        echo "========================\n";
    }

    // عرض إحصائية نهائية
    $stats = [
        'المستخدمين' => $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
        'الموظفين' => $pdo->query('SELECT COUNT(*) FROM employees')->fetchColumn(),
        'الأدوار' => $pdo->query('SELECT COUNT(*) FROM roles')->fetchColumn(),
        'الصلاحيات' => $pdo->query('SELECT COUNT(*) FROM permissions')->fetchColumn(),
        'أنواع المواقع' => $pdo->query('SELECT COUNT(*) FROM location_types')->fetchColumn(),
    ];

    echo "\n📊 إحصائيات النظام:\n";
    foreach ($stats as $name => $count) {
        echo "📈 $name: $count\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
