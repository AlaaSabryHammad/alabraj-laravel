<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "فحص علاقة المستخدم بالأدوار...\n\n";

    // فحص المستخدم محمد الشهراني
    $stmt = $pdo->query("
        SELECT u.id, u.name, u.email, u.role_id, r.name as role_name, r.display_name
        FROM users u 
        LEFT JOIN roles r ON u.role_id = r.id 
        WHERE u.email = 'mohamed@abraj.com'
    ");

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "👤 المستخدم: {$user['name']}\n";
        echo "📧 الإيميل: {$user['email']}\n";
        echo "🆔 Role ID: {$user['role_id']}\n";
        echo "👔 اسم الدور: {$user['role_name']}\n";
        echo "🏷️ عرض الدور: {$user['display_name']}\n\n";

        // فحص جدول user_roles إذا كان موجوداً
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='user_roles'");
        if ($stmt->fetch()) {
            echo "فحص جدول user_roles:\n";
            $stmt = $pdo->query("
                SELECT ur.user_id, ur.role_id, r.name, r.display_name
                FROM user_roles ur
                JOIN roles r ON ur.role_id = r.id
                WHERE ur.user_id = {$user['id']}
            ");

            while ($userRole = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "  - دور إضافي: {$userRole['display_name']} ({$userRole['name']})\n";
            }
        }
    } else {
        echo "❌ لم يتم العثور على المستخدم محمد الشهراني\n";
    }

    echo "\n📊 جميع الأدوار المتاحة:\n";
    $stmt = $pdo->query("SELECT id, name, display_name FROM roles ORDER BY id");
    while ($role = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  {$role['id']}. {$role['display_name']} ({$role['name']})\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
