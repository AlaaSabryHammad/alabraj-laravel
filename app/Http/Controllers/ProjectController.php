<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use App\Models\ProjectExtract;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Clear any model cache and get fresh data
        $projects = Project::with('projectManager')->latest()->paginate(10);

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
        $employees = \App\Models\Employee::where('status', 'active')->get();
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
            'locations.manager'
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
        // Load relationships for editing including project items, extracts, and images
        $project->load([
            'projectImages',
            'projectItems',
            'projectExtracts' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        // Get active employees for project manager dropdown
        $employees = \App\Models\Employee::where('status', 'active')->orderBy('name')->get();

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
            'location' => 'required|string|max:255',
            'project_manager_id' => 'required|exists:employees,id',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'progress' => 'nullable|integer|min:0|max:100',
            // New fields
            'project_number' => 'nullable|string|max:255',
            'government_entity' => 'nullable|string|max:255',
            'consulting_office' => 'nullable|string|max:255',
            'project_scope' => 'nullable|string|max:255',
            // Images
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Project items validation
            'existing_items.*.name' => 'nullable|string|max:255',
            'existing_items.*.quantity' => 'nullable|numeric|min:0',
            'existing_items.*.unit' => 'nullable|string|max:50',
            'existing_items.*.unit_price' => 'nullable|numeric|min:0',
            'existing_items.*.total_price' => 'nullable|numeric|min:0',
            'existing_items.*.total_with_tax' => 'nullable|numeric|min:0',
            'new_items.*.name' => 'nullable|string|max:255',
            'new_items.*.quantity' => 'nullable|numeric|min:0',
            'new_items.*.unit' => 'nullable|string|max:50',
            'new_items.*.unit_price' => 'nullable|numeric|min:0',
            'new_items.*.total_price' => 'nullable|numeric|min:0',
            'new_items.*.total_with_tax' => 'nullable|numeric|min:0',
            'deleted_items.*' => 'nullable|integer|exists:project_items,id',
            'subtotal' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'final_total' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        // Debug: Log all received data
        Log::info('Project Update - All Data:', $validated);
        Log::info('Project Update - Request Data:', $request->all());

        // Debug: Log the status value before update
        Log::info('Project Update - Status received:', ['status' => $validated['status'], 'project_id' => $project->id]);

                // Update project with validated data
        $project->update($validated);

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
                'manager' => $updatedProject->projectManager->name ?? 'غير محدد'
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

            // Create the extract record
            $extract = $project->projectExtracts()->create([
                'extract_number' => $validated['extract_number'],
                'description' => $validated['extract_description'],
                'extract_date' => $validated['extract_date'],
                'status' => $validated['extract_status'],
                'total_amount' => $validated['extract_total'],
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

            return redirect()->route('projects.show', $project)
                ->with('success', 'تم حفظ المستخلص بنجاح');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ المستخلص: ' . $e->getMessage());
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

            // Update the extract record
            $extract->update([
                'extract_number' => $validated['extract_number'],
                'description' => $validated['extract_description'],
                'extract_date' => $validated['extract_date'],
                'status' => $validated['extract_status'],
                'total_amount' => $validated['extract_total'],
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
}
