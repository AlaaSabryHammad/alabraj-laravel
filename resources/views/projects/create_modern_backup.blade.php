@extends('layouts.app')

@section('title', 'إنشاء مشروع جديد')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إنشاء مشروع جديد</h1>
                <p class="text-gray-600">اتبع الخطوات لإدخال بيانات المشروع الجديد</p>
            </div>
            <a href="{{ route('projects.index') }}"
               class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                <i class="ri-arrow-right-line ml-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- Form & Wizard -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <!-- Progress Bar -->
        <div class="max-w-2xl mx-auto mb-8 px-4">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 w-full h-1 bg-gray-200" style="transform: translateY(-50%);"></div>
                <div id="progress-line" class="absolute left-0 top-1/2 h-1 bg-primary-600 transition-all duration-500" style="width: 0%; transform: translateY(-50%);"></div>
                <div id="step-1" class="step active">
                    <div class="step-icon">1</div>
                    <p class="step-text">البيانات الأساسية</p>
                </div>
                <div id="step-2" class="step">
                    <div class="step-icon">2</div>
                    <p class="step-text">الملفات</p>
                </div>
                <div id="step-3" class="step">
                    <div class="step-icon">3</div>
                    <p class="step-text">الصور</p>
                </div>
                <div id="step-4" class="step">
                    <div class="step-icon">4</div>
                    <p class="step-text">طلبات الاستلام</p>
                </div>
            </div>
        </div>

        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="mt-8">
            @csrf

            <div id="form-sections-container">
                <!-- Section 1: Basic Information -->
                <div id="section-1" class="form-section active">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label">اسم المشروع *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input" required>
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="project_number" class="form-label">رقم المشروع *</label>
                            <input type="text" name="project_number" id="project_number" value="{{ old('project_number') }}" class="form-input" required>
                            @error('project_number')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="budget" class="form-label">قيمة المشروع (ر.س) *</label>
                            <input type="number" name="budget" id="budget" value="{{ old('budget') }}" step="0.01" min="0" class="form-input" required>
                            @error('budget')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="project_manager_id" class="form-label">مدير المشروع *</label>
                            <select name="project_manager_id" id="project_manager_id" class="form-select" required>
                                <option value="">اختر مدير المشروع</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('project_manager_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_manager_id')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="start_date" class="form-label">تاريخ بداية المشروع *</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="form-input" required>
                            @error('start_date')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="end_date" class="form-label">التاريخ المتوقع للنهاية</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="form-input">
                            @error('end_date')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="government_entity" class="form-label">الجهة الحكومية</label>
                            <input type="text" name="government_entity" id="government_entity" value="{{ old('government_entity') }}" class="form-input">
                            @error('government_entity')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="consulting_office" class="form-label">مكتب الاستشاري</label>
                            <input type="text" name="consulting_office" id="consulting_office" value="{{ old('consulting_office') }}" class="form-input">
                            @error('consulting_office')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="project_scope" class="form-label">نطاق عمل المشروع</label>
                            <input type="text" name="project_scope" id="project_scope" value="{{ old('project_scope') }}" class="form-input">
                            @error('project_scope')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="description" class="form-label">وصف المشروع</label>
                            <textarea name="description" id="description" rows="4" class="form-textarea">{{ old('description') }}</textarea>
                            @error('description')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Project Files -->
                <div id="section-2" class="form-section">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ملفات المشروع</h3>
                    <div id="files-container" class="space-y-4"></div>
                    <button type="button" onclick="addFileField()" class="btn-secondary mt-4">
                        <i class="ri-add-line"></i> إضافة ملف آخر
                    </button>
                </div>

                <!-- Section 3: Project Images -->
                <div id="section-3" class="form-section">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">صور المشروع</h3>
                    <div>
                        <label for="project_images" class="form-label">رفع صور</label>
                        <input type="file" name="images[]" id="project_images" multiple accept="image/*" class="form-file-input">
                        <p class="mt-2 text-sm text-gray-500">يمكنك اختيار عدة صور (PNG, JPG, JPEG).</p>
                    </div>
                    <div id="image-preview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4"></div>
                </div>

                <!-- Section 4: Delivery Requests -->
                <div id="section-4" class="form-section">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">طلبات الاستلام</h3>
                    <div id="requests-container" class="space-y-4"></div>
                    <button type="button" onclick="addRequestField()" class="btn-secondary mt-4">
                        <i class="ri-add-line"></i> إضافة طلب آخر
                    </button>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center pt-6 mt-8 border-t border-gray-200">
                <button type="button" id="prev-btn" class="btn-secondary" style="display: none;">
                    <i class="ri-arrow-right-line ml-2"></i> السابق
                </button>
                <button type="button" id="next-btn" class="btn-primary">
                    التالي <i class="ri-arrow-left-line mr-2"></i>
                </button>
                <button type="submit" id="submit-btn" class="btn-primary" style="display: none;">
                    <i class="ri-save-line ml-2"></i> حفظ المشروع
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .step { @apply relative flex flex-col items-center w-24; }
    .step-icon {
        @apply w-10 h-10 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center font-bold text-lg transition-all duration-300 z-10;
    }
    .step-text { @apply text-center text-sm mt-2 text-gray-400 transition-all duration-300; }
    .step.active .step-icon { @apply bg-primary-600 text-white; }
    .step.active .step-text { @apply text-primary-600 font-semibold; }
    .step.completed .step-icon { @apply bg-green-500 text-white; }

    .form-section { display: none; animation: fadeIn 0.5s; }
    .form-section.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .form-label { @apply block text-sm font-medium text-gray-700 mb-2; }
    .form-input, .form-select, .form-textarea {
        @apply w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors;
    }
    .form-select { @apply appearance-none bg-white; }
    .form-file-input {
        @apply w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold;
        @apply file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer;
    }
    .form-error { @apply mt-1 text-xs text-red-600; }

    .btn-primary { @apply bg-primary-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-primary-700 transition-all duration-200 flex items-center; }
    .btn-secondary { @apply bg-gray-200 text-gray-800 px-6 py-3 rounded-xl font-medium hover:bg-gray-300 transition-all duration-200 flex items-center; }
    .btn-danger { @apply bg-red-500 text-white px-3 py-2 rounded-lg font-medium hover:bg-red-600 transition-all duration-200 flex items-center; }

    .file-entry, .request-entry { @apply p-4 bg-gray-50 border border-gray-200 rounded-xl; }
</style>
@endpush

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 4;
    let fileCounter = 0;
    let requestCounter = 0;

    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');

    document.addEventListener('DOMContentLoaded', () => {
        addFileField();
        addRequestField();
        updateWizard();

        nextBtn.addEventListener('click', () => {
            if (currentStep < totalSteps) {
                currentStep++;
                updateWizard();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                updateWizard();
            }
        });
        
        const startDateInput = document.getElementById('start_date');
        if (!startDateInput.value) {
            startDateInput.value = new Date().toISOString().split('T')[0];
        }
        
        startDateInput.addEventListener('change', function() {
            const endDateInput = document.getElementById('end_date');
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = '';
            }
        });

        document.getElementById('project_images').addEventListener('change', previewImages);
    });

    function updateWizard() {
        // Sections
        document.querySelectorAll('.form-section').forEach(sec => sec.classList.remove('active'));
        document.getElementById(`section-${currentStep}`).classList.add('active');

        // Progress Bar
        const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.getElementById('progress-line').style.width = `${progressPercentage}%`;

        // Steps
        for (let i = 1; i <= totalSteps; i++) {
            const step = document.getElementById(`step-${i}`);
            step.classList.remove('active', 'completed');
            if (i < currentStep) {
                step.classList.add('completed');
            } else if (i === currentStep) {
                step.classList.add('active');
            }
        }

        // Buttons
        prevBtn.style.display = currentStep > 1 ? 'flex' : 'none';
        nextBtn.style.display = currentStep < totalSteps ? 'flex' : 'none';
        submitBtn.style.display = currentStep === totalSteps ? 'flex' : 'none';
    }

    function addFileField() {
        const container = document.getElementById('files-container');
        const div = document.createElement('div');
        div.className = 'file-entry grid grid-cols-1 md:grid-cols-3 gap-4 items-start';
        div.innerHTML = `
            <div>
                <label class="form-label text-sm">اسم الملف</label>
                <input type="text" name="files[${fileCounter}][name]" class="form-input text-sm" placeholder="مثال: العقد الأساسي">
            </div>
            <div>
                <label class="form-label text-sm">وصف الملف</label>
                <input type="text" name="files[${fileCounter}][description]" class="form-input text-sm" placeholder="وصف مختصر للملف">
            </div>
            <div class="flex items-end gap-2 h-full">
                <input type="file" name="files[${fileCounter}][file]" class="form-file-input w-full" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                <button type="button" onclick="this.closest('.file-entry').remove()" class="btn-danger flex-shrink-0">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        fileCounter++;
    }

    function addRequestField() {
        const container = document.getElementById('requests-container');
        const div = document.createElement('div');
        div.className = 'request-entry grid grid-cols-1 md:grid-cols-3 gap-4 items-start';
        div.innerHTML = `
            <div>
                <label class="form-label text-sm">رقم الطلب</label>
                <input type="text" name="requests[${requestCounter}][number]" class="form-input text-sm" placeholder="رقم طلب الاستلام">
            </div>
            <div>
                <label class="form-label text-sm">وصف الطلب</label>
                <input type="text" name="requests[${requestCounter}][description]" class="form-input text-sm" placeholder="وصف مختصر للطلب">
            </div>
            <div class="flex items-end gap-2 h-full">
                <input type="file" name="requests[${requestCounter}][file]" class="form-file-input w-full" accept=".pdf,.doc,.docx,.xls,.xlsx">
                <button type="button" onclick="this.closest('.request-entry').remove()" class="btn-danger flex-shrink-0">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        requestCounter++;
    }

    function previewImages(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = '';
        Array.from(event.target.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative rounded-lg overflow-hidden border border-gray-200';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover">
                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2 text-xs truncate">${file.name}</div>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
@endsection