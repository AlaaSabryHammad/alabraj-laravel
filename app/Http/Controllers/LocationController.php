<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Employee;
use App\Models\LocationType;
use App\Models\Project;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::with('locationType')->latest()->paginate(10);

        // Calculate statistics for all locations (not just paginated ones)
        $stats = [
            'active' => Location::where('status', 'active')->count(),
            'site' => Location::where('type', 'site')->count(),
            'warehouse' => Location::where('type', 'warehouse')->count(),
            'office' => Location::where('type', 'office')->count(),
        ];

        return view('locations.index', compact('locations', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        $locationTypes = LocationType::where('is_active', true)->get();
        $projects = Project::where('status', 'active')->get();
        return view('locations.create', compact('employees', 'locationTypes', 'projects'));
    }

    /**
     * Search employees for AJAX requests
     */
    public function searchEmployees(Request $request)
    {
        $search = $request->get('search', '');

        $employees = Employee::where('status', 'active')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('position', 'LIKE', "%{$search}%")
                    ->orWhere('department', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'position', 'department', 'phone']);

        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // First validate basic fields
        $request->validate([
            'name' => 'required|string|max:255',
            'location_type_id' => 'required|exists:location_types,id',
        ]);

        // Check if location type requires project selection
        $locationType = LocationType::find($request->location_type_id);
        $isProjectRequired = $locationType && (
            stripos($locationType->name, 'موقع') !== false ||
            stripos($locationType->name, 'site') !== false
        );

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_type_id' => 'required|exists:location_types,id',
            'project_id' => $isProjectRequired ? 'required|exists:projects,id' : 'nullable|exists:projects,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'coordinates' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:employees,id',
            'manager_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'area_size' => 'nullable|numeric|min:0',
        ]);

        $validated['status'] = 'active';

        // If manager_id is provided, get the employee info
        if ($validated['manager_id']) {
            $employee = Employee::find($validated['manager_id']);
            $validated['manager_name'] = $employee->name;
            $validated['contact_phone'] = $employee->phone;
        }

        Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success', 'تم إضافة الموقع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        $location->load(['equipment', 'locationType']);
        return view('locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        $employees = Employee::where('status', 'active')->get();
        $locationTypes = LocationType::where('is_active', true)->get();
        return view('locations.edit', compact('location', 'employees', 'locationTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_type_id' => 'required|exists:location_types,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'coordinates' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,under_construction',
            'manager_id' => 'nullable|exists:employees,id',
            'manager_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'area_size' => 'nullable|numeric|min:0',
        ]);

        // If manager_id is provided, get the employee info
        if ($validated['manager_id']) {
            $employee = Employee::find($validated['manager_id']);
            $validated['manager_name'] = $employee->name;
            $validated['contact_phone'] = $employee->phone;
        }

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'تم تحديث بيانات الموقع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'تم حذف الموقع بنجاح');
    }
}
