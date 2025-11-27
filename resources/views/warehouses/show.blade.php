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
                                // تجميع قطع الغيار حسب الاسم والكود من المخزون الجديد والتالف
                                $groupedParts = [];

                                // إضافة قطع الغيار الجديدة
                                foreach ($newInventory as $item) {
                                    $partName = $item->sparePart->name;
                                    $partCode = $item->sparePart->code;
                                    $key = $partName . '_' . $partCode;

                                    if (!isset($groupedParts[$key])) {
                                        $groupedParts[$key] = [
                                            'name' => $partName,
                                            'code' => $partCode,
                                            'price' => $item->sparePart->unit_price ?? 0,
                                            'new_stock' => 0,
                                            'damaged_stock' => 0,
                                        ];
                                    }

                                    $groupedParts[$key]['new_stock'] += $item->current_stock;
                                }

                                // إضافة قطع الغيار التالفة
                                foreach ($damagedInventory as $item) {
                                    $partName = $item->sparePart->name;
                                    $partCode = $item->sparePart->code;
                                    $key = $partName . '_' . $partCode;

                                    if (!isset($groupedParts[$key])) {
                                        $groupedParts[$key] = [
                                            'name' => $partName,
                                            'code' => $partCode,
                                            'price' => $item->sparePart->unit_price ?? 0,
                                            'new_stock' => 0,
                                            'damaged_stock' => 0,
                                        ];
                                    }

                                    $groupedParts[$key]['damaged_stock'] += $item->current_stock;
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

        <!-- قسم استلامات قطع الغيار التالفة -->
        <div class="bg-white rounded-xl shadow-sm border mt-6">
            <div class="flex items-center justify-between p-6 border-b">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ri-file-damage-line text-xl text-red-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">استلامات قطع الغيار التالفة</h2>
                        <p class="text-sm text-gray-600">{{ $damagedPartsReceipts->total() }} استلام</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('damaged-parts-receipts.create') }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="ri-add-line"></i>
                        إضافة استلام جديد
                    </a>
                </div>
            </div>

            <div class="p-6">
                @if($damagedPartsReceipts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-right">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 rounded-tr-lg">رقم الاستلام</th>
                                    <th scope="col" class="px-6 py-3">تاريخ الاستلام</th>
                                    <th scope="col" class="px-6 py-3">المشروع</th>
                                    <th scope="col" class="px-6 py-3">قطعة الغيار</th>
                                    <th scope="col" class="px-6 py-3">الكمية</th>
                                    <th scope="col" class="px-6 py-3">حالة التلف</th>
                                    <th scope="col" class="px-6 py-3">حالة المعالجة</th>
                                    <th scope="col" class="px-6 py-3">مُستلمة بواسطة</th>
                                    <th scope="col" class="px-6 py-3 rounded-tl-lg">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($damagedPartsReceipts as $receipt)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $receipt->receipt_number }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $receipt->receipt_date->format('Y-m-d') }}
                                            <br>
                                            <span class="text-xs text-gray-500">{{ $receipt->receipt_time }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $receipt->project->name ?? 'غير محدد' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                {{ $receipt->sparePart->name ?? 'غير محدد' }}
                                                @if($receipt->sparePart)
                                                    <br>
                                                    <span class="text-xs text-gray-500">{{ $receipt->sparePart->code }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="font-semibold text-red-600">{{ $receipt->quantity_received }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                                {{ $receipt->damage_condition_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'received' => 'bg-blue-100 text-blue-800',
                                                    'under_evaluation' => 'bg-yellow-100 text-yellow-800',
                                                    'approved_repair' => 'bg-green-100 text-green-800',
                                                    'approved_replace' => 'bg-purple-100 text-purple-800',
                                                    'disposed' => 'bg-gray-100 text-gray-800',
                                                    'returned_fixed' => 'bg-emerald-100 text-emerald-800',
                                                ];
                                                $colorClass = $statusColors[$receipt->processing_status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 text-xs rounded-full {{ $colorClass }}">
                                                {{ $receipt->processing_status_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $receipt->receivedByEmployee->name ?? 'غير محدد' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('damaged-parts-receipts.show', $receipt) }}" 
                                                   class="text-blue-600 hover:text-blue-900" title="عرض التفاصيل">
                                                    <i class="ri-eye-line text-lg"></i>
                                                </a>
                                                <a href="{{ route('damaged-parts-receipts.edit', $receipt) }}" 
                                                   class="text-green-600 hover:text-green-900" title="تعديل">
                                                    <i class="ri-edit-line text-lg"></i>
                                                </a>
                                                <form action="{{ route('damaged-parts-receipts.destroy', $receipt) }}" 
                                                      method="POST" class="inline" 
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الاستلام؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="حذف">
                                                        <i class="ri-delete-bin-line text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($damagedPartsReceipts->hasPages())
                        <div class="mt-6">
                            {{ $damagedPartsReceipts->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class="ri-file-damage-line text-4xl text-gray-400 mb-3"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد استلامات قطع غيار تالفة</h3>
                        <p class="text-gray-600 mb-4">لم يتم تسجيل أي استلامات لقطع غيار تالفة في هذا المستودع بعد</p>
                        <a href="{{ route('damaged-parts-receipts.create') }}" 
                           class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="ri-add-line"></i>
                            إضافة أول استلام
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Exported Parts Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-6">
            <div class="bg-gradient-to-r from-cyan-600 to-blue-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="ri-send-plane-line text-2xl"></i>
                        <div>
                            <h2 class="text-2xl font-bold">القطع المُصَدَّرة</h2>
                            <p class="text-cyan-100">سجل القطع التي تم تصديرها من هذا المستودع</p>
                        </div>
                    </div>
                    <span class="bg-white bg-opacity-20 px-4 py-2 rounded-lg font-semibold">{{ $exportedParts->total() }} عملية تصدير</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">التاريخ</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">اسم القطعة</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الكود</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">الكمية</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">السعر</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">المجموع</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">المعدة</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">المُصدِّر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exportedParts as $transaction)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($transaction->sparePart)
                                    <span class="font-medium text-gray-900">{{ $transaction->sparePart->name }}</span>
                                @else
                                    <span class="text-gray-500">غير محدد</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dir-ltr">
                                {{ $transaction->sparePart?->code ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center text-gray-900">
                                {{ $transaction->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dir-ltr">
                                {{ number_format($transaction->unit_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dir-ltr">
                                {{ number_format($transaction->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($transaction->equipment)
                                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">
                                        {{ $transaction->equipment->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-xs">لم يتم تحديد معدة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($transaction->user)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $transaction->user->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500">غير محدد</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="ri-inbox-line text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-600 font-medium">لا توجد عمليات تصدير</p>
                                    <p class="text-gray-500 text-sm mt-1">لم يتم تصدير أي قطع غيار من هذا المستودع حتى الآن</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($exportedParts->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        عرض <span class="font-semibold">{{ $exportedParts->from() ?? 0 }}</span> إلى <span class="font-semibold">{{ $exportedParts->to() ?? 0 }}</span> من <span class="font-semibold">{{ $exportedParts->total() }}</span> عملية
                    </div>
                    <nav class="flex gap-2">
                        @if($exportedParts->onFirstPage())
                        <button disabled class="px-3 py-2 text-gray-400 cursor-not-allowed">
                            <i class="ri-arrow-right-line"></i>
                        </button>
                        @else
                        <a href="{{ $exportedParts->previousPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ri-arrow-right-line"></i>
                        </a>
                        @endif

                        @foreach($exportedParts->getUrlRange(1, $exportedParts->lastPage()) as $page => $url)
                            @if($page == $exportedParts->currentPage())
                            <button class="px-3 py-2 bg-blue-600 text-white rounded-lg font-medium">{{ $page }}</button>
                            @else
                            <a href="{{ $url }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($exportedParts->hasMorePages())
                        <a href="{{ $exportedParts->nextPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ri-arrow-left-line"></i>
                        </a>
                        @else
                        <button disabled class="px-3 py-2 text-gray-400 cursor-not-allowed">
                            <i class="ri-arrow-left-line"></i>
                        </button>
                        @endif
                    </nav>
                </div>
            </div>
            @endif
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
            console.log('openReceiveModal called'); // Debug log
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

            console.log('Adding modal to DOM'); // Debug log
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            console.log('Modal added successfully'); // Debug log
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
                                <button type="button" onclick="printAllBarcodes();"
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

        // دالة طباعة جميع الباركودات
        function printAllBarcodes() {
            closeSerialNumbersModal();

            // إنشاء نافذة طباعة جديدة
            const printWindow = window.open('', '', 'width=900,height=600');

            // جمع البيانات من الصفحة
            const warehouse = {
                name: document.querySelector('h1')?.textContent.replace(/\s+/g, ' ').trim().split(' - ')[1] || 'المستودع',
                date: new Date().toLocaleDateString('ar-SA')
            };

            // البحث عن جميع الأرقام التسلسلية في الجدول
            const serialNumbers = [];
            const rows = document.querySelectorAll('table tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 3) {
                    serialNumbers.push({
                        partName: cells[0]?.textContent.trim() || 'غير محدد',
                        serialNumber: cells[1]?.textContent.trim() || 'غير محدد',
                        barcode: cells[2]?.textContent.trim() || 'غير محدد',
                        quantity: cells[3]?.textContent.trim() || '1'
                    });
                }
            });

            // إذا لم نجد بيانات من الجدول، نستخدم بيانات من المتغيرات العامة
            if (serialNumbers.length === 0) {
                // جمع من جميع الصفحة
                const barcodeElements = document.querySelectorAll('[data-barcode]');
                barcodeElements.forEach(el => {
                    serialNumbers.push({
                        partName: el.getAttribute('data-part-name') || 'غير محدد',
                        serialNumber: el.getAttribute('data-serial') || 'غير محدد',
                        barcode: el.getAttribute('data-barcode') || 'غير محدد',
                        quantity: 1
                    });
                });
            }

            // إذا لم نجد بيانات، نعرض رسالة خطأ
            if (serialNumbers.length === 0) {
                showSuccessModal('لا توجد بيانات', 'لم يتم العثور على أي أرقام تسلسلية أو باركود في هذا المستودع');
                return;
            }

            // إنشاء محتوى الطباعة
            const html = `
                <!DOCTYPE html>
                <html dir="rtl" lang="ar">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>طباعة الباركودات</title>
                    <style>
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }
                        body {
                            font-family: 'Arial', sans-serif;
                            direction: rtl;
                            padding: 20px;
                            background: #f5f5f5;
                        }
                        .print-container {
                            background: white;
                            max-width: 900px;
                            margin: 0 auto;
                        }
                        .header {
                            text-align: center;
                            padding: 20px;
                            border-bottom: 2px solid #333;
                            margin-bottom: 20px;
                        }
                        .header h1 {
                            font-size: 24px;
                            margin-bottom: 5px;
                            color: #333;
                        }
                        .header p {
                            color: #666;
                            font-size: 14px;
                        }
                        .barcodes-grid {
                            display: grid;
                            grid-template-columns: repeat(2, 1fr);
                            gap: 20px;
                            padding: 20px;
                        }
                        .barcode-item {
                            border: 1px solid #ddd;
                            padding: 15px;
                            text-align: center;
                            page-break-inside: avoid;
                            border-radius: 4px;
                        }
                        .barcode-item h3 {
                            font-size: 12px;
                            margin-bottom: 10px;
                            color: #333;
                            max-height: 40px;
                            overflow: hidden;
                        }
                        .barcode-image {
                            margin: 10px 0;
                            text-align: center;
                        }
                        .barcode-image img {
                            max-width: 100%;
                            height: auto;
                        }
                        .barcode-number {
                            font-weight: bold;
                            margin: 10px 0 5px;
                            font-size: 11px;
                        }
                        .serial-number {
                            font-size: 10px;
                            color: #666;
                        }
                        @media print {
                            body {
                                background: white;
                                padding: 0;
                            }
                            .barcodes-grid {
                                padding: 10px;
                            }
                            .barcode-item {
                                break-inside: avoid;
                            }
                            .no-print {
                                display: none;
                            }
                        }
                        .print-controls {
                            text-align: center;
                            padding: 20px;
                            border-top: 2px solid #ddd;
                        }
                        .print-controls button {
                            padding: 10px 20px;
                            margin: 0 10px;
                            font-size: 14px;
                            cursor: pointer;
                            border: none;
                            border-radius: 4px;
                            background: #007bff;
                            color: white;
                        }
                        .print-controls button:hover {
                            background: #0056b3;
                        }
                        .close-btn {
                            background: #6c757d;
                        }
                        .close-btn:hover {
                            background: #5a6268;
                        }
                    </style>
                </head>
                <body>
                    <div class="print-container">
                        <div class="header">
                            <h1>${warehouse.name}</h1>
                            <p>قائمة الأرقام التسلسلية والباركود</p>
                            <p style="margin-top: 10px; font-size: 12px;">التاريخ: ${warehouse.date}</p>
                        </div>

                        <div class="barcodes-grid">
                            ${serialNumbers.map((item, index) => `
                                <div class="barcode-item">
                                    <h3>${item.partName}</h3>
                                    <div class="barcode-image" id="barcode-${index}"></div>
                                    <div class="barcode-number">${item.barcode}</div>
                                    <div class="serial-number">الرقم: ${item.serialNumber}</div>
                                </div>
                            `).join('')}
                        </div>

                        <div class="print-controls no-print">
                            <button onclick="window.print()">طباعة</button>
                            <button class="close-btn" onclick="window.close()">إغلاق</button>
                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
                    <script>
                        // توليد الباركودات باستخدام JsBarcode
                        document.addEventListener('DOMContentLoaded', function() {
                            const serialNumbers = ${JSON.stringify(serialNumbers)};

                            serialNumbers.forEach((item, index) => {
                                const container = document.getElementById('barcode-' + index);
                                if (container && item.barcode !== 'غير محدد') {
                                    JsBarcode("#barcode-" + index, item.barcode, {
                                        format: "CODE128",
                                        width: 2,
                                        height: 50,
                                        displayValue: false
                                    });
                                }
                            });
                        });
                    <\/script>
                </body>
                </html>
            `;

            printWindow.document.write(html);
            printWindow.document.close();

            // إظهار رسالة نجاح
            showSuccessModal('تم فتح نافذة الطباعة', 'تم إعداد ' + serialNumbers.length + ' باركود للطباعة. استخدم Ctrl+P أو الزر "طباعة" لطباعة البيانات.');
        }

        // مودال استلام قطع غيار جديدة مفصل
        function openNewPartsReceiveModal() {
            const modalHTML = `
                <div id="newPartsReceiveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-4xl w-full overflow-y-auto shadow-2xl" style="max-height: 90vh;">
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
                                            @foreach($sparePartSuppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                        data-code="{{ $supplier->id }}"
                                                        data-phone="{{ $supplier->phone ?? '' }}"
                                                        data-email="{{ $supplier->email ?? '' }}">
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
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
                        const formData = new FormData(form);

                        // تعطيل الزر أثناء الإرسال
                        submitButton.disabled = true;
                        submitButton.innerHTML =
                            '<i class="ri-loader-4-line animate-spin"></i> جاري الحفظ...';

                        // تحويل بيانات النموذج إلى صيغة صحيحة للـ API
                        const data = {
                            invoice_number: document.getElementById('invoiceNumber').value,
                            supplier_id: document.getElementById('supplierName').value,
                            invoice_date: document.getElementById('invoiceDate').value,
                            notes: document.getElementById('notes').value,
                            items: []
                        };

                        // جمع بيانات قطع الغيار
                        try {
                            const sparePartsContainer = document.getElementById('sparePartsContainer');
                            const sparePartRows = sparePartsContainer.querySelectorAll('.spare-part-row');

                            sparePartRows.forEach((row, index) => {
                                const nameSelect = row.querySelector(`select[name="spare_parts[${index}][name]"]`);
                                const quantityInput = row.querySelector(`input[name="spare_parts[${index}][quantity]"]`);
                                const priceInput = row.querySelector(`input[name="spare_parts[${index}][unit_price]"]`);
                                const descInput = row.querySelector(`textarea[name="spare_parts[${index}][description]"]`);

                                // Validate required fields
                                if (!nameSelect || !nameSelect.value) {
                                    throw new Error(`الرجاء اختيار اسم قطعة غيار في الصف ${index + 1}`);
                                }
                                if (!quantityInput || !quantityInput.value) {
                                    throw new Error(`الرجاء إدخال الكمية في الصف ${index + 1}`);
                                }
                                if (!priceInput || !priceInput.value) {
                                    throw new Error(`الرجاء إدخال السعر في الصف ${index + 1}`);
                                }

                                // Get or use category from option
                                const selectedOption = nameSelect.querySelector(`option[value="${nameSelect.value}"]`);
                                const category = selectedOption?.getAttribute('data-category') || 'GEN';

                                data.items.push({
                                    name: nameSelect.value,
                                    spare_part_type_id: 1, // Default ID - will be matched by name if needed
                                    quantity: parseInt(quantityInput.value),
                                    unit_price: parseFloat(priceInput.value),
                                    description: descInput?.value || '',
                                    notes: ''
                                });
                            });

                            // التحقق من وجود عناصر
                            if (data.items.length === 0) {
                                throw new Error('الرجاء إضافة واحدة على الأقل من قطع الغيار');
                            }
                        } catch (validationError) {
                            showErrorModal('خطأ في التحقق', validationError.message);
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="ri-save-line"></i> حفظ وإضافة إلى المخزون';
                            return;
                        }

                        // إرسال البيانات إلى الخادم
                        console.log('Sending data:', JSON.stringify(data));
                        fetch(`/warehouses/{{ $warehouse->id }}/receive-new-spares`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            const contentType = response.headers.get('content-type');
                            console.log('Content-type:', contentType);

                            if (contentType && contentType.includes('application/json')) {
                                return response.json().then(json => ({ status: response.status, json }));
                            } else {
                                return response.text().then(text => {
                                    console.log('Non-JSON response:', text.substring(0, 200));
                                    throw new Error('استجابة غير صحيحة من الخادم: ' + text.substring(0, 100));
                                });
                            }
                        })
                        .then(({ status, json }) => {
                            console.log('Parsed JSON:', json);
                            if (status !== 200 && status !== 201) {
                                throw new Error(json.message || 'حدث خطأ أثناء معالجة الطلب');
                            }
                            if (!json.success) {
                                throw new Error(json.message || 'فشل الحفظ');
                            }
                            showSuccessModal('تم بنجاح',
                                json.message || 'تم حفظ البيانات بنجاح وإضافتها إلى المخزون');
                            closeNewPartsReceiveModal();
                            // إعادة تحميل الصفحة بعد ثانيتين
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        })
                        .catch(error => {
                            console.error('Full error:', error);
                            let errorMessage = 'حدث خطأ أثناء حفظ البيانات';
                            if (error.message) {
                                errorMessage = error.message;
                            } else if (error.errors) {
                                errorMessage = Object.values(error.errors).flat().join('\n');
                            }
                            showErrorModal('خطأ', errorMessage);
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="ri-save-line"></i> حفظ وإضافة إلى المخزون';
                        });
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

        // متغيرات عام لتخزين البيانات للاستخدام في دوال مختلفة
        let exportModalData = {
            locationsData: [],
            employeesData: [],
            sparePartsData: []
        };

        // مودال تصدير للمشاريع مع نموذج مفصل
        function openProjectExportModal() {
            // الحصول على البيانات من الخادم
            exportModalData.locationsData = @json($locationsForJson);
            exportModalData.employeesData = @json($employeesForJson);
            exportModalData.sparePartsData = @json($sparePartsForJson);

            const modalHTML = `
                <div id="projectExportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-4xl w-full overflow-y-auto shadow-2xl" style="max-height: 90vh;">
                        <div class="bg-gradient-to-r from-green-600 to-blue-600 p-6 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold flex items-center gap-3">
                                    <i class="ri-truck-line text-3xl"></i>
                                    تصدير قطع غيار للمواقع
                                </h3>
                                <button type="button" onclick="closeProjectExportModal()"
                                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <i class="ri-close-line text-white text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <form id="projectExportForm" class="p-8">
                            <!-- معلومات الموقع -->
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="ri-map-pin-line text-green-600"></i>
                                    معلومات الموقع
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">الموقع/المستودع *</label>
                                        <select id="locationId" name="location_id" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">اختر الموقع</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم طلب الصرف *</label>
                                        <input type="text" id="requestNumber" name="request_number" readonly
                                               class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg"
                                               placeholder="سيتم التوليد تلقائياً">
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
                                        <!-- placeholder -->
                                    </div>
                                </div>
                                <input type="hidden" id="projectId" name="project_id">
                                <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم المستلم *</label>
                                        <select id="receiverId" name="receiver_id" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">اختر الموظف</option>
                                        </select>
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
                                    <button type="button" onclick="addExportPartRow(exportModalData.sparePartsData)"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                        <i class="ri-add-line"></i>
                                        إضافة قطعة
                                    </button>
                                </div>

                                <div id="exportPartsContainer">
                                    <!-- سيتم ملؤه ديناميكياً -->
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

            // تهيئة المودال بعد الإنشاء مع انتظار أطول
            setTimeout(() => {
                initializeExportModal(exportModalData.locationsData, exportModalData.employeesData, exportModalData.sparePartsData);
            }, 300);
        }

        function initializeExportModal(locationsData, employeesData, sparePartsData) {
            // حفظ البيانات في المتغير العام للوصول إليها من دوال أخرى
            exportModalData.locationsData = locationsData;
            exportModalData.employeesData = employeesData;
            exportModalData.sparePartsData = sparePartsData;
            const form = document.getElementById('projectExportForm');
            const locationId = document.getElementById('locationId');
            const receiverId = document.getElementById('receiverId');
            const requestNumber = document.getElementById('requestNumber');
            const projectId = document.getElementById('projectId');
            const container = document.getElementById('exportPartsContainer');

            // توليد رقم طلب صرف تلقائي
            const generateRequestNumber = () => {
                const date = new Date();
                const dateStr = date.getFullYear() + '' + String(date.getMonth() + 1).padStart(2, '0');
                const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
                return 'EXP-' + dateStr + '-' + random;
            };

            // تعيين رقم الطلب عند الفتح
            requestNumber.value = generateRequestNumber();

            // ملء قائمة المواقع
            locationsData.forEach(loc => {
                const option = document.createElement('option');
                option.value = loc.id;
                option.textContent = loc.name;
                locationId.appendChild(option);
            });

            // معالج تغيير الموقع سيتم التعامل معه عبر Select2 أدناه

            // ملء قائمة الموظفين (المستلمين)
            employeesData.forEach(emp => {
                const option = document.createElement('option');
                option.value = emp.id;
                option.textContent = emp.name + (emp.position ? ' - ' + emp.position : '');
                receiverId.appendChild(option);
            });

            // تفعيل Select2 للبحث في القوائم المنسدلة
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                try {
                    // قم بتدمير أي instance قديم من Select2 أولاً
                    if (jQuery('#locationId').data('select2')) {
                        jQuery('#locationId').select2('destroy');
                    }
                    if (jQuery('#receiverId').data('select2')) {
                        jQuery('#receiverId').select2('destroy');
                    }

                    // تهيئة Select2 للمواقع
                    jQuery('#locationId').select2({
                        language: 'ar',
                        dir: 'rtl',
                        placeholder: 'اختر الموقع',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: jQuery('#projectExportModal')
                    });

                    // تهيئة Select2 للموظفين
                    jQuery('#receiverId').select2({
                        language: 'ar',
                        dir: 'rtl',
                        placeholder: 'اختر الموظف',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: jQuery('#projectExportModal')
                    });

                    // معالج تغيير الموقع في Select2 - حفظ project_id للموقع المختار
                    jQuery('#locationId').on('change', function() {
                        const selectedLocationId = jQuery(this).val();
                        if (!selectedLocationId) {
                            projectId.value = '';
                            return;
                        }

                        const selectedLocation = locationsData.find(loc => String(loc.id) === String(selectedLocationId));

                        if (selectedLocation && selectedLocation.project_id) {
                            projectId.value = selectedLocation.project_id;
                        } else {
                            projectId.value = '';
                        }
                    });
                } catch (error) {
                    console.error('Select2 initialization error:', error);
                }
            }

            // إضافة أول صف من قطع الغيار
            if (container.children.length === 0 && sparePartsData.length > 0) {
                addExportPartRow(sparePartsData);
            }

            // معالج النموذج
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const submitButton = form.querySelector('button[type="submit"]');
                    const locationIdValue = document.getElementById('locationId').value;
                    const receiverIdValue = document.getElementById('receiverId').value;
                    const exportDateValue = document.getElementById('exportDate').value;
                    const generalNotesValue = document.getElementById('generalNotes').value;

                    // التحقق من البيانات المطلوبة
                    if (!locationIdValue) {
                        showErrorModal('خطأ', 'يرجى اختيار الموقع');
                        return;
                    }
                    if (!receiverIdValue) {
                        showErrorModal('خطأ', 'يرجى اختيار المستلم');
                        return;
                    }
                    if (!exportDateValue) {
                        showErrorModal('خطأ', 'يرجى اختيار تاريخ التصدير');
                        return;
                    }

                    // جمع بيانات قطع الغيار
                    const exportParts = [];
                    const partRows = form.querySelectorAll('.export-part-row');

                    if (partRows.length === 0) {
                        showErrorModal('خطأ', 'يرجى إضافة قطعة غيار واحدة على الأقل');
                        return;
                    }

                    partRows.forEach((row, rowIndex) => {
                        // استخدم جميع الـ selects والـ inputs في الصف بدلاً من البحث بـ index محدد
                        const partIdSelect = row.querySelector('select');
                        const inputs = row.querySelectorAll('input[type="number"]');
                        const textarea = row.querySelector('textarea');
                        const quantityInput = inputs.length > 0 ? inputs[0] : null;

                        console.log(`Row ${rowIndex}:`, { partIdSelect: partIdSelect?.value, quantityInput: quantityInput?.value });

                        if (partIdSelect && partIdSelect.value && quantityInput && quantityInput.value) {
                            exportParts.push({
                                spare_part_id: parseInt(partIdSelect.value),
                                quantity: parseInt(quantityInput.value),
                                purpose: 'تصدير لقطع الغيار',
                                equipment_id: null,
                                notes: textarea ? textarea.value : ''
                            });
                        }
                    });

                    if (exportParts.length === 0) {
                        showErrorModal('خطأ', 'يرجى ملء بيانات قطع الغيار');
                        return;
                    }

                    // تعطيل الزر أثناء الإرسال
                    submitButton.disabled = true;
                    submitButton.innerHTML =
                        '<i class="ri-loader-4-line animate-spin"></i> جاري التصدير...';

                    // إرسال البيانات إلى الخادم
                    const formData = {
                        location_id: locationIdValue,
                        recipient_employee_id: receiverIdValue,
                        export_date: exportDateValue,
                        items: exportParts,
                        general_notes: generalNotesValue
                    };

                    console.log('البيانات المرسلة:', JSON.stringify(formData, null, 2));

                    fetch(`/warehouses/{{ $warehouse->id }}/export-spares`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => {
                        console.log('Status:', response.status);
                        console.log('OK:', response.ok);

                        // حاول قراءة الـ response كـ text أولاً للتحقق
                        return response.text().then(text => {
                            console.log('الاستجابة الخام (أول 500 حرف):', text.substring(0, 500));

                            try {
                                const data = JSON.parse(text);
                                return { status: response.status, ok: response.ok, data: data };
                            } catch (e) {
                                console.error('خطأ في parsing JSON:', e);
                                throw new Error(`استجابة غير صحيحة من الخادم (${response.status}): ${text.substring(0, 100)}`);
                            }
                        });
                    })
                    .then(result => {
                        if (!result.ok) {
                            const errorMsg = result.data.message || 'حدث خطأ في التصدير';
                            throw new Error(errorMsg);
                        }
                        showSuccessModal('تم التصدير بنجاح',
                            'تم تصدير قطع الغيار بنجاح وتحديث المخزون');
                        closeProjectExportModal();
                        // إعادة تحميل الصفحة بعد ثانيتين
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('خطأ كامل:', error);
                        let errorMessage = 'حدث خطأ في التصدير';

                        // محاولة الحصول على رسالة خطأ أفضل من الخطأ
                        if (error.message) {
                            if (error.message.includes('<')) {
                                // إذا كانت الرسالة HTML، حاول استخراج الرسالة منها
                                const parser = new DOMParser();
                                const htmlDoc = parser.parseFromString(error.message, 'text/html');
                                const errorElement = htmlDoc.querySelector('[class*="error"]') || htmlDoc.body;
                                errorMessage = errorElement.textContent.trim() || 'حدث خطأ في التصدير';
                            } else {
                                errorMessage = error.message;
                            }
                        }

                        showErrorModal('خطأ', errorMessage);
                        submitButton.disabled = false;
                        submitButton.innerHTML =
                            '<i class="ri-check-line"></i> تأكيد التصدير';
                    });
                });
            }
        }

        function closeProjectExportModal() {
            const modal = document.getElementById('projectExportModal');
            if (modal) {
                modal.remove();
            }
        }

        // دوال إدارة قطع الغيار في التصدير
        function addExportPartRow(sparePartsData) {
            const container = document.getElementById('exportPartsContainer');
            const rowCount = container.children.length;

            let optionsHTML = '<option value="">اختر قطعة الغيار</option>';
            sparePartsData.forEach(part => {
                optionsHTML += `<option value="${part.id}" data-stock="${part.stock}">${part.name} - (${part.code}) - المتوفر: ${part.stock}</option>`;
            });

            const newRowHTML = `
                <div class="export-part-row bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">قطعة الغيار *</label>
                            <select name="export_parts[${rowCount}][spare_part_id]" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    onchange="updateAvailableStock(${rowCount}, this)">
                                ${optionsHTML}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">المتوفر في المخزن</label>
                            <input type="text" id="availableStock_${rowCount}" readonly
                                   class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg font-bold"
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
                // تغيير اللون بناءً على المخزون
                if (parseInt(stock) === 0) {
                    stockInput.classList.add('bg-red-100');
                    stockInput.classList.remove('bg-gray-100', 'bg-green-100');
                } else if (parseInt(stock) < 5) {
                    stockInput.classList.add('bg-yellow-100');
                    stockInput.classList.remove('bg-gray-100', 'bg-green-100');
                } else {
                    stockInput.classList.add('bg-green-100');
                    stockInput.classList.remove('bg-gray-100', 'bg-yellow-100');
                }
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
                        const supplierAddress = formData.get('address');

                        // إرسال البيانات إلى الخادم
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        fetch('{{ route("spare-part-suppliers.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                name: supplierName,
                                phone: supplierPhone,
                                email: supplierEmail,
                                address: supplierAddress,
                                status: 'نشط',
                                credit_limit: 0,
                                company_name: null,
                                phone_2: null,
                                city: null,
                                country: null,
                                tax_number: null,
                                cr_number: null,
                                contact_person: null,
                                contact_person_phone: null,
                                notes: null,
                                payment_terms: null
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    // معالجة أخطاء التحقق من الصحة
                                    if (data.errors) {
                                        const errors = Object.values(data.errors).flat();
                                        throw new Error(errors[0] || 'حدث خطأ في إضافة المورد');
                                    }
                                    throw new Error(data.message || 'حدث خطأ في إضافة المورد');
                                }).catch(e => {
                                    throw e;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // إضافة المورد الجديد للقائمة
                            addNewSupplierToList(supplierName, data.id, supplierPhone, supplierEmail, data.id);

                            // إغلاق المودال
                            closeNewSupplierModal();

                            // رسالة نجاح
                            showSuccessModal('تمت الإضافة بنجاح',
                                `تم إضافة المورد "${supplierName}" إلى قاعدة البيانات بنجاح`);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showErrorModal('خطأ', error.message || 'حدث خطأ في إضافة المورد');
                        });
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

        function addNewSupplierToList(name, supplierId, phone, email) {
            const supplierSelect = document.getElementById('supplierName');
            if (supplierSelect) {
                // إنشاء خيار جديد
                const newOption = document.createElement('option');

                newOption.value = supplierId;
                newOption.setAttribute('data-code', supplierId);
                newOption.setAttribute('data-phone', phone || '');
                newOption.setAttribute('data-email', email || '');
                newOption.textContent = name;

                // إضافة الخيار قبل خيار "إضافة مورد جديد"
                const newSupplierOption = supplierSelect.querySelector('option[value="new"]');
                supplierSelect.insertBefore(newOption, newSupplierOption);

                // اختيار المورد الجديد
                supplierSelect.value = supplierId;
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

    @push('styles')
    <style>
        /* تخصيص Select2 */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            min-height: 44px;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151;
            padding-right: 1rem;
        }

        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }

        .select2-results__option {
            padding: 0.75rem 1rem;
        }

        .select2-results__option--highlighted {
            background-color: #3b82f6;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }

        .select2-container--default.select2-container--open .select2-selection--single {
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .select2-search__field {
            font-family: inherit;
            padding: 0.5rem;
        }
    </style>
    @endpush
@endsection
