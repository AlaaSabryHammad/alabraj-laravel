<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transport;
use App\Models\Equipment;
use App\Models\ExternalTruck;
use App\Models\Location;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transport::with(['user', 'loadingLocation', 'unloadingLocation', 'material']);

        // Apply filters
        if ($request->filled('loading_location')) {
            $query->where('loading_location_id', $request->loading_location);
        }

        if ($request->filled('unloading_location')) {
            $query->where('unloading_location_id', $request->unloading_location);
        }

        if ($request->filled('arrival_date')) {
            $query->whereDate('arrival_time', $request->arrival_date);
        }

        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', 'like', '%' . $request->vehicle_type . '%');
        }

        $transports = $query->latest()->paginate(10)->withQueryString();

        return view('transport.index', compact('transports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get internal vehicles (trucks from equipment)
        $internalVehicles = Equipment::with(['driver', 'locationDetail', 'equipmentType'])
            ->whereHas('equipmentType', function($query) {
                $query->where('name', 'LIKE', '%شاحن%')
                      ->orWhere('name', 'LIKE', '%truck%');
            })
            ->where('status', 'متاح')
            ->orderBy('name')
            ->get();

        // Get external trucks
        $externalTrucks = ExternalTruck::with('supplier')
            ->where('status', 'active')
            ->orderBy('plate_number')
            ->get();

        // Get locations
        $locations = Location::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('transport.create', compact('internalVehicles', 'externalTrucks', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'transport_type' => 'required|in:internal,external',
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|numeric|min:0.01',
            'loading_location_id' => 'required|exists:locations,id',
            'unloading_location_id' => 'required|exists:locations,id',
            'arrival_time' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ];

        // Add vehicle validation based on transport type
        if ($request->transport_type === 'internal') {
            $rules['internal_vehicle_id'] = 'required|exists:equipment,id';
        } else {
            $rules['external_truck_id'] = 'required|exists:external_trucks,id';
        }

        $validated = $request->validate($rules);

        // Add the authenticated user ID
        $validated['user_id'] = Auth::id();

        // Get vehicle details based on transport type
        if ($validated['transport_type'] === 'internal') {
            $vehicle = Equipment::with(['driver', 'locationDetail'])->find($validated['internal_vehicle_id']);
            $validated['vehicle_type'] = $vehicle->type;
            $validated['vehicle_number'] = $vehicle->name;
            $validated['driver_name'] = $vehicle->driver ? $vehicle->driver->name : 'غير محدد';
        } else {
            $truck = ExternalTruck::find($validated['external_truck_id']);
            $validated['vehicle_type'] = 'شاحنة خارجية';
            $validated['vehicle_number'] = $truck->plate_number;
            $validated['driver_name'] = $truck->driver_name;
        }

        // Get material details for cargo description
        $material = \App\Models\Material::find($validated['material_id']);
        $validated['cargo_description'] = $material->name . ' - ' . $validated['quantity'] . ' ' . $material->unit;

        // Get location names for destination description
        $loadingLocation = Location::find($validated['loading_location_id']);
        $unloadingLocation = Location::find($validated['unloading_location_id']);

        $validated['destination'] = "من {$loadingLocation->name} إلى {$unloadingLocation->name}";

        Transport::create($validated);

        return redirect()->route('transport.index')
            ->with('success', 'تم إضافة الرحلة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transport $transport)
    {
        return view('transport.show', compact('transport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transport $transport)
    {
        return view('transport.edit', compact('transport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transport $transport)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:255',
            'driver_name' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_time' => 'nullable|date',
            'arrival_time' => 'nullable|date',
            'cargo_description' => 'nullable|string',
            'fuel_cost' => 'nullable|numeric|min:0'
        ]);

        $transport->update($validated);

        return redirect()->route('transport.index')
            ->with('success', 'تم تحديث بيانات الرحلة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transport $transport)
    {
        $transport->delete();

        return redirect()->route('transport.index')
            ->with('success', 'تم حذف الرحلة بنجاح');
    }

    /**
     * Get transport details for modal view.
     */
    public function details(Transport $transport)
    {
        try {
            $transport->load(['user', 'loadingLocation', 'unloadingLocation', 'material']);

            return response()->json([
                'success' => true,
                'transport' => [
                    'id' => $transport->id,
                    'vehicle_type' => $transport->vehicle_type,
                    'vehicle_number' => $transport->vehicle_number,
                    'driver_name' => $transport->driver_name,
                    'loading_location' => $transport->loadingLocation ? $transport->loadingLocation->name : 'غير محدد',
                    'unloading_location' => $transport->unloadingLocation ? $transport->unloadingLocation->name : 'غير محدد',
                    'departure_time' => $transport->departure_time ? $transport->departure_time->format('Y-m-d H:i') : null,
                    'arrival_time' => $transport->arrival_time ? $transport->arrival_time->format('Y-m-d H:i') : null,
                    'cargo_description' => $transport->cargo_description,
                    'quantity' => $transport->quantity,
                    'material' => $transport->material ? $transport->material->name : null,
                    'fuel_cost' => $transport->fuel_cost ? number_format((float)$transport->fuel_cost, 2) : null,
                    'notes' => $transport->notes,
                    'created_by' => $transport->user ? $transport->user->name : 'غير محدد',
                    'created_at' => $transport->created_at->format('Y-m-d H:i'),
                    'updated_at' => $transport->updated_at->format('Y-m-d H:i'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحميل البيانات: ' . $e->getMessage()
            ]);
        }
    }
}
