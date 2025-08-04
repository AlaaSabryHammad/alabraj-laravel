@extends('layouts.app')

@section('title', 'تعديل المشروع: ' . $project->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50" dir="rtl">
    <!-- Modern Header with Project Branding -->
    <div class="bg-white/80 backdrop-blur-lg border-b border-white/20 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button onclick="window.history.back()"
                        class="p-2 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white hover:shadow-lg transition-all duration-300">
                        <i class="ri-arrow-right-line text-xl"></i>
                    </button>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            {{ substr($project->name, 0, 2) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-800 bg-clip-text text-transparent">
                                تحرير المشروع
                            </h1>
                            <p class="text-gray-600 font-medium">{{ $project->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('projects.show', $project) }}"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-medium shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                        <i class="ri-eye-line"></i>
                        عرض المشروع
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Project Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Progress Card -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                        <i class="ri-progress-line text-white text-lg"></i>
                    </div>
                    <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">نسبة الإنجاز</span>
                </div>
                <div class="text-3xl font-bold text-gray-800 mb-2">{{ number_format($project->progress ?? 0) }}%</div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500"
                         style="width: {{ $project->progress ?? 0 }}%"></div>
                </div>
            </div>

            <!-- Budget Card -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 flex items-center justify-center">
                        <i class="ri-money-dollar-box-line text-white text-lg"></i>
                    </div>
                    <span class="text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">الميزانية</span>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ number_format($project->budget ?? 0) }}</div>
                <div class="text-sm text-gray-600 mt-1">ريال سعودي</div>
            </div>

            <!-- Status Card -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-r
                        @if($project->status === 'active') from-green-500 to-green-600
                        @elseif($project->status === 'planning') from-yellow-500 to-yellow-600
                        @elseif($project->status === 'completed') from-gray-500 to-gray-600
                        @else from-red-500 to-red-600 @endif
                        flex items-center justify-center">
                        <i class="ri-pulse-line text-white text-lg"></i>
                    </div>
                    <span class="text-sm font-medium
                        @if($project->status === 'active') text-green-600 bg-green-50
                        @elseif($project->status === 'planning') text-yellow-600 bg-yellow-50
                        @elseif($project->status === 'completed') text-gray-600 bg-gray-50
                        @else text-red-600 bg-red-50 @endif
                        px-3 py-1 rounded-full">الحالة</span>
                </div>
                <div class="text-xl font-bold text-gray-800">{{ $project->status_label ?? 'غير محدد' }}</div>
            </div>

            <!-- Manager Card -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center">
                        <i class="ri-user-crown-line text-white text-lg"></i>
                    </div>
                    <span class="text-sm font-medium text-purple-600 bg-purple-50 px-3 py-1 rounded-full">مدير المشروع</span>
                </div>
                <div class="text-lg font-semibold text-gray-800">
                    {{ $project->projectManager->name ?? $project->project_manager ?? 'غير محدد' }}
                </div>
            </div>
        </div>
                                @if ($project->status === 'active')
                                    نشط
                                @elseif($project->status === 'planning')
                                    التخطيط
                                @elseif($project->status === 'completed')
                                    مكتمل
                                @else
                                    متوقف
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">الحالة الحالية</div>
                        </div>
                        <div class="text-center">
                            @php
                                $daysRemaining = $project->end_date
                                    ? \Carbon\Carbon::now()->diffInDays(
                                        \Carbon\Carbon::parse($project->end_date),
                                        false,
                                    )
                                    : null;
                            @endphp
                            <div
                                class="text-2xl font-bold {{ $daysRemaining && $daysRemaining < 0 ? 'text-red-600' : 'text-orange-600' }}">
                                @if ($daysRemaining !== null)
                                    {{ $daysRemaining < 0 ? 'متأخر ' . number_format(abs($daysRemaining), 0) : number_format($daysRemaining, 0) }}
                                    يوم
                                @else
                                    غير محدد
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">المتبقي</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- آخر التحديثات -->
            <div class="bg-white rounded-xl shadow-sm border mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ri-time-line text-blue-600"></i>
                        آخر التحديثات
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Recent Extracts -->
                    @if ($project->projectExtracts && $project->projectExtracts->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center gap-2">
                                <i class="ri-file-list-3-line text-green-600"></i>
                                المستخلصات الأخيرة
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-medium">
                                    {{ $project->projectExtracts->count() }}
                                </span>
                            </h3>
                            <div class="space-y-2">
                                @foreach ($project->projectExtracts->sortByDesc('created_at')->take(3) as $extract)
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-2 h-2 rounded-full
                                                @if ($extract->status === 'paid') bg-green-500
                                                @elseif($extract->status === 'approved') bg-blue-500
                                                @elseif($extract->status === 'submitted') bg-yellow-500
                                                @else bg-gray-400 @endif">
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $extract->extract_number }}</p>
                                                <p class="text-sm text-gray-600">
                                                    {{ $extract->extract_date->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-medium text-gray-900">
                                                {{ number_format($extract->total_amount, 2) }} ر.س</p>
                                            <span
                                                class="text-xs px-2 py-1 rounded-full
                                                @if ($extract->status === 'paid') bg-green-100 text-green-700
                                                @elseif($extract->status === 'approved') bg-blue-100 text-blue-700
                                                @elseif($extract->status === 'submitted') bg-yellow-100 text-yellow-700
                                                @else bg-gray-100 text-gray-700 @endif">
                                                {{ $extract->status_display }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($project->projectExtracts->count() > 3)
                                <div class="mt-3 text-center">
                                    <a href="{{ route('projects.show', $project) }}#extracts"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center gap-1">
                                        <i class="ri-arrow-left-line"></i>
                                        عرض جميع المستخلصات ({{ $project->projectExtracts->count() }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mb-6">
                            <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center gap-2">
                                <i class="ri-file-list-3-line text-gray-400"></i>
                                المستخلصات
                            </h3>
                            <div class="text-center p-6 bg-gray-50 rounded-lg">
                                <i class="ri-file-list-3-line text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500 mb-3">لا توجد مستخلصات بعد</p>
                                <a href="{{ route('projects.extract.create', $project) }}" target="_blank"
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                    <i class="ri-add-line"></i>
                                    إنشاء أول مستخلص
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Project Timeline -->
                    <div class="mb-6">
                        <h3 class="text-md font-medium text-gray-800 mb-3 flex items-center gap-2">
                            <i class="ri-history-line text-purple-600"></i>
                            الجدول الزمني
                        </h3>
                        <div class="space-y-3">
                            <!-- Project Creation -->
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">تم إنشاء المشروع</p>
                                    <p class="text-sm text-gray-600">{{ $project->created_at->format('d/m/Y - H:i') }}</p>
                                </div>
                                <i class="ri-add-circle-line text-gray-500"></i>
                            </div>

                            <!-- Last Update -->
                            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">آخر تحديث</p>
                                    <p class="text-sm text-gray-600">{{ $project->updated_at->format('d/m/Y - H:i') }}</p>
                                </div>
                                <i class="ri-edit-line text-blue-500"></i>
                            </div>

                            @if ($project->start_date)
                                <!-- Start Date -->
                                <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">تاريخ البداية</p>
                                        <p class="text-sm text-gray-600">{{ $project->start_date->format('d/m/Y') }}</p>
                                    </div>
                                    <i class="ri-play-circle-line text-green-500"></i>
                                </div>
                            @endif

                            @if ($project->end_date)
                                <!-- End Date -->
                                <div
                                    class="flex items-center gap-3 p-3
                                    @if ($project->end_date->isPast()) bg-red-50 @else bg-orange-50 @endif rounded-lg">
                                    <div
                                        class="w-2 h-2
                                        @if ($project->end_date->isPast()) bg-red-500 @else bg-orange-500 @endif rounded-full">
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">
                                            @if ($project->end_date->isPast())
                                                انتهى المشروع
                                            @else
                                                تاريخ النهاية المتوقع
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-600">{{ $project->end_date->format('d/m/Y') }}</p>
                                    </div>
                                    <i
                                        class="@if ($project->end_date->isPast()) ri-stop-circle-line text-red-500 @else ri-time-line text-orange-500 @endif"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="flex items-center justify-center mb-2">
                                <i class="ri-list-check-2 text-2xl text-blue-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-blue-600">{{ $project->projectItems->count() }}</div>
                            <div class="text-sm text-blue-600">البنود</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="flex items-center justify-center mb-2">
                                <i class="ri-file-list-3-line text-2xl text-green-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-green-600">{{ $project->projectExtracts->count() }}</div>
                            <div class="text-sm text-green-600">المستخلصات</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="flex items-center justify-center mb-2">
                                <i class="ri-image-line text-2xl text-purple-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-purple-600">{{ $project->projectImages->count() }}</div>
                            <div class="text-sm text-purple-600">الصور</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                            <div class="flex items-center justify-center mb-2">
                                <i class="ri-calendar-line text-2xl text-orange-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-orange-600">
                                {{ $project->created_at->diffInDays() }}
                            </div>
                            <div class="text-sm text-orange-600">يوم منذ الإنشاء</div>
                        </div>
                    </div>

                    <!-- Recent Activity Summary -->
                    @if ($project->projectExtracts->count() > 0)
                        <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-green-50 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">ملخص الأنشطة الحديثة</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        آخر مستخلص:
                                        {{ $project->projectExtracts->sortByDesc('created_at')->first()->extract_number }}
                                        ({{ $project->projectExtracts->sortByDesc('created_at')->first()->created_at->diffForHumans() }})
                                    </p>
                                </div>
                                <div class="text-left">
                                    <p class="text-lg font-bold text-blue-600">
                                        {{ number_format($project->projectExtracts->where('status', '!=', 'draft')->sum('total_amount'), 0) }}
                                        ر.س
                                    </p>
                                    <p class="text-sm text-gray-600">إجمالي المستخلصات</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        <!-- Modern Edit Form -->
        <div class="bg-white/70 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <i class="ri-edit-box-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">تحرير بيانات المشروع</h2>
                        <p class="text-blue-100 mt-1">قم بتحديث معلومات المشروع وحفظ التغييرات</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="{{ route('projects.update', $project) }}" method="POST"
                  enctype="multipart/form-data"
                  class="p-8"
                  id="projectEditForm">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                            <i class="ri-information-line text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">المعلومات الأساسية</h3>
                        <div class="flex-1 h-px bg-gradient-to-r from-blue-200 to-transparent"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Project Name -->
                        <div class="space-y-3">
                            <label for="name" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <i class="ri-file-text-line text-blue-600"></i>
                                اسم المشروع
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', $project->name) }}"
                                       class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 bg-white/50 backdrop-blur-sm @error('name') border-red-300 @enderror"
                                       placeholder="أدخل اسم المشروع"
                                       required>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <i class="ri-building-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('name')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Project Manager -->
                        <div class="space-y-3">
                            <label for="project_manager_id" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <i class="ri-user-crown-line text-purple-600"></i>
                                مدير المشروع
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="project_manager_id"
                                        id="project_manager_id"
                                        class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/50 backdrop-blur-sm @error('project_manager_id') border-red-300 @enderror appearance-none"
                                        required>
                                    <option value="">-- اختر مدير المشروع --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ (old('project_manager_id', $project->project_manager_id) == $employee->id) ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <i class="ri-arrow-down-s-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('project_manager_id')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="space-y-3">
                            <label for="location" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <i class="ri-map-pin-line text-emerald-600"></i>
                                موقع المشروع
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       name="location"
                                       id="location"
                                       value="{{ old('location', $project->location) }}"
                                       class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-300 bg-white/50 backdrop-blur-sm @error('location') border-red-300 @enderror"
                                       placeholder="أدخل موقع المشروع"
                                       required>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <i class="ri-map-2-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('location')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="space-y-3">
                            <label for="status" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <i class="ri-pulse-line text-orange-600"></i>
                                حالة المشروع
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="status"
                                        id="status"
                                        class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 bg-white/50 backdrop-blur-sm @error('status') border-red-300 @enderror appearance-none"
                                        required>
                                    <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>في التخطيط</option>
                                    <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>معلق</option>
                                    <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                    <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <i class="ri-arrow-down-s-line text-gray-400"></i>
                                </div>
                            </div>
                            @error('status')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Project Details Section -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 flex items-center justify-center">
                            <i class="ri-calendar-line text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">تفاصيل المشروع</h3>
                        <div class="flex-1 h-px bg-gradient-to-r from-emerald-200 to-transparent"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6">
                        <!-- Start Date -->
                        <div class="space-y-3">
                            <label for="start_date" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <i class="ri-calendar-event-line text-green-600"></i>
                                تاريخ البداية
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   name="start_date"
                                   id="start_date"
                                   value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 bg-white/50 backdrop-blur-sm @error('start_date') border-red-300 @enderror"
                                   required>
                            @error('start_date')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div class="space-y-3">
                            <label for="end_date" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <i class="ri-calendar-check-line text-red-600"></i>
                                تاريخ النهاية
                            </label>
                            <input type="date"
                                   name="end_date"
                                   id="end_date"
                                   value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all duration-300 bg-white/50 backdrop-blur-sm @error('end_date') border-red-300 @enderror">
                            @error('end_date')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Budget -->
                        <div class="space-y-3">
                            <label for="budget" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <i class="ri-money-dollar-circle-line text-yellow-600"></i>
                                الميزانية
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number"
                                       name="budget"
                                       id="budget"
                                       value="{{ old('budget', $project->budget) }}"
                                       class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 transition-all duration-300 bg-white/50 backdrop-blur-sm @error('budget') border-red-300 @enderror"
                                       placeholder="0"
                                       min="0"
                                       step="0.01"
                                       required>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <span class="text-gray-400 text-sm">ر.س</span>
                                </div>
                            </div>
                            @error('budget')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Progress -->
                        <div class="space-y-3">
                            <label for="progress" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <i class="ri-progress-3-line text-indigo-600"></i>
                                نسبة الإنجاز
                            </label>
                            <div class="relative">
                                <input type="number"
                                       name="progress"
                                       id="progress"
                                       value="{{ old('progress', $project->progress) }}"
                                       class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 bg-white/50 backdrop-blur-sm @error('progress') border-red-300 @enderror"
                                       placeholder="0"
                                       min="0"
                                       max="100">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <span class="text-gray-400 text-sm">%</span>
                                </div>
                            </div>
                            @error('progress')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center">
                            <i class="ri-file-text-line text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">وصف المشروع</h3>
                        <div class="flex-1 h-px bg-gradient-to-r from-purple-200 to-transparent"></div>
                    </div>

                    <div class="space-y-3">
                        <label for="description" class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <i class="ri-draft-line text-purple-600"></i>
                            الوصف التفصيلي
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="6"
                                  class="w-full px-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/50 backdrop-blur-sm @error('description') border-red-300 @enderror resize-none"
                                  placeholder="أدخل وصفاً تفصيلياً للمشروع...">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-gray-200">
                    <div class="flex items-center gap-3">
                        <button type="button"
                                onclick="window.history.back()"
                                class="px-6 py-3 rounded-2xl border-2 border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-all duration-300 flex items-center gap-2">
                            <i class="ri-arrow-go-back-line"></i>
                            إلغاء
                        </button>

                        <a href="{{ route('projects.show', $project) }}"
                           class="px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                            <i class="ri-eye-line"></i>
                            معاينة
                        </a>
                    </div>

                    <button type="submit"
                            class="px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center gap-3 text-lg">
                        <i class="ri-save-line text-xl"></i>
                        حفظ التغييرات
                        <div class="w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
                    </button>
                </div>
            </form>
        </div>

                <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Project Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المشروع <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name', $project->name) }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Project Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">المعلومات الإضافية
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="project_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    رقم المشروع
                                </label>
                                <input type="text" name="project_number" id="project_number"
                                    value="{{ old('project_number', $project->project_number) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('project_number') border-red-300 @enderror"
                                    placeholder="الرقم المرجعي للمشروع">
                                @error('project_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="government_entity" class="block text-sm font-medium text-gray-700 mb-2">
                                    الجهة الحكومية
                                </label>
                                <input type="text" name="government_entity" id="government_entity"
                                    value="{{ old('government_entity', $project->government_entity) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('government_entity') border-red-300 @enderror"
                                    placeholder="الجهة الحكومية المشرفة">
                                @error('government_entity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="consulting_office" class="block text-sm font-medium text-gray-700 mb-2">
                                    مكتب الاستشاري
                                </label>
                                <input type="text" name="consulting_office" id="consulting_office"
                                    value="{{ old('consulting_office', $project->consulting_office) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('consulting_office') border-red-300 @enderror"
                                    placeholder="اسم المكتب الاستشاري">
                                @error('consulting_office')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="project_scope" class="block text-sm font-medium text-gray-700 mb-2">
                                    نطاق العمل
                                </label>
                                <input type="text" name="project_scope" id="project_scope"
                                    value="{{ old('project_scope', $project->project_scope) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('project_scope') border-red-300 @enderror"
                                    placeholder="نطاق ومجال العمل">
                                @error('project_scope')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Project Manager and Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="project_manager_id" class="block text-sm font-medium text-gray-700 mb-2">
                                مدير المشروع <span class="text-red-500">*</span>
                            </label>
                            <select name="project_manager_id" id="project_manager_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('project_manager_id') border-red-300 @enderror"
                                required>
                                <option value="">-- اختر مدير المشروع --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ (old('project_manager_id', $project->project_manager_id) == $employee->id) ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_manager_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                موقع المشروع <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location" id="location"
                                value="{{ old('location', $project->location) }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('location') border-red-300 @enderror"
                                required>
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates, Budget, and Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ البداية <span class="text-red-500">*</span>
                                @if($project->start_date)
                                    <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded ml-2">
                                        <i class="ri-database-2-line text-xs"></i>
                                        محمل من قاعدة البيانات
                                    </span>
                                @endif
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_date') border-red-300 @enderror"
                                required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الانتهاء المتوقع
                                @if($project->end_date)
                                    <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded ml-2">
                                        <i class="ri-database-2-line text-xs"></i>
                                        محمل من قاعدة البيانات
                                    </span>
                                @endif
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('end_date') border-red-300 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">
                                الميزانية (ر.س) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="budget" id="budget"
                                value="{{ old('budget', $project->budget) }}" step="0.01" min="0"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('budget') border-red-300 @enderror"
                                required>
                            @error('budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                حالة المشروع <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-300 @enderror"
                                required>
                                <option value="planning"
                                    {{ old('status', $project->status) === 'planning' ? 'selected' : '' }}>التخطيط</option>
                                <option value="active"
                                    {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="on_hold"
                                    {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>متوقف</option>
                                <option value="completed"
                                    {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="cancelled"
                                    {{ old('status', $project->status) === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Progress -->
                    <div>
                        <label for="progress" class="block text-sm font-medium text-gray-700 mb-2">
                            نسبة الإنجاز (%)
                        </label>
                        <div class="flex items-center gap-4">
                            <input type="range" name="progress" id="progress" min="0" max="100"
                                value="{{ old('progress', $project->progress) }}"
                                class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <span id="progress-value" class="text-sm font-medium text-gray-700 min-w-[3rem]">
                                {{ old('progress', $project->progress) }}%
                            </span>
                        </div>
                        @error('progress')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            وصف المشروع
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-300 @enderror"
                            placeholder="وصف تفصيلي للمشروع، أهدافه، ومتطلباته">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Images Management -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">إدارة صور المشروع
                        </h3>

                        <!-- Current Images -->
                        @if ($project->projectImages && $project->projectImages->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-700 mb-3">الصور الحالية</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                    @foreach ($project->projectImages as $image)
                                        <div class="relative group" id="image-{{ $image->id }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                alt="{{ $image->name ?? 'صورة المشروع' }}"
                                                class="w-full h-24 object-cover rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-all">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-all flex items-center justify-center">
                                                <button type="button" onclick="deleteImage({{ $image->id }})"
                                                    class="bg-red-500 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                                                    <i class="ri-delete-bin-line text-sm"></i>
                                                </button>
                                            </div>
                                            @if ($image->name)
                                                <p class="text-xs text-gray-600 mt-1 text-center truncate">
                                                    {{ $image->name }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Add New Images -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-3">إضافة صور جديدة</h4>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                <div class="text-center">
                                    <i class="ri-image-add-line text-4xl text-gray-400 mb-4"></i>
                                    <div class="space-y-2">
                                        <label for="new_images" class="cursor-pointer">
                                            <span
                                                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors inline-block">
                                                اختر الصور
                                            </span>
                                            <input type="file" id="new_images" name="new_images[]" multiple
                                                accept="image/*" class="hidden" onchange="previewNewImages(this)">
                                        </label>
                                        <p class="text-sm text-gray-500">يمكنك اختيار عدة صور (PNG, JPG, JPEG)</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Preview new images -->
                            <div id="new-images-preview"
                                class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4 hidden"></div>
                        </div>
                    </div>

                    <!-- Project Items Management -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">إدارة بنود المشروع
                        </h3>

                        <!-- Tax Rate Setting -->
                        <div class="mb-6">
                            <label for="edit_tax_rate" class="block text-sm font-medium text-gray-700 mb-2">معدل الضريبة
                                (%)</label>
                            <input type="number" id="edit_tax_rate" value="15" min="0" max="100"
                                step="0.1"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                oninput="calculateAllTotals()">
                            <span class="text-sm text-gray-600 mr-2">القيمة الافتراضية: 15%</span>
                        </div>

                        <!-- Current Items Display -->
                        @if ($project->projectItems && $project->projectItems->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-700 mb-3">البنود الحالية</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[800px] bg-white rounded-lg shadow-sm border">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">اسم
                                                    البند</th>
                                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">الكمية
                                                </th>
                                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">الوحدة
                                                </th>
                                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">السعر
                                                    الإفرادي</th>
                                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">السعر
                                                    الإجمالي</th>
                                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">مع
                                                    الضريبة</th>
                                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">
                                                    الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="current-items-body">
                                            @foreach ($project->projectItems as $index => $item)
                                                <tr class="existing-item-row border-b border-gray-200"
                                                    data-item-id="{{ $item->id }}">
                                                    <td class="px-4 py-3">
                                                        <input type="text"
                                                            name="existing_items[{{ $item->id }}][name]"
                                                            value="{{ $item->name }}"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <input type="number"
                                                            name="existing_items[{{ $item->id }}][quantity]"
                                                            value="{{ $item->quantity }}" min="0" step="0.1"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center existing-quantity-input"
                                                            oninput="calculateRowTotal(this.closest('tr'))">
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <select name="existing_items[{{ $item->id }}][unit]"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                            <option value="">اختر الوحدة</option>
                                                            <option value="متر"
                                                                {{ $item->unit == 'متر' ? 'selected' : '' }}>متر</option>
                                                            <option value="متر مربع"
                                                                {{ $item->unit == 'متر مربع' ? 'selected' : '' }}>متر مربع
                                                            </option>
                                                            <option value="متر مكعب"
                                                                {{ $item->unit == 'متر مكعب' ? 'selected' : '' }}>متر مكعب
                                                            </option>
                                                            <option value="كيلوجرام"
                                                                {{ $item->unit == 'كيلوجرام' ? 'selected' : '' }}>كيلوجرام
                                                            </option>
                                                            <option value="طن"
                                                                {{ $item->unit == 'طن' ? 'selected' : '' }}>طن</option>
                                                            <option value="قطعة"
                                                                {{ $item->unit == 'قطعة' ? 'selected' : '' }}>قطعة</option>
                                                            <option value="مجموعة"
                                                                {{ $item->unit == 'مجموعة' ? 'selected' : '' }}>مجموعة
                                                            </option>
                                                            <option value="لتر"
                                                                {{ $item->unit == 'لتر' ? 'selected' : '' }}>لتر</option>
                                                            <option value="ساعة"
                                                                {{ $item->unit == 'ساعة' ? 'selected' : '' }}>ساعة</option>
                                                            <option value="يوم"
                                                                {{ $item->unit == 'يوم' ? 'selected' : '' }}>يوم</option>
                                                            <option value="أخرى"
                                                                {{ $item->unit == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <input type="number"
                                                            name="existing_items[{{ $item->id }}][unit_price]"
                                                            value="{{ $item->unit_price }}" min="0"
                                                            step="0.01"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center existing-unit-price-input"
                                                            oninput="calculateRowTotal(this.closest('tr'))">
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <input type="text"
                                                            name="existing_items[{{ $item->id }}][total_price]"
                                                            value="{{ number_format($item->total_price, 2) }}"
                                                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center existing-total-price-display"
                                                            readonly>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <input type="text"
                                                            name="existing_items[{{ $item->id }}][total_with_tax]"
                                                            value="{{ number_format($item->total_with_tax, 2) }}"
                                                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center existing-total-with-tax-display"
                                                            readonly>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        <button type="button"
                                                            onclick="deleteExistingItem({{ $item->id }}, this)"
                                                            class="text-red-600 hover:text-red-800 transition-colors p-1">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Add New Items Section -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-3">إضافة بنود جديدة</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[800px] bg-white rounded-lg shadow-sm border">
                                    <thead class="bg-blue-50">
                                        <tr>
                                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">اسم البند
                                            </th>
                                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">الكمية</th>
                                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">الوحدة</th>
                                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">السعر
                                                الإفرادي</th>
                                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">السعر
                                                الإجمالي</th>
                                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">مع الضريبة
                                            </th>
                                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">الإجراءات
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="new-items-table-body">
                                        <!-- Initial empty row -->
                                        <tr class="new-item-row border-b border-gray-200">
                                            <td class="px-4 py-3">
                                                <input type="text" name="new_items[0][name]"
                                                    placeholder="اسم البند الجديد"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" name="new_items[0][quantity]" placeholder="0"
                                                    min="0" step="0.1"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center new-quantity-input"
                                                    oninput="calculateRowTotal(this.closest('tr'))">
                                            </td>
                                            <td class="px-4 py-3">
                                                <select name="new_items[0][unit]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    <option value="">اختر الوحدة</option>
                                                    <option value="متر">متر</option>
                                                    <option value="متر مربع">متر مربع</option>
                                                    <option value="متر مكعب">متر مكعب</option>
                                                    <option value="كيلوجرام">كيلوجرام</option>
                                                    <option value="طن">طن</option>
                                                    <option value="قطعة">قطعة</option>
                                                    <option value="مجموعة">مجموعة</option>
                                                    <option value="لتر">لتر</option>
                                                    <option value="ساعة">ساعة</option>
                                                    <option value="يوم">يوم</option>
                                                    <option value="أخرى">أخرى</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" name="new_items[0][unit_price]" placeholder="0.00"
                                                    min="0" step="0.01"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center new-unit-price-input"
                                                    oninput="calculateRowTotal(this.closest('tr'))">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" name="new_items[0][total_price]"
                                                    class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center new-total-price-display"
                                                    readonly placeholder="0.00">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" name="new_items[0][total_with_tax]"
                                                    class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center new-total-with-tax-display"
                                                    readonly placeholder="0.00">
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button" onclick="removeNewItemRow(this)"
                                                    class="text-red-600 hover:text-red-800 transition-colors p-1">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Add New Item Button -->
                            <div class="mt-4">
                                <button type="button" onclick="addNewItemRow()"
                                    class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors flex items-center gap-2">
                                    <i class="ri-add-line"></i>
                                    إضافة بند جديد
                                </button>
                            </div>
                        </div>

                        <!-- Totals Summary -->
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column: Summary -->
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">الإجمالي قبل الضريبة:</span>
                                        <span id="edit-subtotal-display" class="font-medium text-gray-900">0.00 ر.س</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">الضريبة (<span
                                                id="edit-tax-rate-display">15</span>%):</span>
                                        <span id="edit-tax-amount-display" class="font-medium text-gray-900">0.00
                                            ر.س</span>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-gray-200 pt-3">
                                        <span class="text-lg font-semibold text-gray-900">الإجمالي النهائي:</span>
                                        <span id="edit-final-total-display" class="text-xl font-bold text-blue-600">0.00
                                            ر.س</span>
                                    </div>
                                </div>

                                <!-- Right Column: Amount in Words -->
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-blue-900 mb-2">المبلغ بالحروف:</h4>
                                    <p id="edit-amount-in-words" class="text-blue-800 text-sm leading-relaxed">صفر ريال
                                        سعودي</p>
                                </div>
                            </div>

                            <!-- Hidden inputs for form submission -->
                            <input type="hidden" id="edit-subtotal-input" name="subtotal" value="0">
                            <input type="hidden" id="edit-tax-amount-input" name="tax_amount" value="0">
                            <input type="hidden" id="edit-final-total-input" name="final_total" value="0">
                            <input type="hidden" id="edit-tax-rate-input" name="tax_rate" value="15">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('projects.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                                إلغاء
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeModernForm();
        });

        function initializeModernForm() {
            // Modern form enhancements
            addFormAnimations();
            setupProgressSync();
            setupDateValidation();
            setupFormSubmission();
        }

        function addFormAnimations() {
            // Add focus animations to inputs
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('transform', 'scale-[1.02]');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('transform', 'scale-[1.02]');
                });
            });

            // Add loading state to submit button
            const submitBtn = document.querySelector('button[type="submit"]');
            const form = document.getElementById('projectEditForm');

            form.addEventListener('submit', function() {
                submitBtn.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <span>جارٍ الحفظ...</span>
                    </div>
                `;
                submitBtn.disabled = true;
            });
        }

        function setupProgressSync() {
            const progressInput = document.getElementById('progress');
            const statusSelect = document.getElementById('status');

            progressInput.addEventListener('input', function() {
                const value = parseInt(this.value);

                // Auto-suggest status based on progress
                if (value === 0) {
                    statusSelect.value = 'planning';
                } else if (value > 0 && value < 100) {
                    statusSelect.value = 'active';
                } else if (value === 100) {
                    statusSelect.value = 'completed';
                }

                // Update visual feedback
                updateProgressVisuals(value);
            });
        }

        function updateProgressVisuals(value) {
            // Update progress bar in the overview
            const progressBar = document.querySelector('.bg-gradient-to-r.from-blue-500.to-blue-600');
            if (progressBar) {
                progressBar.style.width = value + '%';
            }

            // Update progress text
            const progressText = document.querySelector('.text-3xl.font-bold.text-gray-800');
            if (progressText && progressText.textContent.includes('%')) {
                progressText.textContent = value + '%';
            }
        }

        function setupDateValidation() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');

            startDate.addEventListener('change', function() {
                endDate.min = this.value;
                if (endDate.value && endDate.value < this.value) {
                    endDate.value = '';
                    showNotification('تاريخ النهاية يجب أن يكون بعد تاريخ البداية', 'warning');
                }
            });

            endDate.addEventListener('change', function() {
                if (startDate.value && this.value < startDate.value) {
                    this.value = '';
                    showNotification('تاريخ النهاية يجب أن يكون بعد تاريخ البداية', 'error');
                }
            });
        }

        function setupFormSubmission() {
            const form = document.getElementById('projectEditForm');

            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    showNotification('يرجى التحقق من جميع الحقول المطلوبة', 'error');
                    return false;
                }

                showNotification('جارٍ حفظ التغييرات...', 'info');
            });
        }

        function validateForm() {
            const requiredFields = document.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500', 'shake');
                    setTimeout(() => field.classList.remove('shake'), 500);
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            return isValid;
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-2xl shadow-lg transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-white' :
                'bg-blue-500 text-white'
            }`;

            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="ri-${type === 'success' ? 'check' : type === 'error' ? 'error-warning' : type === 'warning' ? 'alert' : 'information'}-line text-xl"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
            .shake {
                animation: shake 0.5s ease-in-out;
            }

            /* Custom scrollbar */
            .overflow-y-auto::-webkit-scrollbar {
                width: 6px;
            }
            .overflow-y-auto::-webkit-scrollbar-track {
                background: rgba(0,0,0,0.1);
                border-radius: 3px;
            }
            .overflow-y-auto::-webkit-scrollbar-thumb {
                background: rgba(59,130,246,0.5);
                border-radius: 3px;
            }
            .overflow-y-auto::-webkit-scrollbar-thumb:hover {
                background: rgba(59,130,246,0.7);
            }
        `;
        document.head.appendChild(style);
    </script>
</div>
@endsection
