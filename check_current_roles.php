<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== الأدوار الموجودة حالياً في قاعدة البيانات ===\n\n";

    $stmt = $pdo->query("SELECT id, name, display_name, category FROM roles ORDER BY id");
    while ($role = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$role['id']}\n";
        echo "Name: {$role['name']}\n";
        echo "Display Name: {$role['display_name']}\n";
        echo "Category: {$role['category']}\n";
        echo "---\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
