@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-yellow-600 to-orange-600 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 space-x-reverse">
                <div class="bg-white/20 rounded-full p-3">
                    <i class="ri-edit-2-line text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">تعديل بيانات المورد</h1>
                    <p class="text-yellow-100">
                        <i class="ri-user-line ml-1"></i>
                        {{ $supplier->name }} - {{ $supplier->company_name ?: 'تحديث المعلومات' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="text-left">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white">
                        <i class="ri-calendar-line text-white text-xs ml-1.5"></i>
                        آخر تحديث: {{ $supplier->updated_at->format('d/m/Y') }}
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
            <a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                {{ $supplier->name }}
            </a>
            <i class="ri-arrow-left-s-line text-gray-400"></i>
            <span class="text-gray-900 font-medium">تعديل البيانات</span>
        </nav>
    </div>
</div>

<!-- Form Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form action="{{ route('suppliers.update', $supplier) }}" method="POST" id="supplierForm" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Basic Information Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                <h3 class="text-xl font-semibold text-blue-900 flex items-center">
                    <div class="bg-blue-500 rounded-lg p-2 ml-3">
                        <i class="ri-user-3-line text-white"></i>
                    </div>
                    المعلومات الأساسية
                    <span class="mr-auto text-sm text-blue-600 bg-blue-100 px-2 py-1 rounded-full">مطلوب</span>
                </h3>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <!-- Supplier Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم المورد <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror"
                                       id="name" name="name" value="{{ old('name', $supplier->name) }}" required
                                       placeholder="أدخل اسم المورد">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-user-3-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Company Name -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم الشركة
                            </label>
                            <div class="relative">
                                <input type="text"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('company_name') border-red-500 @enderror"
                                       id="company_name" name="company_name" value="{{ old('company_name', $supplier->company_name) }}"
                                       placeholder="أدخل اسم الشركة">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-building-2-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني
                            </label>
                            <div class="relative">
                                <input type="email"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('email') border-red-500 @enderror"
                                       id="email" name="email" value="{{ old('email', $supplier->email) }}"
                                       placeholder="example@company.com">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-mail-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Phone Numbers -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    الهاتف الأساسي <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('phone') border-red-500 @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}" required
                                           placeholder="05xxxxxxxx">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-phone-line text-gray-400"></i>
                                    </div>
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone_2" class="block text-sm font-medium text-gray-700 mb-2">
                                    هاتف إضافي
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('phone_2') border-red-500 @enderror"
                                           id="phone_2" name="phone_2" value="{{ old('phone_2', $supplier->phone_2) }}"
                                           placeholder="01xxxxxxxx">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-smartphone-line text-gray-400"></i>
                                    </div>
                                </div>
                                @error('phone_2')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Person -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                                    اسم الشخص المسؤول
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('contact_person') border-red-500 @enderror"
                                           id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                                           placeholder="اسم المسؤول">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-contacts-line text-gray-400"></i>
                                    </div>
                                </div>
                                @error('contact_person')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_person_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    هاتف الشخص المسؤول
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('contact_person_phone') border-red-500 @enderror"
                                           id="contact_person_phone" name="contact_person_phone" value="{{ old('contact_person_phone', $supplier->contact_person_phone) }}"
                                           placeholder="05xxxxxxxx">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-phone-find-line text-gray-400"></i>
                                    </div>
                                </div>
                                @error('contact_person_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Information Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                <h3 class="text-xl font-semibold text-green-900 flex items-center">
                    <div class="bg-green-500 rounded-lg p-2 ml-3">
                        <i class="ri-briefcase-line text-white"></i>
                    </div>
                    المعلومات التجارية
                    <span class="mr-auto text-sm text-green-600 bg-green-100 px-2 py-1 rounded-full">تجاري</span>
                </h3>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                فئة المورد
                            </label>
                            <div class="relative">
                                <input type="text"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('category') border-red-500 @enderror"
                                       id="category" name="category" value="{{ old('category', $supplier->category) }}"
                                       placeholder="مثال: مواد بناء، أثاث، إلكترونيات"
                                       list="category-suggestions">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-price-tag-3-line text-gray-400"></i>
                                </div>
                            </div>
                            <datalist id="category-suggestions">
                                <option value="مواد البناء">
                                <option value="أثاث ومفروشات">
                                <option value="تقنية ومعدات">
                                <option value="خدمات النقل">
                                <option value="مواد كهربائية">
                                <option value="أدوات صحية">
                                <option value="مواد تشطيب">
                                <option value="معدات ثقيلة">
                            </datalist>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Terms -->
                        <div>
                            <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">
                                شروط الدفع <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('payment_terms') border-red-500 @enderror"
                                        id="payment_terms" name="payment_terms" required>
                                    <option value="">اختر شروط الدفع</option>
                                    <option value="نقدي" {{ old('payment_terms', $supplier->payment_terms) == 'نقدي' ? 'selected' : '' }}>نقدي</option>
                                    <option value="آجل 30 يوم" {{ old('payment_terms', $supplier->payment_terms) == 'آجل 30 يوم' ? 'selected' : '' }}>آجل 30 يوم</option>
                                    <option value="آجل 60 يوم" {{ old('payment_terms', $supplier->payment_terms) == 'آجل 60 يوم' ? 'selected' : '' }}>آجل 60 يوم</option>
                                    <option value="آجل 90 يوم" {{ old('payment_terms', $supplier->payment_terms) == 'آجل 90 يوم' ? 'selected' : '' }}>آجل 90 يوم</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-money-dollar-circle-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('payment_terms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Credit Limit -->
                        <div>
                            <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                الحد الائتماني (ريال سعودي)
                            </label>
                            <div class="relative">
                                <input type="number"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('credit_limit') border-red-500 @enderror"
                                       id="credit_limit" name="credit_limit" value="{{ old('credit_limit', $supplier->credit_limit) }}"
                                       min="0" step="0.01" placeholder="0.00">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-bank-card-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('credit_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Tax and CR Numbers -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    الرقم الضريبي
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('tax_number') border-red-500 @enderror"
                                           id="tax_number" name="tax_number" value="{{ old('tax_number', $supplier->tax_number) }}"
                                           placeholder="3xxxxxxxxxx">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-file-text-line text-gray-400"></i>
                                    </div>
                                </div>
                                @error('tax_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cr_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    رقم السجل التجاري
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('cr_number') border-red-500 @enderror"
                                           id="cr_number" name="cr_number" value="{{ old('cr_number', $supplier->cr_number) }}"
                                           placeholder="1xxxxxxxxx">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-award-line text-gray-400"></i>
                                    </div>
                                </div>
                                @error('cr_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                حالة المورد <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('status') border-red-500 @enderror"
                                        id="status" name="status" required>
                                    <option value="">اختر الحالة</option>
                                    <option value="نشط" {{ old('status', $supplier->status) == 'نشط' ? 'selected' : '' }}>نشط</option>
                                    <option value="غير نشط" {{ old('status', $supplier->status) == 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
                                    <option value="معلق" {{ old('status', $supplier->status) == 'معلق' ? 'selected' : '' }}>معلق</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-shield-check-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Indicator -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">الحالة الحالية</p>
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
                                <i class="ri-information-line text-gray-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-4 border-b border-yellow-200">
                <h3 class="text-xl font-semibold text-yellow-900 flex items-center">
                    <div class="bg-yellow-500 rounded-lg p-2 ml-3">
                        <i class="ri-map-pin-line text-white"></i>
                    </div>
                    معلومات العنوان والموقع
                    <span class="mr-auto text-sm text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">اختياري</span>
                </h3>
            </div>
            <div class="p-8">
                <div class="space-y-6">
                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            العنوان التفصيلي
                        </label>
                        <div class="relative">
                            <textarea class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all @error('address') border-red-500 @enderror"
                                      id="address" name="address" rows="3" placeholder="أدخل العنوان التفصيلي للمورد">{{ old('address', $supplier->address) }}</textarea>
                            <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                                <i class="ri-road-map-line text-gray-400 mt-1"></i>
                            </div>
                        </div>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City and Country -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                المدينة
                            </label>
                            <div class="relative">
                                <input type="text"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all @error('city') border-red-500 @enderror"
                                       id="city" name="city" value="{{ old('city', $supplier->city) }}"
                                       placeholder="الرياض، جدة، الدمام..."
                                       list="city-suggestions">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-building-4-line text-gray-400"></i>
                                </div>
                            </div>
                            <datalist id="city-suggestions">
                                <option value="الرياض">
                                <option value="جدة">
                                <option value="الدمام">
                                <option value="مكة المكرمة">
                                <option value="المدينة المنورة">
                                <option value="الطائف">
                                <option value="تبوك">
                                <option value="بريدة">
                                <option value="خميس مشيط">
                                <option value="حائل">
                            </datalist>
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                البلد
                            </label>
                            <div class="relative">
                                <input type="text"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all @error('country') border-red-500 @enderror"
                                       id="country" name="country" value="{{ old('country', $supplier->country) }}"
                                       placeholder="المملكة العربية السعودية">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-earth-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات إضافية
                        </label>
                        <div class="relative">
                            <textarea class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all @error('notes') border-red-500 @enderror"
                                      id="notes" name="notes" rows="4" placeholder="أي ملاحظات أو تعليقات إضافية حول المورد">{{ old('notes', $supplier->notes) }}</textarea>
                            <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                                <i class="ri-sticky-note-line text-gray-400 mt-1"></i>
                            </div>
                        </div>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <button type="button" onclick="previewChanges()" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all duration-200">
                        <i class="ri-eye-line ml-2"></i>
                        معاينة التغييرات
                    </button>
                    <button type="button" onclick="resetForm()" class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-all duration-200">
                        <i class="ri-refresh-line ml-2"></i>
                        إعادة تعيين
                    </button>
                </div>

                <div class="flex items-center space-x-3 space-x-reverse">
                    <a href="{{ route('suppliers.show', $supplier) }}" class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-200 hover:shadow-lg">
                        <i class="ri-close-line ml-2"></i>
                        إلغاء
                    </a>
                    <button type="submit" id="submitBtn" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white rounded-lg transition-all duration-200 hover:shadow-lg hover:scale-105 font-semibold">
                        <i class="ri-save-line ml-2"></i>
                        حفظ التحديثات
                    </button>
                    <button type="submit" name="save_and_continue" value="1" class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all duration-200 hover:shadow-lg">
                        <i class="ri-save-2-line ml-2"></i>
                        حفظ ومتابعة
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('supplierForm');
    const submitBtns = document.querySelectorAll('button[type="submit"]');

    // Form submission handler
    form.addEventListener('submit', function(e) {
        submitBtns.forEach(btn => {
            if (btn.contains(e.submitter)) {
                btn.innerHTML = '<i class="ri-loader-line animate-spin ml-2"></i>جاري الحفظ...';
                btn.disabled = true;
            }
        });
    });

    // Auto-format phone numbers
    const phoneInputs = document.querySelectorAll('input[type="text"][id*="phone"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d+]/g, '');
            if (value.startsWith('966')) {
                value = '+' + value;
            }
            e.target.value = value;
        });
    });

    // Auto-capitalize names
    const nameInputs = document.querySelectorAll('#name, #company_name, #contact_person');
    nameInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            const words = e.target.value.split(' ');
            const capitalizedWords = words.map(word =>
                word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
            );
            e.target.value = capitalizedWords.join(' ');
        });
    });

    // Email validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function(e) {
            const email = e.target.value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailPattern.test(email)) {
                e.target.classList.add('border-red-500');
                showFieldError(e.target, 'يرجى إدخال بريد إلكتروني صحيح');
            } else {
                e.target.classList.remove('border-red-500');
                clearFieldError(e.target);
            }
        });
    }

    // Credit limit formatting
    const creditLimitInput = document.getElementById('credit_limit');
    if (creditLimitInput) {
        creditLimitInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/,/g, '');
            if (value && !isNaN(value)) {
                e.target.value = parseFloat(value).toLocaleString('ar-SA', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
            }
        });
    }
});

function previewChanges() {
    const form = document.getElementById('supplierForm');
    const formData = new FormData(form);

    let preview = 'معاينة التغييرات:\n\n';
    for (let [key, value] of formData.entries()) {
        if (value) {
            const label = form.querySelector(`[name="${key}"]`).closest('div').querySelector('label').textContent.replace('*', '').trim();
            preview += `${label}: ${value}\n`;
        }
    }

    alert(preview);
}

function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ سيتم فقدان جميع التغييرات غير المحفوظة.')) {
        document.getElementById('supplierForm').reset();
    }
}

function showFieldError(field, message) {
    clearFieldError(field);
    const errorElement = document.createElement('p');
    errorElement.className = 'mt-1 text-sm text-red-600 field-error';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
}

function clearFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Show success message if form was submitted successfully
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.createElement('div');
        successAlert.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg shadow-lg z-50';
        successAlert.innerHTML = `
            <div class="flex items-center">
                <i class="ri-check-line text-green-600 ml-2 text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="mr-4 text-green-600 hover:text-green-800">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        `;
        document.body.appendChild(successAlert);

        setTimeout(() => {
            successAlert.remove();
        }, 5000);
    });
@endif
</script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Focus states */
input:focus, select:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Smooth transitions */
input, select, textarea, button {
    transition: all 0.2s ease-in-out;
}

/* Hover effects */
button:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Loading state */
button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
}
</style>
@endsection

