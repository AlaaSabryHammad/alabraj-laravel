<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test creating a material unit first
    $materialUnit = \App\Models\MaterialUnit::firstOrCreate(
        ['name' => 'طن'],
        ['name' => 'طن']
    );
    
    // Test creating a material
    $material = \App\Models\Material::create([
        'name' => 'مادة تجريبية',
        'material_unit_id' => $materialUnit->id,
        'category' => 'عام',
        'minimum_stock' => 0,
        'current_stock' => 0,
        'status' => 'active'
    ]);
    
    echo "تم إنشاء المادة بنجاح - ID: " . $material->id . "\n";
    echo "اسم المادة: " . $material->name . "\n";
    echo "وحدة القياس: " . $material->materialUnit->name . "\n";
    
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
    echo "السطر: " . $e->getLine() . "\n";
    echo "الملف: " . $e->getFile() . "\n";
}
