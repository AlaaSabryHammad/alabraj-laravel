@extends('layouts.app')

@section('title', 'تقارير قطع الغيار')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <i class="ri-bar-chart-line text-blue-600"></i>
                    تقارير قطع الغيار
                </h1>
                <p class="text-gray-600">تقارير شاملة لحركة قطع الغيار والمخزون</p>
            </div>
        </div>

        <!-- Report Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Daily Report -->
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="ri-calendar-line text-2xl text-blue-600"></i>
                        </div>
                        <span class="text-sm text-gray-500">تحديث يومي</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">التقرير اليومي</h3>
                    <p class="text-gray-600 text-sm mb-4">تقرير تفصيلي لحركة قطع الغيار خلال يوم محدد</p>
                    <a href="{{ route('reports.spare-parts.daily') }}" 
                       class="inline-flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ri-eye-line mr-2"></i>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <!-- Monthly Report -->
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="ri-calendar-2-line text-2xl text-green-600"></i>
                        </div>
                        <span class="text-sm text-gray-500">تحديث شهري</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">التقرير الشهري</h3>
                    <p class="text-gray-600 text-sm mb-4">تقرير شامل وإحصائيات تفصيلية للشهر</p>
                    <a href="{{ route('reports.spare-parts.monthly') }}" 
                       class="inline-flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ri-eye-line mr-2"></i>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <!-- Inventory Report -->
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="ri-stock-line text-2xl text-yellow-600"></i>
                        </div>
                        <span class="text-sm text-gray-500">حالة المخزون</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">تقرير المخزون</h3>
                    <p class="text-gray-600 text-sm mb-4">حالة المخزون الحالي والكميات المتاحة</p>
                    <a href="{{ route('reports.spare-parts.inventory') }}" 
                       class="inline-flex items-center justify-center w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ri-eye-line mr-2"></i>
                        عرض التقرير
                    </a>
                </div>
            </div>

            <!-- Employee Report -->
            <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="ri-team-line text-2xl text-purple-600"></i>
                        </div>
                        <span class="text-sm text-gray-500">تقرير الموظفين</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">تقرير الموظفين</h3>
                    <p class="text-gray-600 text-sm mb-4">قطع الغيار المُصدرة للموظفين</p>
                    <a href="{{ route('reports.spare-parts.employees') }}" 
                       class="inline-flex items-center justify-center w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ri-eye-line mr-2"></i>
                        عرض التقرير
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="ri-dashboard-3-line text-blue-600"></i>
                إحصائيات سريعة
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="ri-store-3-line text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">إجمالي المستودعات</p>
                            <p class="text-xl font-bold text-gray-900">{{ $warehouses->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="ri-tools-line text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">اليوم</p>
                            <p class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="ri-calendar-line text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">الشهر الحالي</p>
                            <p class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::now()->format('m/Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="ri-time-line text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">آخر تحديث</p>
                            <p class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::now()->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouses List -->
        @if($warehouses->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border p-6 mt-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="ri-building-line text-gray-600"></i>
                المستودعات المتاحة
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($warehouses as $warehouse)
                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="ri-store-3-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $warehouse->name }}</p>
                                <p class="text-sm text-gray-600">{{ $warehouse->address ?? 'لا يوجد عنوان' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('warehouses.show', $warehouse) }}" 
                           class="text-blue-600 hover:text-blue-800 transition-colors">
                            <i class="ri-external-link-line"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection
