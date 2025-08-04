<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $material = App\Models\Material::first();

    echo "=== Testing Material Model Accessors ===\n";
    echo "Name: " . $material->name . "\n";
    echo "Unit (accessor): " . ($material->unit ?? 'NULL') . "\n";
    echo "Unit_of_measure (direct): " . ($material->unit_of_measure ?? 'NULL') . "\n";
    echo "Effective Unit: " . ($material->effective_unit ?? 'NULL') . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
