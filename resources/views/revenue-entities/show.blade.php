@extends('layouts.app')

@section('title', 'تفاصيل جهة الإيراد')

@section('content')
<div class="p-6" dir="rtl">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
            <i class="ri-arrow-right-line text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تفاصيل جهة الإيراد</h1>
            <p class="text-gray-600 mt-1">عرض معلومات جهة الإيراد</p>
        </div>
        <div class="ml-auto flex items-center gap-3">
            <a href="{{ route('settings.revenue-entities.edit', $revenueEntity) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="ri-edit-line"></i>
                تعديل
            </a>
            <a href="{{ route('settings.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                رجوع
            </a>
        </div>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- المعلومات الأساسية -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">المعلومات الأساسية</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">اسم الجهة</label>
                            <p class="text-lg font-medium text-gray-900">{{ $revenueEntity->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">نوع الجهة</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($revenueEntity->type === 'government') bg-blue-100 text-blue-800
                                @elseif($revenueEntity->type === 'company') bg-green-100 text-green-800
                                @elseif($revenueEntity->type === 'individual') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $revenueEntity->type_text }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">الحالة</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($revenueEntity->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $revenueEntity->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">تاريخ الإنشاء</label>
                            <p class="text-gray-900">{{ $revenueEntity->created_at->format('Y-m-d H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">آخر تحديث</label>
                            <p class="text-gray-900">{{ $revenueEntity->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    <!-- معلومات الاتصال -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">معلومات الاتصال</h3>
                        
                        @if($revenueEntity->contact_person)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">الشخص المسؤول</label>
                            <p class="text-gray-900">{{ $revenueEntity->contact_person }}</p>
                        </div>
                        @endif

                        @if($revenueEntity->phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">رقم الجوال</label>
                            <p class="text-gray-900">{{ $revenueEntity->phone }}</p>
                        </div>
                        @endif

                        @if($revenueEntity->email)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">البريد الإلكتروني</label>
                            <p class="text-gray-900">{{ $revenueEntity->email }}</p>
                        </div>
                        @endif

                        @if($revenueEntity->address)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">العنوان</label>
                            <p class="text-gray-900">{{ $revenueEntity->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- المعلومات القانونية -->
                @if($revenueEntity->tax_number || $revenueEntity->commercial_record)
                <div class="mt-8 pt-6 border-t">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">المعلومات القانونية</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($revenueEntity->tax_number)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">الرقم الضريبي</label>
                            <p class="text-gray-900 font-mono">{{ $revenueEntity->tax_number }}</p>
                        </div>
                        @endif

                        @if($revenueEntity->commercial_record)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">السجل التجاري</label>
                            <p class="text-gray-900 font-mono">{{ $revenueEntity->commercial_record }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- الإحصائيات -->
                <div class="mt-8 pt-6 border-t">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">الإحصائيات</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $revenueEntity->revenueVouchers()->count() }}</div>
                            <div class="text-sm text-blue-600">سندات القبض</div>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $revenueEntity->revenueVouchers()->sum('amount') ?? 0 }}</div>
                            <div class="text-sm text-green-600">إجمالي الإيرادات</div>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $revenueEntity->created_at->diffForHumans() }}</div>
                            <div class="text-sm text-purple-600">تاريخ التسجيل</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

