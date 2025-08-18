@extends('layouts.app')

@section('title', 'تعديل جهة إيراد')

@section('content')
    <div class="p-6" dir="rtl">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('settings.revenue-entities.index') }}"
                class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تعديل جهة إيراد</h1>
                <p class="text-gray-600 mt-1">تعديل بيانات جهة مصدر الإيراد</p>
            </div>
        </div>
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-sm border">
                <form action="{{ route('settings.revenue-entities.update', $entity) }}" method="POST"
                    class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الجهة <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('name', $entity->name) }}" required>
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع الجهة</label>
                        <select name="type" id="type"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="client" {{ old('type', $entity->type) == 'client' ? 'selected' : '' }}>عميل
                            </option>
                            <option value="government" {{ old('type', $entity->type) == 'government' ? 'selected' : '' }}>
                                جهة حكومية</option>
                            <option value="company" {{ old('type', $entity->type) == 'company' ? 'selected' : '' }}>شركة
                            </option>
                            <option value="individual" {{ old('type', $entity->type) == 'individual' ? 'selected' : '' }}>
                                فرد</option>
                        </select>
                    </div>
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">اسم الشخص
                            المسؤول</label>
                        <input type="text" name="contact_person" id="contact_person"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('contact_person', $entity->contact_person) }}">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الجوال</label>
                        <input type="text" name="phone" id="phone"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('phone', $entity->phone) }}">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('email', $entity->email) }}">
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                        <input type="text" name="address" id="address"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('address', $entity->address) }}">
                    </div>
                    <div>
                        <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">الرقم الضريبي</label>
                        <input type="text" name="tax_number" id="tax_number"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('tax_number', $entity->tax_number) }}">
                    </div>
                    <div>
                        <label for="commercial_record" class="block text-sm font-medium text-gray-700 mb-2">السجل
                            التجاري</label>
                        <input type="text" name="commercial_record" id="commercial_record"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('commercial_record', $entity->commercial_record) }}">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select name="status" id="status"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="active" {{ old('status', $entity->status) == 'active' ? 'selected' : '' }}>نشط
                            </option>
                            <option value="inactive" {{ old('status', $entity->status) == 'inactive' ? 'selected' : '' }}>
                                غير نشط</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-save-line"></i>
                            حفظ التعديلات
                        </button>
                        <a href="{{ route('settings.revenue-entities.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
