<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use App\Models\ManagerAssignment;

// Bootstrap Laravel app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "=== Testing Manager Assignment Functionality ===\n\n";

try {
    // Find an admin user
    $adminUser = User::where('role', 'admin')->first();
    if (!$adminUser) {
        $adminUser = User::where('role', 'manager')->first();
    }

    if (!$adminUser) {
        echo "No admin or manager user found!\n";
        exit(1);
    }

    echo "Using admin user: {$adminUser->name} (ID: {$adminUser->id})\n";

    // Simulate authentication
    Auth::login($adminUser);
    echo "Authenticated as: " . Auth::user()->name . "\n";
    echo "Auth ID: " . Auth::id() . "\n\n";

    // Get first employee and first manager for testing
    $employee = Employee::where('role', '!=', 'مسئول رئيسي')->first();
    $manager = Employee::where('role', 'مشرف موقع')->orWhere('role', 'مهندس')->first();

    if (!$employee || !$manager) {
        echo "No suitable employee or manager found for testing\n";
        exit(1);
    }

    echo "Testing employee: {$employee->name} (ID: {$employee->id})\n";
    echo "Testing manager: {$manager->name} (ID: {$manager->id})\n\n";

    // Test creating a manager assignment
    $assignment = ManagerAssignment::create([
        'employee_id' => $employee->id,
        'manager_id' => $manager->id,
        'assigned_by' => Auth::id(),
        'assigned_at' => now(),
        'assignment_type' => 'تعيين',
        'notes' => 'تعيين تجريبي للاختبار'
    ]);

    echo "✅ Manager assignment created successfully!\n";
    echo "Assignment ID: {$assignment->id}\n";
    echo "Employee: {$assignment->employee->name}\n";
    echo "Manager: {$assignment->manager->name}\n";
    echo "Assigned by: {$assignment->assignedBy->name}\n";
    echo "Assignment type: {$assignment->assignment_type}\n";
    echo "Notes: {$assignment->notes}\n\n";

    // Update the employee's direct manager
    $employee->update(['direct_manager_id' => $manager->id]);
    echo "✅ Employee's direct manager updated successfully!\n\n";

    echo "=== Test completed successfully! ===\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
