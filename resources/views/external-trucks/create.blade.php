@extends('layouts.app')

@section('title', 'إضافة شاحنة جديدة - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة شاحنة جديدة</h1>
                <p class="text-gray-600">إضافة شاحنة جديدة إلى أسطول النقل الخارجي</p>
            </div>
            <a href="{{ route('external-trucks.index') }}"
               class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                <i class="ri-arrow-right-line ml-2"></i>
                العودة إلى القائمة
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">بيانات الشاحنة</h2>
        </div>

        <form action="{{ route('external-trucks.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Plate Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">رقم اللوحة *</label>
                    <input type="text"
                           name="plate_number"
                           value="{{ old('plate_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('plate_number') border-red-500 @enderror"
                           placeholder="مثال: أ ب ج 1234"
                           required>
                    @error('plate_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('status') border-red-500 @enderror" required>
                        <option value="">اختر الحالة</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>قيد الصيانة</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Driver Information -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">بيانات السائق</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Driver Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">اسم السائق *</label>
                        <input type="text"
                               name="driver_name"
                               value="{{ old('driver_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('driver_name') border-red-500 @enderror"
                               placeholder="اسم السائق الكامل"
                               required>
                        @error('driver_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Driver Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم جوال السائق *</label>
                        <input type="tel"
                               name="driver_phone"
                               value="{{ old('driver_phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('driver_phone') border-red-500 @enderror"
                               placeholder="05xxxxxxxx"
                               required>
                        @error('driver_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Supplier Information -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">بيانات المورد</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Supplier Selection -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">اختيار المورد *</label>
                        <select name="supplier_id"
                                id="supplier_select"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('supplier_id') border-red-500 @enderror"
                                required
                                onchange="loadSupplierData(this.value)">
                            <option value="">اختر المورد</option>
                            @forelse($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                        data-name="{{ $supplier->name }}"
                                        data-company="{{ $supplier->company_name }}"
                                        data-phone="{{ $supplier->phone }}"
                                        data-email="{{ $supplier->email }}"
                                        {{ (old('supplier_id') == $supplier->id || (isset($selectedSupplierId) && $selectedSupplierId == $supplier->id)) ? 'selected' : '' }}>
                                    {{ $supplier->name }} @if($supplier->company_name) - {{ $supplier->company_name }} @endif
                                </option>
                            @empty
                                <option value="" disabled>لا توجد موردين متاحين</option>
                            @endforelse
                        </select>
                        @error('supplier_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if($suppliers->isEmpty())
                            <p class="mt-1 text-sm text-amber-600">
                                <i class="ri-information-line"></i>
                                لا توجد موردين نشطين. يرجى إضافة مورد أولاً من
                                <a href="{{ route('suppliers.create') }}" class="text-blue-600 hover:underline">صفحة الموردين</a>
                            </p>
                        @endif
                    </div>

                    <!-- Supplier Details Display -->
                    <div id="supplier-details" class="md:col-span-2 hidden">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">تفاصيل المورد المحدد</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">اسم المورد:</span>
                                    <p id="supplier-name" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">الشركة:</span>
                                    <p id="supplier-company" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">الهاتف:</span>
                                    <p id="supplier-phone" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">البريد الإلكتروني:</span>
                                    <p id="supplier-email" class="font-medium text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contract Information -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم العقد/الاتفاقية</label>
                        <input type="text"
                               name="contract_number"
                               value="{{ old('contract_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('contract_number') border-red-500 @enderror"
                               placeholder="رقم العقد أو الاتفاقية">
                        @error('contract_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Daily Rate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الأجر اليومي (ريال)</label>
                        <div class="relative">
                            <input type="number"
                                   name="daily_rate"
                                   value="{{ old('daily_rate') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-3 py-2 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('daily_rate') border-red-500 @enderror"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">ريال</span>
                            </div>
                        </div>
                        @error('daily_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ بداية التعاقد</label>
                        <input type="date"
                               name="contract_start_date"
                               value="{{ old('contract_start_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('contract_start_date') border-red-500 @enderror">
                        @error('contract_start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ انتهاء التعاقد</label>
                        <input type="date"
                               name="contract_end_date"
                               value="{{ old('contract_end_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('contract_end_date') border-red-500 @enderror">
                        @error('contract_end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Photos -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">صور الشاحنة</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">رفع الصور</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-cyan-400 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <i class="ri-image-line text-4xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="photos" class="relative cursor-pointer bg-white rounded-md font-medium text-cyan-600 hover:text-cyan-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-cyan-500">
                                    <span>رفع الصور</span>
                                    <input id="photos" name="photos[]" type="file" class="sr-only" multiple accept="image/*" onchange="displaySelectedFiles(this)">
                                </label>
                                <p class="pr-1">أو سحب وإفلات</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG أقل من 10MB</p>
                        </div>
                    </div>
                    <div id="selected-files" class="mt-3 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">الصور المحددة:</p>
                        <div id="files-list" class="space-y-2"></div>
                    </div>
                    @error('photos.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="border-t pt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات إضافية</label>
                    <textarea name="notes"
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('notes') border-red-500 @enderror"
                              placeholder="أي ملاحظات إضافية حول الشاحنة...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="border-t pt-6">
                <div class="flex space-x-3 space-x-reverse">
                    <button type="submit"
                            class="bg-gradient-to-r from-cyan-600 to-cyan-700 text-white px-8 py-3 rounded-xl font-medium hover:from-cyan-700 hover:to-cyan-800 focus:ring-4 focus:ring-cyan-300 transition-all duration-200">
                        <i class="ri-save-line ml-2"></i>
                        حفظ الشاحنة
                    </button>
                    <a href="{{ route('external-trucks.index') }}"
                       class="bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-medium hover:bg-gray-400 transition-all duration-200">
                        إلغاء
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Load supplier data when selected
    function loadSupplierData(supplierId) {
        const detailsDiv = document.getElementById('supplier-details');

        if (supplierId) {
            // Show loading state
            detailsDiv.classList.remove('hidden');
            document.getElementById('supplier-name').textContent = 'جاري التحميل...';
            document.getElementById('supplier-company').textContent = 'جاري التحميل...';
            document.getElementById('supplier-phone').textContent = 'جاري التحميل...';
            document.getElementById('supplier-email').textContent = 'جاري التحميل...';

            // Fetch supplier data via API
            fetch(`/external-trucks/api/supplier/${supplierId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    // Populate fields with API data
                    document.getElementById('supplier-name').textContent = data.name || '-';
                    document.getElementById('supplier-company').textContent = data.company_name || '-';
                    document.getElementById('supplier-phone').textContent = data.phone || '-';
                    document.getElementById('supplier-email').textContent = data.email || '-';
                })
                .catch(error => {
                    console.error('Error loading supplier data:', error);
                    document.getElementById('supplier-name').textContent = 'خطأ في تحميل البيانات';
                    document.getElementById('supplier-company').textContent = '-';
                    document.getElementById('supplier-phone').textContent = '-';
                    document.getElementById('supplier-email').textContent = '-';
                });
        } else {
            detailsDiv.classList.add('hidden');
        }
    }

    // Display selected files
    function displaySelectedFiles(input) {
        const selectedFiles = document.getElementById('selected-files');
        const filesList = document.getElementById('files-list');

        if (input.files.length > 0) {
            selectedFiles.classList.remove('hidden');
            filesList.innerHTML = '';

            Array.from(input.files).forEach((file, index) => {
                const fileDiv = document.createElement('div');
                fileDiv.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                fileDiv.innerHTML = `
                    <span class="text-sm text-gray-700">${file.name}</span>
                    <span class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                `;
                filesList.appendChild(fileDiv);
            });
        } else {
            selectedFiles.classList.add('hidden');
        }
    }

    // Auto-format phone number
    function formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.startsWith('966')) {
            value = '+' + value;
        }
        input.value = value;
    }

    // Validate contract dates
    function validateContractDates() {
        const startDate = document.querySelector('input[name="contract_start_date"]').value;
        const endDate = document.querySelector('input[name="contract_end_date"]').value;

        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
            return false;
        }
        return true;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add phone number formatting
        const phoneInput = document.querySelector('input[name="driver_phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                formatPhoneNumber(this);
            });
        }

        // Add date validation
        const startDateInput = document.querySelector('input[name="contract_start_date"]');
        const endDateInput = document.querySelector('input[name="contract_end_date"]');

        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', validateContractDates);
            endDateInput.addEventListener('change', validateContractDates);
        }

        // Load supplier data if already selected (for old input)
        const supplierSelect = document.getElementById('supplier_select');
        if (supplierSelect.value) {
            loadSupplierData(supplierSelect.value);
        }

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validateContractDates()) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection
