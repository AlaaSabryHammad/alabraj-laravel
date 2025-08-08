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
        $trucks = InternalTruck::with('user', 'equipment', 'driver', 'location')->paginate(10);

        // جلب المعدات من نوع شاحنات التي لا تملك شاحنة مرتبطة
        $unlinkedTruckEquipments = \App\Models\Equipment::where(function ($query) {
            $query->where('category', 'شاحنات')
                ->orWhere('category', 'شاحنة');
        })
            ->whereNull('truck_id')
            ->with('driver')
            ->get();

        return view('internal-trucks.index', compact('trucks', 'unlinkedTruckEquipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = \App\Models\Employee::all();
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
            'driver_id' => 'nullable|exists:employees,id',
            'location_id' => 'nullable|exists:locations,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:40960', // 40MB max
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('internal-trucks', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        // If driver is assigned, set status to in_use
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
        $employees = \App\Models\Employee::all();
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
            'driver_id' => 'nullable|exists:employees,id',
            'location_id' => 'nullable|exists:locations,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:40960', // 40MB max
            'removed_images' => 'nullable|array',
        ]);

        // Handle removed images
        $existingImages = $internalTruck->images ?? [];
        if ($request->has('removed_images')) {
            $removedImages = $request->input('removed_images');
            // Remove deleted images from storage
            foreach ($removedImages as $imagePath) {
                \Storage::disk('public')->delete($imagePath);
            }
            // Filter out removed images from existing images
            $existingImages = array_filter($existingImages, function ($imagePath) use ($removedImages) {
                return !in_array($imagePath, $removedImages);
            });
            $existingImages = array_values($existingImages); // Re-index array
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('internal-trucks', 'public');
                $imagePaths[] = $path;
            }
            // Merge with existing images
            $validated['images'] = array_merge($existingImages, $imagePaths);
        } else {
            // Keep existing images if no new images uploaded
            $validated['images'] = $existingImages;
        }

        // If driver is assigned, set status to in_use
        if ($validated['driver_id']) {
            $validated['status'] = 'in_use';
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

    /**
     * Convert an equipment to internal truck
     */
    public function linkEquipment(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'year' => 'nullable|integer|min:1980|max:' . (date('Y') + 1),
            'fuel_type' => 'required|in:gasoline,diesel,hybrid',
        ]);

        $equipment = \App\Models\Equipment::findOrFail($validated['equipment_id']);

        // تحقق من أن المعدة ليست مرتبطة بشاحنة
        if ($equipment->truck_id) {
            return redirect()->back()->with('error', 'هذه المعدة مرتبطة بشاحنة بالفعل');
        }

        // استخراج معلومات من اسم المعدة
        $equipmentName = $equipment->name;
        $parts = explode(' - ', $equipmentName);

        $brand = 'غير محدد';
        $model = 'غير محدد';
        $plateNumber = 'EQ-' . $equipment->id;

        if (count($parts) >= 2) {
            $brandModel = trim($parts[0]);
            $plateNumber = trim($parts[1]);

            $brandModelParts = explode(' ', $brandModel, 2);
            $brand = $brandModelParts[0];
            $model = isset($brandModelParts[1]) ? $brandModelParts[1] : $brand;
        }

        // إنشاء شاحنة داخلية
        $internalTruck = InternalTruck::create([
            'plate_number' => $plateNumber,
            'brand' => $brand,
            'model' => $model,
            'year' => $validated['year'] ?? date('Y'),
            'load_capacity' => 5.0, // قيمة افتراضية
            'fuel_type' => $validated['fuel_type'],
            'status' => $equipment->status == 'in_use' ? 'in_use' : 'available',
            'purchase_date' => $equipment->purchase_date ?? now(),
            'purchase_price' => $equipment->purchase_price ?? 0,
            'description' => $equipment->description ?? 'شاحنة محولة من المعدات',
            'driver_id' => $equipment->driver_id,
            'user_id' => $equipment->user_id ?? \Illuminate\Support\Facades\Auth::id(),
        ]);

        // ربط المعدة بالشاحنة الجديدة
        $equipment->update(['truck_id' => $internalTruck->id]);

        return redirect()->route('internal-trucks.index')
            ->with('success', 'تم تحويل المعدة إلى شاحنة داخلية بنجاح');
    }
}
