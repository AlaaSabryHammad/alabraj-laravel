@extends('layouts.app')

@section('title', 'إضافة بيانات سيارة المحروقات')

@section('content')
    <div class="min-h-screen bg-gray-50" dir="rtl">
        <!-- Header Section -->
        <div class="bg-white shadow-sm border-b">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('fuel-management.index') }}"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 transition-all duration-200">
                        <i class="ri-arrow-right-line text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            <i class="ri-gas-station-fill text-orange-600"></i>
                            إضافة بيانات سيارة المحروقات
                        </h1>
                        <p class="text-gray-600 mt-1 flex items-center gap-2">
                            <i class="ri-price-tag-3-line text-sm"></i>
                            {{ $equipment->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-orange-600 flex items-center justify-center">
                            <i class="ri-file-info-line text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">بيانات السيارة الأساسية</h2>
                    </div>
                </div>

                <form action="{{ route('fuel-truck.store', $equipment) }}" method="POST" class="p-6">
                    @csrf

                    <div class="space-y-6">
                        <!-- Fuel Type -->
                        <div>
                            <label for="fuel_type" class="block text-sm font-medium text-gray-900 mb-2">
                                <i class="ri-droplet-fill text-orange-600 ml-1"></i>
                                نوع المحروقات
                            </label>
                            <select name="fuel_type" id="fuel_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('fuel_type') border-red-500 @enderror"
                                required>
                                <option value="">-- اختر نوع المحروقات --</option>
                                <option value="diesel" @selected(old('fuel_type') === 'diesel')>ديزل</option>
                                <option value="gasoline" @selected(old('fuel_type') === 'gasoline')>بنزين</option>
                                <option value="engine_oil" @selected(old('fuel_type') === 'engine_oil')>زيت ماكينة</option>
                                <option value="hydraulic_oil" @selected(old('fuel_type') === 'hydraulic_oil')>زيت هيدروليك</option>
                                <option value="radiator_water" @selected(old('fuel_type') === 'radiator_water')>ماء ردياتير</option>
                                <option value="brake_oil" @selected(old('fuel_type') === 'brake_oil')>زيت فرامل</option>
                                <option value="other" @selected(old('fuel_type') === 'other')>أخرى</option>
                            </select>
                            @error('fuel_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-900 mb-2">
                                <i class="ri-flow-chart text-orange-600 ml-1"></i>
                                السعة (لتر)
                            </label>
                            <input type="number" name="capacity" id="capacity" step="0.01" min="0.01"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('capacity') border-red-500 @enderror"
                                value="{{ old('capacity') }}" placeholder="مثال: 1000" required>
                            @error('capacity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Quantity -->
                        <div>
                            <label for="current_quantity" class="block text-sm font-medium text-gray-900 mb-2">
                                <i class="ri-remainder-fill text-orange-600 ml-1"></i>
                                الكمية الحالية (لتر)
                            </label>
                            <input type="number" name="current_quantity" id="current_quantity" step="0.01" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('current_quantity') border-red-500 @enderror"
                                value="{{ old('current_quantity') }}" placeholder="مثال: 500" required>
                            @error('current_quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-900 mb-2">
                                <i class="ri-message-3-line text-orange-600 ml-1"></i>
                                ملاحظات (اختياري)
                            </label>
                            <textarea name="notes" id="notes" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                placeholder="أضف أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-3 pt-6 mt-6 border-t border-gray-200">
                        <a href="{{ route('fuel-management.index') }}"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 px-4 py-2 rounded-lg text-center font-medium transition-colors flex items-center justify-center">
                            <i class="ri-close-line ml-1"></i>
                            إلغاء
                        </a>
                        <button type="submit"
                            class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i class="ri-check-line ml-1"></i>
                            حفظ البيانات
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Card -->
            <div class="bg-blue-50 rounded-2xl shadow-sm border border-blue-200 mt-6 p-6">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <i class="ri-information-line text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2">معلومات مهمة</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li><i class="ri-check-line ml-1"></i>تأكد من أن السعة صحيحة</li>
                            <li><i class="ri-check-line ml-1"></i>أدخل الكمية الحالية بدقة</li>
                            <li><i class="ri-check-line ml-1"></i>يمكنك تعديل البيانات لاحقاً إذا لزم الأمر</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
