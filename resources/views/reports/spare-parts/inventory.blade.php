@extends('layouts.app')

@section('title', 'تقرير جرد قطع الغيار')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('reports.spare-parts.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="ri-archive-line text-blue-600"></i>
                        تقرير جرد قطع الغيار
                    </h1>
                    <p class="text-gray-600">عرض حالة المخزون الحالية لجميع قطع الغيار</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-printer-line"></i>
                    طباعة
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <form method="GET" action="{{ route('reports.spare-parts.inventory') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">المستودع</label>
                    <select name="warehouse_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">جميع المستودعات</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $warehouseId == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="low_stock_only" id="low_stock_only" value="1" {{ $lowStockOnly ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="low_stock_only" class="ml-2 block text-sm text-gray-900">
                        عرض القطع التي وصلت للحد الأدنى فقط
                    </label>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-search-line"></i>
                    عرض
                </button>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm font-medium text-gray-600">إجمالي أنواع القطع</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_parts']) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm font-medium text-gray-600">إجمالي عدد القطع</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_stock']) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm font-medium text-gray-600">قيمة المخزون الإجمالية</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_value'], 2) }} ر.س</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm font-medium text-gray-600">قطع عند الحد الأدنى</p>
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['low_stock_items']) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <p class="text-sm font-medium text-gray-600">قطع نفذت من المخزون</p>
                <p class="text-2xl font-bold text-red-600">{{ number_format($stats['out_of_stock_items']) }}</p>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="ri-list-check text-blue-600"></i>
                    قائمة الجرد ({{ $inventory->count() }} صنف)
                </h2>
            </div>

            @if($inventory->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قطعة الغيار</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستودع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المخزون الحالي</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحد الأدنى للمخزون</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">متوسط السعر</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القيمة الإجمالية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($inventory as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ $item->sparePart->name }}</div>
                                <div class="text-gray-500">{{ $item->sparePart->code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->location->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $item->current_stock <= $item->sparePart->minimum_stock ? 'text-red-600' : 'text-gray-900' }}">
                                {{ number_format($item->current_stock) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ number_format($item->sparePart->minimum_stock) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item->average_cost, 2) }} ر.س</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ number_format($item->total_value, 2) }} ر.س</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->current_stock == 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">نفذت</span>
                                @elseif($item->current_stock <= $item->sparePart->minimum_stock)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">مخزون منخفض</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">متوفر</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-archive-line text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد بيانات جرد</h3>
                <p class="text-gray-500">لا توجد بيانات جرد لعرضها حسب الفلاتر المحددة.</p>
            </div>
            @endif
        </div>
    </div>
@endsection
