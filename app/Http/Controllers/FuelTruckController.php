<?php

namespace App\Http\Controllers;

use App\Models\FuelTruck;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FuelTruckController extends Controller
{
    /**
     * Show the form for creating a new fuel truck record
     */
    public function create(Equipment $equipment)
    {
        // Check if fuel truck already exists
        if ($equipment->fuelTruck) {
            return redirect()->route('fuel-management.index')
                ->with('info', 'بيانات سيارة المحروقات موجودة بالفعل');
        }

        return view('fuel-truck.create', compact('equipment'));
    }

    /**
     * Store a newly created fuel truck record
     */
    public function store(Request $request, Equipment $equipment)
    {
        try {
            // Check if fuel truck already exists
            if ($equipment->fuelTruck) {
                return redirect()->route('fuel-management.index')
                    ->with('info', 'بيانات سيارة المحروقات موجودة بالفعل');
            }

            $validated = $request->validate([
                'fuel_type' => 'required|in:diesel,gasoline,engine_oil,hydraulic_oil,radiator_water,brake_oil,other',
                'capacity' => 'required|numeric|min:0.01|max:99999.99',
                'current_quantity' => 'required|numeric|min:0|max:99999.99',
                'notes' => 'nullable|string|max:1000',
            ]);

            $validated['equipment_id'] = $equipment->id;

            FuelTruck::create($validated);

            return redirect()->route('fuel-management.index')
                ->with('success', 'تم إضافة بيانات سيارة المحروقات بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Fuel Truck Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a fuel truck record
     */
    public function edit(Equipment $equipment)
    {
        if (!$equipment->fuelTruck) {
            return redirect()->route('fuel-truck.create', $equipment)
                ->with('info', 'لم يتم العثور على بيانات سيارة المحروقات');
        }

        return view('fuel-truck.edit', compact('equipment'));
    }

    /**
     * Update the specified fuel truck record
     */
    public function update(Request $request, Equipment $equipment)
    {
        try {
            if (!$equipment->fuelTruck) {
                return redirect()->route('fuel-truck.create', $equipment)
                    ->with('info', 'لم يتم العثور على بيانات سيارة المحروقات');
            }

            $validated = $request->validate([
                'fuel_type' => 'required|in:diesel,gasoline,engine_oil,hydraulic_oil,radiator_water,brake_oil,other',
                'capacity' => 'required|numeric|min:0.01|max:99999.99',
                'current_quantity' => 'required|numeric|min:0|max:99999.99',
                'notes' => 'nullable|string|max:1000',
            ]);

            $equipment->fuelTruck->update($validated);

            return redirect()->route('fuel-management.index')
                ->with('success', 'تم تحديث بيانات سيارة المحروقات بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Fuel Truck Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Create fuel truck data via API (for modal form)
     */
    public function storeViaModal(Request $request, Equipment $equipment)
    {
        try {
            // Check if fuel truck already exists
            if ($equipment->fuelTruck) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات سيارة المحروقات موجودة بالفعل'
                ], 409);
            }

            $validated = $request->validate([
                'fuel_type' => 'required|in:diesel,gasoline,engine_oil,hydraulic_oil,radiator_water,brake_oil,other',
                'capacity' => 'required|numeric|min:0.01|max:99999.99',
                'current_quantity' => 'required|numeric|min:0|max:99999.99',
                'notes' => 'nullable|string|max:1000',
            ]);

            $validated['equipment_id'] = $equipment->id;

            $fuelTruck = FuelTruck::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة بيانات سيارة المحروقات بنجاح',
                'fuel_truck' => $fuelTruck
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Fuel Truck Modal Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
}
