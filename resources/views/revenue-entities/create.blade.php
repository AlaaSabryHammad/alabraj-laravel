@extends('layouts.app')

@section('title', 'إضافة جهة إيراد')

@section('content')
<div class="p-6" dir="rtl">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
            <i class="ri-arrow-right-line text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">إضافة جهة إيراد جديدة</h1>
            <p class="text-gray-600 mt-1">إدخال جهة مصدر إيراد جديدة</p>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border">
            <form action="{{ route('settings.revenue-entities.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        اسم الجهة <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="أدخل اسم الجهة">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        نوع الجهة <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">اختر نوع الجهة</option>
                        <option value="government">جهة حكومية</option>
                        <option value="company">شركة</option>
                        <option value="client">عميل</option>
                        <option value="individual">فرد</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                        الشخص المسؤول
                    </label>
                    <input type="text" name="contact_person" id="contact_person"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="أدخل اسم الشخص المسؤول">
                    @error('contact_person')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم الجوال
                        </label>
                        <input type="tel" name="phone" id="phone"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="أدخل رقم الجوال">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            البريد الإلكتروني
                        </label>
                        <input type="email" name="email" id="email"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="أدخل البريد الإلكتروني">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        العنوان
                    </label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="أدخل العنوان الكامل"></textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">
                            الرقم الضريبي
                        </label>
                        <input type="text" name="tax_number" id="tax_number"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="أدخل الرقم الضريبي">
                        @error('tax_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="commercial_record" class="block text-sm font-medium text-gray-700 mb-2">
                            السجل التجاري
                        </label>
                        <input type="text" name="commercial_record" id="commercial_record"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="أدخل رقم السجل التجاري">
                        @error('commercial_record')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        الحالة <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="ri-save-line ml-2"></i>
                        حفظ الجهة
                    </button>
                    <a href="{{ route('settings.index') }}" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// تحديث الحقول حسب نوع الجهة
document.getElementById('type').addEventListener('change', function() {
    const type = this.value;
    const taxNumberField = document.getElementById('tax_number');
    const commercialRecordField = document.getElementById('commercial_record');
    
    if (type === 'government' || type === 'company') {
        taxNumberField.required = true;
        taxNumberField.parentElement.querySelector('label').innerHTML = 'الرقم الضريبي <span class="text-red-500">*</span>';
    } else {
        taxNumberField.required = false;
        taxNumberField.parentElement.querySelector('label').innerHTML = 'الرقم الضريبي';
    }
    
    if (type === 'company') {
        commercialRecordField.required = true;
        commercialRecordField.parentElement.querySelector('label').innerHTML = 'السجل التجاري <span class="text-red-500">*</span>';
    } else {
        commercialRecordField.required = false;
        commercialRecordField.parentElement.querySelector('label').innerHTML = 'السجل التجاري';
    }
});
</script>
@endsection

