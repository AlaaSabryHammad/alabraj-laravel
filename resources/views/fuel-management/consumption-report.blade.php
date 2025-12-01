@extends('layouts.app')

@section('title', 'تقرير استهلاك المحروقات')

@section('content')
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
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <i class="ri-printer-line ml-2"></i>
                        طباعة
                    </button>
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
            .hidden-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .space-y-6 {
                margin-bottom: 0 !important;
            }
        }
    </style>
@endsection
