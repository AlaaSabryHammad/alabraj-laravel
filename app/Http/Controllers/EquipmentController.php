<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\EquipmentFile;
use App\Models\EquipmentDriverHistory;
use App\Models\EquipmentMovementHistory;
use App\Models\EquipmentType;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Equipment::with('driver');

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('serial_number', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Handle status filter
        if ($request->filled('status') && $request->get('status') != 'all') {
            $query->where('status', $request->get('status'));
        }

        // Handle category filter
        if ($request->filled('category') && $request->get('category') != 'all') {
            $query->where('category', $request->get('category'));
        }

        $equipment = $query->latest()->paginate(10)->withQueryString();

        // Statistics for cards
        $stats = [
            'available' => Equipment::where('status', 'available')->count(),
            'in_use' => Equipment::where('status', 'in_use')->count(),
            'maintenance' => Equipment::where('status', 'maintenance')->count(),
            'out_of_order' => Equipment::where('status', 'out_of_order')->count(),
            'total' => Equipment::count()
        ];

        // Get unique categories for filter dropdown
        $categories = Equipment::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->sort();

        return view('equipment.index', compact('equipment', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employees = Employee::select('id', 'name')->orderBy('name')->get();
        $locations = Location::select('id', 'name')->orderBy('name')->get();
        $equipmentTypes = EquipmentType::where('is_active', true)->orderBy('name')->get();

        // Check if location_id is passed in request
        $preselectedLocationId = $request->input('location_id');
        $preselectedLocation = null;

        if ($preselectedLocationId) {
            $preselectedLocation = Location::find($preselectedLocationId);
        }

        return view('equipment.create', compact('employees', 'locations', 'equipmentTypes', 'preselectedLocation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:equipment_types,id',
            'model' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'serial_number' => 'required|string|max:255|unique:equipment',
            'location_id' => 'nullable|exists:locations,id',
            'driver_id' => 'nullable|exists:employees,id',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'last_maintenance' => 'nullable|date',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:40960', // 40MB max per image
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240', // 10MB max per file
            'file_names.*' => 'nullable|string|max:255',
            'file_expiry_dates.*' => 'nullable|date',
            'file_descriptions.*' => 'nullable|string',
        ]);

        $validated['status'] = 'available';

        // Set type field based on type_id relationship
        if (!empty($validated['type_id'])) {
            $equipmentType = \App\Models\EquipmentType::find($validated['type_id']);
            $validated['type'] = $equipmentType ? $equipmentType->name : 'غير محدد';
        }

        // Debug: Log the validated data
        Log::info('Equipment creation data:', $validated);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('equipment', $imageName, 'public');
                $imagePaths[] = $imagePath;
            }
        }
        $validated['images'] = $imagePaths;

        $equipment = Equipment::create($validated);

        // Debug: Log the created equipment
        Log::info('Equipment created:', ['id' => $equipment->id, 'location_id' => $equipment->location_id, 'location' => $equipment->location]);

        // Create initial movement history record
        if ($equipment->location_id) {
            EquipmentMovementHistory::create([
                'equipment_id' => $equipment->id,
                'from_location_id' => null,
                'from_location_text' => null,
                'to_location_id' => $equipment->location_id,
                'to_location_text' => $equipment->location ? $equipment->location->name : null,
                'moved_by' => Auth::id(),
                'moved_at' => now(),
                'movement_reason' => 'تسجيل أولي للمعدة',
                'movement_type' => 'initial_assignment',
                'notes' => 'تم تسجيل المعدة في النظام'
            ]);
        }

        // Create initial driver history record if driver assigned
        if ($equipment->driver_id) {
            EquipmentDriverHistory::create([
                'equipment_id' => $equipment->id,
                'driver_id' => $equipment->driver_id,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'assignment_notes' => 'تعيين أولي عند تسجيل المعدة',
                'status' => 'active'
            ]);

            // Auto-update status to in_use when driver is assigned
            $equipment->update(['status' => 'in_use']);
            Log::info('Equipment status changed to in_use on creation', [
                'equipment_id' => $equipment->id,
                'equipment_name' => $equipment->name,
                'driver_id' => $equipment->driver_id,
                'reason' => 'Driver assigned on creation'
            ]);
        }        // Handle file uploads
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $fileNames = $request->input('file_names', []);
            $fileExpiryDates = $request->input('file_expiry_dates', []);
            $fileDescriptions = $request->input('file_descriptions', []);

            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('equipment/files', $fileName, 'public');

                    $equipment->files()->create([
                        'file_name' => $fileNames[$index] ?? $originalName,
                        'original_name' => $originalName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'expiry_date' => $fileExpiryDates[$index] ?? null,
                        'description' => $fileDescriptions[$index] ?? null,
                    ]);
                }
            }
        }

        // Check if this was created from a specific location page
        if ($request->input('from_location') && $validated['location_id']) {
            // Additional check to ensure the equipment was saved with location
            $equipment->refresh(); // Reload from database
            Log::info('Redirecting to location:', ['location_id' => $validated['location_id'], 'equipment_location_id' => $equipment->location_id]);

            return redirect()->route('locations.show', $validated['location_id'])
                ->with('success', 'تم إضافة المعدة بنجاح للموقع');
        }

        return redirect()->route('equipment.index')
            ->with('success', 'تم إضافة المعدة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        $equipment->load([
            'files',
            'driver',
            'locationDetail',
            'driverHistory.driver',
            'movementHistory.fromLocation',
            'movementHistory.toLocation',
            'fuelConsumptions.user',
            'maintenances.user'
        ]);

        $employees = Employee::select('id', 'name', 'position')->orderBy('name')->get();
        $locations = Location::select('id', 'name')->orderBy('name')->get();

        return view('equipment.show', compact('equipment', 'employees', 'locations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        $equipment->load('files');
        $employees = Employee::select('id', 'name')->orderBy('name')->get();
        $locations = Location::select('id', 'name')->orderBy('name')->get();
        $equipmentTypes = EquipmentType::where('is_active', true)->orderBy('name')->get();
        return view('equipment.edit', compact('equipment', 'employees', 'locations', 'equipmentTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        // Check if this is a location-only update
        if ($request->has('location_id') && $request->input('location_update_only') !== null) {
            $validated = $request->validate([
                'location_id' => 'nullable|exists:locations,id',
            ]);

            $equipment->update($validated);

            return response()->json(['success' => true, 'message' => 'تم تحديث موقع المعدة بنجاح']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:equipment_types,id',
            'model' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'serial_number' => 'required|string|max:255|unique:equipment,serial_number,' . $equipment->id,
            'location_id' => 'nullable|exists:locations,id',
            'driver_id' => 'nullable|exists:employees,id',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'last_maintenance' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'required|in:available,in_use,maintenance,out_of_order',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:40960', // 40MB max per image
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
        ]);

        // Handle existing images and removals
        $existingImages = $equipment->images ?? [];
        $imagePaths = $existingImages;

        // Remove selected images
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $index) {
                if (isset($imagePaths[$index])) {
                    // Delete file from storage
                    if (Storage::disk('public')->exists($imagePaths[$index])) {
                        Storage::disk('public')->delete($imagePaths[$index]);
                    }
                    unset($imagePaths[$index]);
                }
            }
            $imagePaths = array_values($imagePaths); // Re-index array
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('equipment', $imageName, 'public');
                $imagePaths[] = $imagePath;
            }
        }

        $validated['images'] = $imagePaths;

        // Set type field based on type_id relationship
        if (!empty($validated['type_id'])) {
            $equipmentType = \App\Models\EquipmentType::find($validated['type_id']);
            $validated['type'] = $equipmentType ? $equipmentType->name : 'غير محدد';
        }

        // Handle file removals
        if ($request->has('remove_files')) {
            foreach ($request->remove_files as $fileId) {
                $file = EquipmentFile::find($fileId);
                if ($file && $file->equipment_id == $equipment->id) {
                    // Delete file from storage
                    if (Storage::disk('public')->exists($file->file_path)) {
                        Storage::disk('public')->delete($file->file_path);
                    }
                    $file->delete();
                }
            }
        }

        // Handle new file uploads
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $fileNames = $request->input('file_names', []);
            $expiryDates = $request->input('expiry_dates', []);
            $fileDescriptions = $request->input('file_descriptions', []);

            foreach ($files as $index => $file) {
                // Validate file
                $validTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'];
                $extension = strtolower($file->getClientOriginalExtension());

                if (!in_array($extension, $validTypes)) {
                    continue; // Skip invalid files
                }

                if ($file->getSize() > 10 * 1024 * 1024) { // 10MB limit
                    continue; // Skip files that are too large
                }

                // Store file
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                $filePath = $file->storeAs('equipment/files', $fileName, 'public');

                // Create equipment file record
                EquipmentFile::create([
                    'equipment_id' => $equipment->id,
                    'file_name' => $fileNames[$index] ?? 'ملف المعدة',
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'expiry_date' => !empty($expiryDates[$index]) ? $expiryDates[$index] : null,
                    'description' => $fileDescriptions[$index] ?? null,
                ]);
            }
        }

        // Store old driver_id for comparison
        $oldDriverId = $equipment->driver_id;

        $equipment->update($validated);

        // Auto-update status based on driver assignment
        $this->updateEquipmentStatusBasedOnDriver($equipment, $oldDriverId, $validated['driver_id'] ?? null);

        return redirect()->route('equipment.index')
            ->with('success', 'تم تحديث بيانات المعدة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        // Delete associated images
        if ($equipment->images && count($equipment->images) > 0) {
            foreach ($equipment->images as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        // Delete associated files
        $files = $equipment->files;
        foreach ($files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $equipment->delete();

        return redirect()->route('equipment.index')
            ->with('success', 'تم حذف المعدة بنجاح');
    }

    /**
     * Download equipment file
     */
    public function downloadFile(EquipmentFile $file)
    {
        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'الملف غير موجود');
        }

        $filePath = storage_path('app/public/' . $file->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'الملف غير موجود');
        }

        return response()->download($filePath, $file->original_name);
    }

    /**
     * Get available equipment for location assignment
     */
    public function getAvailableForLocation(Request $request)
    {
        $currentLocationId = $request->get('current_location_id');

        $query = Equipment::with(['locationDetail'])
            ->select('id', 'name', 'type', 'serial_number', 'status', 'location', 'location_id');

        // Exclude equipment that are already in the current location
        if ($currentLocationId) {
            $query->where(function ($q) use ($currentLocationId) {
                $q->where('location_id', '!=', $currentLocationId)
                    ->orWhereNull('location_id');
            });
        }

        $equipment = $query->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => $item->type,
                    'serial_number' => $item->serial_number,
                    'status' => $item->status,
                    'location' => $item->locationDetail ? $item->locationDetail->name : $item->location,
                    'location_id' => $item->location_id
                ];
            });

        return response()->json($equipment);
    }

    /**
     * Generate equipment report for printing
     */
    public function generateReport(Equipment $equipment)
    {
        $equipment->load(['files', 'driver', 'locationDetail', 'driverHistory.driver', 'movementHistory.fromLocation', 'movementHistory.toLocation']);

        return view('equipment.report', compact('equipment'));
    }

    /**
     * Get all equipment data for printing
     */
    public function getAllEquipmentData()
    {
        $equipment = Equipment::with(['driver', 'locationDetail'])
            ->select('id', 'name', 'type', 'serial_number', 'status', 'location_id', 'purchase_date', 'driver_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => $item->type,
                    'serial_number' => $item->serial_number,
                    'location' => $item->locationDetail ? $item->locationDetail->name : 'غير محدد',
                    'driver' => $item->driver ? $item->driver->name : 'غير محدد',
                    'status' => $item->status,
                    'status_text' => $this->getStatusText($item->status),
                    'purchase_date' => $item->purchase_date ? (string)$item->purchase_date : 'غير محدد'
                ];
            });

        // احصائيات المعدات
        $stats = [
            'total' => $equipment->count(),
            'available' => $equipment->where('status', 'available')->count(),
            'in_use' => $equipment->where('status', 'in_use')->count(),
            'maintenance' => $equipment->where('status', 'maintenance')->count(),
            'out_of_order' => $equipment->where('status', 'out_of_order')->count()
        ];

        return response()->json([
            'equipment' => $equipment,
            'stats' => $stats
        ]);
    }

    /**
     * Get status text in Arabic
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'available' => 'متاحة',
            'in_use' => 'قيد الاستخدام',
            'maintenance' => 'في الصيانة',
            'out_of_order' => 'خارج الخدمة'
        ];

        return $statusTexts[$status] ?? $status;
    }

    /**
     * Update equipment status based on driver assignment
     */
    private function updateEquipmentStatusBasedOnDriver(Equipment $equipment, $oldDriverId, $newDriverId)
    {
        // If driver was assigned (from null or different driver)
        if (!$oldDriverId && $newDriverId) {
            // Driver assigned for the first time
            if ($equipment->status === 'available') {
                $equipment->update(['status' => 'in_use']);
                Log::info('Equipment status changed to in_use', [
                    'equipment_id' => $equipment->id,
                    'equipment_name' => $equipment->name,
                    'driver_id' => $newDriverId,
                    'reason' => 'Driver assigned'
                ]);
            }
        }
        // If driver was removed
        elseif ($oldDriverId && !$newDriverId) {
            // Driver removed
            if ($equipment->status === 'in_use') {
                $equipment->update(['status' => 'available']);
                Log::info('Equipment status changed to available', [
                    'equipment_id' => $equipment->id,
                    'equipment_name' => $equipment->name,
                    'old_driver_id' => $oldDriverId,
                    'reason' => 'Driver removed'
                ]);
            }
        }
        // If driver was changed (from one driver to another)
        elseif ($oldDriverId && $newDriverId && $oldDriverId !== $newDriverId) {
            // Driver changed - keep status as in_use if it was already in_use
            if ($equipment->status === 'available') {
                $equipment->update(['status' => 'in_use']);
                Log::info('Equipment status changed to in_use', [
                    'equipment_id' => $equipment->id,
                    'equipment_name' => $equipment->name,
                    'old_driver_id' => $oldDriverId,
                    'new_driver_id' => $newDriverId,
                    'reason' => 'Driver changed'
                ]);
            }
        }
    }

    /**
     * Get equipment details for modal display
     */
    public function getEquipmentDetails(Equipment $equipment)
    {
        $equipment->load(['locationDetail']);

        return response()->json([
            'id' => $equipment->id,
            'name' => $equipment->name,
            'type' => $equipment->type,
            'serial_number' => $equipment->serial_number,
            'status' => $equipment->status,
            'current_location' => $equipment->locationDetail ? $equipment->locationDetail->name : 'غير محدد'
        ]);
    }

    public function updateLocation(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'location_id' => 'nullable|exists:locations,id',
        ]);

        $equipment->update($validated);

        return response()->json(['success' => true, 'message' => 'تم تحديث موقع المعدة بنجاح']);
    }
}
