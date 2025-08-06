@extends('layouts.app')

@section('title', 'إضافة قطعة غيار جديدة')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('warehouses.show', $warehouse) }}" class="text-gray-600 hover:text-gray-900">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">إضافة قطعة غيار جديدة</h1>
                    <p class="text-gray-600">مستودع: {{ $warehouse->name }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <i class="ri-error-warning-line text-red-500 text-xl mr-3 mt-0.5"></i>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('warehouses.store-spare-part', $warehouse) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="ri-information-line text-orange-600"></i>
                                المعلومات الأساسية
                            </h3>
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                كود قطعة الغيار <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code') }}" 
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('code') border-red-500 @enderror" 
                                   required
                                   placeholder="مثال: SP001">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم قطعة الغيار <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror" 
                                   required
                                   placeholder="مثال: فلتر زيت المحرك">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">
                                سعر الوحدة (ريال) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="unit_price" 
                                   name="unit_price" 
                                   value="{{ old('unit_price') }}" 
                                   step="0.01" 
                                   min="0"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('unit_price') border-red-500 @enderror" 
                                   required
                                   placeholder="0.00">
                            @error('unit_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit_type" class="block text-sm font-medium text-gray-700 mb-2">
                                وحدة القياس <span class="text-red-500">*</span>
                            </label>
                            <select id="unit_type" 
                                    name="unit_type" 
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('unit_type') border-red-500 @enderror" 
                                    required>
                                <option value="">اختر وحدة القياس</option>
                                <option value="قطعة" {{ old('unit_type') == 'قطعة' ? 'selected' : '' }}>قطعة</option>
                                <option value="متر" {{ old('unit_type') == 'متر' ? 'selected' : '' }}>متر</option>
                                <option value="كيلو" {{ old('unit_type') == 'كيلو' ? 'selected' : '' }}>كيلو</option>
                                <option value="لتر" {{ old('unit_type') == 'لتر' ? 'selected' : '' }}>لتر</option>
                                <option value="طن" {{ old('unit_type') == 'طن' ? 'selected' : '' }}>طن</option>
                                <option value="كرتون" {{ old('unit_type') == 'كرتون' ? 'selected' : '' }}>كرتون</option>
                            </select>
                            @error('unit_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="minimum_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                الحد الأدنى للمخزون <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="minimum_stock" 
                                   name="minimum_stock" 
                                   value="{{ old('minimum_stock', 1) }}" 
                                   min="0"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('minimum_stock') border-red-500 @enderror" 
                                   required
                                   placeholder="1">
                            @error('minimum_stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">
                                العلامة التجارية
                            </label>
                            <input type="text" 
                                   id="brand" 
                                   name="brand" 
                                   value="{{ old('brand') }}" 
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('brand') border-red-500 @enderror"
                                   placeholder="مثال: Caterpillar">
                            @error('brand')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                                الموديل
                            </label>
                            <input type="text" 
                                   id="model" 
                                   name="model" 
                                   value="{{ old('model') }}" 
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('model') border-red-500 @enderror"
                                   placeholder="مثال: 320D">
                            @error('model')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">
                                المورد
                            </label>
                            <input type="text" 
                                   id="supplier" 
                                   name="supplier" 
                                   value="{{ old('supplier') }}" 
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('supplier') border-red-500 @enderror"
                                   placeholder="اسم شركة التوريد">
                            @error('supplier')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location_shelf" class="block text-sm font-medium text-gray-700 mb-2">
                                موقع الرف
                            </label>
                            <input type="text" 
                                   id="location_shelf" 
                                   name="location_shelf" 
                                   value="{{ old('location_shelf') }}" 
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('location_shelf') border-red-500 @enderror"
                                   placeholder="مثال: A1-05">
                            @error('location_shelf')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                الفئة
                            </label>
                            <select id="category" 
                                    name="category" 
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('category') border-red-500 @enderror">
                                <option value="">اختر الفئة</option>
                                <option value="محرك" {{ old('category') == 'محرك' ? 'selected' : '' }}>محرك</option>
                                <option value="كهرباء" {{ old('category') == 'كهرباء' ? 'selected' : '' }}>كهرباء</option>
                                <option value="هيدروليك" {{ old('category') == 'هيدروليك' ? 'selected' : '' }}>هيدروليك</option>
                                <option value="فرامل" {{ old('category') == 'فرامل' ? 'selected' : '' }}>فرامل</option>
                                <option value="تبريد" {{ old('category') == 'تبريد' ? 'selected' : '' }}>تبريد</option>
                                <option value="عادم" {{ old('category') == 'عادم' ? 'selected' : '' }}>عادم</option>
                                <option value="أخرى" {{ old('category') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                المواصفات والوصف
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror"
                                      placeholder="اكتب المواصفات التفصيلية لقطعة الغيار...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Initial Stock -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="ri-stack-line text-orange-600"></i>
                                المخزون الأولي (اختياري)
                            </h3>
                        </div>

                        <div>
                            <label for="initial_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                الكمية الأولية <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="initial_quantity" 
                                   name="initial_quantity" 
                                   value="{{ old('initial_quantity', 0) }}" 
                                   min="0"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('initial_quantity') border-red-500 @enderror"
                                   required
                                   placeholder="0">
                            @error('initial_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم المرجع
                            </label>
                            <input type="text" 
                                   id="reference_number" 
                                   name="reference_number" 
                                   value="{{ old('reference_number') }}" 
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('reference_number') border-red-500 @enderror"
                                   placeholder="رقم المرجع أو الفاتورة">
                            @error('reference_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                ملاحظات الإدخال
                            </label>
                            <input type="text" 
                                   id="notes" 
                                   name="notes" 
                                   value="{{ old('notes') }}" 
                                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('notes') border-red-500 @enderror"
                                   placeholder="أي ملاحظات حول الإدخال الأولي">
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 mt-8 pt-6 border-t">
                        <button type="submit" 
                                class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-save-line"></i>
                            حفظ قطعة الغيار
                        </button>
                        <a href="{{ route('warehouses.show', $warehouse) }}" 
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate code based on name
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    
    nameInput.addEventListener('input', function() {
        if (!codeInput.value) {
            const name = this.value;
            // Simple code generation - you can make this more sophisticated
            const words = name.split(' ');
            let code = 'SP';
            words.forEach(word => {
                if (word.length > 0) {
                    code += word.charAt(0).toUpperCase();
                }
            });
            code += Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            codeInput.value = code;
        }
    });
});
</script>
@endsection
