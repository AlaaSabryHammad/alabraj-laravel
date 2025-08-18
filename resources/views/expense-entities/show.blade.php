@extends('layouts.app')

@section('title', 'تفاصيل جهة الصرف - ' . $expenseEntity->name)

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تفاصيل جهة الصرف</h1>
            <p class="text-gray-600 mt-1">عرض تفاصيل: {{ $expenseEntity->name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('expense-entities.edit', $expenseEntity) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-edit-line"></i>
                تعديل
            </a>
            <a href="{{ route('expense-entities.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-arrow-right-line"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Entity Details -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold flex items-center gap-3">
                        <i class="ri-building-line text-2xl"></i>
                        معلومات جهة الصرف
                    </h3>
                </div>
                <div class="text-left">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($expenseEntity->status === 'active') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        @if($expenseEntity->status === 'active') نشط @else غير نشط @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 border-b pb-2">المعلومات الأساسية</h4>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">اسم الجهة</label>
                        <p class="text-gray-900 font-medium">{{ $expenseEntity->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">نوع الجهة</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($expenseEntity->type === 'supplier') bg-blue-100 text-blue-800
                            @elseif($expenseEntity->type === 'contractor') bg-green-100 text-green-800
                            @elseif($expenseEntity->type === 'government') bg-red-100 text-red-800
                            @elseif($expenseEntity->type === 'bank') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $expenseEntity->type_text }}
                        </span>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">الشخص المسؤول</label>
                        <p class="text-gray-900">{{ $expenseEntity->contact_person ?: 'غير محدد' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">الحالة</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($expenseEntity->status === 'active') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($expenseEntity->status === 'active') نشط @else غير نشط @endif
                        </span>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 border-b pb-2">معلومات الاتصال</h4>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">رقم الجوال</label>
                        @if($expenseEntity->phone)
                            <p class="text-gray-900 font-mono">{{ $expenseEntity->phone }}</p>
                        @else
                            <p class="text-gray-500">غير متوفر</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">البريد الإلكتروني</label>
                        @if($expenseEntity->email)
                            <p class="text-gray-900">{{ $expenseEntity->email }}</p>
                        @else
                            <p class="text-gray-500">غير متوفر</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">العنوان</label>
                        @if($expenseEntity->address)
                            <p class="text-gray-900">{{ $expenseEntity->address }}</p>
                        @else
                            <p class="text-gray-500">غير محدد</p>
                        @endif
                    </div>
                </div>

                <!-- Legal Info -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-900 border-b pb-2">المعلومات القانونية</h4>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-600">الرقم الضريبي</label>
                        @if($expenseEntity->tax_number)
                            <p class="text-gray-900 font-mono">{{ $expenseEntity->tax_number }}</p>
                        @else
                            <p class="text-gray-500">غير محدد</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">السجل التجاري</label>
                        @if($expenseEntity->commercial_record)
                            <p class="text-gray-900 font-mono">{{ $expenseEntity->commercial_record }}</p>
                        @else
                            <p class="text-gray-500">غير محدد</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">تاريخ الإنشاء</label>
                        <p class="text-gray-900">{{ $expenseEntity->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">آخر تحديث</label>
                        <p class="text-gray-900">{{ $expenseEntity->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Related Expense Vouchers -->
            @if($expenseEntity->expenseVouchers && $expenseEntity->expenseVouchers->count() > 0)
            <div class="mt-8 border-t pt-6">
                <h4 class="font-semibold text-gray-900 mb-4">سندات الصرف المرتبطة ({{ $expenseEntity->expenseVouchers->count() }})</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($expenseEntity->expenseVouchers->take(6) as $voucher)
                            <div class="bg-white p-3 rounded border">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $voucher->voucher_number }}</span>
                                    <span class="text-xs text-gray-500">{{ $voucher->voucher_date->format('Y-m-d') }}</span>
                                </div>
                                <div class="text-sm text-gray-600 mb-2">{{ $voucher->formatted_amount }}</div>
                                <a href="{{ route('expense-vouchers.show', $voucher) }}" 
                                   class="text-xs text-purple-600 hover:text-purple-800">
                                    عرض التفاصيل
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if($expenseEntity->expenseVouchers->count() > 6)
                        <div class="text-center mt-4">
                            <p class="text-sm text-gray-500">و {{ $expenseEntity->expenseVouchers->count() - 6 }} سندات أخرى...</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t">
                <div class="flex justify-between items-center">
                    <div class="flex gap-3">
                        <a href="{{ route('expense-entities.edit', $expenseEntity) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-edit-line"></i>
                            تعديل البيانات
                        </a>
                    </div>
                    
                    @if(!$expenseEntity->expenseVouchers || $expenseEntity->expenseVouchers->count() === 0)
                        <form action="{{ route('expense-entities.destroy', $expenseEntity) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الجهة؟\n\nلا يمكن التراجع عن هذا الإجراء!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-delete-bin-line"></i>
                                حذف الجهة
                            </button>
                        </form>
                    @else
                        <div class="text-sm text-gray-500">
                            <i class="ri-information-line ml-1"></i>
                            لا يمكن حذف هذه الجهة لوجود سندات صرف مرتبطة بها
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection