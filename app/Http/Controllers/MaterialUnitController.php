<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialUnit;

class MaterialUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materialUnits = MaterialUnit::orderBy('type')->orderBy('name')->get();
        return view('settings.material-units', compact('materialUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:material_units,name',
            'symbol' => 'required|string|max:10|unique:material_units,symbol',
            'type' => 'required|in:volume,weight,length,area,count',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        MaterialUnit::create($validated);

        return redirect()->route('settings.material-units')
            ->with('success', 'تم إضافة الوحدة بنجاح');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaterialUnit $materialUnit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:material_units,name,' . $materialUnit->id,
            'symbol' => 'required|string|max:10|unique:material_units,symbol,' . $materialUnit->id,
            'type' => 'required|in:volume,weight,length,area,count',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $materialUnit->update($validated);

        return redirect()->route('settings.material-units')
            ->with('success', 'تم تحديث الوحدة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialUnit $materialUnit)
    {
        try {
            $materialUnit->delete();
            return redirect()->route('settings.material-units')
                ->with('success', 'تم حذف الوحدة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('settings.material-units')
                ->with('error', 'لا يمكن حذف هذه الوحدة لأنها مستخدمة في مواد أخرى');
        }
    }
}
