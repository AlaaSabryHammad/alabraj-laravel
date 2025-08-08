<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExternalTruck;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ExternalTruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ExternalTruck::with('supplier');

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%")
                    ->orWhere('driver_phone', 'like', "%{$search}%")
                    ->orWhere('contract_number', 'like', "%{$search}%");
            });
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Handle supplier filter
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->get('supplier_id'));
        }

        $trucks = $query->latest()->paginate(10);

        // Preserve filters in pagination links
        $trucks->appends($request->only(['search', 'status', 'supplier_id']));

        // Get suppliers for filter dropdown
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        return view('external-trucks.index', compact('trucks', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $suppliers = Supplier::where('status', 'نشط')
            ->orderBy('name')
            ->get(['id', 'name', 'company_name', 'phone', 'email']);

        // Get the pre-selected supplier if provided
        $selectedSupplierId = $request->get('supplier');

        return view('external-trucks.create', compact('suppliers', 'selectedSupplierId'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:255|unique:external_trucks',
            'driver_name' => 'required|string|max:255',
            'driver_phone' => 'required|string|max:20',
            'supplier_id' => 'required|exists:suppliers,id',
            'contract_number' => 'nullable|string|max:255',
            'daily_rate' => 'nullable|numeric|min:0',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        // Handle file uploads
        $photosPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('external-trucks/photos', 'public');
                $photosPaths[] = $path;
            }
        }
        $validated['photos'] = $photosPaths;

        ExternalTruck::create($validated);

        return redirect()->route('external-trucks.index')
            ->with('success', 'تم إضافة الشاحنة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExternalTruck $externalTruck)
    {
        $externalTruck->load('supplier');
        return view('external-trucks.show', compact('externalTruck'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExternalTruck $externalTruck)
    {
        $suppliers = Supplier::where('status', 'نشط')
            ->orderBy('name')
            ->get(['id', 'name', 'company_name', 'phone', 'email']);

        return view('external-trucks.edit', compact('externalTruck', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExternalTruck $externalTruck)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:255|unique:external_trucks,plate_number,' . $externalTruck->id,
            'driver_name' => 'required|string|max:255',
            'driver_phone' => 'required|string|max:20',
            'supplier_id' => 'required|exists:suppliers,id',
            'contract_number' => 'nullable|string|max:255',
            'daily_rate' => 'nullable|numeric|min:0',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        // Handle file uploads
        if ($request->hasFile('photos')) {
            // Delete old photos
            if ($externalTruck->photos) {
                foreach ($externalTruck->photos as $oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }

            // Upload new photos
            $photosPaths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('external-trucks/photos', 'public');
                $photosPaths[] = $path;
            }
            $validated['photos'] = $photosPaths;
        }

        $externalTruck->update($validated);

        return redirect()->route('external-trucks.index')
            ->with('success', 'تم تحديث بيانات الشاحنة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExternalTruck $externalTruck)
    {
        // Delete photos
        if ($externalTruck->photos) {
            foreach ($externalTruck->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $externalTruck->delete();

        return redirect()->route('external-trucks.index')
            ->with('success', 'تم حذف الشاحنة بنجاح');
    }

    /**
     * Get supplier data for AJAX requests
     */
    public function getSupplierData($id)
    {
        try {
            $supplier = Supplier::find($id);

            if (!$supplier) {
                return response()->json(['error' => 'المورد غير موجود'], 404);
            }

            return response()->json([
                'id' => $supplier->id,
                'name' => $supplier->name,
                'company_name' => $supplier->company_name,
                'phone' => $supplier->phone,
                'email' => $supplier->email,
                'address' => $supplier->address
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'خطأ في جلب بيانات المورد'], 500);
        }
    }
}
