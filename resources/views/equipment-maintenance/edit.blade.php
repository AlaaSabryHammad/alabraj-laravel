@extends('layouts.app')

@section('title', 'تعديل الصيانة')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('equipment-maintenance.show', $equipmentMaintenance) }}"
                class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تعديل صيانة المعدة</h1>
                <p class="text-gray-600 mt-1">تعديل بيانات صيانة {{ $equipmentMaintenance->equipment->name }}</p>
            </div>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="ri-error-warning-line text-red-600 text-xl ml-2 mt-0.5"></i>
                    <div class="flex-1">
                        <h3 class="font-medium text-red-800 mb-2">يرجى تصحيح الأخطاء التالية:</h3>
                        <ul class="list-disc list-inside text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('equipment-maintenance.update', $equipmentMaintenance) }}"
            enctype="multipart/form-data" class="max-w-4xl">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- النموذج الرئيسي -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- معلومات الصيانة الأساسية -->
                    <div class="bg-white rounded-xl shadow-sm border">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="ri-settings-2-line text-blue-600 ml-2"></i>
                                معلومات الصيانة الأساسية
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- اختيار المعدة -->
                                <div class="col-span-full">
                                    <label for="equipment_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="ri-tools-line text-orange-600 ml-1"></i>
                                        المعدة
                                    </label>
                                    <select id="equipment_id" name="equipment_id" required
                                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">اختر المعدة...</option>
                                        @foreach ($equipments as $equipment)
                                            <option value="{{ $equipment->id }}"
                                                {{ old('equipment_id', $equipmentMaintenance->equipment_id) == $equipment->id ? 'selected' : '' }}>
                                                {{ $equipment->name }}
                                                @if ($equipment->model)
                                                    - {{ $equipment->model }}
                                                @endif
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
                                        <i class="ri-calendar-line text-green-600 ml-1"></i>
                                        تاريخ دخول الصيانة
                                    </label>
                                    <input type="date" id="maintenance_date" name="maintenance_date" required
                                        value="{{ old('maintenance_date', $equipmentMaintenance->maintenance_date->format('Y-m-d')) }}"
                                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('maintenance_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- نوع الصيانة -->
                                <div>
                                    <label for="maintenance_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="ri-exchange-line text-purple-600 ml-1"></i>
                                        نوع الصيانة
                                    </label>
                                    <select id="maintenance_type" name="maintenance_type" required
                                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="toggleExternalFields()">
                                        <option value="">اختر نوع الصيانة...</option>
                                        <option value="internal"
                                            {{ old('maintenance_type', $equipmentMaintenance->maintenance_type) === 'internal' ? 'selected' : '' }}>
                                            صيانة داخلية
                                        </option>
                                        <option value="external"
                                            {{ old('maintenance_type', $equipmentMaintenance->maintenance_type) === 'external' ? 'selected' : '' }}>
                                            صيانة خارجية
                                        </option>
                                    </select>
                                    @error('maintenance_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تاريخ الإنجاز المتوقع -->
                                <div>
                                    <label for="expected_completion_date"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="ri-time-line text-yellow-600 ml-1"></i>
                                        تاريخ الإنجاز المتوقع (اختياري)
                                    </label>
                                    <input type="date" id="expected_completion_date" name="expected_completion_date"
                                        value="{{ old('expected_completion_date', $equipmentMaintenance->expected_completion_date?->format('Y-m-d')) }}"
                                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('expected_completion_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- الحالة -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="ri-flag-line text-blue-600 ml-1"></i>
                                        الحالة
                                    </label>
                                    <select id="status" name="status" required
                                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="in_progress"
                                            {{ old('status', $equipmentMaintenance->status) === 'in_progress' ? 'selected' : '' }}>
                                            قيد الصيانة
                                        </option>
                                        <option value="completed"
                                            {{ old('status', $equipmentMaintenance->status) === 'completed' ? 'selected' : '' }}>
                                            مكتملة
                                        </option>
                                        <option value="cancelled"
                                            {{ old('status', $equipmentMaintenance->status) === 'cancelled' ? 'selected' : '' }}>
                                            ملغية
                                        </option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل الصيانة الخارجية -->
                    <div id="external_fields"
                        class="bg-white rounded-xl shadow-sm border {{ $equipmentMaintenance->maintenance_type !== 'external' ? 'hidden' : '' }}">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="ri-building-line text-purple-600 ml-2"></i>
                                تفاصيل الصيانة الخارجية
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- مركز الصيانة -->
                                <div>
                                    <label for="maintenance_center" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="ri-building-2-line text-gray-600 ml-1"></i>
                                        مركز الصيانة
                                    </label>
                                    <input type="text" id="maintenance_center" name="maintenance_center"
                                        value="{{ old('maintenance_center', $equipmentMaintenance->maintenance_center) }}"
                                        placeholder="اسم مركز الصيانة"
                                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('maintenance_center')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تكلفة الصيانة -->
                                <div>
                                    <label for="external_cost" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="ri-money-dollar-circle-line text-green-600 ml-1"></i>
                                        تكلفة الصيانة (ر.س)
                                    </label>
                                    <input type="number" step="0.01" min="0" id="external_cost"
                                        name="external_cost"
                                        value="{{ old('external_cost', $equipmentMaintenance->external_cost) }}"
                                        placeholder="0.00"
                                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('external_cost')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- رقم الفاتورة -->
                                <div>
                                    <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="ri-file-list-line text-blue-600 ml-1"></i>
                                        رقم الفاتورة
                                    </label>
                                    <input type="text" id="invoice_number" name="invoice_number"
                                        value="{{ old('invoice_number', $equipmentMaintenance->invoice_number) }}"
                                        placeholder="رقم الفاتورة"
                                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('invoice_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- صورة الفاتورة -->
                                <div>
                                    <label for="invoice_image" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="ri-image-line text-indigo-600 ml-1"></i>
                                        صورة الفاتورة
                                    </label>
                                    <input type="file" id="invoice_image" name="invoice_image" accept="image/*"
                                        class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @if ($equipmentMaintenance->invoice_image)
                                        <div class="mt-2 text-sm text-gray-600 flex items-center gap-2">
                                            <i class="ri-file-image-line text-green-600"></i>
                                            <span>الفاتورة الحالية:</span>
                                            <a href="{{ Storage::url($equipmentMaintenance->invoice_image) }}"
                                                target="_blank" class="text-blue-600 hover:text-blue-800">عرض الفاتورة</a>
                                        </div>
                                    @endif
                                    @error('invoice_image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الوصف والملاحظات -->
                    <div class="bg-white rounded-xl shadow-sm border">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="ri-file-text-line text-gray-600 ml-2"></i>
                                تفاصيل إضافية
                            </h3>

                            <!-- وصف الأعطال -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="ri-file-text-2-line text-orange-600 ml-1"></i>
                                    وصف الأعطال أو الصيانة المطلوبة
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    placeholder="اكتب وصفاً مفصلاً للأعطال أو نوع الصيانة المطلوبة..."
                                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $equipmentMaintenance->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- الملاحظات -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="ri-sticky-note-line text-yellow-600 ml-1"></i>
                                    الملاحظات (اختياري)
                                </label>
                                <textarea id="notes" name="notes" rows="3" placeholder="ملاحظات إضافية أو تعليقات خاصة..."
                                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $equipmentMaintenance->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الشريط الجانبي -->
                <div class="space-y-6">
                    <!-- معاينة المعدة -->
                    <div class="bg-white rounded-xl shadow-sm border">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات المعدة</h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="text-gray-600">الاسم:</span>
                                    <p class="font-medium text-gray-900">{{ $equipmentMaintenance->equipment->name }}</p>
                                </div>
                                @if ($equipmentMaintenance->equipment->model)
                                    <div>
                                        <span class="text-gray-600">الموديل:</span>
                                        <p class="text-gray-900">{{ $equipmentMaintenance->equipment->model }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- الإجراءات -->
                    <div class="bg-white rounded-xl shadow-sm border">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">الإجراءات</h3>
                            <div class="space-y-3">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <i class="ri-save-line"></i>
                                    حفظ التغييرات
                                </button>

                                <a href="{{ route('equipment-maintenance.show', $equipmentMaintenance) }}"
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <i class="ri-close-line"></i>
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleExternalFields() {
            const maintenanceType = document.getElementById('maintenance_type').value;
            const externalFields = document.getElementById('external_fields');

            if (maintenanceType === 'external') {
                externalFields.classList.remove('hidden');
            } else {
                externalFields.classList.add('hidden');
                // Clear external fields when switching to internal
                document.getElementById('maintenance_center').value = '';
                document.getElementById('external_cost').value = '';
                document.getElementById('invoice_number').value = '';
                document.getElementById('invoice_image').value = '';
            }
        }

        // Initialize the form state on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleExternalFields();
        });
    </script>
@endsection
