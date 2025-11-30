<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    /**
     * Store a newly created spare part in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:spare_parts,name',
            'code' => 'required|string|max:100|unique:spare_parts,code',
            'spare_part_type_id' => 'nullable|exists:spare_part_types,id',
            'minimum_stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['unit_price'] = 0;

        SparePart::create($validated);

        return redirect()->route('warehouses.index')->with('success', 'تم إضافة قطعة الغيار بنجاح');
    }

    /**
     * Update the specified spare part in storage.
     */
    public function update(Request $request, SparePart $sparePart)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:spare_parts,name,' . $sparePart->id,
            'code' => 'required|string|max:100|unique:spare_parts,code,' . $sparePart->id,
            'spare_part_type_id' => 'nullable|exists:spare_part_types,id',
            'minimum_stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $sparePart->update($validated);

        return redirect()->route('warehouses.index')->with('success', 'تم تحديث قطعة الغيار بنجاح');
    }

    /**
     * Remove the specified spare part from storage.
     */
    public function destroy(SparePart $sparePart)
    {
        // Check if spare part has any inventory records
        if ($sparePart->inventories()->exists()) {
            return redirect()->route('warehouses.index')
                ->with('error', 'لا يمكن حذف قطعة غيار لها سجلات مخزون');
        }

        $sparePart->delete();

        return redirect()->route('warehouses.index')->with('success', 'تم حذف قطعة الغيار بنجاح');
    }
}
