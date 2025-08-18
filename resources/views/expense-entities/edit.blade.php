@extends('layouts.app')

@section('title', 'تعديل جهة الصرف - ' . $expenseEntity->name)

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تعديل جهة الصرف</h1>
            <p class="text-gray-600 mt-1">تعديل بيانات: {{ $expenseEntity->name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('expense-entities.show', $expenseEntity) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-eye-line"></i>
                عرض التفاصيل
            </a>
            <a href="{{ route('expense-entities.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-arrow-right-line"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- Errors -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6 text-white rounded-t-xl">
            <h3 class="text-xl font-bold flex items-center gap-3">
                <i class="ri-edit-line text-2xl"></i>
                تعديل بيانات جهة الصرف
            </h3>
        </div>

        <form action="{{ route('expense-entities.update', $expenseEntity) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- اسم الجهة -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-building-line text-purple-600"></i>
                        اسم الجهة *
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $expenseEntity->name) }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="مثال: شركة المواد الإنشائية">
                </div>

                <!-- نوع الجهة -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-price-tag-3-line text-purple-600"></i>
                        نوع الجهة *
                    </label>
                    <select name="type" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">اختر نوع الجهة</option>
                        <option value="supplier" {{ old('type', $expenseEntity->type) === 'supplier' ? 'selected' : '' }}>مورد</option>
                        <option value="contractor" {{ old('type', $expenseEntity->type) === 'contractor' ? 'selected' : '' }}>مقاول</option>
                        <option value="government" {{ old('type', $expenseEntity->type) === 'government' ? 'selected' : '' }}>جهة حكومية</option>
                        <option value="bank" {{ old('type', $expenseEntity->type) === 'bank' ? 'selected' : '' }}>بنك</option>
                        <option value="other" {{ old('type', $expenseEntity->type) === 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- الشخص المسؤول -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-user-line text-purple-600"></i>
                        الشخص المسؤول
                    </label>
                    <input type="text" 
                           name="contact_person" 
                           value="{{ old('contact_person', $expenseEntity->contact_person) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="اسم الشخص المسؤول">
                </div>

                <!-- رقم الجوال -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-phone-line text-purple-600"></i>
                        رقم الجوال
                    </label>
                    <input type="text" 
                           name="phone" 
                           value="{{ old('phone', $expenseEntity->phone) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="966501234567">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- البريد الإلكتروني -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-mail-line text-purple-600"></i>
                        البريد الإلكتروني
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $expenseEntity->email) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="example@company.com">
                </div>

                <!-- الحالة -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-toggle-line text-purple-600"></i>
                        الحالة *
                    </label>
                    <select name="status" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="active" {{ old('status', $expenseEntity->status) === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('status', $expenseEntity->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
            </div>

            <!-- العنوان -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="ri-map-pin-line text-purple-600"></i>
                    العنوان
                </label>
                <textarea name="address" 
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                          placeholder="العنوان التفصيلي...">{{ old('address', $expenseEntity->address) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- الرقم الضريبي -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-file-text-line text-purple-600"></i>
                        الرقم الضريبي
                    </label>
                    <input type="text" 
                           name="tax_number" 
                           value="{{ old('tax_number', $expenseEntity->tax_number) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="مثال: 310123456789003">
                </div>

                <!-- السجل التجاري -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-bookmark-line text-purple-600"></i>
                        السجل التجاري
                    </label>
                    <input type="text" 
                           name="commercial_record" 
                           value="{{ old('commercial_record', $expenseEntity->commercial_record) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="مثال: 1010123456">
                </div>
            </div>

            <!-- معلومات التحديث -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <strong>تاريخ الإنشاء:</strong> {{ $expenseEntity->created_at->format('Y-m-d H:i') }}
                    </div>
                    <div>
                        <strong>آخر تحديث:</strong> {{ $expenseEntity->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>

            <!-- أزرار الحفظ -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('expense-entities.index') }}" 
                   class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                    <i class="ri-save-line"></i>
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection