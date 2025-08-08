<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "فحص عينة من البيانات...\n";

// Get sample records
$samples = DB::table('attendances')
    ->whereNotNull('check_in')
    ->whereNotNull('check_out')
    ->limit(5)
    ->get(['id', 'check_in', 'check_out', 'working_hours', 'overtime_hours']);

foreach ($samples as $sample) {
    $checkIn = Carbon::createFromFormat('H:i:s', $sample->check_in);
    $checkOut = Carbon::createFromFormat('H:i:s', $sample->check_out);
    
    $totalMinutes = $checkOut->diffInMinutes($checkIn);
    $workingHours = ($totalMinutes - 60) / 60; // subtract 1 hour lunch
    $overtimeHours = max(0, $workingHours - 8);
    
    echo sprintf(
        "ID: %d - حضور: %s - انصراف: %s - دقائق: %d - ساعات محسوبة: %.2f - إضافي محسوب: %.2f - ساعات حالية: %.2f - إضافي حالي: %.2f\n",
        $sample->id,
        $sample->check_in,
        $sample->check_out,
        $totalMinutes,
        $workingHours,
        $overtimeHours,
        $sample->working_hours,
        $sample->overtime_hours
    );
}

echo "\nإصلاح البيانات...\n";

// Fix the calculation
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
        $checkIn = Carbon::createFromFormat('H:i:s', $record->check_in);
        $checkOut = Carbon::createFromFormat('H:i:s', $record->check_out);
        
        $totalMinutes = $checkOut->diffInMinutes($checkIn);
        $workingHours = max(0, ($totalMinutes - 60) / 60);
        $overtimeHours = max(0, $workingHours - 8);
        
        DB::table('attendances')
            ->where('id', $record->id)
            ->update([
                'working_hours' => round($workingHours, 2),
                'overtime_hours' => round($overtimeHours, 2)
            ]);
    }
    
    $totalUpdated += $records->count();
    $offset += $batchSize;
    
    echo "تم تحديث $totalUpdated سجل...\n";
    
} while ($records->count() == $batchSize);

echo "انتهى التحديث. تم تحديث $totalUpdated سجل إجمالي.\n";

// Show final statistics
echo "\n=== الإحصائيات النهائية ===\n";
echo "إجمالي ساعات العمل: " . DB::table('attendances')->sum('working_hours') . "\n";
echo "إجمالي ساعات العمل الإضافي: " . DB::table('attendances')->sum('overtime_hours') . "\n";
echo "متوسط ساعات العمل اليومية: " . round(DB::table('attendances')->where('working_hours', '>', 0)->avg('working_hours'), 2) . "\n";
echo "أعلى ساعات عمل في يوم واحد: " . DB::table('attendances')->max('working_hours') . "\n";
?>
