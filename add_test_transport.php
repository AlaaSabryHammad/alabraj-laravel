<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a test transport record
$transport = new \App\Models\Transport();
$transport->vehicle_type = 'شاحنة خارجية';
$transport->vehicle_number = 'ABC-123';
$transport->driver_name = 'أحمد محمد';
$transport->loading_location_id = 1;
$transport->unloading_location_id = 2;
$transport->arrival_time = now();
$transport->cargo_description = 'مواد بناء';
$transport->quantity = 10.5;
$transport->user_id = 1;
// Set destination to a test value
$transport->destination = 'محطة اختبار';
$transport->save();

echo "Test transport record created with ID: " . $transport->id . "\n";
