@extends('layouts.app')

@section('title', 'إضافة مورد جديد - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة مورد جديد</h1>
                <p class="text-gray-600">إضافة مورد جديد لنظام إدارة الموردين</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl flex items-center justify-center">
                <i class="ri-truck-line text-white text-2xl"></i>
            </div>
        </div>

        <!-- Navigation Breadcrumb -->
        <div class="mt-4 flex items-center text-sm text-gray-500">
            <a href="{{ route('suppliers.index') }}" class="hover:text-blue-600 transition-colors">إدارة الموردين</a>
            <i class="ri-arrow-left-s-line mx-2"></i>
            <span class="text-gray-900">إضافة مورد جديد</span>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('suppliers.store') }}" method="POST" id="supplierForm">
            @csrf

            <!-- Form Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">معلومات المورد</h2>
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <a href="{{ route('suppliers.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                            <i class="ri-arrow-right-line ml-2"></i>
                            إلغاء
                        </a>
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-all">
                            <i class="ri-save-line ml-2"></i>
                            حفظ المورد
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Basic Information Section -->
                    <div class="space-y-6">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                                <i class="ri-user-line ml-2"></i>
                                المعلومات الأساسية
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        اسم المورد <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required
                                           placeholder="أدخل اسم المورد">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        اسم الشركة
                                    </label>
                                    <input type="text"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('company_name') border-red-500 @enderror"
                                           id="company_name" name="company_name" value="{{ old('company_name') }}"
                                           placeholder="أدخل اسم الشركة (اختياري)">
                                    @error('company_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        البريد الإلكتروني
                                    </label>
                                    <div class="relative">
                                        <input type="email"
                                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('email') border-red-500 @enderror"
                                               id="email" name="email" value="{{ old('email') }}"
                                               placeholder="example@company.com">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ri-mail-line text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            الهاتف الأساسي <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text"
                                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('phone') border-red-500 @enderror"
                                                   id="phone" name="phone" value="{{ old('phone') }}" required
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
                                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('phone_2') border-red-500 @enderror"
                                                   id="phone_2" name="phone_2" value="{{ old('phone_2') }}"
                                                   placeholder="01xxxxxxxx">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="ri-phone-line text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('phone_2')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                                            اسم الشخص المسؤول
                                        </label>
                                        <input type="text"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('contact_person') border-red-500 @enderror"
                                               id="contact_person" name="contact_person" value="{{ old('contact_person') }}"
                                               placeholder="اسم الشخص المسؤول">
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
                                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('contact_person_phone') border-red-500 @enderror"
                                                   id="contact_person_phone" name="contact_person_phone" value="{{ old('contact_person_phone') }}"
                                                   placeholder="05xxxxxxxx">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="ri-phone-line text-gray-400"></i>
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

                    <!-- Business Information Section -->
                    <div class="space-y-6">
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <h3 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                                <i class="ri-building-line ml-2"></i>
                                المعلومات التجارية
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                        الفئة
                                    </label>
                                    <input type="text"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('category') border-red-500 @enderror"
                                           id="category" name="category" value="{{ old('category') }}"
                                           list="category-suggestions"
                                           placeholder="مثال: مواد بناء، أثاث، إلكترونيات">
                                    <datalist id="category-suggestions">
                                        <option value="مواد البناء">
                                        <option value="أثاث ومفروشات">
                                        <option value="تقنية ومعدات">
                                        <option value="خدمات النقل">
                                        <option value="مواد كهربائية">
                                        <option value="أدوات صحية">
                                    </datalist>
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">
                                        شروط الدفع <span class="text-red-500">*</span>
                                    </label>
                                    <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('payment_terms') border-red-500 @enderror"
                                            id="payment_terms" name="payment_terms" required>
                                        <option value="">اختر شروط الدفع</option>
                                        <option value="نقدي" {{ old('payment_terms') == 'نقدي' ? 'selected' : '' }}>نقدي</option>
                                        <option value="آجل 30 يوم" {{ old('payment_terms') == 'آجل 30 يوم' ? 'selected' : '' }}>آجل 30 يوم</option>
                                        <option value="آجل 60 يوم" {{ old('payment_terms') == 'آجل 60 يوم' ? 'selected' : '' }}>آجل 60 يوم</option>
                                        <option value="آجل 90 يوم" {{ old('payment_terms') == 'آجل 90 يوم' ? 'selected' : '' }}>آجل 90 يوم</option>
                                    </select>
                                    @error('payment_terms')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                        الحد الائتماني (ريال سعودي)
                                    </label>
                                    <div class="relative">
                                        <input type="number"
                                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('credit_limit') border-red-500 @enderror"
                                               id="credit_limit" name="credit_limit" value="{{ old('credit_limit', '0') }}"
                                               min="0" step="0.01" placeholder="0.00">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ri-money-dollar-circle-line text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('credit_limit')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">
                                            الرقم الضريبي
                                        </label>
                                        <input type="text"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('tax_number') border-red-500 @enderror"
                                               id="tax_number" name="tax_number" value="{{ old('tax_number') }}"
                                               placeholder="300123456789003">
                                        @error('tax_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="cr_number" class="block text-sm font-medium text-gray-700 mb-2">
                                            رقم السجل التجاري
                                        </label>
                                        <input type="text"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('cr_number') border-red-500 @enderror"
                                               id="cr_number" name="cr_number" value="{{ old('cr_number') }}"
                                               placeholder="1010123456">
                                        @error('cr_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        الحالة <span class="text-red-500">*</span>
                                    </label>
                                    <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('status') border-red-500 @enderror"
                                            id="status" name="status" required>
                                        <option value="">اختر الحالة</option>
                                        <option value="نشط" {{ old('status') == 'نشط' ? 'selected' : '' }}>نشط</option>
                                        <option value="غير نشط" {{ old('status') == 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
                                        <option value="معلق" {{ old('status') == 'معلق' ? 'selected' : '' }}>معلق</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 mt-6">
                    <h3 class="text-lg font-semibold text-yellow-900 mb-4 flex items-center">
                        <i class="ri-map-pin-line ml-2"></i>
                        معلومات العنوان
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                العنوان
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('address') border-red-500 @enderror resize-none"
                                      id="address" name="address" rows="3"
                                      placeholder="أدخل العنوان التفصيلي">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    المدينة
                                </label>
                                <input type="text"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('city') border-red-500 @enderror"
                                       id="city" name="city" value="{{ old('city') }}"
                                       placeholder="مثال: الرياض، جدة، الدمام">
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                    البلد
                                </label>
                                <input type="text"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('country') border-red-500 @enderror"
                                       id="country" name="country" value="{{ old('country', 'السعودية') }}"
                                       placeholder="البلد">
                                @error('country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                ملاحظات
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('notes') border-red-500 @enderror resize-none"
                                      id="notes" name="notes" rows="3"
                                      placeholder="أي ملاحظات إضافية حول المورد">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 space-x-reverse">
                <a href="{{ route('suppliers.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                    <i class="ri-close-line ml-2"></i>
                    إلغاء
                </a>
                <button type="submit" id="submitBtnFooter"
                        class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-all">
                    <i class="ri-save-line ml-2"></i>
                    حفظ المورد
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and submission
    const form = document.getElementById('supplierForm');
    const submitBtns = document.querySelectorAll('#submitBtn, #submitBtnFooter');

    form.addEventListener('submit', function(e) {
        submitBtns.forEach(btn => {
            btn.innerHTML = '<i class="ri-loader-line animate-spin ml-2"></i>جاري الحفظ...';
            btn.disabled = true;
        });
    });

    // Auto-format phone numbers
    const phoneInputs = document.querySelectorAll('input[type="text"][id*="phone"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            // Remove non-digits except for plus sign
            let value = e.target.value.replace(/[^\d+]/g, '');

            // Saudi phone number formatting
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
            // Capitalize first letter of each word
            const words = e.target.value.split(' ');
            const capitalizedWords = words.map(word =>
                word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
            );
            e.target.value = capitalizedWords.join(' ');
        });
    });

    // Validate email format
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function(e) {
            const email = e.target.value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailPattern.test(email)) {
                e.target.classList.add('border-red-500');
                // Show error message if not already shown
                if (!e.target.nextElementSibling || !e.target.nextElementSibling.classList.contains('text-red-600')) {
                    const errorMsg = document.createElement('p');
                    errorMsg.className = 'mt-1 text-sm text-red-600';
                    errorMsg.textContent = 'يرجى إدخال بريد إلكتروني صحيح';
                    e.target.parentNode.appendChild(errorMsg);
                }
            } else {
                e.target.classList.remove('border-red-500');
                // Remove error message if exists
                const errorMsg = e.target.parentNode.querySelector('.text-red-600');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
    }

    // Credit limit formatting
    const creditLimitInput = document.getElementById('credit_limit');
    if (creditLimitInput) {
        creditLimitInput.addEventListener('input', function(e) {
            // Format number with commas
            let value = e.target.value.replace(/,/g, '');
            if (value && !isNaN(value)) {
                e.target.value = parseFloat(value).toLocaleString('ar-SA', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
            }
        });
    }

    // Form validation before submit
    form.addEventListener('submit', function(e) {
        let hasErrors = false;

        // Check required fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                hasErrors = true;
            } else {
                field.classList.remove('border-red-500');
            }
        });

        if (hasErrors) {
            e.preventDefault();
            // Show error message
            const errorAlert = document.createElement('div');
            errorAlert.className = 'bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4';
            errorAlert.innerHTML = `
                <div class="flex items-center">
                    <i class="ri-error-warning-line text-red-600 ml-2"></i>
                    يرجى ملء جميع الحقول المطلوبة
                </div>
            `;
            form.insertBefore(errorAlert, form.firstChild);

            // Remove error message after 5 seconds
            setTimeout(() => {
                errorAlert.remove();
            }, 5000);

            // Reset submit buttons
            submitBtns.forEach(btn => {
                btn.innerHTML = '<i class="ri-save-line ml-2"></i>حفظ المورد';
                btn.disabled = false;
            });
        }
    });

    // Show success message if form was submitted successfully
    @if(session('success'))
        const successAlert = document.createElement('div');
        successAlert.className = 'bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4';
        successAlert.innerHTML = `
            <div class="flex items-center">
                <i class="ri-check-line text-green-600 ml-2"></i>
                {{ session('success') }}
            </div>
        `;
        document.querySelector('.space-y-6').insertBefore(successAlert, document.querySelector('.space-y-6').firstChild);
    @endif
});
</script>

<!-- Add some custom CSS for animations -->
<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Focus states for better accessibility */
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
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Loading state for buttons */
button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
@endsection
