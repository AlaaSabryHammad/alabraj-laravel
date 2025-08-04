<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Handle category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Handle payment terms filter
        if ($request->filled('payment_terms')) {
            $query->where('payment_terms', $request->get('payment_terms'));
        }

        $suppliers = $query->latest()->paginate(10);

        // Preserve all parameters in pagination links
        $suppliers->appends($request->except('page'));

        // Get filter options for dropdowns
        $categories = Supplier::distinct()->pluck('category')->filter()->sort();

        // Get stats for all suppliers (not just current page)
        $allSuppliers = Supplier::all();

        return view('suppliers.index', compact('suppliers', 'categories', 'allSuppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'cr_number' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'payment_terms' => 'required|in:نقدي,آجل 30 يوم,آجل 60 يوم,آجل 90 يوم',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:نشط,غير نشط,معلق',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20'
        ]);

        // تعيين قيمة افتراضية للحد الائتماني إذا كان فارغاً
        if (empty($validated['credit_limit'])) {
            $validated['credit_limit'] = 0;
        }

        $supplier = Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'تم إضافة المورد بنجاح');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('externalTrucks');
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'cr_number' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'payment_terms' => 'required|in:نقدي,آجل 30 يوم,آجل 60 يوم,آجل 90 يوم',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:نشط,غير نشط,معلق',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20'
        ]);

        // تعيين قيمة افتراضية للحد الائتماني إذا كان فارغاً
        if (empty($validated['credit_limit'])) {
            $validated['credit_limit'] = 0;
        }

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'تم تحديث بيانات المورد بنجاح');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'تم حذف المورد بنجاح');
    }

    /**
     * Get content for AJAX tab loading
     */
    public function content(Request $request)
    {
        $query = Supplier::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Handle category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Handle payment terms filter
        if ($request->filled('payment_terms')) {
            $query->where('payment_terms', $request->get('payment_terms'));
        }

        $suppliers = $query->latest()->paginate(10);

        // Preserve all parameters in pagination links
        $suppliers->appends($request->except('page'));

        // Get filter options for dropdowns
        $categories = Supplier::distinct()->pluck('category')->filter()->sort();

        // Get stats for all suppliers (not just current page)
        $allSuppliers = Supplier::all();

        return view('settings.partials.suppliers-content', compact('suppliers', 'categories', 'allSuppliers'));
    }
}
