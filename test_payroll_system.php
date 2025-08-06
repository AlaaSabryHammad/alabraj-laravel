<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\EmployeeBalance;

echo "=== Testing Payroll System with Overtime Data ===" . PHP_EOL;

// Test month: August 2025
$year = 2025;
$month = 8;
$startDate = "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
$endDate = "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-31";

echo "Testing period: $startDate to $endDate" . PHP_EOL . PHP_EOL;

// Get first 5 employees for testing
$employees = Employee::limit(5)->get();

foreach ($employees as $employee) {
    echo "=== Employee: {$employee->name} ===" . PHP_EOL;
    
    // Get attendance data
    $attendances = Attendance::where('employee_id', $employee->id)
        ->whereBetween('date', [$startDate, $endDate])
        ->get();
    
    $totalDays = $attendances->count();
    $presentDays = $attendances->where('status', 'present')->count();
    $absentDays = $totalDays - $presentDays;
    
    $totalWorkingHours = $attendances->sum('working_hours');
    $totalOvertimeHours = $attendances->sum('overtime_hours');
    
    echo "- Total attendance records: $totalDays" . PHP_EOL;
    echo "- Present days: $presentDays" . PHP_EOL;
    echo "- Absent days: $absentDays" . PHP_EOL;
    echo "- Total working hours: $totalWorkingHours" . PHP_EOL;
    echo "- Total overtime hours: $totalOvertimeHours" . PHP_EOL;
    
    // Get balance
    $balance = EmployeeBalance::where('employee_id', $employee->id)->first();
    if ($balance) {
        $creditAmount = $balance->credit_amount ?? 0;
        $debitAmount = $balance->debit_amount ?? 0;
        $netBalance = $creditAmount - $debitAmount;
        echo "- Balance: Credit {$creditAmount} SR, Debit {$debitAmount} SR, Net: {$netBalance} SR" . PHP_EOL;
    } else {
        echo "- No balance record found" . PHP_EOL;
    }
    
    echo PHP_EOL;
}

echo "=== System is ready for full payroll processing! ===" . PHP_EOL;
