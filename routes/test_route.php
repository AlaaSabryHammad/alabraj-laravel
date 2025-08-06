<?php
// Simple test route
Route::get('/test-daily-attendance', function () {
    try {
        $date = '2025-05-05';
        
        // Test database connection
        $attendanceCount = \App\Models\Attendance::whereDate('date', $date)->count();
        
        return response()->json([
            'status' => 'success',
            'date' => $date,
            'attendance_count' => $attendanceCount,
            'message' => 'Test successful'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
});
