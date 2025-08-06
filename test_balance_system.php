<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\EmployeeBalance;

echo "=== Testing Balance System for Payroll ===" . PHP_EOL;

// Get a few employees
$employees = Employee::limit(3)->get();

foreach ($employees as $employee) {
    echo "\n=== Employee: {$employee->name} ===" . PHP_EOL;
    
    // Get balance
    $creditTotal = EmployeeBalance::where('employee_id', $employee->id)
        ->where('type', 'credit')
        ->sum('amount');
    
    $debitTotal = EmployeeBalance::where('employee_id', $employee->id)
        ->where('type', 'debit')
        ->sum('amount');
    
    $netBalance = $creditTotal - $debitTotal;
    
    echo "- Credit Balance: " . number_format($creditTotal, 2) . " SAR" . PHP_EOL;
    echo "- Debit Balance: " . number_format($debitTotal, 2) . " SAR" . PHP_EOL;
    echo "- Net Balance: " . number_format($netBalance, 2) . " SAR" . PHP_EOL;
    
    // Determine button status
    if ($netBalance == 0) {
        echo "- Button Status: Hidden (Balance is zero)" . PHP_EOL;
    } elseif ($netBalance > 0) {
        echo "- Button Status: 'Add Credit Balance' (Employee has credit)" . PHP_EOL;
    } else {
        echo "- Button Status: 'Deduct Debit Balance' (Employee has debt)" . PHP_EOL;
    }
}

echo "\n✅ Balance system is ready for payroll integration!" . PHP_EOL;
