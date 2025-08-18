<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseVoucher;
use App\Models\ExpenseCategory;
use App\Models\ExpenseEntity;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class ExpenseVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = ExpenseVoucher::with(['expenseCategory', 'employee', 'expenseEntity', 'project', 'location'])
            ->latest()
            ->paginate(15);

        return view('expense-vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expenseCategories = ExpenseCategory::active()->orderBy('name')->get();
        $employees = Employee::active()->orderBy('name')->get();
        $expenseEntities = ExpenseEntity::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('expense-vouchers.create', compact(
            'expenseCategories',
            'employees',
            'expenseEntities',
            'projects',
            'locations'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'employee_id' => 'nullable|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,other',
            'tax_type' => 'required|in:taxable,non_taxable',
            'description' => 'required|string|max:1000',
            'expense_entity_id' => 'nullable|exists:expense_entities,id',
            'project_id' => 'nullable|exists:projects,id',
            'location_id' => 'nullable|exists:locations,id',
            'notes' => 'nullable|string|max:2000',
            'reference_number' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // معالجة رفع الملف
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('expense-vouchers', $fileName, 'public');
            $validated['attachment_path'] = $filePath;
        }

        $validated['created_by'] = Auth::id();

        $voucher = ExpenseVoucher::create($validated);

        return redirect()->route('expense-vouchers.show', $voucher)
            ->with('success', 'تم إنشاء سند الصرف بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseVoucher $expenseVoucher)
    {
        $expenseVoucher->load(['expenseCategory', 'employee', 'expenseEntity', 'project', 'location', 'creator']);

        return view('expense-vouchers.show', compact('expenseVoucher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseVoucher $expenseVoucher)
    {
        $expenseCategories = ExpenseCategory::active()->orderBy('name')->get();
        $employees = Employee::active()->orderBy('name')->get();
        $expenseEntities = ExpenseEntity::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('expense-vouchers.edit', compact(
            'expenseVoucher',
            'expenseCategories',
            'employees',
            'expenseEntities',
            'projects',
            'locations'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseVoucher $expenseVoucher)
    {
        $validated = $request->validate([
            'voucher_date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'employee_id' => 'nullable|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,other',
            'description' => 'required|string|max:1000',
            'expense_entity_id' => 'nullable|exists:expense_entities,id',
            'project_id' => 'nullable|exists:projects,id',
            'location_id' => 'nullable|exists:locations,id',
            'notes' => 'nullable|string|max:2000',
            'reference_number' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,paid,cancelled'
        ]);

        $expenseVoucher->update($validated);

        return redirect()->route('expense-vouchers.show', $expenseVoucher)
            ->with('success', 'تم تحديث سند الصرف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseVoucher $expenseVoucher)
    {
        $expenseVoucher->delete();

        return redirect()->route('expense-vouchers.index')
            ->with('success', 'تم حذف سند الصرف بنجاح');
    }

    /**
     * Approve the expense voucher
     */
    public function approve(ExpenseVoucher $expenseVoucher)
    {
        $expenseVoucher->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'تم اعتماد سند الصرف بنجاح');
    }


    /**
     * Print the expense voucher.
     */
    public function print(ExpenseVoucher $expenseVoucher)
    {
        $expenseVoucher->load(['expenseCategory', 'employee', 'expenseEntity', 'project', 'location', 'creator', 'approver']);
        return view('expense-vouchers.print', compact('expenseVoucher'));
    }
}
