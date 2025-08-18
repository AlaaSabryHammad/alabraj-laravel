<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "إنشاء جداول الأدوار والصلاحيات...\n";

    // إنشاء جدول الأدوار
    $sql = "CREATE TABLE IF NOT EXISTS roles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) UNIQUE NOT NULL,
        display_name VARCHAR(255) NOT NULL,
        description TEXT,
        category VARCHAR(255) DEFAULT 'general',
        is_active BOOLEAN DEFAULT 1,
        created_at DATETIME,
        updated_at DATETIME
    )";
    $pdo->exec($sql);
    echo "✅ تم إنشاء جدول roles\n";

    // إنشاء جدول الصلاحيات
    $sql = "CREATE TABLE IF NOT EXISTS permissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) UNIQUE NOT NULL,
        display_name VARCHAR(255) NOT NULL,
        category VARCHAR(255) DEFAULT 'general',
        description TEXT,
        is_active BOOLEAN DEFAULT 1,
        created_at DATETIME,
        updated_at DATETIME
    )";
    $pdo->exec($sql);
    echo "✅ تم إنشاء جدول permissions\n";

    // إنشاء جدول ربط الأدوار والصلاحيات
    $sql = "CREATE TABLE IF NOT EXISTS role_permissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        role_id INTEGER NOT NULL,
        permission_id INTEGER NOT NULL,
        created_at DATETIME,
        updated_at DATETIME,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
        FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✅ تم إنشاء جدول role_permissions\n";

    // إضافة عمود role_id إلى جدول users إذا لم يكن موجوداً
    $stmt = $pdo->query("PRAGMA table_info(users)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hasRoleId = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'role_id') {
            $hasRoleId = true;
            break;
        }
    }

    if (!$hasRoleId) {
        $sql = "ALTER TABLE users ADD COLUMN role_id INTEGER";
        $pdo->exec($sql);
        echo "✅ تم إضافة عمود role_id إلى جدول users\n";
    } else {
        echo "✅ عمود role_id موجود بالفعل في جدول users\n";
    }

    echo "\n🎉 تم إنشاء جميع الجداول بنجاح!\n";
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
