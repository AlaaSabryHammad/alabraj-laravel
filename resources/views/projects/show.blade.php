@extends('layouts.app')

@section('title', $project->name)

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <div class="p-6" dir="rtl">
        <!-- زر عمل مستخلص جديد -->
        <div class="mb-6 flex justify-end">
            <a href="{{ route('projects.extract.create', $project) }}" target="_blank"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 shadow">
                <i class="ri-file-list-3-line text-lg"></i>
                عمل مستخلص جديد
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
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">نسبة الإنجاز</span>
                        <span class="text-sm font-medium text-gray-900">{{ number_format($project->progress) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-300"
                            style="width: {{ $project->progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
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
                                            'employees' => $location->employees->map(function($emp) {
                                                return [
                                                    'name' => $emp->name,
                                                    'position' => $emp->position ?? 'موظف',
                                                    'phone' => $emp->phone,
                                                    'email' => $emp->email
                                                ];
                                            }),
                                            'equipment' => $location->equipment->map(function($eq) {
                                                return [
                                                    'name' => $eq->name,
                                                    'type' => $eq->type,
                                                    'model' => $eq->model,
                                                    'status' => $eq->status,
                                                    'serial_number' => $eq->serial_number
                                                ];
                                            })
                                         ]) }}"
                                         onclick="openLocationModal(this)">

                                        <div class="p-6">
                                            <!-- Header Section -->
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex items-center gap-4">
                                                    <!-- Location Icon -->
                                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                                        <i class="ri-map-pin-line text-blue-600 text-xl"></i>
                                                    </div>

                                                    <!-- Location Info -->
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <h3 class="text-lg font-semibold text-gray-900">{{ $location->name }}</h3>
                                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-lg">
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
                                                    <div class="w-8 h-8 bg-green-100 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                                        <i class="ri-group-line text-green-600"></i>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mb-1">العمالة</p>
                                                    <p class="text-lg font-bold text-gray-900">{{ $location->employees->count() }}</p>
                                                </div>

                                                <!-- Equipment Count -->
                                                <div class="bg-orange-50 rounded-lg p-3 text-center">
                                                    <div class="w-8 h-8 bg-orange-100 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                                        <i class="ri-truck-line text-orange-600"></i>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mb-1">المعدات</p>
                                                    <p class="text-lg font-bold text-gray-900">{{ $location->equipment->count() }}</p>
                                                </div>

                                                <!-- Available Equipment -->
                                                <div class="bg-blue-50 rounded-lg p-3 text-center">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                                        <i class="ri-check-line text-blue-600"></i>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mb-1">متاح</p>
                                                    <p class="text-lg font-bold text-gray-900">
                                                        {{ $location->equipment->where('status', 'available')->count() }}
                                                    </p>
                                                </div>

                                                <!-- Manager Info -->
                                                <div class="bg-purple-50 rounded-lg p-3 text-center">
                                                    <div class="w-8 h-8 bg-purple-100 rounded-lg mx-auto mb-2 flex items-center justify-center">
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
                                                    <p class="text-sm text-gray-700">{{ Str::limit($location->description, 100) }}</p>
                                                </div>
                                            @endif

                                            <!-- Action Button -->
                                            <div class="border-t border-gray-200 pt-4">
                                                <div class="flex items-center justify-center text-blue-600 hover:text-blue-700 transition-colors">
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
    <div id="locationModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4" onclick="closeLocationModal()">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
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
                    <button onclick="closeLocationModal()" class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-colors">
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
                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                <i class="ri-group-line text-white text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600">إجمالي العمالة</p>
                            <p id="modalEmployeesCount" class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-red-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                <i class="ri-truck-line text-white text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600">إجمالي المعدات</p>
                            <p id="modalEquipmentCount" class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                <i class="ri-checkbox-circle-line text-white text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600">معدات متاحة</p>
                            <p id="modalAvailableEquipment" class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
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
                        <button onclick="switchTab('employees')" id="employeesTab" class="flex-1 py-4 px-6 text-center font-medium text-blue-600 border-b-2 border-blue-600 bg-blue-50">
                            <i class="ri-group-line ml-2"></i>
                            العمالة
                        </button>
                        <button onclick="switchTab('equipment')" id="equipmentTab" class="flex-1 py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700">
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($project->deliveryRequests as $index => $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 border text-center font-medium">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 border">
                                        @if ($request->number)
                                            <span
                                                class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $request->number }}
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
                                        {{ number_format($extract->total_amount, 2) }} ر.س
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
                                                <form action="{{ route('projects.extract.destroy', [$project, $extract]) }}"
                                                    method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخلص؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 p-1 rounded" title="حذف">
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

            @if($correspondences && $correspondences->count() > 0)
                <div class="space-y-4">
                    @foreach($correspondences as $correspondence)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if($correspondence->type === 'incoming')
                                                bg-green-100 text-green-800
                                            @else
                                                bg-blue-100 text-blue-800
                                            @endif">
                                            @if($correspondence->type === 'incoming')
                                                <i class="ri-inbox-line"></i>
                                                واردة
                                            @else
                                                <i class="ri-send-plane-line"></i>
                                                صادرة
                                            @endif
                                        </span>

                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if($correspondence->priority === 'urgent')
                                                bg-red-100 text-red-800
                                            @elseif($correspondence->priority === 'high')
                                                bg-orange-100 text-orange-800
                                            @elseif($correspondence->priority === 'medium')
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif">
                                            @if($correspondence->priority === 'urgent')
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
                                        @if($correspondence->type === 'incoming')
                                            <span class="flex items-center gap-1">
                                                <i class="ri-building-line text-xs"></i>
                                                من: {{ $correspondence->from_entity }}
                                            </span>
                                            @if($correspondence->assignedEmployee)
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

                                        @if($correspondence->file_path)
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
                                       class="text-blue-600 hover:text-blue-800 transition-colors"
                                       title="عرض التفاصيل">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    @if($correspondence->file_path)
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
                                    alt="{{ $image->name ?? 'صورة المشروع' }}"
                                    class="w-full h-40 object-cover rounded-lg cursor-pointer hover:shadow-lg transition-all hover:scale-105"
                                    onclick="showImageModal('{{ asset('storage/' . $image->image_path) }}', '{{ $image->name ?? 'صورة المشروع' }}')">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all flex items-center justify-center">
                                    <i
                                        class="ri-eye-line text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                                @if ($image->name)
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2 rounded-b-lg">
                                        <p class="text-xs text-center truncate">{{ $image->name }}</p>
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
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
            <div class="absolute top-4 right-4">
                <button onclick="closeImageModal()"
                    class="bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-800 rounded-full p-2 transition-all">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            <div id="modalImageTitle"
                class="absolute bottom-4 left-4 right-4 bg-black bg-opacity-50 text-white p-3 rounded-lg text-center">
            </div>
        </div>
    </div>

    <script>
        // Image modal functions
        function showImageModal(imageSrc, imageTitle) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalImageTitle');

            modalImage.src = imageSrc;
            modalImage.alt = imageTitle;
            modalTitle.textContent = imageTitle;
            modal.classList.remove('hidden');

            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');

            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
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
            downloadAnchorNode.setAttribute("download", "project_{{ $project->id }}_{{ $project->name }}_detailed.json");
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
                employeesTab.className = 'flex-1 py-4 px-6 text-center font-medium text-blue-600 border-b-2 border-blue-600 bg-blue-50';
                equipmentTab.className = 'flex-1 py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700';
            } else {
                equipmentTab.className = 'flex-1 py-4 px-6 text-center font-medium text-blue-600 border-b-2 border-blue-600 bg-blue-50';
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

                switch(eq.status) {
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
    </style>
@endsection
