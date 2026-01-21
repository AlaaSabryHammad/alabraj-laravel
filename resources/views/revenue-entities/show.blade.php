@extends('layouts.app')

@section('title', 'تفاصيل جهة الإيراد')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumb and Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 space-x-reverse mb-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="ri-home-line"></i>
                </a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                    الإعدادات
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-blue-600 font-medium">تفاصيل جهة الإيراد</span>
            </div>
            <div class="flex items-center space-x-reverse space-x-4">
                <a href="{{ route('settings.index') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة
                </a>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">تفاصيل جهة الإيراد</h1>
            </div>
            <p class="text-gray-600 mt-2">عرض معلومات جهة الإيراد والإحصائيات</p>
        </div>
        <div class="hidden md:flex items-center justify-center">
            <div class="w-24 h-24 bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="ri-building-line text-white text-4xl"></i>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('settings.revenue-entities.edit', $revenueEntity) }}"
           class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
            <i class="ri-edit-line ml-2"></i>
            تعديل
        </a>
    </div>

    <div class="max-w-7xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- المعلومات الأساسية -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2 pb-3 border-b-2 border-green-500">
                            <i class="ri-information-line text-green-600"></i>
                            المعلومات الأساسية
                        </h3>

                        <div class="bg-gray-50 rounded-xl p-5">
                            <label class="block text-sm font-medium text-gray-500 mb-2">
                                <i class="ri-building-2-line ml-1"></i>
                                اسم الجهة
                            </label>
                            <p class="text-xl font-bold text-gray-900">{{ $revenueEntity->name }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5">
                            <label class="block text-sm font-medium text-gray-500 mb-2">
                                <i class="ri-price-tag-3-line ml-1"></i>
                                نوع الجهة
                            </label>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                                @if($revenueEntity->type === 'government') bg-blue-100 text-blue-800
                                @elseif($revenueEntity->type === 'company') bg-green-100 text-green-800
                                @elseif($revenueEntity->type === 'individual') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($revenueEntity->type === 'government')
                                    <i class="ri-government-line ml-2"></i>
                                @elseif($revenueEntity->type === 'company')
                                    <i class="ri-building-line ml-2"></i>
                                @elseif($revenueEntity->type === 'individual')
                                    <i class="ri-user-line ml-2"></i>
                                @endif
                                {{ $revenueEntity->type_text }}
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5">
                            <label class="block text-sm font-medium text-gray-500 mb-2">
                                <i class="ri-checkbox-circle-line ml-1"></i>
                                الحالة
                            </label>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                                @if($revenueEntity->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                <span class="w-2 h-2 rounded-full ml-2
                                    @if($revenueEntity->status === 'active') bg-green-500
                                    @else bg-red-500
                                    @endif"></span>
                                {{ $revenueEntity->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                                <label class="block text-xs font-medium text-blue-600 mb-1">
                                    <i class="ri-calendar-line ml-1"></i>
                                    تاريخ الإنشاء
                                </label>
                                <p class="text-sm font-semibold text-blue-900">{{ $revenueEntity->created_at->format('Y/m/d') }}</p>
                                <p class="text-xs text-blue-600 mt-1">{{ $revenueEntity->created_at->format('h:i A') }}</p>
                            </div>

                            <div class="bg-purple-50 rounded-xl p-4 border border-purple-200">
                                <label class="block text-xs font-medium text-purple-600 mb-1">
                                    <i class="ri-refresh-line ml-1"></i>
                                    آخر تحديث
                                </label>
                                <p class="text-sm font-semibold text-purple-900">{{ $revenueEntity->updated_at->format('Y/m/d') }}</p>
                                <p class="text-xs text-purple-600 mt-1">{{ $revenueEntity->updated_at->format('h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات الاتصال -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2 pb-3 border-b-2 border-blue-500">
                            <i class="ri-contacts-line text-blue-600"></i>
                            معلومات الاتصال
                        </h3>

                        @if($revenueEntity->contact_person)
                        <div class="bg-gray-50 rounded-xl p-5">
                            <label class="block text-sm font-medium text-gray-500 mb-2">
                                <i class="ri-user-3-line ml-1"></i>
                                الشخص المسؤول
                            </label>
                            <p class="text-lg font-semibold text-gray-900">{{ $revenueEntity->contact_person }}</p>
                        </div>
                        @endif

                        @if($revenueEntity->phone)
                        <div class="bg-gray-50 rounded-xl p-5">
                            <label class="block text-sm font-medium text-gray-500 mb-2">
                                <i class="ri-phone-line ml-1"></i>
                                رقم الجوال
                            </label>
                            <p class="text-lg font-semibold text-gray-900 font-mono" dir="ltr">{{ $revenueEntity->phone }}</p>
                        </div>
                        @endif

                        @if($revenueEntity->email)
                        <div class="bg-gray-50 rounded-xl p-5">
                            <label class="block text-sm font-medium text-gray-500 mb-2">
                                <i class="ri-mail-line ml-1"></i>
                                البريد الإلكتروني
                            </label>
                            <p class="text-lg font-semibold text-gray-900" dir="ltr">{{ $revenueEntity->email }}</p>
                        </div>
                        @endif

                        @if($revenueEntity->address)
                        <div class="bg-gray-50 rounded-xl p-5">
                            <label class="block text-sm font-medium text-gray-500 mb-2">
                                <i class="ri-map-pin-line ml-1"></i>
                                العنوان
                            </label>
                            <p class="text-lg font-semibold text-gray-900">{{ $revenueEntity->address }}</p>
                        </div>
                        @endif

                        @if(!$revenueEntity->contact_person && !$revenueEntity->phone && !$revenueEntity->email && !$revenueEntity->address)
                        <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-200 text-center">
                            <i class="ri-information-line text-yellow-600 text-3xl mb-2"></i>
                            <p class="text-yellow-800 font-medium">لا توجد معلومات اتصال متاحة</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- المعلومات القانونية -->
                @if($revenueEntity->tax_number || $revenueEntity->commercial_record)
                <div class="mt-8 pt-8 border-t-2 border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2 pb-3 border-b-2 border-orange-500 mb-6">
                        <i class="ri-file-list-3-line text-orange-600"></i>
                        المعلومات القانونية
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @if($revenueEntity->tax_number)
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
                            <label class="block text-sm font-medium text-orange-700 mb-2">
                                <i class="ri-file-text-line ml-1"></i>
                                الرقم الضريبي
                            </label>
                            <p class="text-2xl font-bold text-orange-900 font-mono" dir="ltr">{{ $revenueEntity->tax_number }}</p>
                        </div>
                        @endif

                        @if($revenueEntity->commercial_record)
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200">
                            <label class="block text-sm font-medium text-indigo-700 mb-2">
                                <i class="ri-file-shield-2-line ml-1"></i>
                                السجل التجاري
                            </label>
                            <p class="text-2xl font-bold text-indigo-900 font-mono" dir="ltr">{{ $revenueEntity->commercial_record }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- الإحصائيات -->
                <div class="mt-8 pt-8 border-t-2 border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2 pb-3 border-b-2 border-purple-500 mb-6">
                        <i class="ri-bar-chart-box-line text-purple-600"></i>
                        الإحصائيات والبيانات المالية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                    <i class="ri-file-list-2-line text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="text-4xl font-bold text-white mb-2">{{ $revenueEntity->revenueVouchers()->count() }}</div>
                            <div class="text-blue-100 font-medium">سندات القبض</div>
                        </div>

                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                    <i class="ri-money-dollar-circle-line text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="text-4xl font-bold text-white mb-2">{{ number_format($revenueEntity->revenueVouchers()->sum('amount') ?? 0, 2) }}</div>
                            <div class="text-green-100 font-medium">إجمالي الإيرادات (ريال)</div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                    <i class="ri-time-line text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="text-2xl font-bold text-white mb-2">{{ $revenueEntity->created_at->diffForHumans() }}</div>
                            <div class="text-purple-100 font-medium">تاريخ التسجيل</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

