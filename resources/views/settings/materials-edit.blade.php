@extends('layouts.app')

@section('title', 'تحرير المادة - إدارة المواد')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تحرير المادة</h1>
                <p class="text-gray-600">تحرير معلومات {{ $material->name }}</p>
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
        <form action="{{ route('settings.materials.update', $material) }}" method="POST" class="space-y-8 p-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">معلومات المادة</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المادة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $material->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="أدخل اسم المادة" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit_of_measure" class="block text-sm font-medium text-gray-700 mb-2">
                            وحدة القياس <span class="text-red-500">*</span>
                        </label>
                        <select id="unit_of_measure" name="unit_of_measure"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('unit_of_measure') border-red-500 @enderror" required>
                            <option value="">اختر وحدة القياس</option>
                            <option value="طن" {{ old('unit_of_measure', $material->unit_of_measure) == 'طن' ? 'selected' : '' }}>طن</option>
                            <option value="م3" {{ old('unit_of_measure', $material->unit_of_measure) == 'م3' ? 'selected' : '' }}>م3 (متر مكعب)</option>
                            <option value="م2" {{ old('unit_of_measure', $material->unit_of_measure) == 'م2' ? 'selected' : '' }}>م2 (متر مربع)</option>
                            <option value="لتر" {{ old('unit_of_measure', $material->unit_of_measure) == 'لتر' ? 'selected' : '' }}>لتر</option>
                        </select>
                        @error('unit_of_measure')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            الفئة <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror" required>
                            <option value="">اختر الفئة</option>
                            <option value="cement" {{ old('category', $material->category) == 'cement' ? 'selected' : '' }}>أسمنت</option>
                            <option value="steel" {{ old('category', $material->category) == 'steel' ? 'selected' : '' }}>حديد</option>
                            <option value="aggregate" {{ old('category', $material->category) == 'aggregate' ? 'selected' : '' }}>خرسانة</option>
                            <option value="tools" {{ old('category', $material->category) == 'tools' ? 'selected' : '' }}>أدوات</option>
                            <option value="electrical" {{ old('category', $material->category) == 'electrical' ? 'selected' : '' }}>كهربائية</option>
                            <option value="plumbing" {{ old('category', $material->category) == 'plumbing' ? 'selected' : '' }}>سباكة</option>
                            <option value="other" {{ old('category', $material->category) == 'other' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            الحالة <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                            <option value="">اختر الحالة</option>
                            <option value="active" {{ old('status', $material->status) == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status', $material->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="out_of_stock" {{ old('status', $material->status) == 'out_of_stock' ? 'selected' : '' }}>نفذ المخزون</option>
                            <option value="discontinued" {{ old('status', $material->status) == 'discontinued' ? 'selected' : '' }}>متوقف</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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
                    تحديث المادة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
