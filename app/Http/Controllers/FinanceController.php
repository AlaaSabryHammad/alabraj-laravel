<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Finance;
use App\Models\Custody;
use App\Models\Employee;
use App\Models\ExpenseVoucher;
use App\Models\RevenueVoucher;
use Carbon\Carbon;

use Illuminate\Pagination\LengthAwarePaginator;

class FinanceController extends Controller
{
    /**
     * Show detailed financial report for an employee
     */
    public function employeeReport(Employee $employee)
    {
        $custodies = $employee->custodies()
            ->with(['type', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();

        $expenseVouchers = $employee->expenseVouchers()
            ->with(['approver'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCustody = $custodies->sum('amount');
        $totalExpenses = $expenseVouchers->sum('amount');
        $balance = $totalCustody - $totalExpenses;

        return view('finance.employee-report', compact(
            'employee',
            'custodies',
            'expenseVouchers',
            'totalCustody',
            'totalExpenses',
            'balance'
        ));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all transactions
        $revenueVouchers = RevenueVoucher::with(['revenueEntity'])
            ->get()
            ->map(function ($voucher) {
                return [
                    'id' => $voucher->id,
                    'type' => 'revenue_voucher',
                    'type_label' => 'سند قبض',
                    'type_color' => 'green',
                    'description' => $voucher->description,
                    'amount' => $voucher->amount,
                    'formatted_amount' => $voucher->formatted_amount,
                    'transaction_date' => $voucher->voucher_date,
                    'payment_method' => $voucher->payment_method_text,
                    'status' => $voucher->status,
                    'status_label' => $voucher->status_text,
                    'status_color' => $voucher->status_color,
                    'reference_number' => $voucher->voucher_number,
                    'created_at' => $voucher->created_at
                ];
            });

        $expenseVouchers = ExpenseVoucher::with(['expenseCategory', 'employee'])
            ->get()
            ->map(function ($voucher) {
                return [
                    'id' => $voucher->id,
                    'type' => 'expense_voucher',
                    'type_label' => 'سند صرف',
                    'type_color' => 'red',
                    'description' => $voucher->description,
                    'amount' => $voucher->amount,
                    'formatted_amount' => $voucher->formatted_amount,
                    'transaction_date' => $voucher->voucher_date,
                    'payment_method' => $voucher->payment_method_text,
                    'status' => $voucher->status,
                    'status_label' => $voucher->status_text,
                    'status_color' => $voucher->status_color,
                    'reference_number' => $voucher->voucher_number,
                    'created_at' => $voucher->created_at
                ];
            });

        $custodies = Custody::with('employee')
            ->get()
            ->map(function ($custody) {
                return [
                    'id' => $custody->id,
                    'type' => 'custody',
                    'type_label' => 'عهدة',
                    'type_color' => 'blue',
                    'description' => 'عهدة للموظف: ' . ($custody->employee->name ?? 'غير محدد'),
                    'amount' => $custody->amount,
                    'formatted_amount' => number_format($custody->amount, 2) . ' ر.س',
                    'transaction_date' => $custody->disbursement_date,
                    'payment_method' => $custody->receipt_method_text,
                    'status' => $custody->status ?? 'disbursed',
                    'status_label' => $custody->status_text ?? 'تم الصرف',
                    'status_color' => 'blue',
                    'reference_number' => 'C-' . str_pad($custody->id, 6, '0', STR_PAD_LEFT),
                    'created_at' => $custody->created_at
                ];
            });

        // إضافة المستخلصات المدفوعة التي لها سندات قبض
        $paidExtracts = \App\Models\ProjectExtract::with(['project', 'revenueVoucher'])
            ->where('status', 'paid')
            ->whereNotNull('revenue_voucher_id')
            ->get()
                                ->map(function ($extract) {
                        return [
                            'id' => $extract->id,
                            'type' => 'paid_extract',
                            'type_label' => 'مستخلص مدفوع',
                            'type_color' => 'purple',
                            'description' => 'مستخلص مشروع: ' . ($extract->project->name ?? 'غير محدد') . ' - ' . ($extract->description ?? 'مستخلص رقم ' . $extract->extract_number),
                            'amount' => $extract->total_with_tax, // استخدام القيمة مع الضريبة
                            'formatted_amount' => number_format($extract->total_with_tax, 2) . ' ر.س',
                            'transaction_date' => $extract->extract_date,
                            'payment_method' => 'تحويل بنكي',
                            'status' => 'paid',
                            'status_label' => 'مدفوع',
                            'status_color' => 'green',
                            'reference_number' => $extract->extract_number,
                            'created_at' => $extract->created_at
                        ];
                    });

        $transactions = $revenueVouchers
            ->concat($expenseVouchers)
            ->concat($custodies)
            ->concat($paidExtracts)
            ->sortByDesc('created_at')
            ->values();

        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $currentPageItems = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $finances = new LengthAwarePaginator($currentPageItems, $transactions->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        // Calculate statistics
        $totalIncome = RevenueVoucher::where('status', '!=', 'cancelled')->sum('amount');
        $totalExpenseVouchers = ExpenseVoucher::where('status', '!=', 'cancelled')->sum('amount');
        $totalCustodies = Custody::where('status', '!=', 'cancelled')->sum('amount');
        $totalExpense = $totalExpenseVouchers + $totalCustodies; // إجمالي المصروفات (سندات صرف + عهد)
        $netProfit = $totalIncome - $totalExpenseVouchers; // صافي الربح = الإيرادات - سندات الصرف فقط
        $monthlyIncome = RevenueVoucher::where('status', '!=', 'cancelled')
            ->whereMonth('voucher_date', Carbon::now()->month)
            ->sum('amount');

        // حساب الضرائب
        // 1. الضرائب من المستخلصات المدفوعة
        $extractTaxRevenue = \App\Models\ProjectExtract::where('status', 'paid')
            ->whereNotNull('revenue_voucher_id')
            ->sum('tax_amount');
        
        // 2. الضرائب من سندات القبض الخاضعة للضريبة
        $voucherTaxRevenue = \App\Models\RevenueVoucher::where('status', '!=', 'cancelled')
            ->where('tax_type', 'taxable')
            ->sum('tax_amount');
        
        // إجمالي الإيرادات الضريبية (المستخلصات + سندات القبض)
        $totalTaxRevenue = $extractTaxRevenue + $voucherTaxRevenue;
        
        // حساب الضرائب من سندات الصرف (افتراضياً 15%)
        $totalTaxExpenses = ExpenseVoucher::where('status', '!=', 'cancelled')
            ->where('tax_type', 'taxable')
            ->get()
            ->sum(function ($voucher) {
                return $voucher->amount * 0.15; // ضريبة 15%
            });

        // Get all active employees for custody form
        $employees = Employee::active()->orderBy('name')->get();

        // Get employee balances for active custodies
        $employeeBalances = Employee::whereHas('custodies', function ($query) {
            $query->where('status', 'active');
        })
            ->with(['custodies' => function ($query) {
                $query->where('status', 'active');
            }, 'expenseVouchers' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get()
            ->map(function ($employee) {
                $totalCustodies = $employee->custodies->sum('amount');
                $totalExpenses = $employee->expenseVouchers->sum('amount');

                return [
                    'employee' => $employee,
                    'total_custodies' => $totalCustodies,
                    'total_expenses' => $totalExpenses,
                    'current_balance' => $totalCustodies - $totalExpenses,
                    'last_custody' => $employee->custodies->sortByDesc('created_at')->first(),
                    'custodies_count' => $employee->custodies->count()
                ];
            })
            ->sortByDesc('current_balance')
            ->values();

        return view('finance.index', compact('finances', 'totalIncome', 'totalExpense', 'totalExpenseVouchers', 'totalCustodies', 'netProfit', 'monthlyIncome', 'totalTaxRevenue', 'totalTaxExpenses', 'employees', 'employeeBalances'));
    }

    /**
     * Display all financial transactions
     */
    public function allTransactions(Request $request)
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->format('Y-m-d'));
        $type = $request->get('type', 'all');

        // Get revenue vouchers
        $revenueVouchers = RevenueVoucher::with(['revenueEntity', 'project', 'location'])
            ->when($fromDate, function ($query) use ($fromDate) {
                return $query->whereDate('voucher_date', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->whereDate('voucher_date', '<=', $toDate);
            })
            ->when($type != 'all', function ($query) use ($type) {
                return $type == 'revenue' ? $query : $query->whereRaw('1 = 0');
            })
            ->get()
            ->map(function ($voucher) {
                return [
                    'id' => $voucher->id,
                    'type' => 'revenue_voucher',
                    'type_text' => 'سند قبض',
                    'voucher_number' => $voucher->voucher_number,
                    'date' => $voucher->voucher_date,
                    'amount' => $voucher->amount,
                    'description' => $voucher->description,
                    'payment_method' => $voucher->payment_method_text,
                    'status' => $voucher->status,
                    'status_text' => $voucher->status_text,
                    'status_color' => $voucher->status_color,
                    'entity' => $voucher->revenueEntity->name ?? 'غير محدد',
                    'project' => $voucher->project->name ?? 'غير محدد',
                    'notes' => $voucher->notes,
                    'is_income' => true,
                    'created_at' => $voucher->created_at
                ];
            });

        // Get expense vouchers
        $expenseVouchers = ExpenseVoucher::with(['expenseCategory', 'employee', 'expenseEntity', 'project', 'location'])
            ->when($fromDate, function ($query) use ($fromDate) {
                return $query->whereDate('voucher_date', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->whereDate('voucher_date', '<=', $toDate);
            })
            ->when($type != 'all', function ($query) use ($type) {
                return $type == 'expense' ? $query : $query->whereRaw('1 = 0');
            })
            ->get()
            ->map(function ($voucher) {
                return [
                    'id' => $voucher->id,
                    'type' => 'expense_voucher',
                    'type_text' => 'سند صرف',
                    'voucher_number' => $voucher->voucher_number,
                    'date' => $voucher->voucher_date,
                    'amount' => $voucher->amount,
                    'description' => $voucher->description,
                    'payment_method' => $voucher->payment_method_text,
                    'status' => $voucher->status,
                    'status_text' => $voucher->status_text,
                    'status_color' => $voucher->status_color,
                    'entity' => $voucher->expenseEntity->name ?? ($voucher->employee->name ?? 'غير محدد'),
                    'project' => $voucher->project->name ?? 'غير محدد',
                    'notes' => $voucher->notes,
                    'is_income' => false,
                    'created_at' => $voucher->created_at
                ];
            });

        // Get custodies
        $custodies = Custody::with('employee')
            ->when($fromDate, function ($query) use ($fromDate) {
                return $query->whereDate('disbursement_date', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->whereDate('disbursement_date', '<=', $toDate);
            })
            ->when($type != 'all', function ($query) use ($type) {
                return $type == 'custody' ? $query : $query->whereRaw('1 = 0');
            })
            ->get()
            ->map(function ($custody) {
                return [
                    'id' => $custody->id,
                    'type' => 'custody',
                    'type_text' => 'عهدة',
                    'voucher_number' => 'C-' . str_pad($custody->id, 6, '0', STR_PAD_LEFT),
                    'date' => $custody->disbursement_date,
                    'amount' => $custody->amount,
                    'description' => 'عهدة للموظف: ' . $custody->employee->name,
                    'payment_method' => $custody->receipt_method_text,
                    'status' => $custody->status ?? 'disbursed',
                    'status_text' => $custody->status_text ?? 'تم الصرف',
                    'status_color' => 'blue',
                    'entity' => $custody->employee->name ?? 'غير محدد',
                    'project' => 'غير محدد',
                    'notes' => $custody->notes,
                    'is_income' => false,
                    'created_at' => $custody->created_at
                ];
            });

        // Merge and sort all transactions
        $transactions = $revenueVouchers
            ->concat($expenseVouchers)
            ->concat($custodies)
            ->sortByDesc(function ($transaction) {
                return $transaction['date'] . ' ' . $transaction['created_at'];
            })
            ->values();

        // Calculate statistics - only include approved transactions
        $totalRevenue = $transactions->where('is_income', true)
            ->where('status', 'approved')
            ->sum('amount');
        $totalExpense = $transactions->where('is_income', false)
            ->where('status', 'approved')
            ->sum('amount');
        $netBalance = $totalRevenue - $totalExpense;

        // Calculate carried balance
        $carriedBalance = $this->calculateCarriedBalance($fromDate);

        return view('finance.all-transactions', compact(
            'transactions',
            'totalRevenue',
            'totalExpense',
            'netBalance',
            'carriedBalance',
            'fromDate',
            'toDate',
            'type'
        ));
    }

    /**
     * Calculate carried balance from start of year to a specific date
     */
    private function calculateCarriedBalance($fromDate)
    {
        $startOfYear = Carbon::parse($fromDate)->startOfYear();
        $endDate = Carbon::parse($fromDate)->subDay();

        $revenueSum = RevenueVoucher::whereDate('voucher_date', '>=', $startOfYear)
            ->whereDate('voucher_date', '<=', $endDate)
            ->where('status', 'approved')
            ->sum('amount');

        $expenseSum = ExpenseVoucher::whereDate('voucher_date', '>=', $startOfYear)
            ->whereDate('voucher_date', '<=', $endDate)
            ->where('status', 'approved')
            ->sum('amount');

        $custodySum = Custody::whereDate('disbursement_date', '>=', $startOfYear)
            ->whereDate('disbursement_date', '<=', $endDate)
            ->where('status', 'approved')
            ->sum('amount');

        return $revenueSum - $expenseSum - $custodySum;
    }

    /**
     * Display the specified finance record.
     */
    public function show($id)
    {
        // Determine the type and fetch the correct model
        $transaction = null;

        // Try to find in RevenueVoucher
        $transaction = RevenueVoucher::with(['revenueEntity', 'project'])->find($id);
        if ($transaction) {
            $transaction = (object) [
                'id' => $transaction->id,
                'type' => 'revenue',
                'type_text' => 'سند قبض',
                'reference_number' => $transaction->voucher_number,
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'payment_method' => __('finance.payment_methods.' . $transaction->payment_method),
                'date' => $transaction->voucher_date,
                'status' => $transaction->status,
                'status_text' => $transaction->status_text,
                'status_color' => $transaction->status_color,
                'project' => $transaction->project?->name,
                'entity' => $transaction->revenueEntity?->name,
                'notes' => $transaction->notes,
                'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $transaction->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        // If not found, try ExpenseVoucher
        if (!$transaction) {
            $transaction = ExpenseVoucher::with(['expenseEntity', 'employee', 'project'])->find($id);
            if ($transaction) {
                $transaction = (object) [
                    'id' => $transaction->id,
                    'type' => 'expense',
                    'type_text' => 'سند صرف',
                    'reference_number' => $transaction->voucher_number,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'payment_method' => __('finance.payment_methods.' . $transaction->payment_method),
                    'date' => $transaction->voucher_date,
                    'status' => $transaction->status,
                    'status_text' => $transaction->status_text,
                    'status_color' => $transaction->status_color,
                    'project' => $transaction->project?->name,
                    'entity' => $transaction->expenseEntity?->name ?? $transaction->employee?->name,
                    'notes' => $transaction->notes,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $transaction->updated_at->format('Y-m-d H:i:s'),
                ];
            }
        }

        // If still not found, try Custody
        if (!$transaction) {
            $transaction = Custody::with(['employee'])->find($id);
            if ($transaction) {
                $transaction = (object) [
                    'id' => $transaction->id,
                    'type' => 'custody',
                    'type_text' => 'عهدة',
                    'reference_number' => 'C-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT),
                    'amount' => $transaction->amount,
                    'description' => 'عهدة للموظف: ' . $transaction->employee->name,
                    'payment_method' => __('finance.payment_methods.' . ($transaction->receipt_method ?? 'other')),
                    'date' => $transaction->disbursement_date,
                    'status' => $transaction->status ?? 'disbursed',
                    'status_text' => $transaction->status_text ?? 'تم الصرف',
                    'status_color' => 'blue',
                    'project' => null,
                    'entity' => $transaction->employee?->name,
                    'notes' => $transaction->notes,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $transaction->updated_at->format('Y-m-d H:i:s'),
                ];
            }
        }

        if (!$transaction) {
            abort(404, 'المعاملة المالية غير موجودة');
        }

        return view('finance.show', compact('transaction'));
    }

    /**
     * Export daily report
     */
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $print = $request->get('print', false);
        $today = Carbon::parse($date);

        if (!$today->isValid()) {
            $today = now();
            $date = $today->format('Y-m-d');
        }

        // Initialize collections
        $transactions = collect();
        $dayRevenue = 0;
        $dayExpense = 0;

        // Revenue vouchers for approved transactions only
        $revenueVouchers = RevenueVoucher::with(['revenueEntity'])
            ->whereDate('voucher_date', $date)
            ->where('status', 'approved')
            ->get()
            ->map(function ($voucher) {
                return [
                    'type' => 'سند قبض',
                    'number' => $voucher->voucher_number ?? '-',
                    'description' => $voucher->description,
                    'amount' => $voucher->amount ?? 0,
                    'is_income' => true,
                    'status' => $voucher->status_text ?? 'معتمد'
                ];
            });

        // Expense vouchers for approved transactions only
        $expenseVouchers = ExpenseVoucher::with(['expenseCategory', 'employee'])
            ->whereDate('voucher_date', $date)
            ->where('status', 'approved')
            ->get()
            ->map(function ($voucher) {
                return [
                    'type' => 'سند صرف',
                    'number' => $voucher->voucher_number ?? '-',
                    'description' => $voucher->description,
                    'amount' => $voucher->amount ?? 0,
                    'is_income' => false,
                    'status' => $voucher->status_text ?? 'معتمد'
                ];
            });

        // Custodies for approved transactions only
        $custodies = Custody::with('employee')
            ->whereDate('disbursement_date', $date)
            ->where('status', 'approved')
            ->get()
            ->map(function ($custody) {
                return [
                    'type' => 'عهدة',
                    'number' => 'C-' . str_pad($custody->id, 6, '0', STR_PAD_LEFT),
                    'description' => $custody->employee
                        ? 'عهدة للموظف: ' . $custody->employee->name
                        : 'عهدة مالية',
                    'amount' => $custody->amount ?? 0,
                    'is_income' => false,
                    'status' => $custody->status_text ?? 'معتمد'
                ];
            });

        // Combine all transactions and calculate totals
        $transactions = $revenueVouchers->concat($expenseVouchers)->concat($custodies);
        $dayRevenue = $transactions->where('is_income', true)->sum('amount') ?? 0;
        $dayExpense = $transactions->where('is_income', false)->sum('amount') ?? 0;
        $dayNet = $dayRevenue - $dayExpense;

        // Get carried balance
        $carriedBalance = $this->calculateCarriedBalance($date) ?? 0;
        $finalBalance = $carriedBalance + $dayNet;

        $data = compact(
            'transactions',
            'dayRevenue',
            'dayExpense',
            'dayNet',
            'carriedBalance',
            'finalBalance',
            'date'
        );

        return view('finance.daily-report', $data);
    }
}
