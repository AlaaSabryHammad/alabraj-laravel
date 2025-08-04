@extends('layouts.app')

@section('title', $project->name)

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

                        <!-- جدول كميات المشروع -->
                        @if ($project->projectItems && $project->projectItems->count() > 0)
                            <div class="mt-8">
                                <h3 class="text-sm font-medium text-gray-700 mb-3">جدول كميات المشروع</h3>
                                <div class="overflow-x-auto bg-gray-50 rounded-lg p-4">
                                    <table class="min-w-full text-sm text-right border">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-3 py-2 border">اسم البند</th>
                                                <th class="px-3 py-2 border">الكمية</th>
                                                <th class="px-3 py-2 border">الوحدة</th>
                                                <th class="px-3 py-2 border">السعر الإفرادي</th>
                                                <th class="px-3 py-2 border">الإجمالي</th>
                                                <th class="px-3 py-2 border">الإجمالي مع الضريبة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = 0;
                                                $totalWithTax = 0;
                                            @endphp
                                            @foreach ($project->projectItems as $item)
                                                <tr>
                                                    <td class="px-3 py-2 border">{{ $item->name }}</td>
                                                    <td class="px-3 py-2 border">{{ number_format($item->quantity, 2) }}
                                                    </td>
                                                    <td class="px-3 py-2 border">{{ $item->unit }}</td>
                                                    <td class="px-3 py-2 border">{{ number_format($item->unit_price, 2) }}
                                                    </td>
                                                    <td class="px-3 py-2 border">
                                                        {{ number_format($item->total_price, 2) }}</td>
                                                    <td class="px-3 py-2 border">
                                                        {{ number_format($item->total_with_tax, 2) }}</td>
                                                </tr>
                                                @php
                                                    $total += $item->total_price;
                                                    $totalWithTax += $item->total_with_tax;
                                                @endphp
                                            @endforeach
                                            <tr class="font-bold bg-gray-200">
                                                <td class="px-3 py-2 border text-center" colspan="4">الإجمالي</td>
                                                <td class="px-3 py-2 border">{{ number_format($total, 2) }}</td>
                                                <td class="px-3 py-2 border">{{ number_format($totalWithTax, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Project Images -->

                        <!-- صور المشروع (تم نقلها لأسفل الصفحة) -->

                        <!-- Delivery Requests -->
                        @if ($project->deliveryRequests && $project->deliveryRequests->count() > 0)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-3">طلبات استلام الأعمال</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="space-y-3">
                                        @foreach ($project->deliveryRequests as $request)
                                            <div class="bg-white rounded-lg p-4 border">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <i class="ri-file-list-3-line text-gray-600"></i>
                                                        <div>
                                                            @if ($request->number)
                                                                <p class="font-medium text-gray-900">طلب رقم:
                                                                    {{ $request->number }}</p>
                                                            @endif
                                                            @if ($request->description)
                                                                <p class="text-sm text-gray-600">
                                                                    {{ $request->description }}</p>
                                                            @endif
                                                            @if ($request->created_at)
                                                                <p class="text-xs text-gray-500">تاريخ الإنشاء:
                                                                    {{ $request->created_at->format('d/m/Y H:i') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if ($request->file_path)
                                                        <a href="{{ asset('storage/' . $request->file_path) }}"
                                                            target="_blank"
                                                            class="text-blue-600 hover:text-blue-800 transition-colors">
                                                            <i class="ri-download-line"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
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
            </div>
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
    </script>
@endsection
