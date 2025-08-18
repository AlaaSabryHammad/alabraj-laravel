<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "فحص جدول finances:\n";
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM finances');
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "إجمالي السجلات: $count\n";

    if ($count > 0) {
        $stmt = $pdo->query('SELECT SUM(amount) as total FROM finances WHERE type = "income" AND status = "completed"');
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "إجمالي الإيرادات المكتملة: " . number_format($total) . "\n";

        // فحص الشهر الحالي
        $currentMonth = date('m');
        $currentYear = date('Y');

        $query = "SELECT SUM(amount) as total FROM finances 
                  WHERE type = 'income' 
                  AND status = 'completed' 
                  AND strftime('%m', transaction_date) = '$currentMonth' 
                  AND strftime('%Y', transaction_date) = '$currentYear'";
        $stmt = $pdo->query($query);
        $currentMonthTotal = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "إيرادات الشهر الحالي ($currentMonth/$currentYear): " . number_format($currentMonthTotal) . "\n";

        echo "\nعينة من السجلات:\n";
        $stmt = $pdo->query('SELECT * FROM finances WHERE type = "income" LIMIT 5');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID: {$row['id']}, المبلغ: " . number_format($row['amount']) . ", التاريخ: {$row['transaction_date']}, الحالة: {$row['status']}\n";
        }
    }

    echo "\nفحص جدول revenue_vouchers:\n";
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM revenue_vouchers');
    $voucherCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "إجمالي السجلات: $voucherCount\n";

    if ($voucherCount > 0) {
        $stmt = $pdo->query('SELECT SUM(amount) as total FROM revenue_vouchers WHERE status = "approved"');
        $voucherTotal = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "إجمالي السندات المعتمدة: " . number_format($voucherTotal) . "\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}
