<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::latest()->paginate(10);
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
        // Load all relationships including project items
        $project->load([
            'projectManager',
            'projectFiles',
            'projectImages',
            'deliveryRequests',
            'projectItems'
        ]);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Load relationships for editing including project items
        $project->load(['projectImages', 'projectItems']);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'project_manager' => 'required|string|max:255',
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

        $project->update($validated);

        // Handle new images upload
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $imagePath = $image->store('projects/images', 'public');

                $project->projectImages()->create([
                    'image_path' => $imagePath,
                    'name' => $image->getClientOriginalName()
                ]);
            }
        }

        // Handle deleted items
        if ($request->has('deleted_items') && is_array($request->deleted_items)) {
            foreach ($request->deleted_items as $itemId) {
                $project->projectItems()->where('id', $itemId)->delete();
            }
        }

        // Handle existing items updates
        if ($request->has('existing_items')) {
            foreach ($request->existing_items as $itemId => $itemData) {
                if (!empty($itemData['name']) && !empty($itemData['quantity']) && !empty($itemData['unit_price'])) {
                    $project->projectItems()->where('id', $itemId)->update([
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

        // Handle new items
        if ($request->has('new_items')) {
            foreach ($request->new_items as $itemData) {
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

        return redirect()->route('projects.show', $project)
            ->with('success', 'تم تحديث بيانات المشروع والبنود بنجاح');
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
}
