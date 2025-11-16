<?php

namespace App\Http\Controllers;

use App\Models\EquipmentMaintenance;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EquipmentMaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EquipmentMaintenance::with(['equipment', 'user']);

        // فلترة حسب نوع الصيانة
        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب المعدة
        if ($request->filled('equipment_id')) {
            $query->where('equipment_id', $request->equipment_id);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('equipment', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            })->orWhere('maintenance_center', 'like', "%{$search}%")
                ->orWhere('invoice_number', 'like', "%{$search}%");
        }

        $maintenances = $query->orderBy('maintenance_date', 'desc')->paginate(15);
        $equipment = Equipment::orderBy('name')->get();

        return view('equipment-maintenance.index', compact('maintenances', 'equipment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $equipment = Equipment::orderBy('name')->get();
        return view('equipment-maintenance.create', compact('equipment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'maintenance_date' => 'required|date',
            'maintenance_type' => 'required|in:internal,external',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'expected_completion_date' => 'nullable|date|after:maintenance_date',

            // حقول الصيانة الخارجية
            'external_cost' => 'nullable|required_if:maintenance_type,external|numeric|min:0',
            'maintenance_center' => 'nullable|required_if:maintenance_type,external|string|max:255',
            'invoice_number' => 'nullable|required_if:maintenance_type,external|string|max:255',
            'invoice_image' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $validated['user_id'] = Auth::id();

        // رفع صورة الفاتورة إذا كانت موجودة
        if ($request->hasFile('invoice_image')) {
            $validated['invoice_image'] = $request->file('invoice_image')->store('maintenance/invoices', 'public');
        }

        EquipmentMaintenance::create($validated);

        return redirect()->route('equipment-maintenance.index')
            ->with('success', 'تم تسجيل طلب الصيانة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentMaintenance $equipmentMaintenance)
    {
        $equipmentMaintenance->load(['equipment', 'user']);
        return view('equipment-maintenance.show', compact('equipmentMaintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentMaintenance $equipmentMaintenance)
    {
        $equipments = Equipment::orderBy('name')->get();
        return view('equipment-maintenance.edit', compact('equipmentMaintenance', 'equipments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentMaintenance $equipmentMaintenance)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'maintenance_date' => 'required|date',
            'maintenance_type' => 'required|in:internal,external',
            'status' => 'required|in:in_progress,completed,cancelled',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'expected_completion_date' => 'nullable|date|after:maintenance_date',
            'actual_completion_date' => 'nullable|date|after:maintenance_date',

            // حقول الصيانة الخارجية
            'external_cost' => 'nullable|required_if:maintenance_type,external|numeric|min:0',
            'maintenance_center' => 'nullable|required_if:maintenance_type,external|string|max:255',
            'invoice_number' => 'nullable|required_if:maintenance_type,external|string|max:255',
            'invoice_image' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        // رفع صورة فاتورة جديدة إذا كانت موجودة
        if ($request->hasFile('invoice_image')) {
            // حذف الصورة القديمة
            if ($equipmentMaintenance->invoice_image) {
                Storage::disk('public')->delete($equipmentMaintenance->invoice_image);
            }

            $validated['invoice_image'] = $request->file('invoice_image')->store('maintenance/invoices', 'public');
        }

        $equipmentMaintenance->update($validated);

        return redirect()->route('equipment-maintenance.show', $equipmentMaintenance)
            ->with('success', 'تم تحديث بيانات الصيانة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentMaintenance $equipmentMaintenance)
    {
        // Delete invoice image if exists
        if ($equipmentMaintenance->invoice_image) {
            Storage::delete($equipmentMaintenance->invoice_image);
        }

        $equipmentMaintenance->delete();

        return redirect()->route('equipment-maintenance.index')
            ->with('success', 'تم حذف سجل الصيانة بنجاح');
    }

    /**
     * Mark maintenance as completed
     */
    public function complete(Request $request, EquipmentMaintenance $equipmentMaintenance)
    {
        $validated = $request->validate([
            'actual_completion_date' => 'required|date',
            'completion_notes' => 'nullable|string|max:1000',
        ]);

        // Update the maintenance record
        $updateData = [
            'status' => 'completed',
            'actual_completion_date' => $validated['actual_completion_date'],
        ];

        // Add completion notes to existing notes if provided
        if (!empty($validated['completion_notes'])) {
            $existingNotes = $equipmentMaintenance->notes ?? '';
            $completionNote = "ملاحظات الاكتمال ({$validated['actual_completion_date']}): " . $validated['completion_notes'];

            if (!empty($existingNotes)) {
                $updateData['notes'] = $existingNotes . "\n\n" . $completionNote;
            } else {
                $updateData['notes'] = $completionNote;
            }
        }

        $equipmentMaintenance->update($updateData);

        return redirect()->route('equipment-maintenance.show', $equipmentMaintenance)
            ->with('success', 'تم تسجيل اكتمال الصيانة بنجاح في تاريخ ' . \Carbon\Carbon::parse($validated['actual_completion_date'])->format('Y/m/d'));
    }
}
