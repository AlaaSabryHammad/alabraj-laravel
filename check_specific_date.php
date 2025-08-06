<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Attendance;
use App\Models\Employee;

$date = '2025-05-05';
echo "=== Checking data for date: $date ===\n\n";

// Check with exact date match
$exactMatch = Attendance::where('date', $date)->count();
echo "Exact date match (date = '$date'): $exactMatch records\n";

// Check with whereDate
$whereDateMatch = Attendance::whereDate('date', $date)->count();
echo "WhereDate match (whereDate('date', '$date')): $whereDateMatch records\n";

// Show actual date formats in database
echo "\nActual date formats in database for this period:\n";
$records = Attendance::where('date', 'LIKE', '2025-05%')
    ->select('date')
    ->distinct()
    ->orderBy('date')
    ->limit(10)
    ->get();

foreach ($records as $record) {
    $count = Attendance::where('date', $record->date)->count();
    echo "- {$record->date}: $count records\n";
}

// Get specific records for the date
echo "\n=== Sample records for $date ===\n";
$sampleRecords = Attendance::whereDate('date', $date)
    ->with('employee')
    ->limit(10)
    ->get();

if ($sampleRecords->count() > 0) {
    echo "Found {$sampleRecords->count()} sample records:\n";
    foreach ($sampleRecords as $record) {
        echo "- Employee: {$record->employee->name}\n";
        echo "  Date: {$record->date}\n";
        echo "  Status: {$record->status}\n";
        echo "  Check In: {$record->check_in}\n";
        echo "  Check Out: {$record->check_out}\n";
        echo "  Working Hours: {$record->working_hours}\n";
        echo "  ---\n";
    }
} else {
    echo "No records found for $date\n";
}

// Test the query that the controller uses
echo "\n=== Testing Controller Query ===\n";
$employees = Employee::where('status', 'active')
    ->with(['attendances' => function($query) use ($date) {
        $query->whereDate('date', $date);
    }])
    ->limit(5)
    ->get();

echo "Found " . $employees->count() . " employees\n";
foreach ($employees as $employee) {
    $attendanceCount = $employee->attendances->count();
    echo "- {$employee->name}: $attendanceCount attendance record(s)\n";
    if ($attendanceCount > 0) {
        $attendance = $employee->attendances->first();
        echo "  Status: {$attendance->status}, Check In: {$attendance->check_in}, Check Out: {$attendance->check_out}\n";
    }
}
