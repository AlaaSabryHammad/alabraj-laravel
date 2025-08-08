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
                <button type="button" onclick="openReceiveModal()"
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
                        <i class="ri-error-warning-line text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الكمية</p>
                        @php
                            $totalQuantity =
                                $newInventory->sum('current_stock') + $damagedInventory->sum('current_stock');
                        @endphp
                        <p class="text-2xl font-bold text-blue-600">{{ $totalQuantity }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-archive-line text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">أصناف منخفضة</p>
                        @php
                            $lowStock = $newInventory
                                ->filter(function ($item) {
                                    $available = $item->available_stock ?? $item->current_stock;
                                    return $available > 0 && $available < 10;
                                })
                                ->count();
                        @endphp
                        <p class="text-2xl font-bold text-yellow-600">{{ $lowStock }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="ri-error-warning-line text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي القيمة</p>
                        @php
                            $totalValue =
                                $newInventory->sum(function ($item) {
                                    return $item->current_stock * ($item->sparePart->price ?? 0);
                                }) +
                                $damagedInventory->sum(function ($item) {
                                    return $item->current_stock * ($item->sparePart->price ?? 0);
                                });
                        @endphp
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($totalValue, 0) }} ر.س</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="ri-money-dollar-circle-line text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- New Spare Parts -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="flex items-center justify-between p-6 border-b">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="ri-tools-line text-xl text-green-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">قطع الغيار الجديدة</h2>
                            <p class="text-sm text-gray-600">{{ $newInventory->count() }} نوع قطعة غيار</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if ($newInventory->count() > 0)
                        <div class="space-y-3">
                            @foreach ($newInventory->take(5) as $item)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $item->sparePart->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item->sparePart->code }}</p>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold text-gray-900">{{ $item->current_stock }}</p>
                                        <p class="text-sm text-gray-600">متوفر</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="ri-inbox-line text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600">لا توجد قطع غيار جديدة</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Damaged Spare Parts -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="flex items-center justify-between p-6 border-b">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="ri-error-warning-line text-xl text-red-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">قطع الغيار التالفة</h2>
                            <p class="text-sm text-gray-600">{{ $damagedInventory->count() }} نوع قطعة تالفة</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if ($damagedInventory->count() > 0)
                        <div class="space-y-3">
                            @foreach ($damagedInventory->take(5) as $item)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $item->sparePart->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item->sparePart->code }}</p>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold text-red-600">{{ $item->current_stock }}</p>
                                        <p class="text-sm text-red-600">تالف</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="ri-shield-check-line text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600">لا توجد قطع غيار تالفة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- جدول أنواع قطع الغيار المسجلة -->
        <div class="bg-white rounded-xl shadow-sm border mt-6">
            <div class="flex items-center justify-between p-6 border-b">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="ri-list-check-3 text-xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">أنواع قطع الغيار المسجلة</h2>
                        @php
                            $allSpareParts = collect($newInventory)->merge($damagedInventory);
                            $uniquePartNames = $allSpareParts->pluck('sparePart.name')->unique();
                        @endphp
                        <p class="text-sm text-gray-600">{{ $uniquePartNames->count() }} نوع مختلف من قطع الغيار</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="exportSparePartsTable()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="ri-file-excel-line"></i>
                        تصدير Excel
                    </button>
                    <button type="button" onclick="printSparePartsTable()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="ri-printer-line"></i>
                        طباعة
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- فلاتر البحث -->
                <div class="mb-6 flex gap-4">
                    <div class="flex-1">
                        <input type="text" id="searchSparePartName" placeholder="البحث عن اسم قطعة الغيار..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onkeyup="filterSparePartsTable()">
                    </div>
                    <div>
                        <select id="filterSparePartCategory" onchange="filterSparePartsTable()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">جميع الفئات</option>
                            <option value="new">قطع جديدة</option>
                            <option value="damaged">قطع تالفة</option>
                        </select>
                    </div>
                </div>

                <!-- الجدول -->
                <div class="overflow-x-auto">
                    <table id="sparePartsTable" class="w-full text-sm text-right">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-tr-lg">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-tools-line text-gray-600"></i>
                                        اسم قطعة الغيار
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-qr-code-line text-gray-600"></i>
                                        الكود
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-archive-line text-gray-600"></i>
                                        الكمية الجديدة
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-error-warning-line text-gray-600"></i>
                                        الكمية التالفة
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-calculator-line text-gray-600"></i>
                                        إجمالي الكمية
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-money-dollar-circle-line text-gray-600"></i>
                                        السعر
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 rounded-tl-lg">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-eye-line text-gray-600"></i>
                                        الحالة
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @php
                                // تجميع قطع الغيار حسب الاسم والكود
                                $groupedParts = [];

                                foreach ($allSpareParts as $item) {
                                    $partName = $item->sparePart->name;
                                    $partCode = $item->sparePart->code;
                                    $key = $partName . '_' . $partCode;

                                    if (!isset($groupedParts[$key])) {
                                        $groupedParts[$key] = [
                                            'name' => $partName,
                                            'code' => $partCode,
                                            'price' => $item->sparePart->price ?? 0,
                                            'new_stock' => 0,
                                            'damaged_stock' => 0,
                                        ];
                                    }

                                    if ($item->type === 'new') {
                                        $groupedParts[$key]['new_stock'] += $item->current_stock;
                                    } else {
                                        $groupedParts[$key]['damaged_stock'] += $item->current_stock;
                                    }
                                }

                                // ترتيب حسب الاسم
                                ksort($groupedParts);
                            @endphp

                            @forelse($groupedParts as $part)
                                @php
                                    $totalStock = $part['new_stock'] + $part['damaged_stock'];
                                    $statusClass = '';
                                    $statusText = '';
                                    $statusIcon = '';

                                    if ($part['new_stock'] > 0 && $part['damaged_stock'] == 0) {
                                        $statusClass = 'text-green-600 bg-green-50';
                                        $statusText = 'متوفر';
                                        $statusIcon = 'ri-check-circle-line';
                                    } elseif ($part['new_stock'] == 0 && $part['damaged_stock'] > 0) {
                                        $statusClass = 'text-red-600 bg-red-50';
                                        $statusText = 'تالف فقط';
                                        $statusIcon = 'ri-error-warning-line';
                                    } elseif ($part['new_stock'] > 0 && $part['damaged_stock'] > 0) {
                                        $statusClass = 'text-orange-600 bg-orange-50';
                                        $statusText = 'مختلط';
                                        $statusIcon = 'ri-alert-line';
                                    } else {
                                        $statusClass = 'text-gray-600 bg-gray-50';
                                        $statusText = 'نفد المخزون';
                                        $statusIcon = 'ri-close-circle-line';
                                    }
                                @endphp
                                <tr class="border-b hover:bg-gray-50 transition-colors"
                                    data-category="{{ $part['new_stock'] > 0 ? 'new' : 'damaged' }}">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $part['name'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $part['code'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($part['new_stock'] > 0)
                                            <span class="text-green-600 font-semibold">{{ $part['new_stock'] }}</span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($part['damaged_stock'] > 0)
                                            <span class="text-red-600 font-semibold">{{ $part['damaged_stock'] }}</span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-blue-600">{{ $totalStock }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-purple-600 font-semibold">{{ number_format($part['price'], 2) }}
                                            ر.س</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                            <i class="{{ $statusIcon }}"></i>
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <i class="ri-inbox-line text-4xl text-gray-400"></i>
                                            <p>لا توجد قطع غيار مسجلة حتى الآن</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- إحصائيات سريعة -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class="ri-check-circle-line text-2xl text-green-600"></i>
                            <div>
                                <p class="text-sm text-green-700 font-medium">قطع متوفرة</p>
                                @php
                                    $availableParts = collect($groupedParts)->where('new_stock', '>', 0)->count();
                                @endphp
                                <p class="text-xl font-bold text-green-800">{{ $availableParts }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class="ri-error-warning-line text-2xl text-red-600"></i>
                            <div>
                                <p class="text-sm text-red-700 font-medium">قطع تالفة فقط</p>
                                @php
                                    $damagedOnlyParts = collect($groupedParts)
                                        ->where('new_stock', '=', 0)
                                        ->where('damaged_stock', '>', 0)
                                        ->count();
                                @endphp
                                <p class="text-xl font-bold text-red-800">{{ $damagedOnlyParts }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class="ri-alert-line text-2xl text-orange-600"></i>
                            <div>
                                <p class="text-sm text-orange-700 font-medium">قطع مختلطة</p>
                                @php
                                    $mixedParts = collect($groupedParts)
                                        ->where('new_stock', '>', 0)
                                        ->where('damaged_stock', '>', 0)
                                        ->count();
                                @endphp
                                <p class="text-xl font-bold text-orange-800">{{ $mixedParts }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class="ri-calculator-line text-2xl text-blue-600"></i>
                            <div>
                                <p class="text-sm text-blue-700 font-medium">إجمالي الأنواع</p>
                                <p class="text-xl font-bold text-blue-800">{{ count($groupedParts) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // دوال Modals المتطورة
        function showDevelopmentModal(title, message) {
            const modalHTML = `
                <div id="developmentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 rounded-t-xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white">${title}</h3>
                                <button type="button" onclick="closeDevelopmentModal()" class="text-white hover:text-gray-200">
                                    <i class="ri-close-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-tools-line text-2xl text-blue-600"></i>
                            </div>
                            <p class="text-gray-700 text-lg">${message}</p>
                            <button onclick="closeDevelopmentModal()" 
                                    class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                حسناً
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function closeDevelopmentModal() {
            const modal = document.getElementById('developmentModal');
            if (modal) {
                modal.remove();
            }
        }

        function showSuccessModal(title, message) {
            const modalHTML = `
                <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-t-xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white">${title}</h3>
                                <button type="button" onclick="closeSuccessModal()" class="text-white hover:text-gray-200">
                                    <i class="ri-close-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-check-line text-2xl text-green-600"></i>
                            </div>
                            <p class="text-gray-700 text-lg">${message}</p>
                            <button onclick="closeSuccessModal()" 
                                    class="mt-6 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                                تمام
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.remove();
            }
        }

        // دالة فتح modal نوع الاستلام
        function openReceiveModal() {
            const modalHTML = `
                <div id="receiveTypeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl">
                        <div class="bg-gradient-to-r from-green-600 to-blue-600 p-6 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold">اختر نوع الاستلام</h3>
                                <button type="button" onclick="closeReceiveModal()" 
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <i class="ri-close-line text-white"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-8 space-y-4">
                            <p class="text-gray-600 text-center mb-6">حدد نوع الاستلام المناسب لقطع الغيار</p>
                            
                            <button type="button" onclick="openNewPartsReceiveModal(); closeReceiveModal();" 
                                    class="w-full bg-blue-50 hover:bg-blue-100 border-2 border-blue-200 hover:border-blue-300 p-6 rounded-xl transition-all duration-300 flex items-center gap-4 text-right">
                                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="ri-file-text-line text-2xl text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-gray-900">قطع غيار جديدة</div>
                                    <div class="text-sm text-gray-600">من فاتورة شراء أو مورد جديد</div>
                                </div>
                            </button>

                            <button type="button" onclick="showDevelopmentModal('استلام قطع غيار تالفة', 'سيتم تطوير وظيفة استلام القطع التالفة من المشاريع قريباً'); closeReceiveModal();" 
                                    class="w-full bg-orange-50 hover:bg-orange-100 border-2 border-orange-200 hover:border-orange-300 p-6 rounded-xl transition-all duration-300 flex items-center gap-4 text-right">
                                <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center">
                                    <i class="ri-error-warning-line text-2xl text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-gray-900">قطع غيار تالفة</div>
                                    <div class="text-sm text-gray-600">استلام قطع غيار تحتاج لإصلاح</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function closeReceiveModal() {
            const modal = document.getElementById('receiveTypeModal');
            if (modal) {
                modal.remove();
            }
        }

        function openExportModal() {
            const modalHTML = `
                <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold">تصدير قطع غيار</h3>
                                <button type="button" onclick="closeExportModal()" 
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <i class="ri-close-line text-white"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-8 space-y-4">
                            <p class="text-gray-600 text-center mb-6">حدد نوع التصدير المناسب</p>
                            
                            <button type="button" onclick="openProjectExportModal(); closeExportModal();" 
                                    class="w-full bg-green-50 hover:bg-green-100 border-2 border-green-200 hover:border-green-300 p-6 rounded-xl transition-all duration-300 flex items-center gap-4 text-right">
                                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i class="ri-truck-line text-2xl text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-gray-900">تصدير للمشاريع</div>
                                    <div class="text-sm text-gray-600">إرسال قطع غيار للمشاريع الجارية</div>
                                </div>
                            </button>

                            <button type="button" onclick="showDevelopmentModal('تصدير لمستودع آخر', 'سيتم تطوير وظيفة النقل بين المستودعات قريباً'); closeExportModal();" 
                                    class="w-full bg-purple-50 hover:bg-purple-100 border-2 border-purple-200 hover:border-purple-300 p-6 rounded-xl transition-all duration-300 flex items-center gap-4 text-right">
                                <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <i class="ri-building-line text-2xl text-purple-600"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-gray-900">نقل لمستودع آخر</div>
                                    <div class="text-sm text-gray-600">نقل قطع غيار بين المستودعات</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function closeExportModal() {
            const modal = document.getElementById('exportModal');
            if (modal) {
                modal.remove();
            }
        }

        function showAllSerialNumbers() {
            const modalHTML = `
                <div id="serialNumbersModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold flex items-center gap-3">
                                    <i class="ri-qr-code-line text-2xl"></i>
                                    الأرقام التسلسلية والباركود
                                </h3>
                                <button type="button" onclick="closeSerialNumbersModal()" 
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <i class="ri-close-line text-white"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="text-center mb-6">
                                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ri-qr-code-line text-3xl text-purple-600"></i>
                                </div>
                                <p class="text-gray-600">إدارة وطباعة الأرقام التسلسلية لقطع الغيار</p>
                            </div>
                            
                            <div class="space-y-4">
                                <button type="button" onclick="showDevelopmentModal('طباعة جميع الباركود', 'سيتم تطوير وظيفة طباعة جميع الباركود قريباً'); closeSerialNumbersModal();" 
                                        class="w-full bg-blue-50 hover:bg-blue-100 border-2 border-blue-200 hover:border-blue-300 p-4 rounded-xl transition-all duration-300 flex items-center gap-3">
                                    <i class="ri-printer-line text-2xl text-blue-600"></i>
                                    <span class="font-semibold text-gray-900">طباعة جميع الباركود</span>
                                </button>
                                
                                <button type="button" onclick="showDevelopmentModal('تصدير قائمة الأرقام', 'سيتم تطوير وظيفة تصدير القائمة قريباً'); closeSerialNumbersModal();" 
                                        class="w-full bg-green-50 hover:bg-green-100 border-2 border-green-200 hover:border-green-300 p-4 rounded-xl transition-all duration-300 flex items-center gap-3">
                                    <i class="ri-file-excel-line text-2xl text-green-600"></i>
                                    <span class="font-semibold text-gray-900">تصدير إلى Excel</span>
                                </button>
                                
                                <button type="button" onclick="showDevelopmentModal('مراجعة الأرقام', 'سيتم تطوير وظيفة مراجعة وتدقيق الأرقام قريباً'); closeSerialNumbersModal();" 
                                        class="w-full bg-orange-50 hover:bg-orange-100 border-2 border-orange-200 hover:border-orange-300 p-4 rounded-xl transition-all duration-300 flex items-center gap-3">
                                    <i class="ri-search-line text-2xl text-orange-600"></i>
                                    <span class="font-semibold text-gray-900">مراجعة وتدقيق</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function closeSerialNumbersModal() {
            const modal = document.getElementById('serialNumbersModal');
            if (modal) {
                modal.remove();
            }
        }

        // مودال استلام قطع غيار جديدة مفصل
        function openNewPartsReceiveModal() {
            const modalHTML = `
                <div id="newPartsReceiveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                        <div class="bg-gradient-to-r from-blue-600 to-green-600 p-6 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold flex items-center gap-3">
                                    <i class="ri-add-box-line text-3xl"></i>
                                    استلام قطع غيار جديدة
                                </h3>
                                <button type="button" onclick="closeNewPartsReceiveModal()" 
                                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <i class="ri-close-line text-white text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <form id="newPartsForm" class="p-8">
                            <!-- معلومات المورد -->
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="ri-building-line text-blue-600"></i>
                                    معلومات المورد
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم المورد *</label>
                                        <select id="supplierName" name="supplier_id" required onchange="handleSupplierChange(this)"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">اختر المورد</option>
                                            <option value="1" data-code="SUP001" data-phone="966501234567" data-email="info@alrajhi-parts.com">
                                                شركة الراجحي لقطع الغيار
                                            </option>
                                            <option value="2" data-code="SUP002" data-phone="966505678901" data-email="sales@almutlaq-supply.com">
                                                مؤسسة المطلق للتوريدات
                                            </option>
                                            <option value="3" data-code="SUP003" data-phone="966512345678" data-email="orders@binladin-parts.com">
                                                مجموعة بن لادن لقطع الغيار
                                            </option>
                                            <option value="4" data-code="SUP004" data-phone="966556789012" data-email="support@alsalamah-trading.com">
                                                شركة السلامة للتجارة والتوريد
                                            </option>
                                            <option value="5" data-code="SUP005" data-phone="966509876543" data-email="info@alothaim-industrial.com">
                                                العثيم للمعدات الصناعية
                                            </option>
                                            <option value="6" data-code="SUP006" data-phone="966534567890" data-email="sales@zamil-parts.com">
                                                مجموعة الزامل لقطع الغيار
                                            </option>
                                            <option value="7" data-code="SUP007" data-phone="966567890123" data-email="orders@aldrees-supply.com">
                                                شركة الدريس للتوريدات الفنية
                                            </option>
                                            <option value="8" data-code="SUP008" data-phone="966578901234" data-email="info@almana-parts.com">
                                                مؤسسة المانع لقطع الغيار
                                            </option>
                                            <option value="new" data-code="NEW" data-phone="" data-email="">
                                                + إضافة مورد جديد
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الفاتورة *</label>
                                        <input type="text" id="invoiceNumber" name="invoice_number" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="رقم الفاتورة">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">كود المورد</label>
                                        <input type="text" id="supplierCode" name="supplier_code" readonly
                                               class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg"
                                               placeholder="سيظهر تلقائياً">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">هاتف المورد</label>
                                        <input type="text" id="supplierPhone" name="supplier_phone" readonly
                                               class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg"
                                               placeholder="سيظهر تلقائياً">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الفاتورة *</label>
                                        <input type="date" id="invoiceDate" name="invoice_date" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">المبلغ الإجمالي</label>
                                        <input type="number" id="totalAmount" name="total_amount" step="0.01"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="المبلغ الإجمالي">
                                    </div>
                                </div>
                            </div>

                            <!-- قطع الغيار -->
                            <div class="mb-8">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                        <i class="ri-tools-line text-green-600"></i>
                                        قطع الغيار
                                    </h4>
                                    <button type="button" onclick="addSparePartRow()" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                        <i class="ri-add-line"></i>
                                        إضافة قطعة
                                    </button>
                                </div>
                                
                                <div id="sparePartsContainer">
                                    <div class="spare-part-row bg-gray-50 p-4 rounded-lg mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">اسم القطعة *</label>
                                                <select name="spare_parts[0][name]" id="sparePartName_0" required onchange="handleSparePartNameChange(0, this)"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">اختر نوع قطعة الغيار</option>
                                                    
                                                    <!-- قطع كهربائية -->
                                                    <optgroup label="⚡ قطع كهربائية">
                                                        <option value="محرك كهربائي" data-category="ELC" data-desc="محرك كهربائي للمعدات الصناعية">محرك كهربائي</option>
                                                        <option value="مفتاح كهربائي" data-category="ELC" data-desc="مفتاح تحكم كهربائي">مفتاح كهربائي</option>
                                                        <option value="كابل كهربائي" data-category="ELC" data-desc="كابل نقل الطاقة الكهربائية">كابل كهربائي</option>
                                                        <option value="مقاومة كهربائية" data-category="ELC" data-desc="مقاومة للدوائر الكهربائية">مقاومة كهربائية</option>
                                                        <option value="مكثف كهربائي" data-category="ELC" data-desc="مكثف لتخزين الطاقة">مكثف كهربائي</option>
                                                        <option value="ترانس كهربائي" data-category="ELC" data-desc="محول كهربائي">ترانس كهربائي</option>
                                                    </optgroup>
                                                    
                                                    <!-- قطع ميكانيكية -->
                                                    <optgroup label="⚙️ قطع ميكانيكية">
                                                        <option value="محمل معدني" data-category="MEC" data-desc="محمل للأجزاء المتحركة">محمل معدني</option>
                                                        <option value="ترس معدني" data-category="MEC" data-desc="ترس نقل الحركة">ترس معدني</option>
                                                        <option value="سير ناقل" data-category="BLT" data-desc="سير نقل الحركة">سير ناقل</option>
                                                        <option value="زنبرك معدني" data-category="SPG" data-desc="زنبرك مرن">زنبرك معدني</option>
                                                        <option value="جوان مطاطي" data-category="GSK" data-desc="جوان منع التسريب">جوان مطاطي</option>
                                                        <option value="برغي معدني" data-category="FAS" data-desc="برغي تثبيت">برغي معدني</option>
                                                        <option value="صامولة معدنية" data-category="FAS" data-desc="صامولة تثبيت">صامولة معدنية</option>
                                                    </optgroup>
                                                    
                                                    <!-- مضخات وصمامات -->
                                                    <optgroup label="🚰 مضخات وصمامات">
                                                        <option value="مضخة مياه" data-category="PMP" data-desc="مضخة ضخ المياه">مضخة مياه</option>
                                                        <option value="مضخة زيت" data-category="PMP" data-desc="مضخة ضخ الزيت">مضخة زيت</option>
                                                        <option value="صمام تحكم" data-category="VAL" data-desc="صمام للتحكم في التدفق">صمام تحكم</option>
                                                        <option value="صمام أمان" data-category="VAL" data-desc="صمام الأمان والحماية">صمام أمان</option>
                                                        <option value="صمام كرة" data-category="VAL" data-desc="صمام كروي">صمام كرة</option>
                                                        <option value="صمام فراشة" data-category="VAL" data-desc="صمام فراشة للتحكم">صمام فراشة</option>
                                                    </optgroup>
                                                    
                                                    <!-- حساسات ومقاييس -->
                                                    <optgroup label="📊 حساسات ومقاييس">
                                                        <option value="حساس حرارة" data-category="SEN" data-desc="حساس قياس الحرارة">حساس حرارة</option>
                                                        <option value="حساس ضغط" data-category="SEN" data-desc="حساس قياس الضغط">حساس ضغط</option>
                                                        <option value="حساس رطوبة" data-category="SEN" data-desc="حساس قياس الرطوبة">حساس رطوبة</option>
                                                        <option value="مقياس تدفق" data-category="SEN" data-desc="مقياس معدل التدفق">مقياس تدفق</option>
                                                        <option value="حساس مستوى" data-category="SEN" data-desc="حساس قياس المستوى">حساس مستوى</option>
                                                    </optgroup>
                                                    
                                                    <!-- فلاتر ومرشحات -->
                                                    <optgroup label="🔍 فلاتر ومرشحات">
                                                        <option value="فلتر هواء" data-category="FLT" data-desc="فلتر تنقية الهواء">فلتر هواء</option>
                                                        <option value="فلتر مياه" data-category="FLT" data-desc="فلتر تنقية المياه">فلتر مياه</option>
                                                        <option value="فلتر زيت" data-category="FLT" data-desc="فلتر تنقية الزيت">فلتر زيت</option>
                                                        <option value="فلتر وقود" data-category="FLT" data-desc="فلتر تنقية الوقود">فلتر وقود</option>
                                                    </optgroup>
                                                    
                                                    <!-- تكييف وتبريد -->
                                                    <optgroup label="❄️ تكييف وتبريد">
                                                        <option value="كمبروسر تكييف" data-category="HVC" data-desc="ضاغط مكيف الهواء">كمبروسر تكييف</option>
                                                        <option value="مروحة تكييف" data-category="HVC" data-desc="مروحة نظام التكييف">مروحة تكييف</option>
                                                        <option value="ملف تبريد" data-category="HVC" data-desc="ملف التبريد">ملف تبريد</option>
                                                        <option value="ثرموستات" data-category="HVC" data-desc="منظم الحرارة">ثرموستات</option>
                                                        <option value="غاز تبريد" data-category="HVC" data-desc="غاز المبرد">غاز تبريد</option>
                                                    </optgroup>
                                                    
                                                    <!-- أدوات السلامة -->
                                                    <optgroup label="🦺 أدوات السلامة">
                                                        <option value="مطفأة حريق" data-category="SAF" data-desc="مطفأة للحماية من الحريق">مطفأة حريق</option>
                                                        <option value="كاشف دخان" data-category="SAF" data-desc="جهاز كشف الدخان">كاشف دخان</option>
                                                        <option value="إنذار حريق" data-category="SAF" data-desc="جهاز إنذار الحريق">إنذار حريق</option>
                                                        <option value="مخرج طوارئ" data-category="SAF" data-desc="لوحة مخرج الطوارئ">مخرج طوارئ</option>
                                                        <option value="كاميرا مراقبة" data-category="SAF" data-desc="كاميرا نظام المراقبة">كاميرا مراقبة</option>
                                                    </optgroup>
                                                    
                                                    <!-- أدوات ومعدات -->
                                                    <optgroup label="🔧 أدوات ومعدات">
                                                        <option value="مفك براغي" data-category="TOL" data-desc="مفك لفك وربط البراغي">مفك براغي</option>
                                                        <option value="مفتاح إنجليزي" data-category="TOL" data-desc="مفتاح قابل للتعديل">مفتاح إنجليزي</option>
                                                        <option value="كماشة" data-category="TOL" data-desc="كماشة للإمساك">كماشة</option>
                                                        <option value="منشار معدني" data-category="TOL" data-desc="منشار للقطع">منشار معدني</option>
                                                        <option value="مثقاب كهربائي" data-category="TOL" data-desc="مثقاب للثقب">مثقاب كهربائي</option>
                                                    </optgroup>
                                                    
                                                    <!-- أخرى -->
                                                    <optgroup label="📦 أخرى">
                                                        <option value="أخرى - حدد في الوصف" data-category="GEN" data-desc="نوع آخر - يرجى التحديد في الوصف">أخرى - حدد في الوصف</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الكود *</label>
                                                <div class="flex gap-2">
                                                    <input type="text" name="spare_parts[0][code]" id="sparePartCode_0" required readonly
                                                           class="flex-1 px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                                                           placeholder="سيتم التوليد تلقائياً">
                                                    <button type="button" onclick="generateSparePartCode(0)" 
                                                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                                                            title="توليد كود جديد">
                                                        <i class="ri-refresh-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية *</label>
                                                <input type="number" name="spare_parts[0][quantity]" required min="1"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="الكمية" onchange="calculateRowTotal(0)">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">سعر الوحدة *</label>
                                                <input type="number" name="spare_parts[0][unit_price]" required step="0.01"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="سعر الوحدة" onchange="calculateRowTotal(0)">
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                                                <textarea name="spare_parts[0][description]" rows="2"
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                          placeholder="وصف قطعة الغيار"></textarea>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">إجمالي السعر</label>
                                                    <input type="text" id="rowTotal_0" readonly
                                                           class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                                                           placeholder="0.00 ريال">
                                                </div>
                                                <button type="button" onclick="removeSparePartRow(this)" 
                                                        class="mt-6 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ملاحظات -->
                            <div class="mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="أي ملاحظات إضافية"></textarea>
                            </div>

                            <!-- أزرار الحفظ -->
                            <div class="flex justify-end gap-4 pt-6 border-t">
                                <button type="button" onclick="closeNewPartsReceiveModal()" 
                                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                    إلغاء
                                </button>
                                <button type="submit" 
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                                    <i class="ri-save-line"></i>
                                    حفظ وإضافة إلى المخزون
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // إضافة معالج النموذج
            setTimeout(() => {
                // توليد كود تلقائي للصف الأول
                generateSparePartCode(0);

                const form = document.getElementById('newPartsForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const submitButton = form.querySelector('button[type="submit"]');

                        // تعطيل الزر أثناء الإرسال
                        submitButton.disabled = true;
                        submitButton.innerHTML =
                            '<i class="ri-loader-4-line animate-spin"></i> جاري الحفظ...';

                        // محاكاة عملية الحفظ
                        setTimeout(() => {
                            showSuccessModal('تم بنجاح',
                                'تم حفظ البيانات بنجاح وإضافتها إلى المخزون');
                            closeNewPartsReceiveModal();
                        }, 2000);
                    });
                }
            }, 100);
        }

        function closeNewPartsReceiveModal() {
            const modal = document.getElementById('newPartsReceiveModal');
            if (modal) {
                modal.remove();
            }
        }

        // دوال إدارة قطع الغيار في النموذج
        function addSparePartRow() {
            const container = document.getElementById('sparePartsContainer');
            const rowCount = container.children.length;

            const newRowHTML = `
                <div class="spare-part-row bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم القطعة *</label>
                            <select name="spare_parts[${rowCount}][name]" id="sparePartName_${rowCount}" required onchange="handleSparePartNameChange(${rowCount}, this)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">اختر نوع قطعة الغيار</option>
                                
                                <!-- قطع كهربائية -->
                                <optgroup label="⚡ قطع كهربائية">
                                    <option value="محرك كهربائي" data-category="ELC" data-desc="محرك كهربائي للمعدات الصناعية">محرك كهربائي</option>
                                    <option value="مفتاح كهربائي" data-category="ELC" data-desc="مفتاح تحكم كهربائي">مفتاح كهربائي</option>
                                    <option value="كابل كهربائي" data-category="ELC" data-desc="كابل نقل الطاقة الكهربائية">كابل كهربائي</option>
                                    <option value="مقاومة كهربائية" data-category="ELC" data-desc="مقاومة للدوائر الكهربائية">مقاومة كهربائية</option>
                                    <option value="مكثف كهربائي" data-category="ELC" data-desc="مكثف لتخزين الطاقة">مكثف كهربائي</option>
                                    <option value="ترانس كهربائي" data-category="ELC" data-desc="محول كهربائي">ترانس كهربائي</option>
                                </optgroup>
                                
                                <!-- قطع ميكانيكية -->
                                <optgroup label="⚙️ قطع ميكانيكية">
                                    <option value="محمل معدني" data-category="MEC" data-desc="محمل للأجزاء المتحركة">محمل معدني</option>
                                    <option value="ترس معدني" data-category="MEC" data-desc="ترس نقل الحركة">ترس معدني</option>
                                    <option value="سير ناقل" data-category="BLT" data-desc="سير نقل الحركة">سير ناقل</option>
                                    <option value="زنبرك معدني" data-category="SPG" data-desc="زنبرك مرن">زنبرك معدني</option>
                                    <option value="جوان مطاطي" data-category="GSK" data-desc="جوان منع التسريب">جوان مطاطي</option>
                                    <option value="برغي معدني" data-category="FAS" data-desc="برغي تثبيت">برغي معدني</option>
                                    <option value="صامولة معدنية" data-category="FAS" data-desc="صامولة تثبيت">صامولة معدنية</option>
                                </optgroup>
                                
                                <!-- مضخات وصمامات -->
                                <optgroup label="🚰 مضخات وصمامات">
                                    <option value="مضخة مياه" data-category="PMP" data-desc="مضخة ضخ المياه">مضخة مياه</option>
                                    <option value="مضخة زيت" data-category="PMP" data-desc="مضخة ضخ الزيت">مضخة زيت</option>
                                    <option value="صمام تحكم" data-category="VAL" data-desc="صمام للتحكم في التدفق">صمام تحكم</option>
                                    <option value="صمام أمان" data-category="VAL" data-desc="صمام الأمان والحماية">صمام أمان</option>
                                    <option value="صمام كرة" data-category="VAL" data-desc="صمام كروي">صمام كرة</option>
                                    <option value="صمام فراشة" data-category="VAL" data-desc="صمام فراشة للتحكم">صمام فراشة</option>
                                </optgroup>
                                
                                <!-- حساسات ومقاييس -->
                                <optgroup label="📊 حساسات ومقاييس">
                                    <option value="حساس حرارة" data-category="SEN" data-desc="حساس قياس الحرارة">حساس حرارة</option>
                                    <option value="حساس ضغط" data-category="SEN" data-desc="حساس قياس الضغط">حساس ضغط</option>
                                    <option value="حساس رطوبة" data-category="SEN" data-desc="حساس قياس الرطوبة">حساس رطوبة</option>
                                    <option value="مقياس تدفق" data-category="SEN" data-desc="مقياس معدل التدفق">مقياس تدفق</option>
                                    <option value="حساس مستوى" data-category="SEN" data-desc="حساس قياس المستوى">حساس مستوى</option>
                                </optgroup>
                                
                                <!-- فلاتر ومرشحات -->
                                <optgroup label="🔍 فلاتر ومرشحات">
                                    <option value="فلتر هواء" data-category="FLT" data-desc="فلتر تنقية الهواء">فلتر هواء</option>
                                    <option value="فلتر مياه" data-category="FLT" data-desc="فلتر تنقية المياه">فلتر مياه</option>
                                    <option value="فلتر زيت" data-category="FLT" data-desc="فلتر تنقية الزيت">فلتر زيت</option>
                                    <option value="فلتر وقود" data-category="FLT" data-desc="فلتر تنقية الوقود">فلتر وقود</option>
                                </optgroup>
                                
                                <!-- تكييف وتبريد -->
                                <optgroup label="❄️ تكييف وتبريد">
                                    <option value="كمبروسر تكييف" data-category="HVC" data-desc="ضاغط مكيف الهواء">كمبروسر تكييف</option>
                                    <option value="مروحة تكييف" data-category="HVC" data-desc="مروحة نظام التكييف">مروحة تكييف</option>
                                    <option value="ملف تبريد" data-category="HVC" data-desc="ملف التبريد">ملف تبريد</option>
                                    <option value="ثرموستات" data-category="HVC" data-desc="منظم الحرارة">ثرموستات</option>
                                    <option value="غاز تبريد" data-category="HVC" data-desc="غاز المبرد">غاز تبريد</option>
                                </optgroup>
                                
                                <!-- أدوات السلامة -->
                                <optgroup label="🦺 أدوات السلامة">
                                    <option value="مطفأة حريق" data-category="SAF" data-desc="مطفأة للحماية من الحريق">مطفأة حريق</option>
                                    <option value="كاشف دخان" data-category="SAF" data-desc="جهاز كشف الدخان">كاشف دخان</option>
                                    <option value="إنذار حريق" data-category="SAF" data-desc="جهاز إنذار الحريق">إنذار حريق</option>
                                    <option value="مخرج طوارئ" data-category="SAF" data-desc="لوحة مخرج الطوارئ">مخرج طوارئ</option>
                                    <option value="كاميرا مراقبة" data-category="SAF" data-desc="كاميرا نظام المراقبة">كاميرا مراقبة</option>
                                </optgroup>
                                
                                <!-- أدوات ومعدات -->
                                <optgroup label="🔧 أدوات ومعدات">
                                    <option value="مفك براغي" data-category="TOL" data-desc="مفك لفك وربط البراغي">مفك براغي</option>
                                    <option value="مفتاح إنجليزي" data-category="TOL" data-desc="مفتاح قابل للتعديل">مفتاح إنجليزي</option>
                                    <option value="كماشة" data-category="TOL" data-desc="كماشة للإمساك">كماشة</option>
                                    <option value="منشار معدني" data-category="TOL" data-desc="منشار للقطع">منشار معدني</option>
                                    <option value="مثقاب كهربائي" data-category="TOL" data-desc="مثقاب للثقب">مثقاب كهربائي</option>
                                </optgroup>
                                
                                <!-- أخرى -->
                                <optgroup label="📦 أخرى">
                                    <option value="أخرى - حدد في الوصف" data-category="GEN" data-desc="نوع آخر - يرجى التحديد في الوصف">أخرى - حدد في الوصف</option>
                                </optgroup>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الكود *</label>
                            <div class="flex gap-2">
                                <input type="text" name="spare_parts[${rowCount}][code]" id="sparePartCode_${rowCount}" required readonly
                                       class="flex-1 px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                                       placeholder="سيتم التوليد تلقائياً">
                                <button type="button" onclick="generateSparePartCode(${rowCount})" 
                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                                        title="توليد كود جديد">
                                    <i class="ri-refresh-line"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الكمية *</label>
                            <input type="number" name="spare_parts[${rowCount}][quantity]" required min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="الكمية" onchange="calculateRowTotal(${rowCount})">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">سعر الوحدة *</label>
                            <input type="number" name="spare_parts[${rowCount}][unit_price]" required step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="سعر الوحدة" onchange="calculateRowTotal(${rowCount})">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                            <textarea name="spare_parts[${rowCount}][description]" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="وصف قطعة الغيار"></textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">إجمالي السعر</label>
                                <input type="text" id="rowTotal_${rowCount}" readonly
                                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                                       placeholder="0.00 ريال">
                            </div>
                            <button type="button" onclick="removeSparePartRow(this)" 
                                    class="mt-6 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', newRowHTML);

            // توليد كود تلقائي للصف الجديد
            generateSparePartCode(rowCount);
        }

        function removeSparePartRow(button) {
            const container = document.getElementById('sparePartsContainer');
            if (container.children.length > 1) {
                button.closest('.spare-part-row').remove();
            } else {
                showErrorModal('تحذير', 'يجب أن تكون هناك قطعة غيار واحدة على الأقل');
            }
        }

        function calculateRowTotal(rowIndex) {
            const quantityInput = document.querySelector(`input[name="spare_parts[${rowIndex}][quantity]"]`);
            const priceInput = document.querySelector(`input[name="spare_parts[${rowIndex}][unit_price]"]`);
            const totalInput = document.getElementById(`rowTotal_${rowIndex}`);

            if (quantityInput && priceInput && totalInput) {
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = quantity * price;

                totalInput.value = total.toFixed(2) + ' ريال';
            }
        }

        // مودال الخطأ
        function showErrorModal(title, message) {
            const modalHTML = `
                <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 rounded-t-xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white">${title}</h3>
                                <button type="button" onclick="closeErrorModal()" class="text-white hover:text-gray-200">
                                    <i class="ri-close-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-error-warning-line text-2xl text-red-600"></i>
                            </div>
                            <p class="text-gray-700 text-lg">${message}</p>
                            <button onclick="closeErrorModal()" 
                                    class="mt-6 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors">
                                إغلاق
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            if (modal) {
                modal.remove();
            }
        }

        // مودال تصدير للمشاريع مع نموذج مفصل
        function openProjectExportModal() {
            const modalHTML = `
                <div id="projectExportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                        <div class="bg-gradient-to-r from-green-600 to-blue-600 p-6 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold flex items-center gap-3">
                                    <i class="ri-truck-line text-3xl"></i>
                                    تصدير قطع غيار للمشاريع
                                </h3>
                                <button type="button" onclick="closeProjectExportModal()" 
                                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <i class="ri-close-line text-white text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <form id="projectExportForm" class="p-8">
                            <!-- معلومات المشروع -->
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="ri-building-4-line text-green-600"></i>
                                    معلومات المشروع
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم المشروع *</label>
                                        <select id="projectName" name="project_id" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">اختر المشروع</option>
                                            <option value="1">مشروع برج الرياض</option>
                                            <option value="2">مشروع مجمع جدة التجاري</option>
                                            <option value="3">مشروع فيلا الدمام</option>
                                            <option value="4">مشروع مكاتب الخبر</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم طلب الصرف *</label>
                                        <input type="text" id="requestNumber" name="request_number" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="رقم طلب الصرف">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ التصدير *</label>
                                        <input type="date" id="exportDate" name="export_date" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               value="${new Date().toISOString().split('T')[0]}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم المستلم *</label>
                                        <input type="text" id="receiverName" name="receiver_name" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="اسم المستلم في الموقع">
                                    </div>
                                </div>
                            </div>

                            <!-- قطع الغيار المطلوبة -->
                            <div class="mb-8">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                        <i class="ri-tools-line text-green-600"></i>
                                        قطع الغيار المطلوبة
                                    </h4>
                                    <button type="button" onclick="addExportPartRow()" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                        <i class="ri-add-line"></i>
                                        إضافة قطعة
                                    </button>
                                </div>
                                
                                <div id="exportPartsContainer">
                                    <div class="export-part-row bg-gray-50 p-4 rounded-lg mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">قطعة الغيار *</label>
                                                <select name="export_parts[0][spare_part_id]" required
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        onchange="updateAvailableStock(0, this)">
                                                    <option value="">اختر قطعة الغيار</option>
                                                    <option value="1" data-stock="25">مضخة المياه - كود: P001</option>
                                                    <option value="2" data-stock="15">صمام التحكم - كود: V002</option>
                                                    <option value="3" data-stock="30">محرك كهربائي - كود: M003</option>
                                                    <option value="4" data-stock="8">حساس الحرارة - كود: S004</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">المتوفر في المخزن</label>
                                                <input type="text" id="availableStock_0" readonly
                                                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                                                       placeholder="0">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية المطلوبة *</label>
                                                <input type="number" name="export_parts[0][quantity]" required min="1"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="الكمية المطلوبة">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" onclick="removeExportPartRow(this)" 
                                                        class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                                    <i class="ri-delete-bin-line"></i>
                                                    حذف
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات خاصة بهذه القطعة</label>
                                            <textarea name="export_parts[0][notes]" rows="2"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                      placeholder="أي ملاحظات خاصة بهذه القطعة"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ملاحظات عامة -->
                            <div class="mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات عامة</label>
                                <textarea id="generalNotes" name="general_notes" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="ملاحظات عامة حول عملية التصدير"></textarea>
                            </div>

                            <!-- أزرار العمل -->
                            <div class="flex justify-end gap-4 pt-6 border-t">
                                <button type="button" onclick="closeProjectExportModal()" 
                                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                    إلغاء
                                </button>
                                <button type="button" onclick="previewExportForm()"
                                        class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                                    <i class="ri-eye-line"></i>
                                    معاينة
                                </button>
                                <button type="submit" 
                                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                                    <i class="ri-check-line"></i>
                                    تأكيد التصدير
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // إضافة معالج النموذج
            setTimeout(() => {
                const form = document.getElementById('projectExportForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const submitButton = form.querySelector('button[type="submit"]');

                        // تعطيل الزر أثناء الإرسال
                        submitButton.disabled = true;
                        submitButton.innerHTML =
                            '<i class="ri-loader-4-line animate-spin"></i> جاري التصدير...';

                        // محاكاة عملية التصدير
                        setTimeout(() => {
                            showSuccessModal('تم التصدير بنجاح',
                                'تم تصدير قطع الغيار للمشروع بنجاح وتحديث المخزون');
                            closeProjectExportModal();
                        }, 2500);
                    });
                }
            }, 100);
        }

        function closeProjectExportModal() {
            const modal = document.getElementById('projectExportModal');
            if (modal) {
                modal.remove();
            }
        }

        // دوال إدارة قطع الغيار في التصدير
        function addExportPartRow() {
            const container = document.getElementById('exportPartsContainer');
            const rowCount = container.children.length;

            const newRowHTML = `
                <div class="export-part-row bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">قطعة الغيار *</label>
                            <select name="export_parts[${rowCount}][spare_part_id]" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    onchange="updateAvailableStock(${rowCount}, this)">
                                <option value="">اختر قطعة الغيار</option>
                                <option value="1" data-stock="25">مضخة المياه - كود: P001</option>
                                <option value="2" data-stock="15">صمام التحكم - كود: V002</option>
                                <option value="3" data-stock="30">محرك كهربائي - كود: M003</option>
                                <option value="4" data-stock="8">حساس الحرارة - كود: S004</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">المتوفر في المخزن</label>
                            <input type="text" id="availableStock_${rowCount}" readonly
                                   class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الكمية المطلوبة *</label>
                            <input type="number" name="export_parts[${rowCount}][quantity]" required min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="الكمية المطلوبة">
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="removeExportPartRow(this)" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                <i class="ri-delete-bin-line"></i>
                                حذف
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات خاصة بهذه القطعة</label>
                        <textarea name="export_parts[${rowCount}][notes]" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="أي ملاحظات خاصة بهذه القطعة"></textarea>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', newRowHTML);
        }

        function removeExportPartRow(button) {
            const container = document.getElementById('exportPartsContainer');
            if (container.children.length > 1) {
                button.closest('.export-part-row').remove();
            } else {
                showErrorModal('تحذير', 'يجب أن تكون هناك قطعة غيار واحدة على الأقل للتصدير');
            }
        }

        function updateAvailableStock(rowIndex, selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock') || '0';
            const stockInput = document.getElementById(`availableStock_${rowIndex}`);
            if (stockInput) {
                stockInput.value = stock;
            }
        }

        function previewExportForm() {
            showDevelopmentModal('معاينة التصدير', 'سيتم تطوير وظيفة معاينة التصدير قبل التأكيد قريباً');
        }

        // وظائف إدارة الموردين
        function handleSupplierChange(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const supplierCode = selectedOption.getAttribute('data-code');
            const supplierPhone = selectedOption.getAttribute('data-phone');
            const supplierEmail = selectedOption.getAttribute('data-email');

            // تحديث حقول معلومات المورد
            const codeField = document.getElementById('supplierCode');
            const phoneField = document.getElementById('supplierPhone');

            if (codeField) codeField.value = supplierCode || '';
            if (phoneField) phoneField.value = supplierPhone || '';

            // إذا اختار "إضافة مورد جديد"
            if (selectElement.value === 'new') {
                openNewSupplierModal();
            }
        }

        function openNewSupplierModal() {
            const modalHTML = `
                <div id="newSupplierModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl">
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-6 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold flex items-center gap-3">
                                    <i class="ri-building-add-line text-2xl"></i>
                                    إضافة مورد جديد
                                </h3>
                                <button type="button" onclick="closeNewSupplierModal()" 
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <i class="ri-close-line text-white"></i>
                                </button>
                            </div>
                        </div>
                        
                        <form id="newSupplierForm" class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم المورد *</label>
                                    <input type="text" id="newSupplierName" name="name" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="اسم الشركة أو المؤسسة">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">كود المورد *</label>
                                    <input type="text" id="newSupplierCode" name="code" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="SUP009" value="SUP${String(Math.floor(Math.random() * 900) + 100)}">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف *</label>
                                    <input type="tel" id="newSupplierPhone" name="phone" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="966XXXXXXXXX">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                                    <input type="email" id="newSupplierEmail" name="email"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="info@supplier.com">
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                                <textarea id="newSupplierAddress" name="address" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="عنوان المورد"></textarea>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">نوع التخصص</label>
                                <select id="newSupplierCategory" name="category"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">اختر نوع التخصص</option>
                                    <option value="electrical">قطع كهربائية</option>
                                    <option value="mechanical">قطع ميكانيكية</option>
                                    <option value="plumbing">قطع سباكة</option>
                                    <option value="hvac">تكييف وتبريد</option>
                                    <option value="safety">معدات السلامة</option>
                                    <option value="tools">أدوات ومعدات</option>
                                    <option value="general">عام</option>
                                </select>
                            </div>
                            
                            <div class="flex justify-end gap-4 pt-6 border-t">
                                <button type="button" onclick="closeNewSupplierModal()" 
                                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                    إلغاء
                                </button>
                                <button type="submit" 
                                        class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                                    <i class="ri-save-line"></i>
                                    حفظ المورد
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // معالج النموذج
            setTimeout(() => {
                const form = document.getElementById('newSupplierForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(form);
                        const supplierName = formData.get('name');
                        const supplierCode = formData.get('code');
                        const supplierPhone = formData.get('phone');
                        const supplierEmail = formData.get('email');

                        // إضافة المورد الجديد للقائمة
                        addNewSupplierToList(supplierName, supplierCode, supplierPhone, supplierEmail);

                        // إغلاق المودال
                        closeNewSupplierModal();

                        // رسالة نجاح
                        showSuccessModal('تمت الإضافة بنجاح',
                            `تم إضافة المورد "${supplierName}" إلى القائمة بنجاح`);
                    });
                }
            }, 100);
        }

        function closeNewSupplierModal() {
            const modal = document.getElementById('newSupplierModal');
            if (modal) {
                modal.remove();
            }

            // إعادة تعيين قائمة الموردين للخيار الأول
            const supplierSelect = document.getElementById('supplierName');
            if (supplierSelect) {
                supplierSelect.selectedIndex = 0;
                handleSupplierChange(supplierSelect);
            }
        }

        function addNewSupplierToList(name, code, phone, email) {
            const supplierSelect = document.getElementById('supplierName');
            if (supplierSelect) {
                // إنشاء خيار جديد
                const newOption = document.createElement('option');
                const newId = Date.now(); // استخدام timestamp كمعرف مؤقت

                newOption.value = newId;
                newOption.setAttribute('data-code', code);
                newOption.setAttribute('data-phone', phone);
                newOption.setAttribute('data-email', email);
                newOption.textContent = name;

                // إضافة الخيار قبل خيار "إضافة مورد جديد"
                const newSupplierOption = supplierSelect.querySelector('option[value="new"]');
                supplierSelect.insertBefore(newOption, newSupplierOption);

                // اختيار المورد الجديد
                supplierSelect.value = newId;
                handleSupplierChange(supplierSelect);
            }
        }

        // دالة توليد كود قطعة الغيار التلقائي
        function generateSparePartCode(rowIndex) {
            const codeInput = document.getElementById(`sparePartCode_${rowIndex}`);
            if (codeInput) {
                const categories = [
                    'ELC', // Electrical - كهربائية
                    'MEC', // Mechanical - ميكانيكية
                    'PLB', // Plumbing - سباكة
                    'HVC', // HVAC - تكييف وتهوية
                    'SAF', // Safety - سلامة
                    'TOL', // Tools - أدوات
                    'PMP', // Pumps - مضخات
                    'VAL', // Valves - صمامات
                    'SEN', // Sensors - حساسات
                    'FLT', // Filters - فلاتر
                    'BLT', // Belts - سيور
                    'BRG', // Bearings - محامل
                    'GSK', // Gaskets - جوانات
                    'SPG', // Springs - زنبركات
                    'FAS' // Fasteners - مثبتات
                ];

                // اختيار فئة عشوائية
                const randomCategory = categories[Math.floor(Math.random() * categories.length)];

                // رقم عشوائي من 4 أرقام
                const randomNumber = String(Math.floor(Math.random() * 9000) + 1000);

                // تاريخ اليوم بصيغة مختصرة (آخر رقمين من السنة + شهر)
                const today = new Date();
                const year = String(today.getFullYear()).slice(-2);
                const month = String(today.getMonth() + 1).padStart(2, '0');

                // تكوين الكود: فئة + رقم عشوائي + سنة + شهر
                const generatedCode = `${randomCategory}${randomNumber}${year}${month}`;

                codeInput.value = generatedCode;

                // إضافة تأثير بصري لإظهار التحديث
                codeInput.style.backgroundColor = '#dcfce7'; // أخضر فاتح
                setTimeout(() => {
                    codeInput.style.backgroundColor = '#f9fafb'; // رجوع للون الأصلي
                }, 1000);
            }
        }

        // دالة توليد كود ذكي بناءً على اسم القطعة
        function generateSmartSparePartCode(rowIndex, partName = '') {
            const codeInput = document.getElementById(`sparePartCode_${rowIndex}`);
            if (codeInput) {
                let category = 'GEN'; // عام كافتراضي

                // تحديد الفئة بناءً على اسم القطعة
                const partNameLower = partName.toLowerCase();
                if (partNameLower.includes('مضخة') || partNameLower.includes('pump')) {
                    category = 'PMP';
                } else if (partNameLower.includes('صمام') || partNameLower.includes('valve')) {
                    category = 'VAL';
                } else if (partNameLower.includes('محرك') || partNameLower.includes('motor')) {
                    category = 'MOT';
                } else if (partNameLower.includes('حساس') || partNameLower.includes('sensor')) {
                    category = 'SEN';
                } else if (partNameLower.includes('فلتر') || partNameLower.includes('filter')) {
                    category = 'FLT';
                } else if (partNameLower.includes('كهرب') || partNameLower.includes('electric')) {
                    category = 'ELC';
                } else if (partNameLower.includes('ميكانيك') || partNameLower.includes('mechanical')) {
                    category = 'MEC';
                } else if (partNameLower.includes('سباك') || partNameLower.includes('plumb')) {
                    category = 'PLB';
                } else if (partNameLower.includes('تكييف') || partNameLower.includes('hvac')) {
                    category = 'HVC';
                } else if (partNameLower.includes('أمان') || partNameLower.includes('safety')) {
                    category = 'SAF';
                }

                // رقم عشوائي من 4 أرقام
                const randomNumber = String(Math.floor(Math.random() * 9000) + 1000);

                // تاريخ اليوم
                const today = new Date();
                const year = String(today.getFullYear()).slice(-2);
                const month = String(today.getMonth() + 1).padStart(2, '0');

                const generatedCode = `${category}${randomNumber}${year}${month}`;

                codeInput.value = generatedCode;

                // تأثير بصري
                codeInput.style.backgroundColor = '#dbeafe'; // أزرق فاتح
                setTimeout(() => {
                    codeInput.style.backgroundColor = '#f9fafb';
                }, 1000);
            }
        }

        // معالج تغيير اسم قطعة الغيار
        function handleSparePartNameChange(rowIndex, selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const category = selectedOption.getAttribute('data-category');
            const description = selectedOption.getAttribute('data-desc');
            const partName = selectElement.value;

            // تحديث الوصف تلقائياً
            const descriptionField = document.querySelector(`textarea[name="spare_parts[${rowIndex}][description]"]`);
            if (descriptionField && description) {
                descriptionField.value = description;

                // تأثير بصري للوصف
                descriptionField.style.backgroundColor = '#f0f9ff'; // أزرق فاتح جداً
                setTimeout(() => {
                    descriptionField.style.backgroundColor = '#ffffff';
                }, 1500);
            }

            // توليد كود ذكي بناءً على الفئة والاسم
            if (category && partName) {
                generateSmartSparePartCodeFromCategory(rowIndex, category, partName);
            }
        }

        // دالة توليد كود ذكي بناءً على الفئة
        function generateSmartSparePartCodeFromCategory(rowIndex, category, partName) {
            const codeInput = document.getElementById(`sparePartCode_${rowIndex}`);
            if (codeInput) {
                // رقم عشوائي من 4 أرقام
                const randomNumber = String(Math.floor(Math.random() * 9000) + 1000);

                // تاريخ اليوم
                const today = new Date();
                const year = String(today.getFullYear()).slice(-2);
                const month = String(today.getMonth() + 1).padStart(2, '0');

                const generatedCode = `${category}${randomNumber}${year}${month}`;

                codeInput.value = generatedCode;

                // تأثير بصري مميز للكود الذكي
                codeInput.style.backgroundColor = '#ecfdf5'; // أخضر فاتح جداً
                codeInput.style.borderColor = '#10b981'; // حدود خضراء
                setTimeout(() => {
                    codeInput.style.backgroundColor = '#f9fafb';
                    codeInput.style.borderColor = '#d1d5db';
                }, 2000);
            }
        }

        // فلترة جدول قطع الغيار
        function filterSparePartsTable() {
            const searchTerm = document.getElementById('searchSparePartName').value.toLowerCase();
            const categoryFilter = document.getElementById('filterSparePartCategory').value;
            const tableRows = document.querySelectorAll('#sparePartsTable tbody tr');

            tableRows.forEach(row => {
                // تأكد من أن هذا ليس السطر الفارغ (empty state)
                if (row.children.length < 7) return;

                const partName = row.children[0].textContent.toLowerCase();
                const category = row.getAttribute('data-category');

                const matchesSearch = partName.includes(searchTerm);
                const matchesCategory = !categoryFilter || category === categoryFilter;

                if (matchesSearch && matchesCategory) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // إحصائيات الفلترة
            updateFilterStats();
        }

        // تحديث إحصائيات الفلترة
        function updateFilterStats() {
            const visibleRows = document.querySelectorAll(
                '#sparePartsTable tbody tr[style=""], #sparePartsTable tbody tr:not([style])');
            const totalRows = document.querySelectorAll('#sparePartsTable tbody tr').length;

            // يمكن إضافة عرض إحصائيات الفلترة هنا إذا لزم الأمر
            console.log(`عرض ${visibleRows.length} من أصل ${totalRows} قطعة غيار`);
        }

        // تصدير جدول قطع الغيار إلى Excel
        function exportSparePartsTable() {
            showDevelopmentModal(
                'تصدير Excel',
                'سيتم إضافة ميزة تصدير البيانات إلى Excel قريباً. ستتضمن الميزة:<br>• تصدير جميع البيانات<br>• فلترة حسب النوع والفئة<br>• تنسيق احترافي مع الألوان',
                'ri-file-excel-line'
            );
        }

        // طباعة جدول قطع الغيار
        function printSparePartsTable() {
            // الحصول على البيانات المفلترة فقط
            const visibleRows = Array.from(document.querySelectorAll('#sparePartsTable tbody tr')).filter(row =>
                row.style.display !== 'none' && row.children.length >= 7
            );

            if (visibleRows.length === 0) {
                alert('لا توجد بيانات لطباعتها');
                return;
            }

            let tableHTML = `
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">اسم قطعة الغيار</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">الكود</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">الكمية الجديدة</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">الكمية التالفة</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">إجمالي الكمية</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">السعر</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            visibleRows.forEach(row => {
                tableHTML += `
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">${row.children[0].textContent}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${row.children[1].textContent}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${row.children[2].textContent}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${row.children[3].textContent}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${row.children[4].textContent}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${row.children[5].textContent}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${row.children[6].textContent}</td>
                    </tr>
                `;
            });

            tableHTML += '</tbody></table>';

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>تقرير أنواع قطع الغيار المسجلة</title>
                    <meta charset="UTF-8">
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            direction: rtl; 
                            text-align: right; 
                            margin: 20px;
                        }
                        h1 { 
                            text-align: center; 
                            color: #1f2937; 
                            margin-bottom: 10px;
                        }
                        .header-info {
                            text-align: center;
                            color: #6b7280;
                            margin-bottom: 30px;
                        }
                        @media print {
                            body { margin: 0; }
                            .header-info { margin-bottom: 20px; }
                        }
                    </style>
                </head>
                <body>
                    <h1>تقرير أنواع قطع الغيار المسجلة</h1>
                    <div class="header-info">
                        <p>المستودع: {{ $warehouse->name }}</p>
                        <p>تاريخ الطباعة: ${new Date().toLocaleDateString('ar-EG')}</p>
                        <p>عدد الأنواع المعروضة: ${visibleRows.length}</p>
                    </div>
                    ${tableHTML}
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();

            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }
    </script>
@endsection
