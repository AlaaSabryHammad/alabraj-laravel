<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RevenueVoucher;
use App\Models\RevenueEntity;
use App\Models\Project;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class RevenueVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('finance.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $revenueEntities = RevenueEntity::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('revenue-vouchers.create', compact(
            'revenueEntities',
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
            'revenue_entity_id' => 'nullable|exists:revenue_entities,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,other',
            'tax_type' => 'required|in:taxable,non_taxable',
            'project_id' => 'nullable|exists:projects,id',
            'location_id' => 'nullable|exists:locations,id',
            'notes' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // معالجة رفع الملف
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('revenue-vouchers', $fileName, 'public');
            $validated['attachment_path'] = $filePath;
        }

        // حساب الضريبة تلقائياً
        $taxRate = 15.00; // معدل الضريبة 15%
        
        if ($validated['tax_type'] === 'taxable') {
            // المبلغ شامل الضريبة، نحسب المبلغ بدون الضريبة
            $amountWithTax = $validated['amount'];
            $amountWithoutTax = $amountWithTax / (1 + ($taxRate / 100));
            $taxAmount = $amountWithTax - $amountWithoutTax;
            
            $validated['tax_rate'] = $taxRate;
            $validated['tax_amount'] = $taxAmount;
            $validated['amount_without_tax'] = $amountWithoutTax;
        } else {
            // غير خاضع للضريبة
            $validated['tax_rate'] = 0;
            $validated['tax_amount'] = 0;
            $validated['amount_without_tax'] = $validated['amount'];
        }

        $validated['created_by'] = Auth::id();

        $voucher = RevenueVoucher::create($validated);

        return redirect()->route('finance.index')
            ->with('success', 'تم إنشاء سند القبض بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(RevenueVoucher $revenueVoucher)
    {
        $revenueVoucher->load(['revenueEntity', 'project', 'location', 'creator', 'approver']);
        return view('revenue-vouchers.show', compact('revenueVoucher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RevenueVoucher $revenueVoucher)
    {
        $revenueEntities = RevenueEntity::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('revenue-vouchers.edit', compact(
            'revenueVoucher',
            'revenueEntities',
            'projects',
            'locations'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RevenueVoucher $revenueVoucher)
    {
        $validated = $request->validate([
            'voucher_date' => 'required|date',
            'revenue_entity_id' => 'nullable|exists:revenue_entities,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,other',
            'tax_type' => 'required|in:taxable,non_taxable',
            'project_id' => 'nullable|exists:projects,id',
            'location_id' => 'nullable|exists:locations,id',
            'notes' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // حساب الضريبة تلقائياً
        $taxRate = 15.00; // معدل الضريبة 15%
        
        if ($validated['tax_type'] === 'taxable') {
            // المبلغ شامل الضريبة، نحسب المبلغ بدون الضريبة
            $amountWithTax = $validated['amount'];
            $amountWithoutTax = $amountWithTax / (1 + ($taxRate / 100));
            $taxAmount = $amountWithTax - $amountWithoutTax;
            
            $validated['tax_rate'] = $taxRate;
            $validated['tax_amount'] = $taxAmount;
            $validated['amount_without_tax'] = $amountWithoutTax;
        } else {
            // غير خاضع للضريبة
            $validated['tax_rate'] = 0;
            $validated['tax_amount'] = 0;
            $validated['amount_without_tax'] = $validated['amount'];
        }

        // معالجة رفع الملف
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('revenue-vouchers', $fileName, 'public');
            $validated['attachment_path'] = $filePath;
        }

        $revenueVoucher->update($validated);

        return redirect()->route('finance.index')
            ->with('success', 'تم تحديث سند القبض بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RevenueVoucher $revenueVoucher)
    {
        $revenueVoucher->delete();

        return redirect()->route('finance.index')
            ->with('success', 'تم حذف سند القبض بنجاح');
    }

    /**
     * Approve the revenue voucher.
     */
    public function approve(RevenueVoucher $revenueVoucher)
    {
        $revenueVoucher->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->route('finance.index')
            ->with('success', 'تم اعتماد سند القبض بنجاح');
    }

    /**
     * Mark the revenue voucher as received.
     */
    public function markAsReceived(RevenueVoucher $revenueVoucher)
    {
        $revenueVoucher->update([
            'status' => 'received'
        ]);

        return redirect()->route('finance.index')
            ->with('success', 'تم تسجيل سند القبض كمستلم بنجاح');
    }

    /**
     * Print the revenue voucher.
     */
    public function print(RevenueVoucher $revenueVoucher)
    {
        $revenueVoucher->load(['revenueEntity', 'project', 'location', 'creator', 'approver']);
        return view('revenue-vouchers.print', compact('revenueVoucher'));
    }
}
