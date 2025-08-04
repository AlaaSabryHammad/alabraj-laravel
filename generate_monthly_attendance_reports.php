<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== توليد بيانات الحضور الشهرية من يناير 2024 حتى أغسطس 2025 ===\n\n";

try {
    // الحصول على جميع الموظفين النشطين
    $employees = DB::table('employees')->where('status', 'active')->get();
    echo "تم العثور على " . count($employees) . " موظف نشط\n\n";

    if (count($employees) == 0) {
        echo "❌ لا يوجد موظفين نشطين في النظام\n";
        exit;
    }

    // تاريخ البداية والنهاية
    $startDate = Carbon::create(2024, 1, 1); // 1 يناير 2024
    $endDate = Carbon::create(2025, 8, 4);   // 4 أغسطس 2025 (اليوم)

    echo "📅 توليد بيانات الحضور من: {$startDate->format('Y-m-d')} إلى: {$endDate->format('Y-m-d')}\n";
    echo "📊 إجمالي الأيام: " . $startDate->diffInDays($endDate) . " يوم\n\n";

    // إحصائيات
    $totalRecords = 0;
    $monthCount = 0;

    // التكرار عبر كل شهر
    $currentDate = $startDate->copy();
    while ($currentDate <= $endDate) {
        $monthStart = $currentDate->copy()->startOfMonth();
        $monthEnd = $currentDate->copy()->endOfMonth();

        // التأكد من عدم تجاوز تاريخ النهاية
        if ($monthEnd > $endDate) {
            $monthEnd = $endDate;
        }

        $monthName = $currentDate->locale('ar')->translatedFormat('F Y');
        echo "📅 معالجة شهر: {$monthName}\n";

        // حذف البيانات الموجودة لهذا الشهر لتجنب التكرار
        $deletedCount = DB::table('attendances')
            ->whereBetween('date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
            ->delete();

        if ($deletedCount > 0) {
            echo "  🗑️  تم حذف {$deletedCount} سجل حضور موجود مسبقاً\n";
        }

        $monthRecords = 0;

        // التكرار عبر كل يوم في الشهر
        $dayDate = $monthStart->copy();
        while ($dayDate <= $monthEnd) {
            // تخطي أيام الجمعة والسبت (عطلة نهاية الأسبوع)
            if (!in_array($dayDate->dayOfWeek, [5, 6])) { // 5=الجمعة، 6=السبت

                foreach ($employees as $employee) {
                    // تحديد حالة الحضور بشكل عشوائي واقعي
                    $attendanceScenario = rand(1, 100);

                    if ($attendanceScenario <= 75) {
                        // 75% حضور عادي
                        $status = 'present';
                        $checkIn = sprintf('%02d:%02d', rand(7, 8), rand(0, 59));
                        $checkOut = sprintf('%02d:%02d', rand(16, 18), rand(0, 59));
                        $lateMinutes = 0;

                        // حساب ساعات العمل
                        $checkInTime = Carbon::parse($checkIn);
                        $checkOutTime = Carbon::parse($checkOut);
                        $workingHours = $checkOutTime->diffInHours($checkInTime, true);

                    } elseif ($attendanceScenario <= 85) {
                        // 10% تأخير
                        $status = 'late';
                        $lateMinutes = rand(15, 120); // تأخير من 15 إلى 120 دقيقة
                        $checkInHour = 8 + floor($lateMinutes / 60);
                        $checkInMinute = $lateMinutes % 60;
                        $checkIn = sprintf('%02d:%02d', $checkInHour, $checkInMinute);
                        $checkOut = sprintf('%02d:%02d', rand(16, 18), rand(0, 59));

                        $checkInTime = Carbon::parse($checkIn);
                        $checkOutTime = Carbon::parse($checkOut);
                        $workingHours = $checkOutTime->diffInHours($checkInTime, true);

                    } elseif ($attendanceScenario <= 92) {
                        // 7% غياب
                        $status = 'absent';
                        $checkIn = null;
                        $checkOut = null;
                        $lateMinutes = 0;
                        $workingHours = 0;

                    } elseif ($attendanceScenario <= 97) {
                        // 5% إجازة
                        $status = 'leave';
                        $checkIn = null;
                        $checkOut = null;
                        $lateMinutes = 0;
                        $workingHours = 0;

                    } else {
                        // 3% إجازة مرضية
                        $status = 'sick_leave';
                        $checkIn = null;
                        $checkOut = null;
                        $lateMinutes = 0;
                        $workingHours = 0;
                    }

                    // إنشاء ملاحظات واقعية
                    $notes = '';
                    switch ($status) {
                        case 'present':
                            $notes = 'حضور منتظم';
                            break;
                        case 'late':
                            $reasons = ['زحمة سير', 'ظروف شخصية', 'تأخير المواصلات', 'ظروف طارئة'];
                            $notes = "تأخر {$lateMinutes} دقيقة - " . $reasons[array_rand($reasons)];
                            break;
                        case 'absent':
                            $reasons = ['غياب بدون عذر', 'ظروف شخصية', 'مرض طارئ'];
                            $notes = $reasons[array_rand($reasons)];
                            break;
                        case 'leave':
                            $reasons = ['إجازة سنوية', 'إجازة عارضة', 'إجازة شخصية'];
                            $notes = $reasons[array_rand($reasons)];
                            break;
                        case 'sick_leave':
                            $notes = 'إجازة مرضية';
                            break;
                    }

                    // إدراج سجل الحضور
                    DB::table('attendances')->insert([
                        'employee_id' => $employee->id,
                        'date' => $dayDate->format('Y-m-d'),
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                        'status' => $status,
                        'notes' => $notes,
                        'late_minutes' => $lateMinutes,
                        'working_hours' => $workingHours,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $monthRecords++;
                    $totalRecords++;
                }
            }

            $dayDate->addDay();
        }

        echo "  ✅ تم إنشاء {$monthRecords} سجل حضور\n";
        $monthCount++;

        // الانتقال للشهر التالي
        $currentDate->addMonth()->startOfMonth();
    }

    echo "\n=== النتائج النهائية ===\n";
    echo "✅ تم إنشاء بيانات الحضور لـ {$monthCount} شهر\n";
    echo "✅ إجمالي سجلات الحضور: " . number_format($totalRecords) . " سجل\n";
    echo "👥 عدد الموظفين: " . count($employees) . " موظف\n";

    // إحصائيات إضافية
    $stats = DB::table('attendances')
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();

    echo "\n=== إحصائيات الحضور ===\n";
    foreach ($stats as $stat) {
        $statusText = [
            'present' => 'حضور عادي',
            'late' => 'تأخير',
            'absent' => 'غياب',
            'leave' => 'إجازة',
            'sick_leave' => 'إجازة مرضية'
        ];

        $percentage = round(($stat->count / $totalRecords) * 100, 1);
        echo "📊 {$statusText[$stat->status]}: " . number_format($stat->count) . " ({$percentage}%)\n";
    }

    echo "\n🎉 تم الانتهاء بنجاح! يمكنك الآن الوصول لتقارير الحضور الشهرية عبر:\n";
    echo "🔗 http://127.0.0.1:8000/employees/attendance/report\n";

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "📍 الملف: " . $e->getFile() . "\n";
    echo "📍 السطر: " . $e->getLine() . "\n";
}
