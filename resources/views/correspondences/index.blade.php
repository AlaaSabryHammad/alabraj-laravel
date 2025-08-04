@extends('layouts.app')

@section('title', 'الاتصالات الإدارية')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">الاتصالات الإدارية</h1>
                @if(request('project_id'))
                    @php
                        $currentProject = $projects->where('id', request('project_id'))->first();
                    @endphp
                    @if($currentProject)
                        <div class="flex items-center gap-2 mt-2">
                            <p class="text-gray-600">مراسلات المشروع:</p>
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2 py-1 rounded">
                                {{ $currentProject->name }}
                            </span>
                            <a href="{{ route('projects.show', $currentProject) }}"
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                (عرض المشروع)
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-gray-600">إدارة المراسلات الواردة والصادرة</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @if(request('project_id'))
                    <a href="{{ route('correspondences.create', ['type' => 'incoming', 'project_id' => request('project_id')]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-mail-add-line"></i>
                        إضافة وارد
                    </a>
                    <a href="{{ route('correspondences.create', ['type' => 'outgoing', 'project_id' => request('project_id')]) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-mail-send-line"></i>
                        إضافة صادر
                    </a>
                @else
                    <a href="{{ route('correspondences.create', ['type' => 'incoming']) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-mail-add-line"></i>
                        إضافة وارد
                    </a>
                    <a href="{{ route('correspondences.create', ['type' => 'outgoing']) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-mail-send-line"></i>
                        إضافة صادر
                    </a>
                @endif
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي المراسلات</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $correspondences->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="ri-mail-line text-xl text-gray-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">تم الرد عليها</p>
                        <p class="text-2xl font-bold text-green-600">{{ $correspondences->where('status', 'replied')->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-check-line text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">في انتظار الرد</p>
                        <p class="text-2xl font-bold text-red-600">{{ $correspondences->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ri-time-line text-xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">العاجل</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $correspondences->where('priority', 'urgent')->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="ri-alarm-warning-line text-xl text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <form method="GET" action="{{ route('correspondences.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="البحث في الموضوع أو الرقم..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">جميع الأنواع</option>
                        <option value="incoming" {{ request('type') === 'incoming' ? 'selected' : '' }}>وارد</option>
                        <option value="outgoing" {{ request('type') === 'outgoing' ? 'selected' : '' }}>صادر</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الأولوية</label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">جميع الأولويات</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>متوسطة</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>عالية</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>عاجل</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">حالة الرد</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>لم يتم الرد</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>تم الرد</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>مغلق</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المشروع</label>
                    <select name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">جميع المشاريع</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex-1">
                        <i class="ri-search-line ml-1"></i>
                        بحث
                    </button>
                    <a href="{{ route('correspondences.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Correspondences Table -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right border">النوع</th>
                            <th class="px-4 py-3 text-right border">الرقم المرجعي</th>
                            <th class="px-4 py-3 text-right border">الرقم الخارجي</th>
                            <th class="px-4 py-3 text-right border">الموضوع</th>
                            <th class="px-4 py-3 text-right border">الجهة</th>
                            <th class="px-4 py-3 text-center border">التاريخ</th>
                            <th class="px-4 py-3 text-center border">الأولوية</th>
                            <th class="px-4 py-3 text-center border">المشروع</th>
                            <th class="px-4 py-3 text-center border">المسؤول</th>
                            <th class="px-4 py-3 text-center border">حالة الرد</th>
                            <th class="px-4 py-3 text-center border">الملف</th>
                            <th class="px-4 py-3 text-center border">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($correspondences as $correspondence)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 border">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $correspondence->type === 'incoming' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $correspondence->type_display }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border font-medium">{{ $correspondence->reference_number }}</td>
                                <td class="px-4 py-3 border">{{ $correspondence->external_number ?? '-' }}</td>
                                <td class="px-4 py-3 border">
                                    <div class="max-w-xs truncate" title="{{ $correspondence->subject }}">
                                        {{ $correspondence->subject }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 border">
                                    {{ $correspondence->type === 'incoming' ? $correspondence->from_entity : $correspondence->to_entity }}
                                </td>
                                <td class="px-4 py-3 border text-center">{{ $correspondence->correspondence_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 border text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        bg-{{ $correspondence->priority_color }}-100 text-{{ $correspondence->priority_color }}-700">
                                        {{ $correspondence->priority_display }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    {{ $correspondence->project->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    {{ $correspondence->assignedEmployee->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    @if($correspondence->status === 'replied')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <i class="ri-check-line"></i> تم الرد
                                        </span>
                                    @elseif($correspondence->status === 'in_progress')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                            <i class="ri-time-line"></i> قيد المعالجة
                                        </span>
                                    @elseif($correspondence->status === 'closed')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            <i class="ri-lock-line"></i> مغلق
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            <i class="ri-close-line"></i> لم يتم الرد
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    @if($correspondence->file_path)
                                        <a href="{{ route('correspondences.download', $correspondence) }}"
                                           class="text-blue-600 hover:text-blue-800" title="تحميل الملف">
                                            <i class="ri-file-download-line"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('correspondences.show', $correspondence) }}"
                                           class="text-blue-600 hover:text-blue-800 p-1 rounded" title="عرض">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('correspondences.edit', $correspondence) }}"
                                           class="text-green-600 hover:text-green-800 p-1 rounded" title="تعديل">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form method="POST" action="{{ route('correspondences.destroy', $correspondence) }}"
                                              class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه المراسلة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1 rounded" title="حذف">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-4 py-8 text-center text-gray-500">
                                    <i class="ri-mail-line text-4xl mb-2"></i>
                                    <p>لا توجد مراسلات</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($correspondences->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $correspondences->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
