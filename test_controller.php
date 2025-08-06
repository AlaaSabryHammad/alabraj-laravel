<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Simulate a request
$request = new \Illuminate\Http\Request();
$request->merge(['date' => '2025-05-05']);

// Create controller instance
$controller = new \App\Http\Controllers\EmployeeController();

try {
    echo "Testing dailyAttendanceReport method...\n";
    $result = $controller->dailyAttendanceReport($request);
    
    if ($result instanceof \Illuminate\View\View) {
        echo "SUCCESS: Method returned a view\n";
        echo "View name: " . $result->getName() . "\n";
        
        $data = $result->getData();
        echo "Data keys: " . implode(', ', array_keys($data)) . "\n";
        
        if (isset($data['employees'])) {
            echo "Employees count: " . $data['employees']->count() . "\n";
        }
        
        if (isset($data['stats'])) {
            echo "Stats: " . json_encode($data['stats']) . "\n";
        }
    } else {
        echo "Unexpected return type: " . get_class($result) . "\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
