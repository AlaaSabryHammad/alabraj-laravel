<?php

use Illuminate\Support\Facades\DB;

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

// حل مشكلة اللود
\Illuminate\Foundation\AliasLoader::getInstance([
    'Illuminate\Container\Container',
    'Illuminate\Foundation\Application'
]);

try {
    echo "=== فحص جدول finances ===\n";
    $financeCount = DB::table('finances')->count();
    echo "إجمالي السجلات في finances: $financeCount\n";

    if ($financeCount > 0) {
        $incomeRecords = DB::table('finances')->where('type', 'income')->count();
        echo "سجلات الإيرادات: $incomeRecords\n";

        $completedIncomeRecords = DB::table('finances')
            ->where('type', 'income')
            ->where('status', 'completed')
            ->count();
        echo "سجلات الإيرادات المكتملة: $completedIncomeRecords\n";

        $totalIncome = DB::table('finances')
            ->where('type', 'income')
            ->where('status', 'completed')
            ->sum('amount');
        echo "إجمالي الإيرادات: " . number_format($totalIncome) . "\n";

        // فحص الشهر الحالي
        $currentMonth = date('m');
        $currentYear = date('Y');

        $currentMonthIncome = DB::table('finances')
            ->where('type', 'income')
            ->where('status', 'completed')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');
        echo "إيرادات الشهر الحالي ($currentMonth/$currentYear): " . number_format($currentMonthIncome) . "\n";

        // عرض بعض السجلات
        echo "\n--- عينة من السجلات ---\n";
        $sampleRecords = DB::table('finances')
            ->where('type', 'income')
            ->select('id', 'amount', 'transaction_date', 'status', 'description')
            ->limit(5)
            ->get();

        foreach ($sampleRecords as $record) {
            echo "ID: {$record->id}, المبلغ: " . number_format($record->amount) . ", التاريخ: {$record->transaction_date}, الحالة: {$record->status}\n";
        }
    }

    echo "\n=== فحص جدول revenue_vouchers ===\n";
    $voucherCount = DB::table('revenue_vouchers')->count();
    echo "إجمالي السجلات في revenue_vouchers: $voucherCount\n";

    if ($voucherCount > 0) {
        $approvedVouchers = DB::table('revenue_vouchers')->where('status', 'approved')->count();
        echo "سندات معتمدة: $approvedVouchers\n";

        $totalVoucherAmount = DB::table('revenue_vouchers')
            ->where('status', 'approved')
            ->sum('amount');
        echo "إجمالي مبالغ السندات المعتمدة: " . number_format($totalVoucherAmount) . "\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
