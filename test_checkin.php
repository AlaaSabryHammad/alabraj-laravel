<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing database connection...\n";

    // Test employee count
    $empCount = App\Models\Employee::count();
    echo "Employees count: " . $empCount . "\n";

    // Test attendance count
    $attCount = App\Models\Attendance::count();
    echo "Attendance records count: " . $attCount . "\n";

    // Check today's attendance records
    echo "\nToday's attendance records:\n";
    $todayRecords = App\Models\Attendance::where('date', '2025-08-01')->get();
    foreach ($todayRecords as $record) {
        echo "Employee ID: {$record->employee_id}, Status: {$record->status}, Check-in: {$record->check_in}\n";
    }

    // Find an employee without attendance record for today
    $employeesWithoutAttendance = App\Models\Employee::whereNotIn('id', function($query) {
        $query->select('employee_id')
              ->from('attendances')
              ->where('date', '2025-08-01');
    })->first();

    if ($employeesWithoutAttendance) {
        echo "\nTesting check-in for employee without attendance: {$employeesWithoutAttendance->id}\n";
        $attendance = App\Models\Attendance::create([
            'employee_id' => $employeesWithoutAttendance->id,
            'date' => '2025-08-01',
            'check_in' => '09:30',
            'status' => 'present',
            'late_minutes' => 30,
            'notes' => 'Test check-in'
        ]);
        echo "Success! Created attendance record with ID: " . $attendance->id . "\n";
    } else {
        echo "\nAll employees already have attendance records for today.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
