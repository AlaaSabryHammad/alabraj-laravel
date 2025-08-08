<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "إصلاح حساب ساعات العمل...\n";

// Test with one record first
$testRecord = DB::table('attendances')
    ->whereNotNull('check_in')
    ->whereNotNull('check_out')
    ->first();

echo "اختبار مع سجل واحد:\n";
echo "حضور: " . $testRecord->check_in . "\n";
echo "انصراف: " . $testRecord->check_out . "\n";

// Parse times correctly with today's date
$checkIn = Carbon::today()->setTimeFromTimeString($testRecord->check_in);
$checkOut = Carbon::today()->setTimeFromTimeString($testRecord->check_out);

// Handle overnight shifts
if ($checkOut->lt($checkIn)) {
    $checkOut->addDay();
}

$totalMinutes = $checkOut->diffInMinutes($checkIn);
$workingHours = max(0, ($totalMinutes - 60) / 60); // subtract lunch hour
$overtimeHours = max(0, $workingHours - 8);

echo "إجمالي الدقائق: $totalMinutes\n";
echo "ساعات العمل: $workingHours\n";
echo "ساعات إضافية: $overtimeHours\n\n";

echo "بدء تحديث جميع السجلات...\n";

$batchSize = 1000;
$offset = 0;
$totalUpdated = 0;

do {
    $records = DB::table('attendances')
        ->whereNotNull('check_in')
        ->whereNotNull('check_out')
        ->whereIn('status', ['present', 'late'])
        ->offset($offset)
        ->limit($batchSize)
        ->get();
    
    if ($records->count() == 0) break;
    
    foreach ($records as $record) {
        try {
            $checkIn = Carbon::today()->setTimeFromTimeString($record->check_in);
            $checkOut = Carbon::today()->setTimeFromTimeString($record->check_out);
            
            // Handle overnight shifts
            if ($checkOut->lt($checkIn)) {
                $checkOut->addDay();
            }
            
            $totalMinutes = $checkOut->diffInMinutes($checkIn);
            $workingHours = max(0, ($totalMinutes - 60) / 60);
            $overtimeHours = max(0, $workingHours - 8);
            
            DB::table('attendances')
                ->where('id', $record->id)
                ->update([
                    'working_hours' => round($workingHours, 2),
                    'overtime_hours' => round($overtimeHours, 2)
                ]);
        } catch (Exception $e) {
            echo "خطأ في السجل ID: " . $record->id . " - " . $e->getMessage() . "\n";
        }
    }
    
    $totalUpdated += $records->count();
    $offset += $batchSize;
    
    if ($totalUpdated % 5000 == 0) {
        echo "تم تحديث $totalUpdated سجل...\n";
    }
    
} while ($records->count() == $batchSize);

echo "انتهى التحديث. تم تحديث $totalUpdated سجل إجمالي.\n\n";

// Show final statistics
echo "=== الإحصائيات النهائية ===\n";
$totalWorkingHours = DB::table('attendances')->sum('working_hours');
$totalOvertimeHours = DB::table('attendances')->sum('overtime_hours');
$avgWorkingHours = DB::table('attendances')->where('working_hours', '>', 0)->avg('working_hours');
$maxWorkingHours = DB::table('attendances')->max('working_hours');

echo "إجمالي ساعات العمل: " . round($totalWorkingHours, 2) . "\n";
echo "إجمالي ساعات العمل الإضافي: " . round($totalOvertimeHours, 2) . "\n";
echo "متوسط ساعات العمل اليومية: " . round($avgWorkingHours, 2) . "\n";
echo "أعلى ساعات عمل في يوم واحد: " . round($maxWorkingHours, 2) . "\n";

// Show some samples
echo "\n=== عينة من السجلات المحدثة ===\n";
$samples = DB::table('attendances')
    ->join('employees', 'attendances.employee_id', '=', 'employees.id')
    ->select('employees.name', 'attendances.date', 'attendances.check_in', 'attendances.check_out', 
             'attendances.working_hours', 'attendances.overtime_hours')
    ->where('attendances.working_hours', '>', 0)
    ->where('attendances.date', '>=', '2025-08-01')
    ->limit(5)
    ->get();

foreach ($samples as $sample) {
    echo sprintf(
        "%s - %s - %s إلى %s - ساعات: %.2f - إضافي: %.2f\n",
        $sample->name,
        $sample->date,
        $sample->check_in,
        $sample->check_out,
        $sample->working_hours,
        $sample->overtime_hours
    );
}
?>
