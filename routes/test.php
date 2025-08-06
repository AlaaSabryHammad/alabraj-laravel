<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test-daily-report', function (Request $request) {
    return response()->json([
        'message' => 'Route is working',
        'date' => $request->input('date'),
        'current_time' => now()->format('Y-m-d H:i:s')
    ]);
});
