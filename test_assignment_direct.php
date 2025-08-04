<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== Testing Manager Assignment for Employee 7 ===\n\n";

try {
    // التحقق من الموظف والمشرفين
    $employee = \App\Models\Employee::find(7);
    if (!$employee) {
        echo "❌ Employee 7 not found!\n";
        exit(1);
    }

    echo "Employee: {$employee->name} (Role: {$employee->role})\n";
    echo "Current Manager: " . ($employee->directManager ? $employee->directManager->name : 'None') . "\n\n";

    // البحث عن مشرفي المواقع
    $supervisors = \App\Models\Employee::where('role', 'مشرف موقع')
        ->where('id', '!=', $employee->id)
        ->get();

    echo "Available Supervisors:\n";
    foreach($supervisors as $supervisor) {
        echo "- ID: {$supervisor->id}, Name: {$supervisor->name}\n";
    }

    if ($supervisors->count() == 0) {
        echo "❌ No supervisors available!\n";
        exit(1);
    }

    // محاولة تعيين أول مشرف
    $firstSupervisor = $supervisors->first();
    echo "\nTrying to assign supervisor: {$firstSupervisor->name} (ID: {$firstSupervisor->id})\n";

    // محاكاة الطلب
    $employee->update(['direct_manager_id' => $firstSupervisor->id]);

    echo "✅ Assignment completed!\n";

    // التحقق من النتيجة
    $employee->refresh();
    echo "Updated Manager: " . ($employee->directManager ? $employee->directManager->name : 'Still None') . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
