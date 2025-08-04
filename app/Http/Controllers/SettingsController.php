<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\EquipmentType;
use App\Models\LocationType;

class SettingsController extends Controller
{
    // Main settings page
    public function index()
    {
        return view('settings.index');
    }

    // Equipment Types Management
    public function equipmentTypes()
    {
        $equipmentTypes = EquipmentType::withCount('equipment')->orderBy('name')->get();
        return view('settings.equipment-types', compact('equipmentTypes'));
    }

    public function storeEquipmentType(Request $request)
    {
        // Debug information
        Log::info('storeEquipmentType called', [
            'method' => $request->method(),
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'expects_json' => $request->expectsJson(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'user_id' => Auth::id(),
            'data' => $request->all()
        ]);

        // Force JSON response for AJAX requests
        $isAjax = $request->ajax() || $request->wantsJson() || $request->header('Content-Type') === 'application/json';

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:equipment_types',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            Log::info('Validation failed', ['errors' => $validator->errors()]);
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $equipmentType = EquipmentType::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            Log::info('Equipment type created successfully', ['id' => $equipmentType->id]);

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إضافة نوع المعدة بنجاح',
                    'data' => $equipmentType
                ]);
            }

            return redirect()->route('settings.equipment-types')
                ->with('success', 'تم إضافة نوع المعدة بنجاح');

        } catch (\Exception $e) {
            Log::error('Error creating equipment type', ['error' => $e->getMessage()]);
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إضافة نوع المعدة: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة نوع المعدة')
                ->withInput();
        }
    }

    public function updateEquipmentType(Request $request, EquipmentType $equipmentType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:equipment_types,name,' . $equipmentType->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $equipmentType->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', false)
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث نوع المعدة بنجاح'
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث نوع المعدة بنجاح');
    }

    public function destroyEquipmentType(EquipmentType $equipmentType)
    {
        // Check if equipment type is used
        if ($equipmentType->equipment()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف هذا النوع لأنه مرتبط بمعدات موجودة'
                ], 422);
            }
            return redirect()->back()->with('error', 'لا يمكن حذف هذا النوع لأنه مرتبط بمعدات موجودة');
        }

        $equipmentType->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف نوع المعدة بنجاح'
            ]);
        }

        return redirect()->back()->with('success', 'تم حذف نوع المعدة بنجاح');
    }

    // Location Types Management
    public function locationTypes()
    {
        $locationTypes = LocationType::withCount('locations')->orderBy('name')->get();
        return view('settings.location-types', compact('locationTypes'));
    }

    public function storeLocationType(Request $request)
    {
        Log::info('storeLocationType called', [
            'method' => $request->method(),
            'is_ajax' => $request->ajax(),
            'data' => $request->all()
        ]);

        $isAjax = $request->ajax() || $request->wantsJson() || $request->header('Content-Type') === 'application/json';

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:location_types',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            Log::info('Location type validation failed', ['errors' => $validator->errors()]);
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $locationType = LocationType::create([
                'name' => $request->name,
                'description' => $request->description,
                'color' => $request->color ?? '#3B82F6',
                'icon' => $request->icon ?? 'ri-map-pin-line',
                'is_active' => $request->boolean('is_active', true)
            ]);

            Log::info('Location type created successfully', ['location_type' => $locationType]);

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إضافة نوع الموقع بنجاح',
                    'location_type' => $locationType
                ]);
            }

            return redirect()->back()->with('success', 'تم إضافة نوع الموقع بنجاح');
        } catch (\Exception $e) {
            Log::error('Error creating location type', ['error' => $e->getMessage()]);

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إضافة نوع الموقع'
                ], 500);
            }

            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة نوع الموقع');
        }
    }

    public function updateLocationType(Request $request, LocationType $locationType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:location_types,name,' . $locationType->id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $locationType->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
            'icon' => $request->icon ?? 'ri-map-pin-line',
            'is_active' => $request->boolean('is_active', false)
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث نوع الموقع بنجاح'
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث نوع الموقع بنجاح');
    }

    public function destroyLocationType(LocationType $locationType)
    {
        // Check if location type is used
        if ($locationType->locations()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف هذا النوع لأنه مرتبط بمواقع موجودة'
                ], 422);
            }
            return redirect()->back()->with('error', 'لا يمكن حذف هذا النوع لأنه مرتبط بمواقع موجودة');
        }

        $locationType->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف نوع الموقع بنجاح'
            ]);
        }

        return redirect()->back()->with('success', 'تم حذف نوع الموقع بنجاح');
    }

    // AJAX Content Methods
    public function equipmentTypesContent()
    {
        $equipmentTypes = EquipmentType::withCount('equipment')->orderBy('name')->get();
        return view('settings.partials.equipment-types-content', compact('equipmentTypes'));
    }

    public function locationTypesContent()
    {
        $locationTypes = LocationType::withCount('locations')->orderBy('name')->get();
        return view('settings.partials.location-types-content', compact('locationTypes'));
    }

    // Materials Management
    public function materials(Request $request)
    {
        $query = \App\Models\Material::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Handle unit filter
        if ($request->filled('unit')) {
            $query->where('unit_of_measure', $request->get('unit'));
        }

        $materials = $query->latest()->paginate(10);

        // Preserve filters in pagination links
        $materials->appends($request->only(['search', 'unit']));

        return view('settings.materials', compact('materials'));
    }

    public function materialsContent(Request $request)
    {
        $query = \App\Models\Material::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Handle unit filter
        if ($request->filled('unit')) {
            $query->where('unit_of_measure', $request->get('unit'));
        }

        $materials = $query->latest()->paginate(10);

        // Preserve filters in pagination links
        $materials->appends($request->only(['search', 'unit']));

        return view('settings.partials.materials-content', compact('materials'));
    }
}
