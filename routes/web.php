<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentHistoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExternalTruckController;
use App\Http\Controllers\InternalTruckController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MaterialUnitController;
use App\Http\Controllers\EmployeeReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PayrollController;

// Authentication Routes (Public)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Redirect root to login if not authenticated
    Route::get('/', function () {
        return redirect()->route('login');
    });
});

// Change Password Routes (Requires Authentication but accessible even if password needs to be changed)
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password.update');
    // Logout should also be accessible without password check
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected Routes (Require Authentication)
Route::middleware(['auth', 'manager.only', 'check.password.changed'])->group(function () {

    // Dashboard Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Employee Management Routes
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
        Route::post('/{employee}/assign-manager', [EmployeeController::class, 'assignManager'])->name('employees.assign-manager');
        Route::delete('/{employee}/remove-manager', [EmployeeController::class, 'removeManager'])->name('employees.remove-manager');
        Route::get('/{employee}/print', [EmployeeController::class, 'print'])->name('employees.print');
        Route::get('/{employee}/download-pdf', [EmployeeController::class, 'downloadPdf'])->name('employees.download-pdf');
        Route::get('/{employee}/print-test', [EmployeeController::class, 'printTest'])->name('employees.print-test');
        Route::get('/{employee}/simple-test', [EmployeeController::class, 'simpleTest'])->name('employees.simple-test');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::post('/{employee}/create-user-account', [EmployeeController::class, 'createUserAccount'])->name('employees.create-user-account');
        Route::get('/attendance/tracker', [EmployeeController::class, 'attendance'])->name('employees.attendance');
        Route::get('/attendance/report', [EmployeeController::class, 'monthlyAttendanceReport'])->name('employees.attendance.report');
        Route::post('/{employee}/check-in', [EmployeeController::class, 'checkIn'])->name('employees.check-in');
        Route::post('/{employee}/check-out', [EmployeeController::class, 'checkOut'])->name('employees.check-out');
        Route::post('/{employee}/attendance/edit', [EmployeeController::class, 'editAttendance'])->name('employees.attendance.edit');

        // Employee Reports Routes
        Route::post('/{employee}/reports', [EmployeeReportController::class, 'store'])->name('employees.reports.store');
        Route::get('/{employee}/reports', [EmployeeReportController::class, 'index'])->name('employees.reports.index');
        Route::get('/{employee}/reports/print', [EmployeeReportController::class, 'print'])->name('employees.reports.print');
    });

    // Equipment Management Routes
    Route::prefix('equipment')->group(function () {
        Route::get('/', [EquipmentController::class, 'index'])->name('equipment.index');
        Route::get('/create', [EquipmentController::class, 'create'])->name('equipment.create');
        Route::post('/', [EquipmentController::class, 'store'])->name('equipment.store');
        Route::get('/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');
        Route::get('/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
        Route::put('/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
        Route::get('/file/{file}', [EquipmentController::class, 'downloadFile'])->name('equipment.file.download');
        Route::get('/{equipment}/report', [EquipmentController::class, 'generateReport'])->name('equipment.report');
        Route::get('/api/all-equipment-data', [EquipmentController::class, 'getAllEquipmentData'])->name('equipment.all-data');

        // History management routes
        Route::post('/{equipment}/change-driver', [EquipmentHistoryController::class, 'changeDriver'])->name('equipment.change-driver');
        Route::post('/{equipment}/move', [EquipmentHistoryController::class, 'moveEquipment'])->name('equipment.move');
        Route::get('/{equipment}/driver-history', [EquipmentHistoryController::class, 'getDriverHistory'])->name('equipment.driver-history');
        Route::get('/{equipment}/movement-history', [EquipmentHistoryController::class, 'getMovementHistory'])->name('equipment.movement-history');
    });

    // External Trucks Management Routes
    Route::prefix('external-trucks')->group(function () {
        Route::get('/', [ExternalTruckController::class, 'index'])->name('external-trucks.index');
        Route::get('/create', [ExternalTruckController::class, 'create'])->name('external-trucks.create');
        Route::post('/', [ExternalTruckController::class, 'store'])->name('external-trucks.store');
        Route::get('/api/supplier/{id}', [ExternalTruckController::class, 'getSupplierData'])->name('external-trucks.supplier-data');
        Route::get('/{externalTruck}', [ExternalTruckController::class, 'show'])->name('external-trucks.show');
        Route::get('/{externalTruck}/edit', [ExternalTruckController::class, 'edit'])->name('external-trucks.edit');
        Route::put('/{externalTruck}', [ExternalTruckController::class, 'update'])->name('external-trucks.update');
        Route::delete('/{externalTruck}', [ExternalTruckController::class, 'destroy'])->name('external-trucks.destroy');
    });

    // Internal Trucks Management Routes
    Route::prefix('internal-trucks')->group(function () {
        Route::get('/', [InternalTruckController::class, 'index'])->name('internal-trucks.index');
        Route::get('/create', [InternalTruckController::class, 'create'])->name('internal-trucks.create');
        Route::post('/', [InternalTruckController::class, 'store'])->name('internal-trucks.store');
        Route::get('/{internalTruck}', [InternalTruckController::class, 'show'])->name('internal-trucks.show');
        Route::get('/{internalTruck}/edit', [InternalTruckController::class, 'edit'])->name('internal-trucks.edit');
        Route::put('/{internalTruck}', [InternalTruckController::class, 'update'])->name('internal-trucks.update');
        Route::delete('/{internalTruck}', [InternalTruckController::class, 'destroy'])->name('internal-trucks.destroy');
    });

    // API Routes for Equipment
    Route::prefix('api/equipment')->group(function () {
        Route::get('/available-for-location', [EquipmentController::class, 'getAvailableForLocation'])->name('api.equipment.available');
        Route::get('/{equipment}/details', [EquipmentController::class, 'getEquipmentDetails'])->name('api.equipment.details');
    });

    // Location Management Routes
    Route::prefix('locations')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('locations.index');
        Route::get('/create', [LocationController::class, 'create'])->name('locations.create');
        Route::post('/', [LocationController::class, 'store'])->name('locations.store');
        Route::get('/{location}', [LocationController::class, 'show'])->name('locations.show');
        Route::get('/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
        Route::put('/{location}', [LocationController::class, 'update'])->name('locations.update');
        Route::delete('/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
        Route::get('/search-employees', [LocationController::class, 'searchEmployees'])->name('locations.search-employees');
    });

    // Document Management Routes
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // Transport Management Routes
    Route::prefix('transport')->group(function () {
        Route::get('/', [TransportController::class, 'index'])->name('transport.index');
        Route::get('/create', [TransportController::class, 'create'])->name('transport.create');
        Route::post('/', [TransportController::class, 'store'])->name('transport.store');
        Route::get('/{transport}/details', [TransportController::class, 'details'])->name('transport.details');
        Route::get('/{transport}/edit', [TransportController::class, 'edit'])->name('transport.edit');
        Route::get('/{transport}', [TransportController::class, 'show'])->name('transport.show');
        Route::put('/{transport}', [TransportController::class, 'update'])->name('transport.update');
        Route::delete('/{transport}', [TransportController::class, 'destroy'])->name('transport.destroy');
    });

    // Finance Management Routes
    Route::prefix('finance')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('finance.index');
        Route::get('/create', [FinanceController::class, 'create'])->name('finance.create');
        Route::post('/', [FinanceController::class, 'store'])->name('finance.store');
        Route::get('/{finance}', [FinanceController::class, 'show'])->name('finance.show');
        Route::get('/{finance}/edit', [FinanceController::class, 'edit'])->name('finance.edit');
        Route::put('/{finance}', [FinanceController::class, 'update'])->name('finance.update');
        Route::delete('/{finance}', [FinanceController::class, 'destroy'])->name('finance.destroy');
    });

    // Payroll Management Routes
    Route::prefix('payroll')->group(function () {
        Route::get('/', [PayrollController::class, 'index'])->name('payroll.index');
        Route::get('/create', [PayrollController::class, 'create'])->name('payroll.create');
        Route::post('/', [PayrollController::class, 'store'])->name('payroll.store');
        Route::get('/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
        Route::get('/{payroll}/edit', [PayrollController::class, 'edit'])->name('payroll.edit');
        Route::put('/{payroll}', [PayrollController::class, 'update'])->name('payroll.update');
        Route::delete('/{payroll}', [PayrollController::class, 'destroy'])->name('payroll.destroy');
        Route::post('/{payroll}/approve', [PayrollController::class, 'approve'])->name('payroll.approve');
        Route::post('/{payroll}/reject', [PayrollController::class, 'reject'])->name('payroll.reject');
        Route::get('/{payroll}/print', [PayrollController::class, 'print'])->name('payroll.print');

        // Employee Payroll Details
        Route::get('/{payroll}/employees/{employee}', [PayrollController::class, 'employeeDetails'])->name('payroll.employee.details');
        Route::put('/{payroll}/employees/{employee}', [PayrollController::class, 'updateEmployeeDetails'])->name('payroll.employee.update');

        // Deductions & Bonuses
        Route::post('/{payroll}/employees/{employee}/deductions', [PayrollController::class, 'addDeduction'])->name('payroll.employee.deduction.add');
        Route::post('/{payroll}/employees/{employee}/bonuses', [PayrollController::class, 'addBonus'])->name('payroll.employee.bonus.add');
        Route::delete('/deductions/{deduction}', [PayrollController::class, 'deleteDeduction'])->name('payroll.deduction.delete');
        Route::delete('/bonuses/{bonus}', [PayrollController::class, 'deleteBonus'])->name('payroll.bonus.delete');
    });

    // Project Management Routes
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::delete('/images/{image}', [ProjectController::class, 'deleteImage'])->name('projects.images.delete');
    });

    // Settings Management Routes
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::get('/equipment-types', [SettingsController::class, 'equipmentTypes'])->name('settings.equipment-types');
        Route::get('/equipment-types/content', [SettingsController::class, 'equipmentTypesContent'])->name('settings.equipment-types.content');
        Route::get('/equipment-types-simple', function () {
            $equipmentTypes = \App\Models\EquipmentType::withCount('equipment')->orderBy('name')->get();
            return view('settings.equipment-types-simple', compact('equipmentTypes'));
        })->name('settings.equipment-types-simple');
        Route::post('/equipment-types', [SettingsController::class, 'storeEquipmentType'])->name('settings.equipment-types.store');
        Route::put('/equipment-types/{equipmentType}', [SettingsController::class, 'updateEquipmentType'])->name('settings.equipment-types.update');
        Route::delete('/equipment-types/{equipmentType}', [SettingsController::class, 'destroyEquipmentType'])->name('settings.equipment-types.destroy');

        // Location Types Routes
        Route::get('/location-types', [SettingsController::class, 'locationTypes'])->name('settings.location-types');
        Route::get('/location-types/content', [SettingsController::class, 'locationTypesContent'])->name('settings.location-types.content');
        Route::post('/location-types', [SettingsController::class, 'storeLocationType'])->name('settings.location-types.store');
        Route::put('/location-types/{locationType}', [SettingsController::class, 'updateLocationType'])->name('settings.location-types.update');
        Route::delete('/location-types/{locationType}', [SettingsController::class, 'destroyLocationType'])->name('settings.location-types.destroy');

        // Material Units Routes
        Route::get('/material-units', [MaterialUnitController::class, 'index'])->name('settings.material-units');
        Route::post('/material-units', [MaterialUnitController::class, 'store'])->name('settings.material-units.store');
        Route::put('/material-units/{materialUnit}', [MaterialUnitController::class, 'update'])->name('settings.material-units.update');
        Route::delete('/material-units/{materialUnit}', [MaterialUnitController::class, 'destroy'])->name('settings.material-units.destroy');

        // Materials Management Routes
        Route::get('/materials', [SettingsController::class, 'materials'])->name('settings.materials');
        Route::get('/materials/content', [SettingsController::class, 'materialsContent'])->name('settings.materials.content');
        Route::post('/materials', [MaterialController::class, 'store'])->name('settings.materials.store');
        Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('settings.materials.update');
        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('settings.materials.destroy');
    });

    // Supplier Management Routes
    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/content', [SupplierController::class, 'content'])->name('suppliers.content');
        Route::get('/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });
}); // End of auth middleware group
