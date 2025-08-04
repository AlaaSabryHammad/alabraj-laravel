@extends('layouts.app')

@section('title', 'إضافة مشروع جديد - شركة الأبراج للمقاولات')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة مشروع جديد</h1>
                    <p class="text-gray-600">أدخل بيانات المشروع الجديد في النموذج أدناه</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('projects.index') }}"
                        class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="ri-information-line text-blue-600"></i>
                        </div>
                        المعلومات الأساسية
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم المشروع *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="أدخل اسم المشروع" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_number" class="block text-sm font-medium text-gray-700 mb-2">رقم المشروع
                                *</label>
                            <input type="text" id="project_number" name="project_number"
                                value="{{ old('project_number') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('project_number') border-red-500 @enderror"
                                placeholder="رقم المشروع" required>
                            @error('project_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">قيمة المشروع (ر.س)
                                *</label>
                            <input type="number" id="budget" name="budget" value="{{ old('budget') }}" step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('budget') border-red-500 @enderror"
                                placeholder="0.00" required>
                            @error('budget')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="government_entity" class="block text-sm font-medium text-gray-700 mb-2">الجهة
                                الحكومية</label>
                            <input type="text" id="government_entity" name="government_entity"
                                value="{{ old('government_entity') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('government_entity') border-red-500 @enderror"
                                placeholder="اسم الجهة الحكومية">
                            @error('government_entity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="consulting_office" class="block text-sm font-medium text-gray-700 mb-2">مكتب
                                الاستشاري</label>
                            <input type="text" id="consulting_office" name="consulting_office"
                                value="{{ old('consulting_office') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('consulting_office') border-red-500 @enderror"
                                placeholder="اسم المكتب الاستشاري">
                            @error('consulting_office')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_scope" class="block text-sm font-medium text-gray-700 mb-2">نطاق عمل
                                المشروع</label>
                            <input type="text" id="project_scope" name="project_scope"
                                value="{{ old('project_scope') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('project_scope') border-red-500 @enderror"
                                placeholder="نطاق أو مجال العمل">
                            @error('project_scope')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف
                                المشروع</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('description') border-red-500 @enderror"
                                placeholder="وصف تفصيلي للمشروع، أهدافه، ومتطلباته">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Timeline and Management -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="ri-calendar-line text-green-600"></i>
                        </div>
                        الجدولة الزمنية والإدارة
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ بداية المشروع
                                *</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror"
                                required>
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">التاريخ المتوقع
                                لنهاية المشروع</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_manager_id" class="block text-sm font-medium text-gray-700 mb-2">اسم مدير
                                المشروع *</label>
                            <select id="project_manager_id" name="project_manager_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('project_manager_id') border-red-500 @enderror"
                                required>
                                <option value="">اختر مدير المشروع</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('project_manager_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_manager_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="ri-information-line text-blue-600 mt-0.5 ml-2"></i>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-medium">مدير المشروع:</p>
                                        <p class="mt-1">سيكون مسؤولاً عن متابعة تقدم المشروع وإدارة الفريق</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Files -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="ri-folder-line text-purple-600"></i>
                        </div>
                        ملفات المشروع
                    </h3>
                    <div class="space-y-4" id="files-container">
                        <div
                            class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-xl bg-gray-50">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اسم الملف</label>
                                <input type="text" name="files[0][name]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="اسم الملف">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رفع الملف</label>
                                <input type="file" name="files[0][file]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">وصف الملف</label>
                                <input type="text" name="files[0][description]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="وصف مختصر للملف">
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addFileField()"
                        class="mt-4 bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors flex items-center gap-2">
                        <i class="ri-add-line"></i>
                        إضافة ملف آخر
                    </button>
                </div>

                <!-- Project Images -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center">
                            <i class="ri-image-line text-pink-600"></i>
                        </div>
                        صور المشروع
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رفع صور المشروع</label>
                            <input type="file" name="images[]" id="project_images" multiple accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="mt-2 text-sm text-gray-600">يمكنك اختيار عدة صور في نفس الوقت (PNG, JPG, JPEG)</p>
                        </div>
                        <div id="image-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                    </div>
                </div>

                <!-- Delivery Requests -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="ri-file-list-3-line text-orange-600"></i>
                        </div>
                        طلبات استلام الأعمال
                    </h3>
                    <div class="space-y-4" id="requests-container">
                        <div
                            class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border border-gray-200 rounded-xl bg-blue-50">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الطلب</label>
                                <input type="text" name="requests[0][number]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="رقم الطلب">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">وصف الطلب</label>
                                <input type="text" name="requests[0][description]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="وصف الطلب">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رفع الملف</label>
                                <input type="file" name="requests[0][file]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx">
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="removeRequestField(this)"
                                    class="w-full bg-red-500 text-white px-3 py-3 rounded-xl hover:bg-red-600 transition-colors">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addRequestField()"
                        class="mt-4 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
                        <i class="ri-add-line"></i>
                        إضافة طلب آخر
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('projects.index') }}"
                        class="bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-400 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <i class="ri-save-line"></i>
                        إنشاء المشروع
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let fileCounter = 1;
        let requestCounter = 1;

        // Add file field
        function addFileField() {
            const container = document.getElementById('files-container');
            const fileItem = document.createElement('div');
            fileItem.className = 'grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-xl bg-gray-50';
            fileItem.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">اسم الملف</label>
                <input type="text" 
                       name="files[${fileCounter}][name]" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="اسم الملف">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">رفع الملف</label>
                <input type="file" 
                       name="files[${fileCounter}][file]" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">وصف الملف</label>
                <input type="text" 
                       name="files[${fileCounter}][description]" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="وصف مختصر للملف">
                <button type="button" 
                        onclick="removeFileField(this)" 
                        class="mt-2 w-full bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors">
                    <i class="ri-delete-bin-line"></i> حذف الملف
                </button>
            </div>
        `;
            container.appendChild(fileItem);
            fileCounter++;
        }

        function removeFileField(button) {
            button.closest('.grid').remove();
        }

        // Add request field
        function addRequestField() {
            const container = document.getElementById('requests-container');
            const requestItem = document.createElement('div');
            requestItem.className =
            'grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border border-gray-200 rounded-xl bg-blue-50';
            requestItem.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الطلب</label>
                <input type="text" 
                       name="requests[${requestCounter}][number]" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="رقم الطلب">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">وصف الطلب</label>
                <input type="text" 
                       name="requests[${requestCounter}][description]" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="وصف الطلب">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">رفع الملف</label>
                <input type="file" 
                       name="requests[${requestCounter}][file]" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       accept=".pdf,.doc,.docx,.xls,.xlsx">
            </div>
            <div class="flex items-end">
                <button type="button" 
                        onclick="removeRequestField(this)" 
                        class="w-full bg-red-500 text-white px-3 py-3 rounded-xl hover:bg-red-600 transition-colors">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        `;
            container.appendChild(requestItem);
            requestCounter++;
        }

        function removeRequestField(button) {
            button.closest('.grid').remove();
        }

        // Image preview
        document.getElementById('project_images').addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            Array.from(e.target.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative rounded-lg overflow-hidden border border-gray-200';
                        div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover">
                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2">
                            <p class="text-xs truncate">${file.name}</p>
                        </div>
                    `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Set minimum end date to start date
        document.getElementById('start_date').addEventListener('change', function() {
            const endDateInput = document.getElementById('end_date');
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = '';
            }
        });

        // Set default start date to today
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            if (!startDateInput.value) {
                startDateInput.value = new Date().toISOString().split('T')[0];
            }
        });
    </script>
@endsection
