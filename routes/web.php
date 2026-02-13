<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentHistoryController;
use App\Http\Controllers\EquipmentMaintenanceController;
use App\Http\Controllers\EquipmentFuelConsumptionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExternalTruckController;
use App\Http\Controllers\InternalTruckController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MaterialUnitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\CorrespondenceController;
use App\Http\Controllers\MyTasksController;
use App\Http\Controllers\SparePartReportController;
use App\Http\Controllers\SparePartTypeController;
use App\Http\Controllers\FuelManagementController;
use App\Http\Controllers\FuelTruckController;
use App\Http\Controllers\SparePartSupplierController;
use App\Http\Controllers\FuelManagementUnifiedController;

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

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Illuminate\Support\Facades\Hash::make($password)
                ])->save();

                // Optional: Send notification to user that password was reset
            }
        );

        return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    })->name('password.update');
});

// Protected Routes (Require Authentication)
Route::middleware(['auth', 'check.password.changed'])->group(function () {

    // Dashboard Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Employee Management Routes
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
        Route::get('/{employee}/details', [EmployeeController::class, 'getDetails'])->name('employees.details');
        Route::get('/{employee}/notifications', [EmployeeController::class, 'getNotifications'])->name('employees.notifications');
        Route::post('/send-notification', [EmployeeController::class, 'sendNotification'])->name('employees.send-notification');
        Route::post('/{employee}/assign-manager', [EmployeeController::class, 'assignManager'])->name('employees.assign-manager');
        Route::delete('/{employee}/remove-manager', [EmployeeController::class, 'removeManager'])->name('employees.remove-manager');
        Route::get('/{employee}/print', [EmployeeController::class, 'print'])->name('employees.print');
        Route::get('/{employee}/download-pdf', [EmployeeController::class, 'downloadPdf'])->name('employees.download-pdf');
        Route::get('/{employee}/print-test', [EmployeeController::class, 'printTest'])->name('employees.print-test');
        Route::get('/{employee}/simple-test', [EmployeeController::class, 'simpleTest'])->name('employees.simple-test');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::patch('/{employee}/change-password', [EmployeeController::class, 'changePassword'])->name('employees.change-password');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::post('/{employee}/create-user-account', [EmployeeController::class, 'createUserAccount'])->name('employees.create-user-account');
        Route::get('/attendance/tracker', [EmployeeController::class, 'attendance'])->name('employees.attendance');
        Route::get('/attendance/report', [EmployeeController::class, 'monthlyAttendanceReport'])->name('employees.attendance.report');
        Route::get('/daily-attendance-report', [EmployeeController::class, 'dailyAttendanceReport'])->name('employees.daily-attendance-report');
        Route::get('/daily-attendance-edit', [EmployeeController::class, 'dailyAttendanceEdit'])->name('employees.daily-attendance-edit');
        Route::post('/daily-attendance-update', [EmployeeController::class, 'dailyAttendanceUpdate'])->name('employees.daily-attendance-update');
        Route::post('/{employee}/check-in', [EmployeeController::class, 'checkIn'])->name('employees.check-in');
        Route::post('/{employee}/check-out', [EmployeeController::class, 'checkOut'])->name('employees.check-out');
        Route::post('/{employee}/attendance/edit', [EmployeeController::class, 'editAttendance'])->name('employees.attendance.edit');

        // Employee Balance Routes
        Route::post('/{employee}/balance/credit', [EmployeeController::class, 'addCredit'])->name('employees.balance.credit');
        Route::post('/{employee}/balance/debit', [EmployeeController::class, 'addDebit'])->name('employees.balance.debit');

        // Employee Status Management Routes (Admin Only)
        Route::patch('/{employee}/activate', [EmployeeController::class, 'activate'])->name('employees.activate');
        Route::patch('/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');

        // Employee Reports Routes
        Route::post('/{employee}/reports', [EmployeeReportController::class, 'store'])->name('employees.reports.store');
        Route::get('/{employee}/reports', [EmployeeReportController::class, 'index'])->name('employees.reports.index');
        Route::get('/{employee}/reports/print', [EmployeeReportController::class, 'print'])->name('employees.reports.print');
    });

    // Employee Balance Transaction Route (for payroll system)
    Route::post('/employee-balances/record-transaction', [EmployeeController::class, 'recordBalanceTransaction'])->name('employee-balances.record-transaction');

    // Equipment Management Routes
    Route::prefix('equipment')->group(function () {
        Route::get('/', [EquipmentController::class, 'index'])->name('equipment.index');
        Route::get('/create', [EquipmentController::class, 'create'])->name('equipment.create');
        Route::post('/', [EquipmentController::class, 'store'])->name('equipment.store');
        Route::get('/api/next-code', [EquipmentController::class, 'getNextCode'])->name('equipment.getNextCode');
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
        Route::post('/{equipment}/update-location', [EquipmentController::class, 'updateLocation'])->name('equipment.update-location');
    });

    // Equipment Maintenance Routes
    Route::prefix('equipment-maintenance')->name('equipment-maintenance.')->group(function () {
        Route::get('/', [EquipmentMaintenanceController::class, 'index'])->name('index');
        Route::get('/create', [EquipmentMaintenanceController::class, 'create'])->name('create');
        Route::post('/', [EquipmentMaintenanceController::class, 'store'])->name('store');
        Route::get('/{equipmentMaintenance}', [EquipmentMaintenanceController::class, 'show'])->name('show');
        Route::get('/{equipmentMaintenance}/edit', [EquipmentMaintenanceController::class, 'edit'])->name('edit');
        Route::put('/{equipmentMaintenance}', [EquipmentMaintenanceController::class, 'update'])->name('update');
        Route::delete('/{equipmentMaintenance}', [EquipmentMaintenanceController::class, 'destroy'])->name('destroy');
        Route::patch('/{equipmentMaintenance}/complete', [EquipmentMaintenanceController::class, 'complete'])->name('complete');
    });

    // Equipment Fuel Consumption Routes
    Route::prefix('equipment-fuel-consumption')->name('equipment-fuel-consumption.')->group(function () {
        Route::post('/', [EquipmentFuelConsumptionController::class, 'store'])->name('store');
        Route::delete('/{equipmentFuelConsumption}', [EquipmentFuelConsumptionController::class, 'destroy'])->name('destroy');
        Route::get('/{equipment}/consumptions', [EquipmentFuelConsumptionController::class, 'getConsumptionsByEquipment'])->name('consumptions');
        Route::get('/{equipment}/summary', [EquipmentFuelConsumptionController::class, 'getConsumptionSummary'])->name('summary');
        Route::patch('/{fuelConsumption}/approve', [EquipmentFuelConsumptionController::class, 'approve'])->name('approve');
        Route::patch('/{fuelConsumption}/reject', [EquipmentFuelConsumptionController::class, 'reject'])->name('reject');
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
        Route::post('/link-equipment', [InternalTruckController::class, 'linkEquipment'])->name('internal-trucks.linkEquipment');
        Route::post('/unlink-equipment', [InternalTruckController::class, 'unlinkEquipment'])->name('internal-trucks.unlinkEquipment');
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

    // Warehouse Management Routes
    Route::prefix('warehouses')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('warehouses.index');
        Route::get('/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
        Route::get('/{warehouse}/create-spare-part', [WarehouseController::class, 'createSparePart'])->name('warehouses.create-spare-part');
        Route::post('/{warehouse}/store-spare-part', [WarehouseController::class, 'storeSparePart'])->name('warehouses.store-spare-part');
        Route::post('/{warehouse}/receive-spares', [WarehouseController::class, 'storeReceive'])->name('warehouses.receive-spares');
        Route::post('/{warehouse}/receive-new-spares', [WarehouseController::class, 'receiveNewSpares'])->name('warehouses.receive-new-spares');
        Route::post('/{warehouse}/receive-damaged-spares', [WarehouseController::class, 'receiveDamagedSpares'])->name('warehouses.receive-damaged-spares');
        Route::post('/{warehouse}/store-damaged-parts', [WarehouseController::class, 'storeDamagedParts'])->name('warehouses.store-damaged-parts');
        Route::post('/{warehouse}/transfer-spare-parts', [WarehouseController::class, 'transferSpareParts'])->name('warehouses.transfer-spare-parts');
        Route::post('/{warehouse}/export-spares', [WarehouseController::class, 'storeExport'])->name('warehouses.export-spares');
        Route::get('/{warehouse}/reports', [WarehouseController::class, 'reports'])->name('warehouses.reports');
    });

    // Spare Part Types Management Routes
    Route::prefix('spare-part-types')->group(function () {
        Route::get('/', [SparePartTypeController::class, 'index'])->name('spare-part-types.index');
        Route::get('/create', [SparePartTypeController::class, 'create'])->name('spare-part-types.create');
        Route::post('/', [SparePartTypeController::class, 'store'])->name('spare-part-types.store');
        Route::get('/{sparePartType}/edit', [SparePartTypeController::class, 'edit'])->name('spare-part-types.edit');
        Route::put('/{sparePartType}', [SparePartTypeController::class, 'update'])->name('spare-part-types.update');
        Route::delete('/{sparePartType}', [SparePartTypeController::class, 'destroy'])->name('spare-part-types.destroy');
    });

    // Spare Parts Management Routes
    Route::prefix('spare-parts')->group(function () {
        Route::post('/', [App\Http\Controllers\SparePartController::class, 'store'])->name('spare-parts.store');
        Route::put('/{sparePart}', [App\Http\Controllers\SparePartController::class, 'update'])->name('spare-parts.update');
        Route::delete('/{sparePart}', [App\Http\Controllers\SparePartController::class, 'destroy'])->name('spare-parts.destroy');
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
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::get('/all-transactions', [FinanceController::class, 'allTransactions'])->name('all-transactions');
        Route::get('/daily-report', [FinanceController::class, 'dailyReport'])->name('daily-report');
        Route::get('/employee-report/{employee}', [FinanceController::class, 'employeeReport'])->name('employee-report');
        Route::get('/{id}', [FinanceController::class, 'show'])->name('show');

        // Custodies Routes (nested under finance)
        Route::prefix('custodies')->name('custodies.')->group(function () {
            Route::post('/', [App\Http\Controllers\CustodyController::class, 'store'])->name('store');
            Route::get('/{custody}', [App\Http\Controllers\CustodyController::class, 'show'])->name('show');
            Route::get('/{custody}/print', [App\Http\Controllers\CustodyController::class, 'print'])->name('print');
            Route::patch('/{custody}/approve', [App\Http\Controllers\CustodyController::class, 'approve'])->name('approve');
        });
    });

    // Expense Voucher Management Routes
    Route::prefix('expense-vouchers')->name('expense-vouchers.')->group(function () {
        Route::get('/', [App\Http\Controllers\ExpenseVoucherController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ExpenseVoucherController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ExpenseVoucherController::class, 'store'])->name('store');
        Route::get('/{expenseVoucher}', [App\Http\Controllers\ExpenseVoucherController::class, 'show'])->name('show');
        Route::get('/{expenseVoucher}/edit', [App\Http\Controllers\ExpenseVoucherController::class, 'edit'])->name('edit');
        Route::put('/{expenseVoucher}', [App\Http\Controllers\ExpenseVoucherController::class, 'update'])->name('update');
        Route::delete('/{expenseVoucher}', [App\Http\Controllers\ExpenseVoucherController::class, 'destroy'])->name('destroy');
        Route::patch('/{expenseVoucher}/approve', [App\Http\Controllers\ExpenseVoucherController::class, 'approve'])->name('approve');
        Route::get('/{expenseVoucher}/print', [App\Http\Controllers\ExpenseVoucherController::class, 'print'])->name('print');
    });

    // Revenue Voucher Management Routes
    Route::prefix('revenue-vouchers')->name('revenue-vouchers.')->group(function () {
        Route::get('/', [App\Http\Controllers\RevenueVoucherController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\RevenueVoucherController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\RevenueVoucherController::class, 'store'])->name('store');
        Route::get('/{revenueVoucher}', [App\Http\Controllers\RevenueVoucherController::class, 'show'])->name('show');
        Route::get('/{revenueVoucher}/edit', [App\Http\Controllers\RevenueVoucherController::class, 'edit'])->name('edit');
        Route::put('/{revenueVoucher}', [App\Http\Controllers\RevenueVoucherController::class, 'update'])->name('update');
        Route::delete('/{revenueVoucher}', [App\Http\Controllers\RevenueVoucherController::class, 'destroy'])->name('destroy');
        Route::patch('/{revenueVoucher}/approve', [App\Http\Controllers\RevenueVoucherController::class, 'approve'])->name('approve');
        Route::patch('/{revenueVoucher}/mark-received', [App\Http\Controllers\RevenueVoucherController::class, 'markAsReceived'])->name('mark-received');
        Route::get('/{revenueVoucher}/print', [App\Http\Controllers\RevenueVoucherController::class, 'print'])->name('print');
    });

    // Expense Entity Management Routes
    Route::prefix('expense-entities')->name('expense-entities.')->group(function () {
        Route::get('/', [App\Http\Controllers\ExpenseEntityController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ExpenseEntityController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ExpenseEntityController::class, 'store'])->name('store');
        Route::get('/{expenseEntity}', [App\Http\Controllers\ExpenseEntityController::class, 'show'])->name('show');
        Route::get('/{expenseEntity}/edit', [App\Http\Controllers\ExpenseEntityController::class, 'edit'])->name('edit');
        Route::put('/{expenseEntity}', [App\Http\Controllers\ExpenseEntityController::class, 'update'])->name('update');
        Route::delete('/{expenseEntity}', [App\Http\Controllers\ExpenseEntityController::class, 'destroy'])->name('destroy');
    });

    // Payroll Management Routes
    Route::prefix('payroll')->group(function () {
        Route::get('/', [PayrollController::class, 'index'])->name('payroll.index');
        Route::get('/create', [PayrollController::class, 'create'])->name('payroll.create');
        Route::post('/', [PayrollController::class, 'store'])->name('payroll.store');
        Route::post('/get-monthly-attendance', [PayrollController::class, 'getMonthlyAttendance'])->name('payroll.get-monthly-attendance');
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
        Route::post('/{project}/images', [ProjectController::class, 'storeImages'])->name('projects.images.store');
        Route::delete('/images/{image}', [ProjectController::class, 'deleteImage'])->name('projects.images.delete');

        // Project Extension Route
        Route::post('/{project}/extend', [ProjectController::class, 'extendProject'])->name('projects.extend');

        // Project Rental Routes
        Route::post('/{project}/rental', [ProjectController::class, 'storeRental'])->name('projects.rental.store');
        Route::put('/{project}/rental/{rental}', [ProjectController::class, 'updateRental'])->name('projects.rental.update');
        Route::delete('/{project}/rental/{rental}', [ProjectController::class, 'destroyRental'])->name('projects.rental.destroy');

        // Project Progress Routes
        Route::post('/{project}/progress', [ProjectController::class, 'updateProgress'])->name('projects.updateProgress');

        // Project Visit Routes
        Route::post('/{project}/visit', [ProjectController::class, 'storeVisit'])->name('projects.visit.store');

        // Project Loan Routes
        Route::post('/{project}/loan', [ProjectController::class, 'storeLoan'])->name('projects.loan.store');
        Route::put('/{project}/loan/{loan}', [ProjectController::class, 'updateLoan'])->name('projects.loan.update');
        Route::delete('/{project}/loan/{loan}', [ProjectController::class, 'destroyLoan'])->name('projects.loan.destroy');

        // Project Delivery Request Routes
        Route::put('/{project}/delivery-requests/{deliveryRequest}/update', [ProjectController::class, 'updateDeliveryRequest'])->name('projects.delivery-request.update');

        // Project Extract Routes
        Route::get('/{project}/extract/create', [ProjectController::class, 'createExtract'])->name('projects.extract.create');
        Route::post('/projects/{project}/items', [ProjectController::class, 'storeItems'])->name('projects.items.store');

        Route::post('/{project}/extract/store', [ProjectController::class, 'storeExtract'])->name('projects.extract.store');
        Route::get('/{project}/extract/{extract}', [ProjectController::class, 'showExtract'])->name('projects.extract.show');
        Route::get('/{project}/extract/{extract}/edit', [ProjectController::class, 'editExtract'])->name('projects.extract.edit');
        Route::put('/{project}/extract/{extract}', [ProjectController::class, 'updateExtract'])->name('projects.extract.update');
        Route::delete('/{project}/extract/{extract}', [ProjectController::class, 'destroyExtract'])->name('projects.extract.destroy');
    });

    // Settings Management Routes
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');

        // Equipment Types Routes - Both AJAX content and full page
        Route::get('/equipment-types', [SettingsController::class, 'equipmentTypes'])->name('settings.equipment-types');
        Route::get('/equipment-types/content', [SettingsController::class, 'equipmentTypesContent'])->name('settings.equipment-types.content');
        Route::get('/equipment-types/show', function () {
            $equipmentTypes = \App\Models\EquipmentType::paginate(15);
            return view('settings.show.equipment-types', compact('equipmentTypes'));
        })->name('settings.equipment-types.show');
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
        Route::get('/location-types/show', function () {
            $locationTypes = \App\Models\LocationType::paginate(15);
            return view('settings.show.location-types', compact('locationTypes'));
        })->name('settings.location-types.show');
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
        Route::get('/materials/create', [MaterialController::class, 'create'])->name('settings.materials.create');
        Route::post('/materials', [MaterialController::class, 'store'])->name('settings.materials.store');
        Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('settings.materials.edit');
        Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('settings.materials.update');
        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('settings.materials.destroy');

        // Roles and Permissions Routes
        Route::get('/roles-permissions', [SettingsController::class, 'rolesAndPermissions'])->name('settings.roles-permissions');
        Route::get('/roles/{role}', [SettingsController::class, 'showRole'])->name('settings.roles.show');
        Route::post('/roles', [SettingsController::class, 'storeRole'])->name('settings.roles.store');
        Route::put('/roles/{role}', [SettingsController::class, 'updateRole'])->name('settings.roles.update');
        Route::delete('/roles/{role}', [SettingsController::class, 'destroyRole'])->name('settings.roles.destroy');
        Route::post('/permissions', [SettingsController::class, 'storePermission'])->name('settings.permissions.store');
        Route::put('/users/{user}/roles', [SettingsController::class, 'updateUserRoles'])->name('settings.users.roles.update');

        // Expense Categories Routes
        Route::get('/expense-categories/content', [App\Http\Controllers\Settings\ExpenseCategoryController::class, 'content'])->name('settings.expense-categories.content');
        Route::get('/expense-categories/show', function () {
            $categories = \App\Models\ExpenseCategory::paginate(15);
            return view('settings.show.expense-categories', compact('categories'));
        })->name('settings.expense-categories.show');
        Route::post('/expense-categories', [App\Http\Controllers\Settings\ExpenseCategoryController::class, 'store'])->name('settings.expense-categories.store');
        Route::put('/expense-categories/{expenseCategory}', [App\Http\Controllers\Settings\ExpenseCategoryController::class, 'update'])->name('settings.expense-categories.update');
        Route::patch('/expense-categories/{expenseCategory}/toggle-status', [App\Http\Controllers\Settings\ExpenseCategoryController::class, 'toggleStatus'])->name('settings.expense-categories.toggle-status');
        Route::delete('/expense-categories/{expenseCategory}', [App\Http\Controllers\Settings\ExpenseCategoryController::class, 'destroy'])->name('settings.expense-categories.destroy');

        // Revenue Types Routes
        Route::get('/revenue-types/content', [App\Http\Controllers\Settings\RevenueTypeController::class, 'content'])->name('settings.revenue-types.content');
        Route::get('/revenue-types/show', function () {
            $types = \App\Models\RevenueType::paginate(15);
            return view('settings.show.revenue-types', compact('types'));
        })->name('settings.revenue-types.show');
        Route::post('/revenue-types', [App\Http\Controllers\Settings\RevenueTypeController::class, 'store'])->name('settings.revenue-types.store');
        Route::put('/revenue-types/{revenueType}', [App\Http\Controllers\Settings\RevenueTypeController::class, 'update'])->name('settings.revenue-types.update');
        Route::patch('/revenue-types/{revenueType}/toggle-status', [App\Http\Controllers\Settings\RevenueTypeController::class, 'toggleStatus'])->name('settings.revenue-types.toggle-status');
        Route::delete('/revenue-types/{revenueType}', [App\Http\Controllers\Settings\RevenueTypeController::class, 'destroy'])->name('settings.revenue-types.destroy');

        // Expense Entities Routes
        Route::get('/expense-entities/content', [App\Http\Controllers\ExpenseEntityController::class, 'getContent'])->name('settings.expense-entities.content');
        Route::get('/expense-entities/show', function () {
            $entities = \App\Models\ExpenseEntity::paginate(15);
            return view('settings.show.expense-entities', compact('entities'));
        })->name('settings.expense-entities.show');

        // Revenue Entities Routes
        Route::get('/revenue-entities', [App\Http\Controllers\RevenueEntityController::class, 'index'])->name('settings.revenue-entities.index');
        Route::get('/revenue-entities/content', [App\Http\Controllers\RevenueEntityController::class, 'getContent'])->name('settings.revenue-entities.content');
        Route::get('/revenue-entities/show', function () {
            $entities = \App\Models\RevenueEntity::paginate(15);
            $revenueTypes = \App\Models\RevenueType::where('is_active', true)->orderBy('name')->get();
            return view('settings.show.revenue-entities', compact('entities', 'revenueTypes'));
        })->name('settings.revenue-entities.show');
        Route::get('/revenue-entities/create', [App\Http\Controllers\RevenueEntityController::class, 'create'])->name('settings.revenue-entities.create');
        Route::post('/revenue-entities', [App\Http\Controllers\RevenueEntityController::class, 'store'])->name('settings.revenue-entities.store');
        Route::get('/revenue-entities/{revenueEntity}', [App\Http\Controllers\RevenueEntityController::class, 'show'])->name('revenue-entities.show');
        Route::get('/revenue-entities/{revenueEntity}/edit', [App\Http\Controllers\RevenueEntityController::class, 'edit'])->name('settings.revenue-entities.edit');
        Route::put('/revenue-entities/{revenueEntity}', [App\Http\Controllers\RevenueEntityController::class, 'update'])->name('settings.revenue-entities.update');
        Route::delete('/revenue-entities/{revenueEntity}', [App\Http\Controllers\RevenueEntityController::class, 'destroy'])->name('settings.revenue-entities.destroy');
    });

    // Supplier Management Routes
    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/content', [SupplierController::class, 'content'])->name('suppliers.content');
        Route::get('/show', function () {
            $suppliers = \App\Models\Supplier::paginate(15);
            return view('settings.show.suppliers', compact('suppliers'));
        })->name('suppliers.show-page');
        Route::get('/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });

    // Spare Part Supplier Management Routes
    Route::resource('spare-part-suppliers', SparePartSupplierController::class);

    // Correspondence Management Routes
    Route::prefix('correspondences')->group(function () {
        Route::get('/', [CorrespondenceController::class, 'index'])->name('correspondences.index');
        Route::get('/create', [CorrespondenceController::class, 'create'])->name('correspondences.create');
        Route::post('/', [CorrespondenceController::class, 'store'])->name('correspondences.store');
        Route::get('/{correspondence}', [CorrespondenceController::class, 'show'])->name('correspondences.show');
        Route::get('/{correspondence}/edit', [CorrespondenceController::class, 'edit'])->name('correspondences.edit');
        Route::put('/{correspondence}', [CorrespondenceController::class, 'update'])->name('correspondences.update');
        Route::delete('/{correspondence}', [CorrespondenceController::class, 'destroy'])->name('correspondences.destroy');
        Route::get('/{correspondence}/download', [CorrespondenceController::class, 'download'])->name('correspondences.download');
        Route::get('/replies/{reply}/download', [CorrespondenceController::class, 'downloadReply'])->name('correspondences.replies.download');
        Route::post('/{correspondence}/reply', [CorrespondenceController::class, 'storeReply'])->name('correspondences.reply');
    });

    // My Tasks Routes
    Route::prefix('my-tasks')->group(function () {
        Route::get('/', [MyTasksController::class, 'index'])->name('my-tasks.index');
        Route::get('/{correspondence}', [MyTasksController::class, 'show'])->name('my-tasks.show');
        Route::post('/{correspondence}/reply', [MyTasksController::class, 'storeReply'])->name('my-tasks.reply');
        Route::patch('/{correspondence}/status', [MyTasksController::class, 'updateStatus'])->name('my-tasks.update-status');
        Route::get('/reply/{reply}/download', [MyTasksController::class, 'downloadReply'])->name('my-tasks.download-reply');
    });

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::prefix('spare-parts')->name('spare-parts.')->group(function () {
            Route::get('/', [SparePartReportController::class, 'index'])->name('index');
            Route::get('/daily', [SparePartReportController::class, 'daily'])->name('daily');
            Route::get('/monthly', [SparePartReportController::class, 'monthly'])->name('monthly');
            Route::get('/inventory', [SparePartReportController::class, 'inventory'])->name('inventory');
            Route::get('/employees', [SparePartReportController::class, 'employees'])->name('employees');
        });
    });

    // Fuel Management Routes
    Route::prefix('fuel-management')->name('fuel-management.')->group(function () {
        Route::get('/', [FuelManagementUnifiedController::class, 'index'])->name('index');
        Route::get('/consumption-report', [FuelManagementUnifiedController::class, 'consumptionReport'])->name('consumption-report');
        Route::get('/consumption-report/print', [FuelManagementUnifiedController::class, 'printConsumptionReport'])->name('consumption-report-print');
        Route::get('/truck/{truck}/details', [FuelManagementUnifiedController::class, 'getTruckDetails'])->name('truck-details');
        Route::get('/driver', [FuelManagementController::class, 'driverIndex'])->name('driver');
        Route::post('/equipment/{equipment}/add-fuel', [FuelManagementController::class, 'addFuel'])->name('add-fuel');
        Route::post('/fuel-truck/{fuelTruck}/add-quantity', [FuelManagementController::class, 'addQuantity'])->name('add-quantity');
        Route::get('/fuel-truck/{fuelTruck}/distributions', [FuelManagementController::class, 'showDistributions'])->name('distributions');
        Route::post('/fuel-truck/{fuelTruck}/distribute', [FuelManagementController::class, 'distributeFuel'])->name('distribute');
        Route::patch('/distribution/{distribution}/approve', [FuelManagementController::class, 'approveDistribution'])->name('approve-distribution');
        Route::patch('/distribution/{distribution}/reject', [FuelManagementController::class, 'rejectDistribution'])->name('reject-distribution');
        Route::delete('/distribution/{distribution}/cancel', [FuelManagementController::class, 'cancelDistribution'])->name('cancel-distribution');
    });

    // Fuel Truck Management Routes
    Route::prefix('fuel-truck')->name('fuel-truck.')->group(function () {
        Route::get('/equipment/{equipment}/create', [FuelTruckController::class, 'create'])->name('create');
        Route::post('/equipment/{equipment}', [FuelTruckController::class, 'store'])->name('store');
        Route::post('/equipment/{equipment}/modal', [FuelTruckController::class, 'storeViaModal'])->name('store-modal');
        Route::get('/equipment/{equipment}/edit', [FuelTruckController::class, 'edit'])->name('edit');
        Route::put('/equipment/{equipment}', [FuelTruckController::class, 'update'])->name('update');
    });

}); // End of auth middleware group
