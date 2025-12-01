@extends('layouts.app')

@section('title', 'تقرير استهلاك المحروقات')

@section('content')

<script>
// إضافة class لفرض الطباعة
if (window.matchMedia('print').matches) {
    document.documentElement.classList.add('print-mode');
    document.body.classList.add('print-mode');
}
</script>
    <div class="space-y-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">تقرير استهلاك المحروقات</h1>
                <p class="mt-1 text-sm text-gray-600">عرض مفصل لاستهلاك المحروقات والزيوت بجميع أنواعها</p>
            </div>
            <a href="{{ route('fuel-management.index') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                <i class="ri-arrow-right-line ml-1"></i>
                العودة للإدارة
            </a>
        </div>

        <!-- Filter Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">من التاريخ</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $startDate->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">إلى التاريخ</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $endDate->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    <i class="ri-search-line ml-1"></i>
                    بحث
                </button>
            </form>
        </div>

        <!-- Summary Cards -->
        @php
            $fuelTypesGrouped = $byFuelType;
            $maxFuelType = collect($fuelTypesGrouped)->keys()->first();
            $maxFuelQuantity = collect($fuelTypesGrouped)->values()->first();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">إجمالي الاستهلاك</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalConsumption, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">لتر</p>
                    </div>
                    <i class="ri-drop-line text-4xl text-blue-500 opacity-20"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">عدد السجلات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $consumptions->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">عملية استهلاك</p>
                    </div>
                    <i class="ri-bar-chart-line text-4xl text-green-500 opacity-20"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">الفترة الزمنية</p>
                        <p class="text-sm font-bold text-gray-900">{{ $startDate->locale('ar')->isoFormat('D MMMM') }}</p>
                        <p class="text-xs text-gray-500 mt-1">إلى {{ $endDate->locale('ar')->isoFormat('D MMMM') }}</p>
                    </div>
                    <i class="ri-calendar-line text-4xl text-purple-500 opacity-20"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">عدد أنواع المحروقات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $byFuelType->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">نوع</p>
                    </div>
                    <i class="ri-gas-station-line text-4xl text-orange-500 opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Consumption by Fuel Type -->
        @if($byFuelType->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">الاستهلاك حسب نوع المحروقات</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($byFuelType as $fuelType => $quantity)
                            @php
                                $fuelTypeMap = [
                                    'diesel' => ['text' => 'ديزل', 'color' => 'blue'],
                                    'gasoline' => ['text' => 'بنزين', 'color' => 'yellow'],
                                    'engine_oil' => ['text' => 'زيت ماكينة', 'color' => 'red'],
                                    'hydraulic_oil' => ['text' => 'زيت هيدروليك', 'color' => 'purple'],
                                    'radiator_water' => ['text' => 'ماء ردياتير', 'color' => 'green'],
                                    'brake_oil' => ['text' => 'زيت فرامل', 'color' => 'indigo'],
                                    'other' => ['text' => 'أخرى', 'color' => 'gray'],
                                ];
                                $info = $fuelTypeMap[$fuelType] ?? ['text' => $fuelType, 'color' => 'gray'];
                                $color = $info['color'];
                            @endphp
                            <div class="border-2 border-{{ $color }}-200 rounded-lg p-4 bg-{{ $color }}-50">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-bold text-gray-900">{{ $info['text'] }}</h3>
                                    <i class="ri-drop-line text-2xl text-{{ $color }}-500"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">الكمية الإجمالية:</span>
                                        <span class="font-bold text-{{ $color }}-700">
                                            {{ number_format($quantity, 2) }} لتر
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">النسبة المئوية:</span>
                                        <span class="font-bold text-{{ $color }}-700">
                                            {{ $totalConsumption > 0 ? number_format(($quantity / $totalConsumption) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Detailed Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">سجلات الاستهلاك التفصيلية</h2>
            </div>

            @if($consumptions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700">التاريخ</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700">المعدة</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700">نوع المحروقات</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700">الكمية</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700">المسجل</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700">الملاحظات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($consumptions as $consumption)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3">
                                        <span class="text-sm text-gray-900">{{ $consumption['date_formatted'] }}</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="text-sm font-medium text-gray-900">{{ $consumption['equipment_name'] }}</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="text-sm text-gray-900">{{ $consumption['fuel_type'] }}</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="text-sm font-bold text-blue-600">{{ $consumption['quantity'] }} لتر</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="text-sm text-gray-700">{{ $consumption['user_name'] }}</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $consumption['status_color'] }}">
                                            {{ $consumption['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="text-sm text-gray-600 line-clamp-1" title="{{ $consumption['notes'] }}">
                                            {{ $consumption['notes'] ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Print & Export Actions -->
                <div class="px-6 py-4 border-t border-gray-200 flex gap-3">
                    <a href="{{ route('fuel-management.consumption-report-print', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <i class="ri-printer-line ml-2"></i>
                        طباعة
                    </a>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="ri-database-line text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد سجلات استهلاك</h3>
                    <p class="text-gray-500 mb-4">لم يتم تسجيل أي استهلاك للمحروقات في الفترة المحددة</p>
                    <a href="{{ route('fuel-management.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                        <i class="ri-arrow-right-line ml-1"></i>
                        العودة للإدارة
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            /* إخفاء عناصر غير ضرورية بقوة */
            div.fixed.right-0.top-0.h-full.w-64,
            [class*="sidebar"],
            nav,
            button,
            a[href*="fuel-management"],
            .hidden-print {
                display: none !important;
                visibility: hidden !important;
            }

            /* إظهار جميع العناصر */
            body.print-mode *,
            body.print-mode body {
                visibility: visible !important;
                display: block !important;
                opacity: 1 !important;
            }

            /* الخلفيات البيضاء */
            body.print-mode {
                background: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* المحتوى الرئيسي */
            .main-content-wrapper {
                width: 100% !important;
                display: block !important;
                visibility: visible !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible !important;
            }

            .space-y-6 {
                display: block !important;
            }

            /* الرؤوس */
            h1 {
                font-size: 18pt !important;
                margin: 0 0 12pt 0 !important;
                color: #000 !important;
                page-break-after: avoid !important;
            }

            h2 {
                font-size: 14pt !important;
                margin: 12pt 0 8pt 0 !important;
                color: #000 !important;
                border-bottom: 2px solid #000 !important;
                padding-bottom: 4pt !important;
                page-break-after: avoid !important;
            }

            p {
                margin: 0 0 6pt 0 !important;
                color: #000 !important;
                font-size: 9pt !important;
            }

            /* البطاقات */
            .bg-white {
                background: white !important;
                display: block !important;
                visibility: visible !important;
                page-break-inside: avoid !important;
            }

            .rounded-lg {
                border-radius: 0 !important;
            }

            .shadow {
                box-shadow: none !important;
            }

            .grid {
                display: block !important;
                margin-bottom: 12pt !important;
            }

            .grid > div {
                display: inline-block !important;
                width: 22% !important;
                margin: 4pt 2% 4pt 0 !important;
                padding: 6pt !important;
                border: 1px solid #ccc !important;
                background: white !important;
                vertical-align: top !important;
                page-break-inside: avoid !important;
            }

            /* الجداول */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 12pt 0 !important;
                display: table !important;
            }

            thead {
                display: table-header-group !important;
            }

            tbody {
                display: table-row-group !important;
            }

            tr {
                display: table-row !important;
                page-break-inside: avoid !important;
            }

            th, td {
                display: table-cell !important;
                border: 1px solid #999 !important;
                padding: 4pt !important;
                text-align: right !important;
                color: #000 !important;
                font-size: 9pt !important;
            }

            th {
                background: #e8e8e8 !important;
                font-weight: bold !important;
            }

            /* الألوان والنصوص */
            .text-gray-900,
            .text-gray-800,
            .text-gray-700 {
                color: #000 !important;
            }

            .text-gray-600,
            .text-gray-500,
            .text-gray-400 {
                color: #333 !important;
            }

            .text-blue-600 {
                color: #0066cc !important;
            }

            .text-sm {
                font-size: 9pt !important;
            }

            .text-xs {
                font-size: 8pt !important;
            }

            /* إزالة الخلفيات */
            .bg-blue-50,
            .bg-green-50,
            .bg-red-50,
            .bg-yellow-50,
            .bg-purple-50,
            .bg-gray-50,
            [class*="bg-blue"],
            [class*="bg-green"],
            [class*="bg-red"] {
                background: white !important;
            }

            /* الأيقونات */
            i[class*="ri-"] {
                display: none !important;
            }

            /* الفلاتر والأزرار */
            .flex.items-center.justify-between {
                display: block !important;
            }

            /* العلامات */
            .inline-flex {
                display: inline !important;
                border: 1px solid #666 !important;
                padding: 1pt 3pt !important;
                background: white !important;
            }

            .rounded-full {
                border-radius: 0 !important;
            }

            /* النصوص المقطوعة */
            .line-clamp-1,
            .truncate {
                white-space: normal !important;
                overflow: visible !important;
            }

            /* الوسائط والمسافات */
            .m-0, .mt-0, .mb-0, .ml-0, .mr-0 {
                margin: 0 !important;
            }

            .p-0 {
                padding: 0 !important;
            }

            .gap-4, .space-y-4 {
                gap: 8pt !important;
            }

            /* الفونت */
            * {
                font-family: Arial, 'Tajawal', sans-serif !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
@endsection
