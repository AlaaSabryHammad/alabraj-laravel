<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== المواقع المتاحة ===\n";
$locations = \App\Models\Location::all(['id', 'name']);
foreach($locations as $location) {
    echo "ID: {$location->id} - اسم الموقع: {$location->name}\n";
}

echo "\n=== المواد المتاحة ===\n";
$materials = \App\Models\Material::all(['id', 'name']);
foreach($materials as $material) {
    echo "ID: {$material->id} - اسم المادة: {$material->name}\n";
}
