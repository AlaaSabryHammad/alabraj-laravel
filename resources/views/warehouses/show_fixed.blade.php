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
                <button type="button" onclick="showAllSerialNumbers()" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-qr-code-line"></i>
                    جميع الأرقام التسلسلية
                </button>
                <button type="button" onclick="openReceiveTypeModal()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-add-line"></i>
                    استلام قطع غيار
                </button>
                <button type="button" onclick="openExportModal()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-subtract-line"></i>
                    تصدير قطع غيار
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">قطع غيار جديدة</p>
                        <p class="text-2xl font-bold text-green-600">{{ $newInventory->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-tools-line text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">قطع غيار تالفة</p>
                        <p class="text-2xl font-bold text-red-600">{{ $damagedInventory->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ri-tools-fill text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الكمية المتوفرة</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $newInventory->sum('available_stock') ?: $newInventory->sum('current_stock') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-stack-line text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي القيمة</p>
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($newInventory->sum('total_value'), 2) }} ريال</p>
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
                        @php $lowStock = $newInventory->filter(function($item) { 
                            $available = $item->available_stock ?? $item->current_stock;
                            return $available > 0 && $available < 10; 
                        })->count(); @endphp
                        <p class="text-2xl font-bold text-yellow-600">{{ $lowStock }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="ri-error-warning-line text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Spare Parts Table -->
        <div class="bg-white rounded-xl shadow-sm border mb-6">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ri-tools-line text-green-600"></i>
                        قطع الغيار الجديدة
                    </h2>
                    <div class="flex gap-3">
                        <input type="text" placeholder="البحث عن قطعة غيار..." id="searchNewParts"
                               class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="filterNewParts">
                            <option value="">جميع الأصناف</option>
                            <option value="available">متوفر</option>
                            <option value="low">كمية منخفضة</option>
                            <option value="out">نفد المخزون</option>
                        </select>
                    </div>
                </div>
            </div>

            @if($newInventory->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قطعة الغيار</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكود</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية المتوفرة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية الكلية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سعر الوحدة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القيمة الإجمالية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($newInventory as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->sparePart->name }}</div>
                                            @if($item->sparePart->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($item->sparePart->description, 50) }}</div>
                                            @endif
                                            @if($item->sparePart->brand)
                                                <div class="text-xs text-blue-600">{{ $item->sparePart->brand }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-mono">{{ $item->sparePart->code }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-green-700">{{ $item->available_stock ?? $item->current_stock }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->current_stock }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ number_format($item->average_cost ?? $item->sparePart->unit_price, 2) }} ريال</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($item->total_value, 2) }} ريال</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $availableStock = $item->available_stock ?? $item->current_stock; @endphp
                                        @if($availableStock > 10)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                متوفر
                                            </span>
                                        @elseif($availableStock > 0)
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
                                            <button type="button" onclick="showSparePartDetails({{ $item->sparePart->id }})"
                                                    class="text-purple-600 hover:text-purple-900 p-1 rounded" 
                                                    title="عرض التفاصيل">
                                                <i class="ri-information-line text-lg"></i>
                                            </button>
                                            <button type="button" onclick="openExportModal({{ $item->sparePart->id }})"
                                                    class="text-blue-600 hover:text-blue-900 p-1 rounded" 
                                                    title="تصدير">
                                                <i class="ri-subtract-line text-lg"></i>
                                            </button>
                                            <button type="button" onclick="openReceiveModalWithData('new', {{ $item->sparePart->id }}, '{{ addslashes($item->sparePart->name) }}')"
                                                    class="text-green-600 hover:text-green-900 p-1 rounded" 
                                                    title="استلام كمية إضافية">
                                                <i class="ri-add-line text-lg"></i>
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
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-tools-line text-4xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد قطع غيار جديدة</h3>
                    <p class="text-gray-600 mb-4">لم يتم استلام أي قطع غيار جديدة في هذا المستودع بعد</p>
                    <button type="button" onclick="openReceiveTypeModal()"
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ri-add-line"></i>
                        استلام قطع غيار
                    </button>
                </div>
            @endif
        </div>

        <!-- Damaged Spare Parts Table -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ri-tools-fill text-red-600"></i>
                        قطع الغيار التالفة
                    </h2>
                    <div class="flex gap-3">
                        <input type="text" placeholder="البحث في القطع التالفة..." id="searchDamagedParts"
                               class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" id="filterDamagedParts">
                            <option value="">جميع الأنواع</option>
                            <option value="damaged">تالفة</option>
                            <option value="worn_out">مستهلكة</option>
                            <option value="defective">معطلة</option>
                        </select>
                    </div>
                </div>
            </div>

            @if($damagedInventory->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-red-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قطعة الغيار</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكود</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع التلف</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاستلام</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($damagedInventory as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->sparePart->name }}</div>
                                            @if($item->sparePart->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($item->sparePart->description, 50) }}</div>
                                            @endif
                                            @if($item->sparePart->brand)
                                                <div class="text-xs text-blue-600">{{ $item->sparePart->brand }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-sm font-mono">{{ $item->sparePart->code }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->current_stock }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $condition = 'غير محدد';
                                            if($item->sparePart->serialNumbers->first()) {
                                                $notes = $item->sparePart->serialNumbers->first()->condition_notes;
                                                if(str_contains($notes, 'damaged')) $condition = 'تالفة';
                                                elseif(str_contains($notes, 'worn_out')) $condition = 'مستهلكة';
                                                elseif(str_contains($notes, 'defective')) $condition = 'معطلة';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $condition }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">
                                            {{ $item->last_transaction_date ? $item->last_transaction_date->format('Y-m-d') : 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            مُخزنة
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <button type="button" onclick="showSparePartDetails({{ $item->sparePart->id }})"
                                                    class="text-purple-600 hover:text-purple-900 p-1 rounded" 
                                                    title="عرض التفاصيل">
                                                <i class="ri-information-line text-lg"></i>
                                            </button>
                                            <button type="button" onclick="openDisposeModal({{ $item->sparePart->id }})"
                                                    class="text-red-600 hover:text-red-900 p-1 rounded" 
                                                    title="التخلص من القطعة">
                                                <i class="ri-delete-bin-line text-lg"></i>
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
                    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-tools-fill text-4xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد قطع غيار تالفة</h3>
                    <p class="text-gray-600">لم يتم استلام أي قطع غيار تالفة في هذا المستودع</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Auto Receive Modal with Pre-filled Data -->
    <div id="autoReceiveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">استلام كمية إضافية</h3>
                        <button type="button" onclick="closeAutoReceiveModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>
                <form id="autoReceiveForm" action="{{ route('warehouses.receive-new-spares', $warehouse) }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-6">
                        <!-- Invoice Information -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-4">بيانات الفاتورة</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الفاتورة</label>
                                    <input type="text" name="invoice_number" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">المورد</label>
                                    <select name="supplier_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">اختر المورد</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الفاتورة</label>
                                    <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>
                        </div>

                        <!-- Pre-filled Spare Part -->
                        <div id="autoReceiveItems">
                            <div class="auto-receive-item border rounded-lg p-4 bg-green-50">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع قطعة الغيار</label>
                                        <select name="items[0][spare_part_type_id]" id="autoSparePartType" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100" readonly>
                                            @foreach($sparePartTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-blue-600">محدد تلقائياً من القطعة المختارة</small>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم القطعة</label>
                                        <input type="text" name="items[0][name]" id="autoSparePartName" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100" readonly required>
                                        <small class="text-blue-600">محدد تلقائياً من القطعة المختارة</small>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الكمية</label>
                                        <input type="number" name="items[0][quantity]" min="1" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">سعر الوحدة</label>
                                        <input type="number" step="0.01" name="items[0][unit_price]" id="autoUnitPrice" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <small class="text-gray-500">اتركه فارغ لاستخدام السعر الحالي</small>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                                        <textarea name="items[0][description]" id="autoDescription" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100" readonly></textarea>
                                        <small class="text-blue-600">محدد تلقائياً من القطعة المختارة</small>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                                        <textarea name="items[0][notes]" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="ملاحظات إضافية..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 border-t bg-gray-50 flex gap-3">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="ri-check-line mr-1"></i>
                            استلام الكمية الإضافية
                        </button>
                        <button type="button" onclick="closeAutoReceiveModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
let newPartItemCount = 1;
let returnedPartItemCount = 1;
let exportItemCount = 1;

// Auto Receive Modal Functions  
function openReceiveModalWithData(type, sparePartId, sparePartName) {
    // First get spare part details to pre-fill form
    @foreach($newInventory as $item)
        if ({{ $item->sparePart->id }} == sparePartId) {
            document.getElementById('autoSparePartType').value = {{ $item->sparePart->spare_part_type_id ?? 'null' }};
            document.getElementById('autoSparePartName').value = sparePartName;
            document.getElementById('autoDescription').value = '{{ addslashes($item->sparePart->description ?? '') }}';
            
            @if($item->averageUnitPrice)
                document.getElementById('autoUnitPrice').placeholder = 'السعر الحالي: {{ $item->averageUnitPrice }}';
            @endif
        }
    @endforeach
    
    // Show the modal
    document.getElementById('autoReceiveModal').classList.remove('hidden');
    
    // Focus on quantity field for quick entry
    setTimeout(() => {
        const quantityInput = document.querySelector('#autoReceiveModal input[name="items[0][quantity]"]');
        if (quantityInput) quantityInput.focus();
    }, 100);
}

function closeAutoReceiveModal() {
    document.getElementById('autoReceiveModal').classList.add('hidden');
    // Reset form
    document.getElementById('autoReceiveForm').reset();
}

// Receive Type Modal Functions
function openReceiveTypeModal() {
    // Mock function - will be fully implemented
    alert('سيتم تطوير هذه الوظيفة قريباً');
}

// Export Modal Functions
function openExportModal(sparePartId = null) {
    // Mock function - will be fully implemented
    alert('سيتم تطوير وظيفة التصدير قريباً');
}

// Spare Part Details Functions
function showSparePartDetails(sparePartId) {
    // Mock function - will be fully implemented
    alert('سيتم تطوير وظيفة عرض التفاصيل قريباً');
}

// Dispose Modal Functions
function openDisposeModal(sparePartId) {
    // Mock function - will be fully implemented
    alert('سيتم تطوير وظيفة التخلص من القطع التالفة قريباً');
}

function showAllSerialNumbers() {
    const modal = document.getElementById('allSerialsModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeAllSerialsModal() {
    const modal = document.getElementById('allSerialsModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function printBarcode(serialNumber, barcode) {
    const sparePartName = "{{ $warehouse->spare_part_name ?? 'قطعة غيار' }}";
    
    const printWindow = window.open('', '_blank', 'width=400,height=300');
    
    printWindow.document.write(\`
        <!DOCTYPE html>
        <html>
        <head>
            <title>طباعة باركود \${serialNumber}</title>
            <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    margin: 20px;
                }
                .barcode-container {
                    border: 2px solid #000;
                    padding: 20px;
                    margin: 20px;
                    background: white;
                }
                .header {
                    font-size: 16px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .serial-info {
                    font-size: 14px;
                    color: #333;
                    margin-bottom: 15px;
                }
                .barcode-section {
                    margin: 20px 0;
                }
                .footer {
                    font-size: 12px;
                    color: #666;
                    margin-top: 15px;
                }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
                @page {
                    size: A4;
                    margin: 5mm;
                }
            </style>
        </head>
        <body>
            <div class="no-print">
                <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">
                    طباعة
                </button>
                <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    إغلاق
                </button>
            </div>
            
            <div class="barcode-container">
                <div class="header">مستودع الأبراج</div>
                <div class="serial-info">
                    <div><strong>قطعة الغيار:</strong> \${sparePartName}</div>
                    <div><strong>الرقم التسلسلي:</strong> \${serialNumber}</div>
                    <div><strong>الباركود:</strong> \${barcode}</div>
                </div>
                <div class="barcode-section">
                    <svg id="barcode"></svg>
                </div>
                <div class="footer">
                    تاريخ الطباعة: \${new Date().toLocaleDateString('ar-SA')}
                </div>
            </div>
            
            <script>
                window.onload = function() {
                    JsBarcode("#barcode", "\${barcode}", {
                        format: "CODE128",
                        width: 2,
                        height: 80,
                        displayValue: true,
                        fontSize: 12,
                        textMargin: 8,
                        fontOptions: "bold",
                        textAlign: "center",
                        textPosition: "bottom",
                        background: "#ffffff",
                        lineColor: "#000000"
                    });
                };
            <\/script>
        </body>
        </html>
    \`);
    
    printWindow.document.close();
}

function printAllBarcodes() {
    const sparePartName = "{{ $warehouse->spare_part_name ?? 'قطعة غيار' }}";
    const serialNumbers = @json($allSparePartSerials);
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    printWindow.document.write(\`
        <!DOCTYPE html>
        <html>
        <head>
            <title>طباعة جميع الباركودات</title>
            <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
            <style>
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
                
                .barcode-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                    padding: 20px;
                }
                
                .barcode-item {
                    border: 1px solid #ddd;
                    padding: 15px;
                    text-align: center;
                    background: white;
                    border-radius: 8px;
                }
                
                .barcode-item h3 {
                    margin: 0 0 5px 0;
                    font-size: 14px;
                    font-weight: bold;
                    color: #333;
                }
                
                .barcode-item p {
                    margin: 2px 0;
                    font-size: 12px;
                }
                
                .controls {
                    text-align: center;
                    padding: 20px;
                    background: #f5f5f5;
                    border-bottom: 1px solid #ddd;
                }
                
                button {
                    margin: 0 5px;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                }
                
                .print-btn {
                    background: #007bff;
                    color: white;
                }
                
                .close-btn {
                    background: #6c757d;
                    color: white;
                }
                
                @page {
                    size: A4;
                    margin: 10mm;
                }
            </style>
        </head>
        <body>
            <div class="controls no-print">
                <h2>طباعة جميع الباركودات - \${sparePartName}</h2>
                <div>
                    <button onclick="window.print()" class="print-btn">
                        طباعة
                    </button>
                    <button onclick="window.close()" class="close-btn">
                        إغلاق
                    </button>
                </div>
            </div>
            
            <div class="barcode-grid">
                \${serialNumbers.map((serial, index) => \`
                    <div class="barcode-item">
                        <div class="mb-3">
                            <h3>مستودع الأبراج</h3>
                            <p style="color: #007bff; font-weight: bold;">رقم: \${serial.serial_number}</p>
                            <p style="color: #666;">باركود: \${serial.barcode}</p>
                        </div>
                        <div>
                            <svg id="barcode\${index}"></svg>
                        </div>
                        <div style="color: #999; font-size: 10px;">
                            <p>\${new Date().toLocaleDateString('ar-SA')}</p>
                        </div>
                    </div>
                \`).join('')}
            </div>
            
            <script>
                window.onload = function() {
                    \${serialNumbers.map((serial, index) => \`
                        JsBarcode("#barcode\${index}", "\${serial.barcode}", {
                            format: "CODE128",
                            width: 1.5,
                            height: 60,
                            displayValue: true,
                            fontSize: 10,
                            textMargin: 5,
                            fontOptions: "bold",
                            textAlign: "center",
                            textPosition: "bottom",
                            background: "#ffffff",
                            lineColor: "#000000"
                        });
                    \`).join('')}
                };
            <\/script>
        </body>
        </html>
    \`);
    
    printWindow.document.close();
}

</script>
@endpush

<!-- All Serial Numbers Modal -->
<div id="allSerialsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">جميع الأرقام التسلسلية - {{ $warehouse->name }}</h3>
                    <div class="flex gap-3">
                        <button onclick="printAllBarcodes()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-printer-line"></i>
                            طباعة جميع الباركودات
                        </button>
                        <button onclick="closeAllSerialsModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($allSparePartSerials as $serial)
                        <div class="bg-gray-50 rounded-lg p-4 border {{ $serial->status === 'exported' ? 'border-red-300 bg-red-50' : 'border-green-300 bg-green-50' }}">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-bold text-gray-900">{{ $serial->serial_number }}</span>
                                <span class="px-2 py-1 text-xs rounded-full {{ $serial->status === 'exported' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $serial->status === 'exported' ? 'مُصدر' : 'متاح' }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-600 mb-3">
                                <div>الباركود: {{ $serial->barcode }}</div>
                                @if($serial->status === 'exported' && $serial->exportedToEmployee)
                                    <div class="mt-1 text-red-600">
                                        مُصدر إلى: {{ $serial->exportedToEmployee->name }}
                                    </div>
                                    <div class="text-red-600">
                                        تاريخ التصدير: {{ $serial->exported_date ? $serial->exported_date->format('Y-m-d') : 'غير محدد' }}
                                    </div>
                                @endif
                            </div>
                            <button onclick="printBarcode('{{ $serial->serial_number }}', '{{ $serial->barcode }}')" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs transition-colors flex items-center justify-center gap-1">
                                <i class="ri-printer-line"></i>
                                طباعة الباركود
                            </button>
                        </div>
                    @endforeach
                </div>
                
                @if($allSparePartSerials->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-qr-code-line text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد أرقام تسلسلية</h3>
                        <p class="text-gray-600">لم يتم إنشاء أي أرقام تسلسلية في هذا المستودع بعد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Close all serials modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const allSerialsModal = document.getElementById('allSerialsModal');
    if (allSerialsModal) {
        allSerialsModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAllSerialsModal();
            }
        });
    }
});
</script>
@endsection
