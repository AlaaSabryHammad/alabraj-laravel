@extends('layouts.app')

@section('title', 'استلام قطعة تالفة جديدة')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('damaged-parts-receipts.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="ri-arrow-right-line text-2xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <i class="ri-alert-line text-red-600"></i>
                استلام قطعة تالفة جديدة
            </h1>
            <p class="text-gray-600">إدخال معلومات القطعة التالفة والمشروع</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('damaged-parts-receipts.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- معلومات الاستلام الأساسية -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="ri-info-line text-blue-600 text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">معلومات الاستلام الأساسية</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- تاريخ الاستلام -->
                    <div>
                        <label for="receipt_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الاستلام *</label>
                        <input type="date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('receipt_date') border-red-500 @enderror"
                            id="receipt_date" name="receipt_date"
                            value="{{ old('receipt_date', now()->format('Y-m-d')) }}" required>
                        @error('receipt_date')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- وقت الاستلام -->
                    <div>
                        <label for="receipt_time" class="block text-sm font-medium text-gray-700 mb-2">وقت الاستلام *</label>
                        <input type="time"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('receipt_time') border-red-500 @enderror"
                            id="receipt_time" name="receipt_time"
                            value="{{ old('receipt_time', now()->format('H:i')) }}" required>
                        @error('receipt_time')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- موظف الاستلام -->
                    <div>
                        <label for="received_by" class="block text-sm font-medium text-gray-700 mb-2">موظف الاستلام *</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('received_by') border-red-500 @enderror"
                            id="received_by" name="received_by" required>
                            <option value="">اختر الموظف</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ old('received_by') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('received_by')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- معلومات المشروع والمعدة -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                        <i class="ri-building-line text-orange-600 text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">معلومات المشروع والمعدة</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- المشروع -->
                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">المشروع *</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('project_id') border-red-500 @enderror"
                            id="project_id" name="project_id" required>
                            <option value="">اختر المشروع</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- المعدة -->
                    <div>
                        <label for="equipment_id" class="block text-sm font-medium text-gray-700 mb-2">المعدة (اختياري)</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('equipment_id') border-red-500 @enderror"
                            id="equipment_id" name="equipment_id">
                            <option value="">لا توجد معدة محددة</option>
                            @foreach ($equipment as $equip)
                                <option value="{{ $equip->id }}"
                                    {{ old('equipment_id') == $equip->id ? 'selected' : '' }}>
                                    {{ $equip->name }} - {{ $equip->serial_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_id')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- موظف الإرسال -->
                <div>
                    <label for="sent_by" class="block text-sm font-medium text-gray-700 mb-2">موظف الإرسال من المشروع</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sent_by') border-red-500 @enderror" id="sent_by"
                        name="sent_by">
                        <option value="">غير محدد</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ old('sent_by') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('sent_by')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- معلومات قطعة الغيار -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="ri-settings-line text-purple-600 text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">معلومات قطعة الغيار</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <!-- قطعة الغيار -->
                    <div class="md:col-span-3">
                        <label for="spare_part_id" class="block text-sm font-medium text-gray-700 mb-2">قطعة الغيار *</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('spare_part_id') border-red-500 @enderror"
                            id="spare_part_id" name="spare_part_id" required>
                            <option value="">اختر قطعة الغيار</option>
                            @foreach ($spareParts as $sparePart)
                                <option value="{{ $sparePart->id }}"
                                    {{ old('spare_part_id') == $sparePart->id ? 'selected' : '' }}>
                                    {{ $sparePart->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('spare_part_id')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- الكمية -->
                    <div class="md:col-span-2">
                        <label for="quantity_received" class="block text-sm font-medium text-gray-700 mb-2">الكمية المستلمة *</label>
                        <input type="number"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('quantity_received') border-red-500 @enderror"
                            id="quantity_received" name="quantity_received"
                            value="{{ old('quantity_received', 1) }}" min="1" required>
                        @error('quantity_received')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- حالة التلف -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                        <i class="ri-alert-fill text-red-600 text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">تقييم حالة التلف</h2>
                </div>

                <!-- حالة التلف -->
                <div class="mb-6">
                    <label for="damage_condition" class="block text-sm font-medium text-gray-700 mb-3">حالة التلف *</label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors"
                            id="repairable-label">
                            <input type="radio" name="damage_condition" value="repairable"
                                class="w-4 h-4 text-blue-600 accent-blue-600"
                                {{ old('damage_condition') == 'repairable' ? 'checked' : '' }} required>
                            <span class="mr-3 flex-1">
                                <span class="text-sm font-medium text-gray-900">قابلة للإصلاح</span>
                                <span class="text-xs text-gray-600 block">يمكن إصلاح القطعة</span>
                            </span>
                        </label>

                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="damage_condition" value="non_repairable"
                                class="w-4 h-4 text-blue-600 accent-blue-600"
                                {{ old('damage_condition') == 'non_repairable' ? 'checked' : '' }}>
                            <span class="mr-3 flex-1">
                                <span class="text-sm font-medium text-gray-900">غير قابلة للإصلاح</span>
                                <span class="text-xs text-gray-600 block">لا يمكن إصلاح القطعة</span>
                            </span>
                        </label>

                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="damage_condition" value="replacement_needed"
                                class="w-4 h-4 text-blue-600 accent-blue-600"
                                {{ old('damage_condition') == 'replacement_needed' ? 'checked' : '' }}>
                            <span class="mr-3 flex-1">
                                <span class="text-sm font-medium text-gray-900">تحتاج لاستبدال</span>
                                <span class="text-xs text-gray-600 block">يجب استبدالها</span>
                            </span>
                        </label>

                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="damage_condition" value="for_evaluation"
                                class="w-4 h-4 text-blue-600 accent-blue-600"
                                {{ old('damage_condition') == 'for_evaluation' ? 'checked' : '' }}>
                            <span class="mr-3 flex-1">
                                <span class="text-sm font-medium text-gray-900">تحتاج لتقييم</span>
                                <span class="text-xs text-gray-600 block">بحاجة إلى فحص</span>
                            </span>
                        </label>
                    </div>
                    @error('damage_condition')
                        <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- وصف وسبب التلف -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="damage_description" class="block text-sm font-medium text-gray-700 mb-2">وصف التلف</label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('damage_description') border-red-500 @enderror"
                            id="damage_description" name="damage_description" rows="3"
                            placeholder="اكتب وصف مفصل عن نوع التلف...">{{ old('damage_description') }}</textarea>
                        @error('damage_description')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="damage_cause" class="block text-sm font-medium text-gray-700 mb-2">سبب التلف</label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('damage_cause') border-red-500 @enderror"
                            id="damage_cause" name="damage_cause" rows="3"
                            placeholder="اكتب السبب المحتمل للتلف...">{{ old('damage_cause') }}</textarea>
                        @error('damage_cause')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- ملاحظات الفني -->
                <div>
                    <label for="technician_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات الفني</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('technician_notes') border-red-500 @enderror"
                        id="technician_notes" name="technician_notes" rows="3"
                        placeholder="ملاحظات إضافية من الفني المختص...">{{ old('technician_notes') }}</textarea>
                    @error('technician_notes')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- معلومات التخزين -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <i class="ri-store-2-line text-yellow-600 text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">معلومات التخزين</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- المخزن -->
                    <div>
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-2">المخزن *</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('warehouse_id') border-red-500 @enderror"
                            id="warehouse_id" name="warehouse_id" required>
                            <option value="">اختر المخزن</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}"
                                    {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- موقع التخزين -->
                    <div>
                        <label for="storage_location" class="block text-sm font-medium text-gray-700 mb-2">موقع التخزين</label>
                        <input type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('storage_location') border-red-500 @enderror"
                            id="storage_location" name="storage_location"
                            value="{{ old('storage_location') }}" placeholder="مثل: A-01-25 أو رف رقم 3">
                        @error('storage_location')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- التكاليف المقدرة -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                        <i class="ri-money-dollar-circle-line text-green-600 text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">التكاليف المقدرة</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- تكلفة الإصلاح -->
                    <div>
                        <label for="estimated_repair_cost" class="block text-sm font-medium text-gray-700 mb-2">التكلفة المقدرة للإصلاح (ر.س)</label>
                        <div class="relative">
                            <span class="absolute right-4 top-3.5 text-gray-500 font-medium">ر.س</span>
                            <input type="number"
                                class="w-full pr-12 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('estimated_repair_cost') border-red-500 @enderror"
                                id="estimated_repair_cost" name="estimated_repair_cost"
                                value="{{ old('estimated_repair_cost') }}" min="0" step="0.01"
                                placeholder="0.00">
                        </div>
                        @error('estimated_repair_cost')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- تكلفة الاستبدال -->
                    <div>
                        <label for="replacement_cost" class="block text-sm font-medium text-gray-700 mb-2">تكلفة الاستبدال (ر.س)</label>
                        <div class="relative">
                            <span class="absolute right-4 top-3.5 text-gray-500 font-medium">ر.س</span>
                            <input type="number"
                                class="w-full pr-12 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('replacement_cost') border-red-500 @enderror"
                                id="replacement_cost" name="replacement_cost"
                                value="{{ old('replacement_cost') }}" min="0" step="0.01"
                                placeholder="0.00">
                        </div>
                        @error('replacement_cost')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- المرفقات -->
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i class="ri-attachment-2 text-indigo-600 text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">المرفقات</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- الصور -->
                    <div>
                        <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">صور القطعة التالفة</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer"
                            onclick="document.getElementById('photos').click()">
                            <i class="ri-image-add-line text-4xl text-gray-400 mb-2 block"></i>
                            <p class="text-sm text-gray-600">اختر الصور أو اسحبها هنا</p>
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF - حد أقصى 5MB لكل صورة</p>
                        </div>
                        <input type="file"
                            class="hidden @error('photos.*') border-red-500 @enderror" id="photos"
                            name="photos[]" multiple accept="image/*">
                        <div id="photos-preview" class="mt-3 flex flex-wrap gap-2"></div>
                        @error('photos.*')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- المستندات -->
                    <div>
                        <label for="documents" class="block text-sm font-medium text-gray-700 mb-2">مستندات مرفقة</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer"
                            onclick="document.getElementById('documents').click()">
                            <i class="ri-file-pdf-line text-4xl text-gray-400 mb-2 block"></i>
                            <p class="text-sm text-gray-600">اختر المستندات أو اسحبها هنا</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX - حد أقصى 10MB لكل ملف</p>
                        </div>
                        <input type="file"
                            class="hidden @error('documents.*') border-red-500 @enderror"
                            id="documents" name="documents[]" multiple accept=".pdf,.doc,.docx">
                        <div id="documents-preview" class="mt-3 flex flex-wrap gap-2"></div>
                        @error('documents.*')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- أزرار الإجراءات -->
            <div class="p-8 flex justify-end gap-4">
                <a href="{{ route('damaged-parts-receipts.index') }}"
                    class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-close-line"></i>
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                    <i class="ri-save-line"></i>
                    حفظ الاستلام
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث حقول التكلفة حسب حالة التلف
            const damageConditionInputs = document.querySelectorAll('input[name="damage_condition"]');
            const repairCostField = document.getElementById('estimated_repair_cost');
            const replacementCostField = document.getElementById('replacement_cost');

            function updateCostFields() {
                const condition = document.querySelector('input[name="damage_condition"]:checked')?.value;

                if (condition === 'non_repairable' || condition === 'replacement_needed') {
                    repairCostField.disabled = true;
                    repairCostField.value = '';
                    replacementCostField.disabled = false;
                    replacementCostField.focus();
                } else if (condition === 'repairable') {
                    repairCostField.disabled = false;
                    replacementCostField.disabled = true;
                    replacementCostField.value = '';
                    repairCostField.focus();
                } else {
                    repairCostField.disabled = false;
                    replacementCostField.disabled = false;
                }
            }

            damageConditionInputs.forEach(input => {
                input.addEventListener('change', updateCostFields);
            });

            // معاينة الصور
            document.getElementById('photos').addEventListener('change', function() {
                const preview = document.getElementById('photos-preview');
                preview.innerHTML = '';

                Array.from(this.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const badge = document.createElement('span');
                        badge.className = 'inline-flex items-center gap-2 px-3 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium';
                        badge.innerHTML = `<i class="ri-image-line"></i>${file.name}`;
                        preview.appendChild(badge);
                    }
                });
            });

            // معاينة المستندات
            document.getElementById('documents').addEventListener('change', function() {
                const preview = document.getElementById('documents-preview');
                preview.innerHTML = '';

                Array.from(this.files).forEach(file => {
                    const badge = document.createElement('span');
                    badge.className = 'inline-flex items-center gap-2 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium';
                    badge.innerHTML = `<i class="ri-file-line"></i>${file.name}`;
                    preview.appendChild(badge);
                });
            });
        });
    </script>
@endpush
