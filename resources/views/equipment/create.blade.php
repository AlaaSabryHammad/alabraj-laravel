@extends('layouts.app')

@section('title', 'إضافة معدة جديدة - شركة الأبراج للمقاولات')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    @if (isset($preselectedLocation) && $preselectedLocation)
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة معدة جديدة</h1>
                        <p class="text-gray-600">إضافة معدة جديدة للموقع: <span
                                class="font-semibold text-green-600">{{ $preselectedLocation->name }}</span></p>
                    @else
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة معدة جديدة</h1>
                        <p class="text-gray-600">أدخل بيانات المعدة الجديدة في النموذج أدناه</p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    @if (isset($preselectedLocation) && $preselectedLocation)
                        <a href="{{ route('locations.show', $preselectedLocation) }}"
                            class="bg-green-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-green-600 transition-all duration-200 flex items-center">
                            <i class="ri-map-pin-line ml-2"></i>
                            عرض الموقع
                        </a>
                    @endif
                    <a href="{{ route('equipment.index') }}"
                        class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            @if (isset($preselectedLocation) && $preselectedLocation)
                <!-- Location Alert -->
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="ri-map-pin-line text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-green-900">سيتم إضافة المعدة للموقع التالي:</h4>
                            <p class="text-green-700">{{ $preselectedLocation->name }}</p>
                            <p class="text-green-600 text-sm">لا يمكن تغيير الموقع عند الإنشاء من صفحة الموقع</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                @if (isset($preselectedLocation) && $preselectedLocation)
                    <input type="hidden" name="from_location" value="1">
                @endif

                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">المعلومات الأساسية</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">رقم اللوحة *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type_id" class="block text-sm font-medium text-gray-700 mb-2">نوع المعدة *</label>
                            <select id="type_id" name="type_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('type_id') border-red-500 @enderror"
                                required>
                                <option value="">اختر نوع المعدة</option>
                                @foreach ($equipmentTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 mb-2">الموديل</label>
                            <input type="text" id="model" name="model" value="{{ old('model') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('model') border-red-500 @enderror">
                            @error('model')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="manufacturer" class="block text-sm font-medium text-gray-700 mb-2">الشركة
                                المصنعة</label>
                            <input type="text" id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('manufacturer') border-red-500 @enderror">
                            @error('manufacturer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">الرقم التسلسلي
                                *</label>
                            <input type="text" id="serial_number" name="serial_number"
                                value="{{ old('serial_number') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('serial_number') border-red-500 @enderror"
                                required>
                            @error('serial_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">كود المعدة</label>
                            <div class="flex gap-2">
                                <input type="text" id="code" name="code" readonly
                                    class="flex-1 px-4 py-3 border border-blue-300 rounded-xl bg-blue-50 text-blue-900 font-semibold cursor-not-allowed"
                                    placeholder="سيتم توليده تلقائياً">
                                <button type="button" onclick="generateCode()"
                                    class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
                                    <i class="ri-refresh-line"></i>
                                    توليد
                                </button>
                            </div>
                            <p class="text-blue-600 text-sm mt-1">
                                <i class="ri-information-line"></i>
                                سيتم توليد الكود تلقائياً عند حفظ المعدة بشكل متسلسل (مثال: EQ-0001، EQ-0002، إلخ)
                            </p>
                        </div>

                        <div>
                            <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">السائق
                                المسؤول</label>
                            <select id="driver_id" name="driver_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('driver_id') border-red-500 @enderror">
                                <option value="">اختر السائق (اختياري)</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('driver_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div id="driver-status-info" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="ri-information-line text-blue-600 mt-0.5 ml-2"></i>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-medium">تحديث تلقائي للحالة:</p>
                                        <p class="mt-1">عند تعيين سائق، ستتغير حالة المعدة تلقائياً إلى "قيد الاستخدام"
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">الموقع
                                الحالي</label>
                            @if (isset($preselectedLocation) && $preselectedLocation)
                                <!-- Preselected Location (Read-only) -->
                                <div
                                    class="w-full px-4 py-3 border border-green-300 rounded-xl bg-green-50 text-green-900 font-medium flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <i class="ri-map-pin-line text-green-600"></i>
                                        <span>{{ $preselectedLocation->name }}</span>
                                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">محدد
                                            مسبقاً</span>
                                    </div>
                                    <i class="ri-lock-line text-green-600"></i>
                                </div>
                                <input type="hidden" name="location_id" value="{{ $preselectedLocation->id }}">
                                <!-- Debug info -->
                                <script>
                                    console.log('Preselected Location ID:', {{ $preselectedLocation->id }});
                                </script>
                                <p class="text-green-600 text-sm mt-1">
                                    <i class="ri-information-line"></i>
                                    تم تحديد هذا الموقع تلقائياً ولا يمكن تغييره (ID: {{ $preselectedLocation->id }})
                                </p>
                            @else
                                <!-- Normal Location Selection -->
                                <select id="location_id" name="location_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('location_id') border-red-500 @enderror">
                                    <option value="">اختر الموقع (اختياري)</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}"
                                            {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            @error('location_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Purchase Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الشراء</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الشراء
                                *</label>
                            <input type="date" id="purchase_date" name="purchase_date"
                                value="{{ old('purchase_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('purchase_date') border-red-500 @enderror"
                                required>
                            @error('purchase_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">سعر الشراء
                                (ريال سعودي) *</label>
                            <input type="number" id="purchase_price" name="purchase_price"
                                value="{{ old('purchase_price') }}" min="0" step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('purchase_price') border-red-500 @enderror"
                                required>
                            @error('purchase_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="warranty_expiry" class="block text-sm font-medium text-gray-700 mb-2">تاريخ انتهاء
                                الضمان</label>
                            <input type="date" id="warranty_expiry" name="warranty_expiry"
                                value="{{ old('warranty_expiry') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('warranty_expiry') border-red-500 @enderror">
                            @error('warranty_expiry')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_maintenance" class="block text-sm font-medium text-gray-700 mb-2">آخر
                                صيانة</label>
                            <input type="date" id="last_maintenance" name="last_maintenance"
                                value="{{ old('last_maintenance') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('last_maintenance') border-red-500 @enderror">
                            @error('last_maintenance')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف المعدة</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('description') border-red-500 @enderror"
                            placeholder="وصف تفصيلي للمعدة، مواصفاتها، وحالتها">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Equipment Images -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">صور المعدة</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                إضافة صور للمعدة (اختياري)
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-500 transition-colors">
                                <input type="file" id="images" name="images[]" multiple accept="image/*"
                                    class="hidden" onchange="handleImageUpload(this)">

                                <div id="upload-area" class="cursor-pointer"
                                    onclick="document.getElementById('images').click()">
                                    <div
                                        class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="ri-image-add-line text-2xl text-gray-600"></i>
                                    </div>
                                    <p class="text-gray-600 mb-2">انقر لاختيار الصور أو اسحبها هنا</p>
                                    <p class="text-sm text-gray-500">يمكن رفع عدة صور (JPEG, PNG, JPG, GIF - حد أقصى 40
                                        ميجابايت لكل صورة)</p>
                                </div>

                                <!-- Preview Area -->
                                <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                            </div>
                            @error('images.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Equipment Files -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ملفات المعدة</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                إضافة ملفات المعدة (شهادات، كتيبات، ضمانات، إلخ)
                            </label>

                            <!-- Files Container -->
                            <div id="files-container" class="space-y-4">
                                <!-- Initial File Entry -->
                                <div class="file-entry border border-gray-300 rounded-xl p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم الملف</label>
                                            <input type="text" name="file_names[]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                                placeholder="مثال: شهادة الضمان">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ انتهاء
                                                الصلاحية</label>
                                            <input type="date" name="file_expiry_dates[]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">اختيار
                                                الملف</label>
                                            <input type="file" name="files[]"
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">وصف الملف
                                            (اختياري)</label>
                                        <textarea name="file_descriptions[]" rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
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
                                    class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                    <i class="ri-add-line"></i>
                                    إضافة ملف آخر
                                </button>
                            </div>

                            <div class="text-sm text-gray-500 mt-2">
                                <p>الملفات المدعومة: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF (حد أقصى 10 ميجابايت لكل ملف)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-200">
                    <a href="{{ route('equipment.index') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-3 rounded-xl font-medium hover:from-orange-700 hover:to-orange-800 transition-all duration-200 flex items-center">
                        <i class="ri-save-line ml-2"></i>
                        حفظ المعدة
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Generate equipment code preview
        function generateCode() {
            const codeInput = document.getElementById('code');

            // Fetch the next code from the server
            fetch('{{ route("equipment.getNextCode") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                codeInput.value = data.code;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في توليد الكود');
            });
        }

        // Auto-generate code when page loads
        document.addEventListener('DOMContentLoaded', function() {
            generateCode();
        });

        function handleImageUpload(input) {
            const previewArea = document.getElementById('image-preview');
            const uploadArea = document.getElementById('upload-area');

            if (input.files && input.files.length > 0) {
                previewArea.innerHTML = '';
                previewArea.classList.remove('hidden');

                // Check file sizes
                const maxSize = 40 * 1024 * 1024; // 40MB in bytes
                const validFiles = [];
                const invalidFiles = [];

                Array.from(input.files).forEach((file) => {
                    if (file.size > maxSize) {
                        invalidFiles.push(file);
                    } else {
                        validFiles.push(file);
                    }
                });

                // Show error for oversized files
                if (invalidFiles.length > 0) {
                    const fileNames = invalidFiles.map(f => f.name).join('، ');
                    alert(`الملفات التالية تتجاوز الحد الأقصى المسموح (40 ميجابايت): ${fileNames}`);

                    // Reset file input to remove invalid files
                    const dt = new DataTransfer();
                    validFiles.forEach(file => dt.items.add(file));
                    input.files = dt.files;
                }

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
            <p class="text-sm text-gray-500">يمكن رفع عدة صور (JPEG, PNG, JPG, GIF - حد أقصى 40 ميجابايت لكل صورة)</p>
        `;
            }
        }

        // Drag and drop functionality
        const uploadArea = document.getElementById('upload-area');

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
            uploadArea.parentElement.classList.add('border-orange-500', 'bg-orange-50');
        }

        function unhighlight(e) {
            uploadArea.parentElement.classList.remove('border-orange-500', 'bg-orange-50');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const input = document.getElementById('images');
            input.files = files;
            handleImageUpload(input);
        }

        // File management functions
        function addFileEntry() {
            const container = document.getElementById('files-container');
            const fileEntryHTML = `
        <div class="file-entry border border-gray-300 rounded-xl p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم الملف</label>
                    <input type="text"
                           name="file_names[]"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                           placeholder="مثال: شهادة الضمان">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ انتهاء الصلاحية</label>
                    <input type="date"
                           name="file_expiry_dates[]"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اختيار الملف</label>
                    <input type="file"
                           name="files[]"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">وصف الملف (اختياري)</label>
                <textarea name="file_descriptions[]"
                          rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
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
    `;
            container.insertAdjacentHTML('beforeend', fileEntryHTML);
        }

        function removeFileEntry(button) {
            const fileEntry = button.closest('.file-entry');
            const container = document.getElementById('files-container');

            // Don't allow removing the last file entry
            if (container.children.length > 1) {
                fileEntry.remove();
            } else {
                // Clear the inputs instead of removing the entry
                const inputs = fileEntry.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    input.value = '';
                });
            }
        }

        // Monitor driver selection changes
        document.addEventListener('DOMContentLoaded', function() {
            const driverSelect = document.getElementById('driver_id');
            const statusInfo = document.getElementById('driver-status-info');

            if (driverSelect && statusInfo) {
                driverSelect.addEventListener('change', function() {
                    if (this.value) {
                        // Driver selected - show status will change to in_use
                        statusInfo.style.display = 'block';
                        statusInfo.className = 'mt-2 p-3 bg-green-50 border border-green-200 rounded-lg';
                        statusInfo.innerHTML = `
                    <div class="flex items-start">
                        <i class="ri-check-circle-line text-green-600 mt-0.5 ml-2"></i>
                        <div class="text-sm text-green-800">
                            <p class="font-medium">سيتم تعيين الحالة تلقائياً:</p>
                            <p class="mt-1">حالة المعدة ستكون "قيد الاستخدام" عند الحفظ</p>
                        </div>
                    </div>
                `;
                    } else {
                        // No driver - reset to original info
                        statusInfo.className = 'mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg';
                        statusInfo.innerHTML = `
                    <div class="flex items-start">
                        <i class="ri-information-line text-blue-600 mt-0.5 ml-2"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium">تحديث تلقائي للحالة:</p>
                            <p class="mt-1">عند تعيين سائق، ستتغير حالة المعدة تلقائياً إلى "قيد الاستخدام"</p>
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
                        noResults: function() {
                            return 'لا توجد نتائج';
                        },
                        searching: function() {
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
            height: 50px !important;
            padding: 12px 16px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 12px !important;
            background-color: white !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
            line-height: 26px !important;
            color: #374151 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
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
            background-color: #f97316 !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #f97316 !important;
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2) !important;
        }
    </style>

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
@endsection
