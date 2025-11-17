@extends('layouts.app')

@section('title', $project->name)

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <div class="p-6" dir="rtl">
        <!-- أزرار الإجراءات المختلفة -->
        <div class="mb-6 flex flex-wrap items-center justify-end gap-2 sm:gap-3">
            <!-- زر إضافة صور جديدة -->
            <button onclick="openAddImagesModal()"
                class="bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-2 sm:px-4 rounded-lg font-medium flex items-center gap-1 sm:gap-2 shadow transition-colors text-sm sm:text-base">
                <i class="ri-image-add-line text-base sm:text-lg"></i>
                <span class="hidden sm:inline">إضافة صور جديدة</span>
                <span class="sm:hidden">صور</span>
            </button>

            <!-- زر تسجيل قرض -->
            <button onclick="openLoanModal()"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 sm:px-4 rounded-lg font-medium flex items-center gap-1 sm:gap-2 shadow transition-colors text-sm sm:text-base">
                <i class="ri-money-dollar-box-line text-base sm:text-lg"></i>
                <span class="hidden sm:inline">تسجيل قرض</span>
                <span class="sm:hidden">قرض</span>
            </button>

            <!-- زر تمديد فترة المشروع -->
            <button onclick="openExtendModal()"
                class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-2 sm:px-4 rounded-lg font-medium flex items-center gap-1 sm:gap-2 shadow transition-colors text-sm sm:text-base">
                <i class="ri-calendar-line text-base sm:text-lg"></i>
                <span class="hidden sm:inline">تمديد فترة المشروع</span>
                <span class="sm:hidden">تمديد</span>
            </button>

            <!-- زر تسجيل زيارة -->
            <button onclick="openVisitModal()"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 sm:px-4 rounded-lg font-medium flex items-center gap-1 sm:gap-2 shadow transition-colors text-sm sm:text-base">
                <i class="ri-map-pin-line text-base sm:text-lg"></i>
                <span class="hidden sm:inline">تسجيل زيارة</span>
                <span class="sm:hidden">زيارة</span>
            </button>

            <!-- زر تسجيل معدة مستأجرة -->
            <button onclick="openRentalModal()"
                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 sm:px-4 rounded-lg font-medium flex items-center gap-1 sm:gap-2 shadow transition-colors text-sm sm:text-base">
                <i class="ri-truck-line text-base sm:text-lg"></i>
                <span class="hidden sm:inline">تسجيل معدة مستأجرة</span>
                <span class="sm:hidden">معدة</span>
            </button>

            <!-- زر عمل مستخلص جديد -->
            <a href="{{ route('projects.extract.create', $project) }}" target="_blank"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 sm:px-4 rounded-lg font-medium flex items-center gap-1 sm:gap-2 shadow transition-colors text-sm sm:text-base">
                <i class="ri-file-list-3-line text-base sm:text-lg"></i>
                <span class="hidden sm:inline">عمل مستخلص جديد</span>
                <span class="sm:hidden">مستخلص</span>
            </a>
        </div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <p class="text-gray-600 mt-1">تفاصيل المشروع</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('projects.edit', $project) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-edit-line"></i>
                    تعديل
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-delete-bin-line"></i>
                        حذف
                    </button>
                </form>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-3 h-3 rounded-full
                        @if ($project->status === 'active') bg-green-500
                        @elseif($project->status === 'planning') bg-blue-500
                        @elseif($project->status === 'completed') bg-gray-500
                        @else bg-red-500 @endif">
                        </div>
                        <span class="font-medium text-gray-900">
                            @if ($project->status === 'active')
                                مشروع نشط
                            @elseif($project->status === 'planning')
                                في مرحلة التخطيط
                            @elseif($project->status === 'completed')
                                مشروع مكتمل
                            @else
                                مشروع متوقف
                            @endif
                        </span>
                    </div>
                    <span class="text-sm text-gray-500">
                        آخر تحديث: {{ $project->updated_at->diffForHumans() }}
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4 progress-bar-container">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">نسبة الإنجاز</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-900"
                                id="progress-percentage">{{ number_format($project->progress) }}%</span>
                            <button onclick="openProgressModal()"
                                class="text-blue-600 hover:text-blue-800 p-1 rounded-full hover:bg-blue-50 transition-colors tooltip"
                                data-tooltip="تحديث نسبة الإنجاز">
                                <i class="ri-edit-line text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="w-full bg-gray-200 rounded-full h-3 cursor-pointer hover:bg-gray-300 transition-colors tooltip"
                            onclick="openProgressModal()"
                            data-tooltip="انقر لتحديث نسبة الإنجاز ({{ $project->progress }}%)">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500 hover:from-blue-600 hover:to-blue-700 relative overflow-hidden"
                                id="progress-bar" style="width: {{ $project->progress }}%">
                                <!-- شعاع متحرك للتأثير البصري -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse">
                                </div>
                            </div>
                        </div>
                        <!-- نسبة الإنجاز داخل الشريط إذا كانت النسبة كبيرة بما يكفي -->
                        @if ($project->progress > 15)
                            <div class="absolute top-0 left-2 h-3 flex items-center">
                                <span
                                    class="text-xs font-medium text-white drop-shadow-sm">{{ number_format($project->progress) }}%</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>0%</span>
                        <span class="text-center">
                            {{ $project->progress < 25 ? 'بداية المشروع' : ($project->progress < 50 ? 'في مرحلة مبكرة' : ($project->progress < 75 ? 'في التطوير' : ($project->progress < 100 ? 'قرب الانتهاء' : 'مكتمل! 🎉'))) }}
                        </span>
                        <span>100%</span>
                    </div>

                    <!-- مؤشر بصري للحالة -->
                    <div class="flex items-center gap-2 mt-2">
                        <div
                            class="w-2 h-2 rounded-full {{ $project->progress < 25 ? 'bg-red-500' : ($project->progress < 50 ? 'bg-orange-500' : ($project->progress < 75 ? 'bg-yellow-500' : ($project->progress < 100 ? 'bg-blue-500' : 'bg-green-500'))) }}">
                        </div>
                        <span class="text-xs text-gray-600">
                            {{ $project->progress == 0 ? 'لم يبدأ بعد' : ($project->progress == 100 ? 'تم الانتهاء' : 'قيد التنفيذ') }}
                        </span>
                        @if ($project->progress > 0 && $project->progress < 100)
                            <span class="text-xs text-gray-500">• آخر تحديث:
                                {{ $project->updated_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <!-- Budget Card -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="ri-money-dollar-circle-line text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">الميزانية الإجمالية</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($project->budget, 0) }}</p>
                        <p class="text-gray-500 text-xs">ريال سعودي</p>
                    </div>
                </div>
            </div>

            <!-- Bank Guarantee Card -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <i class="ri-bank-line text-purple-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">الضمان البنكي</p>
                        @if ($project->bank_guarantee_amount)
                            <p class="text-xl font-bold text-gray-900">{{ $project->formatted_bank_guarantee_amount }}</p>
                            <p class="text-gray-500 text-xs">{{ $project->bank_guarantee_type_name }}</p>
                        @else
                            <p class="text-lg font-bold text-gray-400">غير محدد</p>
                            <p class="text-gray-500 text-xs">لا يوجد ضمان</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Duration Card -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="ri-calendar-line text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">مدة المشروع</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @if ($project->start_date && $project->end_date)
                                {{ \Carbon\Carbon::parse($project->start_date)->diffInDays(\Carbon\Carbon::parse($project->end_date)) }}
                            @else
                                غير محدد
                            @endif
                        </p>
                        <p class="text-gray-500 text-xs">يوم</p>
                    </div>
                </div>
            </div>

            <!-- Days Remaining Card -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    @php
                        $daysRemaining = $project->end_date
                            ? \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($project->end_date), false)
                            : null;
                    @endphp
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <i class="ri-time-line text-orange-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">المتبقي</p>
                        <p
                            class="text-2xl font-bold {{ $daysRemaining && $daysRemaining < 0 ? 'text-red-600' : 'text-gray-900' }}">
                            @if ($daysRemaining !== null)
                                {{ number_format(abs($daysRemaining), 0) }}
                            @else
                                --
                            @endif
                        </p>
                        <p class="text-gray-500 text-xs">
                            @if ($daysRemaining !== null)
                                {{ $daysRemaining < 0 ? 'متأخر' : 'يوم' }}
                            @else
                                غير محدد
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Team Management Card -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <i class="ri-team-line text-purple-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">مدير المشروع</p>
                        @if ($project->projectManager)
                            <p class="text-lg font-bold text-gray-900">{{ $project->projectManager->name }}</p>
                            <p class="text-gray-500 text-xs">{{ $project->projectManager->department ?? 'الإدارة' }}</p>
                        @else
                            <p class="text-lg font-bold text-gray-900">{{ $project->project_manager ?? 'غير محدد' }}</p>
                            <p class="text-gray-500 text-xs">المسؤول</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">معلومات المشروع</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Project Number -->
                        @if ($project->project_number)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">رقم المشروع</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-3">
                                        <i class="ri-hashtag text-gray-600"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $project->project_number }}</p>
                                            <p class="text-sm text-gray-600">الرقم المرجعي</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Client Information -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">بيانات العميل</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-3">
                                    <i class="ri-user-line text-gray-600"></i>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $project->client_name }}</p>
                                        <p class="text-sm text-gray-600">العميل</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Government Entity -->
                        @if ($project->government_entity)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">الجهة الحكومية</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-3">
                                        <i class="ri-government-line text-gray-600"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $project->government_entity }}</p>
                                            <p class="text-sm text-gray-600">الجهة المشرفة</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Consulting Office -->
                        @if ($project->consulting_office)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">مكتب الاستشاري</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-3">
                                        <i class="ri-building-2-line text-gray-600"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $project->consulting_office }}</p>
                                            <p class="text-sm text-gray-600">المكتب الاستشاري</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Project Scope -->
                        @if ($project->project_scope)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">نطاق العمل</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-3">
                                        <i class="ri-radar-line text-gray-600"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $project->project_scope }}</p>
                                            <p class="text-sm text-gray-600">مجال العمل</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Location -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">موقع المشروع</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-3">
                                    <i class="ri-map-pin-line text-gray-600"></i>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $project->location }}</p>
                                        <p class="text-sm text-gray-600">الموقع</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Project Description -->
                        @if ($project->description)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">وصف المشروع</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-700 leading-relaxed">{{ $project->description }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Timeline Visualization -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">الجدول الزمني</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="space-y-4">
                                    @if ($project->start_date)
                                        <div class="flex items-center gap-3">
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            <div>
                                                <p class="font-medium text-gray-900">بداية المشروع</p>
                                                <p class="text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        <div>
                                            <p class="font-medium text-gray-900">الحالة الحالية</p>
                                            <p class="text-sm text-gray-600">{{ number_format($project->progress) }}%
                                                مكتمل</p>
                                        </div>
                                    </div>

                                    @if ($project->end_date)
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-3 h-3 {{ $daysRemaining && $daysRemaining < 0 ? 'bg-red-500' : 'bg-orange-500' }} rounded-full">
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">الانتهاء المتوقع</p>
                                                <p class="text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Project Files -->

                        @if ($project->projectFiles && $project->projectFiles->count() > 0)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">ملفات المشروع</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="space-y-3">
                                        @foreach ($project->projectFiles as $file)
                                            <div class="flex items-center justify-between bg-white rounded-lg p-3 border">
                                                <div class="flex items-center gap-3">
                                                    <i class="ri-file-text-line text-gray-600"></i>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $file->name }}</p>
                                                        @if ($file->description)
                                                            <p class="text-sm text-gray-600">{{ $file->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if ($file->file_path)
                                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 transition-colors">
                                                        <i class="ri-download-line"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Project Images -->

                        <!-- صور المشروع (تم نقلها لأسفل الصفحة) -->
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">إجراءات سريعة</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('projects.edit', $project) }}"
                            class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg transition-colors flex items-center gap-3">
                            <i class="ri-edit-line"></i>
                            <span>تعديل بيانات المشروع</span>
                        </a>

                        <button onclick="window.print()"
                            class="w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-lg transition-colors flex items-center gap-3">
                            <i class="ri-printer-line"></i>
                            <span>طباعة التقرير</span>
                        </button>

                        <button onclick="exportProject()"
                            class="w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-3 rounded-lg transition-colors flex items-center gap-3">
                            <i class="ri-download-line"></i>
                            <span>تصدير البيانات</span>
                        </button>
                    </div>
                </div>

                <!-- Project Statistics -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">إحصائيات</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">تاريخ الإنشاء</span>
                            <span class="font-medium text-gray-900">{{ $project->created_at->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">آخر تحديث</span>
                            <span class="font-medium text-gray-900">{{ $project->updated_at->diffForHumans() }}</span>
                        </div>

                        @if ($project->start_date)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">أيام منذ البداية</span>
                                <span
                                    class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($project->start_date)->diffInDays(\Carbon\Carbon::now()) }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">تكلفة اليوم الواحد</span>
                            <span class="font-medium text-gray-900">
                                @if ($project->start_date && $project->end_date)
                                    {{ number_format($project->budget / max(1, \Carbon\Carbon::parse($project->start_date)->diffInDays(\Carbon\Carbon::parse($project->end_date))), 0) }}
                                    ر.س
                                @else
                                    غير محدد
                                @endif
                            </span>
                        </div>

                        @if ($project->projectFiles && $project->projectFiles->count() > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">عدد الملفات</span>
                                <span class="font-medium text-gray-900">{{ $project->projectFiles->count() }} ملف</span>
                            </div>
                        @endif

                        @if ($project->projectImages && $project->projectImages->count() > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">عدد الصور</span>
                                <span class="font-medium text-gray-900">{{ $project->projectImages->count() }} صورة</span>
                            </div>
                        @endif

                        @if ($project->deliveryRequests && $project->deliveryRequests->count() > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">طلبات الاستلام</span>
                                <span class="font-medium text-gray-900">{{ $project->deliveryRequests->count() }}
                                    طلب</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Project Locations -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="ri-map-pin-line text-blue-600"></i>
                            مواقع المشروع
                        </h2>
                    </div>
                    <div class="p-6">
                        @if ($project->locations && $project->locations->count() > 0)
                            <div class="space-y-4">
                                @foreach ($project->locations as $location)
                                    <div class="location-card-modern cursor-pointer border border-gray-200 rounded-xl hover:border-blue-300 hover:shadow-lg transition-all duration-300"
                                        data-location="{{ json_encode([
                                            'id' => $location->id,
                                            'name' => $location->name,
                                            'type' => $location->type ?? 'موقع عام',
                                            'address' => $location->address,
                                            'city' => $location->city,
                                            'region' => $location->region,
                                            'description' => $location->description,
                                            'manager' => $location->manager ? $location->manager->name : null,
                                            'employees_count' => $location->employees->count(),
                                            'equipment_count' => $location->equipment->count(),
                                            'employees' => $location->employees->map(function ($emp) {
                                                return [
                                                    'name' => $emp->name,
                                                    'position' => $emp->position ?? 'موظف',
                                                    'phone' => $emp->phone,
                                                    'email' => $emp->email,
                                                ];
                                            }),
                                            'equipment' => $location->equipment->map(function ($eq) {
                                                return [
                                                    'name' => $eq->name,
                                                    'type' => $eq->type,
                                                    'model' => $eq->model,
                                                    'status' => $eq->status,
                                                    'serial_number' => $eq->serial_number,
                                                ];
                                            }),
                                        ]) }}"
                                        onclick="openLocationModal(this)">

                                        <div class="p-6">
                                            <!-- Header Section -->
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex items-center gap-4">
                                                    <!-- Location Icon -->
                                                    <div
                                                        class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                                        <i class="ri-map-pin-line text-blue-600 text-xl"></i>
                                                    </div>

                                                    <!-- Location Info -->
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <h3 class="text-lg font-semibold text-gray-900">
                                                                {{ $location->name }}</h3>
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-lg">
                                                                {{ $location->type ?? 'موقع عام' }}
                                                            </span>
                                                        </div>
                                                        @if ($location->address)
                                                            <p class="text-sm text-gray-600 flex items-center gap-1">
                                                                <i class="ri-map-2-line text-gray-400"></i>
                                                                {{ $location->address }}
                                                            </p>
                                                        @endif
                                                        @if ($location->city || $location->region)
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ $location->city }}{{ $location->city && $location->region ? '، ' : '' }}{{ $location->region }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Arrow Icon -->
                                                <div class="text-gray-400 hover:text-blue-600 transition-colors">
                                                    <i class="ri-arrow-left-s-line text-2xl"></i>
                                                </div>
                                            </div>

                                            <!-- Stats Section -->
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                                <!-- Employees Count -->
                                                <div class="bg-green-50 rounded-lg p-3 text-center">
                                                    <div
                                                        class="w-8 h-8 bg-green-100 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                                        <i class="ri-group-line text-green-600"></i>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mb-1">العمالة</p>
                                                    <p class="text-lg font-bold text-gray-900">
                                                        {{ $location->employees->count() }}</p>
                                                </div>

                                                <!-- Equipment Count -->
                                                <div class="bg-orange-50 rounded-lg p-3 text-center">
                                                    <div
                                                        class="w-8 h-8 bg-orange-100 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                                        <i class="ri-truck-line text-orange-600"></i>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mb-1">المعدات</p>
                                                    <p class="text-lg font-bold text-gray-900">
                                                        {{ $location->equipment->count() }}</p>
                                                </div>

                                                <!-- Available Equipment -->
                                                <div class="bg-blue-50 rounded-lg p-3 text-center">
                                                    <div
                                                        class="w-8 h-8 bg-blue-100 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                                        <i class="ri-check-line text-blue-600"></i>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mb-1">متاح</p>
                                                    <p class="text-lg font-bold text-gray-900">
                                                        {{ $location->equipment->where('status', 'available')->count() }}
                                                    </p>
                                                </div>

                                                <!-- Manager Info -->
                                                <div class="bg-purple-50 rounded-lg p-3 text-center">
                                                    <div
                                                        class="w-8 h-8 bg-purple-100 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                                        <i class="ri-user-star-line text-purple-600"></i>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mb-1">المدير</p>
                                                    <p class="text-xs font-medium text-gray-900">
                                                        {{ $location->manager ? $location->manager->name : 'غير محدد' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            @if ($location->description)
                                                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                                    <p class="text-sm text-gray-700">
                                                        {{ Str::limit($location->description, 100) }}</p>
                                                </div>
                                            @endif

                                            <!-- Action Button -->
                                            <div class="border-t border-gray-200 pt-4">
                                                <div
                                                    class="flex items-center justify-center text-blue-600 hover:text-blue-700 transition-colors">
                                                    <i class="ri-eye-line ml-2"></i>
                                                    <span class="text-sm font-medium">عرض التفاصيل الكاملة</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="ri-map-pin-line text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">لا توجد مواقع مرتبطة بهذا المشروع حالياً</p>
                                <p class="text-sm text-gray-400 mt-1">يمكن إضافة المواقع من خلال إدارة المواقع</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Details Modal -->
    <div id="locationModal"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4"
        onclick="closeLocationModal()">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
            onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="ri-map-pin-line text-xl"></i>
                        </div>
                        <div>
                            <h3 id="modalLocationName" class="text-xl font-bold"></h3>
                            <p id="modalLocationAddress" class="text-blue-100 text-sm"></p>
                        </div>
                    </div>
                    <button onclick="closeLocationModal()"
                        class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-colors">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>
                <div class="flex items-center gap-4 mt-4">
                    <span id="modalLocationType" class="px-3 py-1 text-sm rounded-full bg-white/20"></span>
                    <div id="modalLocationManager" class="flex items-center gap-2 text-sm text-blue-100">
                        <i class="ri-user-star-line"></i>
                        <span>المدير: <span id="modalManagerName">غير محدد</span></span>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="max-h-[70vh] overflow-y-auto">
                <!-- Stats Summary -->
                <div class="p-6 bg-gray-50 border-b">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-xl p-4 text-center">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                <i class="ri-group-line text-white text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600">إجمالي العمالة</p>
                            <p id="modalEmployeesCount" class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-orange-400 to-red-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                <i class="ri-truck-line text-white text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600">إجمالي المعدات</p>
                            <p id="modalEquipmentCount" class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                <i class="ri-checkbox-circle-line text-white text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600">معدات متاحة</p>
                            <p id="modalAvailableEquipment" class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                <i class="ri-tools-line text-white text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600">قيد الصيانة</p>
                            <p id="modalMaintenanceEquipment" class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex" dir="rtl">
                        <button onclick="switchTab('employees')" id="employeesTab"
                            class="flex-1 py-4 px-6 text-center font-medium text-blue-600 border-b-2 border-blue-600 bg-blue-50">
                            <i class="ri-group-line ml-2"></i>
                            العمالة
                        </button>
                        <button onclick="switchTab('equipment')" id="equipmentTab"
                            class="flex-1 py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700">
                            <i class="ri-truck-line ml-2"></i>
                            المعدات
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Employees Tab -->
                    <div id="employeesContent" class="tab-content">
                        <div id="employeesList" class="space-y-3">
                            <!-- Employees will be loaded here -->
                        </div>
                        <div id="noEmployees" class="text-center py-8 hidden">
                            <i class="ri-group-line text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">لا توجد عمالة مخصصة لهذا الموقع</p>
                        </div>
                    </div>

                    <!-- Equipment Tab -->
                    <div id="equipmentContent" class="tab-content hidden">
                        <div id="equipmentList" class="space-y-3">
                            <!-- Equipment will be loaded here -->
                        </div>
                        <div id="noEquipment" class="text-center py-8 hidden">
                            <i class="ri-truck-line text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">لا توجد معدات مخصصة لهذا الموقع</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- جداول المشروع بعرض كامل -->
    <div class="p-6" dir="rtl">
        <!-- جدول كميات المشروع -->
        @if ($project->projectItems && $project->projectItems->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-list-check text-blue-600"></i>
                    </div>
                    جدول كميات المشروع
                </h2>
                <div class="overflow-x-auto bg-gray-50 rounded-lg p-4">
                    <table class="w-full text-sm text-right border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 border">اسم البند</th>
                                <th class="px-4 py-3 border">الكمية</th>
                                <th class="px-4 py-3 border">الوحدة</th>
                                <th class="px-4 py-3 border">السعر الإفرادي</th>
                                <th class="px-4 py-3 border">الإجمالي</th>
                                <th class="px-4 py-3 border">الإجمالي مع الضريبة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                $totalWithTax = 0;
                            @endphp
                            @foreach ($project->projectItems as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 border font-medium">{{ $item->name }}</td>
                                    <td class="px-4 py-3 border text-center">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="px-4 py-3 border text-center">{{ $item->unit }}</td>
                                    <td class="px-4 py-3 border text-center">{{ number_format($item->unit_price, 2) }} ر.س
                                    </td>
                                    <td class="px-4 py-3 border text-center">{{ number_format($item->total_price, 2) }}
                                        ر.س</td>
                                    <td class="px-4 py-3 border text-center">{{ number_format($item->total_with_tax, 2) }}
                                        ر.س</td>
                                </tr>
                                @php
                                    $total += $item->total_price;
                                    $totalWithTax += $item->total_with_tax;
                                @endphp
                            @endforeach
                            <tr class="font-bold bg-blue-50 border-t-2 border-blue-200">
                                <td class="px-4 py-4 border text-center" colspan="4">الإجمالي النهائي</td>
                                <td class="px-4 py-4 border text-center text-blue-600">{{ number_format($total, 2) }} ر.س
                                </td>
                                <td class="px-4 py-4 border text-center text-blue-600">
                                    {{ number_format($totalWithTax, 2) }} ر.س</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- جدول طلبات استلام الأعمال -->
        @if ($project->deliveryRequests && $project->deliveryRequests->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-file-list-3-line text-green-600"></i>
                    </div>
                    طلبات استلام الأعمال
                </h2>
                <div class="overflow-x-auto bg-gray-50 rounded-lg p-4">
                    <table class="w-full text-sm text-right border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 border">#</th>
                                <th class="px-4 py-3 border">رقم الطلب</th>
                                <th class="px-4 py-3 border">الوصف</th>
                                <th class="px-4 py-3 border">تاريخ الإنشاء</th>
                                <th class="px-4 py-3 border">الملف</th>
                                <th class="px-4 py-3 border">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($project->deliveryRequests as $index => $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 border text-center font-medium">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 border">
                                        @if ($request->request_number)
                                            <span
                                                class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $request->request_number }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">غير محدد</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border">
                                        @if ($request->description)
                                            <p class="text-gray-700">{{ $request->description }}</p>
                                        @else
                                            <span class="text-gray-400">لا يوجد وصف</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border text-center">
                                        @if ($request->created_at)
                                            <span class="text-gray-600">{{ $request->created_at->format('d/m/Y') }}</span>
                                            <br>
                                            <span
                                                class="text-xs text-gray-400">{{ $request->created_at->format('H:i') }}</span>
                                        @else
                                            <span class="text-gray-400">غير محدد</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border text-center">
                                        @if ($request->file_path)
                                            <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank"
                                                class="inline-flex items-center gap-2 bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-full text-xs font-medium transition-colors">
                                                <i class="ri-download-line"></i>
                                                تحميل الملف
                                            </a>
                                        @else
                                            <span class="text-gray-400">لا يوجد ملف</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border text-center">
                                        <button
                                            onclick="editDeliveryRequest({{ $request->id }}, '{{ $request->request_number }}')"
                                            class="inline-flex items-center gap-2 bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-full text-xs font-medium transition-colors">
                                            <i class="ri-edit-line"></i>
                                            تعديل الرقم
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- قسم المستخلصات -->
    <div class="p-6" dir="rtl">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="ri-file-list-3-line text-indigo-600"></i>
                </div>
                مستخلصات المشروع
            </h2>

            @if ($project->projectExtracts && $project->projectExtracts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right border">رقم المستخلص</th>
                                <th class="px-4 py-3 text-right border">التاريخ</th>
                                <th class="px-4 py-3 text-right border">الوصف</th>
                                <th class="px-4 py-3 text-center border">المبلغ</th>
                                <th class="px-4 py-3 text-center border">الحالة</th>
                                <th class="px-4 py-3 text-center border">المنشئ</th>
                                <th class="px-4 py-3 text-center border">الملف</th>
                                <th class="px-4 py-3 text-center border">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($project->projectExtracts->sortByDesc('extract_date') as $extract)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 border font-medium">{{ $extract->extract_number }}</td>
                                    <td class="px-4 py-3 border text-sm text-gray-600">
                                        {{ $extract->extract_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 border">
                                        {{ $extract->description ?? 'بدون وصف' }}
                                    </td>
                                    <td class="px-4 py-3 border text-center font-medium">
                                        <div class="text-sm">
                                            <div class="font-medium">{{ number_format($extract->total_amount, 2) }} ر.س
                                            </div>
                                            @if ($extract->tax_amount > 0)
                                                <div class="text-xs text-gray-500">
                                                    + ضريبة: {{ number_format($extract->tax_amount, 2) }} ر.س
                                                </div>
                                                <div class="text-xs font-semibold text-green-600">
                                                    الإجمالي: {{ number_format($extract->total_with_tax, 2) }} ر.س
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border text-center">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium
                                            @if ($extract->status === 'draft') bg-gray-100 text-gray-700
                                            @elseif($extract->status === 'submitted') bg-blue-100 text-blue-700
                                            @elseif($extract->status === 'approved') bg-green-100 text-green-700
                                            @elseif($extract->status === 'paid') bg-purple-100 text-purple-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ $extract->status_display }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 border text-center text-sm text-gray-600">
                                        {{ $extract->creator->name ?? 'غير محدد' }}
                                    </td>
                                    <td class="px-4 py-3 border text-center">
                                        @if ($extract->file_path)
                                            <a href="{{ Storage::url($extract->file_path) }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800 flex items-center justify-center gap-1">
                                                <i class="ri-file-download-line"></i>
                                                تحميل
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">لا يوجد ملف</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 border text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('projects.extract.show', [$project, $extract]) }}"
                                                class="text-blue-600 hover:text-blue-800 p-1 rounded"
                                                title="عرض التفاصيل">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @if ($extract->status === 'draft')
                                                <a href="{{ route('projects.extract.edit', [$project, $extract]) }}"
                                                    class="text-green-600 hover:text-green-800 p-1 rounded"
                                                    title="تعديل">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form
                                                    action="{{ route('projects.extract.destroy', [$project, $extract]) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخلص؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 p-1 rounded"
                                                        title="حذف">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold">
                            <tr>
                                <td colspan="3" class="px-4 py-3 border text-right">إجمالي المستخلصات:</td>
                                <td class="px-4 py-3 border text-center">
                                    {{ number_format($project->projectExtracts->where('status', '!=', 'draft')->sum('total_amount'), 2) }}
                                    ر.س
                                </td>
                                <td colspan="4" class="px-4 py-3 border"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Extract Summary -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="ri-file-list-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600">إجمالي المستخلصات</p>
                                <p class="text-lg font-bold text-blue-900">{{ $project->projectExtracts->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <i class="ri-money-dollar-circle-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-green-600">إجمالي المبالغ</p>
                                <p class="text-lg font-bold text-green-900">
                                    {{ number_format($project->projectExtracts->where('status', '!=', 'draft')->sum('total_amount'), 0) }}
                                    ر.س
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-purple-100 p-2 rounded-lg">
                                <i class="ri-check-double-line text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-purple-600">المستخلصات المدفوعة</p>
                                <p class="text-lg font-bold text-purple-900">
                                    {{ $project->projectExtracts->where('status', 'paid')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-orange-100 p-2 rounded-lg">
                                <i class="ri-percentage-line text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-orange-600">نسبة الإنجاز المالي</p>
                                <p class="text-lg font-bold text-orange-900">
                                    {{ $project->budget > 0 ? number_format(($project->projectExtracts->where('status', '!=', 'draft')->sum('total_amount') / $project->budget) * 100, 1) : 0 }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="ri-file-list-3-line text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">لا توجد مستخلصات لهذا المشروع</p>
                    <a href="{{ route('projects.extract.create', $project) }}" target="_blank"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ri-add-line"></i>
                        إنشاء أول مستخلص
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- قسم المراسلات الخاصة بالمشروع -->
    <div class="p-6" dir="rtl">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="ri-mail-line text-indigo-600"></i>
                    </div>
                    المراسلات الخاصة بالمشروع
                </h2>

                <div class="flex items-center gap-2">
                    <a href="{{ route('correspondences.create', ['type' => 'incoming', 'project_id' => $project->id]) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm">
                        <i class="ri-inbox-line"></i>
                        مراسلة واردة
                    </a>
                    <a href="{{ route('correspondences.create', ['type' => 'outgoing', 'project_id' => $project->id]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm">
                        <i class="ri-send-plane-line"></i>
                        مراسلة صادرة
                    </a>
                </div>
            </div>

            @if ($correspondences && $correspondences->count() > 0)
                <div class="space-y-4">
                    @foreach ($correspondences as $correspondence)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if ($correspondence->type === 'incoming') bg-green-100 text-green-800
                                            @else
                                                bg-blue-100 text-blue-800 @endif">
                                            @if ($correspondence->type === 'incoming')
                                                <i class="ri-inbox-line"></i>
                                                واردة
                                            @else
                                                <i class="ri-send-plane-line"></i>
                                                صادرة
                                            @endif
                                        </span>

                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if ($correspondence->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($correspondence->priority === 'high')
                                                bg-orange-100 text-orange-800
                                            @elseif($correspondence->priority === 'medium')
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-green-100 text-green-800 @endif">
                                            @if ($correspondence->priority === 'urgent')
                                                <i class="ri-alarm-warning-line"></i>
                                                عاجل
                                            @elseif($correspondence->priority === 'high')
                                                <i class="ri-error-warning-line"></i>
                                                عالية
                                            @elseif($correspondence->priority === 'medium')
                                                <i class="ri-information-line"></i>
                                                متوسطة
                                            @else
                                                <i class="ri-checkbox-circle-line"></i>
                                                منخفضة
                                            @endif
                                        </span>

                                        <span class="text-xs text-gray-500">
                                            {{ $correspondence->reference_number }}
                                        </span>
                                    </div>

                                    <h3 class="font-semibold text-gray-900 mb-1">
                                        {{ $correspondence->subject }}
                                    </h3>

                                    <div class="text-sm text-gray-600 mb-2">
                                        @if ($correspondence->type === 'incoming')
                                            <span class="flex items-center gap-1">
                                                <i class="ri-building-line text-xs"></i>
                                                من: {{ $correspondence->from_entity }}
                                            </span>
                                            @if ($correspondence->assignedEmployee)
                                                <span class="flex items-center gap-1 mt-1">
                                                    <i class="ri-user-line text-xs"></i>
                                                    المسؤول: {{ $correspondence->assignedEmployee->name }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="flex items-center gap-1">
                                                <i class="ri-building-line text-xs"></i>
                                                إلى: {{ $correspondence->to_entity }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-4 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <i class="ri-calendar-line"></i>
                                            {{ $correspondence->correspondence_date->format('Y/m/d') }}
                                        </span>

                                        @if ($correspondence->file_path)
                                            <span class="flex items-center gap-1 text-blue-600">
                                                <i class="ri-attachment-line"></i>
                                                ملف مرفق
                                            </span>
                                        @endif

                                        <span class="flex items-center gap-1">
                                            <i class="ri-user-line"></i>
                                            {{ $correspondence->user->name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('correspondences.show', $correspondence) }}"
                                        class="text-blue-600 hover:text-blue-800 transition-colors" title="عرض التفاصيل">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    @if ($correspondence->file_path)
                                        <a href="{{ route('correspondences.download', $correspondence) }}"
                                            class="text-green-600 hover:text-green-800 transition-colors"
                                            title="تحميل الملف">
                                            <i class="ri-download-line"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                    <a href="{{ route('correspondences.index', ['project_id' => $project->id]) }}"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        عرض جميع المراسلات ({{ $correspondences->count() }})
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-mail-line text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مراسلات</h3>
                    <p class="text-gray-600 mb-4">لم يتم ربط أي مراسلات بهذا المشروع بعد</p>
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('correspondences.create', ['type' => 'incoming', 'project_id' => $project->id]) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm">
                            <i class="ri-inbox-line"></i>
                            إنشاء مراسلة واردة
                        </a>
                        <a href="{{ route('correspondences.create', ['type' => 'outgoing', 'project_id' => $project->id]) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm">
                            <i class="ri-send-plane-line"></i>
                            إنشاء مراسلة صادرة
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- قسم العمالة في المشروع -->
    @php
        $projectEmployees = $project->locations
            ->flatMap(function ($location) {
                return $location->employees;
            })
            ->unique('id');

        $employeesByDepartment = $projectEmployees->groupBy('department');
        $employeesByStatus = $projectEmployees->groupBy('status');
        $activeEmployees = $projectEmployees->where('status', 'active');
        $inactiveEmployees = $projectEmployees->where('status', 'inactive');
    @endphp

    <div class="p-6" dir="rtl">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-group-line text-green-600"></i>
                </div>
                العمالة في المشروع
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $projectEmployees->count() }} موظف
                </span>
            </h2>

            @if ($projectEmployees->count() > 0)
                <!-- إحصائيات العمالة -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600">الموظفين النشطين</p>
                                <p class="text-2xl font-bold text-green-800">{{ $activeEmployees->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-200 rounded-lg flex items-center justify-center">
                                <i class="ri-user-check-line text-xl text-green-700"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-red-600">غير نشطين</p>
                                <p class="text-2xl font-bold text-red-800">{{ $inactiveEmployees->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-red-200 rounded-lg flex items-center justify-center">
                                <i class="ri-user-unfollow-line text-xl text-red-700"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600">عدد الأقسام</p>
                                <p class="text-2xl font-bold text-blue-800">{{ $employeesByDepartment->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-200 rounded-lg flex items-center justify-center">
                                <i class="ri-building-line text-xl text-blue-700"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-600">إجمالي العمالة</p>
                                <p class="text-2xl font-bold text-purple-800">{{ $projectEmployees->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-200 rounded-lg flex items-center justify-center">
                                <i class="ri-group-2-line text-xl text-purple-700"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- فلترة العمالة -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">فلترة حسب:</label>
                        <select id="employeeFilter"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="all">جميع الموظفين</option>
                            <option value="active">النشطين فقط</option>
                            <option value="inactive">غير النشطين</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">القسم:</label>
                        <select id="departmentFilter"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="all">جميع الأقسام</option>
                            @foreach ($employeesByDepartment as $department => $employees)
                                <option value="{{ $department }}">{{ $department ?? 'غير محدد' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button onclick="exportEmployeesData()"
                        class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700 transition-colors flex items-center gap-2">
                        <i class="ri-download-line"></i>
                        تصدير البيانات
                    </button>
                </div>

                <!-- جدول العمالة المحدث -->
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full" id="employeesTable">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الموظف</th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الحالة</th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        القسم</th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                        الوظيفة</th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                        الموقع</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($projectEmployees as $employee)
                                    <tr class="hover:bg-gray-50 transition-colors employee-row"
                                        data-status="{{ $employee->status ?? 'active' }}"
                                        data-department="{{ $employee->department ?? '' }}">
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($employee->photo)
                                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200"
                                                            src="{{ Storage::url($employee->photo) }}"
                                                            alt="{{ $employee->name }}">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
                                                            <span class="text-white font-bold text-sm">
                                                                {{ substr($employee->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="mr-3">
                                                    <div class="text-sm font-bold text-gray-900">{{ $employee->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 sm:hidden">
                                                        {{ $employee->department ?? 'غير محدد' }}
                                                    </div>
                                                    @if ($employee->phone)
                                                        <div class="text-xs text-gray-400 md:hidden">
                                                            <i class="ri-phone-line text-xs"></i>
                                                            {{ $employee->phone }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            @if (($employee->status ?? 'active') === 'active')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="ri-checkbox-circle-line ml-1"></i>
                                                    <span class="hidden sm:inline">نشط</span>
                                                    <span class="sm:hidden">✓</span>
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="ri-close-circle-line ml-1"></i>
                                                    <span class="hidden sm:inline">غير نشط</span>
                                                    <span class="sm:hidden">✗</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap hidden sm:table-cell">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="ri-building-line ml-1"></i>
                                                {{ $employee->department ?? 'غير محدد' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap hidden md:table-cell">
                                            <div class="text-sm font-medium text-gray-900">
                                                <i class="ri-user-star-line text-gray-400 ml-1"></i>
                                                {{ $employee->position ?? 'غير محدد' }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap hidden lg:table-cell">
                                            <div class="flex items-center text-sm text-gray-900">
                                                <i class="ri-map-pin-line text-gray-400 ml-1"></i>
                                                <span>{{ $employee->location->name ?? 'غير محدد' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-1 justify-center">
                                                <button onclick="showEmployeeDetails({{ $employee->id }})"
                                                    class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-2 rounded-lg transition-colors"
                                                    title="عرض التفاصيل">
                                                    <i class="ri-eye-line text-sm"></i>
                                                </button>

                                                @can('update', $employee)
                                                    <a href="{{ route('employees.edit', $employee->id) }}"
                                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors"
                                                        title="تعديل">
                                                        <i class="ri-edit-line text-sm"></i>
                                                    </a>
                                                @endcan

                                                <a href="{{ route('employees.show', $employee->id) }}"
                                                    class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 p-2 rounded-lg transition-colors"
                                                    title="الملف الشخصي">
                                                    <i class="ri-user-line text-sm"></i>
                                                </a>

                                                @if ($employee->status === 'active')
                                                    <button onclick="sendNotification({{ $employee->id }})"
                                                        class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-2 rounded-lg transition-colors"
                                                        title="إرسال إشعار">
                                                        <i class="ri-notification-line text-sm"></i>
                                                    </button>
                                                @endif

                                                <div class="relative">
                                                    <button onclick="toggleEmployeeMenu({{ $employee->id }})"
                                                        class="text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors"
                                                        title="المزيد">
                                                        <i class="ri-more-2-line text-sm"></i>
                                                    </button>
                                                    <div id="employeeMenu{{ $employee->id }}"
                                                        class="absolute left-0 top-full mt-1 w-48 bg-white rounded-lg shadow-lg border z-10 hidden">
                                                        <div class="py-1">
                                                            <a href="#"
                                                                onclick="generateEmployeeReport({{ $employee->id }})"
                                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <i class="ri-file-text-line"></i>
                                                                تقرير الموظف
                                                            </a>
                                                            <a href="#"
                                                                onclick="viewAttendance({{ $employee->id }})"
                                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <i class="ri-calendar-check-line"></i>
                                                                سجل الحضور
                                                            </a>
                                                            <a href="#" onclick="viewPayroll({{ $employee->id }})"
                                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <i class="ri-wallet-line"></i>
                                                                كشف الراتب
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- إضافة موظف جديد -->
                @can('create', \App\Models\Employee::class)
                    <div class="mt-6 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('employees.create') }}?project_id={{ $project->id }}"
                                class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="ri-user-add-line"></i>
                                إضافة موظف جديد
                            </a>
                            <button onclick="bulkAssignToProject()"
                                class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="ri-group-add-line"></i>
                                تعيين موظفين للمشروع
                            </button>
                        </div>

                        <div class="text-sm text-gray-500">
                            عرض {{ $projectEmployees->count() }} من {{ $projectEmployees->count() }} موظف
                        </div>
                    </div>
                @endcan
            @else
                <!-- رسالة عدم وجود موظفين -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="ri-group-line text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد عمالة مسجلة</h3>
                    <p class="text-gray-600 mb-6">لم يتم تسجيل أي عمالة في هذا المشروع حتى الآن</p>

                    @can('create', \App\Models\Employee::class)
                        <div class="flex items-center justify-center gap-4">
                            <a href="{{ route('employees.create') }}?project_id={{ $project->id }}"
                                class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="ri-user-add-line"></i>
                                إضافة أول موظف للمشروع
                            </a>
                            <button onclick="bulkAssignToProject()"
                                class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="ri-group-add-line"></i>
                                تعيين موظفين موجودين
                            </button>
                        </div>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <!-- معدات المشروع -->
    @if ($project->equipment && $project->equipment->count() > 0)
        <div class="p-6" dir="rtl">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="ri-tools-line text-indigo-600"></i>
                    </div>
                    معدات المشروع
                    <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full text-sm font-medium">
                        {{ $project->equipment->count() }} معدة
                    </span>
                </h2>

                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المعدة</th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الحالة</th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        النوع</th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                        الموقع</th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                        المسؤول</th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">
                                        التسجيل</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($project->equipment as $equipment)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div
                                                        class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                                        <i class="ri-tools-fill text-indigo-600"></i>
                                                    </div>
                                                </div>
                                                <div class="mr-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $equipment->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 sm:hidden">
                                                        {{ $equipment->type ?? 'غير محدد' }}
                                                    </div>
                                                    @if ($equipment->serial_number)
                                                        <div class="text-xs text-gray-500 md:hidden">
                                                            {{ $equipment->serial_number }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if ($equipment->status === 'active') bg-green-100 text-green-800
                                                @elseif($equipment->status === 'maintenance') bg-yellow-100 text-yellow-800
                                                @elseif($equipment->status === 'inactive') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @switch($equipment->status)
                                                    @case('active')
                                                        <span class="hidden sm:inline">نشط</span>
                                                        <span class="sm:hidden">✓</span>
                                                    @break

                                                    @case('maintenance')
                                                        <span class="hidden sm:inline">صيانة</span>
                                                        <span class="sm:hidden">⚠</span>
                                                    @break

                                                    @case('inactive')
                                                        <span class="hidden sm:inline">غير نشط</span>
                                                        <span class="sm:hidden">✗</span>
                                                    @break

                                                    @default
                                                        غير محدد
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap hidden sm:table-cell">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $equipment->type ?? 'غير محدد' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap hidden md:table-cell">
                                            <div class="text-sm text-gray-900">
                                                {{ $equipment->locationDetail->name ?? 'غير محدد' }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap hidden lg:table-cell">
                                            <div class="text-sm text-gray-900">
                                                {{ $equipment->driver->name ?? 'غير محدد' }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap hidden xl:table-cell">
                                            <div class="text-xs text-gray-900">
                                                {{ $equipment->user ? $equipment->user->name : 'غير محدد' }}
                                                @if ($equipment->created_at)
                                                    <div class="text-xs text-gray-500">
                                                        {{ $equipment->created_at->format('d/m/Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-1">
                                                <a href="{{ route('equipment.show', $equipment->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors"
                                                    title="عرض تفاصيل المعدة">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                @can('update', $equipment)
                                                    <a href="{{ route('equipment.edit', $equipment->id) }}"
                                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors"
                                                        title="تعديل المعدة">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                @endcan
                                                @if ($equipment->movementHistory && $equipment->movementHistory->count() > 0)
                                                    <button type="button"
                                                        onclick="showEquipmentHistory({{ $equipment->id }})"
                                                        class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-2 rounded-lg transition-colors"
                                                        title="تاريخ التحركات">
                                                        <i class="ri-history-line"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ملخص المعدات -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $project->equipment->where('status', 'active')->count() }}
                        </div>
                        <div class="text-sm text-green-600 font-medium">معدات نشطة</div>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600">
                            {{ $project->equipment->where('status', 'maintenance')->count() }}
                        </div>
                        <div class="text-sm text-yellow-600 font-medium">تحت الصيانة</div>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-red-600">
                            {{ $project->equipment->where('status', 'inactive')->count() }}
                        </div>
                        <div class="text-sm text-red-600 font-medium">غير نشطة</div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $project->equipment->count() }}
                        </div>
                        <div class="text-sm text-blue-600 font-medium">إجمالي المعدات</div>
                    </div>
                </div>

                <!-- إضافة معدة جديدة -->
                @can('create', \App\Models\Equipment::class)
                    <div class="mt-6 text-center">
                        <a href="{{ route('equipment.create') }}?project_id={{ $project->id }}"
                            class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="ri-add-line"></i>
                            إضافة معدة جديدة للمشروع
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    @else
        <!-- رسالة عدم وجود معدات -->
        <div class="p-6" dir="rtl">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="ri-tools-line text-indigo-600"></i>
                    </div>
                    معدات المشروع
                </h2>

                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="ri-tools-line text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد معدات مسجلة</h3>
                    <p class="text-gray-600 mb-6">لم يتم تسجيل أي معدات في هذا المشروع حتى الآن</p>

                    @can('create', \App\Models\Equipment::class)
                        <a href="{{ route('equipment.create') }}?project_id={{ $project->id }}"
                            class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="ri-add-line"></i>
                            إضافة أول معدة للمشروع
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    @endif

    <!-- قسم تمديدات المشروع -->
    @if ($project->extensions && $project->extensions->count() > 0)
        <div class="p-6" dir="rtl">
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <i class="ri-calendar-2-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">تمديدات المشروع</h2>
                        <p class="text-gray-600">إجمالي {{ $project->extensions->count() }} تمديد مسجل</p>
                    </div>
                </div>

                <div class="grid gap-4">
                    @foreach ($project->extensions->sortByDesc('created_at') as $extension)
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg p-4 border border-amber-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-medium">
                                        تمديد فترة
                                    </span>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-amber-600 mb-1">
                                        من {{ \Carbon\Carbon::parse($extension->old_end_date)->format('Y-m-d') }}
                                    </div>
                                    <div class="text-lg font-bold text-green-600">
                                        إلى {{ \Carbon\Carbon::parse($extension->new_end_date)->format('Y-m-d') }}
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500 block">المدة الإضافية</span>
                                    <span class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($extension->old_end_date)->diffInDays(\Carbon\Carbon::parse($extension->new_end_date)) }}
                                        يوم
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">تاريخ التمديد</span>
                                    <span
                                        class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($extension->created_at)->format('Y-m-d') }}</span>
                                </div>
                            </div>

                            @if ($extension->reason)
                                <div class="mt-3 pt-3 border-t border-amber-200">
                                    <span class="text-gray-500 text-sm block">سبب التمديد</span>
                                    <p class="text-gray-700 text-sm mt-1">{{ $extension->reason }}</p>
                                </div>
                            @endif

                            <div class="mt-3 pt-3 border-t border-amber-200 text-xs text-gray-500">
                                تم التسجيل بواسطة {{ $extension->extendedBy->name ?? 'غير محدد' }} في
                                {{ \Carbon\Carbon::parse($extension->created_at)->format('Y-m-d H:i') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- قسم زيارات المشروع (تم نقله إلى أسفل الصفحة كسجل زيارات) -->
    @php
        /* القسم القديم أزيل لصالح سجل في أسفل الصفحة */
    @endphp

    <!-- قسم المعدات المستأجرة -->
    @if ($project->rentalEquipment && $project->rentalEquipment->count() > 0)
        <div class="p-6" dir="rtl">
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="ri-truck-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">المعدات المستأجرة</h2>
                        <p class="text-gray-600">إجمالي {{ $project->rentalEquipment->count() }} معدة مستأجرة</p>
                    </div>
                </div>

                <div class="grid gap-4">
                    @foreach ($project->rentalEquipment->sortByDesc('rental_start_date') as $equipment)
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-4 border border-purple-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-2">
                                    @php
                                        $equipmentTypeLabels = [
                                            'excavator' => 'حفار',
                                            'crane' => 'رافعة',
                                            'truck' => 'شاحنة',
                                            'bulldozer' => 'بلدوزر',
                                            'loader' => 'لودر',
                                            'other' => 'أخرى',
                                        ];
                                    @endphp
                                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $equipmentTypeLabels[$equipment->equipment_type] ?? $equipment->equipment_type }}
                                    </span>
                                    @if ($equipment->rental_end_date && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($equipment->rental_end_date)))
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">منتهية</span>
                                    @else
                                        <span
                                            class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">نشطة</span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if ($equipment->daily_rate)
                                        <div class="text-lg font-bold text-purple-600">
                                            {{ number_format($equipment->daily_rate, 2) }} ر.س/يوم
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <h4 class="font-medium text-gray-800 text-lg">{{ $equipment->equipment_name }}</h4>
                                <p class="text-sm text-gray-600">شركة التأجير: {{ $equipment->rental_company }}</p>
                            </div>

                            <div class="grid md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500 block">تاريخ البداية</span>
                                    <span
                                        class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($equipment->rental_start_date)->format('Y-m-d') }}</span>
                                </div>
                                @if ($equipment->rental_end_date)
                                    <div>
                                        <span class="text-gray-500 block">تاريخ الانتهاء</span>
                                        <span
                                            class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($equipment->rental_end_date)->format('Y-m-d') }}</span>
                                    </div>
                                @endif
                                @if ($equipment->rental_start_date && $equipment->rental_end_date)
                                    <div>
                                        <span class="text-gray-500 block">المدة الإجمالية</span>
                                        <span class="font-medium text-gray-800">
                                            {{ \Carbon\Carbon::parse($equipment->rental_start_date)->diffInDays(\Carbon\Carbon::parse($equipment->rental_end_date)) }}
                                            يوم
                                        </span>
                                    </div>
                                @endif
                            </div>

                            @if ($equipment->notes)
                                <div class="mt-3 pt-3 border-t border-purple-200">
                                    <span class="text-gray-500 text-sm block">ملاحظات</span>
                                    <p class="text-gray-700 text-sm mt-1">{{ $equipment->notes }}</p>
                                </div>
                            @endif

                            <div class="mt-3 pt-3 border-t border-purple-200 text-xs text-gray-500">
                                تم التسجيل بواسطة {{ $equipment->recordedBy->name ?? 'غير محدد' }} في
                                {{ \Carbon\Carbon::parse($equipment->created_at)->format('Y-m-d H:i') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- إحصائيات المعدات -->
                <div class="mt-6 p-4 bg-gradient-to-r from-purple-100 to-indigo-100 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-purple-600">{{ $project->rentalEquipment->count() }}
                            </div>
                            <div class="text-gray-600 text-sm">إجمالي المعدات</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $project->rentalEquipment->where(function ($item) {
                                        return !$item->rental_end_date || \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($item->rental_end_date));
                                    })->count() }}
                            </div>
                            <div class="text-gray-600 text-sm">معدات نشطة</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600">
                                {{ number_format($project->rentalEquipment->whereNotNull('daily_rate')->sum('daily_rate'), 2) }}
                            </div>
                            <div class="text-gray-600 text-sm">إجمالي التكلفة اليومية (ر.س)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- سجل الزيارات (في آخر الصفحة) -->
    <div class="p-6" dir="rtl">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-map-pin-line text-green-600"></i>
                    </div>
                    سجل زيارات المشروع
                    @if ($project->visits && $project->visits->count() > 0)
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $project->visits->count() }} زيارة
                        </span>
                    @endif
                </h2>
                <button onclick="openVisitModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                    <i class="ri-add-line"></i>
                    تسجيل زيارة
                </button>
            </div>

            @if ($project->visits && $project->visits->count() > 0)
                <div class="overflow-x-auto bg-gray-50 rounded-lg p-4">
                    <table class="w-full text-sm text-right border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 border">#</th>
                                <th class="px-3 py-2 border">التاريخ</th>
                                <th class="px-3 py-2 border">الوقت</th>
                                <th class="px-3 py-2 border">الزائر</th>
                                <th class="px-3 py-2 border">النوع</th>
                                <th class="px-3 py-2 border">الملاحظات</th>
                                <th class="px-3 py-2 border">المُسجل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $typeLabels = [
                                    'inspection' => 'جولة تفتيش',
                                    'meeting' => 'اجتماع',
                                    'supervision' => 'إشراف',
                                    'coordination' => 'تنسيق',
                                    'maintenance' => 'صيانة',
                                    'delivery' => 'استلام',
                                    'other' => 'أخرى',
                                ];
                            @endphp
                            @foreach ($project->visits->sortByDesc(function ($v) {
            return $v->visit_date . ' ' . ($v->visit_time ?? '');
        }) as $index => $visit)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border text-center font-medium">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2 border text-gray-700">
                                        {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('Y/m/d') : '—' }}
                                    </td>
                                    <td class="px-3 py-2 border text-center">
                                        {{ $visit->visit_time ? \Carbon\Carbon::parse($visit->visit_time)->format('H:i') : '—' }}
                                    </td>
                                    <td class="px-3 py-2 border">
                                        {{ $visit->visitor_name ?? ($visit->visitor->name ?? 'غير محدد') }}</td>
                                    <td class="px-3 py-2 border text-center">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            {{ $typeLabels[$visit->visit_type] ?? $visit->visit_type }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 border text-gray-700">
                                        @if ($visit->visit_notes)
                                            {{ Str::limit($visit->visit_notes, 40) }}
                                            @if (Str::length($visit->visit_notes) > 40)
                                                <span title="{{ $visit->visit_notes }}"
                                                    class="text-blue-600 cursor-help text-xs">المزيد</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 border text-center text-xs text-gray-600">
                                        {{ $visit->recordedBy->name ?? 'غير محدد' }}
                                        <div class="text-[10px] text-gray-400 mt-1">
                                            {{ $visit->created_at ? $visit->created_at->format('Y/m/d H:i') : '' }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100 font-medium">
                            <tr>
                                <td colspan="7" class="px-3 py-2 border text-left text-xs text-gray-500">
                                    آخر تحديث للسجل: {{ now()->format('Y/m/d H:i') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-10">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="ri-map-pin-line text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد زيارات مسجلة</h3>
                    <p class="text-gray-600 mb-4">يمكنك البدء بتسجيل أول زيارة لهذا المشروع الآن</p>
                    <button onclick="openVisitModal()"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm">
                        <i class="ri-add-line"></i>
                        تسجيل أول زيارة
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- القروض المسجلة -->
    @if ($project->loans && $project->loans->count() > 0)
        <div class="p-6" dir="rtl">
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <i class="ri-money-dollar-circle-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">القروض المسجلة</h2>
                        <p class="text-gray-600">إجمالي {{ $project->loans->count() }} قرض مسجل</p>
                    </div>
                </div>

                <div class="grid gap-4">
                    @foreach ($project->loans->sortByDesc('loan_date') as $loan)
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-lg p-4 border border-red-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $loan->loan_source_name }}
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $loan->loan_purpose_name }}
                                    </span>
                                </div>
                                <div class="text-lg font-bold text-red-600">
                                    {{ $loan->formatted_loan_amount }} ر.س
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500 block">الجهة المقرضة</span>
                                    <span class="font-medium text-gray-800">{{ $loan->lender_name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">تاريخ القرض</span>
                                    <span
                                        class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($loan->loan_date)->format('Y-m-d') }}</span>
                                </div>
                                @if ($loan->due_date)
                                    <div>
                                        <span class="text-gray-500 block">تاريخ الاستحقاق</span>
                                        <span
                                            class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($loan->due_date)->format('Y-m-d') }}</span>
                                    </div>
                                @endif
                                @if ($loan->interest_rate)
                                    <div>
                                        <span class="text-gray-500 block">معدل الفائدة</span>
                                        <span class="font-medium text-gray-800">{{ $loan->interest_rate }}%
                                            ({{ $loan->interest_type == 'fixed' ? 'ثابت' : 'متغير' }})
                                        </span>
                                    </div>
                                @endif
                            </div>

                            @if ($loan->notes)
                                <div class="mt-3 pt-3 border-t border-red-200">
                                    <span class="text-gray-500 text-sm block">ملاحظات</span>
                                    <p class="text-gray-700 text-sm mt-1">{{ $loan->notes }}</p>
                                </div>
                            @endif

                            <div class="mt-3 pt-3 border-t border-red-200 text-xs text-gray-500">
                                تم التسجيل بواسطة {{ $loan->recordedBy->name ?? 'غير محدد' }} في
                                {{ \Carbon\Carbon::parse($loan->created_at)->format('Y-m-d H:i') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- إحصائيات القروض -->
                <div class="mt-6 p-4 bg-gradient-to-r from-red-100 to-pink-100 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-red-600">{{ $project->loans->count() }}</div>
                            <div class="text-gray-600 text-sm">إجمالي القروض</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-600">
                                {{ number_format($project->loans->sum('loan_amount'), 2) }}</div>
                            <div class="text-gray-600 text-sm">إجمالي المبالغ (ر.س)</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-600">
                                {{ $project->loans->where('status', 'active')->count() }}</div>
                            <div class="text-gray-600 text-sm">القروض النشطة</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- صور المشروع في آخر الصفحة -->
    @if ($project->projectImages && $project->projectImages->count() > 0)
        <div class="p-6" dir="rtl">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center">
                        <i class="ri-image-line text-pink-600"></i>
                    </div>
                    صور المشروع
                </h2>
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        @foreach ($project->projectImages as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    alt="{{ $image->alt_text ?? 'صورة المشروع' }}"
                                    class="w-full h-40 object-cover rounded-lg cursor-pointer hover:shadow-lg transition-all hover:scale-105 project-image"
                                    onclick="showImageModal('{{ asset('storage/' . $image->image_path) }}', '{{ $image->alt_text ?? 'صورة المشروع' }}')"
                                    loading="lazy"
                                    onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBmaWxsPSIjOTk5IiBmaWxsLW9wYWNpdHk9IjAuNSIvPgo8L3N2Zz4K'; this.alt='صورة غير متاحة';">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all flex items-center justify-center">
                                    <i
                                        class="ri-eye-line text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                                @if ($image->alt_text)
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2 rounded-b-lg">
                                        <p class="text-xs text-center truncate">{{ $image->alt_text }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if ($project->projectImages->count() > 0)
                        <div class="mt-4 text-center text-sm text-gray-600">
                            <i class="ri-information-line"></i>
                            اضغط على أي صورة لعرضها بالحجم الكامل
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif



    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-[9999] items-center justify-center p-4"
        style="display: none;">
        <div class="relative max-w-4xl max-h-full flex items-center justify-center">
            <img id="modalImage" src="" alt=""
                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            <div class="absolute top-4 right-4 z-10">
                <button onclick="closeImageModal()"
                    class="bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-800 rounded-full p-2 transition-all shadow-lg">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            <div id="modalImageTitle"
                class="absolute bottom-4 left-4 right-4 bg-black bg-opacity-70 text-white p-3 rounded-lg text-center z-10">
            </div>
        </div>
    </div>

    <script>
        // Image modal functions
        function showImageModal(imageSrc, imageTitle) {
            console.log('🔍 Opening image modal:', imageSrc, imageTitle);

            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalImageTitle');

            console.log('📋 Modal elements check:', {
                modal: !!modal,
                modalImage: !!modalImage,
                modalTitle: !!modalTitle,
                modalId: modal ? modal.id : 'not found',
                modalClasses: modal ? modal.className : 'not found'
            });

            if (!modal || !modalImage || !modalTitle) {
                console.error('❌ Modal elements not found');
                alert('خطأ: عناصر النافذة المنبثقة غير موجودة');
                return;
            }

            console.log('🖼️ Setting image source:', imageSrc);
            modalImage.src = imageSrc;
            modalImage.alt = imageTitle;
            modalTitle.textContent = imageTitle;

            console.log('👁️ Showing modal...');
            // Show modal with multiple methods to ensure visibility
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            modal.style.visibility = 'visible';
            modal.style.opacity = '1';

            // Prevent body scroll
            document.body.style.overflow = 'hidden';

            console.log('✅ Modal should be visible now');

            // Double check modal visibility
            setTimeout(() => {
                console.log('🔍 Modal visibility check:', {
                    display: modal.style.display,
                    visibility: modal.style.visibility,
                    opacity: modal.style.opacity,
                    hasHiddenClass: modal.classList.contains('hidden')
                });
            }, 100);
        }

        function closeImageModal() {
            console.log('❌ Closing image modal');

            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                modal.style.visibility = 'hidden';
                modal.style.opacity = '0';
                console.log('🔒 Modal hidden');
            }

            // Restore body scroll
            document.body.style.overflow = 'auto';
            console.log('📜 Body scroll restored');
        }

        // Close modal when clicking outside the image
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 DOM loaded, initializing image modal system...');

            const modal = document.getElementById('imageModal');
            if (modal) {
                console.log('✅ Modal element found:', modal.id);
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        console.log('👆 Clicked outside modal, closing...');
                        closeImageModal();
                    }
                });
                console.log('✅ Outside click listener added');
            } else {
                console.error('❌ Modal element not found during DOM load');
            }

            // Test all project images
            const projectImages = document.querySelectorAll('.project-image');
            console.log('🖼️ Found project images:', projectImages.length);

            projectImages.forEach((img, index) => {
                console.log(`Image ${index + 1}:`, img.src);

                // Add additional click listener for debugging
                img.addEventListener('click', function(e) {
                    console.log('📸 Image clicked (event listener):', this.src);
                    e.preventDefault(); // Prevent any default behavior
                });

                img.addEventListener('load', function() {
                    console.log('✅ Image loaded successfully:', this.src);
                });

                img.addEventListener('error', function() {
                    console.error('❌ Image failed to load:', this.src);
                });
            });

            // Test modal elements availability
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalImageTitle');

            console.log('📋 Modal system status:', {
                modal: !!modal,
                modalImage: !!modalImage,
                modalTitle: !!modalTitle,
                projectImages: projectImages.length
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                console.log('⌨️ Escape key pressed, closing modal...');
                closeImageModal();
            }
        });

        // Test image loading and add click debugging
        document.addEventListener('DOMContentLoaded', function() {
            // Remove old debugging code and add test function
            window.testImageModal = function() {
                console.log('🧪 Manual test triggered');
                const firstImage = document.querySelector('.project-image');
                if (firstImage) {
                    const imageSrc = firstImage.src;
                    const imageAlt = firstImage.alt || 'اختبار النافذة المنبثقة';
                    showImageModal(imageSrc, imageAlt);
                } else {
                    console.error('❌ No project images found for testing');
                }
            };

            console.log('🎯 Image modal system initialized. Use testImageModal() to test manually.');
        });

        function exportProject() {
            // Enhanced export functionality with all data
            const projectData = {
                basic_info: {
                    name: '{{ $project->name }}',
                    project_number: '{{ $project->project_number }}',
                    client: '{{ $project->client_name }}',
                    manager: '{{ $project->project_manager }}',
                    location: '{{ $project->location }}',
                    budget: {{ $project->budget }},
                    progress: {{ $project->progress }},
                    status: '{{ $project->status }}',
                    start_date: '{{ $project->start_date }}',
                    end_date: '{{ $project->end_date }}',
                    description: '{{ $project->description }}'
                },
                additional_info: {
                    government_entity: '{{ $project->government_entity }}',
                    consulting_office: '{{ $project->consulting_office }}',
                    project_scope: '{{ $project->project_scope }}'
                },
                files_count: {{ $project->projectFiles ? $project->projectFiles->count() : 0 }},
                images_count: {{ $project->projectImages ? $project->projectImages->count() : 0 }},
                delivery_requests_count: {{ $project->deliveryRequests ? $project->deliveryRequests->count() : 0 }},
                export_date: new Date().toISOString()
            };

            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(projectData, null, 2));
            const downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download",
                "project_{{ $project->id }}_{{ $project->name }}_detailed.json");
            document.body.appendChild(downloadAnchorNode);
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
        }

        // Print styles
        const printStyles = `
    @media print {
        .no-print { display: none !important; }
        .print-hide { display: none !important; }

        /* Hide navigation and header elements */
        nav, .fixed, .sticky { display: none !important; }

        /* Optimize page layout for print */
        body {
            font-size: 12px !important;
            line-height: 1.4 !important;
        }

        .p-6 {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Ensure content is visible */
        .bg-white {
            background-color: white !important;
            -webkit-print-color-adjust: exact;
        }

        /* Adjust cards for print */
        .rounded-xl, .rounded-lg {
            border: 1px solid #e5e7eb !important;
            border-radius: 4px !important;
            margin-bottom: 10px !important;
        }

        /* Grid layout adjustments */
        .grid {
            display: block !important;
        }

        .md\\:grid-cols-4 > div,
        .lg\\:col-span-2 {
            width: 100% !important;
            margin-bottom: 15px !important;
        }

        /* Image handling for print */
        img {
            max-width: 100px !important;
            max-height: 100px !important;
        }
    }
`;

        const styleSheet = document.createElement("style");
        styleSheet.type = "text/css";
        styleSheet.innerText = printStyles;
        document.head.appendChild(styleSheet);

        // Add no-print class to action buttons and modal
        document.addEventListener('DOMContentLoaded', function() {
            // Hide action buttons
            document.querySelectorAll('button, .bg-blue-600, .bg-red-600, a[href*="edit"], a[href*="destroy"]')
                .forEach(el => {
                    if (el.textContent.includes('تعديل') || el.textContent.includes('حذف') || el.classList
                        .contains('bg-blue-600') || el.classList.contains('bg-red-600')) {
                        el.classList.add('no-print');
                    }
                });

            // Hide modal
            document.getElementById('imageModal')?.classList.add('no-print');

            // Add print class to quick actions section
            document.querySelector('.space-y-6')?.classList.add('print-hide');
        });

        // Location Modal Functions
        function openLocationModal(card) {
            const locationData = JSON.parse(card.getAttribute('data-location'));
            const modal = document.getElementById('locationModal');

            // Update modal header
            document.getElementById('modalLocationName').textContent = locationData.name;
            document.getElementById('modalLocationAddress').textContent = locationData.address || 'لا يوجد عنوان محدد';
            document.getElementById('modalLocationType').textContent = locationData.type;
            document.getElementById('modalManagerName').textContent = locationData.manager || 'غير محدد';

            // Update stats
            document.getElementById('modalEmployeesCount').textContent = locationData.employees_count;
            document.getElementById('modalEquipmentCount').textContent = locationData.equipment_count;

            // Calculate equipment stats
            const availableEquipment = locationData.equipment.filter(eq => eq.status === 'available').length;
            const maintenanceEquipment = locationData.equipment.filter(eq => eq.status === 'maintenance').length;

            document.getElementById('modalAvailableEquipment').textContent = availableEquipment;
            document.getElementById('modalMaintenanceEquipment').textContent = maintenanceEquipment;

            // Load employees
            loadEmployees(locationData.employees);

            // Load equipment
            loadEquipment(locationData.equipment);

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLocationModal() {
            const modal = document.getElementById('locationModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function switchTab(tabName) {
            // Update tab buttons
            const employeesTab = document.getElementById('employeesTab');
            const equipmentTab = document.getElementById('equipmentTab');

            if (tabName === 'employees') {
                employeesTab.className =
                    'flex-1 py-4 px-6 text-center font-medium text-blue-600 border-b-2 border-blue-600 bg-blue-50';
                equipmentTab.className = 'flex-1 py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700';
            } else {
                equipmentTab.className =
                    'flex-1 py-4 px-6 text-center font-medium text-blue-600 border-b-2 border-blue-600 bg-blue-50';
                employeesTab.className = 'flex-1 py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700';
            }

            // Update content
            const employeesContent = document.getElementById('employeesContent');
            const equipmentContent = document.getElementById('equipmentContent');

            if (tabName === 'employees') {
                employeesContent.classList.remove('hidden');
                equipmentContent.classList.add('hidden');
            } else {
                equipmentContent.classList.remove('hidden');
                employeesContent.classList.add('hidden');
            }
        }

        function loadEmployees(employees) {
            const employeesList = document.getElementById('employeesList');
            const noEmployees = document.getElementById('noEmployees');

            if (employees.length === 0) {
                employeesList.innerHTML = '';
                noEmployees.classList.remove('hidden');
                return;
            }

            noEmployees.classList.add('hidden');
            employeesList.innerHTML = employees.map(employee => `
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg flex items-center justify-center">
                                <i class="ri-user-line text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">${employee.name}</h4>
                                <p class="text-sm text-gray-600">${employee.position}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            ${employee.phone ? `
                                                                <p class="text-sm text-gray-600 flex items-center gap-1">
                                                                    <i class="ri-phone-line text-xs"></i>
                                                                    ${employee.phone}
                                                                </p>
                                                            ` : ''}
                            ${employee.email ? `
                                                                <p class="text-sm text-gray-600 flex items-center gap-1">
                                                                    <i class="ri-mail-line text-xs"></i>
                                                                    ${employee.email}
                                                                </p>
                                                            ` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function loadEquipment(equipment) {
            const equipmentList = document.getElementById('equipmentList');
            const noEquipment = document.getElementById('noEquipment');

            if (equipment.length === 0) {
                equipmentList.innerHTML = '';
                noEquipment.classList.remove('hidden');
                return;
            }

            noEquipment.classList.add('hidden');
            equipmentList.innerHTML = equipment.map(eq => {
                let statusClass = 'bg-gray-100 text-gray-800';
                let statusText = 'غير محدد';
                let cardGradient = 'from-gray-50 to-gray-100 border-gray-100';

                switch (eq.status) {
                    case 'available':
                        statusClass = 'bg-green-100 text-green-800';
                        statusText = 'متاح';
                        cardGradient = 'from-green-50 to-emerald-50 border-green-100';
                        break;
                    case 'in_use':
                        statusClass = 'bg-blue-100 text-blue-800';
                        statusText = 'قيد الاستخدام';
                        cardGradient = 'from-blue-50 to-indigo-50 border-blue-100';
                        break;
                    case 'maintenance':
                        statusClass = 'bg-yellow-100 text-yellow-800';
                        statusText = 'صيانة';
                        cardGradient = 'from-yellow-50 to-orange-50 border-yellow-100';
                        break;
                }

                return `
                    <div class="bg-gradient-to-r ${cardGradient} border rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-red-500 rounded-lg flex items-center justify-center">
                                    <i class="ri-truck-line text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">${eq.name}</h4>
                                    <p class="text-sm text-gray-600">${eq.type} ${eq.model ? '- ' + eq.model : ''}</p>
                                    ${eq.serial_number ? `<p class="text-xs text-gray-500">الرقم التسلسلي: ${eq.serial_number}</p>` : ''}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 text-xs font-medium rounded-full ${statusClass}">
                                    ${statusText}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLocationModal();
            }
        });

        // عرض تاريخ تحركات المعدة
        window.showEquipmentHistory = function(equipmentId) {
            // إنشاء modal لعرض تاريخ التحركات
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 overflow-y-auto';
            modal.innerHTML = `
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeEquipmentModal(this)">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full" dir="rtl">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">تاريخ تحركات المعدة</h3>
                                <button onclick="closeEquipmentModal(this)" class="text-gray-400 hover:text-gray-600">
                                    <i class="ri-close-line text-xl"></i>
                                </button>
                            </div>
                            <div id="equipment-history-content">
                                <div class="text-center py-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                                    <p class="mt-2 text-gray-500">جاري التحميل...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // تحميل تاريخ التحركات عبر AJAX
            fetch(`/equipment/${equipmentId}/history`)
                .then(response => response.json())
                .then(data => {
                    let historyHtml = '';
                    if (data.length > 0) {
                        historyHtml = `
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                ${data.map(movement => `
                                                                    <div class="border-r-4 border-indigo-500 pr-4 py-3 bg-gray-50 rounded-lg">
                                                                        <div class="flex justify-between items-start">
                                                                            <div class="flex-1">
                                                                                <p class="text-sm font-medium text-gray-900 mb-1">
                                                                                    <i class="ri-arrow-left-right-line text-indigo-600 ml-1"></i>
                                                                                    انتقلت من: ${movement.from_location || 'غير محدد'}
                                                                                </p>
                                                                                <p class="text-sm text-gray-600 mb-1">
                                                                                    <i class="ri-map-pin-line text-green-600 ml-1"></i>
                                                                                    إلى: ${movement.to_location || 'غير محدد'}
                                                                                </p>
                                                                                <p class="text-xs text-gray-500">
                                                                                    <i class="ri-user-line text-blue-600 ml-1"></i>
                                                                                    بواسطة: ${movement.moved_by || 'غير محدد'}
                                                                                </p>
                                                                            </div>
                                                                            <div class="text-left">
                                                                                <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">
                                                                                    ${new Date(movement.moved_at).toLocaleDateString('ar-SA')}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        ${movement.notes ? `
                                            <div class="mt-2 pt-2 border-t border-gray-200">
                                                <p class="text-sm text-gray-600">
                                                    <i class="ri-file-text-line text-orange-600 ml-1"></i>
                                                    ملاحظات: ${movement.notes}
                                                </p>
                                            </div>
                                        ` : ''}
                                                                    </div>
                                                                `).join('')}
                            </div>
                        `;
                    } else {
                        historyHtml = `
                            <div class="text-center py-8">
                                <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="ri-history-line text-3xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">لا يوجد تاريخ تحركات</h4>
                                <p class="text-gray-500">لم يتم تسجيل أي تحركات لهذه المعدة حتى الآن</p>
                            </div>
                        `;
                    }

                    document.getElementById('equipment-history-content').innerHTML = historyHtml;
                })
                .catch(error => {
                    console.error('Error loading equipment history:', error);
                    document.getElementById('equipment-history-content').innerHTML = `
                        <div class="text-center py-8">
                            <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="ri-error-warning-line text-3xl text-red-400"></i>
                            </div>
                            <h4 class="text-lg font-medium text-red-700 mb-2">حدث خطأ</h4>
                            <p class="text-red-500">فشل في تحميل تاريخ التحركات</p>
                        </div>
                    `;
                });
        }

        // إغلاق مودال تاريخ المعدة
        window.closeEquipmentModal = function(button) {
            const modal = button.closest('.fixed') || button;
            if (modal.classList && modal.classList.contains('fixed')) {
                modal.remove();
            } else if (button.closest('.fixed')) {
                button.closest('.fixed').remove();
            }
        }

        // وظائف قسم العمالة
        document.addEventListener('DOMContentLoaded', function() {
            // فلترة الموظفين
            const employeeFilter = document.getElementById('employeeFilter');
            const departmentFilter = document.getElementById('departmentFilter');

            if (employeeFilter) {
                employeeFilter.addEventListener('change', filterEmployees);
            }

            if (departmentFilter) {
                departmentFilter.addEventListener('change', filterEmployees);
            }
        });

        // فلترة الموظفين
        function filterEmployees() {
            const statusFilter = document.getElementById('employeeFilter')?.value || 'all';
            const departmentFilter = document.getElementById('departmentFilter')?.value || 'all';
            const rows = document.querySelectorAll('.employee-row');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const department = row.getAttribute('data-department');

                let showRow = true;

                // فلترة حسب الحالة
                if (statusFilter !== 'all' && status !== statusFilter) {
                    showRow = false;
                }

                // فلترة حسب القسم
                if (departmentFilter !== 'all' && department !== departmentFilter) {
                    showRow = false;
                }

                row.style.display = showRow ? '' : 'none';
            });
        }

        // تصدير بيانات الموظفين
        window.exportEmployeesData = function() {
            const table = document.getElementById('employeesTable');
            const rows = table.querySelectorAll('tbody .employee-row');
            const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');

            if (visibleRows.length === 0) {
                alert('لا توجد بيانات لتصديرها');
                return;
            }

            let csvContent = 'الموظف,الحالة,القسم,الوظيفة,الموقع,تاريخ التعيين,الراتب\n';

            visibleRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const name = cells[0].querySelector('.font-bold').textContent.trim();
                const status = cells[1].textContent.trim();
                const department = cells[2].textContent.trim();
                const position = cells[3].textContent.trim();
                const location = cells[4].textContent.trim();
                const hireDate = cells[5].textContent.trim();
                const salary = cells[6].textContent.trim();

                csvContent +=
                    `"${name}","${status}","${department}","${position}","${location}","${hireDate}","${salary}"\n`;
            });

            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `employees_project_${new Date().getTime()}.csv`;
            link.click();
        }

        // عرض تفاصيل الموظف السريعة
        window.showEmployeeDetails = function(employeeId) {
            // إنشاء modal لعرض تفاصيل الموظف
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 overflow-y-auto';
            modal.innerHTML = `
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeEmployeeModal(this)">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full" dir="rtl">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">تفاصيل الموظف</h3>
                                <button onclick="closeEmployeeModal(this)" class="text-gray-400 hover:text-gray-600">
                                    <i class="ri-close-line text-xl"></i>
                                </button>
                            </div>
                            <div id="employee-details-content">
                                <div class="text-center py-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto"></div>
                                    <p class="mt-2 text-gray-500">جاري التحميل...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // تحميل تفاصيل الموظف عبر AJAX
            fetch(`/employees/${employeeId}/details`)
                .then(response => response.json())
                .then(data => {
                    const detailsHtml = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">معلومات أساسية</h4>
                                <div class="space-y-2 text-sm">
                                    <p><strong>الاسم:</strong> ${data.name || 'غير محدد'}</p>
                                    <p><strong>البريد الإلكتروني:</strong> ${data.email || 'غير محدد'}</p>
                                    <p><strong>الهاتف:</strong> ${data.phone || 'غير محدد'}</p>
                                    <p><strong>الحالة:</strong> <span class="px-2 py-1 rounded text-xs ${data.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${data.status === 'active' ? 'نشط' : 'غير نشط'}</span></p>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">معلومات العمل</h4>
                                <div class="space-y-2 text-sm">
                                    <p><strong>القسم:</strong> ${data.department || 'غير محدد'}</p>
                                    <p><strong>الوظيفة:</strong> ${data.position || 'غير محدد'}</p>
                                    <p><strong>تاريخ التعيين:</strong> ${data.hire_date ? new Date(data.hire_date).toLocaleDateString('ar-SA') : 'غير محدد'}</p>
                                    <p><strong>الراتب:</strong> ${data.salary ? data.salary.toLocaleString() + ' ريال' : 'غير محدد'}</p>
                                </div>
                            </div>
                        </div>
                        ${data.notes ? `
                                                            <div class="mt-4 bg-yellow-50 p-4 rounded-lg">
                                                                <h4 class="font-medium text-gray-900 mb-2">ملاحظات</h4>
                                                                <p class="text-sm text-gray-700">${data.notes}</p>
                                                            </div>
                                                        ` : ''}
                    `;

                    document.getElementById('employee-details-content').innerHTML = detailsHtml;
                })
                .catch(error => {
                    console.error('Error loading employee details:', error);
                    document.getElementById('employee-details-content').innerHTML = `
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="ri-error-warning-line text-2xl text-red-400"></i>
                            </div>
                            <p class="text-red-500">فشل في تحميل تفاصيل الموظف</p>
                        </div>
                    `;
                });
        }

        // تبديل القوائم المنسدلة للموظف
        window.toggleEmployeeMenu = function(employeeId) {
            // إغلاق جميع القوائم الأخرى
            document.querySelectorAll('[id^="employeeMenu"]').forEach(menu => {
                if (menu.id !== `employeeMenu${employeeId}`) {
                    menu.classList.add('hidden');
                }
            });

            // تبديل القائمة المحددة
            const menu = document.getElementById(`employeeMenu${employeeId}`);
            menu.classList.toggle('hidden');
        }

        // إغلاق القوائم عند النقر خارجها
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleEmployeeMenu"]')) {
                document.querySelectorAll('[id^="employeeMenu"]').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // إرسال إشعار للموظف
        window.sendNotification = function(employeeId) {
            // جلب بيانات الموظف أولاً
            fetch(`/employees/${employeeId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch employee details');
                    }
                    return response.json();
                })
                .then(data => {
                    // عرض modal الإشعار
                    window.showNotificationModal(employeeId, data.name);
                })
                .catch(error => {
                    console.error('Error loading employee:', error);
                    alert('فشل في تحميل بيانات الموظف');
                });
        }

        window.showNotificationModal = function(employeeId, employeeName) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
            modal.dir = 'rtl';
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full animate-in">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 rounded-t-xl">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="ri-notification-2-line"></i>
                            إرسال إشعار
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                            <p class="text-sm text-orange-800">
                                <strong>الموظف:</strong> ${employeeName}
                            </p>
                        </div>
                        
                        <div>
                            <label for="notificationMessage" class="block text-sm font-medium text-gray-700 mb-2">
                                نص الإشعار <span class="text-red-500">*</span>
                            </label>
                            <textarea id="notificationMessage" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"
                                rows="4"
                                placeholder="اكتب نص الإشعار هنا..."
                                required></textarea>
                        </div>

                        <div class="flex gap-3">
                            <button onclick="window.submitNotification(${employeeId}, this)" 
                                class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="ri-send-plane-line"></i>
                                إرسال
                            </button>
                            <button onclick="window.closeNotificationModal(this)" 
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                                إلغاء
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // إغلاق عند النقر خارج الموديال
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
            
            // التركيز على حقل الإدخال
            setTimeout(() => {
                document.getElementById('notificationMessage').focus();
            }, 100);
        }

        window.submitNotification = function(employeeId, button) {
            const message = document.getElementById('notificationMessage').value.trim();
            
            if (!message) {
                alert('الرجاء إدخال نص الإشعار');
                return;
            }

            // إضافة حالة التحميل
            button.disabled = true;
            button.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> جاري الإرسال...';
            
            // إرسال الإشعار عبر AJAX
            fetch('/employees/send-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    employee_id: employeeId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // إغلاق الموديال والعرض رسالة نجاح
                    const modal = button.closest('.fixed');
                    modal.remove();
                    
                    // عرض رسالة نجاح
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg animate-in z-50 flex items-center gap-2';
                    successMsg.innerHTML = '<i class="ri-check-line text-xl"></i> تم إرسال الإشعار بنجاح';
                    document.body.appendChild(successMsg);
                    
                    setTimeout(() => successMsg.remove(), 3000);
                } else {
                    alert('خطأ: ' + (data.message || 'فشل في إرسال الإشعار'));
                    button.disabled = false;
                    button.innerHTML = '<i class="ri-send-plane-line"></i> إرسال';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في إرسال الإشعار');
                button.disabled = false;
                button.innerHTML = '<i class="ri-send-plane-line"></i> إرسال';
            });
        }

        window.closeNotificationModal = function(button) {
            const modal = button.closest('.fixed');
            modal.remove();
        }

        // تعيين موظفين للمشروع
        window.bulkAssignToProject = function() {
            window.location.href = `/projects/{{ $project->id }}/assign-employees`;
        }

        // وظائف إضافية
        window.generateEmployeeReport = function(employeeId) {
            window.open(`/employees/${employeeId}/report`, '_blank');
        }

        window.viewAttendance = function(employeeId) {
            window.location.href = `/employees/${employeeId}/attendance`;
        }

        window.viewPayroll = function(employeeId) {
            window.location.href = `/employees/${employeeId}/payroll`;
        }

        window.transferEmployee = function(employeeId) {
            window.location.href = `/employees/${employeeId}/transfer`;
        }

        // إغلاق مودال الموظف
        window.closeEmployeeModal = function(button) {
            const modal = button.closest('.fixed') || button;
            if (modal.classList && modal.classList.contains('fixed')) {
                modal.remove();
            } else if (button.closest('.fixed')) {
                button.closest('.fixed').remove();
            }
        }
    </script>

    <style>
        /* Location Cards Modern Styles */
        .location-card-modern {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .location-card-modern:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
        }

        .location-card-modern:hover .ri-arrow-left-s-line {
            transform: translateX(-4px);
            color: #3b82f6;
        }

        /* Modal Styles */
        #locationModal {
            animation: fadeIn 0.3s ease-out;
        }

        #locationModal .bg-white {
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Tab Content Animation */
        .tab-content {
            animation: fadeInContent 0.3s ease-out;
        }

        @keyframes fadeInContent {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Smooth transitions for stats boxes */
        .location-card-modern .bg-green-50,
        .location-card-modern .bg-orange-50,
        .location-card-modern .bg-blue-50,
        .location-card-modern .bg-purple-50 {
            transition: all 0.3s ease;
        }

        .location-card-modern:hover .bg-green-50 {
            background-color: #dcfce7;
            transform: scale(1.02);
        }

        .location-card-modern:hover .bg-orange-50 {
            background-color: #fed7aa;
            transform: scale(1.02);
        }

        .location-card-modern:hover .bg-blue-50 {
            background-color: #dbeafe;
            transform: scale(1.02);
        }

        .location-card-modern:hover .bg-purple-50 {
            background-color: #e879f9;
            transform: scale(1.02);
        }

        /* Icon animations */
        .location-card-modern .w-8.h-8 {
            transition: all 0.3s ease;
        }

        .location-card-modern:hover .w-8.h-8 {
            transform: rotate(5deg) scale(1.1);
        }

        /* Responsive table fixes */
        .table-responsive {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Hide horizontal scrollbar on main page */
        body {
            overflow-x: hidden;
        }

        /* Ensure containers don't exceed viewport width */
        .container,
        .max-w-full,
        .w-full {
            max-width: 100vw;
            box-sizing: border-box;
        }

        /* Fix for mobile table cells */
        @media (max-width: 640px) {
            .table-cell-compact {
                padding: 0.5rem 0.25rem !important;
                font-size: 0.75rem;
            }

            .hidden-mobile {
                display: none !important;
            }
        }

        /* Prevent text overflow */
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Progress Slider Styles */
        .slider {
            background: linear-gradient(to right, #e5e7eb 0%, #e5e7eb 100%);
        }

        .slider::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
            transition: all 0.2s ease;
        }

        .slider::-webkit-slider-thumb:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
            border: none;
            transition: all 0.2s ease;
        }

        .slider::-moz-range-thumb:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .slider::-webkit-slider-track {
            background: linear-gradient(to right, #3b82f6 0%, #e5e7eb 0%);
            height: 8px;
            border-radius: 4px;
        }

        .slider::-moz-range-track {
            background: linear-gradient(to right, #3b82f6 0%, #e5e7eb 0%);
            height: 8px;
            border-radius: 4px;
            border: none;
        }

        /* Progressive color change animation */
        @keyframes progressUpdate {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .progress-animate {
            animation: progressUpdate 0.3s ease-in-out;
        }

        /* Interactive progress bar hover effects */
        .progress-bar-container:hover .progress-info {
            opacity: 1;
            visibility: visible;
        }

        .progress-info {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        /* Tooltip styles */
        .tooltip {
            position: relative;
        }

        .tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 5px;
        }

        .tooltip:hover::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
        }
    </style>

    <!-- Modal تمديد فترة المشروع -->
    <div id="extendModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeExtendModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
                <div class="bg-amber-600 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-semibold">تمديد فترة المشروع</h3>
                </div>
                <form id="extendForm" method="POST" action="{{ route('projects.extend', $project) }}">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الانتهاء الحالي</label>
                            <input type="text"
                                value="{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : 'غير محدد' }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الانتهاء الجديد <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="new_end_date" id="new_end_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                required min="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">سبب التمديد <span
                                    class="text-red-500">*</span></label>
                            <textarea name="extension_reason" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                                placeholder="اكتب سبب التمديد..." required></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 rounded-b-lg flex justify-end gap-3">
                        <button type="button" onclick="closeExtendModal()"
                            class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            إلغاء
                        </button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">
                            حفظ التمديد
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تسجيل زيارة -->
    <div id="visitModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeVisitModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full" onclick="event.stopPropagation()">
                <div class="bg-green-600 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-semibold">تسجيل زيارة مشروع</h3>
                </div>
                <form id="visitForm" method="POST" action="{{ route('projects.visit.store', $project) }}">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الزيارة <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="visit_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">وقت الزيارة</label>
                                <input type="time" name="visit_time"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    value="{{ now()->format('H:i') }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الزائر <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="visitor_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="أدخل اسم الزائر" required>
                            <input type="hidden" name="visitor_employee_id" id="visitor_employee_id">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع الزيارة</label>
                            <select name="visit_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                <option value="inspection">جولة تفتيش</option>
                                <option value="meeting">اجتماع</option>
                                <option value="supervision">إشراف</option>
                                <option value="coordination">تنسيق</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">المدة (ساعات)</label>
                                <input type="number" step="0.25" min="0" max="24"
                                    name="duration_hours"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="مثال: 1.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الغرض</label>
                                <input type="text" name="purpose"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="الغرض من الزيارة">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">تفاصيل الزيارة <span
                                    class="text-red-500">*</span></label>
                            <textarea name="visit_notes" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="اكتب تفاصيل الزيارة والملاحظات..." required></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 rounded-b-lg flex justify-end gap-3">
                        <button type="button" onclick="closeVisitModal()"
                            class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            إلغاء
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            حفظ الزيارة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تسجيل معدة مستأجرة -->
    <div id="rentalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeRentalModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full" onclick="event.stopPropagation()">
                <div class="bg-purple-600 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-semibold">تسجيل معدة مستأجرة</h3>
                </div>
                <form id="rentalForm" method="POST"
                    action="{{ route('projects.rental.store', ['project' => $project]) }}">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع المعدة <span
                                    class="text-red-500">*</span></label>
                            <select name="equipment_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                required>
                                <option value="">اختر نوع المعدة</option>
                                <option value="excavator">حفار</option>
                                <option value="bulldozer">جرافة</option>
                                <option value="crane">رافعة</option>
                                <option value="truck">شاحنة</option>
                                <option value="concrete_mixer">خلاطة خرسانة</option>
                                <option value="generator">مولد كهرباء</option>
                                <option value="compressor">ضاغط هواء</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم/رقم المعدة <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="equipment_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                placeholder="مثال: حفار كوماتسو PC200" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">المورد/الشركة المؤجرة <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="rental_company"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                placeholder="اسم الشركة أو المورد" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ بداية الإيجار <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="rental_start_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                    value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ نهاية الإيجار</label>
                                <input type="date" name="rental_end_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تكلفة الإيجار (يومي)</label>
                                <input type="number" name="daily_rate" step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">العملة</label>
                                <select name="currency"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                    <option value="SAR">ريال سعودي</option>
                                    <option value="USD">دولار أمريكي</option>
                                    <option value="EUR">يورو</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                placeholder="أي ملاحظات إضافية..."></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 rounded-b-lg flex justify-end gap-3">
                        <button type="button" onclick="closeRentalModal()"
                            class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                            حفظ المعدة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تسجيل قرض -->
    <div id="loanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeLoanModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full" onclick="event.stopPropagation()">
                <div class="bg-red-600 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-semibold">تسجيل قرض على المشروع</h3>
                </div>
                <form id="loanForm" method="POST" action="{{ route('projects.loan.store', $project) }}">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ القرض (ر.س) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="loan_amount" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                placeholder="0.00" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مصدر القرض <span
                                    class="text-red-500">*</span></label>
                            <select name="loan_source"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                required>
                                <option value="">اختر مصدر القرض</option>
                                <option value="bank">بنك</option>
                                <option value="company">شركة</option>
                                <option value="individual">فرد</option>
                                <option value="government">جهة حكومية</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم الجهة المقرضة <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="lender_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                placeholder="مثال: البنك الأهلي السعودي" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ القرض <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="loan_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                    value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الاستحقاق</label>
                                <input type="date" name="due_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">معدل الفائدة (%)</label>
                                <input type="number" name="interest_rate" step="0.01" min="0"
                                    max="100"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                    placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نوع الفائدة</label>
                                <select name="interest_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                                    <option value="">بدون فائدة</option>
                                    <option value="fixed">ثابتة</option>
                                    <option value="variable">متغيرة</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الغرض من القرض <span
                                    class="text-red-500">*</span></label>
                            <select name="loan_purpose"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                required>
                                <option value="">اختر الغرض</option>
                                <option value="equipment">شراء معدات</option>
                                <option value="materials">شراء مواد</option>
                                <option value="wages">دفع أجور</option>
                                <option value="operations">تكاليف تشغيلية</option>
                                <option value="expansion">توسعة المشروع</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                placeholder="أي ملاحظات إضافية عن القرض..."></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 rounded-b-lg flex justify-end gap-3">
                        <button type="button" onclick="closeLoanModal()"
                            class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            إلغاء
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            حفظ القرض
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تحديث نسبة الإنجاز -->
    <div id="progressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeProgressModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-semibold">تحديث نسبة إنجاز المشروع</h3>
                </div>
                <form id="progressForm" method="POST" action="{{ route('projects.updateProgress', $project) }}">
                    @csrf
                    @method('PATCH')
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">النسبة الحالية</label>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full"
                                        style="width: {{ $project->progress }}%"></div>
                                </div>
                                <span
                                    class="text-sm font-medium text-gray-900 min-w-max">{{ number_format($project->progress) }}%</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">النسبة الجديدة <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="range" name="progress" id="progressSlider" min="0"
                                    max="100" value="{{ $project->progress }}"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                                    oninput="updateProgressPreview(this.value)">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>0%</span>
                                    <span>25%</span>
                                    <span>50%</span>
                                    <span>75%</span>
                                    <span>100%</span>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    id="progressPreview">
                                    {{ number_format($project->progress) }}%
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تفاصيل التحديث</label>
                            <textarea name="update_notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                placeholder="اكتب تفاصيل التقدم المحرز أو الأعمال المكتملة..."></textarea>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <i class="ri-information-line text-blue-600 mt-0.5 mr-2"></i>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium mb-1">نصائح لتحديث نسبة الإنجاز:</p>
                                    <ul class="list-disc list-inside space-y-1 text-xs">
                                        <li>قم بتحديث النسبة بناءً على الأعمال المكتملة فعلياً</li>
                                        <li>أضف تفاصيل واضحة عن التقدم المحرز</li>
                                        <li>راجع جميع مراحل المشروع قبل التحديث</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 rounded-b-lg flex justify-end gap-3">
                        <button type="button" onclick="closeProgressModal()"
                            class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            إلغاء
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            تحديث النسبة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // وظائف المودالات
        function openExtendModal() {
            document.getElementById('extendModal').classList.remove('hidden');
        }

        function closeExtendModal() {
            document.getElementById('extendModal').classList.add('hidden');
        }

        function openVisitModal() {
            document.getElementById('visitModal').classList.remove('hidden');
        }

        function closeVisitModal() {
            document.getElementById('visitModal').classList.add('hidden');
        }

        function openRentalModal() {
            document.getElementById('rentalModal').classList.remove('hidden');
        }

        function closeRentalModal() {
            document.getElementById('rentalModal').classList.add('hidden');
        }

        function openLoanModal() {
            document.getElementById('loanModal').classList.remove('hidden');
        }

        function closeLoanModal() {
            document.getElementById('loanModal').classList.add('hidden');
        }

        // وظائف مودال تحديث نسبة الإنجاز
        function openProgressModal() {
            document.getElementById('progressModal').classList.remove('hidden');

            // تأثير صوتي خفيف (اختياري - يمكن إزالته)
            try {
                // إنشاء تأثير صوتي بسيط
                const audio = new AudioContext();
                const oscillator = audio.createOscillator();
                const gainNode = audio.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audio.destination);

                oscillator.frequency.value = 800;
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0, audio.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.1, audio.currentTime + 0.01);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audio.currentTime + 0.1);

                oscillator.start(audio.currentTime);
                oscillator.stop(audio.currentTime + 0.1);
            } catch (e) {
                // تجاهل الأخطاء الصوتية
            }

            // تركيز على شريط التمرير
            setTimeout(() => {
                const slider = document.getElementById('progressSlider');
                if (slider) {
                    slider.focus();
                }
            }, 100);
        }

        function updateProgressPreview(value) {
            const preview = document.getElementById('progressPreview');
            const percentage = parseInt(value);

            // تحديث النص
            preview.textContent = percentage + '%';

            // تحديث الألوان بناءً على النسبة
            preview.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ';

            if (percentage < 25) {
                preview.className += 'bg-red-100 text-red-800';
            } else if (percentage < 50) {
                preview.className += 'bg-orange-100 text-orange-800';
            } else if (percentage < 75) {
                preview.className += 'bg-yellow-100 text-yellow-800';
            } else if (percentage < 100) {
                preview.className += 'bg-blue-100 text-blue-800';
            } else {
                preview.className += 'bg-green-100 text-green-800';
            }

            // تحديث شريط التقدم في المعاينة
            const previewBar = document.querySelector('#progressModal .bg-blue-600');
            if (previewBar) {
                previewBar.style.width = percentage + '%';
                previewBar.classList.add('progress-animate');
                setTimeout(() => {
                    previewBar.classList.remove('progress-animate');
                }, 300);
            }

            // تحديث شريط التقدم الرئيسي في الوقت الفعلي (معاينة فقط)
            const mainProgressBar = document.getElementById('progress-bar');
            const mainProgressPercentage = document.getElementById('progress-percentage');

            if (mainProgressBar && mainProgressPercentage) {
                // إضافة تأثير بصري
                mainProgressBar.style.width = percentage + '%';
                mainProgressPercentage.textContent = percentage + '%';

                // تحديث لون الشريط الرئيسي
                mainProgressBar.className = 'h-3 rounded-full transition-all duration-500 ';
                if (percentage < 25) {
                    mainProgressBar.className += 'bg-gradient-to-r from-red-500 to-red-600';
                } else if (percentage < 50) {
                    mainProgressBar.className += 'bg-gradient-to-r from-orange-500 to-orange-600';
                } else if (percentage < 75) {
                    mainProgressBar.className += 'bg-gradient-to-r from-yellow-500 to-yellow-600';
                } else if (percentage < 100) {
                    mainProgressBar.className += 'bg-gradient-to-r from-blue-500 to-blue-600';
                } else {
                    mainProgressBar.className += 'bg-gradient-to-r from-green-500 to-green-600';
                }

                // تحديث نص الحالة
                const statusTexts = document.querySelectorAll('.text-center');
                statusTexts.forEach(element => {
                    if (element.textContent.includes('بداية المشروع') ||
                        element.textContent.includes('في التطوير') ||
                        element.textContent.includes('قرب الانتهاء')) {
                        if (percentage < 25) {
                            element.textContent = 'بداية المشروع';
                        } else if (percentage < 50) {
                            element.textContent = 'في مرحلة مبكرة';
                        } else if (percentage < 75) {
                            element.textContent = 'في التطوير';
                        } else if (percentage < 100) {
                            element.textContent = 'قرب الانتهاء';
                        } else {
                            element.textContent = 'مكتمل!';
                        }
                    }
                });
            }
        }

        // إعادة تعيين الشريط الرئيسي عند إغلاق المودال
        function closeProgressModal() {
            document.getElementById('progressModal').classList.add('hidden');

            // إعادة الشريط الرئيسي لحالته الأصلية
            const mainProgressBar = document.getElementById('progress-bar');
            const mainProgressPercentage = document.getElementById('progress-percentage');

            if (mainProgressBar && mainProgressPercentage) {
                const originalProgress = {{ $project->progress }};
                mainProgressBar.style.width = originalProgress + '%';
                mainProgressPercentage.textContent = originalProgress + '%';

                // إعادة اللون الأصلي
                mainProgressBar.className =
                    'bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500 hover:from-blue-600 hover:to-blue-700';

                // إعادة نص الحالة الأصلي
                const statusTexts = document.querySelectorAll('.text-center');
                statusTexts.forEach(element => {
                    if (element.textContent.includes('بداية المشروع') ||
                        element.textContent.includes('في التطوير') ||
                        element.textContent.includes('قرب الانتهاء') ||
                        element.textContent.includes('في مرحلة مبكرة') ||
                        element.textContent.includes('مكتمل!')) {
                        if (originalProgress < 50) {
                            element.textContent = 'بداية المشروع';
                        } else if (originalProgress < 80) {
                            element.textContent = 'في التطوير';
                        } else {
                            element.textContent = 'قرب الانتهاء';
                        }
                    }
                });
            }
        }

        // إغلاق المودال بالضغط على Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeExtendModal();
                closeVisitModal();
                closeRentalModal();
                closeLoanModal();
                closeProgressModal();
            }
        });

        // إضافة مستمعي الأحداث عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // تحسين تجربة استخدام شريط التمرير
            const slider = document.getElementById('progressSlider');
            if (slider) {
                // إضافة تأثيرات بصرية عند الاستخدام
                slider.addEventListener('input', function(e) {
                    updateProgressPreview(e.target.value);
                });

                // تأثير عند بداية التحريك
                slider.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(1.02)';
                });

                // إزالة التأثير عند الانتهاء
                slider.addEventListener('mouseup', function() {
                    this.style.transform = 'scale(1)';
                });
            }

            // إضافة تأثيرات للشريط الرئيسي
            const mainProgressBar = document.getElementById('progress-bar');
            if (mainProgressBar) {
                mainProgressBar.addEventListener('mouseover', function() {
                    this.style.transform = 'scaleY(1.1)';
                });

                mainProgressBar.addEventListener('mouseout', function() {
                    this.style.transform = 'scaleY(1)';
                });
            }

            // إضافة تأثير نبضة للأزرار التفاعلية
            const editButtons = document.querySelectorAll('[onclick*="openProgressModal"]');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });
        });
    </script>

    <!-- Add Images Modal -->
    <div id="addImagesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
        onclick="closeAddImagesModal()">
        <div class="bg-white rounded-2xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto"
            onclick="event.stopPropagation()" dir="rtl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-cyan-600 flex items-center justify-center">
                        <i class="ri-image-add-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">إضافة صور جديدة للمشروع</h3>
                        <p class="text-gray-600 text-sm">{{ $project->name }}</p>
                    </div>
                </div>
                <button onclick="closeAddImagesModal()"
                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                    <i class="ri-close-line text-gray-600"></i>
                </button>
            </div>

            <!-- Upload Form -->
            <form id="uploadImagesForm" action="{{ route('projects.images.store', $project) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <!-- File Upload Area -->
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center mb-6 hover:border-cyan-400 transition-colors"
                    id="uploadArea">
                    <input type="file" id="imageFiles" name="images[]" multiple accept="image/*" class="hidden"
                        onchange="handleFileSelect(this)">

                    <div id="uploadPrompt">
                        <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-image-add-line text-cyan-600 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">اختر الصور للرفع</h4>
                        <p class="text-gray-600 mb-4">يمكنك اختيار عدة صور في نفس الوقت</p>
                        <button type="button" onclick="document.getElementById('imageFiles').click()"
                            class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="ri-upload-2-line ml-2"></i>
                            اختيار الصور
                        </button>
                        <p class="text-sm text-gray-500 mt-3">الأنواع المدعومة: JPG, JPEG, PNG, GIF (حد أقصى 5 ميجابايت
                            لكل صورة)</p>
                    </div>

                    <!-- Preview Area -->
                    <div id="imagePreview" class="hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">معاينة الصور</h4>
                        <div id="previewGrid" class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4"></div>
                        <button type="button" onclick="clearSelection()"
                            class="text-red-600 hover:text-red-700 font-medium">
                            <i class="ri-delete-bin-line ml-1"></i>
                            مسح التحديد
                        </button>
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="imageDescription" class="block text-sm font-semibold text-gray-700 mb-2">
                            وصف الصور (اختياري)
                        </label>
                        <textarea id="imageDescription" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all duration-200"
                            placeholder="أضف وصفاً للصور أو تفاصيل حول ما تُظهره..."></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between">
                    <button type="button" onclick="closeAddImagesModal()"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" id="uploadButton"
                        class="px-8 py-3 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <i class="ri-upload-2-line ml-2"></i>
                        رفع الصور
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add Images Modal Functions
        function openAddImagesModal() {
            document.getElementById('addImagesModal').classList.remove('hidden');
            document.getElementById('addImagesModal').classList.add('flex');
        }

        function closeAddImagesModal() {
            document.getElementById('addImagesModal').classList.add('hidden');
            document.getElementById('addImagesModal').classList.remove('flex');
            // Reset form
            document.getElementById('uploadImagesForm').reset();
            clearSelection();
        }

        function handleFileSelect(input) {
            const files = input.files;
            const previewArea = document.getElementById('imagePreview');
            const uploadPrompt = document.getElementById('uploadPrompt');
            const previewGrid = document.getElementById('previewGrid');
            const uploadButton = document.getElementById('uploadButton');

            if (files.length > 0) {
                uploadPrompt.classList.add('hidden');
                previewArea.classList.remove('hidden');
                previewGrid.innerHTML = '';

                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative group';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 group-hover:border-cyan-400 transition-colors">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center">
                                    <button type="button" onclick="removeImage(${index})" 
                                            class="w-8 h-8 bg-red-600 text-white rounded-full items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hidden">
                                        <i class="ri-close-line text-sm"></i>
                                    </button>
                                </div>
                                <div class="absolute bottom-2 left-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                                    ${file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name}
                                </div>
                            `;
                            previewGrid.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                uploadButton.disabled = false;
            } else {
                clearSelection();
            }
        }

        function clearSelection() {
            document.getElementById('imageFiles').value = '';
            document.getElementById('uploadPrompt').classList.remove('hidden');
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('uploadButton').disabled = true;
        }

        // Handle form submission
        document.getElementById('uploadImagesForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const uploadButton = document.getElementById('uploadButton');
            const formData = new FormData(this);

            // Update button state
            uploadButton.innerHTML = '<i class="ri-loader-4-line ml-2 animate-spin"></i>جاري الرفع...';
            uploadButton.disabled = true;

            // Send AJAX request with proper headers for JSON response
            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'X-Requested-With': 'XMLHttpRequest', // This tells Laravel it's an AJAX request
                        'Accept': 'application/json' // This requests JSON response
                    }
                })
                .then(response => {
                    console.log('Response received:', response);
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);

                    // Check if response is ok
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    // Check content type
                    const contentType = response.headers.get('content-type');
                    console.log('Content type:', contentType);

                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Server returned HTML instead of JSON:', text);
                            throw new Error('Server returned HTML instead of JSON. Check server logs.');
                        });
                    }

                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('تم رفع الصور بنجاح!', 'success');

                        // Close modal and refresh page
                        closeAddImagesModal();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'حدث خطأ في رفع الصور');
                    }
                })
                .catch(error => {
                    console.error('Upload Error:', error);

                    // More specific error handling
                    let errorMessage = 'حدث خطأ في رفع الصور';

                    if (error.message.includes('JSON')) {
                        errorMessage = 'خطأ في الاستجابة من الخادم. يرجى المحاولة مرة أخرى.';
                    } else if (error.message.includes('HTTP error')) {
                        errorMessage = 'خطأ في الاتصال بالخادم. تحقق من الاتصال بالإنترنت.';
                    } else if (error.message) {
                        errorMessage = error.message;
                    }

                    showNotification(errorMessage, 'error');

                    // Reset button
                    uploadButton.innerHTML = '<i class="ri-upload-2-line ml-2"></i>رفع الصور';
                    uploadButton.disabled = false;
                });
        });

        // Notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-600' : 
                type === 'error' ? 'bg-red-600' : 'bg-blue-600'
            }`;
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="ri-${type === 'success' ? 'check' : type === 'error' ? 'error-warning' : 'information'}-line"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Animate out and remove
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>

    <!-- موديال تعديل رقم الطلب -->
    <div id="editDeliveryModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" dir="rtl">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">تعديل رقم الطلب</h3>
            <form id="editDeliveryForm" onsubmit="submitEditDelivery(event)" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="deliveryRequestId" name="delivery_request_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الطلب الجديد</label>
                    <input type="text" id="requestNumberInput" name="request_number" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="أدخل رقم الطلب">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        حفظ
                    </button>
                    <button type="button" onclick="closeEditDeliveryModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Image Modal Styles */
        #imageModal {
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }

        #imageModal.hidden {
            opacity: 0;
            visibility: hidden;
        }

        #imageModal:not(.hidden) {
            opacity: 1;
            visibility: visible;
        }

        #modalImage {
            max-width: 90vw;
            max-height: 90vh;
            transition: transform 0.3s ease;
        }

        #modalImage:hover {
            transform: scale(1.02);
        }

        /* Ensure modal is above everything */
        #imageModal {
            z-index: 99999 !important;
        }

        /* Smooth hover effects for images */
        .project-image {
            transition: all 0.3s ease;
        }

        .project-image:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Loading animation for images */
        .project-image.loading {
            opacity: 0.5;
            filter: blur(2px);
        }

        /* Notification Modal Animation */
        .animate-in {
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Spin animation for loader */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>

    <script>
        function editDeliveryRequest(id, currentNumber) {
            document.getElementById('deliveryRequestId').value = id;
            document.getElementById('requestNumberInput').value = currentNumber || '';
            document.getElementById('editDeliveryModal').classList.remove('hidden');
            document.getElementById('requestNumberInput').focus();
        }

        function closeEditDeliveryModal() {
            document.getElementById('editDeliveryModal').classList.add('hidden');
            document.getElementById('editDeliveryForm').reset();
        }

        function submitEditDelivery(event) {
            event.preventDefault();
            const requestId = document.getElementById('deliveryRequestId').value;
            const requestNumber = document.getElementById('requestNumberInput').value;
            const projectId = {{ $project->id }};

            fetch(`/projects/${projectId}/delivery-requests/${requestId}/update`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        request_number: requestNumber
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('فشل التحديث');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('تم التحديث بنجاح');
                        closeEditDeliveryModal();
                        location.reload();
                    } else {
                        alert('خطأ: ' + (data.message || 'فشل التحديث'));
                    }
                })
                .catch(error => {
                    alert('حدث خطأ: ' + error.message);
                });
        }

        // إغلاق الموديال عند النقر خارجه
        document.getElementById('editDeliveryModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditDeliveryModal();
            }
        });
    </script>
@endsection
