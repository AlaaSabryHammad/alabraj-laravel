@extends('layouts.app')

@section('title', 'إضافة مستند جديد - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة مستند جديد</h1>
                <p class="text-gray-600">أدخل بيانات المستند الجديد في النموذج أدناه</p>
            </div>
            <a href="{{ route('documents.index') }}"
               class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                <i class="ri-arrow-right-line ml-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Document Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات المستند</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان المستند *</label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('title') border-red-500 @enderror"
                               required>
                        @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع المستند *</label>
                        <select id="type"
                                name="type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('type') border-red-500 @enderror"
                                required>
                            <option value="">اختر نوع المستند</option>
                            <option value="عقد" {{ old('type') == 'عقد' ? 'selected' : '' }}>عقد</option>
                            <option value="فاتورة" {{ old('type') == 'فاتورة' ? 'selected' : '' }}>فاتورة</option>
                            <option value="ترخيص" {{ old('type') == 'ترخيص' ? 'selected' : '' }}>ترخيص</option>
                            <option value="شهادة" {{ old('type') == 'شهادة' ? 'selected' : '' }}>شهادة</option>
                            <option value="تأمين" {{ old('type') == 'تأمين' ? 'selected' : '' }}>تأمين</option>
                            <option value="تقرير" {{ old('type') == 'تقرير' ? 'selected' : '' }}>تقرير</option>
                            <option value="مذكرة" {{ old('type') == 'مذكرة' ? 'selected' : '' }}>مذكرة</option>
                            <option value="مراسلة" {{ old('type') == 'مراسلة' ? 'selected' : '' }}>مراسلة</option>
                            <option value="أخرى" {{ old('type') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف المستند</label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="وصف مختصر للمستند ومحتواه">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">العلامات (اختياري)</label>
                        <input type="text"
                               id="tags"
                               name="tags"
                               value="{{ old('tags') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('tags') border-red-500 @enderror"
                               placeholder="مشروع الرياض، مالية، عاجل (افصل بفاصلة)">
                        @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الانتهاء (اختياري)</label>
                        <input type="date"
                               id="expiry_date"
                               name="expiry_date"
                               value="{{ old('expiry_date') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('expiry_date') border-red-500 @enderror">
                        @error('expiry_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- File Upload -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">رفع الملف</h3>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-purple-400 transition-colors">
                    <input type="file"
                           id="file"
                           name="file"
                           class="hidden"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           onchange="showFileName()">
                    <label for="file" class="cursor-pointer">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-upload-cloud-line text-2xl text-purple-600"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">اختر ملف للرفع</h4>
                        <p class="text-gray-500 mb-2">أو اسحب وأفلت الملف هنا</p>
                        <p class="text-sm text-gray-400">PDF, DOC, DOCX, JPG, PNG (حتى 10 ميجابايت)</p>
                    </label>
                    <div id="file-name" class="mt-4 text-sm text-gray-600 hidden"></div>
                </div>
                @error('file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('documents.index') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center">
                    <i class="ri-save-line ml-2"></i>
                    حفظ المستند
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showFileName() {
    const fileInput = document.getElementById('file');
    const fileNameDiv = document.getElementById('file-name');

    if (fileInput.files && fileInput.files[0]) {
        const fileName = fileInput.files[0].name;
        const fileSize = (fileInput.files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
        fileNameDiv.innerHTML = `
            <div class="flex items-center justify-center space-x-2 space-x-reverse">
                <i class="ri-file-line text-purple-600"></i>
                <span class="font-medium">${fileName}</span>
                <span class="text-gray-400">(${fileSize} MB)</span>
            </div>
        `;
        fileNameDiv.classList.remove('hidden');
    }
}

// Drag and drop functionality
const dropZone = document.querySelector('.border-dashed');
const fileInput = document.getElementById('file');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-purple-400', 'bg-purple-50');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-purple-400', 'bg-purple-50');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-purple-400', 'bg-purple-50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        showFileName();
    }
});
</script>
@endpush
@endsection
