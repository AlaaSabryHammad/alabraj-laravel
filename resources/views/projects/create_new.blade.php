@extends('layouts.app')

@section('title', 'إنشاء مشروع جديد')

@push('styles')
    <style>
        /* Modern Theme Variables */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --surface-glass: rgba(255, 255, 255, 0.25);
            --border-glass: rgba(255, 255, 255, 0.18);
            --shadow-soft: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --backdrop-blur: blur(4px);
        }

        /* Modern Container */
        .modern-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
        }

        .modern-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="120" fill="url(%23a)"/></svg>') center/cover;
            pointer-events: none;
        }

        /* Glass Card */
        .glass-card {
            background: var(--surface-glass);
            backdrop-filter: var(--backdrop-blur);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
            position: relative;
            z-index: 1;
        }

        /* Modern Tabs */
        .modern-tab {
            @apply relative px-8 py-4 rounded-xl font-semibold transition-all duration-300 cursor-pointer;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .modern-tab.active {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            color: #1e293b;
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .modern-tab:not(.active):hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-1px);
        }

        /* Progress Indicator */
        .progress-modern {
            @apply relative h-2 bg-white bg-opacity-20 rounded-full overflow-hidden;
        }

        .progress-fill-modern {
            @apply h-full bg-gradient-to-r from-blue-400 to-purple-500 rounded-full transition-all duration-700 ease-out;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
        }

        .progress-step-modern {
            @apply relative w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300;
            background: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.7);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .progress-step-modern.active {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border-color: #4facfe;
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        }

        .progress-step-modern.completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-color: #10b981;
        }

        /* Modern Form Elements */
        .form-section-modern {
            @apply hidden p-8;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin: 20px;
        }

        .form-section-modern.active {
            @apply block;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-header-modern {
            @apply flex items-center gap-4 mb-8 pb-6;
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg, #667eea, #764ba2) 1;
        }

        .section-icon-modern {
            @apply w-14 h-14 rounded-2xl flex items-center justify-center text-2xl text-white;
            background: var(--primary-gradient);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .section-title-modern {
            @apply text-2xl font-bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Input Styles */
        .input-modern {
            @apply w-full px-6 py-4 rounded-xl border-2 border-gray-200 transition-all duration-300;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .input-modern:focus {
            @apply outline-none border-transparent;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2), 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .label-modern {
            @apply block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-3;
        }

        .label-icon-modern {
            @apply w-5 h-5 text-indigo-500;
        }

        .select-modern {
            @apply w-full px-6 py-4 rounded-xl border-2 border-gray-200 appearance-none cursor-pointer transition-all duration-300;
            background: rgba(255, 255, 255, 0.9) url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236366f1' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") no-repeat right 1rem center/1.5em 1.5em;
            backdrop-filter: blur(10px);
        }

        .select-modern:focus {
            @apply outline-none border-transparent;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2), 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .textarea-modern {
            @apply w-full px-6 py-4 rounded-xl border-2 border-gray-200 resize-none transition-all duration-300;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .textarea-modern:focus {
            @apply outline-none border-transparent;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2), 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .file-input-modern {
            @apply w-full px-6 py-4 rounded-xl border-2 border-dashed border-gray-300 transition-all duration-300 cursor-pointer;
            background: rgba(249, 250, 251, 0.9);
            backdrop-filter: blur(10px);
        }

        .file-input-modern:hover {
            @apply border-indigo-400;
            background: rgba(238, 242, 255, 0.9);
            transform: translateY(-1px);
        }

        /* Modern Buttons */
        .btn-modern {
            @apply px-8 py-4 rounded-xl font-semibold transition-all duration-300 flex items-center gap-3;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-success-modern {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
        }

        .btn-success-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(79, 172, 254, 0.4);
        }

        .btn-secondary-modern {
            background: rgba(255, 255, 255, 0.9);
            color: #374151;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-secondary-modern:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-danger-modern {
            background: var(--secondary-gradient);
            color: white;
            box-shadow: 0 8px 25px rgba(245, 87, 108, 0.3);
        }

        .btn-danger-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(245, 87, 108, 0.4);
        }

        /* Card Containers */
        .file-card-modern {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.9) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .file-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .request-card-modern {
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(79, 172, 254, 0.2);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            transition: all 0.3s ease;
        }

        .request-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(79, 172, 254, 0.15);
        }

        /* Header Modern */
        .header-modern {
            @apply relative z-10 p-8 text-center;
        }

        .title-modern {
            @apply text-4xl font-bold text-white mb-4;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .subtitle-modern {
            @apply text-lg text-white text-opacity-90;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@section('content')
    <div class="modern-container" dir="rtl">
        <!-- Header -->
        <div class="header-modern">
            <div class="flex items-center justify-center gap-4 mb-6">
                <a href="{{ route('projects.index') }}"
                    class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white hover:bg-opacity-20">
                    <i class="ri-arrow-right-line text-2xl"></i>
                </a>
                <div>
                    <h1 class="title-modern">إنشاء مشروع جديد</h1>
                    <p class="subtitle-modern">قم بإدخال بيانات المشروع بطريقة حديثة ومنظمة</p>
                </div>
            </div>

            <!-- Progress Indicator -->
            <div class="max-w-2xl mx-auto mb-8">
                <div class="progress-modern">
                    <div class="progress-fill-modern" style="width: 25%" id="progressFillModern"></div>
                </div>
                <div class="flex justify-between mt-4">
                    <div class="progress-step-modern active" id="stepModern1">1</div>
                    <div class="progress-step-modern" id="stepModern2">2</div>
                    <div class="progress-step-modern" id="stepModern3">3</div>
                    <div class="progress-step-modern" id="stepModern4">4</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto pb-8 px-4">
            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Glass Card Container -->
                <div class="glass-card">
                    <!-- Tabs -->
                    <div class="p-6 border-b border-white border-opacity-20">
                        <div class="flex flex-wrap gap-4 justify-center">
                            <div class="modern-tab active" data-section="basic-info">
                                <i class="ri-information-line ml-2"></i>
                                <span>البيانات الأساسية</span>
                            </div>
                            <div class="modern-tab" data-section="project-files">
                                <i class="ri-folder-line ml-2"></i>
                                <span>الملفات</span>
                            </div>
                            <div class="modern-tab" data-section="project-images">
                                <i class="ri-image-line ml-2"></i>
                                <span>الصور</span>
                            </div>
                            <div class="modern-tab" data-section="delivery-requests">
                                <i class="ri-file-list-3-line ml-2"></i>
                                <span>طلبات الاستلام</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 1: Basic Information -->
                    <div id="basic-info" class="form-section-modern active">
                        <div class="section-header-modern">
                            <div class="section-icon-modern">
                                <i class="ri-information-line"></i>
                            </div>
                            <h3 class="section-title-modern">معلومات المشروع الأساسية</h3>
                        </div>

                        <!-- Project Name and Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="label-modern">
                                    <i class="ri-building-line label-icon-modern"></i>
                                    اسم المشروع <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="input-modern @error('name') border-red-300 @enderror"
                                    placeholder="أدخل اسم المشروع" required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="project_number" class="label-modern">
                                    <i class="ri-numbers-line label-icon-modern"></i>
                                    رقم المشروع <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="project_number" id="project_number"
                                    value="{{ old('project_number') }}"
                                    class="input-modern @error('project_number') border-red-300 @enderror"
                                    placeholder="رقم المشروع" required>
                                @error('project_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Project Value and Government Entity -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="budget" class="label-modern">
                                    <i class="ri-money-dollar-circle-line label-icon-modern"></i>
                                    قيمة المشروع (ر.س) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="budget" id="budget" value="{{ old('budget') }}"
                                    step="0.01" min="0"
                                    class="input-modern @error('budget') border-red-300 @enderror" placeholder="0.00"
                                    required>
                                @error('budget')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="government_entity" class="label-modern">
                                    <i class="ri-government-line label-icon-modern"></i>
                                    الجهة الحكومية
                                </label>
                                <input type="text" name="government_entity" id="government_entity"
                                    value="{{ old('government_entity') }}"
                                    class="input-modern @error('government_entity') border-red-300 @enderror"
                                    placeholder="اسم الجهة الحكومية">
                                @error('government_entity')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Consulting Office and Project Scope -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="consulting_office" class="label-modern">
                                    <i class="ri-building-2-line label-icon-modern"></i>
                                    مكتب الاستشاري
                                </label>
                                <input type="text" name="consulting_office" id="consulting_office"
                                    value="{{ old('consulting_office') }}"
                                    class="input-modern @error('consulting_office') border-red-300 @enderror"
                                    placeholder="اسم المكتب الاستشاري">
                                @error('consulting_office')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="project_scope" class="label-modern">
                                    <i class="ri-focus-3-line label-icon-modern"></i>
                                    نطاق عمل المشروع
                                </label>
                                <input type="text" name="project_scope" id="project_scope"
                                    value="{{ old('project_scope') }}"
                                    class="input-modern @error('project_scope') border-red-300 @enderror"
                                    placeholder="نطاق أو مجال العمل">
                                @error('project_scope')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Project Description -->
                        <div class="mb-6">
                            <label for="description" class="label-modern">
                                <i class="ri-file-text-line label-icon-modern"></i>
                                وصف المشروع
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="textarea-modern @error('description') border-red-300 @enderror"
                                placeholder="وصف تفصيلي للمشروع، أهدافه، ومتطلباته">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dates and Project Manager -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="start_date" class="label-modern">
                                    <i class="ri-calendar-line label-icon-modern"></i>
                                    تاريخ بداية المشروع <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                    class="input-modern @error('start_date') border-red-300 @enderror" required>
                                @error('start_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="label-modern">
                                    <i class="ri-calendar-check-line label-icon-modern"></i>
                                    التاريخ المتوقع لنهاية المشروع
                                </label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                    class="input-modern @error('end_date') border-red-300 @enderror">
                                @error('end_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="project_manager_id" class="label-modern">
                                    <i class="ri-user-star-line label-icon-modern"></i>
                                    اسم مدير المشروع <span class="text-red-500">*</span>
                                </label>
                                <select name="project_manager_id" id="project_manager_id"
                                    class="select-modern @error('project_manager_id') border-red-300 @enderror" required>
                                    <option value="">اختر مدير المشروع</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ old('project_manager_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_manager_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Project Files -->
                    <div id="project-files" class="form-section-modern">
                        <div class="section-header-modern">
                            <div class="section-icon-modern"
                                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="ri-folder-line"></i>
                            </div>
                            <h3 class="section-title-modern">إدارة ملفات المشروع</h3>
                        </div>

                        <div id="files-container">
                            <div class="file-card-modern">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="label-modern">
                                            <i class="ri-file-text-line label-icon-modern"></i>
                                            اسم الملف
                                        </label>
                                        <input type="text" name="files[0][name]" class="input-modern"
                                            placeholder="اسم الملف">
                                    </div>
                                    <div>
                                        <label class="label-modern">
                                            <i class="ri-upload-line label-icon-modern"></i>
                                            رفع الملف
                                        </label>
                                        <input type="file" name="files[0][file]" class="file-input-modern"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                    </div>
                                    <div>
                                        <label class="label-modern">
                                            <i class="ri-information-line label-icon-modern"></i>
                                            وصف الملف
                                        </label>
                                        <input type="text" name="files[0][description]" class="input-modern"
                                            placeholder="وصف مختصر للملف">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="addFileFieldModern()" class="btn-modern btn-primary-modern">
                            <i class="ri-add-line"></i>
                            إضافة ملف آخر
                        </button>
                    </div>

                    <!-- Section 3: Project Images -->
                    <div id="project-images" class="form-section-modern">
                        <div class="section-header-modern">
                            <div class="section-icon-modern"
                                style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                <i class="ri-image-line"></i>
                            </div>
                            <h3 class="section-title-modern">إدارة صور المشروع</h3>
                        </div>

                        <div class="mb-6">
                            <label class="label-modern">
                                <i class="ri-image-add-line label-icon-modern"></i>
                                رفع صور المشروع
                            </label>
                            <input type="file" name="images[]" id="project_images" multiple accept="image/*"
                                class="file-input-modern">
                            <p class="mt-3 text-sm text-gray-600 flex items-center gap-2">
                                <i class="ri-information-line text-blue-500"></i>
                                يمكنك اختيار عدة صور في نفس الوقت (PNG, JPG, JPEG)
                            </p>
                        </div>

                        <div id="image-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                    </div>

                    <!-- Section 4: Delivery Requests -->
                    <div id="delivery-requests" class="form-section-modern">
                        <div class="section-header-modern">
                            <div class="section-icon-modern"
                                style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                            <h3 class="section-title-modern">إدارة طلبات الاستلام</h3>
                        </div>

                        <div id="requests-container">
                            <div class="request-card-modern">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="label-modern">
                                            <i class="ri-hashtag label-icon-modern"></i>
                                            رقم الطلب
                                        </label>
                                        <input type="text" name="requests[0][number]" class="input-modern"
                                            placeholder="رقم الطلب">
                                    </div>
                                    <div>
                                        <label class="label-modern">
                                            <i class="ri-file-text-line label-icon-modern"></i>
                                            وصف الطلب
                                        </label>
                                        <input type="text" name="requests[0][description]" class="input-modern"
                                            placeholder="وصف الطلب">
                                    </div>
                                    <div>
                                        <label class="label-modern">
                                            <i class="ri-upload-line label-icon-modern"></i>
                                            رفع الملف
                                        </label>
                                        <input type="file" name="requests[0][file]" class="file-input-modern"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" onclick="removeRequestFieldModern(this)"
                                            class="btn-modern btn-danger-modern w-full justify-center">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="addRequestFieldModern()" class="btn-modern btn-primary-modern">
                            <i class="ri-add-line"></i>
                            إضافة طلب آخر
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between p-8 border-t border-white border-opacity-20">
                        <a href="{{ route('projects.index') }}" class="btn-modern btn-secondary-modern">
                            <i class="ri-arrow-right-line"></i>
                            العودة للقائمة
                        </a>
                        <div class="flex gap-4">
                            <button type="button" id="prevBtnModern" onclick="changeSectionModern(-1)"
                                class="btn-modern btn-secondary-modern" style="display: none;">
                                <i class="ri-arrow-right-line"></i>
                                السابق
                            </button>
                            <button type="button" id="nextBtnModern" onclick="changeSectionModern(1)"
                                class="btn-modern btn-primary-modern">
                                التالي
                                <i class="ri-arrow-left-line"></i>
                            </button>
                            <button type="submit" id="submitBtnModern" class="btn-modern btn-success-modern"
                                style="display: none;">
                                <i class="ri-save-line"></i>
                                إنشاء المشروع
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Modern Script Variables
            const sectionsModern = ['basic-info', 'project-files', 'project-images', 'delivery-requests'];
            let currentSectionModern = 0;
            let fileCounterModern = 1;
            let requestCounterModern = 1;

            // Section navigation
            function showSectionModern(index) {
                // Hide all sections
                sectionsModern.forEach((section, i) => {
                    document.getElementById(section).classList.remove('active');
                    document.querySelector(`[data-section="${section}"]`).classList.remove('active');
                });

                // Show current section
                document.getElementById(sectionsModern[index]).classList.add('active');
                document.querySelector(`[data-section="${sectionsModern[index]}"]`).classList.add('active');

                // Update progress indicator
                updateProgressIndicatorModern(index);

                // Update navigation buttons
                updateNavigationButtonsModern(index);
            }

            function updateProgressIndicatorModern(index) {
                const progressFill = document.getElementById('progressFillModern');
                const steps = document.querySelectorAll('.progress-step-modern');

                // Update progress fill
                const progressPercentage = ((index + 1) / sectionsModern.length) * 100;
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

            function changeSectionModern(direction) {
                const newIndex = currentSectionModern + direction;
                if (newIndex >= 0 && newIndex < sectionsModern.length) {
                    currentSectionModern = newIndex;
                    showSectionModern(currentSectionModern);
                }
            }

            function updateNavigationButtonsModern(index) {
                const prevBtn = document.getElementById('prevBtnModern');
                const nextBtn = document.getElementById('nextBtnModern');
                const submitBtn = document.getElementById('submitBtnModern');

                prevBtn.style.display = index === 0 ? 'none' : 'flex';
                nextBtn.style.display = index === sectionsModern.length - 1 ? 'none' : 'flex';
                submitBtn.style.display = index === sectionsModern.length - 1 ? 'flex' : 'none';
            }

            // Tab click handlers
            document.querySelectorAll('.modern-tab').forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    currentSectionModern = index;
                    showSectionModern(currentSectionModern);
                });
            });

            // Add file field
            function addFileFieldModern() {
                const container = document.getElementById('files-container');
                const fileItem = document.createElement('div');
                fileItem.className = 'file-card-modern';
                fileItem.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="label-modern">
                                <i class="ri-file-text-line label-icon-modern"></i>
                                اسم الملف
                            </label>
                            <input type="text" 
                                   name="files[${fileCounterModern}][name]" 
                                   class="input-modern"
                                   placeholder="اسم الملف">
                        </div>
                        <div>
                            <label class="label-modern">
                                <i class="ri-upload-line label-icon-modern"></i>
                                رفع الملف
                            </label>
                            <input type="file" 
                                   name="files[${fileCounterModern}][file]" 
                                   class="file-input-modern"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        </div>
                        <div>
                            <label class="label-modern">
                                <i class="ri-information-line label-icon-modern"></i>
                                وصف الملف
                            </label>
                            <input type="text" 
                                   name="files[${fileCounterModern}][description]" 
                                   class="input-modern"
                                   placeholder="وصف مختصر للملف">
                            <button type="button" 
                                    onclick="removeFileFieldModern(this)" 
                                    class="btn-modern btn-danger-modern mt-3 w-full justify-center">
                                <i class="ri-delete-bin-line"></i>
                                حذف الملف
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(fileItem);
                fileCounterModern++;
            }

            function removeFileFieldModern(button) {
                button.closest('.file-card-modern').remove();
            }

            // Add request field
            function addRequestFieldModern() {
                const container = document.getElementById('requests-container');
                const requestItem = document.createElement('div');
                requestItem.className = 'request-card-modern';
                requestItem.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="label-modern">
                                <i class="ri-hashtag label-icon-modern"></i>
                                رقم الطلب
                            </label>
                            <input type="text" 
                                   name="requests[${requestCounterModern}][number]" 
                                   class="input-modern"
                                   placeholder="رقم الطلب">
                        </div>
                        <div>
                            <label class="label-modern">
                                <i class="ri-file-text-line label-icon-modern"></i>
                                وصف الطلب
                            </label>
                            <input type="text" 
                                   name="requests[${requestCounterModern}][description]" 
                                   class="input-modern"
                                   placeholder="وصف الطلب">
                        </div>
                        <div>
                            <label class="label-modern">
                                <i class="ri-upload-line label-icon-modern"></i>
                                رفع الملف
                            </label>
                            <input type="file" 
                                   name="requests[${requestCounterModern}][file]" 
                                   class="file-input-modern"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx">
                        </div>
                        <div class="flex items-end">
                            <button type="button" 
                                    onclick="removeRequestFieldModern(this)" 
                                    class="btn-modern btn-danger-modern w-full justify-center">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(requestItem);
                requestCounterModern++;
            }

            function removeRequestFieldModern(button) {
                button.closest('.request-card-modern').remove();
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
                            div.className = 'relative rounded-lg overflow-hidden';
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
        </script>
    @endpush
@endsection
