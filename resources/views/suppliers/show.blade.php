@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 space-x-reverse">
                <div class="bg-white/20 rounded-full p-3">
                    <i class="ri-building-2-line text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $supplier->name }}</h1>
                    <p class="text-blue-100">
                        <i class="ri-bookmark-line ml-1"></i>
                        {{ $supplier->company_name ?: 'تفاصيل المورد' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="text-left">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($supplier->status == 'نشط') bg-green-100 text-green-800
                        @elseif($supplier->status == 'معلق') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        <i class="ri-circle-fill text-xs ml-1.5
                            @if($supplier->status == 'نشط') text-green-500
                            @elseif($supplier->status == 'معلق') text-yellow-500
                            @else text-red-500
                            @endif"></i>
                        {{ $supplier->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <nav class="flex items-center space-x-2 space-x-reverse text-sm">
            <a href="{{ route('suppliers.index') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                <i class="ri-home-line ml-1"></i>
                إدارة الموردين
            </a>
            <i class="ri-arrow-left-s-line text-gray-400"></i>
            <span class="text-gray-500">تفاصيل المورد</span>
            <i class="ri-arrow-left-s-line text-gray-400"></i>
            <span class="text-gray-900 font-medium">{{ $supplier->name }}</span>
        </nav>
    </div>
</div>

<!-- Action Buttons -->
<div class="bg-white border-b border-gray-200 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3 space-x-reverse">
                <a href="mailto:{{ $supplier->email }}" class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-all duration-200 hover:shadow-md">
                    <i class="ri-mail-line ml-2"></i>
                    إرسال بريد
                </a>
            </div>

            <div class="flex items-center space-x-3 space-x-reverse">
                <a href="{{ route('external-trucks.create', ['supplier' => $supplier->id]) }}" class="inline-flex items-center px-6 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-all duration-200 hover:shadow-lg hover:scale-105">
                    <i class="ri-truck-line ml-2"></i>
                    إضافة شاحنة نقل خارجي
                </a>
                <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex items-center px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-all duration-200 hover:shadow-lg hover:scale-105">
                    <i class="ri-edit-2-line ml-2"></i>
                    تعديل البيانات
                </a>
                <button onclick="confirmDelete()" class="inline-flex items-center px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200 hover:shadow-lg hover:scale-105">
                    <i class="ri-delete-bin-line ml-2"></i>
                    حذف المورد
                </button>
                <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-200 hover:shadow-lg hover:scale-105">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Basic Information Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 flex items-center">
                        <div class="bg-blue-500 rounded-lg p-2 ml-3">
                            <i class="ri-user-line text-white"></i>
                        </div>
                        المعلومات الأساسية
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="bg-blue-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-user-3-line text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">اسم المورد</label>
                                    <p class="text-gray-900 font-semibold">{{ $supplier->name }}</p>
                                </div>
                            </div>

                            @if($supplier->company_name)
                            <div class="flex items-start">
                                <div class="bg-green-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-building-2-line text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">اسم الشركة</label>
                                    <p class="text-gray-900 font-semibold">{{ $supplier->company_name }}</p>
                                </div>
                            </div>
                            @endif

                            @if($supplier->contact_person)
                            <div class="flex items-start">
                                <div class="bg-purple-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-contacts-line text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">الشخص المسؤول</label>
                                    <p class="text-gray-900 font-semibold">{{ $supplier->contact_person }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @if($supplier->email)
                            <div class="flex items-start">
                                <div class="bg-red-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-mail-line text-red-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">البريد الإلكتروني</label>
                                    <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:text-blue-800 font-semibold">{{ $supplier->email }}</a>
                                </div>
                            </div>
                            @endif

                            <div class="flex items-start">
                                <div class="bg-green-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-phone-line text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">الهاتف الأساسي</label>
                                    <a href="tel:{{ $supplier->phone }}" class="text-blue-600 hover:text-blue-800 font-semibold">{{ $supplier->phone }}</a>
                                </div>
                            </div>

                            @if($supplier->phone_2)
                            <div class="flex items-start">
                                <div class="bg-orange-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-smartphone-line text-orange-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">هاتف إضافي</label>
                                    <a href="tel:{{ $supplier->phone_2 }}" class="text-blue-600 hover:text-blue-800 font-semibold">{{ $supplier->phone_2 }}</a>
                                </div>
                            </div>
                            @endif

                            @if($supplier->contact_person_phone)
                            <div class="flex items-start">
                                <div class="bg-indigo-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-phone-find-line text-indigo-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">هاتف الشخص المسؤول</label>
                                    <a href="tel:{{ $supplier->contact_person_phone }}" class="text-blue-600 hover:text-blue-800 font-semibold">{{ $supplier->contact_person_phone }}</a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Information Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                    <h3 class="text-lg font-semibold text-green-900 flex items-center">
                        <div class="bg-green-500 rounded-lg p-2 ml-3">
                            <i class="ri-briefcase-line text-white"></i>
                        </div>
                        المعلومات التجارية
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            @if($supplier->category)
                            <div class="flex items-start">
                                <div class="bg-blue-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-price-tag-3-line text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">الفئة</label>
                                    <p class="text-gray-900 font-semibold">{{ $supplier->category }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="flex items-start">
                                <div class="bg-yellow-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-money-dollar-circle-line text-yellow-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">شروط الدفع</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($supplier->payment_terms == 'نقدي') bg-green-100 text-green-800
                                        @elseif($supplier->payment_terms == 'آجل 30 يوم') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ $supplier->payment_terms }}
                                    </span>
                                </div>
                            </div>

                            @if($supplier->credit_limit)
                            <div class="flex items-start">
                                <div class="bg-green-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-bank-card-line text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">الحد الائتماني</label>
                                    <p class="text-gray-900 font-semibold">{{ number_format($supplier->credit_limit, 2) }} ريال</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @if($supplier->tax_number)
                            <div class="flex items-start">
                                <div class="bg-red-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-file-text-line text-red-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">الرقم الضريبي</label>
                                    <p class="text-gray-900 font-semibold">{{ $supplier->tax_number }}</p>
                                </div>
                            </div>
                            @endif

                            @if($supplier->cr_number)
                            <div class="flex items-start">
                                <div class="bg-purple-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-award-line text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">رقم السجل التجاري</label>
                                    <p class="text-gray-900 font-semibold">{{ $supplier->cr_number }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            @if($supplier->address || $supplier->city || $supplier->country)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-4 border-b border-yellow-200">
                    <h3 class="text-lg font-semibold text-yellow-900 flex items-center">
                        <div class="bg-yellow-500 rounded-lg p-2 ml-3">
                            <i class="ri-map-pin-line text-white"></i>
                        </div>
                        معلومات العنوان
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($supplier->address)
                        <div class="flex items-start">
                            <div class="bg-blue-50 rounded-lg p-2 ml-3 mt-1">
                                <i class="ri-road-map-line text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-500">العنوان التفصيلي</label>
                                <p class="text-gray-900">{{ $supplier->address }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($supplier->city)
                            <div class="flex items-start">
                                <div class="bg-green-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-building-4-line text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">المدينة</label>
                                    <p class="text-gray-900 font-semibold">{{ $supplier->city }}</p>
                                </div>
                            </div>
                            @endif

                            @if($supplier->country)
                            <div class="flex items-start">
                                <div class="bg-red-50 rounded-lg p-2 ml-3 mt-1">
                                    <i class="ri-earth-line text-red-600"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm font-medium text-gray-500">البلد</label>
                                    <p class="text-gray-900 font-semibold">{{ $supplier->country }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($supplier->notes)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                    <h3 class="text-lg font-semibold text-purple-900 flex items-center">
                        <div class="bg-purple-500 rounded-lg p-2 ml-3">
                            <i class="ri-sticky-note-line text-white"></i>
                        </div>
                        ملاحظات إضافية
                    </h3>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed">{{ $supplier->notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>        <!-- Right Column - Statistics & Timeline -->
        <div class="space-y-6">

            <!-- Quick Stats Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-indigo-200">
                    <h3 class="text-lg font-semibold text-indigo-900 flex items-center">
                        <div class="bg-indigo-500 rounded-lg p-2 ml-3">
                            <i class="ri-dashboard-line text-white"></i>
                        </div>
                        إحصائيات سريعة
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600 font-medium">إجمالي المعاملات</p>
                                <p class="text-2xl font-bold text-blue-900">-</p>
                            </div>
                            <div class="bg-blue-500 rounded-lg p-3">
                                <i class="ri-exchange-dollar-line text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600 font-medium">آخر معاملة</p>
                                <p class="text-2xl font-bold text-green-900">-</p>
                            </div>
                            <div class="bg-green-500 rounded-lg p-3">
                                <i class="ri-calendar-check-line text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-yellow-600 font-medium">الرصيد المتبقي</p>
                                <p class="text-2xl font-bold text-yellow-900">{{ number_format($supplier->credit_limit, 0) }}</p>
                                <p class="text-xs text-yellow-600">ريال سعودي</p>
                            </div>
                            <div class="bg-yellow-500 rounded-lg p-3">
                                <i class="ri-wallet-line text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <div class="bg-gray-500 rounded-lg p-2 ml-3">
                            <i class="ri-time-line text-white"></i>
                        </div>
                        التواريخ المهمة
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="bg-green-100 rounded-full p-2 ml-4">
                                <i class="ri-add-circle-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">تاريخ الإضافة</p>
                                <p class="text-sm text-gray-500">{{ $supplier->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-2 ml-4">
                                <i class="ri-edit-circle-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">آخر تحديث</p>
                                <p class="text-sm text-gray-500">{{ $supplier->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                    <h3 class="text-lg font-semibold text-orange-900 flex items-center">
                        <div class="bg-orange-500 rounded-lg p-2 ml-3">
                            <i class="ri-flashlight-line text-white"></i>
                        </div>
                        إجراءات سريعة
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="tel:{{ $supplier->phone }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="ri-phone-line text-green-600 ml-3"></i>
                        <span class="text-green-700 font-medium">اتصال مباشر</span>
                    </a>

                    @if($supplier->email)
                    <a href="mailto:{{ $supplier->email }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="ri-mail-line text-blue-600 ml-3"></i>
                        <span class="text-blue-700 font-medium">إرسال بريد إلكتروني</span>
                    </a>
                    @endif

                    <button onclick="shareSupplier()" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors w-full">
                        <i class="ri-share-line text-purple-600 ml-3"></i>
                        <span class="text-purple-700 font-medium">مشاركة البيانات</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- External Trucks Table - Full Width -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
            <h3 class="text-lg font-semibold text-green-900 flex items-center">
                <div class="bg-green-500 rounded-lg p-2 ml-3">
                    <i class="ri-truck-line text-white"></i>
                </div>
                شاحنات النقل الخارجي
                <span class="bg-green-200 text-green-800 text-xs font-medium ml-3 px-2 py-1 rounded-full">{{ $supplier->externalTrucks->count() }}</span>
            </h3>
        </div>

        @if($supplier->externalTrucks->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم اللوحة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السائق</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع التحميل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الأجر اليومي</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($supplier->externalTrucks as $truck)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-lg p-2 ml-3">
                                    <i class="ri-truck-line text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $truck->plate_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $truck->contract_number ?? 'لا يوجد رقم عقد' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $truck->driver_name }}</div>
                            @if($truck->driver_phone)
                                <div class="text-sm text-gray-500">{{ $truck->driver_phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $truck->loading_type === 'box' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $truck->loading_type_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $truck->capacity_with_unit }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($truck->daily_rate, 2) }} ريال
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($truck->status === 'active') bg-green-100 text-green-800
                                @elseif($truck->status === 'maintenance') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $truck->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('external-trucks.show', $truck) }}" class="text-blue-600 hover:text-blue-900" title="عرض">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('external-trucks.edit', $truck) }}" class="text-indigo-600 hover:text-indigo-900" title="تعديل">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button type="button" onclick="deleteTruck({{ $truck->id }}, '{{ $truck->plate_number }}')" class="text-red-600 hover:text-red-900" title="حذف">
                                    <i class="ri-delete-bin-line"></i>
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
            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="ri-truck-line text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد شاحنات</h3>
            <p class="text-gray-500 mb-6">لم يتم إضافة أي شاحنات نقل خارجي لهذا المورد بعد</p>
            <a href="{{ route('external-trucks.create', ['supplier' => $supplier->id]) }}" class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all duration-200">
                <i class="ri-truck-line ml-2"></i>
                إضافة شاحنة جديدة
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete() {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف هذا المورد نهائياً ولا يمكن التراجع عن هذا الإجراء',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'نعم، احذف المورد',
        cancelButtonText: 'إلغاء',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6 py-2',
            cancelButton: 'rounded-lg px-6 py-2'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}

function deleteTruck(truckId, plateNumber) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: `سيتم حذف الشاحنة ${plateNumber} نهائياً!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'نعم، احذف الشاحنة',
        cancelButtonText: 'إلغاء',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6 py-2',
            cancelButton: 'rounded-lg px-6 py-2'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit a delete form for the truck
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/external-trucks/${truckId}`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
}

function shareSupplier() {
    if (navigator.share) {
        navigator.share({
            title: 'تفاصيل المورد - {{ $supplier->name }}',
            text: 'معلومات المورد: {{ $supplier->name }}',
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support native sharing
        navigator.clipboard.writeText(window.location.href).then(() => {
            Swal.fire({
                title: 'تم النسخ!',
                text: 'تم نسخ رابط الصفحة إلى الحافظة',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        });
    }
}

    }
}
</script>

<style>

/* Smooth animations */
* {
    transition: all 0.2s ease-in-out;
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Focus states */
button:focus,
a:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}
</style>
@endsection

