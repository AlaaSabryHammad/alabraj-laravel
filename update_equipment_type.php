<?php

use App\Models\Equipment;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// فحص المعدات الموجودة بنوع "شاحنة"
$trucks = Equipment::where('type', 'شاحنة')->get();
echo 'عدد المعدات بنوع شاحنة: ' . $trucks->count() . PHP_EOL;

if ($trucks->count() > 0) {
    echo 'المعدات الموجودة:' . PHP_EOL;
    foreach($trucks as $truck) {
        echo 'ID: ' . $truck->id . ' - ' . $truck->name . PHP_EOL;
    }

    // تحديث جميع المعدات من "شاحنة" إلى "سيارة"
    $updated = Equipment::where('type', 'شاحنة')->update(['type' => 'سيارة']);
    echo PHP_EOL . 'تم تحديث ' . $updated . ' معدة من شاحنة إلى سيارة' . PHP_EOL;
} else {
    echo 'لا توجد معدات بنوع شاحنة' . PHP_EOL;
}
