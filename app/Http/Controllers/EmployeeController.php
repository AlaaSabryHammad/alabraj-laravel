<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\ManagerAssignment;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'location']);

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
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
            $query->where('role', $request->get('role'));
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

        // Handle hire date range filter
        if ($request->filled('hire_date_from')) {
            $query->whereDate('hire_date', '>=', $request->get('hire_date_from'));
        }
        if ($request->filled('hire_date_to')) {
            $query->whereDate('hire_date', '<=', $request->get('hire_date_to'));
        }

        $employees = $query->latest()->paginate(10);

        // Preserve all parameters in pagination links
        $employees->appends($request->except('page'));

        // Get filter options for dropdowns
        $departments = Employee::distinct()->pluck('department')->filter()->sort();
        $roles = Employee::distinct()->pluck('role')->filter()->sort();
        $locations = Location::where('status', 'active')->get();

        return view('employees.index', compact('employees', 'departments', 'roles', 'locations'));
    }

    public function create()
    {
        $locations = Location::where('status', 'active')->get();
        return view('employees.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string|max:20',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
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
            'national_id_expiry_date' => 'nullable|date|after:today',
            'address' => 'nullable|string',
            'role' => 'required|in:عامل,مشرف موقع,مهندس,إداري,مسئول رئيسي',
            'sponsorship' => 'required|in:شركة الأبراج للمقاولات المحدودة,فرع1 شركة الأبراج للمقاولات المحدودة,فرع2 شركة الأبراج للمقاولات المحدودة,مؤسسة فريق التعمير للمقاولات,فرع مؤسسة فريق التعمير للنقل,مؤسسة الزفاف الذهبي,مؤسسة عنوان الكادي,عمالة منزلية,عمالة كفالة خارجية تحت التجربة,أخرى',
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
            'driving_license_expiry_date' => 'nullable|date|after:driving_license_issue_date',
            'driving_license_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Location assignment
            'location_id' => 'nullable|exists:locations,id',
            'location_assignment_date' => 'nullable|date',

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
            'medical_insurance_status' => 'nullable|in:مشمول,غير مشمول',
            'location_type' => 'nullable|in:داخل المملكة,خارج المملكة',

            // Rating
            'rating' => 'nullable|integer|min:1|max:5',

            // Additional documents
            'additional_documents' => 'nullable|array',
            'additional_documents.*.name' => 'required_with:additional_documents|string|max:255',
            'additional_documents.*.file' => 'required_with:additional_documents|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $validated['status'] = 'active';

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
            $validated['additional_documents'] = json_encode($additionalDocuments);
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
            'managerAssignments.assignedBy'
        ]);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $locations = Location::where('status', 'active')->get();
        return view('employees.edit', compact('employee', 'locations'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string|max:20',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'national_id' => 'required|string|max:20|unique:employees,national_id,' . $employee->id,
            'national_id_expiry_date' => 'nullable|date|after:today',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:عامل,مشرف موقع,مهندس,إداري,مسئول رئيسي',
            'sponsorship' => 'required|in:شركة الأبراج للمقاولات المحدودة,فرع1 شركة الأبراج للمقاولات المحدودة,فرع2 شركة الأبراج للمقاولات المحدودة,مؤسسة فريق التعمير للمقاولات,فرع مؤسسة فريق التعمير للنقل,مؤسسة الزفاف الذهبي,مؤسسة عنوان الكادي,عمالة منزلية,عمالة كفالة خارجية تحت التجربة,أخرى',
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
            'driving_license_expiry_date' => 'nullable|date|after:driving_license_issue_date',
            'driving_license_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Location assignment
            'location_id' => 'nullable|exists:locations,id',
            'location_assignment_date' => 'nullable|date',

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
            'medical_insurance_status' => 'nullable|in:مشمول,غير مشمول',
            'location_type' => 'nullable|in:داخل المملكة,خارج المملكة',

            // Rating
            'rating' => 'nullable|integer|min:1|max:5',

            // Additional documents
            'additional_documents' => 'nullable|array',
            'additional_documents.*.name' => 'required_with:additional_documents|string|max:255',
            'additional_documents.*.file' => 'required_with:additional_documents|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

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
            $validated['additional_documents'] = json_encode($additionalDocuments);
        }

        $employee->update($validated);

        // Update user role if user account exists
        if ($employee->user) {
            $userRole = $this->mapEmployeeRoleToUserRole($validated['role'] ?? 'عامل');
            $employee->user->update(['role' => $userRole]);
            Log::info("تم تحديث صلاحية المستخدم للموظف: {$employee->name} - الصلاحية الجديدة: {$userRole}");
        }

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
        $employees = Employee::where('status', 'active')
            ->with(['attendances' => function($query) use ($today) {
                $query->where('date', $today);
            }])
            ->get();

        // Calculate attendance statistics for today
        $stats = [
            'total_employees' => $employees->count(),
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'leave' => 0
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

        $employees = Employee::where('status', 'active')
            ->with(['attendances' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }])
            ->get();

        foreach ($employees as $employee) {
            $employee->present_days = $employee->attendances->where('status', 'present')->count();
            $employee->absent_days = $startOfMonth->diffInDays($endOfMonth) + 1 - $employee->attendances->count();
            $employee->late_days = $employee->attendances->where('status', 'late')->count();
            $employee->leave_days = $employee->attendances->whereIn('status', ['leave', 'sick_leave'])->count();
        }

        return view('employees.monthly_attendance_report', compact('employees', 'month', 'year'));
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
        $roleMapping = [
            'عامل' => 'employee',
            'مشرف موقع' => 'supervisor',
            'مهندس' => 'engineer',
            'إداري' => 'admin',
            'مسئول رئيسي' => 'manager'
        ];

        return $roleMapping[$employeeRole] ?? 'employee';
    }

    /**
     * تعيين مدير مباشر للموظف
     */
    public function assignManager(Request $request, Employee $employee)
    {
        $request->validate([
            'direct_manager_id' => 'required|exists:employees,id|different:id'
        ]);

        $manager = Employee::find($request->direct_manager_id);

        // تطبيق قواعد التعيين حسب دور الموظف
        if ($employee->role === 'عامل') {
            // للعمال: يجب أن يكون المدير مشرف موقع
            if ($manager->role !== 'مشرف موقع') {
                return redirect()->back()->with('error', 'العامل يمكن أن يكون مديره المباشر مشرف موقع فقط');
            }
        } else {
            // لغير العمال: يجب أن يكون المدير مسؤول رئيسي
            if ($manager->role !== 'مسئول رئيسي') {
                return redirect()->back()->with('error', 'هذا الموظف يمكن أن يكون مديره المباشر مسؤول رئيسي فقط');
            }
        }

        // التأكد من عدم إنشاء دورة في التبعية (الموظف لا يمكن أن يكون مدير لمديره)
        if ($this->wouldCreateCycle($employee->id, $request->direct_manager_id)) {
            return redirect()->back()->with('error', 'لا يمكن تعيين هذا المدير لأنه سيؤدي إلى تضارب في التبعية');
        }

        $employee->update(['direct_manager_id' => $request->direct_manager_id]);

        // إذا كان الموظف عامل، يتم تحديث موقع عمله ليصبح نفس موقع المدير المباشر
        if ($employee->role === 'عامل' && $manager->location_id) {
            $employee->update([
                'location_id' => $manager->location_id,
                'location_assignment_date' => now()
            ]);
        }

        // تسجيل التعيين في جدول تعيينات المدير
        $notes = $request->notes ?? 'تعيين مدير مباشر';
        if ($employee->role === 'عامل' && $manager->location_id) {
            $locationName = $manager->location ? $manager->location->name : 'موقع المدير';
            $notes .= " - تم تحديث موقع العمل إلى: {$locationName}";
        }

        ManagerAssignment::create([
            'employee_id' => $employee->id,
            'manager_id' => $request->direct_manager_id,
            'assigned_by' => 1, // يمكن تحديث هذا لاحقاً ليكون معرف المستخدم الحالي
            'assigned_at' => now(),
            'assignment_type' => 'تعيين',
            'notes' => $notes
        ]);

        $managerName = $manager->name;

        // إعداد رسالة النجاح
        $successMessage = "تم تعيين {$managerName} كمدير مباشر بنجاح";
        if ($employee->role === 'عامل' && $manager->location_id) {
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
                'assigned_by' => 1, // يمكن تحديث هذا لاحقاً ليكون معرف المستخدم الحالي
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
        if (in_array($managerId, $visited)) {
            return true; // دورة موجودة
        }

        $visited[] = $managerId;

        $manager = Employee::find($managerId);
        if ($manager && $manager->direct_manager_id) {
            if ($manager->direct_manager_id == $employeeId) {
                return true; // الموظف هو مدير للمدير المقترح
            }
            return $this->wouldCreateCycle($employeeId, $manager->direct_manager_id, $visited);
        }

        return false;
    }
}
