<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Attendance;
use App\Models\Employee;

echo "=== Checking Database Data ===\n";

// Check total attendance records
$totalAttendance = Attendance::count();
echo "Total attendance records: $totalAttendance\n";

// Check records for specific date
$date = '2025-05-05';
$recordsForDate = Attendance::where('date', $date)->count();
echo "Records for $date: $recordsForDate\n";

// Show recent dates with data
echo "\nRecent dates with attendance data:\n";
$recentDates = Attendance::select('date')
    ->distinct()
    ->orderBy('date', 'desc')
    ->limit(10)
    ->pluck('date');

foreach ($recentDates as $recentDate) {
    $count = Attendance::where('date', $recentDate)->count();
    echo "- $recentDate: $count records\n";
}

// Check employees count
$employeesCount = Employee::where('status', 'active')->count();
echo "\nActive employees: $employeesCount\n";

// Show some sample attendance data if exists
echo "\nSample attendance records:\n";
$sampleRecords = Attendance::with('employee')
    ->orderBy('date', 'desc')
    ->limit(5)
    ->get();

foreach ($sampleRecords as $record) {
    echo "- {$record->date}: {$record->employee->name} ({$record->status})\n";
}
