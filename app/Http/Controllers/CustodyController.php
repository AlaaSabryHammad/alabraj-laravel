<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Custody;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class CustodyController extends Controller
{
    /**
     * Store a newly created custody in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'disbursement_date' => 'required|date',
            'receipt_method' => 'required|in:cash,bank_transfer,check,other',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = Custody::STATUS_PENDING;

        $custody = Custody::create($validated);

        return redirect()->route('finance.custodies.show', $custody)
            ->with('success', 'تم تسجيل العهدة بنجاح');
    }
    /**
     * Display the specified custody.
     */
    public function show(Custody $custody)
    {
        $custody->load('employee');
        return view('custodies.show', compact('custody'));
    }

    /**
     * Print the custody document.
     */
    public function print(Custody $custody)
    {
        $custody->load(['employee']);
        return view('custodies.print', compact('custody'));
    }

    /**
     * Approve the custody.
     */
    public function approve(Custody $custody)
    {
        $custody->status = 'active';
        $custody->approved_at = now();
        $custody->approved_by = Auth::user()->id;
        $custody->save();

        return redirect()->back()
            ->with('success', 'تم اعتماد العهدة بنجاح');
    }
}
