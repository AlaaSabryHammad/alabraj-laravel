<?php

namespace App\Http\Controllers;

use App\Models\SparePartSupplier;
use Illuminate\Http\Request;

class SparePartSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SparePartSupplier::query();

        // Handle search
        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $suppliers = $query->latest()->paginate(10);

        // Preserve filters in pagination links
        $suppliers->appends($request->only(['search', 'status']));

        return view('spare-part-suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('spare-part-suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:spare_part_suppliers',
                'company_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'phone_2' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'tax_number' => 'nullable|string|max:255',
                'cr_number' => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'contact_person_phone' => 'nullable|string|max:20',
                'notes' => 'nullable|string',
                'status' => 'required|in:نشط,غير نشط',
                'credit_limit' => 'nullable|numeric|min:0',
                'payment_terms' => 'nullable|string|max:255',
            ]);

            // Ensure credit_limit is never null
            $validated['credit_limit'] = $validated['credit_limit'] ?? 0;

            $supplier = SparePartSupplier::create($validated);

            // إذا كان طلب AJAX، أرجع JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'phone' => $supplier->phone,
                    'email' => $supplier->email
                ], 201)->header('Content-Type', 'application/json; charset=utf-8');
            }

            return redirect()->route('spare-part-suppliers.index')
                ->with('success', 'تم إضافة المورد بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // معالجة أخطاء التحقق من الصحة
            if ($request->wantsJson() || $request->header('Accept') === 'application/json' || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'message' => 'خطأ في التحقق من البيانات',
                    'errors' => $e->errors()
                ], 422)->header('Content-Type', 'application/json; charset=utf-8');
            }
            throw $e;
        } catch (\Exception $e) {
            // معالجة الأخطاء العامة
            // تسجيل الخطأ للتصحيح
            \Log::error('SparePartSupplier store error: ' . $e->getMessage(), ['exception' => $e]);

            if ($request->wantsJson() || $request->header('Accept') === 'application/json' || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'message' => 'حدث خطأ في إضافة المورد: ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ], 500)->header('Content-Type', 'application/json; charset=utf-8');
            }
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SparePartSupplier $sparePartSupplier)
    {
        $sparePartSupplier->load('spareParts');
        return view('spare-part-suppliers.show', compact('sparePartSupplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SparePartSupplier $sparePartSupplier)
    {
        return view('spare-part-suppliers.edit', compact('sparePartSupplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SparePartSupplier $sparePartSupplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:spare_part_suppliers,name,' . $sparePartSupplier->id,
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'cr_number' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'status' => 'required|in:نشط,غير نشط',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:255',
        ]);

        // Ensure credit_limit is never null
        $validated['credit_limit'] = $validated['credit_limit'] ?? 0;

        $sparePartSupplier->update($validated);

        return redirect()->route('spare-part-suppliers.show', $sparePartSupplier)
            ->with('success', 'تم تحديث بيانات المورد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SparePartSupplier $sparePartSupplier)
    {
        // Check if supplier has spare parts
        if ($sparePartSupplier->spareParts()->exists()) {
            return back()->with('error', 'لا يمكن حذف مورد لديه قطع غيار مرتبطة به');
        }

        $sparePartSupplier->delete();

        return redirect()->route('spare-part-suppliers.index')
            ->with('success', 'تم حذف المورد بنجاح');
    }
}
