<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\PayrollEmployee;
use App\Models\PayrollDeduction;
use App\Models\PayrollBonus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with(['creator', 'employees'])
            ->withCount('employees')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_payrolls' => Payroll::count(),
            'pending_payrolls' => Payroll::where('status', 'pending')->count(),
            'approved_payrolls' => Payroll::where('status', 'approved')->count(),
            'total_amount_this_month' => Payroll::whereMonth('payroll_date', now()->month)
                ->whereYear('payroll_date', now()->year)
                ->where('status', 'approved')
                ->sum('total_amount'),
        ];

        return view('payroll.index', compact('payrolls', 'stats'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('payroll.create', compact('employees'));
    }

    /**
     * Get monthly attendance data for payroll calculation
     */
    public function getMonthlyAttendance(Request $request)
    {
        try {
            $month = $request->input('month'); // Format: YYYY-MM
            
            if (!$month) {
                return response()->json([
                    'success' => false,
                    'message' => 'الشهر مطلوب'
                ]);
            }

            // Parse month
            $date = Carbon::createFromFormat('Y-m', $month);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            $workingDaysInMonth = $this->getWorkingDaysInMonth($startOfMonth, $endOfMonth);

            // Get all active employees with their balances
            $employees = Employee::where('status', 'active')
                ->with(['balances' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }])
                ->get();
            
            $attendanceData = [];

            foreach ($employees as $employee) {
                // Get attendance records for the month
                $attendances = $employee->attendances()
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->get();

                $workingDays = 0;
                $absentDays = 0;
                $totalOvertimeHours = 0;
                $totalWorkingHours = 0;
                $presentDays = 0;
                $lateDays = 0;
                $leaveDays = 0;
                $sickLeaveDays = 0;

                // Count working days from attendance records
                foreach ($attendances as $attendance) {
                    switch ($attendance->status) {
                        case 'present':
                            $presentDays++;
                            $workingDays++;
                            break;
                        case 'late':
                            $lateDays++;
                            $workingDays++;
                            break;
                        case 'absent':
                            $absentDays++;
                            break;
                        case 'leave':
                            $leaveDays++;
                            break;
                        case 'sick_leave':
                            $sickLeaveDays++;
                            break;
                    }

                    // Sum total working hours and overtime
                    $totalWorkingHours += $attendance->working_hours ?? 0;
                    $totalOvertimeHours += $attendance->overtime_hours ?? 0;
                }

                // Calculate actual absent days (working days in month - present days - leave days)
                $actualWorkingDays = $workingDaysInMonth - $leaveDays - $sickLeaveDays;
                $actualAbsentDays = max(0, $actualWorkingDays - $presentDays - $lateDays);

                // Calculate employee balances
                $creditBalance = $employee->balances()->where('type', 'credit')->sum('amount');
                $debitBalance = $employee->balances()->where('type', 'debit')->sum('amount');
                $netBalance = $creditBalance - $debitBalance;

                // Get recent balance transactions for context
                $recentTransactions = $employee->balances()
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get()
                    ->map(function($balance) {
                        return [
                            'type' => $balance->type,
                            'amount' => $balance->amount,
                            'notes' => $balance->notes,
                            'date' => $balance->transaction_date->format('Y-m-d')
                        ];
                    });

                $attendanceData[$employee->id] = [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'working_days' => $presentDays + $lateDays,
                    'present_days' => $presentDays,
                    'late_days' => $lateDays,
                    'absent_days' => $actualAbsentDays,
                    'leave_days' => $leaveDays,
                    'sick_leave_days' => $sickLeaveDays,
                    'overtime_hours' => $totalOvertimeHours,
                    'total_hours' => $totalWorkingHours,
                    'working_days_in_month' => $workingDaysInMonth,
                    'actual_working_days' => $actualWorkingDays,
                    // Balance information
                    'credit_balance' => $creditBalance,
                    'debit_balance' => $debitBalance,
                    'net_balance' => $netBalance,
                    'recent_transactions' => $recentTransactions,
                    // Summary for alerts
                    'balance_status' => $this->getBalanceStatus($netBalance),
                    'attendance_summary' => [
                        'total_days' => $workingDaysInMonth,
                        'worked_days' => $presentDays + $lateDays,
                        'attendance_rate' => $actualWorkingDays > 0 ? round((($presentDays + $lateDays) / $actualWorkingDays) * 100, 1) : 0
                    ]
                ];
            }

            return response()->json([
                'success' => true,
                'attendance' => $attendanceData,
                'working_days_in_month' => $workingDaysInMonth,
                'month_start' => $startOfMonth->format('Y-m-d'),
                'month_end' => $endOfMonth->format('Y-m-d'),
                'month_name' => $this->getArabicMonthName($date->month) . ' ' . $date->year
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting monthly attendance: ' . $e->getMessage(), [
                'month' => $request->input('month'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب بيانات الحضور: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get balance status for alerts
     */
    private function getBalanceStatus($netBalance)
    {
        if ($netBalance > 0) {
            return [
                'type' => 'credit',
                'label' => 'دائن',
                'color' => 'green',
                'message' => 'للموظف رصيد دائن'
            ];
        } elseif ($netBalance < 0) {
            return [
                'type' => 'debit',
                'label' => 'مدين',
                'color' => 'red',
                'message' => 'على الموظف رصيد مدين'
            ];
        } else {
            return [
                'type' => 'neutral',
                'label' => 'متوازن',
                'color' => 'gray',
                'message' => 'رصيد الموظف متوازن'
            ];
        }
    }

    /**
     * Get Arabic month name
     */
    private function getArabicMonthName($monthNumber)
    {
        $months = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];
        
        return $months[$monthNumber] ?? 'غير معروف';
    }

    /**
     * Calculate working days in a month (excluding Fridays)
     */
    private function getWorkingDaysInMonth($startDate, $endDate)
    {
        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Skip Fridays (day of week 5 in Carbon)
            if ($currentDate->dayOfWeek !== Carbon::FRIDAY) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'payroll_date' => 'required|date',
            'payroll_month' => 'required|string',
            'notes' => 'nullable|string',
            'employees' => 'required|array|min:1',
            'employees.*' => 'exists:employees,id',
            'working_days' => 'nullable|array',
            'working_days.*' => 'nullable|integer|min:0|max:31',
            'absent_days' => 'nullable|array',
            'absent_days.*' => 'nullable|integer|min:0|max:31',
            'overtime_hours' => 'nullable|array',
            'overtime_hours.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $payroll = Payroll::create([
                'title' => $validated['title'],
                'payroll_date' => $validated['payroll_date'],
                'notes' => $validated['notes'],
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            // Add employees to payroll
            foreach ($validated['employees'] as $employeeId) {
                $employee = Employee::find($employeeId);

                // Get salary and eligibility from form
                $baseSalary = $request->input("salary.{$employeeId}", $employee->salary ?? 0);
                $isEligible = $request->has("is_eligible.{$employeeId}");
                
                // Get attendance data
                $workingDays = $request->input("working_days.{$employeeId}", 0);
                $absentDays = $request->input("absent_days.{$employeeId}", 0);
                $overtimeHours = $request->input("overtime_hours.{$employeeId}", 0);

                $payrollEmployee = PayrollEmployee::create([
                    'payroll_id' => $payroll->id,
                    'employee_id' => $employeeId,
                    'base_salary' => $baseSalary,
                    'working_days' => $workingDays,
                    'absent_days' => $absentDays,
                    'overtime_hours' => $overtimeHours,
                    'total_working_hours' => 0, // Will be calculated later if needed
                    'total_deductions' => 0,
                    'total_bonuses' => 0,
                    'net_salary' => $baseSalary,
                    'is_eligible' => $isEligible,
                ]);

                // Add bonuses if any
                $bonuses = $request->input("bonuses.{$employeeId}", []);
                if (!empty($bonuses['type'])) {
                    foreach ($bonuses['type'] as $index => $type) {
                        if (!empty($type) && !empty($bonuses['amount'][$index])) {
                            PayrollBonus::create([
                                'payroll_employee_id' => $payrollEmployee->id,
                                'type' => $type,
                                'amount' => $bonuses['amount'][$index],
                                'notes' => $bonuses['notes'][$index] ?? null,
                            ]);
                        }
                    }
                }

                // Add deductions if any
                $deductions = $request->input("deductions.{$employeeId}", []);
                if (!empty($deductions['type'])) {
                    foreach ($deductions['type'] as $index => $type) {
                        if (!empty($type) && !empty($deductions['amount'][$index])) {
                            PayrollDeduction::create([
                                'payroll_employee_id' => $payrollEmployee->id,
                                'type' => $type,
                                'amount' => $deductions['amount'][$index],
                                'notes' => $deductions['notes'][$index] ?? null,
                            ]);
                        }
                    }
                }

                // Calculate net salary after bonuses and deductions
                $payrollEmployee->calculateNetSalary();
            }

            // Calculate total amount
            $payroll->calculateTotalAmount();

            DB::commit();

            return redirect()->route('payroll.show', $payroll)
                ->with('success', 'تم إنشاء مسيرة الراتب بنجاح');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء مسيرة الراتب: ' . $e->getMessage());
        }
    }

    public function show(Payroll $payroll)
    {
        $payroll->load([
            'employees.employee',
            'employees.deductions',
            'employees.bonuses',
            'creator'
        ]);

        $summary = [
            'total_employees' => $payroll->employees->count(),
            'eligible_employees' => $payroll->employees->where('is_eligible', true)->count(),
            'total_base_salary' => $payroll->employees->sum('base_salary'),
            'total_deductions' => $payroll->employees->sum('total_deductions'),
            'total_bonuses' => $payroll->employees->sum('total_bonuses'),
            'total_net_salary' => $payroll->employees->sum('net_salary'),
        ];

        return view('payroll.show', compact('payroll', 'summary'));
    }

    public function edit(Payroll $payroll)
    {
        if ($payroll->status === 'approved') {
            return redirect()->route('payroll.show', $payroll)
                ->with('error', 'لا يمكن تعديل مسيرة راتب معتمدة');
        }

        $payroll->load([
            'employees.employee',
            'employees.deductions',
            'employees.bonuses'
        ]);
        $allEmployees = Employee::where('status', 'active')->orderBy('name')->get();

        return view('payroll.edit', compact('payroll', 'allEmployees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        if ($payroll->status === 'approved') {
            return redirect()->route('payroll.show', $payroll)
                ->with('error', 'لا يمكن تعديل مسيرة راتب معتمدة');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'payroll_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $payroll->update($validated);

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'تم تحديث مسيرة الراتب بنجاح');
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'approved') {
            return redirect()->route('payroll.index')
                ->with('error', 'لا يمكن حذف مسيرة راتب معتمدة');
        }

        $payroll->delete();

        return redirect()->route('payroll.index')
            ->with('success', 'تم حذف مسيرة الراتب بنجاح');
    }

    public function approve(Payroll $payroll)
    {
        if ($payroll->status === 'approved') {
            return back()->with('error', 'مسيرة الراتب معتمدة بالفعل');
        }

        // إذا كانت مسودة، غيرها إلى pending للمراجعة
        if ($payroll->status === 'draft') {
            $payroll->update(['status' => 'pending']);
            return back()->with('success', 'تم تجهيز مسيرة الراتب للاعتماد');
        }

        // إذا كانت pending، اعتمدها نهائياً
        $payroll->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'تم اعتماد مسيرة الراتب بنجاح');
    }

    public function reject(Payroll $payroll)
    {
        if ($payroll->status === 'approved') {
            return back()->with('error', 'لا يمكن رفض مسيرة راتب معتمدة');
        }

        $payroll->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'تم رفض مسيرة الراتب');
    }

    public function employeeDetails(Payroll $payroll, Employee $employee)
    {
        $payrollEmployee = PayrollEmployee::where('payroll_id', $payroll->id)
            ->where('employee_id', $employee->id)
            ->with(['deductions', 'bonuses'])
            ->firstOrFail();

        return view('payroll.employee-details', compact('payroll', 'employee', 'payrollEmployee'));
    }

    public function updateEmployeeDetails(Request $request, Payroll $payroll, Employee $employee)
    {
        if ($payroll->status === 'approved') {
            return back()->with('error', 'لا يمكن تعديل مسيرة راتب معتمدة');
        }

        $validated = $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'is_eligible' => 'boolean',
        ]);

        $payrollEmployee = PayrollEmployee::where('payroll_id', $payroll->id)
            ->where('employee_id', $employee->id)
            ->firstOrFail();

        $payrollEmployee->update([
            'base_salary' => $validated['base_salary'],
            'is_eligible' => $request->has('is_eligible'),
        ]);

        $payrollEmployee->calculateNetSalary();
        $payroll->calculateTotalAmount();

        return back()->with('success', 'تم تحديث تفاصيل الموظف بنجاح');
    }

    public function addDeduction(Request $request, Payroll $payroll, Employee $employee)
    {
        if ($payroll->status === 'approved') {
            return back()->with('error', 'لا يمكن تعديل مسيرة راتب معتمدة');
        }

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $payrollEmployee = PayrollEmployee::where('payroll_id', $payroll->id)
            ->where('employee_id', $employee->id)
            ->firstOrFail();

        PayrollDeduction::create([
            'payroll_employee_id' => $payrollEmployee->id,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'notes' => $validated['notes'],
        ]);

        $payrollEmployee->calculateNetSalary();
        $payroll->calculateTotalAmount();

        return back()->with('success', 'تم إضافة الاستقطاع بنجاح');
    }

    public function addBonus(Request $request, Payroll $payroll, Employee $employee)
    {
        if ($payroll->status === 'approved') {
            return back()->with('error', 'لا يمكن تعديل مسيرة راتب معتمدة');
        }

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $payrollEmployee = PayrollEmployee::where('payroll_id', $payroll->id)
            ->where('employee_id', $employee->id)
            ->firstOrFail();

        PayrollBonus::create([
            'payroll_employee_id' => $payrollEmployee->id,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'notes' => $validated['notes'],
        ]);

        $payrollEmployee->calculateNetSalary();
        $payroll->calculateTotalAmount();

        return back()->with('success', 'تم إضافة البدل بنجاح');
    }

    public function deleteDeduction(PayrollDeduction $deduction)
    {
        if ($deduction->payrollEmployee->payroll->status === 'approved') {
            return back()->with('error', 'لا يمكن تعديل مسيرة راتب معتمدة');
        }

        $payrollEmployee = $deduction->payrollEmployee;
        $payroll = $payrollEmployee->payroll;

        $deduction->delete();

        $payrollEmployee->calculateNetSalary();
        $payroll->calculateTotalAmount();

        return back()->with('success', 'تم حذف الاستقطاع بنجاح');
    }

    public function deleteBonus(PayrollBonus $bonus)
    {
        if ($bonus->payrollEmployee->payroll->status === 'approved') {
            return back()->with('error', 'لا يمكن تعديل مسيرة راتب معتمدة');
        }

        $payrollEmployee = $bonus->payrollEmployee;
        $payroll = $payrollEmployee->payroll;

        $bonus->delete();

        $payrollEmployee->calculateNetSalary();
        $payroll->calculateTotalAmount();

        return back()->with('success', 'تم حذف البدل بنجاح');
    }

    public function print(Payroll $payroll)
    {
        $payroll->load([
            'employees.employee',
            'employees.deductions',
            'employees.bonuses',
            'creator'
        ]);

        return view('payroll.print', compact('payroll'));
    }
}
