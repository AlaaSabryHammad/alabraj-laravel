@extends('layouts.app')

@section('title', 'المعاملات الخاصة')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">المعاملات الخاصة</h1>
                <p class="text-gray-600">المراسلات الموجهة إليك والتي تحتاج للمتابعة والرد</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي المعاملات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $correspondences->total() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-task-line text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">في الانتظار</p>
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ $correspondences->where('status', 'pending')->count() }}
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="ri-time-line text-xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">قيد المعالجة</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $correspondences->where('status', 'in_progress')->count() }}
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-settings-line text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">تم الرد</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $correspondences->where('status', 'replied')->count() }}
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-check-double-line text-xl text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <form method="GET" action="{{ route('my-tasks.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">حالة المعاملة</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>تم الرد</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>مغلق</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">درجة الأهمية</label>
                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">جميع الأولويات</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>متوسطة</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>عالية</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>عاجل</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-search-line"></i>
                            فلترة
                        </button>
                        <a href="{{ route('my-tasks.index') }}" class="mr-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Correspondences List -->
        <div class="bg-white rounded-xl shadow-sm border">
            @if($correspondences->count() > 0)
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($correspondences as $correspondence)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                                @if($correspondence->status === 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($correspondence->status === 'in_progress')
                                                    bg-blue-100 text-blue-800
                                                @elseif($correspondence->status === 'replied')
                                                    bg-green-100 text-green-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif">
                                                @if($correspondence->status === 'pending')
                                                    <i class="ri-time-line"></i>
                                                    في الانتظار
                                                @elseif($correspondence->status === 'in_progress')
                                                    <i class="ri-settings-line"></i>
                                                    قيد المعالجة
                                                @elseif($correspondence->status === 'replied')
                                                    <i class="ri-check-double-line"></i>
                                                    تم الرد
                                                @else
                                                    <i class="ri-close-line"></i>
                                                    مغلق
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
                                            <span class="flex items-center gap-1">
                                                <i class="ri-building-line text-xs"></i>
                                                من: {{ $correspondence->from_entity }}
                                            </span>
                                            @if($correspondence->project)
                                                <span class="flex items-center gap-1 mt-1">
                                                    <i class="ri-folder-line text-xs"></i>
                                                    المشروع: {{ $correspondence->project->name }}
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

                                            @if($correspondence->replies_count > 0)
                                                <span class="flex items-center gap-1 text-green-600">
                                                    <i class="ri-reply-line"></i>
                                                    {{ $correspondence->replies_count }} رد
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('my-tasks.show', $correspondence) }}"
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors flex items-center gap-1">
                                            <i class="ri-eye-line"></i>
                                            عرض والرد
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $correspondences->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-task-line text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد معاملات</h3>
                    <p class="text-gray-600">لم يتم توجيه أي مراسلات إليك بعد</p>
                </div>
            @endif
        </div>
    </div>
@endsection
