<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Finance;
use Carbon\Carbon;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $finances = Finance::latest()->paginate(10);

        // Calculate summary statistics
        $totalIncome = Finance::where('type', 'income')->sum('amount');
        $totalExpense = Finance::where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;
        $monthlyIncome = Finance::where('type', 'income')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->sum('amount');

        return view('finance.index', compact('finances', 'totalIncome', 'totalExpense', 'netProfit', 'monthlyIncome'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'completed';

        Finance::create($validated);

        return redirect()->route('finance.index')
            ->with('success', 'تم إضافة المعاملة المالية بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Finance $finance)
    {
        return view('finance.show', compact('finance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Finance $finance)
    {
        return view('finance.edit', compact('finance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Finance $finance)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $finance->update($validated);

        return redirect()->route('finance.index')
            ->with('success', 'تم تحديث المعاملة المالية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Finance $finance)
    {
        $finance->delete();

        return redirect()->route('finance.index')
            ->with('success', 'تم حذف المعاملة المالية بنجاح');
    }
}
