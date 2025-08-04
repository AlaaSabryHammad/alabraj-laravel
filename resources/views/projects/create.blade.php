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

                <!-- Project Items -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="ri-list-check-2 text-indigo-600"></i>
                        </div>
                        بنود المشروع
                    </h3>

                    <div class="bg-gray-50 rounded-xl p-6">
                        <!-- Tax Rate Setting -->
                        <div class="mb-6">
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">معدل الضريبة
                                (%)</label>
                            <input type="number" id="tax_rate" value="15" min="0" max="100"
                                step="0.1"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                onchange="calculateTotals()">
                            <span class="text-sm text-gray-600 mr-2">القيمة الافتراضية: 15%</span>
                        </div>

                        <!-- Items Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[800px] bg-white rounded-lg shadow-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">اسم البند</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">الكمية</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">الوحدة</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">السعر الإفرادي
                                        </th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">السعر الإجمالي
                                        </th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">مع الضريبة</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="items-table-body">
                                    <!-- Initial empty row -->
                                    <tr class="item-row border-b border-gray-200">
                                        <td class="px-4 py-3">
                                            <input type="text" name="items[0][name]" placeholder="اسم البند"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="items[0][quantity]" placeholder="0"
                                                min="0" step="0.1"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center quantity-input"
                                                onchange="calculateRowTotal(this)">
                                        </td>
                                        <td class="px-4 py-3">
                                            <select name="items[0][unit]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">اختر الوحدة</option>
                                                <option value="متر">متر</option>
                                                <option value="متر مربع">متر مربع</option>
                                                <option value="متر مكعب">متر مكعب</option>
                                                <option value="كيلوجرام">كيلوجرام</option>
                                                <option value="طن">طن</option>
                                                <option value="قطعة">قطعة</option>
                                                <option value="مجموعة">مجموعة</option>
                                                <option value="لتر">لتر</option>
                                                <option value="ساعة">ساعة</option>
                                                <option value="يوم">يوم</option>
                                                <option value="أخرى">أخرى</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="items[0][unit_price]" placeholder="0.00"
                                                min="0" step="0.01"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center unit-price-input"
                                                onchange="calculateRowTotal(this)">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="items[0][total_price]"
                                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center total-price-display"
                                                readonly placeholder="0.00">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="items[0][total_with_tax]"
                                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center total-with-tax-display"
                                                readonly placeholder="0.00">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" onclick="removeItemRow(this)"
                                                class="text-red-600 hover:text-red-800 transition-colors p-1">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Add Item Button -->
                        <div class="mt-4">
                            <button type="button" onclick="addItemRow()"
                                class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors flex items-center gap-2">
                                <i class="ri-add-line"></i>
                                إضافة بند جديد
                            </button>
                        </div>

                        <!-- Totals Summary -->
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column: Summary -->
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">الإجمالي قبل الضريبة:</span>
                                        <span id="subtotal-display" class="font-medium text-gray-900">0.00 ر.س</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">الضريبة (<span
                                                id="tax-rate-display">15</span>%):</span>
                                        <span id="tax-amount-display" class="font-medium text-gray-900">0.00 ر.س</span>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-gray-200 pt-3">
                                        <span class="text-lg font-semibold text-gray-900">الإجمالي النهائي:</span>
                                        <span id="final-total-display" class="text-xl font-bold text-blue-600">0.00
                                            ر.س</span>
                                    </div>
                                </div>

                                <!-- Right Column: Amount in Words -->
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-blue-900 mb-2">المبلغ بالحروف:</h4>
                                    <p id="amount-in-words" class="text-blue-800 text-sm leading-relaxed">صفر ريال سعودي
                                    </p>
                                </div>
                            </div>

                            <!-- Hidden inputs for form submission -->
                            <input type="hidden" id="subtotal-input" name="subtotal" value="0">
                            <input type="hidden" id="tax-amount-input" name="tax_amount" value="0">
                            <input type="hidden" id="final-total-input" name="final_total" value="0">
                            <input type="hidden" id="tax-rate-input" name="tax_rate" value="15">
                        </div>
                    </div>
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

        // Project Items Management
        let itemCounter = 1;

        function addItemRow() {
            const tbody = document.getElementById('items-table-body');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row border-b border-gray-200';
            newRow.innerHTML = `
            <td class="px-4 py-3">
                <input type="text" 
                       name="items[${itemCounter}][name]" 
                       placeholder="اسم البند"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </td>
            <td class="px-4 py-3">
                <input type="number" 
                       name="items[${itemCounter}][quantity]" 
                       placeholder="0"
                       min="0"
                       step="0.1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center quantity-input"
                       onchange="calculateRowTotal(this)">
            </td>
            <td class="px-4 py-3">
                <select name="items[${itemCounter}][unit]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">اختر الوحدة</option>
                    <option value="متر">متر</option>
                    <option value="متر مربع">متر مربع</option>
                    <option value="متر مكعب">متر مكعب</option>
                    <option value="كيلوجرام">كيلوجرام</option>
                    <option value="طن">طن</option>
                    <option value="قطعة">قطعة</option>
                    <option value="مجموعة">مجموعة</option>
                    <option value="لتر">لتر</option>
                    <option value="ساعة">ساعة</option>
                    <option value="يوم">يوم</option>
                    <option value="أخرى">أخرى</option>
                </select>
            </td>
            <td class="px-4 py-3">
                <input type="number" 
                       name="items[${itemCounter}][unit_price]" 
                       placeholder="0.00"
                       min="0"
                       step="0.01"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center unit-price-input"
                       onchange="calculateRowTotal(this)">
            </td>
            <td class="px-4 py-3">
                <input type="text" 
                       name="items[${itemCounter}][total_price]" 
                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center total-price-display"
                       readonly
                       placeholder="0.00">
            </td>
            <td class="px-4 py-3">
                <input type="text" 
                       name="items[${itemCounter}][total_with_tax]" 
                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center total-with-tax-display"
                       readonly
                       placeholder="0.00">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" 
                        onclick="removeItemRow(this)"
                        class="text-red-600 hover:text-red-800 transition-colors p-1">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        `;
            tbody.appendChild(newRow);
            itemCounter++;
        }

        function removeItemRow(button) {
            const row = button.closest('tr');
            const tbody = document.getElementById('items-table-body');
            if (tbody.children.length > 1) {
                row.remove();
                calculateTotals();
            }
        }

        function calculateRowTotal(input) {
            const row = input.closest('tr');
            const quantityInput = row.querySelector('.quantity-input');
            const unitPriceInput = row.querySelector('.unit-price-input');
            const totalPriceDisplay = row.querySelector('.total-price-display');
            const totalWithTaxDisplay = row.querySelector('.total-with-tax-display');

            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(unitPriceInput.value) || 0;
            const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;

            const totalPrice = quantity * unitPrice;
            const totalWithTax = totalPrice * (1 + taxRate / 100);

            totalPriceDisplay.value = totalPrice.toFixed(2);
            totalWithTaxDisplay.value = totalWithTax.toFixed(2);

            calculateTotals();
        }

        function calculateTotals() {
            const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
            let subtotal = 0;

            // Sum all row totals
            document.querySelectorAll('.total-price-display').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });

            const taxAmount = subtotal * (taxRate / 100);
            const finalTotal = subtotal + taxAmount;

            // Update displays
            document.getElementById('subtotal-display').textContent = subtotal.toFixed(2) + ' ر.س';
            document.getElementById('tax-amount-display').textContent = taxAmount.toFixed(2) + ' ر.س';
            document.getElementById('final-total-display').textContent = finalTotal.toFixed(2) + ' ر.س';
            document.getElementById('tax-rate-display').textContent = taxRate;

            // Update hidden inputs
            document.getElementById('subtotal-input').value = subtotal.toFixed(2);
            document.getElementById('tax-amount-input').value = taxAmount.toFixed(2);
            document.getElementById('final-total-input').value = finalTotal.toFixed(2);
            document.getElementById('tax-rate-input').value = taxRate;

            // Update amount in words
            document.getElementById('amount-in-words').textContent = numberToArabicWords(finalTotal);

            // Recalculate all row tax amounts
            document.querySelectorAll('.item-row').forEach(row => {
                const totalPrice = parseFloat(row.querySelector('.total-price-display').value) || 0;
                const totalWithTax = totalPrice * (1 + taxRate / 100);
                row.querySelector('.total-with-tax-display').value = totalWithTax.toFixed(2);
            });
        }

        // Number to Arabic words conversion
        function numberToArabicWords(number) {
            if (number === 0) return 'صفر ريال سعودي';

            const ones = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة'];
            const tens = ['', '', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];
            const teens = ['عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر', 'سبعة عشر',
                'ثمانية عشر', 'تسعة عشر'
            ];
            const hundreds = ['', 'مائة', 'مائتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة',
                'تسعمائة'
            ];
            const scales = ['', 'ألف', 'مليون', 'مليار'];

            function convertGroup(num) {
                let result = '';
                const h = Math.floor(num / 100);
                const t = Math.floor((num % 100) / 10);
                const o = num % 10;

                if (h > 0) {
                    result += hundreds[h];
                    if (t > 0 || o > 0) result += ' ';
                }

                if (t === 1) {
                    result += teens[o];
                } else {
                    if (t > 0) {
                        result += tens[t];
                        if (o > 0) result += ' ';
                    }
                    if (o > 0 && t !== 1) {
                        result += ones[o];
                    }
                }

                return result;
            }

            const integerPart = Math.floor(number);
            const decimalPart = Math.round((number - integerPart) * 100);

            let result = '';
            let scaleIndex = 0;
            let tempNum = integerPart;

            if (tempNum === 0) {
                result = 'صفر';
            } else {
                const groups = [];
                while (tempNum > 0) {
                    groups.push(tempNum % 1000);
                    tempNum = Math.floor(tempNum / 1000);
                }

                for (let i = groups.length - 1; i >= 0; i--) {
                    if (groups[i] > 0) {
                        if (result) result += ' ';
                        result += convertGroup(groups[i]);
                        if (i > 0) {
                            result += ' ' + scales[i];
                        }
                    }
                }
            }

            result += ' ريال سعودي';

            if (decimalPart > 0) {
                result += ' و ' + convertGroup(decimalPart) + ' هللة';
            }

            return result;
        }
    </script>
@endsection
