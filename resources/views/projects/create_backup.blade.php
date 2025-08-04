@extends('layouts.app')

@section('title', 'إضافة مشروع جديد')

@push('styles')
    <style>
        .section-tab {
            @apply px-6 py-3 text-sm font-semibold rounded-xl transition-all duration-300 cursor-pointer border-2;
        }

        .section-tab.active {
            @apply bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg border-blue-600 transform scale-105;
        }

        .section-tab:not(.active) {
            @apply bg-white text-gray-600 hover:bg-blue-50 hover:text-blue-600 border-gray-200 hover:border-blue-300;
        }

        .section-content {
            @apply hidden;
            <div><label class="enhanced-label"><i class="ri-information-line input-icon"></i>وصف الملف </label><input type="text" name="files[${fileCounter}][description]" class="enhanced-input" placeholder="وصف مختصر للملف"></div><button type="button" onclick="removeFileField(this)" class="btn-danger flex items-center justify-center w-full md:w-auto px-4 py-3"><i class="ri-delete-bin-line"></i></button></div></div>`;

            section-content.active {
                @apply block;
            }

            /* Enhanced Input Styles */
            .enhanced-input {
                @apply w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-300 bg-white;
                @apply focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none;
                @apply hover:border-gray-300;
            }

            .enhanced-input:focus {
                @apply shadow-lg transform scale-[1.02];
            }

            .enhanced-label {
                @apply block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2;
            }

            .enhanced-select {
                @apply w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-300 bg-white;
                @apply focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none appearance-none;
                @apply hover:border-gray-300 cursor-pointer;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
                background-position: left 0.75rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
                padding-left: 2.5rem;
            }

            .enhanced-textarea {
                @apply w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-300 bg-white resize-none;
                @apply focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none;
                @apply hover:border-gray-300;
            }

            .enhanced-file-input {
                @apply w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl transition-all duration-300;
                @apply hover:border-blue-400 hover:bg-blue-50 cursor-pointer;
                @apply focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100;
            }

            .input-icon {
                @apply text-blue-500 text-lg;
            }

            .section-header {
                @apply flex items-center gap-3 text-xl font-bold text-gray-800 mb-8 pb-4 border-b-2 border-gray-100;
            }

            .section-icon {
                @apply w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-xl;
            }

            .form-grid {
                @apply grid gap-8;
            }

            .enhanced-button {
                @apply px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center gap-2;
                @apply shadow-md hover:shadow-lg transform hover:scale-105;
            }

            .btn-primary {
                @apply bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800;
            }

            .btn-success {
                @apply bg-gradient-to-r from-green-600 to-green-700 text-white hover:from-green-700 hover:to-green-800;
            }

            .btn-success {
                @apply bg-gradient-to-r from-green-500 to-green-600 text-white hover:from-green-600 hover:to-green-700;
            }

            .btn-secondary {
                @apply bg-gradient-to-r from-gray-500 to-gray-600 text-white hover:from-gray-600 hover:to-gray-700;
            }

            .btn-danger {
                @apply bg-gradient-to-r from-red-500 to-red-600 text-white hover:from-red-600 hover:to-red-700;
            }

            .file-item {
                @apply bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 mb-6 border border-gray-200;
                @apply hover:shadow-md transition-all duration-300;
            }

            .request-item {
                @apply bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 border border-blue-200;
                @apply hover:shadow-md transition-all duration-300;
            }

            /* Progress Indicator */
            .progress-container {
                @apply relative mb-8 mt-6;
            }

            .progress-line {
                @apply absolute top-4 left-0 right-0 h-1 bg-gray-200 rounded-full;
            }

            .progress-fill {
                @apply h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-500;
            }

            .progress-step {
                @apply relative bg-white border-4 border-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-xs font-bold;
                @apply transition-all duration-300;
            }

            .progress-step.active {
                @apply border-blue-500 text-blue-500;
            }

            .progress-step.completed {
                @apply bg-green-500 border-green-500 text-white;
            }
    </style>
@endpush

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">مشروع جديد</h1>
                    <p class="text-gray-600 mt-1">قم بإدخال بيانات المشروع الجديد بشكل منظم</p>
                </div>
            </div>
            <div class="bg-blue-50 px-4 py-2 rounded-lg">
                <span class="text-blue-700 font-medium">نموذج إدخال البيانات</span>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="progress-container">
            <div class="progress-line">
                <div class="progress-fill" style="width: 25%" id="progressFill"></div>
            </div>
            <div class="flex justify-between">
                <div class="progress-step active" id="step1">1</div>
                <div class="progress-step" id="step2">2</div>
                <div class="progress-step" id="step3">3</div>
                <div class="progress-step" id="step4">4</div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="max-w-6xl">
            @csrf

            <!-- Section Tabs -->
            <div class="bg-white rounded-xl shadow-sm border mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-wrap gap-3 justify-center">
                        <div class="section-tab active" data-section="basic-info">
                            <i class="ri-information-line ml-2"></i>
                            <span>البيانات الأساسية</span>
                        </div>
                        <div class="section-tab" data-section="project-files">
                            <i class="ri-folder-line ml-2"></i>
                            <span>الملفات</span>
                        </div>
                        <div class="section-tab" data-section="project-images">
                            <i class="ri-image-line ml-2"></i>
                            <span>الصور</span>
                        </div>
                        <div class="section-tab" data-section="delivery-requests">
                            <i class="ri-file-list-3-line ml-2"></i>
                            <span>طلبات الاستلام</span>
                        </div>
                    </div>
                </div>

                <!-- Section 1: Basic Information -->
                <div id="basic-info" class="section-content active p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="ri-information-line text-blue-600 text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">معلومات المشروع الأساسية</h3>
                    </div>

                    <!-- Row 1: Project Name and Number -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="enhanced-label">
                                <i class="ri-building-line input-icon"></i>
                                اسم المشروع <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="enhanced-input @error('name') border-red-300 @enderror"
                                placeholder="أدخل اسم المشروع" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_number" class="enhanced-label">
                                <i class="ri-numbers-line input-icon"></i>
                                رقم المشروع <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="project_number" id="project_number"
                                value="{{ old('project_number') }}"
                                class="enhanced-input @error('project_number') border-red-300 @enderror"
                                placeholder="رقم المشروع" required>
                            @error('project_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Project Value and Government Entity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="budget" class="enhanced-label">
                                <i class="ri-money-dollar-circle-line input-icon"></i>
                                قيمة المشروع (ر.س) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="budget" id="budget" value="{{ old('budget') }}" step="0.01"
                                min="0" class="enhanced-input @error('budget') border-red-300 @enderror"
                                placeholder="0.00" required>
                            @error('budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="government_entity" class="enhanced-label">
                                <i class="ri-government-line input-icon"></i>
                                الجهة الحكومية
                            </label>
                            <input type="text" name="government_entity" id="government_entity"
                                value="{{ old('government_entity') }}"
                                class="enhanced-input @error('government_entity') border-red-300 @enderror"
                                placeholder="اسم الجهة الحكومية">
                            @error('government_entity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 3: Consulting Office and Project Scope -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="consulting_office" class="enhanced-label">
                                <i class="ri-building-2-line input-icon"></i>
                                مكتب الاستشاري
                            </label>
                            <input type="text" name="consulting_office" id="consulting_office"
                                value="{{ old('consulting_office') }}"
                                class="enhanced-input @error('consulting_office') border-red-300 @enderror"
                                placeholder="اسم المكتب الاستشاري">
                            @error('consulting_office')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_scope" class="enhanced-label">
                                <i class="ri-focus-3-line input-icon"></i>
                                نطاق عمل المشروع
                            </label>
                            <input type="text" name="project_scope" id="project_scope"
                                value="{{ old('project_scope') }}"
                                class="enhanced-input @error('project_scope') border-red-300 @enderror"
                                placeholder="نطاق أو مجال العمل">
                            @error('project_scope')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Project Description -->
                    <!-- Project Description -->
                    <div class="mb-6">
                        <label for="description" class="enhanced-label">
                            <i class="ri-file-text-line input-icon"></i>
                            وصف المشروع
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="enhanced-textarea @error('description') border-red-300 @enderror"
                            placeholder="وصف تفصيلي للمشروع، أهدافه، ومتطلباته">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Row 4: Dates and Project Manager -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="start_date" class="enhanced-label">
                                <i class="ri-calendar-line input-icon"></i>
                                تاريخ بداية المشروع <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                class="enhanced-input @error('start_date') border-red-300 @enderror" required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="enhanced-label">
                                <i class="ri-calendar-check-line input-icon"></i>
                                التاريخ المتوقع لنهاية المشروع
                            </label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                class="enhanced-input @error('end_date') border-red-300 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_manager_id" class="enhanced-label">
                                <i class="ri-user-star-line input-icon"></i>
                                اسم مدير المشروع <span class="text-red-500">*</span>
                            </label>
                            <select name="project_manager_id" id="project_manager_id"
                                class="enhanced-select @error('project_manager_id') border-red-300 @enderror" required>
                                <option value="">اختر مدير المشروع</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('project_manager_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_manager_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Project Files -->
                <div id="project-files" class="section-content p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="ri-folder-line text-green-600 text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">إدارة ملفات المشروع</h3>
                    </div>

                    <div id="files-container">
                        <div
                            class="file-item bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 mb-6 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="enhanced-label">
                                        <i class="ri-file-text-line input-icon"></i>
                                        اسم الملف
                                    </label>
                                    <input type="text" name="files[0][name]" class="enhanced-input"
                                        placeholder="اسم الملف">
                                </div>
                                <div>
                                    <label class="enhanced-label">
                                        <i class="ri-upload-line input-icon"></i>
                                        رفع الملف
                                    </label>
                                    <input type="file" name="files[0][file]" class="enhanced-file-input"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                </div>
                                <div>
                                    <label class="enhanced-label">
                                        <i class="ri-information-line input-icon"></i>
                                        وصف الملف
                                    </label>
                                    <input type="text" name="files[0][description]" class="enhanced-input"
                                        placeholder="وصف مختصر للملف">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="addFileField()" class="btn-primary flex items-center gap-2">
                        <i class="ri-add-line"></i>
                        إضافة ملف آخر
                    </button>
                </div>

                <!-- Section 3: Project Images -->
                <div id="project-images" class="section-content p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="ri-image-line text-purple-600 text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">إدارة صور المشروع</h3>
                    </div>

                    <div class="mb-6">
                        <label class="enhanced-label">
                            <i class="ri-image-add-line input-icon"></i>
                            رفع صور المشروع
                        </label>
                        <input type="file" name="images[]" id="project_images" multiple accept="image/*"
                            class="enhanced-file-input">
                        <p class="mt-2 text-sm text-gray-500 flex items-center gap-2">
                            <i class="ri-information-line text-blue-500"></i>
                            يمكنك اختيار عدة صور في نفس الوقت (PNG, JPG, JPEG)
                        </p>
                    </div>

                    <div id="image-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                </div>

                <!-- Section 4: Delivery Requests -->
                <div id="delivery-requests" class="section-content p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="ri-file-list-3-line text-orange-600 text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">إدارة طلبات الاستلام</h3>
                    </div>

                    <div id="requests-container">
                        <div
                            class="request-item bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 border border-blue-200">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="enhanced-label">
                                        <i class="ri-hashtag input-icon"></i>
                                        رقم الطلب
                                    </label>
                                    <input type="text" name="requests[0][number]" class="enhanced-input"
                                        placeholder="رقم الطلب">
                                </div>
                                <div>
                                    <label class="enhanced-label">
                                        <i class="ri-file-text-line input-icon"></i>
                                        وصف الطلب
                                    </label>
                                    <input type="text" name="requests[0][description]" class="enhanced-input"
                                        placeholder="وصف الطلب">
                                </div>
                                <div>
                                    <label class="enhanced-label">
                                        <i class="ri-upload-line input-icon"></i>
                                        رفع الملف
                                    </label>
                                    <input type="file" name="requests[0][file]" class="enhanced-file-input"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="removeRequestField(this)"
                                        class="btn-danger flex items-center justify-center w-full md:w-auto px-4 py-3">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="addRequestField()" class="btn-primary flex items-center gap-2">
                        <i class="ri-add-line"></i>
                        إضافة طلب آخر
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between p-8 border-t border-gray-200 bg-gray-50">
                    <a href="{{ route('projects.index') }}" class="btn-secondary flex items-center gap-2">
                        <i class="ri-arrow-right-line"></i>
                        العودة للقائمة
                    </a>
                    <div class="flex gap-3">
                        <button type="button" id="prevBtn" onclick="changeSection(-1)"
                            class="btn-secondary flex items-center gap-2" style="display: none;">
                            <i class="ri-arrow-right-line"></i>
                            السابق
                        </button>
                        <button type="button" id="nextBtn" onclick="changeSection(1)"
                            class="btn-primary flex items-center gap-2">
                            التالي
                            <i class="ri-arrow-left-line"></i>
                        </button>
                        <button type="submit" id="submitBtn" class="btn-success flex items-center gap-2"
                            style="display: none;">
                            <i class="ri-save-line"></i>
                            إنشاء المشروع
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let currentSection = 0;
            const sections = ['basic-info', 'project-files', 'project-images', 'delivery-requests'];
            let fileCounter = 1;
            let requestCounter = 1;

            // Section navigation
            function showSection(index) {
                // Hide all sections
                sections.forEach((section, i) => {
                    document.getElementById(section).classList.remove('active');
                    document.querySelector(`[data-section="${section}"]`).classList.remove('active');
                });

                // Show current section
                document.getElementById(sections[index]).classList.add('active');
                document.querySelector(`[data-section="${sections[index]}"]`).classList.add('active');

                // Update navigation buttons
                updateNavigationButtons(index);

                // Update progress indicator
                updateProgressIndicator(index);
            }

            function updateProgressIndicator(index) {
                const progressFill = document.getElementById('progressFill');
                const steps = document.querySelectorAll('.progress-step');

                // Update progress fill
                const progressPercentage = ((index + 1) / sections.length) * 100;
                progressFill.style.width = progressPercentage + '%';

                // Update step indicators
                steps.forEach((step, stepIndex) => {
                    step.classList.remove('active', 'completed');
                    if (stepIndex < index) {
                        step.classList.add('completed');
                    } else if (stepIndex === index) {
                        step.classList.add('active');
                    }
                });
            }

            function changeSection(direction) {
                const newIndex = currentSection + direction;
                if (newIndex >= 0 && newIndex < sections.length) {
                    currentSection = newIndex;
                    showSection(currentSection);
                }
            }

            function updateNavigationButtons(index) {
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const submitBtn = document.getElementById('submitBtn');

                prevBtn.style.display = index === 0 ? 'none' : 'block';
                nextBtn.style.display = index === sections.length - 1 ? 'none' : 'block';
                submitBtn.style.display = index === sections.length - 1 ? 'flex' : 'none';
            }

            // Tab click handlers
            document.querySelectorAll('.section-tab').forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    currentSection = index;
                    showSection(currentSection);
                });
            });

            // Add file field
            function addFileField() {
                const container = document.getElementById('files-container');
                const fileItem = document.createElement('div');
                fileItem.className =
                    'file-item bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 mb-6 border border-gray-200';
                fileItem.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="enhanced-label">
                        <i class="ri-file-text-line input-icon"></i>
                        اسم الملف
                    </label>
                    <input type="text" 
                           name="files[${fileCounter}][name]" 
                           class="enhanced-input"
                           placeholder="اسم الملف">
                </div>
                <div>
                    <label class="enhanced-label">
                        <i class="ri-upload-line input-icon"></i>
                        رفع الملف
                    </label>
                    <input type="file" 
                           name="files[${fileCounter}][file]" 
                           class="enhanced-file-input"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                </div>
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">وصف الملف</label>
                        <input type="text" 
                               name="files[${fileCounter}][description]" 
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="وصف مختصر للملف">
                    </div>
                    <button type="button" 
                            onclick="removeFileField(this)" 
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg transition-colors">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        `;
                container.appendChild(fileItem);
                fileCounter++;
            }

            function removeFileField(button) {
                button.closest('.file-item').remove();
            }

            // Add request field
            function addRequestField() {
                const container = document.getElementById('requests-container');
                const requestItem = document.createElement('div');
                requestItem.className =
                    'request-item bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 border border-blue-200';
                requestItem.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="enhanced-label">
                        <i class="ri-hashtag input-icon"></i>
                        رقم الطلب
                    </label>
                    <input type="text" 
                           name="requests[${requestCounter}][number]" 
                           class="enhanced-input"
                           placeholder="رقم الطلب">
                </div>
                <div>
                    <label class="enhanced-label">
                        <i class="ri-file-text-line input-icon"></i>
                        وصف الطلب
                    </label>
                    <input type="text" 
                           name="requests[${requestCounter}][description]" 
                           class="enhanced-input"
                           placeholder="وصف الطلب">
                </div>
                <div>
                    <label class="enhanced-label">
                        <i class="ri-upload-line input-icon"></i>
                        رفع الملف
                    </label>
                    <input type="file" 
                           name="requests[${requestCounter}][file]" 
                           class="enhanced-file-input"
                           accept=".pdf,.doc,.docx,.xls,.xlsx">
                </div>
                <div class="flex items-end">
                    <button type="button" 
                            onclick="removeRequestField(this)" 
                            class="btn-danger flex items-center justify-center w-full md:w-auto px-4 py-3">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        `;
                container.appendChild(requestItem);
                requestCounter++;
            }

            function removeRequestField(button) {
                button.closest('.request-item').remove();
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
                            div.className = 'relative';
                            div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">
                        <p class="text-xs text-gray-600 mt-1 truncate">${file.name}</p>
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
        </script>
    @endpush
@endsection
