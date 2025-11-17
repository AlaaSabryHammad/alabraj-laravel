<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use App\Models\ProjectExtract;
use App\Models\ProjectExtension;
use App\Models\ProjectVisit;
use App\Models\ProjectRentalEquipment;
use App\Models\ProjectLoan;
use App\Models\ProjectDeliveryRequest;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Check if current user can access the given project
     */
    private function checkEngineerAccess(Project $project, $action = 'الوصول')
    {
        $currentUser = Auth::user();
        if ($currentUser) {
            $currentEmployee = \App\Models\Employee::where('user_id', $currentUser->id)->first();

            if ($currentEmployee && $currentEmployee->role) {
                $engineerVariants = \App\Models\Employee::variantsForArabic('مهندس');

                // If current user is an engineer, check if they manage this project
                if (in_array($currentEmployee->role, $engineerVariants)) {
                    if ($project->project_manager_id !== $currentEmployee->id) {
                        abort(403, "غير مخول {$action} لهذا المشروع");
                    }
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get current authenticated user and their employee record
        $currentUser = Auth::user();
        $currentEmployee = null;

        if ($currentUser) {
            $currentEmployee = \App\Models\Employee::where('user_id', $currentUser->id)->first();
        }

        // Start with base query
        $query = Project::with('projectManager')->latest();

        // If current user is an engineer, show only projects they manage
        if ($currentEmployee && $currentEmployee->role) {
            $engineerVariants = \App\Models\Employee::variantsForArabic('مهندس');

            if (in_array($currentEmployee->role, $engineerVariants)) {
                // Engineer can only see projects they manage
                $query->where('project_manager_id', $currentEmployee->id);
            }
        }

        $projects = $query->paginate(10);

        // Ensure fresh attributes are loaded
        $projects->getCollection()->each(function ($project) {
            $project->refresh();
        });

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get only employees with "engineer" role (both Arabic and English variants)
        $engineerVariants = \App\Models\Employee::variantsForArabic('مهندس');
        $employees = \App\Models\Employee::where('status', 'active')
            ->whereIn('role', $engineerVariants)
            ->get();

        return view('projects.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_number' => 'nullable|string|max:255|unique:projects',
            'budget' => 'required|numeric|min:0',
            'bank_guarantee_amount' => 'nullable|numeric|min:0',
            'bank_guarantee_type' => 'nullable|in:cash,facilities',
            'government_entity' => 'nullable|string|max:255',
            'consulting_office' => 'nullable|string|max:255',
            'project_scope' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'project_manager_id' => 'required|exists:employees,id',
            'files.*.name' => 'nullable|string|max:255',
            'files.*.file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'files.*.description' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'requests.*.number' => 'nullable|string|max:255',
            'requests.*.description' => 'nullable|string|max:500',
            'requests.*.file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            // Project items validation
            'items.*.name' => 'nullable|string|max:255',
            'items.*.quantity' => 'nullable|numeric|min:0',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.total_price' => 'nullable|numeric|min:0',
            'items.*.total_with_tax' => 'nullable|numeric|min:0',
            'subtotal' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'final_total' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        // Get employee name for project_manager field (backward compatibility)
        $employee = \App\Models\Employee::find($validated['project_manager_id']);
        $validated['project_manager'] = $employee->name;
        $validated['client_name'] = $request->government_entity ?? 'غير محدد';
        $validated['location'] = $request->project_scope ?? 'غير محدد';
        $validated['status'] = 'planning';

        $project = Project::create($validated);

        // Handle file uploads
        if ($request->has('files')) {
            foreach ($request->files as $fileData) {
                if (isset($fileData['file']) && $fileData['file']->isValid()) {
                    $file = $fileData['file'];
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('project_files', $fileName, 'public');

                    $project->projectFiles()->create([
                        'name' => $fileData['name'] ?? $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'description' => $fileData['description'] ?? null,
                        'file_size' => $file->getSize(),
                        'file_type' => $file->getClientMimeType(),
                    ]);
                }
            }
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $imagePath = $image->storeAs('project_images', $imageName, 'public');

                    $project->projectImages()->create([
                        'image_path' => $imagePath,
                        'alt_text' => $project->name . ' - صورة'
                    ]);
                }
            }
        }

        // Handle delivery requests
        if ($request->has('requests')) {
            foreach ($request->requests as $requestData) {
                if (!empty($requestData['number']) || !empty($requestData['description'])) {
                    $deliveryRequest = [
                        'request_number' => $requestData['number'] ?? '',
                        'description' => $requestData['description'] ?? '',
                    ];

                    if (isset($requestData['file']) && $requestData['file']->isValid()) {
                        $file = $requestData['file'];
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $filePath = $file->storeAs('project_requests', $fileName, 'public');
                        $deliveryRequest['file_path'] = $filePath;
                    }

                    $project->deliveryRequests()->create($deliveryRequest);
                }
            }
        }

        // Handle project items
        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                if (!empty($itemData['name']) && !empty($itemData['quantity']) && !empty($itemData['unit_price'])) {
                    $project->projectItems()->create([
                        'name' => $itemData['name'],
                        'quantity' => $itemData['quantity'],
                        'unit' => $itemData['unit'] ?? '',
                        'unit_price' => $itemData['unit_price'],
                        'total_price' => $itemData['total_price'] ?? ($itemData['quantity'] * $itemData['unit_price']),
                        'total_with_tax' => $itemData['total_with_tax'] ?? 0,
                    ]);
                }
            }
        }

        // Update project budget with final total if items exist
        if ($request->filled('final_total') && $request->final_total > 0) {
            $project->update(['budget' => $request->final_total]);
        }

        return redirect()->route('projects.index')
            ->with('success', 'تم إضافة المشروع بنجاح مع جميع الملفات والصور والبنود');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Check if current user can access this project
        $this->checkEngineerAccess($project, 'للوصول');

        // Load all relationships including project items and extracts
        $project->load([
            'projectManager',
            'projectFiles',
            'projectImages',
            'deliveryRequests',
            'projectItems',
            'projectExtracts.extractItems',
            'projectExtracts.creator',
            'locations.equipment',
            'locations.employees',
            'locations.manager',
            'equipment.driver',
            'equipment.user',
            'equipment.locationDetail',
            'equipment.movementHistory',
            'loans.recordedBy',
            'extensions.extendedBy',
            'visits.recordedBy',
            'rentalEquipment.recordedBy'
        ]);

        // Get correspondences related to this project
        $correspondences = \App\Models\Correspondence::with(['assignedEmployee', 'user'])
            ->where('project_id', $project->id)
            ->latest()
            ->get();

        return view('projects.show', compact('project', 'correspondences'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Check if current user can access this project
        $this->checkEngineerAccess($project, 'لتعديل');

        // Load relationships for editing including project items, extracts, and images
        $project->load([
            'projectImages',
            'projectItems',
            'projectExtracts' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        // Get active employees with "engineer" role for project manager dropdown
        $engineerVariants = \App\Models\Employee::variantsForArabic('مهندس');
        $employees = \App\Models\Employee::where('status', 'active')
            ->whereIn('role', $engineerVariants)
            ->orderBy('name')->get();

        return view('projects.edit', compact('project', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'bank_guarantee_amount' => 'nullable|numeric|min:0',
            'bank_guarantee_type' => 'nullable|in:cash,facilities',
            'location' => 'required|string|max:255',
            'project_manager_id' => 'nullable|exists:employees,id',
            'project_manager' => 'nullable|string|max:255',
            'client_name' => 'nullable|string|max:255',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'progress' => 'nullable|integer|min:0|max:100',
            'project_number' => 'nullable|string|max:255',
            'government_entity' => 'nullable|string|max:255',
            'consulting_office' => 'nullable|string|max:255',
            'project_scope' => 'nullable|string|max:255',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Debug: Log all received data
        Log::info('Project Update - All Data:', $validated);
        Log::info('Project Update - Request Data:', $request->all());

        // Debug: Log the status value before update
        Log::info('Project Update - Status received:', ['status' => $validated['status'], 'project_id' => $project->id]);

        // If project_manager_id is provided, get the manager name
        if (!empty($validated['project_manager_id'])) {
            $manager = \App\Models\Employee::find($validated['project_manager_id']);
            if ($manager) {
                $validated['project_manager'] = $manager->name;
            }
        }

        // Update project with validated data (exclude new_images from mass assignment)
        $projectData = collect($validated)->except('new_images')->toArray();
        $project->update($projectData);

        // Handle new images upload
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                if ($image->isValid()) {
                    // Store the image
                    $imagePath = $image->store('projects/images', 'public');

                    // Create image record
                    \App\Models\ProjectImage::create([
                        'project_id' => $project->id,
                        'image_path' => $imagePath,
                        'alt_text' => 'صورة مشروع ' . $project->name,
                    ]);
                }
            }
        }

        // Log successful update for debugging
        Log::info('Project Update Success:', [
            'project_id' => $project->id,
            'updated_fields' => array_keys($validated),
            'timestamp' => now()->toDateTimeString()
        ]);

        // Prepare success message with dynamic content
        $updatedProject = $project->fresh();
        $successMessage = sprintf(
            'تم تحديث المشروع "%s" بنجاح! الحالة الحالية: %s - نسبة الإنجاز: %d%%',
            $updatedProject->name,
            $updatedProject->status_label,
            $updatedProject->progress ?? 0
        );

        // Return with enhanced success message and data
        return redirect()->route('projects.show', $project)
            ->with('success', $successMessage)
            ->with('project_updated', true)
            ->with('updated_data', [
                'name' => $updatedProject->name,
                'status' => $updatedProject->status_label,
                'progress' => $updatedProject->progress,
                'manager' => $updatedProject->projectManager->name ?? $updatedProject->project_manager ?? 'غير محدد',
                'client' => $updatedProject->client_name ?? 'غير محدد',
                'location' => $updatedProject->location ?? 'غير محدد'
            ]);
    }

    /**
     * Delete project image
     */
    public function deleteImage($imageId)
    {
        try {
            $image = \App\Models\ProjectImage::findOrFail($imageId);

            // Delete file from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete record from database
            $image->delete();

            return response()->json(['success' => true, 'message' => 'تم حذف الصورة بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ في حذف الصورة'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Check if current user can access this project
        $this->checkEngineerAccess($project, 'لحذف');

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'تم حذف المشروع بنجاح');
    }

    /**
     * Show the form for creating a new extract
     */
    public function createExtract(Project $project)
    {
        // Load project with items and previous extracts
        $project->load(['projectItems', 'projectExtracts.extractItems']);

        // Calculate previous quantities for each project item
        $previousQuantities = [];
        $previousValues = [];

        foreach ($project->projectItems as $index => $item) {
            $totalPreviousQuantity = 0;
            $totalPreviousValue = 0;

            // Sum up quantities from all previous extracts
            foreach ($project->projectExtracts as $extract) {
                if ($extract->status !== 'draft') { // Only count non-draft extracts
                    foreach ($extract->extractItems as $extractItem) {
                        if ($extractItem->project_item_index == $index) {
                            $totalPreviousQuantity += $extractItem->quantity;
                            $totalPreviousValue += $extractItem->total_value;
                        }
                    }
                }
            }

            $previousQuantities[$index] = $totalPreviousQuantity;
            $previousValues[$index] = $totalPreviousValue;
        }

        // Generate next extract number
        $nextExtractNumber = 'EXT-' . $project->id . '-' . str_pad($project->projectExtracts->count() + 1, 3, '0', STR_PAD_LEFT);

        return view('projects.extract', compact('project', 'previousQuantities', 'previousValues', 'nextExtractNumber'));
    }

    /**
     * Store a new extract for the project
     */
    public function storeExtract(Request $request, Project $project)
    {
        $validated = $request->validate([
            'extract_number' => [
                'required',
                'string',
                'max:255',
                'unique:project_extracts,extract_number,NULL,id,project_id,' . $project->id
            ],
            'extract_description' => 'nullable|string|max:1000',
            'extract_date' => 'required|date',
            'extract_status' => 'required|in:draft,submitted,approved,paid',
            'extract_total' => 'required|numeric|min:0',
            'extract_items' => 'required|json',
            'extract_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240'
        ], [
            'extract_number.unique' => 'رقم المستخلص موجود مسبقاً لهذا المشروع',
            'extract_number.required' => 'رقم المستخلص مطلوب',
            'extract_date.required' => 'تاريخ المستخلص مطلوب',
            'extract_total.min' => 'قيمة المستخلص يجب أن تكون أكبر من صفر',
            'extract_items.required' => 'بيانات بنود المستخلص مطلوبة',
            'extract_file.mimes' => 'نوع الملف غير مدعوم',
            'extract_file.max' => 'حجم الملف كبير جداً (الحد الأقصى: 10 ميجابايت)',
        ]);

        try {
            // Handle file upload
            $filePath = null;
            if ($request->hasFile('extract_file')) {
                $file = $request->file('extract_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('project_extracts', $fileName, 'public');
            }

            // Parse extract items
            $extractItems = json_decode($validated['extract_items'], true);

            // حساب الضريبة والقيمة الإجمالية مع الضريبة
            $taxRate = $request->input('tax_rate', 15); // افتراضياً 15%
            $taxAmount = $validated['extract_total'] * ($taxRate / 100);
            $totalWithTax = $validated['extract_total'] + $taxAmount;

            // Create the extract record
            $extract = $project->projectExtracts()->create([
                'extract_number' => $validated['extract_number'],
                'description' => $validated['extract_description'],
                'extract_date' => $validated['extract_date'],
                'status' => $validated['extract_status'],
                'total_amount' => $validated['extract_total'],
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total_with_tax' => $totalWithTax,
                'file_path' => $filePath,
                'items_data' => json_encode($extractItems),
                'created_by' => Auth::id(),
            ]);

            // Store individual extract items
            foreach ($extractItems as $itemData) {
                $extract->extractItems()->create([
                    'project_item_index' => $itemData['item_index'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_value' => $itemData['total_value'],
                ]);
            }

            // إذا كان المستخلص مدفوع، قم بإنشاء سند قبض تلقائياً
            if ($validated['extract_status'] === 'paid') {
                $this->createRevenueVoucherFromExtract($extract, $project);
            }

            return redirect()->route('projects.show', $project)
                ->with('success', 'تم حفظ المستخلص بنجاح');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ المستخلص: ' . $e->getMessage());
        }
    }

    /**
     * إنشاء سند قبض تلقائياً من المستخلص المدفوع
     */
    private function createRevenueVoucherFromExtract($extract, $project)
    {
        try {
            // البحث عن جهة إيراد افتراضية أو إنشاء واحدة
            $revenueEntity = \App\Models\RevenueEntity::where('type', 'government')
                ->orWhere('type', 'company')
                ->first();

            if (!$revenueEntity) {
                // إنشاء جهة إيراد افتراضية
                $revenueEntity = \App\Models\RevenueEntity::create([
                    'name' => $project->government_entity ?? 'جهة حكومية',
                    'type' => 'government',
                    'status' => 'active'
                ]);
            }

            // إنشاء سند القبض
            $revenueVoucher = \App\Models\RevenueVoucher::create([
                'voucher_number' => 'RV-EXT-' . $extract->extract_number,
                'voucher_date' => $extract->extract_date,
                'revenue_entity_id' => $revenueEntity->id,
                'amount' => $extract->total_with_tax, // استخدام القيمة مع الضريبة
                'description' => 'مستخلص مشروع: ' . $project->name . ' - ' . ($extract->description ?? 'مستخلص رقم ' . $extract->extract_number),
                'payment_method' => 'bank_transfer', // افتراضياً تحويل بنكي
                'tax_type' => 'taxable',
                'project_id' => $project->id,
                'location_id' => $project->location_id ?? null,
                'status' => 'approved', // معتمد تلقائياً
                'notes' => 'تم إنشاؤه تلقائياً من المستخلص المدفوع رقم: ' . $extract->extract_number . ' (القيمة مع الضريبة: ' . number_format($extract->total_with_tax, 2) . ' ر.س)',
                'created_by' => Auth::id(),
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // تحديث المستخلص ليرتبط بسند القبض
            $extract->update([
                'revenue_voucher_id' => $revenueVoucher->id
            ]);

            return $revenueVoucher;
        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء سند القبض من المستخلص: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Show extract details
     */
    public function showExtract(Project $project, ProjectExtract $extract)
    {
        // Ensure extract belongs to project
        if ($extract->project_id !== $project->id) {
            abort(404);
        }

        $project->load(['projectItems', 'projectExtracts']);
        $extract->load(['extractItems']);

        return view('projects.extract-show', compact('project', 'extract'));
    }

    /**
     * Show form for editing extract
     */
    public function editExtract(Project $project, ProjectExtract $extract)
    {
        // Ensure extract belongs to project
        if ($extract->project_id !== $project->id) {
            abort(404);
        }

        // Only draft extracts can be edited
        if ($extract->status !== 'draft') {
            return redirect()->route('projects.show', $project)
                ->with('error', 'لا يمكن تعديل المستخلص إلا في حالة المسودة');
        }

        // Load project with items and previous extracts (excluding current)
        $project->load(['projectItems', 'projectExtracts']);
        $extract->load(['extractItems']);

        // Calculate previous quantities for each project item (excluding current extract)
        $previousQuantities = [];
        $previousValues = [];
        $currentQuantities = [];

        foreach ($project->projectItems as $index => $item) {
            $totalPreviousQuantity = 0;
            $totalPreviousValue = 0;
            $currentQuantity = 0;

            // Sum up quantities from all previous extracts (excluding current)
            foreach ($project->projectExtracts as $existingExtract) {
                if ($existingExtract->status !== 'draft' && $existingExtract->id !== $extract->id) {
                    foreach ($existingExtract->extractItems as $extractItem) {
                        if ($extractItem->project_item_index == $index) {
                            $totalPreviousQuantity += $extractItem->quantity;
                            $totalPreviousValue += $extractItem->total_value;
                        }
                    }
                }
            }

            // Get current extract quantities
            foreach ($extract->extractItems as $extractItem) {
                if ($extractItem->project_item_index == $index) {
                    $currentQuantity = $extractItem->quantity;
                    break;
                }
            }

            $previousQuantities[$index] = $totalPreviousQuantity;
            $previousValues[$index] = $totalPreviousValue;
            $currentQuantities[$index] = $currentQuantity;
        }

        return view('projects.extract-edit', compact('project', 'extract', 'previousQuantities', 'previousValues', 'currentQuantities'));
    }

    /**
     * Update extract
     */
    public function updateExtract(Request $request, Project $project, ProjectExtract $extract)
    {
        // Ensure extract belongs to project
        if ($extract->project_id !== $project->id) {
            abort(404);
        }

        // Only draft extracts can be edited
        if ($extract->status !== 'draft') {
            return redirect()->route('projects.show', $project)
                ->with('error', 'لا يمكن تعديل المستخلص إلا في حالة المسودة');
        }

        $validated = $request->validate([
            'extract_number' => [
                'required',
                'string',
                'max:255',
                'unique:project_extracts,extract_number,' . $extract->id . ',id,project_id,' . $project->id
            ],
            'extract_description' => 'nullable|string|max:1000',
            'extract_date' => 'required|date',
            'extract_status' => 'required|in:draft,submitted,approved,paid',
            'extract_total' => 'required|numeric|min:0',
            'extract_items' => 'required|json',
            'extract_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240'
        ], [
            'extract_number.unique' => 'رقم المستخلص موجود مسبقاً لهذا المشروع',
            'extract_number.required' => 'رقم المستخلص مطلوب',
            'extract_date.required' => 'تاريخ المستخلص مطلوب',
            'extract_total.min' => 'قيمة المستخلص يجب أن تكون أكبر من صفر',
            'extract_items.required' => 'بيانات بنود المستخلص مطلوبة',
            'extract_file.mimes' => 'نوع الملف غير مدعوم',
            'extract_file.max' => 'حجم الملف كبير جداً (الحد الأقصى: 10 ميجابايت)',
        ]);

        try {
            // Handle file upload
            $filePath = $extract->file_path;
            if ($request->hasFile('extract_file')) {
                // Delete old file if exists
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }

                $file = $request->file('extract_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('project_extracts', $fileName, 'public');
            }

            // Parse extract items
            $extractItems = json_decode($validated['extract_items'], true);

            // حساب الضريبة والقيمة الإجمالية مع الضريبة
            $taxRate = $request->input('tax_rate', 15); // افتراضياً 15%
            $taxAmount = $validated['extract_total'] * ($taxRate / 100);
            $totalWithTax = $validated['extract_total'] + $taxAmount;

            // Update the extract record
            $extract->update([
                'extract_number' => $validated['extract_number'],
                'description' => $validated['extract_description'],
                'extract_date' => $validated['extract_date'],
                'status' => $validated['extract_status'],
                'total_amount' => $validated['extract_total'],
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total_with_tax' => $totalWithTax,
                'file_path' => $filePath,
                'items_data' => json_encode($extractItems),
            ]);

            // Delete existing extract items and create new ones
            $extract->extractItems()->delete();
            foreach ($extractItems as $itemData) {
                $extract->extractItems()->create([
                    'project_item_index' => $itemData['item_index'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_value' => $itemData['total_value'],
                ]);
            }

            // إذا تم تغيير حالة المستخلص إلى مدفوع، قم بإنشاء سند قبض
            if ($validated['extract_status'] === 'paid' && $extract->status !== 'paid') {
                $this->createRevenueVoucherFromExtract($extract, $project);
            }

            return redirect()->route('projects.show', $project)
                ->with('success', 'تم تحديث المستخلص بنجاح');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث المستخلص: ' . $e->getMessage());
        }
    }

    /**
     * Delete extract
     */
    public function destroyExtract(Project $project, ProjectExtract $extract)
    {
        // Ensure extract belongs to project
        if ($extract->project_id !== $project->id) {
            abort(404);
        }

        // Only draft extracts can be deleted
        if ($extract->status !== 'draft') {
            return redirect()->route('projects.show', $project)
                ->with('error', 'لا يمكن حذف المستخلص إلا في حالة المسودة');
        }

        try {
            // Delete file if exists
            if ($extract->file_path && Storage::disk('public')->exists($extract->file_path)) {
                Storage::disk('public')->delete($extract->file_path);
            }

            // Delete extract items
            $extract->extractItems()->delete();

            // Delete extract
            $extract->delete();

            return redirect()->route('projects.show', $project)
                ->with('success', 'تم حذف المستخلص بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'حدث خطأ أثناء حذف المستخلص: ' . $e->getMessage());
        }
    }

    /**
     * Extend project duration
     */
    public function extendProject(Request $request, Project $project)
    {
        $validated = $request->validate([
            'new_end_date' => 'required|date|after:' . ($project->end_date ?? now()->format('Y-m-d')),
            'extension_reason' => 'required|string|max:1000',
        ], [
            'new_end_date.required' => 'تاريخ الانتهاء الجديد مطلوب',
            'new_end_date.after' => 'تاريخ الانتهاء الجديد يجب أن يكون بعد التاريخ الحالي',
            'extension_reason.required' => 'سبب التمديد مطلوب',
            'extension_reason.max' => 'سبب التمديد لا يجب أن يزيد عن 1000 حرف',
        ]);

        try {
            $oldEndDate = $project->end_date;

            // Create extension record
            ProjectExtension::create([
                'project_id' => $project->id,
                'old_end_date' => $oldEndDate,
                'new_end_date' => $validated['new_end_date'],
                'extension_reason' => $validated['extension_reason'],
                'extended_by' => Auth::id(),
            ]);

            // Update project end date
            $project->update(['end_date' => $validated['new_end_date']]);

            return redirect()->route('projects.show', $project)
                ->with('success', 'تم تمديد فترة المشروع بنجاح من ' . ($oldEndDate ? \Carbon\Carbon::parse($oldEndDate)->format('Y-m-d') : 'غير محدد') . ' إلى ' . \Carbon\Carbon::parse($validated['new_end_date'])->format('Y-m-d'));
        } catch (\Exception $e) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'حدث خطأ أثناء تمديد فترة المشروع: ' . $e->getMessage());
        }
    }

    /**
     * Store project visit
     */
    public function storeVisit(Request $request, Project $project)
    {
        try {
            $validated = $request->validate([
                'visit_date' => 'required|date',
                'visit_time' => 'nullable|date_format:H:i',
                'visitor_name' => 'required|string|max:255',
                'visit_type' => 'required|in:inspection,meeting,supervision,coordination,other',
                'visit_notes' => 'required|string|max:2000',
                'purpose' => 'nullable|string|max:500',
                'duration_hours' => 'nullable|numeric|min:0|max:24',
            ], [
                'visit_date.required' => 'تاريخ الزيارة مطلوب',
                'visitor_name.required' => 'اسم الزائر مطلوب',
                'visitor_name.max' => 'اسم الزائر لا يجب أن يزيد عن 255 حرف',
                'visit_type.required' => 'نوع الزيارة مطلوب',
                'visit_notes.required' => 'تفاصيل الزيارة مطلوبة',
                'visit_notes.max' => 'تفاصيل الزيارة لا يجب أن تزيد عن 2000 حرف',
                'duration_hours.numeric' => 'المدة يجب أن تكون رقم',
                'duration_hours.max' => 'المدة لا تتجاوز 24 ساعة',
            ]);

            // Optional employee linking: try to find employee by exact name (can be improved later)
            $visitorId = null;
            if ($request->filled('visitor_employee_id')) {
                $visitorId = (int) $request->input('visitor_employee_id');
            }

            ProjectVisit::create([
                'project_id' => $project->id,
                'visit_date' => $validated['visit_date'],
                'visit_time' => $validated['visit_time'],
                'visitor_id' => $visitorId,
                'visitor_name' => $validated['visitor_name'],
                'visit_type' => $validated['visit_type'],
                'visit_notes' => $validated['visit_notes'],
                'duration_hours' => $validated['duration_hours'] ?? null,
                'purpose' => $validated['purpose'] ?? null,
                'notes' => $validated['visit_notes'],
                'recorded_by' => Auth::id(),
            ]);

            $visitTypeLabels = [
                'inspection' => 'جولة تفتيش',
                'meeting' => 'اجتماع',
                'supervision' => 'إشراف',
                'coordination' => 'تنسيق',
                'other' => 'أخرى'
            ];

            return redirect()->route('projects.show', $project)
                ->with('success', 'تم تسجيل زيارة ' . $visitTypeLabels[$validated['visit_type']] . ' للمشروع بواسطة ' . $validated['visitor_name'] . ' بتاريخ ' . \Carbon\Carbon::parse($validated['visit_date'])->format('Y-m-d'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Project visit store failed', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('projects.show', $project)
                ->with('error', 'حدث خطأ أثناء تسجيل الزيارة. الرجاء مراجعة السجلات.');
        }
    }

    /**
     * Store rental equipment for project
     */
    public function storeRental(Request $request, Project $project)
    {
        $validated = $request->validate([
            'equipment_type' => 'required|string|max:100',
            'equipment_name' => 'required|string|max:255',
            'rental_company' => 'required|string|max:255',
            'rental_start_date' => 'required|date',
            'rental_end_date' => 'nullable|date|after:rental_start_date',
            'daily_rate' => 'nullable|numeric|min:0',
            'currency' => 'nullable|in:SAR,USD,EUR',
            'notes' => 'nullable|string|max:1000',
        ], [
            'equipment_type.required' => 'نوع المعدة مطلوب',
            'equipment_name.required' => 'اسم/رقم المعدة مطلوب',
            'rental_company.required' => 'المورد/الشركة المؤجرة مطلوب',
            'rental_start_date.required' => 'تاريخ بداية الإيجار مطلوب',
            'rental_end_date.after' => 'تاريخ نهاية الإيجار يجب أن يكون بعد تاريخ البداية',
            'daily_rate.min' => 'تكلفة الإيجار لا يمكن أن تكون سالبة',
            'notes.max' => 'الملاحظات لا يجب أن تزيد عن 1000 حرف',
        ]);

        try {
            // Create rental equipment record
            ProjectRentalEquipment::create([
                'project_id' => $project->id,
                'equipment_type' => $validated['equipment_type'],
                'equipment_name' => $validated['equipment_name'],
                'rental_company' => $validated['rental_company'],
                'rental_start_date' => $validated['rental_start_date'],
                'rental_end_date' => $validated['rental_end_date'],
                'daily_rate' => $validated['daily_rate'],
                'currency' => $validated['currency'] ?? 'SAR',
                'notes' => $validated['notes'],
                'recorded_by' => Auth::id(),
            ]);

            $equipmentTypeLabels = [
                'excavator' => 'حفار',
                'bulldozer' => 'جرافة',
                'crane' => 'رافعة',
                'truck' => 'شاحنة',
                'concrete_mixer' => 'خلاطة خرسانة',
                'generator' => 'مولد كهرباء',
                'compressor' => 'ضاغط هواء',
                'other' => 'أخرى'
            ];

            $equipmentLabel = $equipmentTypeLabels[$validated['equipment_type']] ?? $validated['equipment_type'];
            $startDate = \Carbon\Carbon::parse($validated['rental_start_date'])->format('Y-m-d');

            return redirect()->route('projects.show', $project)
                ->with('success', 'تم تسجيل ' . $equipmentLabel . ' (' . $validated['equipment_name'] . ') كمعدة مستأجرة للمشروع من شركة ' . $validated['rental_company'] . ' بتاريخ ' . $startDate);
        } catch (\Exception $e) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'حدث خطأ أثناء تسجيل المعدة المستأجرة: ' . $e->getMessage());
        }
    }

    /**
     * Store project loan
     */
    public function storeLoan(Request $request, Project $project)
    {
        $validated = $request->validate([
            'loan_amount' => 'required|numeric|min:0',
            'loan_source' => 'required|in:bank,company,individual,government,other',
            'lender_name' => 'required|string|max:255',
            'loan_date' => 'required|date',
            'due_date' => 'nullable|date|after:loan_date',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'interest_type' => 'nullable|in:fixed,variable',
            'loan_purpose' => 'required|in:equipment,materials,wages,operations,expansion,other',
            'notes' => 'nullable|string|max:2000',
        ], [
            'loan_amount.required' => 'مبلغ القرض مطلوب',
            'loan_amount.min' => 'مبلغ القرض لا يمكن أن يكون سالباً',
            'loan_source.required' => 'مصدر القرض مطلوب',
            'lender_name.required' => 'اسم الجهة المقرضة مطلوب',
            'loan_date.required' => 'تاريخ القرض مطلوب',
            'due_date.after' => 'تاريخ الاستحقاق يجب أن يكون بعد تاريخ القرض',
            'interest_rate.max' => 'معدل الفائدة لا يمكن أن يزيد عن 100%',
            'loan_purpose.required' => 'الغرض من القرض مطلوب',
            'notes.max' => 'الملاحظات لا يجب أن تزيد عن 2000 حرف',
        ]);

        try {
            // Create loan record
            ProjectLoan::create([
                'project_id' => $project->id,
                'loan_amount' => $validated['loan_amount'],
                'loan_source' => $validated['loan_source'],
                'lender_name' => $validated['lender_name'],
                'loan_date' => $validated['loan_date'],
                'due_date' => $validated['due_date'],
                'interest_rate' => $validated['interest_rate'],
                'interest_type' => $validated['interest_type'],
                'loan_purpose' => $validated['loan_purpose'],
                'notes' => $validated['notes'],
                'status' => 'active',
                'recorded_by' => Auth::id(),
            ]);

            $loanSourceLabels = [
                'bank' => 'بنك',
                'company' => 'شركة',
                'individual' => 'فرد',
                'government' => 'جهة حكومية',
                'other' => 'أخرى'
            ];

            $sourceLabel = $loanSourceLabels[$validated['loan_source']] ?? $validated['loan_source'];
            $loanDate = \Carbon\Carbon::parse($validated['loan_date'])->format('Y-m-d');
            $formattedAmount = number_format($validated['loan_amount'], 2);

            return redirect()->route('projects.show', $project)
                ->with('success', 'تم تسجيل قرض بمبلغ ' . $formattedAmount . ' ر.س من ' . $sourceLabel . ' (' . $validated['lender_name'] . ') بتاريخ ' . $loanDate);
        } catch (\Exception $e) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'حدث خطأ أثناء تسجيل القرض: ' . $e->getMessage());
        }
    }

    /**
     * Update project progress
     */
    public function updateProgress(Request $request, Project $project)
    {
        try {
            $validated = $request->validate([
                'progress' => 'required|numeric|min:0|max:100',
                'update_notes' => 'nullable|string|max:1000',
            ], [
                'progress.required' => 'نسبة الإنجاز مطلوبة',
                'progress.numeric' => 'نسبة الإنجاز يجب أن تكون رقماً',
                'progress.min' => 'نسبة الإنجاز لا يمكن أن تكون أقل من 0%',
                'progress.max' => 'نسبة الإنجاز لا يمكن أن تتجاوز 100%',
                'update_notes.max' => 'الملاحظات لا يمكن أن تتجاوز 1000 حرف',
            ]);

            $oldProgress = $project->progress;
            $newProgress = $validated['progress'];

            // Update project progress
            $project->update([
                'progress' => $newProgress
            ]);

            // Determine status based on progress
            if ($newProgress == 100) {
                $project->update(['status' => 'completed']);
            } elseif ($newProgress > 0 && $project->status == 'planning') {
                $project->update(['status' => 'active']);
            }

            // Create a progress update record (you might want to create a separate model for this)
            // For now, we'll just add it to project notes or create a simple log

            $progressChange = $newProgress - $oldProgress;
            $changeDirection = $progressChange > 0 ? 'زيادة' : 'تقليل';
            $changeAmount = abs($progressChange);

            $logMessage = "تم تحديث نسبة الإنجاز من {$oldProgress}% إلى {$newProgress}% ({$changeDirection} {$changeAmount}%)";

            if (!empty($validated['update_notes'])) {
                $logMessage .= " - الملاحظات: " . $validated['update_notes'];
            }

            $logMessage .= " بواسطة: " . Auth::user()->name . " في " . now()->format('Y-m-d H:i');

            // You could log this to a separate progress_updates table
            // For now, we'll use the success message

            $successMessage = "تم تحديث نسبة الإنجاز بنجاح من {$oldProgress}% إلى {$newProgress}%";

            if ($newProgress == 100) {
                $successMessage .= " - تهانينا! تم إكمال المشروع 🎉";
            } elseif ($newProgress >= 75) {
                $successMessage .= " - المشروع قارب على الانتهاء 📈";
            } elseif ($newProgress >= 50) {
                $successMessage .= " - المشروع في منتصف الطريق 💪";
            } elseif ($newProgress >= 25) {
                $successMessage .= " - تقدم جيد في المشروع ✨";
            }

            return redirect()->route('projects.show', $project)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'حدث خطأ أثناء تحديث نسبة الإنجاز: ' . $e->getMessage());
        }
    }

    /**
     * Store a new rental equipment for the project.
     */
    /**
     * Update rental equipment information.
     */
    public function updateRental(Request $request, Project $project, ProjectRentalEquipment $rental)
    {
        $validated = $request->validate([
            'equipment_type' => 'required|string|max:255',
            'equipment_number' => 'required|string|max:255',
            'rental_company' => 'required|string|max:255',
            'daily_rate' => 'required|numeric|min:0',
            'rental_start_date' => 'required|date',
            'rental_end_date' => 'nullable|date|after_or_equal:rental_start_date',
            'notes' => 'nullable|string'
        ]);

        $rental->update($validated);

        return redirect()->back()->with('success', 'تم تحديث بيانات المعدة المستأجرة بنجاح');
    }

    /**
     * Remove rental equipment from the project.
     */
    public function destroyRental(Project $project, ProjectRentalEquipment $rental)
    {
        $rental->delete();
        return redirect()->back()->with('success', 'تم حذف المعدة المستأجرة بنجاح');
    }

    public function storeItems(Request $request, Project $project)
    {
        $validated = $request->validate([
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
            'items.*.total_with_tax' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($validated['items'] as $itemData) {
            // if client passed total_with_tax, use it; otherwise compute using provided tax_rate or default 0
            if (empty($itemData['total_with_tax'])) {
                $taxRate = $validated['tax_rate'] ?? ($request->input('tax_rate') ?? 0);
                $itemData['total_with_tax'] = $itemData['total_price'] + ($itemData['total_price'] * ($taxRate / 100));
            }

            $project->projectItems()->create([
                'name' => $itemData['name'],
                'quantity' => $itemData['quantity'],
                'unit' => $itemData['unit'],
                'unit_price' => $itemData['unit_price'],
                'total_price' => $itemData['total_price'],
                'total_with_tax' => $itemData['total_with_tax'],
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم حفظ البنود بنجاح']);
        }

        return redirect()->route('projects.extract.create', $project)
            ->with('success', 'تم حفظ البنود بنجاح');
    }

    /**
     * Store new images for the project
     */
    public function storeImages(Request $request, Project $project)
    {
        // Log incoming request details
        Log::info('Image upload request received', [
            'project_id' => $project->id,
            'project_name' => $project->name,
            'is_ajax' => $request->ajax(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'files_count' => $request->hasFile('images') ? count($request->file('images')) : 0,
            'request_headers' => $request->headers->all()
        ]);

        try {
            $this->checkEngineerAccess($project, 'إضافة صور إلى');
        } catch (\Exception $e) {
            Log::error('Engineer access check failed', ['error' => $e->getMessage()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مخول بإضافة صور لهذا المشروع'
                ], 403);
            }

            abort(403, 'غير مخول بإضافة صور لهذا المشروع');
        }

        try {
            $request->validate([
                'images' => 'required|array|min:1|max:10',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per image
                'description' => 'nullable|string|max:1000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for image upload', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $e->errors()
                ], 422);
            }

            throw $e;
        }

        $uploadedImages = [];
        $images = $request->file('images');

        Log::info('Starting image processing', ['images_count' => count($images)]);

        foreach ($images as $index => $image) {
            try {
                Log::info("Processing image {$index}", [
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize()
                ]);

                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store the image
                $path = $image->storeAs('projects/' . $project->id . '/images', $filename, 'public');

                if ($path) {
                    $uploadedImages[] = $path;
                    Log::info("Image {$index} uploaded successfully", ['path' => $path]);
                } else {
                    Log::error("Failed to store image {$index}");
                }
            } catch (\Exception $e) {
                Log::error("Error uploading individual project image {$index}: " . $e->getMessage());
                continue;
            }
        }

        Log::info('Image processing complete', [
            'total_processed' => count($images),
            'successfully_uploaded' => count($uploadedImages)
        ]);

        if (!empty($uploadedImages)) {
            try {
                // Create ProjectImage records for each uploaded image
                foreach ($uploadedImages as $imagePath) {
                    $projectImage = $project->projectImages()->create([
                        'image_path' => $imagePath,
                        'alt_text' => $request->description ?: null
                    ]);
                    Log::info('ProjectImage record created', ['id' => $projectImage->id, 'path' => $imagePath]);
                }

                // Log the activity
                Log::info("تم رفع " . count($uploadedImages) . " صورة جديدة للمشروع: " . $project->name);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'تم رفع ' . count($uploadedImages) . ' صورة بنجاح',
                        'uploaded_count' => count($uploadedImages)
                    ]);
                }

                return redirect()->route('projects.show', $project)
                    ->with('success', 'تم رفع ' . count($uploadedImages) . ' صورة بنجاح');
            } catch (\Exception $e) {
                Log::error('Error creating ProjectImage records', ['error' => $e->getMessage()]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'فشل في حفظ بيانات الصور في قاعدة البيانات'
                    ], 500);
                }

                return redirect()->route('projects.show', $project)
                    ->with('error', 'فشل في حفظ بيانات الصور في قاعدة البيانات');
            }
        }

        Log::error('No images were uploaded successfully');

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في رفع الصور. يرجى المحاولة مرة أخرى.'
            ], 500);
        }

        return redirect()->route('projects.show', $project)
            ->with('error', 'فشل في رفع الصور. يرجى المحاولة مرة أخرى.');
    }

    /**
     * Update delivery request
     */
    public function updateDeliveryRequest(Request $request, Project $project, $deliveryRequest)
    {
        try {
            $validated = $request->validate([
                'request_number' => 'required|string|max:255'
            ]);

            // Find the delivery request
            $delivery = $project->deliveryRequests()->findOrFail($deliveryRequest);

            // Update the request number
            $delivery->update(['request_number' => $validated['request_number']]);

            Log::info('تم تحديث رقم طلب الاستلام', [
                'project_id' => $project->id,
                'delivery_id' => $delivery->id,
                'request_number' => $validated['request_number']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث رقم الطلب بنجاح',
                'data' => $delivery
            ]);
        } catch (\Exception $e) {
            Log::error('خطأ في تحديث رقم الطلب', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في تحديث رقم الطلب: ' . $e->getMessage()
            ], 500);
        }
    }
}
