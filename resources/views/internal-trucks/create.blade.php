@extends('layouts.app')

@section('title', 'إضافة شاحنة داخلية جديدة - شركة الأبراج للمقاولات')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة شاحنة داخلية جديدة</h1>
                    <p class="text-gray-600">أدخل بيانات الشاحنة الداخلية الجديدة في النموذج أدناه</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('internal-trucks.index') }}"
                        class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('internal-trucks.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">المعلومات الأساسية</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="plate_number" class="block text-sm font-medium text-gray-700 mb-2">رقم اللوحة
                                *</label>
                            <input type="text" id="plate_number" name="plate_number" value="{{ old('plate_number') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('plate_number') border-red-500 @enderror"
                                placeholder="مثل: ABC-123" required>
                            @error('plate_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">العلامة التجارية
                                *</label>
                            <input type="text" id="brand" name="brand" value="{{ old('brand') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('brand') border-red-500 @enderror"
                                placeholder="مثل: فورد، تويوتا، إيسوزو" required>
                            @error('brand')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 mb-2">الموديل</label>
                            <input type="text" id="model" name="model" value="{{ old('model') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('model') border-red-500 @enderror"
                                placeholder="مثل: H100، ميتسوبيشي كانتر">
                            @error('model')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">سنة الصنع</label>
                            <input type="number" id="year" name="year" value="{{ old('year') }}" min="1980"
                                max="{{ date('Y') + 1 }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('year') border-red-500 @enderror"
                                placeholder="{{ date('Y') }}">
                            @error('year')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        

                        <div>
                            <label for="load_capacity" class="block text-sm font-medium text-gray-700 mb-2">حمولة الشاحنة
                                الداخلية (كيلوجرام)</label>
                            <input type="number" id="load_capacity" name="load_capacity"
                                value="{{ old('load_capacity') }}" min="0" step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('load_capacity') border-red-500 @enderror"
                                placeholder="مثل: 3000">
                            @error('load_capacity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="ri-information-line text-blue-600 mt-0.5 ml-2"></i>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-medium">تحديث تلقائي للحالة:</p>
                                        <p class="mt-1">عند تعيين سائق، ستتغير حالة الشاحنة الداخلية تلقائياً إلى "قيد
                                            الاستخدام"</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">الموقع
                                الحالي</label>
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
                            @error('location_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Technical Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">المعلومات التقنية</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="engine_number" class="block text-sm font-medium text-gray-700 mb-2">رقم
                                المحرك</label>
                            <input type="text" id="engine_number" name="engine_number"
                                value="{{ old('engine_number') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('engine_number') border-red-500 @enderror"
                                placeholder="رقم المحرك المحفور على المحرك">
                            @error('engine_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="chassis_number" class="block text-sm font-medium text-gray-700 mb-2">رقم
                                الشاصي</label>
                            <input type="text" id="chassis_number" name="chassis_number"
                                value="{{ old('chassis_number') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('chassis_number') border-red-500 @enderror"
                                placeholder="رقم الشاصي الموجود في دفتر المركبة">
                            @error('chassis_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الوقود</label>
                            <select id="fuel_type" name="fuel_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('fuel_type') border-red-500 @enderror">
                                <option value="">اختر نوع الوقود</option>
                                <option value="gasoline" {{ old('fuel_type') == 'gasoline' ? 'selected' : '' }}>بنزين
                                </option>
                                <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>ديزل</option>
                                <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>هجين</option>
                            </select>
                            @error('fuel_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">حالة الشاحنة
                                الداخلية</label>
                            <select id="status" name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>متاحة
                                </option>
                                <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>قيد الاستخدام
                                </option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>تحت
                                    الصيانة</option>
                                <option value="out_of_service" {{ old('status') == 'out_of_service' ? 'selected' : '' }}>
                                    خارج الخدمة</option>
                            </select>
                            @error('status')
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

                        <div>
                            <label for="license_expiry" class="block text-sm font-medium text-gray-700 mb-2">تاريخ انتهاء
                                الرخصة</label>
                            <input type="date" id="license_expiry" name="license_expiry"
                                value="{{ old('license_expiry') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('license_expiry') border-red-500 @enderror">
                            @error('license_expiry')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="insurance_expiry" class="block text-sm font-medium text-gray-700 mb-2">تاريخ
                                انتهاء التأمين</label>
                            <input type="date" id="insurance_expiry" name="insurance_expiry"
                                value="{{ old('insurance_expiry') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('insurance_expiry') border-red-500 @enderror">
                            @error('insurance_expiry')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الشاحنة
                            الداخلية</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('description') border-red-500 @enderror"
                            placeholder="وصف تفصيلي للشاحنة الداخلية، مواصفاتها، وحالتها">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Truck Images -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">صور الشاحنة الداخلية</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                إضافة صور للشاحنة الداخلية (اختياري)
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

                <!-- Truck Files -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ملفات الشاحنة الداخلية</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                إضافة ملفات الشاحنة الداخلية (شهادات، كتيبات، ضمانات، إلخ)
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
                    <a href="{{ route('internal-trucks.index') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-3 rounded-xl font-medium hover:from-orange-700 hover:to-orange-800 transition-all duration-200 flex items-center">
                        <i class="ri-save-line ml-2"></i>
                        حفظ الشاحنة الداخلية
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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
            const statusInfo = document.querySelector('.bg-blue-50');

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
                            <p class="mt-1">حالة الشاحنة الداخلية ستكون "قيد الاستخدام" عند الحفظ</p>
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
                            <p class="mt-1">عند تعيين سائق، ستتغير حالة الشاحنة الداخلية تلقائياً إلى "قيد الاستخدام"</p>
                        </div>
                    </div>
                `;
                    }
                });
            }
        });
    </script>
@endsection
