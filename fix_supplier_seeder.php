<?php
$file = 'database/seeders/SupplierSeeder.php';
$content = file_get_contents($file);
$content = str_replace([
    "'payment_terms' => 'cash_on_delivery'",
    "'payment_terms' => 'net_15'",
    "'payment_terms' => 'net_30'",
    "'payment_terms' => 'net_45'",
    "'payment_terms' => 'net_60'",
    "'status' => 'active'",
    "'status' => 'inactive'",
    '$faker->randomElement([\'active\', \'active\', \'active\', \'inactive\'])', // 75% active
], [
    "'payment_terms' => 'نقدي'",
    "'payment_terms' => 'آجل 30 يوم'",
    "'payment_terms' => 'آجل 30 يوم'",
    "'payment_terms' => 'آجل 60 يوم'",
    "'payment_terms' => 'آجل 90 يوم'",
    "'status' => 'نشط'",
    "'status' => 'غير نشط'",
    '$faker->randomElement($statuses)',
], $content);
file_put_contents($file, $content);
echo 'تم إصلاح الملف بنجاح';
