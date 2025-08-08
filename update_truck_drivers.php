<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "تحديث ربط السائقين في جدول المعدات...\n";

$trucks = \App\Models\InternalTruck::with('equipment')->get();

foreach ($trucks as $truck) {
    if ($truck->equipment) {
        $updated = $truck->equipment->update([
            'driver_id' => $truck->driver_id,
            'status' => $truck->driver_id ? 'in_use' : 'available',
        ]);

        if ($updated) {
            echo "تم تحديث المعدة للشاحنة: {$truck->plate_number}\n";
            if ($truck->driver_id) {
                echo "  - السائق: {$truck->driver->name}\n";
            } else {
                echo "  - لا يوجد سائق مسند\n";
            }
        }
    } else {
        echo "لا توجد معدة مرتبطة بالشاحنة: {$truck->plate_number}\n";
    }
}

echo "\nتم الانتهاء من التحديث.\n";
