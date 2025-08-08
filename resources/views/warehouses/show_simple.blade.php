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
    </div>

    <script>
        // الدوال البسيطة للأزرار
        function openReceiveModal() {
            alert('سيتم تطوير وظيفة استلام قطع الغيار قريباً');
        }

        function openExportModal() {
            alert('سيتم تطوير وظيفة تصدير قطع الغيار قريباً');
        }

        function showAllSerialNumbers() {
            alert('سيتم تطوير وظيفة عرض الأرقام التسلسلية قريباً');
        }
    </script>
@endsection
