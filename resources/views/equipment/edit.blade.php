@extends('layouts.app')

@section('title', 'تعديل المعدة: ' . $equipment->name)

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('equipment.index') }}"
           class="text-gray-600 hover:text-gray-900 transition-colors">
            <i class="ri-arrow-right-line text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تعديل المعدة</h1>
            <p class="text-gray-600 mt-1">{{ $equipment->name }}</p>
        </div>
    </div>

    <div class="max-w-4xl">
        <!-- Equipment Status Card -->
        <div class="bg-white rounded-xl shadow-sm border mb-6">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">حالة المعدة</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold
                            @if($equipment->status === 'available') text-green-600
                            @elseif($equipment->status === 'in_use') text-blue-600
                            @elseif($equipment->status === 'maintenance') text-yellow-600
                            @else text-red-600
                            @endif">
                            @if($equipment->status === 'available') متاحة
                            @elseif($equipment->status === 'in_use') قيد الاستخدام
                            @elseif($equipment->status === 'maintenance') تحت الصيانة
                            @else خارج الخدمة
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">الحالة الحالية</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($equipment->purchase_price, 0) }} ر.س</div>
                        <div class="text-sm text-gray-600">سعر الشراء</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ \Carbon\Carbon::parse($equipment->purchase_date)->format('Y') }}</div>
                        <div class="text-sm text-gray-600">سنة الشراء</div>
                    </div>
                    <div class="text-center">
                        @php
                            $months = \Carbon\Carbon::parse($equipment->purchase_date)->diffInMonths(\Carbon\Carbon::now());
                        @endphp
                        <div class="text-2xl font-bold text-purple-600">{{ $months }}</div>
                        <div class="text-sm text-gray-600">شهر (العمر)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">تعديل بيانات المعدة</h2>
            </div>

            <form action="{{ route('equipment.update', $equipment) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Equipment Name and Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم اللوحة <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $equipment->name) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            نوع المعدة <span class="text-red-500">*</span>
                        </label>
                        <select name="type_id"
                                id="type_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('type_id') border-red-300 @enderror"
                                required>
                            <option value="">اختر نوع المعدة</option>
                            @foreach($equipmentTypes as $type)
                                <option value="{{ $type->id }}" {{ old('type_id', $equipment->type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Manufacturer and Model -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="manufacturer" class="block text-sm font-medium text-gray-700 mb-2">
                            الشركة المصنعة
                        </label>
                        <input type="text"
                               name="manufacturer"
                               id="manufacturer"
                               value="{{ old('manufacturer', $equipment->manufacturer) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('manufacturer') border-red-300 @enderror"
                               placeholder="مثل: كاتربيلر، فولفو، إلخ">
                        @error('manufacturer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                            الموديل
                        </label>
                        <input type="text"
                               name="model"
                               id="model"
                               value="{{ old('model', $equipment->model) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('model') border-red-300 @enderror"
                               placeholder="رقم الموديل">
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Serial Number and Location -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">
                            الرقم التسلسلي
                        </label>
                        <input type="text"
                               name="serial_number"
                               id="serial_number"
                               value="{{ old('serial_number', $equipment->serial_number) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('serial_number') border-red-300 @enderror"
                               placeholder="الرقم التسلسلي للمعدة">
                        @error('serial_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">
                            السائق المسؤول
                        </label>
                        <select name="driver_id"
                                id="driver_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('driver_id') border-red-300 @enderror">
                            <option value="">اختر السائق (اختياري)</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                        {{ old('driver_id', $equipment->driver_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('driver_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="ri-information-line text-blue-600 mt-0.5 ml-2"></i>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium">تحديث تلقائي للحالة:</p>
                                    <ul class="mt-1 space-y-1">
                                        <li>• عند تعيين سائق: ستتغير الحالة إلى "قيد الاستخدام"</li>
                                        <li>• عند إزالة السائق: ستتغير الحالة إلى "متاحة"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">
                            الموقع الحالي
                        </label>
                        <select name="location_id"
                                id="location_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('location_id') border-red-300 @enderror">
                            <option value="">اختر الموقع (اختياري)</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}"
                                        {{ old('location_id', $equipment->location_id) == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Purchase Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">
                            سعر الشراء (ر.س) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="purchase_price"
                               id="purchase_price"
                               value="{{ old('purchase_price', $equipment->purchase_price) }}"
                               step="0.01"
                               min="0"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('purchase_price') border-red-300 @enderror"
                               required>
                        @error('purchase_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ الشراء <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="purchase_date"
                               id="purchase_date"
                               value="{{ old('purchase_date', $equipment->purchase_date ? $equipment->purchase_date->format('Y-m-d') : '') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('purchase_date') border-red-300 @enderror"
                               required>
                        @error('purchase_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="warranty_expiry" class="block text-sm font-medium text-gray-700 mb-2">
                            انتهاء الضمان
                        </label>
                        <input type="date"
                               name="warranty_expiry"
                               id="warranty_expiry"
                               value="{{ old('warranty_expiry', $equipment->warranty_expiry ? $equipment->warranty_expiry->format('Y-m-d') : '') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('warranty_expiry') border-red-300 @enderror">
                        @error('warranty_expiry')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_maintenance" class="block text-sm font-medium text-gray-700 mb-2">
                            آخر صيانة
                        </label>
                        <input type="date"
                               name="last_maintenance"
                               id="last_maintenance"
                               value="{{ old('last_maintenance', $equipment->last_maintenance ? $equipment->last_maintenance->format('Y-m-d') : '') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('last_maintenance') border-red-300 @enderror">
                        @error('last_maintenance')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        حالة المعدة <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            class="w-full max-w-xs rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-300 @enderror"
                            required>
                        <option value="available" {{ old('status', $equipment->status) === 'available' ? 'selected' : '' }}>متاحة</option>
                        <option value="in_use" {{ old('status', $equipment->status) === 'in_use' ? 'selected' : '' }}>قيد الاستخدام</option>
                        <option value="maintenance" {{ old('status', $equipment->status) === 'maintenance' ? 'selected' : '' }}>تحت الصيانة</option>
                        <option value="out_of_order" {{ old('status', $equipment->status) === 'out_of_order' ? 'selected' : '' }}>خارج الخدمة</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        وصف المعدة
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-300 @enderror"
                              placeholder="وصف تفصيلي للمعدة، مواصفاتها، وحالتها">{{ old('description', $equipment->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Equipment Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        صور المعدة
                    </label>

                    <!-- Existing Images -->
                    @if($equipment->images && count($equipment->images) > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">الصور الحالية</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="existing-images">
                            @foreach($equipment->images as $index => $imagePath)
                            <div class="relative group" id="existing-image-{{ $index }}">
                                <img src="{{ asset('storage/' . $imagePath) }}"
                                     class="w-full h-24 object-cover rounded-lg border border-gray-200"
                                     alt="صورة المعدة {{ $index + 1 }}">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100
                                            transition-opacity rounded-lg flex items-center justify-center">
                                    <button type="button"
                                            onclick="removeExistingImage({{ $index }})"
                                            class="text-white bg-red-600 rounded-full p-1 hover:bg-red-700">
                                        <i class="ri-close-line text-sm"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- New Images Upload -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">إضافة صور جديدة</h4>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition-colors">
                            <input type="file"
                                   id="images"
                                   name="images[]"
                                   multiple
                                   accept="image/*"
                                   class="hidden"
                                   onchange="handleImageUpload(this)">

                            <div id="upload-area" class="cursor-pointer" onclick="document.getElementById('images').click()">
                                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-image-add-line text-2xl text-gray-600"></i>
                                </div>
                                <p class="text-gray-600 mb-2">انقر لاختيار الصور أو اسحبها هنا</p>
                                <p class="text-sm text-gray-500">يمكن رفع عدة صور (JPEG, PNG, JPG, GIF - حد أقصى 5 ميجابايت لكل صورة)</p>
                            </div>

                            <!-- Preview Area -->
                            <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                        </div>
                        @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Equipment Files -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        ملفات المعدة
                    </label>

                    <!-- Existing Files -->
                    @if($equipment->files && $equipment->files->count() > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">الملفات الحالية</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="existing-files">
                            @foreach($equipment->files as $file)
                            <div class="border border-gray-200 rounded-lg p-3" id="existing-file-{{ $file->id }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            @if(str_contains($file->file_type, 'pdf'))
                                                <i class="ri-file-pdf-line text-red-600"></i>
                                            @elseif(str_contains($file->file_type, 'word') || str_contains($file->file_type, 'document'))
                                                <i class="ri-file-word-line text-blue-600"></i>
                                            @elseif(str_contains($file->file_type, 'image'))
                                                <i class="ri-image-line text-green-600"></i>
                                            @else
                                                <i class="ri-file-line text-gray-600"></i>
                                            @endif
                                            <span class="text-sm font-medium text-gray-900">{{ $file->file_name }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mb-1">{{ $file->original_name }}</p>
                                        @if($file->expiry_date)
                                        <p class="text-xs text-gray-600">انتهاء الصلاحية: {{ $file->expiry_date->format('d/m/Y') }}</p>
                                        @endif
                                    </div>
                                    <button type="button"
                                            onclick="removeExistingFile({{ $file->id }})"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- New Files Upload -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">إضافة ملفات جديدة</h4>
                        <div id="files-container" class="space-y-4">
                            <!-- Initial File Entry -->
                            <div class="file-entry border border-gray-300 rounded-xl p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم الملف</label>
                                        <input type="text"
                                               name="file_names[]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="مثال: شهادة الضمان">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ انتهاء الصلاحية</label>
                                        <input type="date"
                                               name="file_expiry_dates[]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">اختيار الملف</label>
                                        <input type="file"
                                               name="files[]"
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">وصف الملف (اختياري)</label>
                                    <textarea name="file_descriptions[]"
                                              rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              placeholder="وصف مختصر للملف ومحتواه"></textarea>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <button type="button" onclick="removeFileEntry(this)"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        <i class="ri-delete-bin-line mr-1"></i>
                                        حذف الملف
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Add More Files Button -->
                        <div class="mt-4">
                            <button type="button" onclick="addFileEntry()"
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-add-line"></i>
                                إضافة ملف آخر
                            </button>
                        </div>

                        <div class="text-sm text-gray-500 mt-2">
                            <p>الملفات المدعومة: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF (حد أقصى 10 ميجابايت لكل ملف)</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('equipment.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                            إلغاء
                        </a>
                        <a href="{{ route('equipment.show', $equipment) }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-eye-line"></i>
                            عرض التفاصيل
                        </a>
                    </div>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-save-line"></i>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-set warranty expiry based on purchase date (default 2 years)
    const purchaseDateInput = document.getElementById('purchase_date');
    const warrantyExpiryInput = document.getElementById('warranty_expiry');

    purchaseDateInput.addEventListener('change', function() {
        if (this.value && !warrantyExpiryInput.value) {
            const purchaseDate = new Date(this.value);
            const warrantyExpiry = new Date(purchaseDate);
            warrantyExpiry.setFullYear(warrantyExpiry.getFullYear() + 2); // Default 2 years warranty
            warrantyExpiryInput.value = warrantyExpiry.toISOString().split('T')[0];
        }
    });

    // Status change confirmation for critical statuses
    const statusSelect = document.getElementById('status');
    let originalStatus = statusSelect.value;

    statusSelect.addEventListener('change', function() {
        if ((this.value === 'out_of_order' || this.value === 'maintenance') &&
            originalStatus !== this.value) {
            if (!confirm('هل أنت متأكد من تغيير حالة المعدة إلى "' +
                        this.options[this.selectedIndex].text + '"؟')) {
                this.value = originalStatus;
            }
        }
    });

    // Image handling functions
    function handleImageUpload(input) {
        const previewArea = document.getElementById('image-preview');
        const uploadArea = document.getElementById('upload-area');

        if (input.files && input.files.length > 0) {
            previewArea.innerHTML = '';
            previewArea.classList.remove('hidden');

            Array.from(input.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'relative group';
                        previewItem.innerHTML = `
                            <img src="${e.target.result}"
                                 class="w-full h-24 object-cover rounded-lg border border-gray-200"
                                 alt="معاينة الصورة ${index + 1}">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100
                                        transition-opacity rounded-lg flex items-center justify-center">
                                <button type="button"
                                        onclick="removeImage(${index})"
                                        class="text-white bg-red-600 rounded-full p-1 hover:bg-red-700">
                                    <i class="ri-close-line text-sm"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-600 mt-1 text-center truncate" title="${file.name}">
                                ${file.name}
                            </p>
                        `;
                        previewArea.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Update upload area text
            uploadArea.innerHTML = `
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <i class="ri-check-line text-2xl text-green-600"></i>
                </div>
                <p class="text-green-600 mb-2">تم اختيار ${input.files.length} صورة</p>
                <p class="text-sm text-gray-500">انقر لاختيار صور أخرى</p>
            `;
        }
    }

    function removeImage(index) {
        const input = document.getElementById('images');
        const dt = new DataTransfer();

        // Re-add all files except the one to remove
        Array.from(input.files).forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });

        input.files = dt.files;
        handleImageUpload(input);

        if (input.files.length === 0) {
            const previewArea = document.getElementById('image-preview');
            const uploadArea = document.getElementById('upload-area');

            previewArea.classList.add('hidden');
            uploadArea.innerHTML = `
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="ri-image-add-line text-2xl text-gray-600"></i>
                </div>
                <p class="text-gray-600 mb-2">انقر لاختيار الصور أو اسحبها هنا</p>
                <p class="text-sm text-gray-500">يمكن رفع عدة صور (JPEG, PNG, JPG, GIF - حد أقصى 5 ميجابايت لكل صورة)</p>
            `;
        }
    }

    function removeExistingImage(index) {
        if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
            document.getElementById('existing-image-' + index).style.display = 'none';
            // Add hidden input to mark image for deletion
            const form = document.querySelector('form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_images[]';
            input.value = index;
            form.appendChild(input);
        }
    }

    // File management functions
    let fileCounter = 0;

    function addFileEntry() {
        fileCounter++;
        const container = document.getElementById('file-entries-container');
        const fileEntry = document.createElement('div');
        fileEntry.className = 'file-entry bg-gray-50 p-4 rounded-lg border border-gray-200';
        fileEntry.innerHTML = `
            <div class="flex justify-between items-start mb-4">
                <h6 class="text-sm font-medium text-gray-700">ملف جديد</h6>
                <button type="button" onclick="removeFileEntry(this)"
                        class="text-red-500 hover:text-red-700 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-file-line ml-1"></i>
                        اختيار الملف
                    </label>
                    <input type="file" name="files[]"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX أو صورة (حد أقصى 10 ميجابايت)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-text ml-1"></i>
                        اسم الملف
                    </label>
                    <input type="text" name="file_names[]"
                           placeholder="مثال: شهادة صلاحية المعدة"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-calendar-line ml-1"></i>
                        تاريخ انتهاء الصلاحية
                    </label>
                    <input type="date" name="expiry_dates[]"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-file-text-line ml-1"></i>
                        وصف الملف (اختياري)
                    </label>
                    <input type="text" name="file_descriptions[]"
                           placeholder="وصف مختصر للملف"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        `;
        container.appendChild(fileEntry);
    }

    function removeFileEntry(button) {
        button.closest('.file-entry').remove();
    }

    function removeExistingFile(fileId, fileName) {
        if (confirm(`هل أنت متأكد من حذف الملف "${fileName}"؟`)) {
            document.getElementById('existing-file-' + fileId).style.display = 'none';
            // Add hidden input to mark file for deletion
            const form = document.querySelector('form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_files[]';
            input.value = fileId;
            form.appendChild(input);
        }
    }

    function getFileIcon(fileType) {
        if (fileType.includes('pdf')) return 'ri-file-pdf-line text-red-500';
        if (fileType.includes('doc')) return 'ri-file-word-line text-blue-500';
        if (fileType.includes('image')) return 'ri-image-line text-green-500';
        return 'ri-file-line text-gray-500';
    }

    function isExpiringSoon(expiryDate) {
        if (!expiryDate) return false;
        const today = new Date();
        const expiry = new Date(expiryDate);
        const diffTime = expiry - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays <= 30 && diffDays >= 0;
    }

    function isExpired(expiryDate) {
        if (!expiryDate) return false;
        const today = new Date();
        const expiry = new Date(expiryDate);
        return expiry < today;
    }

    // Make functions global
    window.handleImageUpload = handleImageUpload;
    window.removeImage = removeImage;
    window.removeExistingImage = removeExistingImage;
    window.addFileEntry = addFileEntry;
    window.removeFileEntry = removeFileEntry;
    window.removeExistingFile = removeExistingFile;

    // Drag and drop functionality
    const uploadArea = document.getElementById('upload-area');
    if (uploadArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.parentElement.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight(e) {
            uploadArea.parentElement.classList.remove('border-blue-500', 'bg-blue-50');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const input = document.getElementById('images');
            input.files = files;
            handleImageUpload(input);
        }
    }

    // Monitor driver selection changes
    const driverSelect = document.getElementById('driver_id');
    const statusInfo = document.querySelector('.bg-blue-50');

    if (driverSelect && statusInfo) {
        const currentDriverId = '{{ old("driver_id", $equipment->driver_id) }}';

        driverSelect.addEventListener('change', function() {
            const selectedValue = this.value;

            if (selectedValue && selectedValue !== currentDriverId) {
                // New driver selected or changed
                statusInfo.className = 'mt-2 p-3 bg-green-50 border border-green-200 rounded-lg';
                statusInfo.innerHTML = `
                    <div class="flex items-start">
                        <i class="ri-check-circle-line text-green-600 mt-0.5 ml-2"></i>
                        <div class="text-sm text-green-800">
                            <p class="font-medium">سيتم تحديث الحالة تلقائياً:</p>
                            <p class="mt-1">حالة المعدة ستتغير إلى "قيد الاستخدام" عند الحفظ</p>
                        </div>
                    </div>
                `;
            } else if (!selectedValue && currentDriverId) {
                // Driver removed
                statusInfo.className = 'mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg';
                statusInfo.innerHTML = `
                    <div class="flex items-start">
                        <i class="ri-alert-line text-yellow-600 mt-0.5 ml-2"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium">سيتم تحديث الحالة تلقائياً:</p>
                            <p class="mt-1">حالة المعدة ستتغير إلى "متاحة" عند الحفظ</p>
                        </div>
                    </div>
                `;
            } else {
                // Reset to original info
                statusInfo.className = 'mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg';
                statusInfo.innerHTML = `
                    <div class="flex items-start">
                        <i class="ri-information-line text-blue-600 mt-0.5 ml-2"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium">تحديث تلقائي للحالة:</p>
                            <ul class="mt-1 space-y-1">
                                <li>• عند تعيين سائق: ستتغير الحالة إلى "قيد الاستخدام"</li>
                                <li>• عند إزالة السائق: ستتغير الحالة إلى "متاحة"</li>
                            </ul>
                        </div>
                    </div>
                `;
            }
        });
    }

    // Initialize Select2 for equipment types
    if ($('#type_id').length) {
        $('#type_id').select2({
            placeholder: 'اختر نوع المعدة أو ابحث...',
            allowClear: true,
            language: {
                noResults: function () {
                    return 'لا توجد نتائج';
                },
                searching: function () {
                    return 'جارٍ البحث...';
                }
            },
            width: '100%'
        });
    }
});
</script>

<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 42px !important;
        padding: 8px 12px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        background-color: white !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding: 0 !important;
        line-height: 26px !important;
        color: #374151 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 10px !important;
    }

    .select2-dropdown {
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }

    .select2-search--dropdown .select2-search__field {
        padding: 8px 12px !important;
        border-radius: 6px !important;
        border: 1px solid #d1d5db !important;
    }

    .select2-results__option--highlighted {
        background-color: #3b82f6 !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }
</style>

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
@endsection
