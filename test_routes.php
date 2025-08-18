<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "Testing routes:" . PHP_EOL;

try {
    // Test route existence
    $route = route('projects.images.store', 1);
    echo "Route URL: " . $route . PHP_EOL;
    echo "Route exists: YES" . PHP_EOL;
} catch (Exception $e) {
    echo "Route exists: NO" . PHP_EOL;
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

// List all project routes
echo PHP_EOL . "All project routes:" . PHP_EOL;
$routes = \Illuminate\Support\Facades\Route::getRoutes();
foreach ($routes as $route) {
    if (str_contains($route->getName() ?? '', 'projects.images')) {
        echo "Name: " . $route->getName() . " | URI: " . $route->uri() . " | Methods: " . implode(', ', $route->methods()) . PHP_EOL;
    }
}
