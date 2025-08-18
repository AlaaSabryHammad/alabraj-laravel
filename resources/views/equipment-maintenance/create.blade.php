@extends('layouts.app')

@section('title', 'تسجيل صيانة جديدة')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('equipment-maintenance.index') }}"
                    class="text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">تسجيل صيانة جديدة</h1>
                    <p class="text-gray-600 mt-1">إضافة طلب صيانة جديد للمعدات</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border">
            <form method="POST" action="{{ route('equipment-maintenance.store') }}" enctype="multipart/form-data"
                class="p-6">
                @csrf

                <!-- Display All Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="ri-error-warning-line text-red-600 text-xl ml-2"></i>
                            <h4 class="text-red-800 font-semibold">يوجد أخطاء في النموذج:</h4>
                        </div>
                        <ul class="list-disc list-inside text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- الجزء الأول: المعلومات الأساسية -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">المعلومات الأساسية</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- اختيار المعدة -->
                        <div>
                            <label for="equipment_id" class="block text-sm font-medium text-gray-700 mb-2">
                                المعدة <span class="text-red-500">*</span>
                            </label>
                            <select id="equipment_id" name="equipment_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('equipment_id') border-red-500 @enderror"
                                required>
                                <option value="">اختر المعدة</option>
                                @foreach ($equipment as $eq)
                                    <option value="{{ $eq->id }}"
                                        {{ old('equipment_id') == $eq->id ? 'selected' : '' }}>
                                        {{ $eq->name }} - {{ $eq->model }} - {{ $eq->serial_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('equipment_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- تاريخ دخول الصيانة -->
                        <div>
                            <label for="maintenance_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ دخول الصيانة <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="maintenance_date" name="maintenance_date"
                                value="{{ old('maintenance_date', date('Y-m-d')) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('maintenance_date') border-red-500 @enderror"
                                required>
                            @error('maintenance_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- نوع الصيانة -->
                        <div>
                            <label for="maintenance_type" class="block text-sm font-medium text-gray-700 mb-2">
                                نوع الصيانة <span class="text-red-500">*</span>
                            </label>
                            <select id="maintenance_type" name="maintenance_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('maintenance_type') border-red-500 @enderror"
                                required onchange="toggleExternalFields()">
                                <option value="">اختر نوع الصيانة</option>
                                <option value="internal" {{ old('maintenance_type') == 'internal' ? 'selected' : '' }}>
                                    صيانة داخلية</option>
                                <option value="external" {{ old('maintenance_type') == 'external' ? 'selected' : '' }}>
                                    صيانة خارجية</option>
                            </select>
                            @error('maintenance_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- تاريخ الإنجاز المتوقع -->
                        <div>
                            <label for="expected_completion_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الإنجاز المتوقع
                            </label>
                            <input type="date" id="expected_completion_date" name="expected_completion_date"
                                value="{{ old('expected_completion_date') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('expected_completion_date') border-red-500 @enderror">
                            @error('expected_completion_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الجزء الثاني: تفاصيل الصيانة الخارجية -->
                <div id="external_fields" class="border-b border-gray-200 pb-6 mb-6" style="display: none;">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ri-building-line text-purple-600 ml-2"></i>
                        تفاصيل الصيانة الخارجية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- تكلفة الصيانة -->
                        <div>
                            <label for="external_cost" class="block text-sm font-medium text-gray-700 mb-2">
                                تكلفة الصيانة (ر.س) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="external_cost" name="external_cost"
                                value="{{ old('external_cost') }}" step="0.01" min="0" placeholder="0.00"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('external_cost') border-red-500 @enderror">
                            @error('external_cost')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- اسم مركز الصيانة -->
                        <div>
                            <label for="maintenance_center" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم مركز الصيانة <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="maintenance_center" name="maintenance_center"
                                value="{{ old('maintenance_center') }}" placeholder="مثال: مركز الرياض للصيانة"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('maintenance_center') border-red-500 @enderror">
                            @error('maintenance_center')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- رقم الفاتورة -->
                        <div>
                            <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الفاتورة <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="invoice_number" name="invoice_number"
                                value="{{ old('invoice_number') }}" placeholder="مثال: INV-2025-001"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('invoice_number') border-red-500 @enderror">
                            @error('invoice_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- صورة الفاتورة -->
                        <div>
                            <label for="invoice_image" class="block text-sm font-medium text-gray-700 mb-2">
                                صورة الفاتورة
                            </label>
                            <input type="file" id="invoice_image" name="invoice_image"
                                accept="image/*,application/pdf"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('invoice_image') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">يمكن رفع ملفات الصور (JPG, PNG) أو PDF - الحد الأقصى: 5MB
                            </p>
                            @error('invoice_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الجزء الثالث: الوصف والملاحظات -->
                <div class="pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ri-file-text-line text-gray-600 ml-2"></i>
                        تفاصيل إضافية
                    </h3>

                    <div class="space-y-6">
                        <!-- وصف الأعطال أو الصيانة -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                وصف الأعطال أو الصيانة المطلوبة
                            </label>
                            <textarea id="description" name="description" rows="3"
                                placeholder="اكتب وصف مفصل للأعطال أو نوع الصيانة المطلوبة..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الملاحظات -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                الملاحظات
                            </label>
                            <textarea id="notes" name="notes" rows="3" placeholder="أي ملاحظات إضافية..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- أزرار العمل -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('equipment-maintenance.index') }}"
                        class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors">
                        حفظ طلب الصيانة
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleExternalFields() {
            const maintenanceType = document.getElementById('maintenance_type').value;
            const externalFields = document.getElementById('external_fields');

            if (maintenanceType === 'external') {
                externalFields.style.display = 'block';
                // جعل الحقول إجبارية
                document.getElementById('external_cost').setAttribute('required', 'required');
                document.getElementById('maintenance_center').setAttribute('required', 'required');
                document.getElementById('invoice_number').setAttribute('required', 'required');
            } else {
                externalFields.style.display = 'none';
                // إزالة الإجبارية
                document.getElementById('external_cost').removeAttribute('required');
                document.getElementById('maintenance_center').removeAttribute('required');
                document.getElementById('invoice_number').removeAttribute('required');
            }
        }

        // إظهار الحقول الخارجية إذا كانت محددة مسبقاً (في حالة وجود أخطاء في النموذج)
        document.addEventListener('DOMContentLoaded', function() {
            toggleExternalFields();
        });
    </script>
@endsection
