<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "الموظفين والشاحنات المسندة لهم:\n";
echo "================================\n";

$employees = \App\Models\Employee::whereHas('drivenEquipment')->with('drivenEquipment')->get();

foreach ($employees as $employee) {
    echo "الموظف: {$employee->name}\n";
    echo "  المعدات المسندة:\n";
    foreach ($employee->drivenEquipment as $equipment) {
        echo "  - {$equipment->name} (ID: {$equipment->id})\n";
        echo "    الحالة: {$equipment->status}\n";
        echo "    النوع: {$equipment->category}\n";
        if ($equipment->truck_id) {
            echo "    مرتبطة بشاحنة ID: {$equipment->truck_id}\n";
        }
    }
    echo "\n";
}

echo "عدد الموظفين الذين لديهم معدات: " . $employees->count() . "\n";
