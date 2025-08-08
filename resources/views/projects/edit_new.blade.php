@extends('layouts.app')

@section('title', 'تعديل المشروع: ' . $project->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تعديل المشروع</h1>
                <p class="text-gray-600">{{ $project->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.show', $project) }}"
                   class="bg-blue-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-600 transition-all duration-200 flex items-center">
                    <i class="ri-eye-line ml-2"></i>
                    عرض المشروع
                </a>
                <a href="{{ route('projects.index') }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Project Statistics -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="ri-bar-chart-line text-blue-600"></i>
            </div>
            إحصائيات المشروع
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Progress Card -->
            <div class="text-center p-4 bg-blue-50 rounded-xl">
                <div class="w-16 h-16 mx-auto mb-3 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="ri-progress-line text-blue-600 text-2xl"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($project->progress ?? 0) }}%</div>
                <div class="text-sm text-gray-600">نسبة الإنجاز</div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-500"
                         style="width: {{ $project->progress ?? 0 }}%"></div>
                </div>
            </div>

            <!-- Budget Card -->
            <div class="text-center p-4 bg-green-50 rounded-xl">
                <div class="w-16 h-16 mx-auto mb-3 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="ri-money-dollar-box-line text-green-600 text-2xl"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($project->budget ?? 0) }}</div>
                <div class="text-sm text-gray-600">الميزانية (ريال)</div>
            </div>

            <!-- Status Card -->
            <div class="text-center p-4 
                @if($project->status === 'active') bg-green-50
                @elseif($project->status === 'planning') bg-yellow-50
                @elseif($project->status === 'completed') bg-gray-50
                @else bg-red-50 @endif rounded-xl">
                <div class="w-16 h-16 mx-auto mb-3 
                    @if($project->status === 'active') bg-green-100
                    @elseif($project->status === 'planning') bg-yellow-100
                    @elseif($project->status === 'completed') bg-gray-100
                    @else bg-red-100 @endif rounded-full flex items-center justify-center">
                    <i class="ri-pulse-line 
                        @if($project->status === 'active') text-green-600
                        @elseif($project->status === 'planning') text-yellow-600
                        @elseif($project->status === 'completed') text-gray-600
                        @else text-red-600 @endif text-2xl"></i>
                </div>
                <div class="text-lg font-bold text-gray-900 mb-1">{{ $project->status_label ?? 'غير محدد' }}</div>
                <div class="text-sm text-gray-600">حالة المشروع</div>
            </div>

            <!-- Manager Card -->
            <div class="text-center p-4 bg-purple-50 rounded-xl">
                <div class="w-16 h-16 mx-auto mb-3 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="ri-user-crown-line text-purple-600 text-2xl"></i>
                </div>
                <div class="text-lg font-bold text-gray-900 mb-1">
                    {{ $project->projectManager->name ?? $project->project_manager ?? 'غير محدد' }}
                </div>
                <div class="text-sm text-gray-600">مدير المشروع</div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

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
                        <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            placeholder="أدخل اسم المشروع" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_number" class="block text-sm font-medium text-gray-700 mb-2">رقم المشروع *</label>
                        <input type="text" id="project_number" name="project_number" value="{{ old('project_number', $project->project_number) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('project_number') border-red-500 @enderror"
                            placeholder="رقم المشروع" required>
                        @error('project_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">قيمة المشروع (ر.س) *</label>
                        <input type="number" id="budget" name="budget" value="{{ old('budget', $project->budget) }}" step="0.01" min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('budget') border-red-500 @enderror"
                            placeholder="0.00" required>
                        @error('budget')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="government_entity" class="block text-sm font-medium text-gray-700 mb-2">الجهة الحكومية</label>
                        <input type="text" id="government_entity" name="government_entity" value="{{ old('government_entity', $project->government_entity) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('government_entity') border-red-500 @enderror"
                            placeholder="اسم الجهة الحكومية">
                        @error('government_entity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="consulting_office" class="block text-sm font-medium text-gray-700 mb-2">مكتب الاستشاري</label>
                        <input type="text" id="consulting_office" name="consulting_office" value="{{ old('consulting_office', $project->consulting_office) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('consulting_office') border-red-500 @enderror"
                            placeholder="اسم المكتب الاستشاري">
                        @error('consulting_office')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_scope" class="block text-sm font-medium text-gray-700 mb-2">نطاق عمل المشروع</label>
                        <input type="text" id="project_scope" name="project_scope" value="{{ old('project_scope', $project->project_scope) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('project_scope') border-red-500 @enderror"
                            placeholder="نطاق عمل المشروع">
                        @error('project_scope')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Project Details -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-settings-line text-green-600"></i>
                    </div>
                    تفاصيل المشروع
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">حالة المشروع *</label>
                        <select id="status" name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                            required>
                            <option value="">اختر حالة المشروع</option>
                            <option value="planning" {{ old('status', $project->status) === 'planning' ? 'selected' : '' }}>التخطيط</option>
                            <option value="active" {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="on_hold" {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>متوقف مؤقتاً</option>
                            <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ old('status', $project->status) === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="progress" class="block text-sm font-medium text-gray-700 mb-2">نسبة الإنجاز (%)</label>
                        <input type="number" id="progress" name="progress" value="{{ old('progress', $project->progress) }}" min="0" max="100"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('progress') border-red-500 @enderror"
                            placeholder="0">
                        @error('progress')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النهاية المتوقع</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_manager" class="block text-sm font-medium text-gray-700 mb-2">مدير المشروع</label>
                        <input type="text" id="project_manager" name="project_manager" value="{{ old('project_manager', $project->project_manager) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('project_manager') border-red-500 @enderror"
                            placeholder="اسم مدير المشروع">
                        @error('project_manager')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">موقع المشروع</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $project->location) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
                            placeholder="موقع المشروع">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="ri-file-text-line text-purple-600"></i>
                    </div>
                    الوصف والملاحظات
                </h3>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف المشروع</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="وصف تفصيلي للمشروع...">{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('projects.index') }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700 transition-all duration-200 flex items-center gap-2">
                    <i class="ri-save-line"></i>
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
