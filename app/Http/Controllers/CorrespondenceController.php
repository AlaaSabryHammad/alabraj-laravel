<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Correspondence;
use App\Models\Employee;
use App\Models\Project;

class CorrespondenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Correspondence::with(['project', 'assignedEmployee', 'user'])
                                ->withCount('replies')
                                ->latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhere('external_number', 'like', "%{$search}%")
                  ->orWhere('from_entity', 'like', "%{$search}%")
                  ->orWhere('to_entity', 'like', "%{$search}%");
            });
        }

        $correspondences = $query->paginate(15);
        $projects = Project::orderBy('name')->get();

        return view('correspondences.index', compact('correspondences', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'incoming');
        $selectedProjectId = $request->get('project_id');
        $employees = Employee::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        // Generate reference number
        $referenceNumber = Correspondence::generateReferenceNumber($type);

        return view('correspondences.create', compact('type', 'employees', 'projects', 'referenceNumber', 'selectedProjectId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:incoming,outgoing',
            'external_number' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'from_entity' => 'required_if:type,incoming|nullable|string|max:255',
            'to_entity' => 'required_if:type,outgoing|nullable|string|max:255',
            'correspondence_date' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'required_if:type,incoming|nullable|exists:employees,id',
        ], [
            'type.required' => 'نوع المراسلة مطلوب',
            'subject.required' => 'موضوع المراسلة مطلوب',
            'from_entity.required_if' => 'جهة الإصدار مطلوبة للمراسلات الواردة',
            'to_entity.required_if' => 'الجهة المرسل إليها مطلوبة للمراسلات الصادرة',
            'correspondence_date.required' => 'تاريخ المراسلة مطلوب',
            'priority.required' => 'درجة الأهمية مطلوبة',
            'assigned_to.required_if' => 'المسؤول المكلف مطلوب للمراسلات الواردة',
            'file.mimes' => 'نوع الملف غير مدعوم',
            'file.max' => 'حجم الملف كبير جداً (الحد الأقصى: 10 ميجابايت)',
        ]);

        try {
            // Generate reference number
            $referenceNumber = Correspondence::generateReferenceNumber($validated['type']);

            // Handle file upload
            $filePath = null;
            $fileName = null;
            $fileSize = null;
            $fileType = null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getClientMimeType();

                $fileStoreName = time() . '_' . $fileName;
                $filePath = $file->storeAs('correspondences', $fileStoreName, 'public');
            }

            // Create correspondence
            $correspondence = Correspondence::create([
                'type' => $validated['type'],
                'reference_number' => $referenceNumber,
                'external_number' => $validated['external_number'] ?? null,
                'subject' => $validated['subject'],
                'from_entity' => $validated['from_entity'] ?? null,
                'to_entity' => $validated['to_entity'] ?? null,
                'correspondence_date' => $validated['correspondence_date'],
                'priority' => $validated['priority'],
                'notes' => $validated['notes'] ?? null,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_type' => $fileType,
                'project_id' => $validated['project_id'] ?? null,
                'assigned_to' => $validated['assigned_to'] ?? null,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('correspondences.index')
                ->with('success', 'تم إضافة المراسلة بنجاح');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ المراسلة: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Correspondence $correspondence)
    {
        $correspondence->load(['project', 'assignedEmployee', 'user', 'replies.user']);

        return view('correspondences.show', compact('correspondence'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Correspondence $correspondence)
    {
        $employees = Employee::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('correspondences.edit', compact('correspondence', 'employees', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Correspondence $correspondence)
    {
        $validated = $request->validate([
            'external_number' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'from_entity' => 'required_if:type,' . $correspondence->type . ',incoming|nullable|string|max:255',
            'to_entity' => 'required_if:type,' . $correspondence->type . ',outgoing|nullable|string|max:255',
            'correspondence_date' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'required_if:type,' . $correspondence->type . ',incoming|nullable|exists:employees,id',
        ], [
            'subject.required' => 'موضوع المراسلة مطلوب',
            'from_entity.required_if' => 'جهة الإصدار مطلوبة للمراسلات الواردة',
            'to_entity.required_if' => 'الجهة المرسل إليها مطلوبة للمراسلات الصادرة',
            'correspondence_date.required' => 'تاريخ المراسلة مطلوب',
            'priority.required' => 'درجة الأهمية مطلوبة',
            'assigned_to.required_if' => 'المسؤول المكلف مطلوب للمراسلات الواردة',
            'file.mimes' => 'نوع الملف غير مدعوم',
            'file.max' => 'حجم الملف كبير جداً (الحد الأقصى: 10 ميجابايت)',
        ]);

        try {
            // Handle file upload if new file is provided
            $filePath = $correspondence->file_path;
            $fileName = $correspondence->file_name;
            $fileSize = $correspondence->file_size;
            $fileType = $correspondence->file_type;

            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }

                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getClientMimeType();

                $fileStoreName = time() . '_' . $fileName;
                $filePath = $file->storeAs('correspondences', $fileStoreName, 'public');
            }

            // Update correspondence
            $correspondence->update([
                'external_number' => $validated['external_number'] ?? null,
                'subject' => $validated['subject'],
                'from_entity' => $validated['from_entity'] ?? null,
                'to_entity' => $validated['to_entity'] ?? null,
                'correspondence_date' => $validated['correspondence_date'],
                'priority' => $validated['priority'],
                'notes' => $validated['notes'] ?? null,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_type' => $fileType,
                'project_id' => $validated['project_id'] ?? null,
                'assigned_to' => $validated['assigned_to'] ?? null,
            ]);

            return redirect()->route('correspondences.show', $correspondence)
                ->with('success', 'تم تحديث المراسلة بنجاح');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث المراسلة: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Correspondence $correspondence)
    {
        try {
            // Delete file if exists
            if ($correspondence->file_path && Storage::disk('public')->exists($correspondence->file_path)) {
                Storage::disk('public')->delete($correspondence->file_path);
            }

            $correspondence->delete();

            return redirect()->route('correspondences.index')
                ->with('success', 'تم حذف المراسلة بنجاح');

        } catch (\Exception $e) {
            return redirect()->route('correspondences.index')
                ->with('error', 'حدث خطأ أثناء حذف المراسلة: ' . $e->getMessage());
        }
    }

    /**
     * Download correspondence file
     */
    public function download(Correspondence $correspondence)
    {
        if (!$correspondence->file_path || !Storage::disk('public')->exists($correspondence->file_path)) {
            return back()->with('error', 'الملف غير موجود');
        }

        $filePath = storage_path('app/public/' . $correspondence->file_path);
        return response()->download($filePath, $correspondence->file_name);
    }
}
