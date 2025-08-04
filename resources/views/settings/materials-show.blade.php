@extends('layouts.app')

@section('title', 'تفاصيل المادة - إدارة المواد')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $material->name }}</h1>
                <p class="text-gray-600">تفاصيل المادة</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('settings.materials.edit', $material) }}"
                   class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
                    <i class="ri-edit-line ml-2"></i>
                    تحرير
                </a>
                <a href="{{ route('settings.materials') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200 flex items-center">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة إلى القائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Current Stock -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">المخزون الحالي</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($material->current_stock, 0) }}
                    </p>
                    @if($material->unit)
                        <p class="text-sm text-gray-500">{{ $material->unit }}</p>
                    @endif
                </div>
                <div class="p-3 rounded-xl {{ $material->current_stock >= $material->minimum_stock ? 'bg-green-100' : 'bg-red-100' }}">
                    <i class="ri-stack-line text-2xl {{ $material->current_stock >= $material->minimum_stock ? 'text-green-600' : 'text-red-600' }}"></i>
                </div>
            </div>
        </div>

        <!-- Minimum Stock -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الحد الأدنى</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($material->minimum_stock, 0) }}
                    </p>
                    @if($material->unit)
                        <p class="text-sm text-gray-500">{{ $material->unit }}</p>
                    @endif
                </div>
                <div class="p-3 rounded-xl bg-orange-100">
                    <i class="ri-alarm-warning-line text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Unit Price -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">سعر الوحدة</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $material->unit_price ? number_format($material->unit_price, 2) : 'غير محدد' }}
                    </p>
                    @if($material->unit_price)
                        <p class="text-sm text-gray-500">ريال</p>
                    @endif
                </div>
                <div class="p-3 rounded-xl bg-blue-100">
                    <i class="ri-money-dollar-circle-line text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الحالة</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $material->getStatusInArabic() }}
                    </p>
                </div>
                <div class="p-3 rounded-xl {{ $material->status == 'active' ? 'bg-green-100' : ($material->status == 'out_of_stock' ? 'bg-red-100' : 'bg-gray-100') }}">
                    <i class="{{ $material->status == 'active' ? 'ri-check-line text-green-600' : ($material->status == 'out_of_stock' ? 'ri-close-line text-red-600' : 'ri-pause-line text-gray-600') }} text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">المعلومات الأساسية</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">اسم المادة:</span>
                    <span class="font-medium text-gray-900">{{ $material->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">الفئة:</span>
                    <span class="font-medium text-gray-900">{{ $material->getCategoryInArabic() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">وحدة القياس:</span>
                    <span class="font-medium text-gray-900">{{ $material->unit_of_measure ?: 'غير محدد' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">الوحدة:</span>
                    <span class="font-medium text-gray-900">{{ $material->unit ?: 'غير محدد' }}</span>
                </div>
                @if($material->description)
                    <div>
                        <span class="text-gray-600">الوصف:</span>
                        <p class="font-medium text-gray-900 mt-1">{{ $material->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stock Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">معلومات المخزون</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">المخزون الحالي:</span>
                    <span class="font-medium {{ $material->current_stock >= $material->minimum_stock ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($material->current_stock, 0) }} {{ $material->unit }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">الحد الأدنى:</span>
                    <span class="font-medium text-gray-900">{{ number_format($material->minimum_stock, 0) }} {{ $material->unit }}</span>
                </div>
                @if($material->maximum_stock)
                    <div class="flex justify-between">
                        <span class="text-gray-600">الحد الأقصى:</span>
                        <span class="font-medium text-gray-900">{{ number_format($material->maximum_stock, 0) }} {{ $material->unit }}</span>
                    </div>
                @endif
                @if($material->storage_location)
                    <div class="flex justify-between">
                        <span class="text-gray-600">موقع التخزين:</span>
                        <span class="font-medium text-gray-900">{{ $material->storage_location }}</span>
                    </div>
                @endif

                <!-- Stock Status Alert -->
                @if($material->isLowStock())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mt-4">
                        <div class="flex items-center">
                            <i class="ri-alarm-warning-line text-red-500 text-xl ml-3"></i>
                            <div>
                                <p class="text-red-700 font-medium">تحذير: مخزون منخفض</p>
                                <p class="text-red-600 text-sm">المخزون الحالي أقل من الحد الأدنى المطلوب</p>
                            </div>
                        </div>
                    </div>
                @elseif($material->isOutOfStock())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mt-4">
                        <div class="flex items-center">
                            <i class="ri-close-circle-line text-red-500 text-xl ml-3"></i>
                            <div>
                                <p class="text-red-700 font-medium">تحذير: نفذ المخزون</p>
                                <p class="text-red-600 text-sm">لا يوجد مخزون متاح من هذه المادة</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Supplier Information -->
        @if($material->supplier_name || $material->supplier_contact)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">معلومات المورد</h3>
                <div class="space-y-4">
                    @if($material->supplier_name)
                        <div class="flex justify-between">
                            <span class="text-gray-600">اسم المورد:</span>
                            <span class="font-medium text-gray-900">{{ $material->supplier_name }}</span>
                        </div>
                    @endif
                    @if($material->supplier_contact)
                        <div class="flex justify-between">
                            <span class="text-gray-600">جهة الاتصال:</span>
                            <span class="font-medium text-gray-900">{{ $material->supplier_contact }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Product Information -->
        @if($material->brand || $material->model)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">معلومات المنتج</h3>
                <div class="space-y-4">
                    @if($material->brand)
                        <div class="flex justify-between">
                            <span class="text-gray-600">العلامة التجارية:</span>
                            <span class="font-medium text-gray-900">{{ $material->brand }}</span>
                        </div>
                    @endif
                    @if($material->model)
                        <div class="flex justify-between">
                            <span class="text-gray-600">الموديل:</span>
                            <span class="font-medium text-gray-900">{{ $material->model }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Pricing Information -->
        @if($material->unit_price || $material->last_purchase_price || $material->last_purchase_date)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">معلومات السعر</h3>
                <div class="space-y-4">
                    @if($material->unit_price)
                        <div class="flex justify-between">
                            <span class="text-gray-600">سعر الوحدة:</span>
                            <span class="font-medium text-gray-900">{{ number_format($material->unit_price, 2) }} ريال</span>
                        </div>
                    @endif
                    @if($material->last_purchase_price)
                        <div class="flex justify-between">
                            <span class="text-gray-600">آخر سعر شراء:</span>
                            <span class="font-medium text-gray-900">{{ number_format($material->last_purchase_price, 2) }} ريال</span>
                        </div>
                    @endif
                    @if($material->last_purchase_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ آخر شراء:</span>
                            <span class="font-medium text-gray-900">{{ $material->last_purchase_date->format('d/m/Y') }}</span>
                        </div>
                    @endif

                    @if($material->unit_price && $material->current_stock)
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-4">
                            <div class="flex justify-between">
                                <span class="text-blue-700">القيمة الإجمالية للمخزون:</span>
                                <span class="font-bold text-blue-900">{{ number_format($material->unit_price * $material->current_stock, 2) }} ريال</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Notes -->
        @if($material->notes)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">ملاحظات</h3>
                <p class="text-gray-700 leading-relaxed">{{ $material->notes }}</p>
            </div>
        @endif
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">معلومات النظام</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">تاريخ الإنشاء:</span>
                    <span class="font-medium text-gray-900">{{ $material->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">آخر تحديث:</span>
                    <span class="font-medium text-gray-900">{{ $material->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-center gap-4 pt-6">
        <a href="{{ route('settings.materials.edit', $material) }}"
           class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
            <i class="ri-edit-line ml-2"></i>
            تحرير المادة
        </a>
        <form action="{{ route('settings.materials.destroy', $material) }}" method="POST" class="inline"
              onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-xl font-medium hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center">
                <i class="ri-delete-bin-line ml-2"></i>
                حذف المادة
            </button>
        </form>
    </div>
</div>
@endsection
