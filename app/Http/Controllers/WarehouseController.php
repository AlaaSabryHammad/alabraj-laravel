<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\SparePart;
use App\Models\SparePartSerial;
use App\Models\WarehouseInventory;
use App\Models\SparePartTransaction;
use App\Models\Equipment;
use App\Models\Employee;
use App\Models\DamagedPartsReceipt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseController extends Controller
{
    /**
     * عرض جميع المستودعات
     */
    public function index()
    {
        // البحث عن نوع الموقع "مستودع"
        $warehouseTypeId = \App\Models\LocationType::where('name', 'مستودع')->first()?->id;

        if (!$warehouseTypeId) {
            // إذا لم يوجد نوع "مستودع"، ننشئه
            $warehouseType = \App\Models\LocationType::create(['name' => 'مستودع']);
            $warehouseTypeId = $warehouseType->id;
        }

        $warehouses = Location::with(['locationType', 'manager'])
            ->where('location_type_id', $warehouseTypeId)
            ->withCount('inventories')
            ->get();

        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * عرض تفاصيل مستودع محدد
     */
    public function show(Location $warehouse)
    {
        // التأكد من أن الموقع هو مستودع
        $warehouseTypeId = \App\Models\LocationType::where('name', 'مستودع')->first()?->id;

        if ($warehouse->location_type_id !== $warehouseTypeId) {
            abort(404, 'الموقع المطلوب ليس مستودعاً');
        }

        // قطع الغيار الجديدة (الصالحة)
        $newInventory = WarehouseInventory::with(['sparePart.serialNumbers' => function ($query) use ($warehouse) {
            $query->where('location_id', $warehouse->id);
        }])
            ->where('location_id', $warehouse->id)
            ->whereHas('sparePart', function ($query) {
                $query->where('is_active', true)
                    ->whereIn('source', ['new', 'returned']);
            })
            ->get();

        // قطع الغيار التالفة
        $damagedInventory = WarehouseInventory::with(['sparePart.serialNumbers' => function ($query) use ($warehouse) {
            $query->where('location_id', $warehouse->id);
        }])
            ->where('location_id', $warehouse->id)
            ->whereHas('sparePart', function ($query) {
                $query->where('is_active', false)
                    ->where(function ($subQuery) {
                        $subQuery->where('source', 'damaged_replacement')
                            ->orWhere('source', 'returned')
                            ->orWhere('name', 'LIKE', '%(تالفة)%');
                    });
            })
            ->get();

        // للتوافق مع الكود القديم
        $inventory = $newInventory;

        // الحصول على جميع قطع الغيار للنماذج المنبثقة
        $allSpareParts = SparePart::where('is_active', true)->get();

        // الحصول على أنواع قطع الغيار
        $sparePartTypes = \App\Models\SparePartType::all();

        // الحصول على الموردين
        $suppliers = \App\Models\Supplier::all();

        // الحصول على الموظفين
        $employees = \App\Models\Employee::where('status', 'active')->get();

        // الحصول على جميع المواقع (للقوائم المنسدلة)
        $allLocations = Location::select('id', 'name', 'project_id')->get();

        // الحصول على جميع المعدات لربطها بالتصدير
        $equipments = Equipment::where('status', 'active')->get();

        // الحصول على جميع الأرقام التسلسلية للمستودع
        $allSparePartSerials = SparePartSerial::whereHas('sparePart.inventories', function ($query) use ($warehouse) {
            $query->where('location_id', $warehouse->id);
        })
            ->with(['sparePart', 'exportedToEmployee'])
            ->orderBy('created_at', 'desc')
            ->get();

        // الحصول على استلامات قطع الغيار التالفة للمستودع
        $damagedPartsReceipts = DamagedPartsReceipt::with([
            'project',
            'equipment',
            'sparePart',
            'receivedByEmployee',
            'sentByEmployee'
        ])
            ->where('warehouse_id', $warehouse->id)
            ->orderBy('receipt_date', 'desc')
            ->orderBy('receipt_time', 'desc')
            ->paginate(10);

        // تحضير بيانات قطع الغيار للـ JavaScript
        $sparePartsForJson = $newInventory->map(function ($inv) {
            return array(
                'id' => $inv->spare_part_id,
                'name' => $inv->sparePart->name,
                'code' => $inv->sparePart->code,
                'stock' => $inv->current_stock
            );
        })->values()->toArray();

        // تحضير بيانات الموظفين للـ JavaScript
        $employeesForJson = $employees->map(function ($e) {
            return array(
                'id' => $e->id,
                'name' => $e->name,
                'position' => $e->position ?? ''
            );
        })->values()->toArray();

        // تحضير بيانات المواقع للـ JavaScript
        $locationsForJson = $allLocations->map(function ($loc) {
            return array(
                'id' => $loc->id,
                'name' => $loc->name,
                'project_id' => $loc->project_id
            );
        })->values()->toArray();

        return view('warehouses.show', compact('warehouse', 'inventory', 'newInventory', 'damagedInventory', 'allSpareParts', 'sparePartTypes', 'suppliers', 'employees', 'equipments', 'allSparePartSerials', 'damagedPartsReceipts', 'sparePartsForJson', 'employeesForJson', 'locationsForJson'));
    }

    /**
     * عرض صفحة إضافة قطعة غيار جديدة
     */
    public function createSparePart(Location $warehouse)
    {
        return view('warehouses.create-spare-part', compact('warehouse'));
    }

    /**
     * حفظ قطعة غيار جديدة
     */
    public function storeSparePart(Request $request, Location $warehouse)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:spare_parts,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'unit_price' => 'required|numeric|min:0',
            'unit_type' => 'required|string|max:50',
            'minimum_stock' => 'required|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'location_shelf' => 'nullable|string|max:100',
            'initial_quantity' => 'required|integer|min:0',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // إنشاء قطعة الغيار
            $sparePart = SparePart::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'unit_price' => $validated['unit_price'],
                'unit_type' => $validated['unit_type'],
                'minimum_stock' => $validated['minimum_stock'],
                'supplier' => $validated['supplier'],
                'location_shelf' => $validated['location_shelf'],
            ]);

            // إنشاء سجل المخزون إذا كانت الكمية الأولية أكبر من صفر
            if ($validated['initial_quantity'] > 0) {
                $inventory = WarehouseInventory::create([
                    'spare_part_id' => $sparePart->id,
                    'location_id' => $warehouse->id,
                    'current_stock' => $validated['initial_quantity'],
                    'available_stock' => $validated['initial_quantity'],
                    'average_cost' => $validated['unit_price'],
                    'total_value' => $validated['initial_quantity'] * $validated['unit_price'],
                    'last_transaction_date' => now(),
                    'location_shelf' => $validated['location_shelf'],
                ]);

                // إنشاء معاملة الاستلام الأولية
                SparePartTransaction::create([
                    'spare_part_id' => $sparePart->id,
                    'location_id' => $warehouse->id,
                    'user_id' => Auth::id(),
                    'transaction_type' => 'استلام',
                    'quantity' => $validated['initial_quantity'],
                    'unit_price' => $validated['unit_price'],
                    'total_amount' => $validated['initial_quantity'] * $validated['unit_price'],
                    'reference_number' => $validated['reference_number'],
                    'notes' => $validated['notes'] ?? 'استلام أولي لقطعة غيار جديدة',
                    'transaction_date' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('warehouses.show', $warehouse)
                ->with('success', 'تم إضافة قطعة الغيار بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة قطعة الغيار: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة استلام قطع غيار
     */
    public function receiveSpares(Location $warehouse)
    {
        $spareParts = SparePart::where('is_active', true)->orderBy('name')->get();
        return view('warehouses.receive-spares', compact('warehouse', 'spareParts'));
    }

    /**
     * معالجة استلام قطع الغيار
     */
    public function storeReceive(Request $request, Location $warehouse)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.spare_part_id' => 'required|exists:spare_parts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                $sparePart = SparePart::findOrFail($item['spare_part_id']);
                $totalAmount = $item['quantity'] * $sparePart->unit_price;

                // البحث عن سجل المخزون أو إنشاء واحد جديد
                $inventory = WarehouseInventory::firstOrCreate([
                    'spare_part_id' => $sparePart->id,
                    'location_id' => $warehouse->id,
                ], [
                    'current_stock' => 0,
                    'available_stock' => 0,
                    'average_cost' => $sparePart->unit_price,
                    'total_value' => 0,
                ]);

                // حساب متوسط التكلفة الجديد
                $newStock = $inventory->current_stock + $item['quantity'];
                $newTotalValue = ($inventory->total_value) + $totalAmount;
                $newAverageCost = $newStock > 0 ? $newTotalValue / $newStock : $sparePart->unit_price;

                // تحديث المخزون
                $inventory->update([
                    'current_stock' => $newStock,
                    'available_stock' => $inventory->available_stock + $item['quantity'],
                    'average_cost' => $newAverageCost,
                    'total_value' => $newTotalValue,
                    'last_transaction_date' => now(),
                ]);

                // إنشاء معاملة الاستلام
                SparePartTransaction::create([
                    'spare_part_id' => $sparePart->id,
                    'location_id' => $warehouse->id,
                    'transaction_type' => 'استلام',
                    'quantity' => $item['quantity'],
                    'unit_price' => $sparePart->unit_price,
                    'total_amount' => $totalAmount,
                    'notes' => $item['notes'],
                    'user_id' => Auth::id(),
                    'transaction_date' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('warehouses.show', $warehouse)
                ->with('success', 'تم استلام قطع الغيار بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء استلام قطع الغيار: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة تصدير قطع الغيار
     */
    public function exportSpares(Location $warehouse)
    {
        $inventories = WarehouseInventory::with('sparePart')
            ->where('location_id', $warehouse->id)
            ->where('available_stock', '>', 0)
            ->get();

        $equipment = Equipment::orderBy('name')->get();

        return view('warehouses.export-spares', compact('warehouse', 'inventories', 'equipment'));
    }

    /**
     * معالجة تصدير قطع الغيار
     */
    public function storeExport(Request $request, Location $warehouse)
    {
        try {
            $validated = $request->validate([
                'recipient_employee_id' => 'required|exists:employees,id',
                'export_date' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.spare_part_id' => 'required|exists:spare_parts,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.purpose' => 'required|string',
                'items.*.equipment_id' => 'nullable|exists:equipment,id',
                'items.*.notes' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في التحقق من البيانات',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        DB::beginTransaction();
        try {
            $recipientEmployee = Employee::find($request->recipient_employee_id);

            foreach ($request->items as $item) {
                $inventory = WarehouseInventory::where('spare_part_id', $item['spare_part_id'])
                    ->where('location_id', $warehouse->id)
                    ->first();

                if (!$inventory) {
                    $errorMsg = 'قطعة الغيار المطلوبة غير موجودة في المخزون';
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMsg
                        ], 422);
                    }
                    return back()->withInput()
                        ->with('error', $errorMsg);
                }

                // فحص توفر الكمية المطلوبة
                if ($inventory->current_stock < $item['quantity']) {
                    $sparePart = $inventory->sparePart;
                    $errorMsg = "الكمية المطلوبة من {$sparePart->name} غير متوفرة في المخزون. المتوفر: {$inventory->current_stock}";
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMsg
                        ], 422);
                    }
                    return back()->withInput()
                        ->with('error', $errorMsg);
                }

                $totalAmount = $item['quantity'] * $inventory->average_cost;

                // تحديث المخزون
                $inventory->update([
                    'current_stock' => $inventory->current_stock - $item['quantity'],
                    'available_stock' => ($inventory->available_stock ?? $inventory->current_stock) - $item['quantity'],
                    'total_value' => $inventory->total_value - $totalAmount,
                    'last_transaction_date' => now(),
                ]);

                // تحديث حالة الأرقام التسلسلية إلى "مُستخدم"
                $serialNumbers = SparePartSerial::where('spare_part_id', $item['spare_part_id'])
                    ->where('location_id', $warehouse->id)
                    ->where('status', 'available')
                    ->limit($item['quantity'])
                    ->get();

                foreach ($serialNumbers as $serial) {
                    $serial->update([
                        'status' => 'exported',
                        'exported_to_employee_id' => $request->recipient_employee_id,
                        'exported_date' => $request->export_date,
                    ]);
                }

                // إنشاء معاملة التصدير
                SparePartTransaction::create([
                    'spare_part_id' => $item['spare_part_id'],
                    'location_id' => $warehouse->id,
                    'transaction_type' => 'تصدير',
                    'quantity' => $item['quantity'],
                    'unit_price' => $inventory->average_cost,
                    'total_amount' => $totalAmount,
                    'equipment_id' => $item['equipment_id'] ?? null,
                    'notes' => $item['notes'] ?? "تصدير إلى {$recipientEmployee->name} - الغرض: {$item['purpose']}",
                    'user_id' => Auth::id(),
                    'transaction_date' => $request->export_date,
                    'additional_data' => [
                        'recipient_employee_id' => $request->recipient_employee_id,
                        'recipient_name' => $recipientEmployee->name,
                        'purpose' => $item['purpose'],
                        'export_date' => $request->export_date,
                    ],
                ]);
            }

            DB::commit();

            // إذا كان الطلب من AJAX، أعد JSON response
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تصدير قطع الغيار بنجاح'
                ]);
            }

            return redirect()->route('warehouses.show', $warehouse)
                ->with('success', 'تم تصدير قطع الغيار بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            // إذا كان الطلب من AJAX، أعد JSON response للخطأ
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تصدير قطع الغيار: ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تصدير قطع الغيار: ' . $e->getMessage());
        }
    }

    /**
     * استلام قطع غيار جديدة من فاتورة
     */
    public function receiveNewSpares(Request $request, Location $warehouse)
    {
        Log::info('receiveNewSpares called', ['request_data' => $request->all()]);

        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255',
            'supplier_id' => 'required|integer',
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.spare_part_type_id' => 'nullable|integer',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string',
            'items.*.notes' => 'nullable|string',
        ]);

        Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                // إنشاء قطعة غيار جديدة أو العثور على موجودة
                // استخدام الاسم فقط للبحث لتجنب مشاكل spare_part_type_id الفارغة
                $sparePart = SparePart::firstOrCreate([
                    'name' => $item['name'],
                ], [
                    'code' => SparePart::generateCode(),
                    'description' => $item['description'] ?? '',
                    'unit_price' => $item['unit_price'],
                    'spare_part_type_id' => $item['spare_part_type_id'] ?? 1,
                    'is_active' => true,
                    'source' => 'new', // جديدة من فاتورة
                ]);

                Log::info('Spare part created/found', ['spare_part' => $sparePart]);

                // إنتاج الأرقام التسلسلية والباركود
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $serialNumber = $sparePart->generateSerialNumber();
                    $barcode = $sparePart->generateBarcode();

                    // إنشاء السجل في جدول الأرقام التسلسلية
                    \App\Models\SparePartSerial::create([
                        'spare_part_id' => $sparePart->id,
                        'serial_number' => $serialNumber,
                        'barcode' => $barcode,
                        'location_id' => $warehouse->id,
                        'status' => 'available',
                    ]);
                }

                // تحديث أو إنشاء مخزون المستودع
                $inventory = WarehouseInventory::firstOrCreate([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $sparePart->id,
                ], [
                    'current_stock' => 0,
                    'available_stock' => 0,
                    'total_value' => 0,
                    'average_cost' => $item['unit_price'],
                ]);

                $inventory->current_stock += $item['quantity'];
                $inventory->available_stock += $item['quantity'];
                $inventory->total_value += ($item['quantity'] * $item['unit_price']);
                $inventory->average_cost = $inventory->current_stock > 0 ?
                    $inventory->total_value / $inventory->current_stock : $item['unit_price'];
                $inventory->last_transaction_date = now();
                $inventory->save();

                // إنشاء سجل المعاملة
                SparePartTransaction::create([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $sparePart->id,
                    'transaction_type' => 'استلام', // استخدام العربية كما في الmigration
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_amount' => $item['quantity'] * $item['unit_price'],
                    'notes' => $item['notes'],
                    'user_id' => Auth::id(),
                    'transaction_date' => $validated['invoice_date'],
                    'additional_data' => [
                        'source' => 'new_invoice',
                        'invoice_number' => $validated['invoice_number'],
                        'supplier_id' => $validated['supplier_id'],
                    ],
                ]);
            }

            DB::commit();
            Log::info('Transaction committed successfully');

            // إذا كانت طلب AJAX، أرجع JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم استلام قطع الغيار الجديدة بنجاح وإنتاج الأرقام التسلسلية'
                ]);
            }

            return redirect()->route('warehouses.show', $warehouse)
                ->with('success', 'تم استلام قطع الغيار الجديدة بنجاح وإنتاج الأرقام التسلسلية');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in receiveNewSpares', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            // إذا كانت طلب AJAX، أرجع JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء استلام قطع الغيار: ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء استلام قطع الغيار: ' . $e->getMessage());
        }
    }

    /**
     * تصدير قطع غيار للمعدات مع استلام القطع التالفة
     */
    public function exportToEquipment(Request $request, Location $warehouse)
    {
        $validated = $request->validate([
            'export_date' => 'required|date',
            'equipment_id' => 'required|exists:equipment,id',
            'technician_employee_id' => 'required|exists:employees,id',
            'work_order_number' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.spare_part_id' => 'required|exists:spare_parts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.replacement_reason' => 'required|in:scheduled_maintenance,breakdown,preventive,upgrade,other',
            'items.*.old_part_condition' => 'required|in:damaged,worn_out,defective,end_of_life',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                // التحقق من توفر قطعة الغيار في المخزون
                $inventory = WarehouseInventory::where('spare_part_id', $item['spare_part_id'])
                    ->where('location_id', $warehouse->id)
                    ->first();

                if (!$inventory || $inventory->current_stock < $item['quantity']) {
                    $sparePart = SparePart::find($item['spare_part_id']);
                    return back()->withInput()
                        ->with('error', "الكمية المطلوبة من {$sparePart->name} غير متوفرة في المخزون");
                }

                // تصدير القطعة الجديدة
                $inventory->current_stock -= $item['quantity'];
                $inventory->available_stock -= $item['quantity'];
                $inventory->total_value -= ($item['quantity'] * $inventory->average_cost);
                $inventory->last_transaction_date = now();
                $inventory->save();

                // تحديث Serial Numbers للقطع المُصدَّرة
                $serialNumbers = SparePartSerial::where('spare_part_id', $item['spare_part_id'])
                    ->where('location_id', $warehouse->id)
                    ->where('status', 'available')
                    ->limit($item['quantity'])
                    ->get();

                foreach ($serialNumbers as $serial) {
                    $serial->update([
                        'status' => 'in_use',
                        'assigned_to_equipment' => $validated['equipment_id'],
                        'assigned_to_employee' => $validated['technician_employee_id'],
                        'assignment_date' => $validated['export_date'],
                    ]);
                }

                // معاملة تصدير القطعة الجديدة
                SparePartTransaction::create([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $item['spare_part_id'],
                    'equipment_id' => $validated['equipment_id'],
                    'transaction_type' => 'تصدير',
                    'quantity' => $item['quantity'],
                    'unit_price' => $inventory->sparePart->unit_price,
                    'total_amount' => $item['quantity'] * $inventory->sparePart->unit_price,
                    'notes' => $item['notes'],
                    'user_id' => Auth::id(),
                    'transaction_date' => $validated['export_date'],
                    'additional_data' => [
                        'technician_employee_id' => $validated['technician_employee_id'],
                        'work_order_number' => $validated['work_order_number'],
                        'replacement_reason' => $item['replacement_reason'],
                        'old_part_condition' => $item['old_part_condition'],
                    ],
                ]);

                // إنشاء سجل للقطعة التالفة المُستبدَلة
                $damagedPart = SparePart::firstOrCreate([
                    'name' => $inventory->sparePart->name . ' (تالفة)',
                    'spare_part_type_id' => $inventory->sparePart->spare_part_type_id,
                ], [
                    'code' => SparePart::generateCode(),
                    'description' => 'قطعة غيار تالفة تم استبدالها من ' . $inventory->sparePart->name,
                    'unit_price' => 0, // القطع التالفة ليس لها قيمة
                    'is_active' => false, // غير نشطة للاستخدام
                    'source' => 'damaged_replacement',
                ]);

                // إنتاج أرقام تسلسلية للقطع التالفة
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $serialNumber = $damagedPart->generateSerialNumber();
                    $barcode = $damagedPart->generateBarcode();

                    SparePartSerial::create([
                        'spare_part_id' => $damagedPart->id,
                        'serial_number' => $serialNumber,
                        'barcode' => $barcode,
                        'location_id' => $warehouse->id,
                        'status' => 'damaged',
                        'condition_notes' => "قطعة تالفة تم استبدالها من المعدة - السبب: {$item['old_part_condition']}",
                        'returned_by' => $validated['technician_employee_id'],
                        'return_date' => $validated['export_date'],
                        'source_equipment_id' => $validated['equipment_id'],
                    ]);
                }

                // إنشاء مخزون للقطع التالفة (لأغراض التتبع والإحصاء)
                $damagedInventory = WarehouseInventory::firstOrCreate([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $damagedPart->id,
                ], [
                    'current_stock' => 0,
                    'total_value' => 0,
                ]);

                $damagedInventory->current_stock += $item['quantity'];
                // القيمة = صفر للقطع التالفة
                $damagedInventory->save();

                // معاملة استلام القطعة التالفة
                SparePartTransaction::create([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $damagedPart->id,
                    'equipment_id' => $validated['equipment_id'],
                    'transaction_type' => 'استلام',
                    'quantity' => $item['quantity'],
                    'unit_price' => 0, // بدون قيمة
                    'total_amount' => 0,
                    'notes' => "قطع تالفة مُستبدَلة - {$item['notes']}",
                    'user_id' => Auth::id(),
                    'transaction_date' => $validated['export_date'],
                    'additional_data' => [
                        'source' => 'damaged_replacement',
                        'technician_employee_id' => $validated['technician_employee_id'],
                        'work_order_number' => $validated['work_order_number'],
                        'original_part_id' => $item['spare_part_id'],
                        'damage_condition' => $item['old_part_condition'],
                    ],
                ]);
            }

            DB::commit();

            return redirect()->route('warehouses.show', $warehouse)
                ->with('success', 'تم تصدير قطع الغيار الجديدة واستلام القطع التالفة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء العملية: ' . $e->getMessage());
        }
    }

    /**
     * استلام قطع غيار تالفة
     */
    public function receiveDamagedSpares(Request $request, Location $warehouse)
    {
        Log::info('receiveDamagedSpares called', ['request_data' => $request->all()]);

        $validated = $request->validate([
            'returned_by_employee_id' => 'required|integer',
            'return_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.spare_part_type_id' => 'required|integer',
            'items.*.part_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition' => 'required|in:damaged,worn_out,defective',
            'items.*.notes' => 'nullable|string',
        ]);

        Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                // إنشاء قطعة غيار تالفة جديدة
                $damagedSparePart = SparePart::create([
                    'spare_part_type_id' => $item['spare_part_type_id'],
                    'name' => $item['part_name'] . ' (تالفة)',
                    'code' => SparePart::generateCode(),
                    'description' => 'قطعة غيار تالفة تم استلامها',
                    'unit_price' => 0, // القطع التالفة ليس لها قيمة
                    'is_active' => false, // غير نشطة للاستخدام
                    'source' => 'damaged_replacement', // تصحيح القيمة لتتطابق مع الفلتر
                ]);

                // إنتاج الأرقام التسلسلية والباركود للقطع التالفة
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $serialNumber = $damagedSparePart->generateSerialNumber();
                    $barcode = $damagedSparePart->generateBarcode();

                    SparePartSerial::create([
                        'spare_part_id' => $damagedSparePart->id,
                        'serial_number' => $serialNumber,
                        'barcode' => $barcode,
                        'location_id' => $warehouse->id,
                        'status' => 'damaged',
                        'condition_notes' => "قطعة تالفة - حالة: {$item['condition']} - {$item['notes']}",
                        'returned_by' => $validated['returned_by_employee_id'],
                        'return_date' => $validated['return_date'],
                    ]);
                }

                // إنشاء مخزون للقطع التالفة (لأغراض التتبع)
                $inventory = WarehouseInventory::firstOrCreate([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $damagedSparePart->id,
                ], [
                    'current_stock' => 0,
                    'available_stock' => 0, // القطع التالفة غير متاحة للاستخدام
                    'total_value' => 0,
                    'average_cost' => 0,
                ]);

                // تحديث المخزون
                $inventory->current_stock += $item['quantity'];
                // لا نحدث available_stock للقطع التالفة لأنها غير صالحة للاستخدام
                $inventory->last_transaction_date = now();
                // القيمة = صفر للقطع التالفة
                $inventory->save();

                // إنشاء معاملة الاستلام
                SparePartTransaction::create([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $damagedSparePart->id,
                    'transaction_type' => 'استلام',
                    'quantity' => $item['quantity'],
                    'unit_price' => 0,
                    'total_amount' => 0,
                    'notes' => "استلام قطع تالفة - حالة: {$item['condition']} - {$item['notes']}",
                    'user_id' => Auth::id(),
                    'transaction_date' => $validated['return_date'],
                    'additional_data' => [
                        'source' => 'damaged_received',
                        'returned_by_employee_id' => $validated['returned_by_employee_id'],
                        'condition' => $item['condition'],
                        'spare_part_type_id' => $item['spare_part_type_id'],
                    ],
                ]);
            }

            DB::commit();
            Log::info('Damaged parts reception transaction committed successfully');

            return redirect()->route('warehouses.show', $warehouse)
                ->with('success', 'تم استلام القطع التالفة بنجاح وإنتاج الأرقام التسلسلية');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in receiveDamagedSpares', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء استلام القطع التالفة: ' . $e->getMessage());
        }
    }

    /**
     * استلام قطع غيار صالحة مُرجعة من الفنيين (نادر الحدوث)
     */
    public function receiveGoodReturnedSpares(Request $request, Location $warehouse)
    {
        Log::info('receiveGoodReturnedSpares called', ['request_data' => $request->all()]);

        $validated = $request->validate([
            'returned_by_employee_id' => 'required|exists:employees,id',
            'return_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.spare_part_id' => 'required|exists:spare_parts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition' => 'required|in:good,like_new', // فقط القطع الصالحة
            'items.*.return_reason' => 'required|in:excess,wrong_part,cancelled_work,other',
            'items.*.notes' => 'nullable|string',
        ]);

        Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                $sparePart = SparePart::find($item['spare_part_id']);

                Log::info('Processing returned good spare part', ['spare_part' => $sparePart]);

                // البحث عن Serial Numbers التي تم تصديرها لهذا الموظف
                $returnedSerials = SparePartSerial::where('spare_part_id', $sparePart->id)
                    ->where('assigned_to_employee', $validated['returned_by_employee_id'])
                    ->where('status', 'in_use')
                    ->limit($item['quantity'])
                    ->get();

                if ($returnedSerials->count() < $item['quantity']) {
                    return back()->withInput()
                        ->with('error', "لا توجد كمية كافية من {$sparePart->name} مُصدَّرة لهذا الموظف للإرجاع");
                }

                // إرجاع Serial Numbers للمخزون
                foreach ($returnedSerials as $serial) {
                    $serial->update([
                        'status' => 'available',
                        'assigned_to_equipment' => null,
                        'assigned_to_employee' => null,
                        'assignment_date' => null,
                        'return_date' => $validated['return_date'],
                        'condition_notes' => "أُرجعت في حالة صالحة - {$item['notes']}",
                    ]);
                }

                // تحديث مخزون المستودع (إضافة القطع المُرجعة)
                $inventory = WarehouseInventory::firstOrCreate([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $sparePart->id,
                ], [
                    'current_stock' => 0,
                    'total_value' => 0,
                ]);

                $inventory->current_stock += $item['quantity'];
                $inventory->total_value += ($item['quantity'] * ($sparePart->unit_price ?? 0));
                $inventory->save();

                // إنشاء سجل المعاملة
                SparePartTransaction::create([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $sparePart->id,
                    'transaction_type' => 'استلام',
                    'quantity' => $item['quantity'],
                    'unit_price' => $sparePart->unit_price ?? 0,
                    'total_amount' => ($sparePart->unit_price ?? 0) * $item['quantity'],
                    'notes' => $item['notes'],
                    'user_id' => Auth::id(),
                    'transaction_date' => $validated['return_date'],
                    'additional_data' => [
                        'source' => 'good_return',
                        'returned_by_employee_id' => $validated['returned_by_employee_id'],
                        'condition' => $item['condition'],
                        'return_reason' => $item['return_reason'],
                    ],
                ]);
            }

            DB::commit();
            Log::info('Good return transaction committed successfully');

            return redirect()->route('warehouses.show', $warehouse)
                ->with('success', 'تم استلام قطع الغيار الصالحة المُرجعة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in receiveGoodReturnedSpares', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء استلام قطع الغيار: ' . $e->getMessage());
        }
    }

    /**
     * عرض تقارير المستودع
     */
    public function reports(Location $warehouse)
    {
        $transactions = SparePartTransaction::with(['sparePart', 'equipment', 'user'])
            ->where('location_id', $warehouse->id)
            ->latest()
            ->paginate(20);

        return view('warehouses.reports', compact('warehouse', 'transactions'));
    }

    /**
     * التخلص من القطع التالفة أو إعادة تدويرها
     */
    public function disposeDamagedParts(Request $request, Location $warehouse)
    {
        $validated = $request->validate([
            'disposal_date' => 'required|date',
            'disposal_method' => 'required|in:scrap,recycle,repair,return_to_supplier',
            'authorized_by_employee_id' => 'required|exists:employees,id',
            'items' => 'required|array|min:1',
            'items.*.spare_part_id' => 'required|exists:spare_parts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.disposal_reason' => 'required|string',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                $sparePart = SparePart::find($item['spare_part_id']);

                // التحقق من أن هذه قطعة تالفة
                if ($sparePart->source !== 'damaged_replacement') {
                    return back()->withInput()
                        ->with('error', "القطعة {$sparePart->name} ليست قطعة تالفة");
                }

                $inventory = WarehouseInventory::where('location_id', $warehouse->id)
                    ->where('spare_part_id', $sparePart->id)
                    ->first();

                if (!$inventory || $inventory->current_stock < $item['quantity']) {
                    return back()->withInput()
                        ->with('error', "الكمية المطلوبة من {$sparePart->name} غير متوفرة");
                }

                // تحديث المخزون
                $inventory->current_stock -= $item['quantity'];
                $inventory->save();

                // تحديث Serial Numbers
                $serialNumbers = SparePartSerial::where('spare_part_id', $sparePart->id)
                    ->where('location_id', $warehouse->id)
                    ->where('status', 'damaged')
                    ->limit($item['quantity'])
                    ->get();

                foreach ($serialNumbers as $serial) {
                    $serial->update([
                        'status' => 'disposed',
                        'disposal_date' => $validated['disposal_date'],
                        'disposal_method' => $validated['disposal_method'],
                        'condition_notes' => $serial->condition_notes . " | تم التخلص - {$item['disposal_reason']}",
                    ]);
                }

                // إنشاء معاملة التخلص
                SparePartTransaction::create([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $sparePart->id,
                    'transaction_type' => 'إتلاف',
                    'quantity' => $item['quantity'],
                    'unit_price' => 0,
                    'total_amount' => 0,
                    'notes' => $item['notes'],
                    'user_id' => Auth::id(),
                    'transaction_date' => $validated['disposal_date'],
                    'additional_data' => [
                        'disposal_method' => $validated['disposal_method'],
                        'authorized_by_employee_id' => $validated['authorized_by_employee_id'],
                        'disposal_reason' => $item['disposal_reason'],
                    ],
                ]);
            }

            DB::commit();

            return redirect()->route('warehouses.show', $warehouse)
                ->with('success', 'تم التخلص من القطع التالفة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء التخلص من القطع: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل قطعة غيار محددة
     */
    public function showSparePartDetails(Location $warehouse, $sparePartId)
    {
        $sparePart = SparePart::with([
            'sparePartType',
            'serialNumbers' => function ($query) use ($warehouse) {
                $query->where('location_id', $warehouse->id);
            }
        ])->findOrFail($sparePartId);

        $inventory = WarehouseInventory::where('location_id', $warehouse->id)
            ->where('spare_part_id', $sparePartId)
            ->first();

        $transactions = SparePartTransaction::with(['equipment', 'user'])
            ->where('location_id', $warehouse->id)
            ->where('spare_part_id', $sparePartId)
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'sparePart' => $sparePart,
            'inventory' => $inventory,
            'transactions' => $transactions,
            'serialNumbers' => $sparePart->serialNumbers
        ]);
    }
}
