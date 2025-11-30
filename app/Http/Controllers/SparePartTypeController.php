<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SparePartType;

class SparePartTypeController extends Controller
{
    /**
     * Display a listing of spare part types
     */
    public function index()
    {
        $sparePartTypes = SparePartType::orderBy('name')->paginate(20);
        return view('spare-part-types.index', compact('sparePartTypes'));
    }

    /**
     * Show the form for creating a new spare part type
     */
    public function create()
    {
        return view('spare-part-types.create');
    }

    /**
     * Store a new spare part type
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:spare_part_types,name',
            'description' => 'nullable|string|max:1000'
        ], [
            'name.required' => 'اسم نوع القطعة مطلوب',
            'name.unique' => 'هذا النوع موجود مسبقاً'
        ]);

        try {
            SparePartType::create($validated);

            return redirect()->route('warehouses.index')
                ->with('success', 'تم إضافة نوع قطعة الغيار بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('warehouses.index')
                ->with('error', 'حدث خطأ أثناء إضافة نوع القطعة: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a spare part type
     */
    public function edit(SparePartType $sparePartType)
    {
        return view('spare-part-types.edit', compact('sparePartType'));
    }

    /**
     * Update a spare part type
     */
    public function update(Request $request, SparePartType $sparePartType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:spare_part_types,name,' . $sparePartType->id,
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|in:engine,transmission,brakes,electrical,hydraulic,cooling,filters,tires,body,other',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم نوع القطعة مطلوب',
            'name.unique' => 'هذا النوع موجود مسبقاً',
            'category.required' => 'الفئة مطلوبة',
            'category.in' => 'الفئة المختارة غير صحيحة'
        ]);

        try {
            $sparePartType->update($validated);

            return redirect()->route('spare-part-types.index')
                ->with('success', 'تم تحديث نوع قطعة الغيار بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('spare-part-types.index')
                ->with('error', 'حدث خطأ أثناء تحديث نوع القطعة: ' . $e->getMessage());
        }
    }

    /**
     * Delete a spare part type
     */
    public function destroy(SparePartType $sparePartType)
    {
        try {
            // Check if this type is being used by any spare parts
            if ($sparePartType->spareParts()->count() > 0) {
                return redirect()->route('spare-part-types.index')
                    ->with('error', 'لا يمكن حذف هذا النوع لأنه مستخدم في قطع غيار موجودة');
            }

            $sparePartType->delete();

            return redirect()->route('spare-part-types.index')
                ->with('success', 'تم حذف نوع قطعة الغيار بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('spare-part-types.index')
                ->with('error', 'حدث خطأ أثناء حذف نوع القطعة: ' . $e->getMessage());
        }
    }

    /**
     * Get spare part types for AJAX requests
     */
    public function getTypes()
    {
        $types = SparePartType::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'category']);

        return response()->json($types);
    }
}
