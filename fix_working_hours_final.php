<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "إصلاح ساعات العمل بطريقة بسيطة...\n";

// Test calculation
$testRecord = DB::table('attendances')
    ->whereNotNull('check_in')
    ->whereNotNull('check_out')
    ->first();

echo "اختبار الحساب:\n";
echo "حضور: " . $testRecord->check_in . "\n";
echo "انصراف: " . $testRecord->check_out . "\n";

// Simple time parsing
$checkInTime = $testRecord->check_in;
$checkOutTime = $testRecord->check_out;

// Convert to timestamp
$checkIn = strtotime($checkInTime);
$checkOut = strtotime($checkOutTime);

// If checkout is before checkin, assume next day
if ($checkOut < $checkIn) {
    $checkOut += 24 * 60 * 60; // add 24 hours
}

$seconds = $checkOut - $checkIn;
$minutes = $seconds / 60;
$hours = $minutes / 60;
$workingHours = max(0, $hours - 1); // subtract 1 hour lunch
$overtimeHours = max(0, $workingHours - 8);

echo "الثواني: $seconds\n";
echo "الدقائق: $minutes\n";
echo "الساعات الإجمالية: $hours\n";
echo "ساعات العمل: $workingHours\n";
echo "الإضافي: $overtimeHours\n\n";

echo "تحديث جميع السجلات...\n";

$updated = DB::update("
    UPDATE attendances 
    SET 
        working_hours = GREATEST(0, ROUND(
            (CASE 
                WHEN TIME_TO_SEC(check_out) < TIME_TO_SEC(check_in) 
                THEN (TIME_TO_SEC(check_out) + 86400 - TIME_TO_SEC(check_in)) 
                ELSE (TIME_TO_SEC(check_out) - TIME_TO_SEC(check_in)) 
            END / 3600) - 1, 2)
        ),
        overtime_hours = GREATEST(0, ROUND(
            GREATEST(0, (CASE 
                WHEN TIME_TO_SEC(check_out) < TIME_TO_SEC(check_in) 
                THEN (TIME_TO_SEC(check_out) + 86400 - TIME_TO_SEC(check_in)) 
                ELSE (TIME_TO_SEC(check_out) - TIME_TO_SEC(check_in)) 
            END / 3600) - 1) - 8, 2)
        )
    WHERE check_in IS NOT NULL 
    AND check_out IS NOT NULL 
    AND status IN ('present', 'late')
");

echo "تم تحديث $updated سجل.\n\n";

// Show statistics
echo "=== الإحصائيات النهائية ===\n";
$stats = DB::selectOne("
    SELECT 
        ROUND(SUM(working_hours), 2) as total_working_hours,
        ROUND(SUM(overtime_hours), 2) as total_overtime_hours,
        ROUND(AVG(CASE WHEN working_hours > 0 THEN working_hours END), 2) as avg_working_hours,
        ROUND(MAX(working_hours), 2) as max_working_hours,
        COUNT(CASE WHEN working_hours > 0 THEN 1 END) as working_records
    FROM attendances
");

echo "إجمالي ساعات العمل: " . $stats->total_working_hours . "\n";
echo "إجمالي ساعات العمل الإضافي: " . $stats->total_overtime_hours . "\n";
echo "متوسط ساعات العمل اليومية: " . $stats->avg_working_hours . "\n";
echo "أعلى ساعات عمل في يوم واحد: " . $stats->max_working_hours . "\n";
echo "عدد أيام العمل: " . $stats->working_records . "\n\n";

// Show samples
echo "=== عينة من السجلات المحدثة ===\n";
$samples = DB::table('attendances')
    ->join('employees', 'attendances.employee_id', '=', 'employees.id')
    ->select('employees.name', 'attendances.date', 'attendances.check_in', 'attendances.check_out', 
             'attendances.working_hours', 'attendances.overtime_hours', 'attendances.notes')
    ->where('attendances.working_hours', '>', 0)
    ->where('attendances.date', '>=', '2025-08-01')
    ->orderBy('attendances.working_hours', 'desc')
    ->limit(10)
    ->get();

foreach ($samples as $sample) {
    echo sprintf(
        "%s - %s - %s إلى %s - عمل: %.2fس - إضافي: %.2fس - %s\n",
        $sample->name,
        $sample->date,
        $sample->check_in,
        $sample->check_out,
        $sample->working_hours,
        $sample->overtime_hours,
        $sample->notes ?? 'لا توجد ملاحظات'
    );
}
?>
