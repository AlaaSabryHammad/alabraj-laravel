<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Clear the log first
file_put_contents(storage_path('logs/laravel.log'), '');

echo "Log file cleared. Now try to edit employee 147 and check logs again.\n";
echo "Visit: http://127.0.0.1:8000/employees/147/edit\n";
echo "Make some changes and submit the form.\n";
echo "Then run this script again to see the logs.\n\n";

// Check if log file exists and has content
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile) && filesize($logFile) > 0) {
    echo "Current log contents:\n";
    echo "====================\n";
    $logs = file_get_contents($logFile);
    // Show only the last 50 lines
    $lines = explode("\n", $logs);
    $lastLines = array_slice($lines, -50);
    echo implode("\n", $lastLines);
} else {
    echo "Log file is empty or doesn't exist.\n";
}
