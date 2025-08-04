<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeReportController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $request->validate([
            'report_content' => 'required|string|max:2000',
        ]);

        EmployeeReport::create([
            'employee_id' => $employee->id,
            'reporter_id' => Auth::id(),
            'report_content' => $request->report_content,
            'report_type' => 'secret'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ التقرير بنجاح'
        ]);
    }

    public function index(Employee $employee)
    {
        $reports = $employee->reports()
            ->with('reporter')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reports);
    }

    public function print(Employee $employee)
    {
        $reports = $employee->reports()
            ->with('reporter')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employees.reports-print', compact('employee', 'reports'));
    }
}
