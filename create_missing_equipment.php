<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "إنشاء معدات للشاحنات التي لا تملك معدة...\n";

$trucks = \App\Models\InternalTruck::whereDoesntHave('equipment')->get();

foreach ($trucks as $truck) {
    $equipment = \App\Models\Equipment::create([
        'name' => $truck->brand . ' ' . $truck->model . ' - ' . $truck->plate_number,
        'category' => 'شاحنات',
        'description' => 'شاحنة داخلية - رقم اللوحة: ' . $truck->plate_number,
        'serial_number' => $truck->chassis_number ?? 'INT-' . $truck->id,
        'purchase_date' => $truck->purchase_date ?? now()->toDateString(),
        'purchase_price' => $truck->purchase_price ?? 0.00,
        'status' => $truck->driver_id ? 'in_use' : 'available',
        'notes' => 'شاحنة داخلية مضافة تلقائياً - تحديث',
        'user_id' => $truck->user_id,
        'truck_id' => $truck->id,
        'driver_id' => $truck->driver_id,
    ]);

    echo "تم إنشاء معدة للشاحنة: {$truck->plate_number} (ID: {$equipment->id})\n";
    if ($truck->driver_id && $truck->driver) {
        echo "  - السائق: {$truck->driver->name}\n";
    }
}

echo "\nتم الانتهاء من الإنشاء.\n";
