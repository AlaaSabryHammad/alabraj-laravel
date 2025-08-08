@extends('layouts.app')

@section('title', 'مستودع ' . $warehouse->name)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="bg-green-100 border border-green-300 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-center">
                <div class="text-center">
                    <i class="ri-check-circle-line text-4xl text-green-600 mb-2"></i>
                    <h1 class="text-2xl font-bold text-green-900">تم إصلاح مشكلة الترميز! 🎉</h1>
                    <p class="text-green-700">النص العربي يظهر الآن بشكل صحيح</p>
                </div>
            </div>
        </div>

        <!-- Header الأصلي -->
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
                <button type="button" onclick="alert('استلام قطع غيار')" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-add-line"></i>
                    استلام قطع غيار
                </button>
                <button type="button" onclick="alert('تصدير قطع غيار')" 
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
                                            <button type="button" onclick="alert('عرض التفاصيل')"
                                                    class="text-purple-600 hover:text-purple-900 p-1 rounded" 
                                                    title="عرض التفاصيل">
                                                <i class="ri-information-line text-lg"></i>
                                            </button>
                                            <button type="button" onclick="alert('تصدير')"
                                                    class="text-blue-600 hover:text-blue-900 p-1 rounded" 
                                                    title="تصدير">
                                                <i class="ri-subtract-line text-lg"></i>
                                            </button>
                                            <button type="button" onclick="alert('استلام كمية إضافية')"
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
                    <button type="button" onclick="alert('استلام قطع غيار')"
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ri-add-line"></i>
                        استلام قطع غيار
                    </button>
                </div>
            @endif
        </div>

        <!-- الأرقام التسلسلية -->
        @if($allSparePartSerials->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ri-qr-code-line text-purple-600"></i>
                        الأرقام التسلسلية ({{ $allSparePartSerials->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($allSparePartSerials->take(12) as $serial)
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-bold text-gray-900">{{ $serial->serial_number }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        متاح
                                    </span>
                                </div>
                                <div class="text-xs text-gray-600 mb-3">
                                    <div>الباركود: {{ $serial->barcode }}</div>
                                </div>
                                <button onclick="printBarcode('{{ $serial->serial_number }}', '{{ $serial->barcode }}')" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs transition-colors flex items-center justify-center gap-1">
                                    <i class="ri-printer-line"></i>
                                    طباعة الباركود
                                </button>
                            </div>
                        @endforeach
                    </div>

                    @if($allSparePartSerials->count() > 12)
                        <div class="text-center mt-6">
                            <button onclick="showAllSerialNumbers()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors">
                                عرض جميع الأرقام التسلسلية ({{ $allSparePartSerials->count() }})
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

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
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-bold text-gray-900">{{ $serial->serial_number }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        متاح
                                    </span>
                                </div>
                                <div class="text-xs text-gray-600 mb-3">
                                    <div>الباركود: {{ $serial->barcode }}</div>
                                </div>
                                <button onclick="printBarcode('{{ $serial->serial_number }}', '{{ $serial->barcode }}')" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs transition-colors flex items-center justify-center gap-1">
                                    <i class="ri-printer-line"></i>
                                    طباعة الباركود
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
function showAllSerialNumbers() {
    document.getElementById('allSerialsModal').classList.remove('hidden');
}

function closeAllSerialsModal() {
    document.getElementById('allSerialsModal').classList.add('hidden');
}

function printBarcode(serialNumber, barcode) {
    const sparePartName = "قطعة غيار";
    
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
            </style>
        </head>
        <body>
            <div class="barcode-container">
                <h3>مستودع الأبراج</h3>
                <p>الرقم التسلسلي: \${serialNumber}</p>
                <p>الباركود: \${barcode}</p>
                <svg id="barcode"></svg>
                <p>تاريخ الطباعة: \${new Date().toLocaleDateString('ar-SA')}</p>
            </div>
            
            <script>
                window.onload = function() {
                    JsBarcode("#barcode", "\${barcode}", {
                        format: "CODE128",
                        width: 2,
                        height: 80,
                        displayValue: true
                    });
                };
            <\/script>
        </body>
        </html>
    \`);
    
    printWindow.document.close();
}

function printAllBarcodes() {
    const serialNumbers = @json($allSparePartSerials);
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    printWindow.document.write(\`
        <!DOCTYPE html>
        <html>
        <head>
            <title>طباعة جميع الباركودات</title>
            <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
            <style>
                body { font-family: Arial, sans-serif; }
                .barcode-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; padding: 20px; }
                .barcode-item { border: 1px solid #ddd; padding: 15px; text-align: center; }
            </style>
        </head>
        <body>
            <div class="barcode-grid">
                \${serialNumbers.map((serial, index) => \`
                    <div class="barcode-item">
                        <h4>مستودع الأبراج</h4>
                        <p>رقم: \${serial.serial_number}</p>
                        <p>باركود: \${serial.barcode}</p>
                        <svg id="barcode\${index}"></svg>
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
                            displayValue: true
                        });
                    \`).join('')}
                };
            <\/script>
        </body>
        </html>
    \`);
    
    printWindow.document.close();
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('allSerialsModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAllSerialsModal();
            }
        });
    }
});
</script>
@endpush

@endsection
