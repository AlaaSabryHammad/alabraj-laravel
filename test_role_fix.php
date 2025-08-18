<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $data = [
        'name' => 'test_role_' . time(),
        'display_name' => 'دور تجريبي',
        'category' => 'التقنية',
        'description' => 'دور للاختبار',
        'is_active' => true
    ];

    echo "Testing role creation with data:\n";
    print_r($data);

    $role = App\Models\Role::create($data);

    echo "\n✅ Role created successfully!\n";
    echo "ID: " . $role->id . "\n";
    echo "Name: " . $role->name . "\n";
    echo "Display Name: " . $role->display_name . "\n";
    echo "Category: " . $role->category . "\n";

    // Clean up - delete the test role
    $role->delete();
    echo "\n🧹 Test role deleted\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
