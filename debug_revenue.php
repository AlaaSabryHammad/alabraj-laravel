<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

echo "فحص بيانات Finance:\n";
echo "إجمالي السجلات: " . \App\Models\Finance::count() . "\n";
echo "سجلات الإيرادات: " . \App\Models\Finance::where('type', 'income')->count() . "\n";
echo "سجلات الإيرادات المكتملة: " . \App\Models\Finance::where('type', 'income')->where('status', 'completed')->count() . "\n";

$financeIncome = \App\Models\Finance::where('type', 'income')
    ->where('status', 'completed')
    ->sum('amount');
echo "إجمالي الإيرادات المكتملة: " . number_format($financeIncome) . "\n";

$currentMonth = \Carbon\Carbon::now()->month;
$currentYear = \Carbon\Carbon::now()->year;
echo "الشهر الحالي: $currentMonth\n";
echo "السنة الحالية: $currentYear\n";

$currentMonthIncome = \App\Models\Finance::where('type', 'income')
    ->where('status', 'completed')
    ->whereMonth('transaction_date', $currentMonth)
    ->whereYear('transaction_date', $currentYear)
    ->sum('amount');
echo "إيرادات الشهر الحالي: " . number_format($currentMonthIncome) . "\n";

echo "\n---\n";
echo "فحص بيانات RevenueVoucher:\n";
echo "إجمالي السجلات: " . \App\Models\RevenueVoucher::count() . "\n";
echo "سجلات معتمدة: " . \App\Models\RevenueVoucher::where('status', 'approved')->count() . "\n";

$revenueVoucherIncome = \App\Models\RevenueVoucher::where('status', 'approved')
    ->whereMonth('voucher_date', $currentMonth)
    ->whereYear('voucher_date', $currentYear)
    ->sum('amount');
echo "إيرادات سندات الشهر الحالي: " . number_format($revenueVoucherIncome) . "\n";

$totalRevenue = $currentMonthIncome + $revenueVoucherIncome;
echo "\nإجمالي الإيرادات للشهر الحالي: " . number_format($totalRevenue) . "\n";

// فحص بعض السجلات الفعلية
echo "\n--- عينة من بيانات Finance ---\n";
$sampleFinance = \App\Models\Finance::where('type', 'income')->take(5)->get(['id', 'amount', 'transaction_date', 'status', 'description']);
foreach ($sampleFinance as $record) {
    echo "ID: {$record->id}, المبلغ: " . number_format($record->amount) . ", التاريخ: {$record->transaction_date}, الحالة: {$record->status}\n";
}
