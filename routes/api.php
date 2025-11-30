<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\EquipmentController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\FuelManagementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (no authentication required)
Route::post('login', [AuthController::class, 'login']);
Route::get('fuel-truck/{fuelTruck}/distributions', [FuelManagementController::class, 'showDistributions']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    // Dashboard routes
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('dashboard/recent-activities', [DashboardController::class, 'recentActivities']);
    Route::get('dashboard/project-progress', [DashboardController::class, 'projectProgress']);
    Route::get('dashboard/attendance-trends', [DashboardController::class, 'attendanceTrends']);

    // Project routes
    Route::apiResource('projects', ProjectController::class)->names([
        'index' => 'api.projects.index',
        'store' => 'api.projects.store',
        'show' => 'api.projects.show',
        'update' => 'api.projects.update',
        'destroy' => 'api.projects.destroy',
    ]);
    Route::get('projects/{project}/employees', [ProjectController::class, 'employees']);
    Route::get('projects/{project}/equipment', [ProjectController::class, 'equipment']);
    Route::get('projects/{project}/materials', [ProjectController::class, 'materials']);

    // Employee routes
    Route::apiResource('employees', EmployeeController::class)->names([
        'index' => 'api.employees.index',
        'store' => 'api.employees.store',
        'show' => 'api.employees.show',
        'update' => 'api.employees.update',
        'destroy' => 'api.employees.destroy',
    ]);
    Route::get('employees/{employee}/projects', [EmployeeController::class, 'projects']);
    Route::get('employees/{employee}/attendance', [EmployeeController::class, 'attendance']);
    Route::post('employees/{employee}/attendance', [EmployeeController::class, 'recordAttendance']);

    // Equipment routes
    Route::apiResource('equipment', EquipmentController::class)->names([
        'index' => 'api.equipment.index',
        'store' => 'api.equipment.store',
        'show' => 'api.equipment.show',
        'update' => 'api.equipment.update',
        'destroy' => 'api.equipment.destroy',
    ]);
    Route::get('equipment/{equipment}/maintenance', [EquipmentController::class, 'maintenanceHistory']);
    Route::post('equipment/{equipment}/maintenance', [EquipmentController::class, 'addMaintenance']);
    Route::patch('equipment/{equipment}/status', [EquipmentController::class, 'updateStatus']);
});

// Default user route for authenticated users
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
