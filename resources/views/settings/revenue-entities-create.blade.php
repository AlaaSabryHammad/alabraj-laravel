@extends('layouts.app')

@section('title', 'إضافة جهة إيراد')

@section('content')
    <div class="p-6" dir="rtl">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إضافة جهة إيراد</h1>
                <p class="text-gray-600 mt-1">إدخال جهة مصدر إيراد جديدة</p>
            </div>
        </div>
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-sm border">
                <form action="{{ route('settings.revenue-entities.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الجهة <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع الجهة</label>
                        <select name="type" id="type"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="client">عميل</option>
                            <option value="government">جهة حكومية</option>
                            <option value="company">شركة</option>
                            <option value="individual">فرد</option>
                        </select>
                    </div>
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">اسم الشخص
                            المسؤول</label>
                        <input type="text" name="contact_person" id="contact_person"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الجوال</label>
                        <input type="text" name="phone" id="phone"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                        <input type="text" name="address" id="address"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">الرقم الضريبي</label>
                        <input type="text" name="tax_number" id="tax_number"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="commercial_record" class="block text-sm font-medium text-gray-700 mb-2">السجل
                            التجاري</label>
                        <input type="text" name="commercial_record" id="commercial_record"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-save-line"></i>
                            حفظ الجهة
                        </button>
                        <a href="{{ route('settings.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
