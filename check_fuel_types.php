<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Fuel types in database:\n";
$trucks = \App\Models\InternalTruck::select('id', 'fuel_type')->get();

foreach ($trucks as $truck) {
    echo "ID: {$truck->id}, Fuel Type: '{$truck->fuel_type}'\n";
}

echo "\nTotal trucks: " . $trucks->count() . "\n";
