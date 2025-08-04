@extends('layouts.app')

@section('title', 'إدارة المشاريع')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إدارة المشاريع</h1>
                <p class="text-gray-600 mt-1">متابعة وإدارة جميع مشاريع الشركة</p>
            </div>
            <a href="{{ route('projects.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-add-line"></i>
                إضافة مشروع جديد
            </a>
        </div>

        <!-- Project Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي المشاريع</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $projects->total() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-building-line text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">مشاريع نشطة</p>
                        <p class="text-2xl font-bold text-green-600">{{ $projects->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-play-circle-line text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">مشاريع مكتملة</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $projects->where('status', 'completed')->count() }}
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-checkbox-circle-line text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الميزانية</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($projects->sum('budget'), 0) }} ر.س
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="ri-money-dollar-circle-line text-xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Projects Table -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">قائمة المشاريع</h2>
            </div>

            <div class="overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                اسم المشروع
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                العميل
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                مدير المشروع
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                تاريخ البداية
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                تاريخ النهاية
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                الميزانية
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                نسبة الإنجاز
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($projects as $project)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $project->location }}</div>
                                        <!-- Show mobile info -->
                                        <div class="mt-1 sm:hidden">
                                            <div class="text-xs text-gray-600">{{ $project->client_name }}</div>
                                            <div class="text-xs text-gray-600">{{ $project->formatted_budget }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 hidden sm:table-cell">
                                    <div class="text-sm text-gray-900">{{ $project->client_name }}</div>
                                </td>
                                <td class="px-4 py-4 hidden md:table-cell">
                                    <div class="text-sm text-gray-900">{{ $project->project_manager }}</div>
                                </td>
                                <td class="px-4 py-4 hidden lg:table-cell text-sm text-gray-900">
                                    {{ $project->start_date->format('Y-m-d') }}
                                </td>
                                <td class="px-4 py-4 hidden lg:table-cell text-sm text-gray-900">
                                    {{ $project->end_date ? $project->end_date->format('Y-m-d') : 'غير محدد' }}
                                    @if ($project->days_remaining !== null && $project->status === 'active')
                                        <div
                                            class="text-xs {{ $project->days_remaining <= 7 ? 'text-red-500' : 'text-gray-500' }}">
                                            باقي {{ $project->days_remaining }} يوم
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 hidden md:table-cell">
                                    <div class="text-sm font-medium text-gray-900">{{ $project->formatted_budget }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 max-w-16">
                                            <div class="bg-blue-600 h-2 rounded-full"
                                                style="width: {{ $project->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                        <span
                                            class="mr-2 text-xs text-gray-900">{{ $project->progress_percentage ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($project->status_color === 'green') bg-green-100 text-green-800
                                    @elseif($project->status_color === 'blue') bg-blue-100 text-blue-800
                                    @elseif($project->status_color === 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($project->status_color === 'red') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ $project->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('projects.show', $project) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors p-1"
                                            title="عرض التفاصيل">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('projects.edit', $project) }}"
                                            class="text-blue-600 hover:text-blue-900 transition-colors p-1" title="تعديل">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                            class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition-colors p-1"
                                                title="حذف">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="ri-building-line text-4xl text-gray-300 mb-2"></i>
                                        <p class="text-gray-500">لا توجد مشاريع مسجلة</p>
                                        <a href="{{ route('projects.create') }}"
                                            class="mt-2 text-blue-600 hover:text-blue-800">
                                            إضافة أول مشروع
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($projects->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
    </div>
@endsection
