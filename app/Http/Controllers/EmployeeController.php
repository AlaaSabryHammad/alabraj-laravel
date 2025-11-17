<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeBalance;
use App\Models\Attendance;
use App\Models\Location;
use App\Models\User;
use App\Models\ManagerAssignment;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\EmailService;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'location']);

        // Site manager filter: show only employees in same location
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->employee) {
            $currentEmployee = $currentUser->employee;

            // Check if current user is a site manager (includes all variants)
            $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
            if (in_array($currentEmployee->role, $siteManagerRoles)) {
                // Filter to show only employees in the same location
                if ($currentEmployee->location_id) {
                    $query->where('location_id', $currentEmployee->location_id);
                }
                // Exclude the site manager himself from the list
                $query->where('id', '!=', $currentEmployee->id);
            }

            // Engineer filter: show only employees in locations under managed projects
            $engineerVariants = Employee::variantsForArabic('مهندس');
            if (in_array($currentEmployee->role, $engineerVariants)) {
                // Get projects managed by this engineer
                $managedProjectIds = \App\Models\Project::where('project_manager_id', $currentEmployee->id)
                    ->pluck('id');

                if ($managedProjectIds->isNotEmpty()) {
                    // Get all locations under these projects
                    $projectLocationIds = \App\Models\Location::whereIn('project_id', $managedProjectIds)
                        ->pluck('id');

                    if ($projectLocationIds->isNotEmpty()) {
                        // Show employees in these locations
                        $query->whereIn('location_id', $projectLocationIds);
                    } else {
                        // If no locations in managed projects, show empty result
                        $query->where('id', 0);
                    }
                } else {
                    // If engineer has no projects, show empty result
                    $query->where('id', 0);
                }
            }

            // Manager filter: show all employees except managers and general managers
            $managerVariants = Employee::variantsForArabic('مدير');
            if (in_array($currentEmployee->role, $managerVariants)) {
                // Exclude managers and general managers
                $excludedRoles = array_merge(
                    Employee::variantsForArabic('مدير'),
                    Employee::variantsForArabic('مدير عام')
                );
                $query->whereNotIn('role', $excludedRoles);
            }

            // Project Manager filter: show all employees except managers and general managers
            $projectManagerVariants = Employee::variantsForArabic('مدير مشاريع');
            if (in_array($currentEmployee->role, $projectManagerVariants)) {
                // Exclude managers and general managers
                $excludedRoles = array_merge(
                    Employee::variantsForArabic('مدير'),
                    Employee::variantsForArabic('مدير عام')
                );
                $query->whereNotIn('role', $excludedRoles);
            }
        }

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Handle department filter
        if ($request->filled('department')) {
            $query->where('department', $request->get('department'));
        }

        // Handle role filter
        if ($request->filled('role')) {
            $roleName = trim($request->get('role'));
            // Get role from Role model 
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                // Try to match employees with this role name or display name
                $query->where(function ($q) use ($role, $roleName) {
                    $q->where('role', $roleName)
                        ->orWhere('role', $role->display_name);
                });
            } else {
                // Fallback to exact match if role not found in Role model
                $query->where('role', $roleName);
            }
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Handle location filter
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->get('location_id'));
        }

        // Handle system account filter
        if ($request->filled('has_user')) {
            if ($request->get('has_user') == '1') {
                $query->whereNotNull('user_id');
            } else {
                $query->whereNull('user_id');
            }
        }

        // Handle sponsorship filter
        if ($request->filled('sponsorship_status')) {
            $query->where('sponsorship_status', $request->get('sponsorship_status'));
        }

        // Handle hire date range filter
        if ($request->filled('hire_date_from')) {
            $query->whereDate('hire_date', '>=', $request->get('hire_date_from'));
        }
        if ($request->filled('hire_date_to')) {
            $query->whereDate('hire_date', '<=', $request->get('hire_date_to'));
        }

        // Handle sorting
        if ($request->filled('sort')) {
            switch ($request->get('sort')) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'hire_date_desc':
                    $query->orderBy('hire_date', 'desc');
                    break;
                case 'hire_date_asc':
                    $query->orderBy('hire_date', 'asc');
                    break;
                case 'national_id_expiry_asc':
                    $query->orderByRaw('national_id_expiry IS NULL, national_id_expiry ASC');
                    break;
                case 'national_id_expiry_desc':
                    $query->orderByRaw('national_id_expiry IS NULL, national_id_expiry DESC');
                    break;
                case 'department_asc':
                    $query->orderBy('department', 'asc');
                    break;
                case 'role_asc':
                    $query->orderBy('role', 'asc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $employees = $query->paginate(10);

        // Preserve all parameters in pagination links
        $employees->appends($request->except('page'));

        // Get filter options for dropdowns
        $departments = Employee::distinct()->pluck('department')->filter()->map(fn($d) => trim($d))->unique()->sort();
        // Get roles from Role model with Arabic display names
        $roles = Role::where('is_active', true)->pluck('display_name', 'name');
        $sponsorshipStatuses = Employee::distinct()->pluck('sponsorship_status')->filter()->sort();
        $locations = Location::where('status', 'active')->get();

        return view('employees.index', compact('employees', 'departments', 'roles', 'sponsorshipStatuses', 'locations'));
    }

    public function create()
    {
        $locations = Location::where('status', 'active')->get();
        $roles = Role::where('is_active', true)->orderBy('display_name')->get();
        return view('employees.create', compact('locations', 'roles'));
    }

    public function store(Request $request)
    {
        // Get valid role names from database
        $validRoles = Role::where('is_active', true)->pluck('name')->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string|max:20',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'working_hours' => 'required|numeric|min:1|max:24',
            'national_id' => [
                'required',
                'string',
                'max:20',
                'unique:employees',
                function ($attribute, $value, $fail) {
                    // Check if user with this national_id email already exists
                    $userEmail = $value . '@alabraaj.com.sa';
                    if (User::where('email', $userEmail)->exists()) {
                        $fail('يوجد مستخدم مسجل بالفعل بهذا الرقم الوطني.');
                    }
                },
            ],
            'national_id_expiry' => 'nullable|date|after:today',
            'address' => 'nullable|string',
            'role' => 'required|in:' . implode(',', $validRoles),
            'sponsorship_status' => 'required|in:شركة الأبراج للمقاولات المحدودة,فرع1 شركة الأبراج للمقاولات المحدودة,فرع2 شركة الأبراج للمقاولات المحدودة,مؤسسة فريق التعمير للمقاولات,فرع مؤسسة فريق التعمير للنقل,مؤسسة الزفاف الذهبي,مؤسسة عنوان الكادي,عمالة منزلية,عمالة كفالة خارجية تحت التجربة,أخرى',
            'category' => 'required|in:A+,A,B,C,D,E',

            // Photo uploads
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'national_id_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'work_permit_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Passport data
            'passport_number' => 'nullable|string|max:50',
            'passport_issue_date' => 'nullable|date',
            'passport_expiry_date' => 'nullable|date|after:passport_issue_date',

            // Work permit data
            'work_permit_number' => 'nullable|string|max:50',
            'work_permit_issue_date' => 'nullable|date',
            'work_permit_expiry_date' => 'nullable|date|after:work_permit_issue_date',

            // Driving license data
            'driving_license_issue_date' => 'nullable|date',
            'driving_license_expiry' => 'nullable|date|after:driving_license_issue_date',
            'driving_license_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Location assignment
            'location_id' => 'nullable|exists:locations,id',


            // Bank account information
            'bank_name' => 'nullable|string|max:255',
            'iban' => [
                'nullable',
                'string',
                'regex:/^[0-9]{22}$/',
            ],

            // Personal information (newly added fields)
            'birth_date' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',



            // Rating
            'rating' => 'nullable|numeric|min:1|max:5',

            // Additional documents
            'additional_documents' => 'nullable|array',
            'additional_documents.*.name' => 'required_with:additional_documents|string|max:255',
            'additional_documents.*.file' => 'required_with:additional_documents|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            // Custom validation messages in Arabic
            'role.required' => 'الدور الوظيفي مطلوب.',
            'role.in' => 'الدور الوظيفي المختار غير صحيح. يرجى اختيار دور صحيح من القائمة.',
        ]);

        // Set employee status to inactive by default for new employees
        $validated['status'] = 'inactive';

        // Handle file uploads
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        if ($request->hasFile('national_id_photo')) {
            $validated['national_id_photo'] = $request->file('national_id_photo')->store('employees/national_ids', 'public');
        }

        if ($request->hasFile('passport_photo')) {
            $validated['passport_photo'] = $request->file('passport_photo')->store('employees/passports', 'public');
        }

        if ($request->hasFile('work_permit_photo')) {
            $validated['work_permit_photo'] = $request->file('work_permit_photo')->store('employees/work_permits', 'public');
        }

        if ($request->hasFile('driving_license_photo')) {
            $validated['driving_license_photo'] = $request->file('driving_license_photo')->store('employees/driving_licenses', 'public');
        }

        // Handle additional documents
        if ($request->has('additional_documents') && is_array($request->additional_documents)) {
            $additionalDocuments = [];
            foreach ($request->additional_documents as $index => $document) {
                if (isset($document['name']) && isset($document['file']) && $document['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $filePath = $document['file']->store('employees/additional_documents', 'public');
                    $additionalDocuments[] = [
                        'name' => $document['name'],
                        'file_path' => $filePath,
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['additional_documents'] = $additionalDocuments; // Remove json_encode since model has array cast
        } else {
            $validated['additional_documents'] = null;
        }

        // Create user account automatically
        $user = null;
        try {
            $userEmail = $validated['national_id'] . '@alabraaj.com.sa';
            $userPassword = $validated['national_id'];

            // Check if user with this email already exists
            $existingUser = User::where('email', $userEmail)->first();

            if (!$existingUser) {
                // Map employee role to user role
                $userRole = $this->mapEmployeeRoleToUserRole($validated['role'] ?? 'عامل');

                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $userEmail,
                    'password' => Hash::make($userPassword),
                    'role' => $userRole,
                ]);

                Log::info("تم إنشاء حساب مستخدم جديد للموظف: {$validated['name']} - البريد الإلكتروني: {$userEmail} - الصلاحية: {$userRole}");
            } else {
                $user = $existingUser;
            }
        } catch (\Exception $e) {
            Log::error("خطأ في إنشاء حساب المستخدم للموظف {$validated['name']}: " . $e->getMessage());
        }

        // Add user_id to employee data if user was created/found
        if ($user) {
            $validated['user_id'] = $user->id;
        }

        // Create the employee
        $employee = Employee::create($validated);

        $successMessage = 'تم إضافة الموظف بنجاح';

        if ($user) {
            $userEmail = $validated['national_id'] . '@alabraaj.com.sa';
            $successMessage .= " وتم إنشاء حساب مستخدم له. بيانات الدخول: البريد الإلكتروني: {$userEmail} | كلمة المرور: {$validated['national_id']}";
        }

        // إرسال بريد إلكتروني للمدير العام
        try {
            EmailService::sendNewEmployeeNotification($employee);
            Log::info("تم إرسال إشعار بريد إلكتروني للمدير العام بخصوص إضافة الموظف الجديد: {$employee->name}");
        } catch (\Exception $e) {
            Log::error("فشل إرسال إشعار بريد إلكتروني للمدير العام: " . $e->getMessage());
        }

        return redirect()->route('employees.index')
            ->with('success', $successMessage);
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'location',
            'drivenEquipment',
            'user',
            'directManager.location',
            'subordinates',
            'managerAssignments.manager.location',
            'managerAssignments.assignedBy',
            'balances.creator'
        ]);

        // Get balances ordered by transaction date
        $balances = $employee->balances()->with('creator')->orderBy('transaction_date', 'desc')->get();
        $netBalance = $employee->getNetBalance();

        // Get potential managers for the manager assignment modal
        $requiredRole = \App\Models\Employee::MANAGER_CHAIN[$employee->role] ?? null;
        $potentialManagers = collect();
        if ($requiredRole) {
            $potentialManagers = \App\Models\Employee::where('role', $requiredRole)
                ->where('id', '!=', $employee->id)
                ->orderBy('name')
                ->get();
        }

        return view('employees.show', compact('employee', 'balances', 'netBalance', 'potentialManagers'));
    }

    public function edit(Employee $employee)
    {
        $locations = Location::where('status', 'active')->get();
        $roles = Role::where('is_active', true)->orderBy('display_name')->get();
        return view('employees.edit', compact('employee', 'locations', 'roles'));
    }

    public function update(Request $request, Employee $employee)
    {
        Log::info('Employee update started', [
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'request_data_count' => count($request->all()),
            'has_files' => $request->hasFile('photo') || $request->hasFile('national_id_photo'),
            'current_employee_data' => [
                'name' => $employee->name,
                'email' => $employee->email,
                'position' => $employee->position,
                'department' => $employee->department,
                'salary' => $employee->salary,
                'status' => $employee->status,
                'rating' => $employee->rating
            ]
        ]);

        try {
            // Get valid role names from database
            $validRoles = Role::where('is_active', true)->pluck('name')->toArray();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $employee->id,
                'phone' => 'required|string|max:20',
                'hire_date' => 'required|date',
                'salary' => 'required|numeric|min:0',
                'working_hours' => 'required|numeric|min:1|max:24',
                'national_id' => 'required|string|max:20|unique:employees,national_id,' . $employee->id,
                'national_id_expiry' => 'nullable|date|after:today',
                'address' => 'nullable|string',
                'status' => 'nullable|in:active,inactive,suspended,terminated',
                'role' => 'required|in:' . implode(',', $validRoles),
                'sponsorship_status' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',

                // Photo uploads
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'national_id_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'work_permit_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

                // Passport data
                'passport_number' => 'nullable|string|max:50',
                'passport_issue_date' => 'nullable|date',
                'passport_expiry_date' => 'nullable|date|after:passport_issue_date',

                // Work permit data
                'work_permit_number' => 'nullable|string|max:50',
                'work_permit_issue_date' => 'nullable|date',
                'work_permit_expiry_date' => 'nullable|date|after:work_permit_issue_date',

                // Driving license data
                'driving_license_issue_date' => 'nullable|date',
                'driving_license_expiry' => 'nullable|date|after:driving_license_issue_date',
                'driving_license_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

                // Location assignment
                'location_id' => 'nullable|exists:locations,id',


                // Bank account information
                'bank_name' => 'nullable|string|max:255',
                'iban' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]{22}$/',
                ],

                // Personal information (newly added fields)
                'birth_date' => 'nullable|date|before:today',
                'nationality' => 'nullable|string|max:100',
                'religion' => 'nullable|string|max:100',



                // Rating
                'rating' => 'nullable|numeric|min:1|max:5',

                // Additional documents
                'additional_documents' => 'nullable|array',
                'additional_documents.*.name' => 'required_with:additional_documents|string|max:255',
                'additional_documents.*.file' => 'required_with:additional_documents|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ], [
                // Custom validation messages in Arabic
                'role.required' => 'الدور الوظيفي مطلوب.',
                'role.in' => 'الدور الوظيفي المختار غير صحيح. يرجى اختيار دور صحيح من القائمة.',
            ]);

            Log::info('Validation passed successfully', [
                'employee_id' => $employee->id,
                'validated_data_keys' => array_keys($validated)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'employee_id' => $employee->id,
                'validation_errors' => $e->errors(),
                'request_data' => $request->except(['_token', '_method'])
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error during validation', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ غير متوقع: ' . $e->getMessage());
        }

        // Handle file uploads
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        if ($request->hasFile('national_id_photo')) {
            if ($employee->national_id_photo) {
                Storage::disk('public')->delete($employee->national_id_photo);
            }
            $validated['national_id_photo'] = $request->file('national_id_photo')->store('employees/national_ids', 'public');
        }

        if ($request->hasFile('passport_photo')) {
            if ($employee->passport_photo) {
                Storage::disk('public')->delete($employee->passport_photo);
            }
            $validated['passport_photo'] = $request->file('passport_photo')->store('employees/passports', 'public');
        }

        if ($request->hasFile('work_permit_photo')) {
            if ($employee->work_permit_photo) {
                Storage::disk('public')->delete($employee->work_permit_photo);
            }
            $validated['work_permit_photo'] = $request->file('work_permit_photo')->store('employees/work_permits', 'public');
        }

        if ($request->hasFile('driving_license_photo')) {
            if ($employee->driving_license_photo) {
                Storage::disk('public')->delete($employee->driving_license_photo);
            }
            $validated['driving_license_photo'] = $request->file('driving_license_photo')->store('employees/driving_licenses', 'public');
        }

        // Handle additional documents
        if ($request->has('additional_documents') && is_array($request->additional_documents)) {
            // Delete old additional documents files if they exist
            if ($employee->additional_documents) {
                $oldDocuments = $employee->additional_documents;

                // If it's a string (from database), decode it
                if (is_string($oldDocuments)) {
                    $oldDocuments = json_decode($oldDocuments, true);
                }

                if (is_array($oldDocuments)) {
                    foreach ($oldDocuments as $oldDoc) {
                        if (isset($oldDoc['file_path'])) {
                            Storage::disk('public')->delete($oldDoc['file_path']);
                        }
                    }
                }
            }

            $additionalDocuments = [];
            foreach ($request->additional_documents as $index => $document) {
                if (isset($document['name']) && isset($document['file']) && $document['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $filePath = $document['file']->store('employees/additional_documents', 'public');
                    $additionalDocuments[] = [
                        'name' => $document['name'],
                        'file_path' => $filePath,
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
            $validated['additional_documents'] = $additionalDocuments; // Remove json_encode since model has array cast
        }

        try {
            $oldData = $employee->toArray();

            // Handle status field - only general_manager users can modify employee status
            if (isset($validated['status'])) {
                if (!Auth::check() || Auth::user()->role !== 'general_manager') {
                    // Remove status from validated data if user is not general_manager
                    unset($validated['status']);

                    Log::info('Non-general_manager user attempted to modify employee status', [
                        'user_id' => Auth::id(),
                        'user_role' => Auth::user()->role ?? 'guest',
                        'employee_id' => $employee->id
                    ]);
                }
            }

            $employee->update($validated);

            $newData = $employee->fresh()->toArray();

            Log::info('Employee updated successfully', [
                'employee_id' => $employee->id,
                'changes_made' => array_diff_assoc($newData, $oldData),
                'updated_fields_count' => count($validated)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update employee', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'validated_data' => $validated
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'فشل في تحديث بيانات الموظف: ' . $e->getMessage());
        }

        // Update user role if user account exists
        if ($employee->user) {
            $userRole = $this->mapEmployeeRoleToUserRole($validated['role'] ?? 'عامل');
            $employee->user->update(['role' => $userRole]);
            Log::info("تم تحديث صلاحية المستخدم للموظف: {$employee->name} - الصلاحية الجديدة: {$userRole}");
        }

        Log::info('Employee update process completed successfully', [
            'employee_id' => $employee->id,
            'redirecting_to' => 'employees.index'
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }

    public function attendance()
    {
        $today = now()->toDateString();

        // Get all active employees with their today's attendance
        $query = Employee::where('status', 'active');

        // Site manager filter: show only employees in same location
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->employee) {
            $currentEmployee = $currentUser->employee;

            // Check if current user is a site manager (includes all variants)
            $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
            if (in_array($currentEmployee->role, $siteManagerRoles)) {
                // Filter to show only employees in the same location
                if ($currentEmployee->location_id) {
                    $query->where('location_id', $currentEmployee->location_id);
                }
                // Exclude the site manager himself from the list
                $query->where('id', '!=', $currentEmployee->id);
            }

            // Engineer filter: show only employees in locations under managed projects
            $engineerVariants = Employee::variantsForArabic('مهندس');
            if (in_array($currentEmployee->role, $engineerVariants)) {
                // Get projects managed by this engineer
                $managedProjectIds = \App\Models\Project::where('project_manager_id', $currentEmployee->id)
                    ->pluck('id');

                if ($managedProjectIds->isNotEmpty()) {
                    // Get all locations under these projects
                    $projectLocationIds = \App\Models\Location::whereIn('project_id', $managedProjectIds)
                        ->pluck('id');

                    if ($projectLocationIds->isNotEmpty()) {
                        // Show employees in these locations
                        $query->whereIn('location_id', $projectLocationIds);
                    } else {
                        // If no locations in managed projects, show empty result
                        $query->where('id', 0);
                    }
                } else {
                    // If engineer has no projects, show empty result
                    $query->where('id', 0);
                }
            }

            // Manager filter: show all employees except managers and general managers
            $managerVariants = Employee::variantsForArabic('مدير');
            if (in_array($currentEmployee->role, $managerVariants)) {
                // Exclude managers and general managers
                $excludedRoles = array_merge(
                    Employee::variantsForArabic('مدير'),
                    Employee::variantsForArabic('مدير عام')
                );
                $query->whereNotIn('role', $excludedRoles);
            }

            // Manager filter: show all employees except managers and general managers
            $managerVariants = Employee::variantsForArabic('مدير');
            if (in_array($currentEmployee->role, $managerVariants)) {
                // Exclude managers and general managers
                $excludedRoles = array_merge(
                    Employee::variantsForArabic('مدير'),
                    Employee::variantsForArabic('مدير عام')
                );
                $query->whereNotIn('role', $excludedRoles);
            }

            // Project Manager filter: show all employees except managers and general managers
            $projectManagerVariants = Employee::variantsForArabic('مدير مشاريع');
            if (in_array($currentEmployee->role, $projectManagerVariants)) {
                // Exclude managers and general managers
                $excludedRoles = array_merge(
                    Employee::variantsForArabic('مدير'),
                    Employee::variantsForArabic('مدير عام')
                );
                $query->whereNotIn('role', $excludedRoles);
            }
        }

        $employees = $query->with(['attendances' => function ($query) use ($today) {
            $query->where('date', $today);
        }])
            ->get();

        // Calculate attendance statistics for today
        $stats = [
            'total_employees' => $employees->count(),
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'leave' => 0,
            'total_overtime_hours' => 0
        ];

        foreach ($employees as $employee) {
            $attendance = $employee->attendances->first();
            if ($attendance) {
                switch ($attendance->status) {
                    case 'present':
                        $stats['present']++;
                        break;
                    case 'absent':
                        $stats['absent']++;
                        break;
                    case 'late':
                        $stats['late']++;
                        break;
                    case 'leave':
                    case 'sick_leave':
                        $stats['leave']++;
                        break;
                }

                // Calculate overtime hours
                $contractHours = $employee->working_hours ?? 8;
                $actualHours = $attendance->working_hours ?? 0;
                if ($actualHours > $contractHours) {
                    $stats['total_overtime_hours'] += ($actualHours - $contractHours);
                }
            } else {
                // If no attendance record, consider as absent
                $stats['absent']++;
            }
        }

        return view('employees.attendance', compact('employees', 'stats'));
    }

    public function monthlyAttendanceReport(Request $request)
    {
        $month = (int)$request->input('month', now()->month);
        $year = (int)$request->input('year', now()->year);

        $date = \Carbon\Carbon::createFromDate($year, $month, 1);

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Calculate total days in month
        $totalDaysInMonth = $date->daysInMonth;

        // Calculate Fridays (weekends) in the month
        $fridaysCount = 0;
        $currentDate = $startOfMonth->copy();
        while ($currentDate->lte($endOfMonth)) {
            if ($currentDate->dayOfWeek === \Carbon\Carbon::FRIDAY) {
                $fridaysCount++;
            }
            $currentDate->addDay();
        }

        // Calculate working days (excluding Fridays)
        $workingDaysInMonth = $totalDaysInMonth - $fridaysCount;

        // Get employees with site manager filter
        $query = Employee::where('status', 'active');

        // Site manager filter: show only employees in same location
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->employee) {
            $currentEmployee = $currentUser->employee;

            // Check if current user is a site manager (includes all variants)
            $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
            if (in_array($currentEmployee->role, $siteManagerRoles)) {
                // Filter to show only employees in the same location
                if ($currentEmployee->location_id) {
                    $query->where('location_id', $currentEmployee->location_id);
                }
                // Exclude the site manager himself from the list
                $query->where('id', '!=', $currentEmployee->id);
            }

            // Engineer filter: show only employees in locations under managed projects
            $engineerVariants = Employee::variantsForArabic('مهندس');
            if (in_array($currentEmployee->role, $engineerVariants)) {
                // Get projects managed by this engineer
                $managedProjectIds = \App\Models\Project::where('project_manager_id', $currentEmployee->id)
                    ->pluck('id');

                if ($managedProjectIds->isNotEmpty()) {
                    // Get all locations under these projects
                    $projectLocationIds = \App\Models\Location::whereIn('project_id', $managedProjectIds)
                        ->pluck('id');

                    if ($projectLocationIds->isNotEmpty()) {
                        // Show employees in these locations
                        $query->whereIn('location_id', $projectLocationIds);
                    } else {
                        // If no locations in managed projects, show empty result
                        $query->where('id', 0);
                    }
                } else {
                    // If engineer has no projects, show empty result
                    $query->where('id', 0);
                }
            }

            // Manager filter: show all employees except managers and general managers
            $managerVariants = Employee::variantsForArabic('مدير');
            if (in_array($currentEmployee->role, $managerVariants)) {
                // Exclude managers and general managers
                $excludedRoles = array_merge(
                    Employee::variantsForArabic('مدير'),
                    Employee::variantsForArabic('مدير عام')
                );
                $query->whereNotIn('role', $excludedRoles);
            }

            // Manager filter: show all employees except managers and general managers
            $managerVariants = Employee::variantsForArabic('مدير');
            if (in_array($currentEmployee->role, $managerVariants)) {
                // Exclude managers and general managers
                $excludedRoles = array_merge(
                    Employee::variantsForArabic('مدير'),
                    Employee::variantsForArabic('مدير عام')
                );
                $query->whereNotIn('role', $excludedRoles);
            }

            // Project Manager filter: show all employees except managers and general managers
            $projectManagerVariants = Employee::variantsForArabic('مدير مشاريع');
            if (in_array($currentEmployee->role, $projectManagerVariants)) {
                // Exclude managers and general managers
                $excludedRoles = array_merge(
                    Employee::variantsForArabic('مدير'),
                    Employee::variantsForArabic('مدير عام')
                );
                $query->whereNotIn('role', $excludedRoles);
            }
        }

        $employees = $query->with(['attendances' => function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
        }])
            ->get();

        foreach ($employees as $employee) {
            $employee->present_days = $employee->attendances->where('status', 'present')->count();
            $employee->late_days = $employee->attendances->where('status', 'late')->count();
            $employee->leave_days = $employee->attendances->whereIn('status', ['leave', 'sick_leave'])->count();

            // Calculate absent days (working days - all recorded attendances)
            $totalRecordedDays = $employee->attendances->count();
            $employee->absent_days = max(0, $workingDaysInMonth - $totalRecordedDays);

            // Set Friday count for display
            $employee->friday_days = $fridaysCount;
            $employee->working_days_in_month = $workingDaysInMonth;
            $employee->total_days_in_month = $totalDaysInMonth;

            // Calculate overtime hours (hours worked beyond 8 hours per day)
            $employee->overtime_hours = $employee->attendances
                ->where('working_hours', '>', 8)
                ->sum(function ($attendance) {
                    return max(0, $attendance->working_hours - 8);
                });

            // Round to 2 decimal places
            $employee->overtime_hours = round($employee->overtime_hours, 2);
        }

        return view('employees.monthly_attendance_report', compact('employees', 'month', 'year', 'totalDaysInMonth', 'fridaysCount', 'workingDaysInMonth'));
    }

    /**
     * Show daily attendance report for a specific date
     */
    public function dailyAttendanceReport(Request $request)
    {
        try {
            $date = $request->input('date', now()->toDateString());
            $selectedDate = \Carbon\Carbon::parse($date);

            // Get all active employees with their attendance for the selected date
            $query = Employee::where('status', 'active');

            // Site manager filter: show only employees in same location
            $currentUser = Auth::user();
            if ($currentUser && $currentUser->employee) {
                $currentEmployee = $currentUser->employee;

                // Check if current user is a site manager (includes all variants)
                $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
                if (in_array($currentEmployee->role, $siteManagerRoles)) {
                    // Filter to show only employees in the same location
                    if ($currentEmployee->location_id) {
                        $query->where('location_id', $currentEmployee->location_id);
                    }
                    // Exclude the site manager himself from the list
                    $query->where('id', '!=', $currentEmployee->id);
                }

                // Engineer filter: show only employees registered on projects they manage
                $engineerVariants = Employee::variantsForArabic('مهندس');
                if (in_array($currentEmployee->role, $engineerVariants)) {
                    // Get projects managed by this engineer
                    $managedProjectIds = \App\Models\Project::where('project_manager_id', $currentEmployee->id)
                        ->pluck('id');

                    if ($managedProjectIds->isNotEmpty()) {
                        // Get all locations under these projects
                        $projectLocationIds = \App\Models\Location::whereIn('project_id', $managedProjectIds)
                            ->pluck('id');

                        if ($projectLocationIds->isNotEmpty()) {
                            // Show employees in these locations
                            $query->whereIn('location_id', $projectLocationIds);
                        } else {
                            // If no locations in managed projects, show empty result
                            $query->where('id', 0);
                        }
                    } else {
                        // If engineer has no projects, show empty result
                        $query->where('id', 0);
                    }
                }

                // Manager filter: show all employees except managers and general managers
                $managerVariants = Employee::variantsForArabic('مدير');
                if (in_array($currentEmployee->role, $managerVariants)) {
                    // Exclude managers and general managers
                    $excludedRoles = array_merge(
                        Employee::variantsForArabic('مدير'),
                        Employee::variantsForArabic('مدير عام')
                    );
                    $query->whereNotIn('role', $excludedRoles);
                }

                // Project Manager filter: show all employees except managers and general managers
                $projectManagerVariants = Employee::variantsForArabic('مدير مشاريع');
                if (in_array($currentEmployee->role, $projectManagerVariants)) {
                    // Exclude managers and general managers
                    $excludedRoles = array_merge(
                        Employee::variantsForArabic('مدير'),
                        Employee::variantsForArabic('مدير عام')
                    );
                    $query->whereNotIn('role', $excludedRoles);
                }
            }

            $employees = $query->with(['attendances' => function ($query) use ($date) {
                $query->whereDate('date', $date);
            }])
                ->orderBy('name')
                ->get();

            // Calculate statistics for the day
            $stats = [
                'total_employees' => $employees->count(),
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'leave' => 0,
                'total_overtime_hours' => 0
            ];

            foreach ($employees as $employee) {
                $attendance = $employee->attendances->first();

                // Add attendance status to employee for easy access
                $employee->attendance_status = $attendance ? $attendance->status : 'absent';
                $employee->check_in = $attendance ? $attendance->check_in : null;
                $employee->check_out = $attendance ? $attendance->check_out : null;
                $employee->working_hours = $attendance ? $attendance->working_hours : 0;
                $employee->late_minutes = $attendance ? $attendance->late_minutes : 0;
                $employee->notes = $attendance ? $attendance->notes : '';
                $employee->attendance_id = $attendance ? $attendance->id : null;

                if ($attendance) {
                    switch ($attendance->status) {
                        case 'present':
                            $stats['present']++;
                            break;
                        case 'absent':
                            $stats['absent']++;
                            break;
                        case 'late':
                            $stats['late']++;
                            break;
                        case 'leave':
                        case 'sick_leave':
                            $stats['leave']++;
                            break;
                    }

                    if ($attendance->overtime_hours > 0) {
                        $stats['total_overtime_hours'] += $attendance->overtime_hours;
                    }
                } else {
                    $stats['absent']++;
                }
            }

            return view('employees.daily_attendance_report', compact('employees', 'stats', 'selectedDate', 'date'));
        } catch (\Exception $e) {
            Log::error('Error in dailyAttendanceReport: ' . $e->getMessage(), [
                'date' => $request->input('date'),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('employees.attendance')->with('error', 'حدث خطأ أثناء تحميل التقرير اليومي: ' . $e->getMessage());
        }
    }

    /**
     * Show daily attendance edit form for a specific date
     */
    public function dailyAttendanceEdit(Request $request)
    {
        try {
            $date = $request->input('date', now()->toDateString());

            // Validate date format
            try {
                $selectedDate = \Carbon\Carbon::parse($date);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'تاريخ غير صحيح: ' . $date);
            }

            // Get all active employees with their attendance for the selected date
            $query = Employee::where('status', 'active');

            // Site manager filter: show only employees in same location
            $currentUser = Auth::user();
            if ($currentUser && $currentUser->employee) {
                $currentEmployee = $currentUser->employee;

                // Check if current user is a site manager (includes all variants)
                $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
                if (in_array($currentEmployee->role, $siteManagerRoles)) {
                    // Filter to show only employees in the same location
                    if ($currentEmployee->location_id) {
                        $query->where('location_id', $currentEmployee->location_id);
                    }
                    // Exclude the site manager himself from the list
                    $query->where('id', '!=', $currentEmployee->id);
                }

                // Engineer filter: show only employees registered on projects they manage
                $engineerVariants = Employee::variantsForArabic('مهندس');
                if (in_array($currentEmployee->role, $engineerVariants)) {
                    // Get projects managed by this engineer
                    $managedProjectIds = \App\Models\Project::where('project_manager_id', $currentEmployee->id)
                        ->pluck('id');

                    if ($managedProjectIds->isNotEmpty()) {
                        // Get all locations under these projects
                        $projectLocationIds = \App\Models\Location::whereIn('project_id', $managedProjectIds)
                            ->pluck('id');

                        if ($projectLocationIds->isNotEmpty()) {
                            // Show employees in these locations
                            $query->whereIn('location_id', $projectLocationIds);
                        } else {
                            // If no locations in managed projects, show empty result
                            $query->where('id', 0);
                        }
                    } else {
                        // If engineer has no projects, show empty result
                        $query->where('id', 0);
                    }
                }

                // Manager filter: show all employees except managers and general managers
                $managerVariants = Employee::variantsForArabic('مدير');
                if (in_array($currentEmployee->role, $managerVariants)) {
                    // Exclude managers and general managers
                    $excludedRoles = array_merge(
                        Employee::variantsForArabic('مدير'),
                        Employee::variantsForArabic('مدير عام')
                    );
                    $query->whereNotIn('role', $excludedRoles);
                }

                // Project Manager filter: show all employees except managers and general managers
                $projectManagerVariants = Employee::variantsForArabic('مدير مشاريع');
                if (in_array($currentEmployee->role, $projectManagerVariants)) {
                    // Exclude managers and general managers
                    $excludedRoles = array_merge(
                        Employee::variantsForArabic('مدير'),
                        Employee::variantsForArabic('مدير عام')
                    );
                    $query->whereNotIn('role', $excludedRoles);
                }
            }

            $employees = $query->with(['attendances' => function ($query) use ($date) {
                $query->whereDate('date', $date);
            }])
                ->orderBy('name')
                ->get();

            foreach ($employees as $employee) {
                $attendance = $employee->attendances->first();

                // Add attendance data to employee for easy access
                $employee->attendance_status = $attendance ? $attendance->status : 'absent';
                $employee->check_in = $attendance ? $attendance->check_in : '';
                $employee->check_out = $attendance ? $attendance->check_out : '';
                $employee->working_hours = $attendance ? $attendance->working_hours : 0;
                $employee->late_minutes = $attendance ? $attendance->late_minutes : 0;
                $employee->notes = $attendance ? $attendance->notes : '';
                $employee->attendance_id = $attendance ? $attendance->id : null;
            }

            return view('employees.daily_attendance_edit', compact('employees', 'selectedDate', 'date'));
        } catch (\Exception $e) {
            Log::error('Error in dailyAttendanceEdit: ' . $e->getMessage(), [
                'date' => $request->input('date'),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('employees.attendance')->with('error', 'حدث خطأ أثناء تحميل صفحة التعديل: ' . $e->getMessage());
        }
    }

    /**
     * Update daily attendance data
     */
    public function dailyAttendanceUpdate(Request $request)
    {
        $date = $request->input('date');
        $selectedDate = \Carbon\Carbon::parse($date);
        $attendanceData = $request->input('attendance', []);

        try {
            foreach ($attendanceData as $employeeId => $data) {
                $employee = Employee::find($employeeId);
                if (!$employee) continue;

                // Find or create attendance record
                $attendance = Attendance::where('employee_id', $employeeId)
                    ->whereDate('date', $date)
                    ->first();

                if (!$attendance) {
                    $attendance = new Attendance([
                        'employee_id' => $employeeId,
                        'date' => $date
                    ]);
                }

                // Update attendance data
                $attendance->status = $data['status'] ?? 'absent';
                $attendance->check_in = $data['check_in'] ?: null;
                $attendance->check_out = $data['check_out'] ?: null;
                $attendance->working_hours = (float)($data['working_hours'] ?? 0);
                $attendance->late_minutes = (int)($data['late_minutes'] ?? 0);
                $attendance->notes = $data['notes'] ?? '';

                // Calculate working hours if check_in and check_out are provided
                if ($attendance->check_in && $attendance->check_out) {
                    $checkIn = \Carbon\Carbon::parse($date . ' ' . $attendance->check_in);
                    $checkOut = \Carbon\Carbon::parse($date . ' ' . $attendance->check_out);

                    if ($checkOut->greaterThan($checkIn)) {
                        $totalMinutes = $checkOut->diffInMinutes($checkIn);
                        // Subtract 1 hour for lunch break if working more than 6 hours
                        if ($totalMinutes > 360) { // More than 6 hours
                            $totalMinutes -= 60;
                        }
                        $attendance->working_hours = max(0, $totalMinutes / 60);
                    }
                }

                // Only save if not absent status or if we have actual data
                if ($attendance->status !== 'absent' || $attendance->check_in || $attendance->check_out || $attendance->notes) {
                    $attendance->save();
                } elseif ($attendance->exists) {
                    // Delete attendance record if status is absent and no other data
                    $attendance->delete();
                }
            }

            return redirect()->back()->with('success', 'تم تحديث بيانات الحضور بنجاح لتاريخ ' . $selectedDate->format('Y/m/d'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث بيانات الحضور: ' . $e->getMessage());
        }
    }

    public function print(Employee $employee)
    {
        $employee->load(['location', 'drivenEquipment.locationDetail']);
        return view('employees.print', compact('employee'));
    }

    public function printTest(Employee $employee)
    {
        $employee->load(['location', 'drivenEquipment.locationDetail']);
        return view('employees.print-test', compact('employee'));
    }

    public function simpleTest(Employee $employee)
    {
        return view('employees.simple-test', compact('employee'));
    }

    /**
     * Check-in employee for today
     */
    public function checkIn(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $today = now()->toDateString();

        // Check if employee already has attendance record for today
        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if ($attendance) {
            return redirect()->route('employees.attendance')
                ->with('error', 'تم تسجيل حضور هذا الموظف مسبقاً لهذا اليوم');
        }

        $checkInTime = now();
        $standardStartTime = now()->setTime(8, 0); // 8:00 AM
        $lateMinutes = $checkInTime->gt($standardStartTime) ?
            $checkInTime->diffInMinutes($standardStartTime) : 0;

        $status = $lateMinutes > 0 ? 'late' : 'present';

        // Prepare notes
        $notes = $validated['notes'] ?? '';
        if ($lateMinutes > 0) {
            $lateNote = "تأخر {$lateMinutes} دقيقة";
            $notes = $notes ? $lateNote . '. ' . $notes : $lateNote;
        }
        if (!$notes) {
            $notes = 'حضور منتظم';
        }

        try {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'check_in' => $checkInTime->format('H:i'),
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'notes' => $notes
            ]);

            return redirect()->route('employees.attendance')
                ->with('success', "تم تسجيل حضور {$employee->name} بنجاح");
        } catch (\Exception $e) {
            Log::error('Check-in failed: ' . $e->getMessage());
            return redirect()->route('employees.attendance')
                ->with('error', 'حدث خطأ أثناء تسجيل الحضور. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Check-out employee for today
     */
    public function checkOut(Employee $employee)
    {
        $today = now()->toDateString();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return redirect()->route('employees.attendance')
                ->with('error', 'لم يتم العثور على تسجيل حضور لهذا الموظف اليوم');
        }

        if ($attendance->check_out) {
            return redirect()->route('employees.attendance')
                ->with('error', 'تم تسجيل انصراف هذا الموظف مسبقاً');
        }

        $checkOutTime = now();
        $checkInTime = \Carbon\Carbon::parse($attendance->check_in);
        $workingHours = $checkOutTime->diffInHours($checkInTime, true);

        $attendance->update([
            'check_out' => $checkOutTime->format('H:i'),
            'working_hours' => round($workingHours, 2)
        ]);

        return redirect()->route('employees.attendance')
            ->with('success', "تم تسجيل انصراف {$employee->name} بنجاح");
    }

    /**
     * Edit attendance record
     */
    public function editAttendance(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,leave,sick_leave,excused',
            'notes' => 'nullable|string|max:500'
        ]);

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $validated['date'])
            ->first();

        if (!$attendance) {
            // Create new attendance record
            $attendance = new Attendance();
            $attendance->employee_id = $employee->id;
            $attendance->date = $validated['date'];
        }

        $attendance->check_in = $validated['check_in'];
        $attendance->check_out = $validated['check_out'];
        $attendance->status = $validated['status'];
        $attendance->notes = $validated['notes'];

        // Calculate late minutes and working hours
        if ($validated['check_in']) {
            $checkInTime = \Carbon\Carbon::parse($validated['check_in']);
            $standardStartTime = \Carbon\Carbon::parse('08:00');
            $attendance->late_minutes = $checkInTime->gt($standardStartTime) ?
                $checkInTime->diffInMinutes($standardStartTime) : 0;
        }

        if ($validated['check_in'] && $validated['check_out']) {
            $checkInTime = \Carbon\Carbon::parse($validated['check_in']);
            $checkOutTime = \Carbon\Carbon::parse($validated['check_out']);
            $attendance->working_hours = $checkOutTime->diffInHours($checkInTime, true);
        }

        $attendance->save();

        return redirect()->route('employees.attendance')
            ->with('success', "تم تحديث بيانات حضور {$employee->name} بنجاح");
    }

    public function downloadPdf(Employee $employee)
    {
        try {
            // Load employee with relationships
            $employee->load(['location', 'drivenEquipment.locationDetail']);

            // Return the print view for PDF generation
            return view('employees.print', compact('employee'))
                ->with([
                    'isPdfDownload' => true,
                    'downloadFileName' => "employee-card-{$employee->id}.pdf"
                ]);
        } catch (\Exception $e) {
            Log::error('Error generating employee PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ في إنشاء ملف PDF');
        }
    }

    public function createUserAccount(Employee $employee)
    {
        try {
            // Check if employee already has a user account
            if ($employee->user_id) {
                return redirect()->back()->with('error', 'يوجد حساب مستخدم مُسجل لهذا الموظف بالفعل');
            }

            // Check if national_id exists
            if (!$employee->national_id) {
                return redirect()->back()->with('error', 'لا يمكن إنشاء حساب مستخدم: رقم الهوية الوطنية غير موجود');
            }

            $userEmail = $employee->national_id . '@alabraaj.com.sa';
            $userPassword = $employee->national_id;

            // Check if user with this email already exists
            $existingUser = User::where('email', $userEmail)->first();

            if ($existingUser) {
                // Link existing user to employee if not already linked
                if (!$existingUser->employee) {
                    $employee->update(['user_id' => $existingUser->id]);
                    return redirect()->back()->with('success', 'تم ربط الموظف بحساب المستخدم الموجود بنجاح');
                } else {
                    return redirect()->back()->with('error', 'يوجد حساب مستخدم بهذا البريد الإلكتروني مُرتبط بموظف آخر');
                }
            }

            // Create new user account
            // Map employee role to user role
            $userRole = $this->mapEmployeeRoleToUserRole($employee->role ?? 'عامل');

            $user = User::create([
                'name' => $employee->name,
                'email' => $userEmail,
                'password' => Hash::make($userPassword),
                'role' => $userRole,
            ]);

            // Link user to employee
            $employee->update(['user_id' => $user->id]);

            Log::info("تم إنشاء حساب مستخدم جديد للموظف: {$employee->name} - البريد الإلكتروني: {$userEmail}");

            return redirect()->back()->with('success', "تم إنشاء حساب مستخدم بنجاح. البريد الإلكتروني: {$userEmail} | كلمة المرور: {$userPassword}");
        } catch (\Exception $e) {
            Log::error("خطأ في إنشاء حساب المستخدم للموظف {$employee->name}: " . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ في إنشاء حساب المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * Map employee role (Arabic) to user role (English)
     */
    private function mapEmployeeRoleToUserRole($employeeRole)
    {
        // أدوار النظام - يجب أن تتطابق مع role field في جدول roles
        $roleMapping = [
            'general_manager' => 'general_manager',
            'project_manager' => 'project_manager',
            'engineer' => 'engineer',
            'financial_manager' => 'financial_manager',
            'accountant' => 'accountant',
            'manager' => 'manager',
            'driver' => 'driver',
            'security' => 'security',
            'worker' => 'worker',
            'warehouse_manager' => 'warehouse_manager',
            'workship_manager' => 'workship_manager',
            'site_manager' => 'site_manager',
            'fuel_manager' => 'fuel_manager',
            'truck_driver' => 'truck_driver',

            // Legacy mappings for old data
            'عامل' => 'worker',
            'مشرف موقع' => 'site_manager',
            'مهندس' => 'engineer',
            'إداري' => 'manager',
            'مسئول رئيسي' => 'manager'
        ];

        return $roleMapping[$employeeRole] ?? 'worker';
    }

    /**
     * تعيين مدير مباشر للموظف
     */
    public function assignManager(Request $request, Employee $employee)
    {
        $request->validate([
            'direct_manager_id' => [
                'required',
                'exists:employees,id',
                function ($attribute, $value, $fail) use ($employee) {
                    if ($value == $employee->id) {
                        $fail('لا يمكن للموظف أن يكون مديراً لنفسه');
                    }
                },
            ]
        ]);

        $manager = Employee::find($request->direct_manager_id);
        // Determine required manager role from hierarchy
        $requiredRole = Employee::MANAGER_CHAIN[$employee->role] ?? null;
        if ($requiredRole === null) {
            return redirect()->back()->with('error', 'هذا الدور في أعلى التسلسل ولا يمكن تعيين مدير له');
        }
        if ($manager->role !== $requiredRole) {
            return redirect()->back()->with('error', "يجب أن يكون المدير المباشر ذو الدور: {$requiredRole}");
        }

        // التأكد من عدم إنشاء دورة في التبعية (الموظف لا يمكن أن يكون مدير لمديره)
        if ($this->wouldCreateCycle($employee->id, $request->direct_manager_id)) {
            $managerName = $manager->name;
            return redirect()->back()->with('error', "لا يمكن تعيين {$managerName} كمدير مباشر لأن ذلك سيؤدي إلى تضارب في التسلسل الإداري (دورة في التبعية)");
        }

        $employee->update(['direct_manager_id' => $request->direct_manager_id]);

        // تحديث موقع الموظف ليصبح نفس موقع المدير المباشر (لجميع الموظفين)
        if ($manager->location_id) {
            $employee->update([
                'location_id' => $manager->location_id,
            ]);
        }

        // تسجيل التعيين في جدول تعيينات المدير
        $notes = $request->notes ?? 'تعيين مدير مباشر';
        if ($manager->location_id) {
            $locationName = $manager->location ? $manager->location->name : 'موقع المدير';
            $notes .= " - تم تحديث موقع العمل إلى: {$locationName}";
        }

        ManagerAssignment::create([
            'employee_id' => $employee->id,
            'manager_id' => $request->direct_manager_id,
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
            'assignment_type' => 'تعيين',
            'notes' => $notes
        ]);

        $managerName = $manager->name;

        // إعداد رسالة النجاح
        $successMessage = "تم تعيين {$managerName} كمدير مباشر بنجاح";
        if ($manager->location_id) {
            $locationName = $manager->location ? $manager->location->name : 'الموقع الجديد';
            $successMessage .= " وتم تحديث موقع العمل إلى {$locationName}";
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * إزالة المدير المباشر
     */
    public function removeManager(Employee $employee)
    {
        $oldManager = $employee->direct_manager_id;

        $employee->update(['direct_manager_id' => null]);

        // تسجيل الإزالة في جدول تعيينات المدير
        if ($oldManager) {
            ManagerAssignment::create([
                'employee_id' => $employee->id,
                'manager_id' => $oldManager,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'assignment_type' => 'إزالة',
                'notes' => 'إزالة المدير المباشر'
            ]);
        }

        return redirect()->back()->with('success', 'تم إزالة المدير المباشر بنجاح');
    }

    /**
     * فحص ما إذا كان تعيين المدير سيؤدي إلى دورة في التبعية
     */
    private function wouldCreateCycle($employeeId, $managerId, $visited = [])
    {
        // التحقق الأساسي: الموظف لا يمكن أن يكون مديراً لنفسه
        if ($employeeId == $managerId) {
            return true;
        }

        // التحقق من الدورة: إذا كان المدير المقترح يتبع للموظف بطريقة غير مباشرة
        if (in_array($managerId, $visited)) {
            return true; // دورة موجودة
        }

        $visited[] = $managerId;

        $manager = Employee::find($managerId);
        if ($manager && $manager->direct_manager_id) {
            // إذا كان المدير المقترح يتبع للموظف نفسه، فهذا يؤدي لدورة
            if ($manager->direct_manager_id == $employeeId) {
                return true;
            }
            // فحص السلسلة الكاملة
            return $this->wouldCreateCycle($employeeId, $manager->direct_manager_id, $visited);
        }

        return false;
    }

    /**
     * Change employee password
     */
    public function changePassword(Request $request, Employee $employee)
    {
        try {
            // Validate the request
            $request->validate([
                'new_password' => 'required|string|min:8|confirmed',
            ], [
                'new_password.required' => 'كلمة السر الجديدة مطلوبة',
                'new_password.min' => 'كلمة السر يجب أن تكون 8 أحرف على الأقل',
                'new_password.confirmed' => 'تأكيد كلمة السر غير متطابق',
            ]);

            // Check if employee has a user account
            if (!$employee->user) {
                return redirect()->back()->with('error', 'الموظف لا يملك حساب مستخدم');
            }

            // Update the password
            $employee->user->update([
                'password' => Hash::make($request->new_password),
                'must_change_password' => false, // Reset the flag if it exists
            ]);

            Log::info('Password changed successfully', [
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'user_id' => $employee->user->id,
                'changed_by' => Auth::user()->name ?? 'System',
            ]);

            return redirect()->back()->with('success', 'تم تغيير كلمة السر بنجاح');
        } catch (\Exception $e) {
            Log::error('Error changing employee password', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'حدث خطأ أثناء تغيير كلمة السر: ' . $e->getMessage());
        }
    }

    public function addCredit(Request $request, Employee $employee)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date'
        ]);

        try {
            EmployeeBalance::create([
                'employee_id' => $employee->id,
                'type' => 'credit',
                'amount' => $request->amount,
                'notes' => $request->notes,
                'transaction_date' => $request->transaction_date,
                'created_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'تم إضافة الرصيد الدائن بنجاح');
        } catch (\Exception $e) {
            Log::error('Error adding credit balance', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الرصيد');
        }
    }

    public function addDebit(Request $request, Employee $employee)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date'
        ]);

        try {
            EmployeeBalance::create([
                'employee_id' => $employee->id,
                'type' => 'debit',
                'amount' => $request->amount,
                'notes' => $request->notes,
                'transaction_date' => $request->transaction_date,
                'created_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'تم إضافة الرصيد المدين بنجاح');
        } catch (\Exception $e) {
            Log::error('Error adding debit balance', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الرصيد');
        }
    }

    public function recordBalanceTransaction(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            EmployeeBalance::create([
                'employee_id' => $request->employee_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'notes' => $request->notes,
                'transaction_date' => now(),
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل معاملة الرصيد بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Error recording balance transaction', [
                'employee_id' => $request->employee_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل معاملة الرصيد'
            ]);
        }
    }

    /**
     * تفعيل الموظف - للمستخدمين الذين لهم نفس الدور فقط
     */
    public function activate(Employee $employee)
    {
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if (!$authUser || !$authUser->isGeneralManager()) {
            return redirect()->back()->with('error', 'غير مصرح لك بتفعيل الموظفين. يسمح فقط للمدير العام.');
        }
        $employee->update(['status' => 'active']);

        return redirect()->back()->with('success', 'تم تفعيل الموظف بنجاح');
    }

    /**
     * إلغاء تفعيل الموظف - للمستخدمين الذين لهم نفس الدور فقط
     */
    public function deactivate(Employee $employee)
    {
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if (!$authUser || !$authUser->isGeneralManager()) {
            return redirect()->back()->with('error', 'غير مصرح لك بإلغاء تفعيل الموظفين. يسمح فقط للمدير العام.');
        }
        $employee->update(['status' => 'inactive']);

        return redirect()->back()->with('success', 'تم إلغاء تفعيل الموظف بنجاح');
    }

    /**
     * جلب تفاصيل الموظف كـ JSON (للاستخدام في AJAX)
     */
    public function getDetails(Employee $employee)
    {
        try {
            return response()->json([
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'phone' => $employee->phone,
                'status' => $employee->status,
                'department' => $employee->department,
                'position' => $employee->position,
                'hire_date' => $employee->hire_date,
                'salary' => $employee->salary,
                'notes' => $employee->notes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'فشل في جلب بيانات الموظف',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إرسال إشعار للموظف
     */
    public function sendNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'message' => 'required|string|min:1|max:500'
            ], [
                'employee_id.required' => 'معرف الموظف مطلوب',
                'employee_id.exists' => 'الموظف غير موجود',
                'message.required' => 'نص الإشعار مطلوب',
                'message.min' => 'نص الإشعار لا يمكن أن يكون فارغاً',
                'message.max' => 'نص الإشعار لا يزيد عن 500 حرف'
            ]);

            $employee = Employee::findOrFail($validated['employee_id']);
            $currentUser = Auth::user();

            // Log the notification
            Log::info('تم إرسال إشعار للموظف', [
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'message' => $validated['message'],
                'sent_by' => $currentUser ? $currentUser->name : 'نظام',
                'timestamp' => now()
            ]);

            // TODO: يمكن إضافة حفظ الإشعار في جدول في قاعدة البيانات
            // أو إرساله عبر البريد الإلكتروني أو SMS

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال الإشعار بنجاح',
                'data' => [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'sent_at' => now()->toDateTimeString()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في التحقق من البيانات',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال الإشعار', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إرسال الإشعار: ' . $e->getMessage()
            ], 500);
        }
    }
}

