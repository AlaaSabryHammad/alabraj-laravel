<?php
require __DIR__ . '/bootstrap/app.php';

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Test the routes
echo "Testing routes:\n";
echo "Daily Report: " . url('/employees/daily-attendance-report?date=2025-08-01') . "\n";
echo "Daily Edit: " . url('/employees/daily-attendance-edit?date=2025-08-01') . "\n";
