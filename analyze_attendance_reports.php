<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== إنشاء تقارير تفصيلية للحضور الشهري ===\n\n";

try {
    // إنشاء تقرير لكل شهر من يناير 2024 حتى أغسطس 2025
    $startDate = Carbon::create(2024, 1, 1);
    $endDate = Carbon::create(2025, 8, 4);

    $reports = [];
    $currentDate = $startDate->copy();

    while ($currentDate <= $endDate) {
        $monthStart = $currentDate->copy()->startOfMonth();
        $monthEnd = $currentDate->copy()->endOfMonth();

        if ($monthEnd > $endDate) {
            $monthEnd = $endDate;
        }

        $monthName = $currentDate->locale('ar')->translatedFormat('F Y');

        // إحصائيات الشهر
        $monthStats = DB::table('attendances')
            ->select('status', DB::raw('count(*) as count'))
            ->whereBetween('date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // إجمالي الأيام العملية في الشهر (بدون جمعة وسبت)
        $workingDays = 0;
        $tempDate = $monthStart->copy();
        while ($tempDate <= $monthEnd) {
            if (!in_array($tempDate->dayOfWeek, [5, 6])) {
                $workingDays++;
            }
            $tempDate->addDay();
        }

        // عدد الموظفين النشطين
        $activeEmployees = DB::table('employees')->where('status', 'active')->count();
        $expectedRecords = $workingDays * $activeEmployees;

        $reports[] = [
            'month' => $currentDate->month,
            'year' => $currentDate->year,
            'month_name' => $monthName,
            'working_days' => $workingDays,
            'active_employees' => $activeEmployees,
            'expected_records' => $expectedRecords,
            'present' => $monthStats->get('present')->count ?? 0,
            'late' => $monthStats->get('late')->count ?? 0,
            'absent' => $monthStats->get('absent')->count ?? 0,
            'leave' => $monthStats->get('leave')->count ?? 0,
            'sick_leave' => $monthStats->get('sick_leave')->count ?? 0,
            'total_records' => $monthStats->sum('count'),
        ];

        echo "📊 {$monthName}: ";
        echo "حضور=" . ($monthStats->get('present')->count ?? 0);
        echo ", تأخير=" . ($monthStats->get('late')->count ?? 0);
        echo ", غياب=" . ($monthStats->get('absent')->count ?? 0);
        echo ", إجازة=" . ($monthStats->get('leave')->count ?? 0);
        echo ", مرضي=" . ($monthStats->get('sick_leave')->count ?? 0);
        echo "\n";

        $currentDate->addMonth();
    }

    echo "\n=== ملخص التقارير الشهرية ===\n";

    foreach ($reports as $report) {
        $attendanceRate = round(($report['present'] / $report['expected_records']) * 100, 1);
        $lateRate = round(($report['late'] / $report['expected_records']) * 100, 1);
        $absenteeRate = round(($report['absent'] / $report['expected_records']) * 100, 1);

        echo "\n📅 {$report['month_name']}:\n";
        echo "   👥 الموظفين النشطين: {$report['active_employees']}\n";
        echo "   📋 أيام العمل: {$report['working_days']}\n";
        echo "   ✅ معدل الحضور: {$attendanceRate}%\n";
        echo "   ⏰ معدل التأخير: {$lateRate}%\n";
        echo "   ❌ معدل الغياب: {$absenteeRate}%\n";
    }

    // إحصائيات إجمالية لكل السنة
    echo "\n=== الإحصائيات الإجمالية ===\n";

    $totalStats = DB::table('attendances')
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get()
        ->keyBy('status');

    $totalRecords = $totalStats->sum('count');

    foreach (['present', 'late', 'absent', 'leave', 'sick_leave'] as $status) {
        $count = $totalStats->get($status)->count ?? 0;
        $percentage = round(($count / $totalRecords) * 100, 1);

        $statusText = [
            'present' => '✅ الحضور المنتظم',
            'late' => '⏰ التأخير',
            'absent' => '❌ الغياب',
            'leave' => '🏖️ الإجازات',
            'sick_leave' => '🏥 الإجازات المرضية'
        ];

        echo "{$statusText[$status]}: " . number_format($count) . " ({$percentage}%)\n";
    }

    // أفضل وأسوأ الشهور من ناحية الحضور
    echo "\n=== تحليل الأداء الشهري ===\n";

    usort($reports, function($a, $b) {
        $aRate = ($a['present'] / $a['expected_records']) * 100;
        $bRate = ($b['present'] / $b['expected_records']) * 100;
        return $bRate <=> $aRate;
    });

    echo "🏆 أفضل 3 شهور من ناحية الحضور:\n";
    for ($i = 0; $i < 3 && $i < count($reports); $i++) {
        $rate = round(($reports[$i]['present'] / $reports[$i]['expected_records']) * 100, 1);
        echo "   " . ($i + 1) . ". {$reports[$i]['month_name']}: {$rate}%\n";
    }

    echo "\n🚨 أسوأ 3 شهور من ناحية الحضور:\n";
    for ($i = count($reports) - 3; $i < count($reports); $i++) {
        if ($i >= 0) {
            $rate = round(($reports[$i]['present'] / $reports[$i]['expected_records']) * 100, 1);
            echo "   " . (count($reports) - $i) . ". {$reports[$i]['month_name']}: {$rate}%\n";
        }
    }

    // معلومات إضافية عن الموظفين
    echo "\n=== إحصائيات الموظفين ===\n";

    // أكثر الموظفين حضوراً
    $topAttendees = DB::table('attendances')
        ->join('employees', 'attendances.employee_id', '=', 'employees.id')
        ->where('attendances.status', 'present')
        ->select('employees.name', DB::raw('count(*) as present_days'))
        ->groupBy('employees.id', 'employees.name')
        ->orderBy('present_days', 'desc')
        ->limit(5)
        ->get();

    echo "🏆 أكثر 5 موظفين حضوراً:\n";
    foreach ($topAttendees as $index => $employee) {
        echo "   " . ($index + 1) . ". {$employee->name}: {$employee->present_days} يوم\n";
    }

    // أكثر الموظفين تأخيراً
    $topLateComers = DB::table('attendances')
        ->join('employees', 'attendances.employee_id', '=', 'employees.id')
        ->where('attendances.status', 'late')
        ->select('employees.name', DB::raw('count(*) as late_days'))
        ->groupBy('employees.id', 'employees.name')
        ->orderBy('late_days', 'desc')
        ->limit(5)
        ->get();

    echo "\n⏰ أكثر 5 موظفين تأخيراً:\n";
    foreach ($topLateComers as $index => $employee) {
        echo "   " . ($index + 1) . ". {$employee->name}: {$employee->late_days} يوم\n";
    }

    echo "\n🎯 تم إنشاء التحليل التفصيلي بنجاح!\n";
    echo "🔗 يمكنك الوصول للتقارير عبر: http://127.0.0.1:8000/employees/attendance/report\n";

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
