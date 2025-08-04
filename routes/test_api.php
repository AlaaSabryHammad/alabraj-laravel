<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentHistoryController;
use App\Models\Equipment;

Route::get('/test-movement-api/{equipment}', function ($equipmentId) {
    // Get the equipment model
    $equipment = Equipment::findOrFail($equipmentId);

    // Create controller instance
    $controller = new EquipmentHistoryController();

    // Call the method with the correct parameter
    $response = $controller->getMovementHistory($equipment);

    // Return JSON response for testing
    return response()->json($response->getData(), 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});
