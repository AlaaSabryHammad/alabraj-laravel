<?php

namespace App\Http\Controllers;

use App\Models\DamagedPartsReceipt;
use App\Models\Project;
use App\Models\Equipment;
use App\Models\SparePart;
use App\Models\Employee;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class DamagedPartsReceiptController extends Controller
{
    /**
     * عرض قائمة استلام القطع التالفة
     */
    public function index(): View
    {
        $receipts = DamagedPartsReceipt::with([
            'project',
            'equipment',
            'sparePart',
            'receivedByEmployee',
            'warehouse'
        ])
            ->orderBy('receipt_date', 'desc')
            ->paginate(20);

        return view('damaged-parts-receipts.index', compact('receipts'));
    }

    /**
     * عرض نموذج إنشاء استلام قطعة تالفة جديدة
     */
    public function create(): View
    {
        $projects = Project::active()->get();
        $equipment = Equipment::active()->get();
        $spareParts = SparePart::active()->get();
        $employees = Employee::active()->get();
        $warehouses = Warehouse::where('status', 'active')->get();

        return view('damaged-parts-receipts.create', compact(
            'projects',
            'equipment',
            'spareParts',
            'employees',
            'warehouses'
        ));
    }

    /**
     * حفظ استلام قطعة تالفة جديدة
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'receipt_date' => 'required|date',
            'receipt_time' => 'required',
            'project_id' => 'required|exists:projects,id',
            'equipment_id' => 'nullable|exists:equipment,id',
            'spare_part_id' => 'required|exists:spare_parts,id',
            'quantity_received' => 'required|integer|min:1',
            'damage_condition' => 'required|in:repairable,non_repairable,replacement_needed,for_evaluation',
            'damage_description' => 'nullable|string',
            'damage_cause' => 'nullable|string',
            'technician_notes' => 'nullable|string',
            'received_by' => 'required|exists:employees,id',
            'sent_by' => 'nullable|exists:employees,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'storage_location' => 'nullable|string',
            'estimated_repair_cost' => 'nullable|numeric|min:0',
            'replacement_cost' => 'nullable|numeric|min:0',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max per image
            'documents.*' => 'nullable|mimes:pdf,doc,docx|max:10240', // 10MB max per document
        ]);

        // معالجة الصور
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('damaged-parts/photos', 'public');
                $photos[] = $path;
            }
        }

        // معالجة المستندات
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('damaged-parts/documents', 'public');
                $documents[] = [
                    'path' => $path,
                    'original_name' => $document->getClientOriginalName(),
                    'size' => $document->getSize()
                ];
            }
        }

        $validated['photos'] = $photos;
        $validated['documents'] = $documents;

        $receipt = DamagedPartsReceipt::create($validated);

        return redirect()
            ->route('damaged-parts-receipts.show', $receipt)
            ->with('success', 'تم حفظ استلام القطعة التالفة بنجاح.');
    }

    /**
     * عرض تفاصيل استلام قطعة تالفة
     */
    public function show(DamagedPartsReceipt $damagedPartsReceipt): View
    {
        $damagedPartsReceipt->load([
            'project',
            'equipment',
            'sparePart',
            'receivedByEmployee',
            'sentByEmployee',
            'warehouse'
        ]);

        return view('damaged-parts-receipts.show', compact('damagedPartsReceipt'));
    }

    /**
     * عرض نموذج تعديل استلام قطعة تالفة
     */
    public function edit(DamagedPartsReceipt $damagedPartsReceipt): View
    {
        $projects = Project::active()->get();
        $equipment = Equipment::active()->get();
        $spareParts = SparePart::active()->get();
        $employees = Employee::active()->get();
        $warehouses = Warehouse::where('status', 'active')->get();

        return view('damaged-parts-receipts.edit', compact(
            'damagedPartsReceipt',
            'projects',
            'equipment',
            'spareParts',
            'employees',
            'warehouses'
        ));
    }

    /**
     * تحديث استلام قطعة تالفة
     */
    public function update(Request $request, DamagedPartsReceipt $damagedPartsReceipt): RedirectResponse
    {
        $validated = $request->validate([
            'receipt_date' => 'required|date',
            'receipt_time' => 'required',
            'project_id' => 'required|exists:projects,id',
            'equipment_id' => 'nullable|exists:equipment,id',
            'spare_part_id' => 'required|exists:spare_parts,id',
            'quantity_received' => 'required|integer|min:1',
            'damage_condition' => 'required|in:repairable,non_repairable,replacement_needed,for_evaluation',
            'damage_description' => 'nullable|string',
            'damage_cause' => 'nullable|string',
            'technician_notes' => 'nullable|string',
            'received_by' => 'required|exists:employees,id',
            'sent_by' => 'nullable|exists:employees,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'storage_location' => 'nullable|string',
            'estimated_repair_cost' => 'nullable|numeric|min:0',
            'replacement_cost' => 'nullable|numeric|min:0',
            'processing_status' => 'required|in:received,under_evaluation,approved_repair,approved_replace,disposed,returned_fixed',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'documents.*' => 'nullable|mimes:pdf,doc,docx|max:10240',
        ]);

        // معالجة الصور الجديدة
        $existingPhotos = $damagedPartsReceipt->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('damaged-parts/photos', 'public');
                $existingPhotos[] = $path;
            }
        }

        // معالجة المستندات الجديدة
        $existingDocuments = $damagedPartsReceipt->documents ?? [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('damaged-parts/documents', 'public');
                $existingDocuments[] = [
                    'path' => $path,
                    'original_name' => $document->getClientOriginalName(),
                    'size' => $document->getSize()
                ];
            }
        }

        $validated['photos'] = $existingPhotos;
        $validated['documents'] = $existingDocuments;

        $damagedPartsReceipt->update($validated);

        return redirect()
            ->route('damaged-parts-receipts.show', $damagedPartsReceipt)
            ->with('success', 'تم تحديث استلام القطعة التالفة بنجاح.');
    }

    /**
     * حذف استلام قطعة تالفة
     */
    public function destroy(DamagedPartsReceipt $damagedPartsReceipt): RedirectResponse
    {
        // حذف الملفات المرفقة
        if ($damagedPartsReceipt->photos) {
            foreach ($damagedPartsReceipt->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        if ($damagedPartsReceipt->documents) {
            foreach ($damagedPartsReceipt->documents as $document) {
                Storage::disk('public')->delete($document['path']);
            }
        }

        $damagedPartsReceipt->delete();

        return redirect()
            ->route('damaged-parts-receipts.index')
            ->with('success', 'تم حذف استلام القطعة التالفة بنجاح.');
    }

    /**
     * تحديث حالة المعالجة
     */
    public function updateStatus(Request $request, DamagedPartsReceipt $damagedPartsReceipt): RedirectResponse
    {
        $validated = $request->validate([
            'processing_status' => 'required|in:received,under_evaluation,approved_repair,approved_replace,disposed,returned_fixed',
            'evaluation_date' => 'nullable|date',
            'decision_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'technician_notes' => 'nullable|string'
        ]);

        $damagedPartsReceipt->update($validated);

        return back()->with('success', 'تم تحديث حالة المعالجة بنجاح.');
    }
}
