<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\SparePart;
use App\Models\WarehouseInventory;
use App\Models\SparePartTransaction;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * عرض جميع المستودعات
     */
    public function index()
    {
        $warehouses = Location::where('type', 'مستودع')
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
        if ($warehouse->type !== 'مستودع') {
            abort(404, 'الموقع المطلوب ليس مستودعاً');
        }

        $inventory = WarehouseInventory::with('sparePart')
            ->where('location_id', $warehouse->id)
            ->get();

        // الحصول على جميع قطع الغيار للنماذج المنبثقة
        $allSpareParts = SparePart::where('is_active', true)->get();
        
        // الحصول على جميع المعدات لربطها بالتصدير
        $equipments = Equipment::where('status', 'active')->get();

        return view('warehouses.show', compact('warehouse', 'inventory', 'allSpareParts', 'equipments'));
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
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.spare_part_id' => 'required|exists:spare_parts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.equipment_id' => 'nullable|exists:equipment,id',
            'items.*.recipient' => 'required|string',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                $inventory = WarehouseInventory::where('spare_part_id', $item['spare_part_id'])
                    ->where('location_id', $warehouse->id)
                    ->first();

                if (!$inventory) {
                    return back()->withInput()
                        ->with('error', 'قطعة الغيار المطلوبة غير موجودة في المخزون');
                }

                // فحص توفر الكمية المطلوبة
                if ($inventory->available_stock < $item['quantity']) {
                    $sparePart = $inventory->sparePart;
                    return back()->withInput()
                        ->with('error', "الكمية المطلوبة من {$sparePart->name} غير متوفرة في المخزون. المتوفر: {$inventory->available_stock}");
                }

                $totalAmount = $item['quantity'] * $inventory->average_cost;

                // تحديث المخزون
                $inventory->update([
                    'current_stock' => $inventory->current_stock - $item['quantity'],
                    'available_stock' => $inventory->available_stock - $item['quantity'],
                    'total_value' => $inventory->total_value - $totalAmount,
                    'last_transaction_date' => now(),
                ]);

                // إنشاء معاملة التصدير
                SparePartTransaction::create([
                    'spare_part_id' => $item['spare_part_id'],
                    'location_id' => $warehouse->id,
                    'transaction_type' => 'تصدير',
                    'quantity' => $item['quantity'],
                    'unit_price' => $inventory->average_cost,
                    'total_amount' => $totalAmount,
                    'equipment_id' => $item['equipment_id'] ?? null,
                    'notes' => $item['notes'],
                    'user_id' => Auth::id(),
                    'transaction_date' => now(),
                    'additional_data' => [
                        'recipient' => $item['recipient']
                    ],
                ]);
            }

            DB::commit();
            return redirect()->route('warehouses.show', $warehouse)
                ->with('success', 'تم تصدير قطع الغيار بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تصدير قطع الغيار: ' . $e->getMessage());
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
}
