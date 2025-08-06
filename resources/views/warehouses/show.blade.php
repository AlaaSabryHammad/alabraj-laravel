@extends('layouts.app')

@section('title', 'مستودع ' . $warehouse->name)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('warehouses.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="ri-store-3-line text-orange-600"></i>
                        {{ $warehouse->name }}
                    </h1>
                    <p class="text-gray-600">إدارة قطع الغيار والمخزون</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('warehouses.create-spare-part', $warehouse) }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="ri-add-box-line mr-1"></i>
                    إضافة قطعة غيار جديدة
                </a>
                <button type="button" onclick="openImportModal()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="ri-add-line mr-1"></i>
                    استلام قطع غيار
                </button>
                <button type="button" onclick="openExportModal()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="ri-subtract-line mr-1"></i>
                    تصدير قطع غيار
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الأصناف</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $inventory->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-box-3-line text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الكمية</p>
                        <p class="text-2xl font-bold text-green-600">{{ $inventory->sum('current_stock') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-stack-line text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي القيمة</p>
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($inventory->sum('total_value'), 2) }} ريال</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="ri-money-dollar-circle-line text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">أصناف منخفضة</p>
                        <p class="text-2xl font-bold text-red-600">{{ $inventory->where('current_stock', '<', 10)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ri-error-warning-line text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">مخزون قطع الغيار</h2>
                    <div class="flex gap-3">
                        <input type="text" placeholder="البحث عن قطعة غيار..." 
                               class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">جميع الأصناف</option>
                            <option value="available">متوفر</option>
                            <option value="low">كمية منخفضة</option>
                            <option value="out">نفد المخزون</option>
                        </select>
                    </div>
                </div>
            </div>

            @if($inventory->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قطعة الغيار</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكود</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سعر الوحدة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القيمة الإجمالية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($inventory as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->sparePart->name }}</div>
                                            @if($item->sparePart->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($item->sparePart->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-gray-100 rounded text-sm font-mono">{{ $item->sparePart->code }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->current_stock }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ number_format($item->sparePart->unit_price, 2) }} ريال</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($item->total_value, 2) }} ريال</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->current_stock > 10)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                متوفر
                                            </span>
                                        @elseif($item->current_stock > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                منخفض
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                نفد
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <button type="button" onclick="openExportModal({{ $item->sparePart->id }})"
                                                    class="text-blue-600 hover:text-blue-900">
                                                <i class="ri-subtract-line"></i>
                                                تصدير
                                            </button>
                                            <button type="button" onclick="openImportModal({{ $item->sparePart->id }})"
                                                    class="text-green-600 hover:text-green-900">
                                                <i class="ri-add-line"></i>
                                                استلام
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-box-3-line text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد قطع غيار</h3>
                    <p class="text-gray-600 mb-4">لم يتم استلام أي قطع غيار في هذا المستودع بعد</p>
                    <button type="button" onclick="openImportModal()"
                            class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ri-add-line"></i>
                        استلام قطع غيار
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">استلام قطع غيار</h3>
                        <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>
                <form id="importForm" action="{{ route('warehouses.receive-spares', $warehouse) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-6">
                        <div id="importItems">
                            <div class="import-item border rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">قطعة الغيار</label>
                                        <select name="items[0][spare_part_id]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                                            <option value="">اختر قطعة غيار</option>
                                            @foreach($allSpareParts as $sparePart)
                                                <option value="{{ $sparePart->id }}">{{ $sparePart->name }} ({{ $sparePart->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الكمية</label>
                                        <input type="number" name="items[0][quantity]" min="1" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                                        <textarea name="items[0][notes]" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addImportItem()" class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-gray-600 hover:border-gray-400 hover:text-gray-700 transition-colors">
                            <i class="ri-add-line mr-1"></i>
                            إضافة صنف آخر
                        </button>
                    </div>
                    <div class="p-6 border-t bg-gray-50 flex gap-3">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="ri-check-line mr-1"></i>
                            استلام
                        </button>
                        <button type="button" onclick="closeImportModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">تصدير قطع غيار</h3>
                        <button type="button" onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>
                <form id="exportForm" action="{{ route('warehouses.export-spares', $warehouse) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-6">
                        <div id="exportItems">
                            <div class="export-item border rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">قطعة الغيار</label>
                                        <select name="items[0][spare_part_id]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                                            <option value="">اختر قطعة غيار</option>
                                            @foreach($inventory as $item)
                                                <option value="{{ $item->sparePart->id }}" data-available="{{ $item->quantity }}">
                                                    {{ $item->sparePart->name }} ({{ $item->sparePart->code }}) - متوفر: {{ $item->quantity }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الكمية</label>
                                        <input type="number" name="items[0][quantity]" min="1" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">المعدة المستهدفة</label>
                                        <select name="items[0][equipment_id]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            <option value="">اختر معدة (اختياري)</option>
                                            @foreach($equipments as $equipment)
                                                <option value="{{ $equipment->id }}">{{ $equipment->name }} ({{ $equipment->serial_number }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">المستلم</label>
                                        <input type="text" name="items[0][recipient]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                                        <textarea name="items[0][notes]" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addExportItem()" class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-gray-600 hover:border-gray-400 hover:text-gray-700 transition-colors">
                            <i class="ri-add-line mr-1"></i>
                            إضافة صنف آخر
                        </button>
                    </div>
                    <div class="p-6 border-t bg-gray-50 flex gap-3">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="ri-check-line mr-1"></i>
                            تصدير
                        </button>
                        <button type="button" onclick="closeExportModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
let importItemCount = 1;
let exportItemCount = 1;

function openImportModal(sparePartId = null) {
    document.getElementById('importModal').classList.remove('hidden');
    if (sparePartId) {
        document.querySelector('select[name="items[0][spare_part_id]"]').value = sparePartId;
    }
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}

function openExportModal(sparePartId = null) {
    document.getElementById('exportModal').classList.remove('hidden');
    if (sparePartId) {
        document.querySelector('select[name="items[0][spare_part_id]"]').value = sparePartId;
    }
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

function addImportItem() {
    const importItems = document.getElementById('importItems');
    const newItem = document.createElement('div');
    newItem.className = 'import-item border rounded-lg p-4';
    newItem.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-medium text-gray-900">صنف ${importItemCount + 1}</h4>
            <button type="button" onclick="removeImportItem(this)" class="text-red-600 hover:text-red-800">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">قطعة الغيار</label>
                <select name="items[${importItemCount}][spare_part_id]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                    <option value="">اختر قطعة غيار</option>
                    @foreach($allSpareParts as $sparePart)
                        <option value="{{ $sparePart->id }}">{{ $sparePart->name }} ({{ $sparePart->code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية</label>
                <input type="number" name="items[${importItemCount}][quantity]" min="1" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                <textarea name="items[${importItemCount}][notes]" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
        </div>
    `;
    importItems.appendChild(newItem);
    importItemCount++;
}

function addExportItem() {
    const exportItems = document.getElementById('exportItems');
    const newItem = document.createElement('div');
    newItem.className = 'export-item border rounded-lg p-4';
    newItem.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-medium text-gray-900">صنف ${exportItemCount + 1}</h4>
            <button type="button" onclick="removeExportItem(this)" class="text-red-600 hover:text-red-800">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">قطعة الغيار</label>
                <select name="items[${exportItemCount}][spare_part_id]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                    <option value="">اختر قطعة غيار</option>
                    @foreach($inventory as $item)
                        <option value="{{ $item->sparePart->id }}" data-available="{{ $item->quantity }}">
                            {{ $item->sparePart->name }} ({{ $item->sparePart->code }}) - متوفر: {{ $item->quantity }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية</label>
                <input type="number" name="items[${exportItemCount}][quantity]" min="1" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المعدة المستهدفة</label>
                <select name="items[${exportItemCount}][equipment_id]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">اختر معدة (اختياري)</option>
                    @foreach($equipments as $equipment)
                        <option value="{{ $equipment->id }}">{{ $equipment->name }} ({{ $equipment->serial_number }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المستلم</label>
                <input type="text" name="items[${exportItemCount}][recipient]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                <textarea name="items[${exportItemCount}][notes]" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
        </div>
    `;
    exportItems.appendChild(newItem);
    exportItemCount++;
}

function removeImportItem(button) {
    button.closest('.import-item').remove();
}

function removeExportItem(button) {
    button.closest('.export-item').remove();
}

// Close modals when clicking outside
document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImportModal();
    }
});

document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExportModal();
    }
});
</script>
@endpush
