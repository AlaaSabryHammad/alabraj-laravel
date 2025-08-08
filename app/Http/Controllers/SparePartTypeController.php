<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SparePartType;

class SparePartTypeController extends Controller
{
    /**
     * Store a new spare part type
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:spare_part_types,name',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|in:engine,transmission,brakes,electrical,hydraulic,cooling,filters,tires,body,other'
        ], [
            'name.required' => 'اسم نوع القطعة مطلوب',
            'name.unique' => 'هذا النوع موجود مسبقاً',
            'category.required' => 'الفئة مطلوبة',
            'category.in' => 'الفئة المختارة غير صحيحة'
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
