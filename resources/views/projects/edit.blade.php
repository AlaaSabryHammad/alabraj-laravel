@extends('layouts.app')

@section('title', 'تعديل المشروع: ' . $project->name)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">تعديل المشروع</h1>
                    <p class="text-gray-600 mt-1">{{ $project->name }}</p>
                </div>
            </div>

            <!-- Header Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.show', $project) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors flex items-center gap-2">
                    <i class="ri-eye-line"></i>
                    عرض التفاصيل
                </a>
            </div>
        </div>

        <div class="max-w-4xl">
            <!-- Project Status Card -->
            <div class="bg-white rounded-xl shadow-sm border mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">حالة المشروع</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($project->progress) }}%</div>
                            <div class="text-sm text-gray-600">نسبة الإنجاز</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ number_format($project->budget, 0) }} ر.س
                            </div>
                            <div class="text-sm text-gray-600">الميزانية الإجمالية</div>
                        </div>
                        <div class="text-center">
                            <div
                                class="text-2xl font-bold
                            @if ($project->status === 'active') text-green-600
                            @elseif($project->status === 'planning') text-blue-600
                            @elseif($project->status === 'completed') text-gray-600
                            @else text-red-600 @endif">
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

            <!-- Edit Form -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">تعديل بيانات المشروع</h2>
                </div>

                <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Project Name and Client -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
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

                        <div>
                            <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم العميل <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="client_name" id="client_name"
                                value="{{ old('client_name', $project->client_name) }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('client_name') border-red-300 @enderror"
                                required>
                            @error('client_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
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
                            <label for="project_manager" class="block text-sm font-medium text-gray-700 mb-2">
                                مدير المشروع <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="project_manager" id="project_manager"
                                value="{{ old('project_manager', $project->project_manager) }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('project_manager') border-red-300 @enderror"
                                required>
                            @error('project_manager')
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
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', $project->start_date) }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_date') border-red-300 @enderror"
                                required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الانتهاء المتوقع
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', $project->end_date) }}"
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
                                <option value="completed"
                                    {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="on_hold"
                                    {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>متوقف</option>
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
                            </a>
                            <a href="{{ route('projects.show', $project) }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-eye-line"></i>
                                عرض التفاصيل
                            </a>
                        </div>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-save-line"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let newItemIndex = 1;

        document.addEventListener('DOMContentLoaded', function() {
            // --- Main Setup ---
            initializePage();
            attachEventListeners();
        });

        function initializePage() {
            // Add one empty row for new items on page load for immediate use.
            addNewItemRow();
            // Recalculate all totals on page load to format existing items correctly.
            calculateAllTotals();
        }

        function attachEventListeners() {
            // --- General Form Listeners ---
            document.getElementById('progress').addEventListener('input', function() {
                document.getElementById('progress-value').textContent = this.value + '%';
            });

            document.getElementById('start_date').addEventListener('change', function() {
                const endDateInput = document.getElementById('end_date');
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = '';
                }
            });

            document.getElementById('progress').addEventListener('change', function() {
                const statusSelect = document.getElementById('status');
                if (this.value == 100 && statusSelect.value !== 'completed') {
                    if (confirm('تم الوصول إلى 100% من الإنجاز. هل تريد تغيير حالة المشروع إلى "مكتمل"؟')) {
                        statusSelect.value = 'completed';
                    }
                }
            });

            document.getElementById('new_images').addEventListener('change', function() {
                previewNewImages(this);
            });

            // --- Project Items Listeners ---
            document.getElementById('add-new-item-btn').addEventListener('click', addNewItemRow);
            document.getElementById('edit_tax_rate').addEventListener('input', calculateAllTotals);

            // Add listeners to initially loaded existing items
            document.querySelectorAll('.existing-item-row').forEach(row => {
                row.querySelectorAll('.existing-quantity-input, .existing-unit-price-input').forEach(input => {
                    input.addEventListener('input', () => calculateRowTotal(row));
                });
            });
        }

        // --- Image Management ---
        function deleteImage(imageId) {
            if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) return;

            fetch(`/projects/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`image-${imageId}`).remove();
                        showMessage('تم حذف الصورة بنجاح', 'success');
                    } else {
                        showMessage(data.message || 'حدث خطأ في حذف الصورة', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('حدث خطأ في حذف الصورة', 'error');
                });
        }

        function previewNewImages(input) {
            const previewContainer = document.getElementById('new-images-preview');
            previewContainer.innerHTML = '';
            if (!input.files.length) {
                previewContainer.classList.add('hidden');
                return;
            }

            previewContainer.classList.remove('hidden');
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border-2 border-green-300">
                        <div class="absolute top-1 right-1"><span class="bg-green-500 text-white text-xs px-2 py-1 rounded">جديد</span></div>
                        <p class="text-xs text-gray-600 mt-1 text-center truncate">${file.name}</p>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function showMessage(message, type) {
            const div = document.createElement('div');
            div.className =
                `fixed top-4 right-4 p-4 rounded-lg z-50 ${type === 'success' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-red-100 text-red-700 border-red-300'}`;
            div.textContent = message;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 3000);
        }

        // --- Project Items Calculations ---
        function calculateRowTotal(row) {
            const quantity = parseFloat(row.querySelector('[name*="[quantity]"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('[name*="[unit_price]"]').value) || 0;
            const taxRate = parseFloat(document.getElementById('edit_tax_rate').value) || 0;

            const totalPrice = quantity * unitPrice;
            const totalWithTax = totalPrice * (1 + taxRate / 100);

            row.querySelector('[name*="[total_price]"]').value = totalPrice.toFixed(2);
            row.querySelector('[name*="[total_with_tax]"]').value = totalWithTax.toFixed(2);

            calculateAllTotals();
        }

        function calculateAllTotals() {
            let subtotal = 0;
            const taxRate = parseFloat(document.getElementById('edit_tax_rate').value) || 0;

            document.querySelectorAll('.existing-item-row, .new-item-row').forEach(row => {
                const totalPriceInput = row.querySelector('[name*="[total_price]"]');
                if (totalPriceInput && totalPriceInput.value) {
                    subtotal += parseFloat(totalPriceInput.value.replace(/,/g, '')) || 0;
                }
            });

            const taxAmount = subtotal * (taxRate / 100);
            const finalTotal = subtotal + taxAmount;

            document.getElementById('edit-subtotal-display').textContent = subtotal.toFixed(2) + ' ر.س';
            document.getElementById('edit-tax-amount-display').textContent = taxAmount.toFixed(2) + ' ر.س';
            document.getElementById('edit-final-total-display').textContent = finalTotal.toFixed(2) + ' ر.س';
            document.getElementById('edit-tax-rate-display').textContent = taxRate;

            document.getElementById('edit-subtotal-input').value = subtotal.toFixed(2);
            document.getElementById('edit-tax-amount-input').value = taxAmount.toFixed(2);
            document.getElementById('edit-final-total-input').value = finalTotal.toFixed(2);
            document.getElementById('edit-tax-rate-input').value = taxRate;

            document.getElementById('edit-amount-in-words').textContent = numberToArabicWords(finalTotal);
        }

        function addNewItemRow() {
            const tbody = document.getElementById('new-items-table-body');
            const newRow = document.createElement('tr');
            newRow.className = 'new-item-row border-b border-gray-200';
            const newRowHtml = `
                <td class="px-4 py-3">
                    <input type="text" name="new_items[${newItemIndex}][name]" placeholder="اسم البند الجديد"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="new_items[${newItemIndex}][quantity]" placeholder="0" min="0" step="0.1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center new-quantity-input">
                </td>
                <td class="px-4 py-3">
                    <select name="new_items[${newItemIndex}][unit]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                    <input type="number" name="new_items[${newItemIndex}][unit_price]" placeholder="0.00" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center new-unit-price-input">
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="new_items[${newItemIndex}][total_price]" readonly placeholder="0.00"
                           class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center">
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="new_items[${newItemIndex}][total_with_tax]" readonly placeholder="0.00"
                           class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center">
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button" class="text-red-600 hover:text-red-800 transition-colors p-1 remove-new-item-btn">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            `;
            newRow.innerHTML = newRowHtml;
            tbody.appendChild(newRow);

            // Add event listeners for the new row
            newRow.querySelectorAll('.new-quantity-input, .new-unit-price-input').forEach(input => {
                input.addEventListener('input', () => calculateRowTotal(newRow));
            });
            newRow.querySelector('.remove-new-item-btn').addEventListener('click', () => {
                newRow.remove();
                calculateAllTotals();
            });

            newItemIndex++;
        }

        function deleteExistingItem(itemId, button) {
            if (confirm('هل أنت متأكد من حذف هذا البند؟')) {
                const row = button.closest('tr');
                row.remove();
                const form = document.querySelector('form');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'deleted_items[]';
                hiddenInput.value = itemId;
                form.appendChild(hiddenInput);
                calculateAllTotals();
            }
        }

        // --- Utility Functions ---
        function numberToArabicWords(num) {
            if (num === 0) return 'صفر ريال سعودي';
            num = Math.round(num);

            const ones = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة'];
            const tens = ['', '', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];
            const teens = ['عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر', 'سبعة عشر',
                'ثمانية عشر', 'تسعة عشر'
            ];
            const hundreds = ['', 'مائة', 'مائتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة',
                'تسعمائة'
            ];

            function convertGroup(n) {
                let result = '';
                const h = Math.floor(n / 100);
                const t = Math.floor((n % 100) / 10);
                const o = n % 10;
                if (h > 0) result += hundreds[h];
                if (t === 1) {
                    if (result) result += ' و';
                    result += teens[o];
                } else {
                    if (o > 0) {
                        if (result) result += ' و';
                        result += ones[o];
                    }
                    if (t > 1) {
                        if (result) result += ' و';
                        result += tens[t];
                    }
                }
                return result;
            }

            let result = '';
            const millions = Math.floor(num / 1000000);
            const thousands = Math.floor((num % 1000000) / 1000);
            const remainder = num % 1000;

            if (millions > 0) {
                result += convertGroup(millions) + (millions === 1 ? ' مليون' : millions === 2 ? 'مليونان' : ' ملايين');
            }
            if (thousands > 0) {
                if (result) result += ' و';
                result += convertGroup(thousands) + (thousands === 1 ? ' ألف' : thousands === 2 ? ' ألفان' : ' آلاف');
            }
            if (remainder > 0) {
                if (result) result += ' و';
                result += convertGroup(remainder);
            }

            return result + ' ريال سعودي';
        }
    </script>
@endsection
