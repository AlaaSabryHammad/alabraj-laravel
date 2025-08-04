@extends('layouts.app')

@section('title', 'تعديل الشاحنة - ' . $externalTruck->plate_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تعديل الشاحنة - {{ $externalTruck->plate_number }}</h1>
                <p class="text-gray-600">تحديث بيانات الشاحنة والمورد</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('external-trucks.show', $externalTruck) }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                    <i class="ri-eye-line ml-2"></i>
                    عرض التفاصيل
                </a>
                <a href="{{ route('external-trucks.index') }}"
                   class="bg-gray-400 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-500 transition-all duration-200 flex items-center">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة إلى القائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">تحديث بيانات الشاحنة</h2>
            <p class="text-gray-600 text-sm mt-1">قم بتحديث المعلومات المطلوبة وحفظ التغييرات</p>
        </div>

        <form action="{{ route('external-trucks.update', $externalTruck) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-blue-900 mb-1 flex items-center">
                    <i class="ri-truck-line ml-2 text-blue-600"></i>
                    المعلومات الأساسية
                </h3>
                <p class="text-blue-700 text-sm mb-4">معلومات الشاحنة والسائق</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Plate Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم اللوحة *</label>
                        <input type="text"
                               name="plate_number"
                               value="{{ old('plate_number', $externalTruck->plate_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('plate_number') border-red-500 @enderror"
                               placeholder="مثال: أ ب ج 1234"
                               required>
                        @error('plate_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Driver Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">اسم السائق *</label>
                        <input type="text"
                               name="driver_name"
                               value="{{ old('driver_name', $externalTruck->driver_name) }}"
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
                               value="{{ old('driver_phone', $externalTruck->driver_phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('driver_phone') border-red-500 @enderror"
                               placeholder="05xxxxxxxx"
                               required>
                        @error('driver_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الحالة *</label>
                        <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('status') border-red-500 @enderror"
                                required>
                            <option value="active" {{ old('status', $externalTruck->status) == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status', $externalTruck->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="maintenance" {{ old('status', $externalTruck->status) == 'maintenance' ? 'selected' : '' }}>تحت الصيانة</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Supplier Information Section -->
            <div class="bg-gradient-to-r from-cyan-50 to-cyan-100 rounded-xl p-6 border-l-4 border-cyan-500">
                <h3 class="text-lg font-semibold text-cyan-900 mb-1 flex items-center">
                    <i class="ri-building-line ml-2 text-cyan-600"></i>
                    معلومات المورد
                </h3>
                <p class="text-cyan-700 text-sm mb-4">اختر المورد ومعلومات العقد</p>

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
                                        {{ old('supplier_id', $externalTruck->supplier_id) == $supplier->id ? 'selected' : '' }}>
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
                    <div id="supplier-details" class="md:col-span-2 {{ $externalTruck->supplier_id ? '' : 'hidden' }}">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">تفاصيل المورد المحدد</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">اسم المورد:</span>
                                    <p id="supplier-name" class="font-medium text-gray-900">{{ $externalTruck->supplier->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">الشركة:</span>
                                    <p id="supplier-company" class="font-medium text-gray-900">{{ $externalTruck->supplier->company_name ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">الهاتف:</span>
                                    <p id="supplier-phone" class="font-medium text-gray-900">{{ $externalTruck->supplier->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">البريد الإلكتروني:</span>
                                    <p id="supplier-email" class="font-medium text-gray-900">{{ $externalTruck->supplier->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contract Information -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم العقد/الاتفاقية</label>
                        <input type="text"
                               name="contract_number"
                               value="{{ old('contract_number', $externalTruck->contract_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('contract_number') border-red-500 @enderror"
                               placeholder="رقم العقد أو الاتفاقية">
                        @error('contract_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Daily Rate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">السعر اليومي</label>
                        <div class="relative">
                            <input type="number"
                                   name="daily_rate"
                                   value="{{ old('daily_rate', $externalTruck->daily_rate) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('daily_rate') border-red-500 @enderror pl-12"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">ر.س</span>
                            </div>
                        </div>
                        @error('daily_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ بداية العقد</label>
                        <input type="date"
                               name="contract_start_date"
                               value="{{ old('contract_start_date', $externalTruck->contract_start_date ? $externalTruck->contract_start_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('contract_start_date') border-red-500 @enderror">
                        @error('contract_start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract End Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ انتهاء العقد</label>
                        <input type="date"
                               name="contract_end_date"
                               value="{{ old('contract_end_date', $externalTruck->contract_end_date ? $externalTruck->contract_end_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('contract_end_date') border-red-500 @enderror">
                        @error('contract_end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Optional Vehicle Specifications -->
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl p-6 border-l-4 border-yellow-500">
                <h3 class="text-lg font-semibold text-yellow-900 mb-1 flex items-center">
                    <i class="ri-settings-3-line ml-2 text-yellow-600"></i>
                    المواصفات الاختيارية
                </h3>
                <p class="text-yellow-700 text-sm mb-4">معلومات إضافية عن نوع التحميل والسعة (اختيارية)</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Loading Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نوع التحميل</label>
                        <select name="loading_type"
                                id="loading_type_select"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('loading_type') border-red-500 @enderror"
                                onchange="toggleCapacityFields()">
                            <option value="">اختر نوع التحميل</option>
                            <option value="box" {{ old('loading_type', $externalTruck->loading_type) == 'box' ? 'selected' : '' }}>صندوق</option>
                            <option value="tank" {{ old('loading_type', $externalTruck->loading_type) == 'tank' ? 'selected' : '' }}>تانك</option>
                        </select>
                        @error('loading_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Box Capacity -->
                    <div id="box-capacity" class="{{ old('loading_type', $externalTruck->loading_type) == 'box' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">سعة الصندوق (متر مكعب)</label>
                        <div class="relative">
                            <input type="number"
                                   name="capacity_volume"
                                   value="{{ old('capacity_volume', $externalTruck->capacity_volume) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('capacity_volume') border-red-500 @enderror pl-8"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">م³</span>
                            </div>
                        </div>
                        @error('capacity_volume')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tank Capacity -->
                    <div id="tank-capacity" class="{{ old('loading_type', $externalTruck->loading_type) == 'tank' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">سعة التانك (طن)</label>
                        <div class="relative">
                            <input type="number"
                                   name="capacity_weight"
                                   value="{{ old('capacity_weight', $externalTruck->capacity_weight) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('capacity_weight') border-red-500 @enderror pl-8"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">طن</span>
                            </div>
                        </div>
                        @error('capacity_weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Current Photos -->
            @if($externalTruck->photos && count($externalTruck->photos) > 0)
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6 border-l-4 border-green-500">
                <h3 class="text-lg font-semibold text-green-900 mb-1 flex items-center">
                    <i class="ri-image-line ml-2 text-green-600"></i>
                    الصور الحالية
                </h3>
                <p class="text-green-700 text-sm mb-4">الصور المرفوعة حالياً للشاحنة</p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    @foreach($externalTruck->photo_urls as $index => $photoUrl)
                    <div class="relative group">
                        <img src="{{ $photoUrl }}"
                             alt="صورة الشاحنة {{ $index + 1 }}"
                             class="w-full h-32 object-cover rounded-lg border border-gray-200 group-hover:shadow-lg transition-all duration-200">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center">
                            <a href="{{ $photoUrl }}" target="_blank"
                               class="bg-white bg-opacity-90 text-gray-700 p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-white">
                                <i class="ri-eye-line text-lg"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-sm text-green-600 bg-green-100 p-2 rounded">
                    <i class="ri-information-line ml-1"></i>
                    يمكنك رفع صور جديدة أدناه لاستبدال الصور الحالية
                </p>
            </div>
            @endif

            <!-- Upload New Photos -->
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6 border-l-4 border-purple-500">
                <h3 class="text-lg font-semibold text-purple-900 mb-1 flex items-center">
                    <i class="ri-upload-cloud-line ml-2 text-purple-600"></i>
                    {{ $externalTruck->photos && count($externalTruck->photos) > 0 ? 'تحديث الصور' : 'رفع صور الشاحنة' }}
                </h3>
                <p class="text-purple-700 text-sm mb-4">اختر صور جديدة للشاحنة (اختياري)</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">اختر الصور</label>
                        <input type="file"
                               name="photos[]"
                               id="photos"
                               multiple
                               accept="image/jpeg,image/png,image/jpg"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('photos.*') border-red-500 @enderror">
                        @error('photos.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            يمكنك رفع عدة صور (JPG, PNG). الحد الأقصى 10MB لكل صورة.
                        </p>
                    </div>

                    <!-- Photo Preview -->
                    <div id="photo-preview" class="hidden">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">معاينة الصور المختارة:</h4>
                        <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border-l-4 border-gray-500">
                <h3 class="text-lg font-semibold text-gray-900 mb-1 flex items-center">
                    <i class="ri-file-text-line ml-2 text-gray-600"></i>
                    ملاحظات إضافية
                </h3>
                <p class="text-gray-700 text-sm mb-4">أي معلومات إضافية عن الشاحنة</p>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الملاحظات</label>
                    <textarea name="notes"
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('notes') border-red-500 @enderror"
                              placeholder="اكتب أي ملاحظات إضافية عن الشاحنة...">{{ old('notes', $externalTruck->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-200">
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="ri-save-line ml-2"></i>
                    حفظ التغييرات
                </button>
                <a href="{{ route('external-trucks.show', $externalTruck) }}"
                   class="bg-gray-300 text-gray-700 px-8 py-3 rounded-xl font-medium hover:bg-gray-400 transition-all duration-200">
                    إلغاء
                </a>
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

    // Toggle capacity fields based on loading type
    function toggleCapacityFields() {
        const loadingType = document.getElementById('loading_type_select').value;
        const boxCapacity = document.getElementById('box-capacity');
        const tankCapacity = document.getElementById('tank-capacity');

        // Hide both initially
        boxCapacity.classList.add('hidden');
        tankCapacity.classList.add('hidden');

        // Show relevant field
        if (loadingType === 'box') {
            boxCapacity.classList.remove('hidden');
        } else if (loadingType === 'tank') {
            tankCapacity.classList.remove('hidden');
        }
    }

    // Photo preview functionality
    document.getElementById('photos').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const previewDiv = document.getElementById('photo-preview');
        const previewContainer = document.getElementById('preview-container');

        if (files.length > 0) {
            previewDiv.classList.remove('hidden');
            previewContainer.innerHTML = '';

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('div');
                        img.innerHTML = `
                            <div class="relative">
                                <img src="${e.target.result}" alt="معاينة ${index + 1}"
                                     class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                <div class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs px-1 rounded">
                                    ${index + 1}
                                </div>
                            </div>
                        `;
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            previewDiv.classList.add('hidden');
        }
    });

    // Initialize capacity fields on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleCapacityFields();

        // Load supplier data if already selected
        const supplierSelect = document.getElementById('supplier_select');
        if (supplierSelect.value) {
            loadSupplierData(supplierSelect.value);
        }
    });
</script>
@endpush
@endsection
