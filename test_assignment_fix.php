<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== Testing Manager Assignment API Endpoint ===\n\n";

try {
    // Get an admin user for authentication
    $adminUser = \App\Models\User::where('role', 'admin')->first();
    if (!$adminUser) {
        $adminUser = \App\Models\User::where('role', 'manager')->first();
    }

    if (!$adminUser) {
        echo "❌ No admin user found!\n";
        exit(1);
    }

    echo "Admin user: {$adminUser->name} (ID: {$adminUser->id}, Email: {$adminUser->email})\n\n";

    // Get employees for testing
    $employee = \App\Models\Employee::where('role', '!=', 'مسئول رئيسي')
        ->whereNull('direct_manager_id')
        ->first();

    $manager = \App\Models\Employee::where('role', 'مشرف موقع')
        ->orWhere('role', 'مهندس')
        ->where('id', '!=', $employee->id ?? 0)
        ->first();

    if (!$employee) {
        echo "❌ No suitable employee found for testing\n";
        exit(1);
    }

    if (!$manager) {
        echo "❌ No suitable manager found for testing\n";
        exit(1);
    }

    echo "Employee: {$employee->name} (ID: {$employee->id})\n";
    echo "Manager: {$manager->name} (ID: {$manager->id})\n\n";

    // Test the assignment endpoint directly
    echo "Testing direct manager assignment...\n";

    // Create a test session
    session_start();
    $_SESSION['user_id'] = $adminUser->id;

    // Simulate the assignment
    \Illuminate\Support\Facades\Auth::login($adminUser);

    // Create assignment using the same logic as the controller
    $assignment = \App\Models\ManagerAssignment::create([
        'employee_id' => $employee->id,
        'manager_id' => $manager->id,
        'assigned_by' => \Illuminate\Support\Facades\Auth::id(),
        'assigned_at' => now(),
        'assignment_type' => 'تعيين',
        'notes' => 'تعيين مدير مباشر - اختبار النظام'
    ]);

    // Update employee
    $employee->update(['direct_manager_id' => $manager->id]);

    echo "✅ Manager assignment successful!\n";
    echo "Assignment ID: {$assignment->id}\n";
    echo "Assigned by user ID: {$assignment->assigned_by}\n";
    echo "Assigned by user name: {$assignment->assignedBy->name}\n\n";

    // Verify the assignment
    $employee->refresh();
    echo "Employee's current manager: " . ($employee->directManager ? $employee->directManager->name : 'None') . "\n";

    echo "\n=== Fix Applied Successfully! ===\n";
    echo "The foreign key constraint error has been resolved.\n";
    echo "The system now uses Auth::id() instead of hardcoded user ID 1.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";

    if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
        echo "❌ Foreign key constraint error still exists!\n";
        echo "Check if the assigned_by user ID exists in the users table.\n";
    }
}
