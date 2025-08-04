@extends('layouts.app')

@section('title', 'إدارة المعدات - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة المعدات</h1>
                <p class="text-gray-600">إدارة شاملة لجميع معدات الشركة وحالتها</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="printEquipmentReport()"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
                    <i class="ri-printer-line ml-2"></i>
                    طباعة تقرير المعدات
                </button>
                <a href="{{ route('equipment.create') }}"
                   class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-3 rounded-xl font-medium hover:from-orange-700 hover:to-orange-800 transition-all duration-200 flex items-center">
                    <i class="ri-tools-add-line ml-2"></i>
                    إضافة معدة جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
        <div class="flex items-center">
            <i class="ri-check-circle-line text-green-600 ml-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Equipment Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">متاحة</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $equipment->where('status', 'available')->count() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl">
                    <i class="ri-check-circle-fill text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">قيد الاستخدام</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $equipment->where('status', 'in_use')->count() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl">
                    <i class="ri-play-circle-fill text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium mb-1">في الصيانة</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $equipment->where('status', 'maintenance')->count() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-3 rounded-xl">
                    <i class="ri-settings-fill text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium mb-1">خارج الخدمة</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $equipment->where('status', 'out_of_order')->count() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-3 rounded-xl">
                    <i class="ri-close-circle-fill text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">قائمة المعدات</h2>
        </div>

        @if($equipment->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full table-fixed" style="min-width: 100%;">
                <colgroup>
                    <col style="width: 30%;">
                    <col class="hidden md:table-column" style="width: 15%;">
                    <col class="hidden lg:table-column" style="width: 15%;">
                    <col class="hidden lg:table-column" style="width: 12%;">
                    <col class="hidden md:table-column" style="width: 12%;">
                    <col style="width: 10%;">
                    <col class="hidden lg:table-column" style="width: 10%;">
                    <col style="width: 6%;">
                </colgroup>
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المعدة</th>
                        <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">النوع</th>
                        <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">الرقم التسلسلي</th>
                        <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">الموقع</th>
                        <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">السائق</th>
                        <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">تاريخ الشراء</th>
                        <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($equipment as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($item->images && count($item->images) > 0)
                                        <img src="{{ asset('storage/' . $item->images[0]) }}"
                                             class="w-full h-full object-cover"
                                             alt="صورة {{ $item->name }}">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center">
                                            <i class="ri-tools-line text-white text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="mr-2 min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $item->name }}</div>
                                    <div class="text-xs text-gray-500 truncate md:hidden">{{ $item->type }}</div>
                                    @if($item->model)
                                        <div class="text-xs text-gray-500 truncate hidden lg:block">{{ $item->model }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-3 text-sm text-gray-900 hidden md:table-cell">
                            <div class="truncate">{{ $item->type }}</div>
                        </td>
                        <td class="px-2 py-3 text-sm text-gray-900 hidden lg:table-cell">
                            <div class="truncate">{{ $item->serial_number }}</div>
                        </td>
                        <td class="px-2 py-3 text-sm text-gray-900 hidden lg:table-cell">
                            <div class="truncate">{{ $item->locationDetail ? $item->locationDetail->name : 'غير محدد' }}</div>
                        </td>
                        <td class="px-2 py-3 text-sm text-gray-900 hidden md:table-cell">
                            @if($item->driver)
                                <div class="flex items-center">
                                    <i class="ri-user-line text-blue-600 ml-1 flex-shrink-0 text-xs"></i>
                                    <span class="text-blue-700 font-medium truncate text-xs">{{ $item->driver->name }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">غير محدد</span>
                            @endif
                        </td>
                        <td class="px-2 py-3">
                            @php
                                $statusConfig = [
                                    'available' => ['text' => 'متاحة', 'class' => 'bg-green-100 text-green-800'],
                                    'in_use' => ['text' => 'قيد الاستخدام', 'class' => 'bg-blue-100 text-blue-800'],
                                    'maintenance' => ['text' => 'في الصيانة', 'class' => 'bg-yellow-100 text-yellow-800'],
                                    'out_of_order' => ['text' => 'خارج الخدمة', 'class' => 'bg-red-100 text-red-800']
                                ];
                                $status = $statusConfig[$item->status] ?? ['text' => $item->status, 'class' => 'bg-gray-100 text-gray-800'];
                            @endphp
                            <span class="px-1 inline-flex text-xs leading-4 font-semibold rounded-full {{ $status['class'] }}">
                                {{ $status['text'] }}
                            </span>
                        </td>
                        <td class="px-2 py-3 text-sm text-gray-900 hidden lg:table-cell">
                            <div class="truncate text-xs">{{ $item->purchase_date->format('Y/m/d') }}</div>
                        </td>
                        <td class="px-2 py-3 text-sm font-medium">
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('equipment.show', $item) }}"
                                   class="text-blue-600 hover:text-blue-900"
                                   title="عرض التفاصيل">
                                    <i class="ri-eye-line text-xs"></i>
                                </a>
                                <a href="{{ route('equipment.edit', $item) }}"
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="تعديل">
                                    <i class="ri-edit-line text-xs"></i>
                                </a>
                                <form action="{{ route('equipment.destroy', $item) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المعدة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900"
                                            title="حذف">
                                        <i class="ri-delete-bin-line text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $equipment->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="ri-tools-line text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد معدات</h3>
            <p class="text-gray-500 mb-6">ابدأ بإضافة معدة جديدة لإدارة المعدات</p>
            <a href="{{ route('equipment.create') }}"
               class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-3 rounded-xl font-medium hover:from-orange-700 hover:to-orange-800 transition-all duration-200 inline-flex items-center">
                <i class="ri-tools-add-line ml-2"></i>
                إضافة معدة جديدة
            </a>
        </div>
        @endif
    </div>
</div>

<!-- CSS لضمان عدم التمرير الأفقي -->
<style>
    @media (max-width: 768px) {
        .space-y-6 {
            padding: 0 0.25rem;
        }

        .overflow-x-auto {
            overflow-x: hidden;
        }

        .table-fixed {
            table-layout: fixed;
            width: 100% !important;
        }

        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }

    @media (max-width: 640px) {
        .bg-white.rounded-2xl {
            margin: 0 -0.5rem;
            border-radius: 0;
        }

        .px-2 {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }

        .text-xs {
            font-size: 0.7rem;
            line-height: 1rem;
        }

        /* تصغير أعمدة الجدول على الموبايل */
        .table-fixed colgroup col:first-child {
            width: 40% !important;
        }
        .table-fixed colgroup col:nth-child(6) {
            width: 35% !important;
        }
        .table-fixed colgroup col:last-child {
            width: 25% !important;
        }
    }
</style>

<script>
function printEquipmentReport() {
    window.open('{{ route("equipment.report") }}', '_blank');
}
</script>
@endsection
