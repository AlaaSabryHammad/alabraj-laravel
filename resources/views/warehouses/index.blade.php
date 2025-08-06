@extends('layouts.app')

@section('title', 'إدارة المستودعات')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إدارة المستودعات</h1>
                <p class="text-gray-600">عرض وإدارة جميع المستودعات وقطع الغيار</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي المستودعات</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $warehouses->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-store-3-line text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الأصناف</p>
                        <p class="text-2xl font-bold text-green-600">{{ $warehouses->sum('inventories_count') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-box-3-line text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">المستودعات النشطة</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $warehouses->where('status', 'نشط')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="ri-checkbox-circle-line text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouses Grid -->
        @if($warehouses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($warehouses as $warehouse)
                    <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="ri-store-3-line text-2xl text-orange-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $warehouse->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $warehouse->type }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                    @if($warehouse->status === 'نشط')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif">
                                    {{ $warehouse->status ?? 'نشط' }}
                                </span>
                            </div>

                            <!-- Info -->
                            <div class="space-y-2 mb-4">
                                @if($warehouse->address)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="ri-map-pin-line"></i>
                                        <span>{{ $warehouse->address }}</span>
                                    </div>
                                @endif
                                @if($warehouse->manager_name)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="ri-user-line"></i>
                                        <span>{{ $warehouse->manager_name }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="ri-box-3-line"></i>
                                    <span>{{ $warehouse->inventories_count }} صنف</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 pt-4 border-t">
                                <a href="{{ route('warehouses.show', $warehouse) }}"
                                   class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors text-center text-sm font-medium">
                                    <i class="ri-eye-line mr-1"></i>
                                    عرض المخزون
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-store-3-line text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مستودعات</h3>
                <p class="text-gray-600 mb-4">لم يتم العثور على أي مستودعات في النظام</p>
                <a href="{{ route('locations.create') }}"
                   class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="ri-add-line"></i>
                    إضافة مستودع جديد
                </a>
            </div>
        @endif
    </div>
@endsection
