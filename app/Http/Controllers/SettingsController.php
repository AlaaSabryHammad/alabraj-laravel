<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\EquipmentType;
use App\Models\LocationType;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Exception;

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
            'x_requested_with' => $request->header('X-Requested-With'),
            'all_headers' => $request->headers->all(),
            'user_id' => Auth::id(),
            'data' => $request->all()
        ]);

        // Force JSON response for AJAX requests
        $isAjax = $request->ajax() || 
                  $request->wantsJson() || 
                  $request->expectsJson() ||
                  $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                  $request->header('Content-Type') === 'application/json';

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

            Log::info('Equipment type created successfully', [
                'id' => $equipmentType->id,
                'is_ajax' => $isAjax,
                'will_return_json' => $isAjax ? 'yes' : 'no'
            ]);

            if ($isAjax) {
                Log::info('Returning JSON response for equipment type creation');
                return response()->json([
                    'success' => true,
                    'message' => 'تم إضافة نوع المعدة بنجاح',
                    'data' => $equipmentType
                ]);
            }

            Log::info('Returning redirect response for equipment type creation');
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
            $isAjax = $request->ajax() || 
                      $request->wantsJson() || 
                      $request->expectsJson() ||
                      $request->header('X-Requested-With') === 'XMLHttpRequest';
            if ($isAjax) {
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

        $isAjax = $request->ajax() || 
                  $request->wantsJson() || 
                  $request->expectsJson() ||
                  $request->header('X-Requested-With') === 'XMLHttpRequest';
        if ($isAjax) {
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
            $isAjax = request()->ajax() || 
                      request()->wantsJson() || 
                      request()->expectsJson() ||
                      request()->header('X-Requested-With') === 'XMLHttpRequest';
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف هذا النوع لأنه مرتبط بمعدات موجودة'
                ], 422);
            }
            return redirect()->back()->with('error', 'لا يمكن حذف هذا النوع لأنه مرتبط بمعدات موجودة');
        }

        $equipmentType->delete();

        $isAjax = request()->ajax() || 
                  request()->wantsJson() || 
                  request()->expectsJson() ||
                  request()->header('X-Requested-With') === 'XMLHttpRequest';
        if ($isAjax) {
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

        $isAjax = $request->ajax() || 
                  $request->wantsJson() || 
                  $request->expectsJson() ||
                  $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                  $request->header('Content-Type') === 'application/json';

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
            $isAjax = $request->ajax() || 
                      $request->wantsJson() || 
                      $request->expectsJson() ||
                      $request->header('X-Requested-With') === 'XMLHttpRequest';
            if ($isAjax) {
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

        $isAjax = $request->ajax() || 
                  $request->wantsJson() || 
                  $request->expectsJson() ||
                  $request->header('X-Requested-With') === 'XMLHttpRequest';
        if ($isAjax) {
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
            $isAjax = request()->ajax() || 
                      request()->wantsJson() || 
                      request()->expectsJson() ||
                      request()->header('X-Requested-With') === 'XMLHttpRequest';
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف هذا النوع لأنه مرتبط بمواقع موجودة'
                ], 422);
            }
            return redirect()->back()->with('error', 'لا يمكن حذف هذا النوع لأنه مرتبط بمواقع موجودة');
        }

        $locationType->delete();

        $isAjax = request()->ajax() || 
                  request()->wantsJson() || 
                  request()->expectsJson() ||
                  request()->header('X-Requested-With') === 'XMLHttpRequest';
        if ($isAjax) {
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
        
        // Get all materials for statistics
        $allMaterials = \App\Models\Material::all();

        // Preserve filters in pagination links
        $materials->appends($request->only(['search', 'unit']));

        return view('settings.materials', compact('materials', 'allMaterials'));
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

    // Roles and Permissions Management
    public function rolesAndPermissions()
    {
        $roles = Role::with('permissions')->withCount('users')->orderBy('name')->get();
        $permissions = Permission::getByCategory();
        $users = User::with('roles')->orderBy('name')->get();

        return view('settings.roles-permissions', compact('roles', 'permissions', 'users'));
    }

    public function getRole(Role $role)
    {
        return response()->json($role);
    }

    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            // ربط الصلاحيات بالدور باستخدام جدول الربط
            if ($request->has('permissions') && is_array($request->permissions)) {
                foreach ($request->permissions as $permissionName) {
                    $permission = \App\Models\Permission::where('name', $permissionName)->first();
                    if ($permission) {
                        \DB::table('role_permissions')->insert([
                            'role_id' => $role->id,
                            'permission_id' => $permission->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الدور بنجاح',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating role', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الدور'
            ], 500);
        }
    }

    public function showRole(Role $role)
    {
        try {
            // جلب الصلاحيات من جدول الربط
            $permissions = $role->permissions()->pluck('name')->toArray();

            return response()->json([
                'success' => true,
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
                'description' => $role->description,
                'permissions' => $permissions,
                'is_active' => $role->is_active,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching role data', [
                'role_id' => $role->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الدور'
            ], 500);
        }
    }

    public function updateRole(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            // حذف الصلاحيات القديمة
            \DB::table('role_permissions')->where('role_id', $role->id)->delete();

            // ربط الصلاحيات الجديدة
            if ($request->has('permissions') && is_array($request->permissions)) {
                foreach ($request->permissions as $permissionName) {
                    $permission = \App\Models\Permission::where('name', $permissionName)->first();
                    if ($permission) {
                        \DB::table('role_permissions')->insert([
                            'role_id' => $role->id,
                            'permission_id' => $permission->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الدور بنجاح',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating role', [
                'error' => $e->getMessage(),
                'role_id' => $role->id,
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الدور'
            ], 500);
        }
    }

    public function destroyRole(Role $role)
    {
        try {
            // Check if role has users
            if ($role->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف الدور لأنه مرتبط بمستخدمين'
                ], 422);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الدور بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting role', [
                'error' => $e->getMessage(),
                'role_id' => $role->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الدور'
            ], 500);
        }
    }

    public function storePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions',
            'display_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'category' => $request->category,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الصلاحية بنجاح',
                'permission' => $permission
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating permission', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الصلاحية'
            ], 500);
        }
    }

    public function updateUserRoles(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'roles' => 'required|array',
            'roles.*' => 'integer|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->roles()->sync($request->roles);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث أدوار المستخدم بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating user roles', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث أدوار المستخدم'
            ], 500);
        }
    }
}
