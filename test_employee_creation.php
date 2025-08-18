<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::createFromGlobals();
$response = $kernel->handle($request);

use App\Models\Employee;

try {
    echo "Testing employee creation with the problematic data:\n";

    $employeeData = [
        'status' => 'active',
        'name' => 'السيد نعجة',
        'position' => 'مهندس',
        'department' => 'الهندسة',
        'email' => 'sayed_test_' . time() . '@gmail.com', // Make unique
        'phone' => '056212355652',
        'hire_date' => '2020-01-01',
        'salary' => 5000,
        'working_hours' => 8,
        'national_id' => '54614151561',
        'role' => 'مهندس',
        'sponsorship_status' => 'مؤسسة الزفاف الذهبي',
        'category' => 'B',
        'location_id' => 2,
        'nationality' => 'مصري',
        'user_id' => 403
    ];

    $employee = Employee::create($employeeData);

    echo "✅ Employee created successfully with ID: " . $employee->id . "\n";
    echo "Status: " . $employee->status . "\n";
    echo "Name: " . $employee->name . "\n";

    // Clean up - delete the test employee
    $employee->delete();
    echo "🗑️ Test employee deleted\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "File: " . $e->getFile() . "\n";
}
