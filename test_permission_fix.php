<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $data = [
        'name' => 'test_permission_' . time(),
        'display_name' => 'صلاحية تجريبية',
        'category' => 'عام',
        'description' => 'صلاحية للاختبار',
        'is_active' => true
    ];

    echo "Testing permission creation with data:\n";
    print_r($data);

    $permission = App\Models\Permission::create($data);

    echo "\n✅ Permission created successfully!\n";
    echo "ID: " . $permission->id . "\n";
    echo "Name: " . $permission->name . "\n";
    echo "Display Name: " . $permission->display_name . "\n";
    echo "Category: " . $permission->category . "\n";

    // Clean up - delete the test permission
    $permission->delete();
    echo "\n🧹 Test permission deleted\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
