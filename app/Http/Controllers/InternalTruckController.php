<?php

namespace App\Http\Controllers;

use App\Models\InternalTruck;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalTruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trucks = InternalTruck::with('user', 'equipment')->paginate(10);
        return view('internal-trucks.index', compact('trucks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::where('role', 'employee')->get();
        $locations = Location::all();

        return view('internal-trucks.create', compact('employees', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:internal_trucks,plate_number',
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1980|max:' . (date('Y') + 1),
            
            'load_capacity' => 'nullable|numeric|min:0',
            'engine_number' => 'nullable|string|max:255',
            'chassis_number' => 'nullable|string|max:255',
            'fuel_type' => 'nullable|string|in:gasoline,diesel,hybrid',
            'status' => 'required|in:available,in_use,maintenance,out_of_service',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'last_maintenance' => 'nullable|date',
            'license_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'description' => 'nullable|string',
            'driver_id' => 'nullable|exists:users,id',
            'location_id' => 'nullable|exists:locations,id',
        ]);

        // Convert status from English to Arabic
        $statusMap = [
            'available' => 'متاح',
            'in_use' => 'قيد الاستخدام',
            'maintenance' => 'تحت الصيانة',
            'out_of_service' => 'غير متاح'
        ];

        $validated['status'] = $statusMap[$validated['status']];

        // Convert fuel_type from English to Arabic
        $fuelTypeMap = [
            'gasoline' => 'بنزين',
            'diesel' => 'ديزل',
            'hybrid' => 'هجين'
        ];

        if (isset($validated['fuel_type'])) {
            $validated['fuel_type'] = $fuelTypeMap[$validated['fuel_type']];
        }

        // If driver is assigned, set status to in_use
        if ($validated['driver_id']) {
            $validated['status'] = 'قيد الاستخدام';
        }
        if ($validated['driver_id']) {
            $validated['status'] = 'in_use';
        }

        $validated['user_id'] = Auth::id();

        $internalTruck = InternalTruck::create($validated);

        return redirect()->route('internal-trucks.index')
            ->with('success', 'تم إضافة الشاحنة الداخلية بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(InternalTruck $internalTruck)
    {
        $internalTruck->load('user', 'driver', 'location');
        return view('internal-trucks.show', compact('internalTruck'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InternalTruck $internalTruck)
    {
        $employees = User::where('role', 'employee')->get();
        $locations = Location::all();

        return view('internal-trucks.edit', compact('internalTruck', 'employees', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InternalTruck $internalTruck)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:internal_trucks,plate_number,' . $internalTruck->id,
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1980|max:' . (date('Y') + 1),
            
            'load_capacity' => 'nullable|numeric|min:0',
            'engine_number' => 'nullable|string|max:255',
            'chassis_number' => 'nullable|string|max:255',
            'fuel_type' => 'nullable|string|in:gasoline,diesel,hybrid',
            'status' => 'required|in:available,in_use,maintenance,out_of_service',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'last_maintenance' => 'nullable|date',
            'license_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'description' => 'nullable|string',
            'driver_id' => 'nullable|exists:users,id',
            'location_id' => 'nullable|exists:locations,id',
        ]);

        // Convert status from English to Arabic
        $statusMap = [
            'available' => 'متاح',
            'in_use' => 'قيد الاستخدام',
            'maintenance' => 'تحت الصيانة',
            'out_of_service' => 'غير متاح'
        ];

        $validated['status'] = $statusMap[$validated['status']];

        // Convert fuel_type from English to Arabic
        $fuelTypeMap = [
            'gasoline' => 'بنزين',
            'diesel' => 'ديزل',
            'hybrid' => 'هجين'
        ];

        if (isset($validated['fuel_type'])) {
            $validated['fuel_type'] = $fuelTypeMap[$validated['fuel_type']];
        }

        // If driver is assigned, set status to in_use
        if ($validated['driver_id']) {
            $validated['status'] = 'قيد الاستخدام';
        }

        $internalTruck->update($validated);

        return redirect()->route('internal-trucks.index')
            ->with('success', 'تم تحديث بيانات الشاحنة الداخلية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InternalTruck $internalTruck)
    {
        $internalTruck->delete();

        return redirect()->route('internal-trucks.index')
            ->with('success', 'تم حذف الشاحنة بنجاح');
    }
}
