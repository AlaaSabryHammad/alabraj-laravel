@extends('layouts.app')

@section('title', 'تعديل نوع قطعة غيار - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('spare-part-types.index') }}"
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تعديل نوع قطعة الغيار</h1>
                <p class="text-gray-600">تحديث بيانات: <strong>{{ $sparePartType->name }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i class="ri-error-warning-line text-red-600 text-xl mt-1"></i>
                <div>
                    <h3 class="text-red-800 font-semibold mb-2">يوجد أخطاء في النموذج:</h3>
                    <ul class="space-y-1 text-red-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <i class="ri-close-line"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('spare-part-types.update', $sparePartType) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">المعلومات الأساسية</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اسم نوع القطعة -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم نوع القطعة <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $sparePartType->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                               placeholder="مثال: بطاريات، مرشحات، إلخ"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- الفئة -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            الفئة <span class="text-red-500">*</span>
                        </label>
                        <select id="category"
                                name="category"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('category') border-red-500 @enderror"
                                required>
                            <option value="">اختر الفئة</option>
                            <option value="engine" {{ old('category', $sparePartType->category) == 'engine' ? 'selected' : '' }}>محرك</option>
                            <option value="transmission" {{ old('category', $sparePartType->category) == 'transmission' ? 'selected' : '' }}>ناقل الحركة</option>
                            <option value="brakes" {{ old('category', $sparePartType->category) == 'brakes' ? 'selected' : '' }}>المكابح</option>
                            <option value="electrical" {{ old('category', $sparePartType->category) == 'electrical' ? 'selected' : '' }}>كهربائي</option>
                            <option value="hydraulic" {{ old('category', $sparePartType->category) == 'hydraulic' ? 'selected' : '' }}>هيدروليك</option>
                            <option value="cooling" {{ old('category', $sparePartType->category) == 'cooling' ? 'selected' : '' }}>تبريد</option>
                            <option value="filters" {{ old('category', $sparePartType->category) == 'filters' ? 'selected' : '' }}>فلاتر</option>
                            <option value="tires" {{ old('category', $sparePartType->category) == 'tires' ? 'selected' : '' }}>إطارات</option>
                            <option value="body" {{ old('category', $sparePartType->category) == 'body' ? 'selected' : '' }}>هيكل</option>
                            <option value="other" {{ old('category', $sparePartType->category) == 'other' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror"
                          placeholder="أدخل وصفاً تفصيلياً للنوع (اختياري)">{{ old('description', $sparePartType->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i class="ri-error-warning-line"></i>
                        {{ $message }}
                    </p>
                @enderror
                <p class="text-gray-500 text-sm mt-2">
                    <i class="ri-information-line ml-1"></i>
                    الوصف يساعدك على تذكر مزيد من التفاصيل عن هذا النوع
                </p>
            </div>

            <!-- Status -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ri-toggle-line text-blue-600"></i>
                    الحالة
                </h3>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $sparePartType->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-gray-700 font-medium">نشط</span>
                    </label>
                    <p class="text-gray-500 text-sm">
                        <i class="ri-information-line ml-1"></i>
                        تفعيل هذا النوع يجعله متاحاً للاستخدام
                    </p>
                </div>
            </div>

            <!-- Related Parts Count -->
            @if ($sparePartType->spareParts->count() > 0)
            <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-900 mb-2 flex items-center gap-2">
                    <i class="ri-information-line text-blue-600"></i>
                    معلومات إضافية
                </h3>
                <p class="text-blue-700">
                    يوجد <strong>{{ $sparePartType->spareParts->count() }}</strong> قطع غيار مرتبطة بهذا النوع.
                    تعديل بيانات النوع سيؤثر على جميع القطع المرتبطة.
                </p>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('spare-part-types.index') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center gap-2">
                    <i class="ri-save-line"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
