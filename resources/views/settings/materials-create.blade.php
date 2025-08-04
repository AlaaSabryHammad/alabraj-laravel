@extends('layouts.app')

@section('title', 'إضافة مادة جديدة - إدارة المواد')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة مادة جديدة</h1>
                <p class="text-gray-600">إضافة مادة جديدة إلى المخزون</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('settings.materials') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200 flex items-center">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة إلى القائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('settings.materials.store') }}" method="POST" class="space-y-8 p-6">
            @csrf

            <!-- Basic Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">المعلومات الأساسية</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المادة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="أدخل اسم المادة">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            الفئة <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                            <option value="">اختر الفئة</option>
                            <option value="cement" {{ old('category') == 'cement' ? 'selected' : '' }}>أسمنت</option>
                            <option value="steel" {{ old('category') == 'steel' ? 'selected' : '' }}>حديد</option>
                            <option value="aggregate" {{ old('category') == 'aggregate' ? 'selected' : '' }}>خرسانة</option>
                            <option value="tools" {{ old('category') == 'tools' ? 'selected' : '' }}>أدوات</option>
                            <option value="electrical" {{ old('category') == 'electrical' ? 'selected' : '' }}>كهربائية</option>
                            <option value="plumbing" {{ old('category') == 'plumbing' ? 'selected' : '' }}>سباكة</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                            الوحدة
                        </label>
                        <input type="text" id="unit" name="unit" value="{{ old('unit') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('unit') border-red-500 @enderror"
                               placeholder="مثل: طن، كيس، متر مكعب">
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit_of_measure" class="block text-sm font-medium text-gray-700 mb-2">
                            وحدة القياس <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="unit_of_measure" name="unit_of_measure" value="{{ old('unit_of_measure') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('unit_of_measure') border-red-500 @enderror"
                               placeholder="أدخل وحدة القياس">
                        @error('unit_of_measure')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            الحالة <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="">اختر الحالة</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>نفذ المخزون</option>
                            <option value="discontinued" {{ old('status') == 'discontinued' ? 'selected' : '' }}>متوقف</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            الوصف
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="أدخل وصف المادة">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Stock Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">معلومات المخزون</h3>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="current_stock" class="block text-sm font-medium text-gray-700 mb-2">
                            المخزون الحالي <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="current_stock" name="current_stock" value="{{ old('current_stock', 0) }}" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_stock') border-red-500 @enderror">
                        @error('current_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="minimum_stock" class="block text-sm font-medium text-gray-700 mb-2">
                            الحد الأدنى للمخزون <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('minimum_stock') border-red-500 @enderror">
                        @error('minimum_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="maximum_stock" class="block text-sm font-medium text-gray-700 mb-2">
                            الحد الأقصى للمخزون
                        </label>
                        <input type="number" id="maximum_stock" name="maximum_stock" value="{{ old('maximum_stock') }}" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('maximum_stock') border-red-500 @enderror">
                        @error('maximum_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="storage_location" class="block text-sm font-medium text-gray-700 mb-2">
                            موقع التخزين
                        </label>
                        <input type="text" id="storage_location" name="storage_location" value="{{ old('storage_location') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('storage_location') border-red-500 @enderror"
                               placeholder="أدخل موقع التخزين">
                        @error('storage_location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Supplier Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">معلومات المورد</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="supplier_name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المورد
                        </label>
                        <input type="text" id="supplier_name" name="supplier_name" value="{{ old('supplier_name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('supplier_name') border-red-500 @enderror"
                               placeholder="أدخل اسم المورد">
                        @error('supplier_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="supplier_contact" class="block text-sm font-medium text-gray-700 mb-2">
                            جهة الاتصال بالمورد
                        </label>
                        <input type="text" id="supplier_contact" name="supplier_contact" value="{{ old('supplier_contact') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('supplier_contact') border-red-500 @enderror"
                               placeholder="رقم الهاتف أو البريد الإلكتروني">
                        @error('supplier_contact')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">معلومات المنتج</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">
                            العلامة التجارية
                        </label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('brand') border-red-500 @enderror"
                               placeholder="أدخل العلامة التجارية">
                        @error('brand')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                            الموديل
                        </label>
                        <input type="text" id="model" name="model" value="{{ old('model') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('model') border-red-500 @enderror"
                               placeholder="أدخل الموديل">
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">معلومات السعر</h3>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">
                            سعر الوحدة (ريال)
                        </label>
                        <input type="number" id="unit_price" name="unit_price" value="{{ old('unit_price') }}" min="0" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('unit_price') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('unit_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_purchase_price" class="block text-sm font-medium text-gray-700 mb-2">
                            آخر سعر شراء (ريال)
                        </label>
                        <input type="number" id="last_purchase_price" name="last_purchase_price" value="{{ old('last_purchase_price') }}" min="0" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('last_purchase_price') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('last_purchase_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ آخر شراء
                        </label>
                        <input type="date" id="last_purchase_date" name="last_purchase_date" value="{{ old('last_purchase_date') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('last_purchase_date') border-red-500 @enderror">
                        @error('last_purchase_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">ملاحظات</h3>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        ملاحظات إضافية
                    </label>
                    <textarea id="notes" name="notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                              placeholder="أدخل أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('settings.materials') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                    <i class="ri-save-line ml-2"></i>
                    حفظ المادة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
