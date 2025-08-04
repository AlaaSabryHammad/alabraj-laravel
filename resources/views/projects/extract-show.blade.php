@extends('layouts.app')

@section('title', 'عرض المستخلص - ' . $extract->extract_number)

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp
    <div class="p-6" dir="rtl">
        <!-- Header with project info -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">عرض المستخلص</h1>
                    <p class="text-gray-600">{{ $project->name }}</p>
                    <p class="text-sm text-gray-500">المستخلص رقم: {{ $extract->extract_number }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-printer-line"></i>
                        طباعة
                    </button>
                    @if ($extract->status === 'draft')
                        <a href="{{ route('projects.extract.edit', [$project, $extract]) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-edit-line"></i>
                            تعديل
                        </a>
                    @endif
                    <a href="{{ route('projects.show', $project) }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-arrow-right-line"></i>
                        العودة
                    </a>
                </div>
            </div>

            <!-- Extract basic info grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">رقم المستخلص:</span>
                    <span class="text-gray-900">{{ $extract->extract_number }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">تاريخ المستخلص:</span>
                    <span class="text-gray-900">{{ $extract->extract_date->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">الحالة:</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        @if ($extract->status === 'draft') bg-gray-100 text-gray-700
                        @elseif($extract->status === 'submitted') bg-blue-100 text-blue-700
                        @elseif($extract->status === 'approved') bg-green-100 text-green-700
                        @elseif($extract->status === 'paid') bg-purple-100 text-purple-700 @endif">
                        {{ $extract->status_display }}
                    </span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">المبلغ الإجمالي:</span>
                    <span class="text-gray-900 font-bold">{{ number_format($extract->total_amount, 2) }} ر.س</span>
                </div>
            </div>

            @if ($extract->description)
                <div class="mt-4">
                    <span class="font-medium text-gray-700">الوصف:</span>
                    <p class="text-gray-900 mt-1">{{ $extract->description }}</p>
                </div>
            @endif
        </div>

        <!-- Extract details table -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">تفاصيل بنود المستخلص</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right border">م</th>
                            <th class="px-4 py-3 text-right border">اسم البند</th>
                            <th class="px-4 py-3 text-center border">الوحدة</th>
                            <th class="px-4 py-3 text-center border">الكمية المستخلصة</th>
                            <th class="px-4 py-3 text-center border">السعر الإفرادي</th>
                            <th class="px-4 py-3 text-center border">القيمة الإجمالية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalValue = 0; @endphp
                        @foreach ($extract->extractItems as $extractItem)
                            @php
                                $projectItem = $project->projectItems[$extractItem->project_item_index] ?? null;
                                if ($projectItem) {
                                    $totalValue += $extractItem->total_value;
                                }
                            @endphp
                            @if ($projectItem)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 border text-center">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 border">{{ $projectItem->name }}</td>
                                    <td class="px-4 py-3 border text-center">{{ $projectItem->unit }}</td>
                                    <td class="px-4 py-3 border text-center font-medium">
                                        {{ number_format($extractItem->quantity, 2) }}
                                    </td>
                                    <td class="px-4 py-3 border text-center">
                                        {{ number_format($extractItem->unit_price, 2) }} ر.س
                                    </td>
                                    <td class="px-4 py-3 border text-center font-medium">
                                        {{ number_format($extractItem->total_value, 2) }} ر.س
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="5" class="px-4 py-3 border text-center">الإجمالي</td>
                            <td class="px-4 py-3 border text-center text-blue-600">{{ number_format($totalValue, 2) }} ر.س</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Additional info section -->
        <div class="mt-6 bg-white rounded-xl shadow-sm border p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- File attachment -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">المرفقات</h3>
                    @if ($extract->file_path)
                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <i class="ri-file-line text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-blue-900">ملف المستخلص</p>
                                <p class="text-sm text-blue-600">{{ basename($extract->file_path) }}</p>
                            </div>
                            <a href="{{ Storage::url($extract->file_path) }}" target="_blank"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-download-line"></i>
                                تحميل
                            </a>
                        </div>
                    @else
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <i class="ri-file-line text-2xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">لا توجد مرفقات</p>
                        </div>
                    @endif
                </div>

                <!-- Creation info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">معلومات الإنشاء</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ الإنشاء:</span>
                            <span class="font-medium">{{ $extract->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">المنشئ:</span>
                            <span class="font-medium">{{ $extract->creator->name ?? 'غير محدد' }}</span>
                        </div>
                        @if ($extract->updated_at != $extract->created_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">آخر تحديث:</span>
                                <span class="font-medium">{{ $extract->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Project context -->
        <div class="mt-6 bg-gray-50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">ملخص المشروع</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="ri-money-dollar-circle-line text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600">ميزانية المشروع</p>
                            <p class="text-lg font-bold text-blue-900">{{ number_format($project->budget, 0) }} ر.س</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <i class="ri-file-list-line text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-green-600">إجمالي المستخلصات</p>
                            <p class="text-lg font-bold text-green-900">{{ $project->projectExtracts->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-100 p-2 rounded-lg">
                            <i class="ri-check-double-line text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-purple-600">المبلغ المدفوع</p>
                            <p class="text-lg font-bold text-purple-900">
                                {{ number_format($project->projectExtracts->where('status', '!=', 'draft')->sum('total_amount'), 0) }} ر.س
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4">
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
        </div>
    </div>

    <style>
        @media print {
            .no-print,
            button,
            a[href*="edit"],
            a[href*="show"] {
                display: none !important;
            }

            .p-6 {
                padding: 1rem !important;
            }

            .rounded-xl,
            .rounded-lg {
                border-radius: 0 !important;
            }

            .shadow-sm {
                box-shadow: none !important;
            }
        }
    </style>
@endsection
