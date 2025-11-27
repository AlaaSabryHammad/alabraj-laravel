@extends('layouts.app')

@section('title', 'إدارة القطع التالفة')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <i class="ri-alert-line text-red-600"></i>
                إدارة القطع التالفة
            </h1>
            <p class="text-gray-600 mt-1">تتبع واستلام القطع التالفة والمعطلة</p>
        </div>
        <a href="{{ route('damaged-parts-receipts.create') }}"
            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
            <i class="ri-add-circle-line"></i>
            استلام قطعة تالفة جديدة
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <!-- إجمالي الاستلامات -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">إجمالي الاستلامات</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $receipts->total() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="ri-inbox-line text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- في الانتظار -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">في الانتظار</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">
                        {{ $receipts->where('processing_status', 'received')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="ri-time-line text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- تحت التقييم -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">تحت التقييم</p>
                    <p class="text-3xl font-bold text-cyan-600 mt-2">
                        {{ $receipts->where('processing_status', 'under_evaluation')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-cyan-100 flex items-center justify-center">
                    <i class="ri-eye-line text-2xl text-cyan-600"></i>
                </div>
            </div>
        </div>

        <!-- تم الإصلاح -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">تم الإصلاح</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ $receipts->whereIn('processing_status', ['approved_repair', 'returned_fixed'])->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="ri-tools-line text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- للاستبدال -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">للاستبدال</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">
                        {{ $receipts->where('processing_status', 'approved_replace')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="ri-exchange-line text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- تم التخلص منها -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">تم التخلص منها</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">
                        {{ $receipts->where('processing_status', 'disposed')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-2xl text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- حالة التلف -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">حالة التلف</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" id="damage-condition-filter">
                    <option value="">جميع الحالات</option>
                    <option value="repairable">قابلة للإصلاح</option>
                    <option value="non_repairable">غير قابلة للإصلاح</option>
                    <option value="replacement_needed">تحتاج لاستبدال</option>
                    <option value="for_evaluation">تحتاج لتقييم</option>
                </select>
            </div>

            <!-- حالة المعالجة -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">حالة المعالجة</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" id="processing-status-filter">
                    <option value="">جميع الحالات</option>
                    <option value="received">تم الاستلام</option>
                    <option value="under_evaluation">تحت التقييم</option>
                    <option value="approved_repair">موافقة على الإصلاح</option>
                    <option value="approved_replace">موافقة على الاستبدال</option>
                    <option value="disposed">تم التخلص منها</option>
                    <option value="returned_fixed">تم إرجاعها بعد الإصلاح</option>
                </select>
            </div>

            <!-- المشروع -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المشروع</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" id="project-filter">
                    <option value="">جميع المشاريع</option>
                </select>
            </div>

            <!-- البحث -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                <div class="relative">
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        id="search-input" placeholder="رقم الإيصال أو اسم القطعة...">
                    <i class="ri-search-line absolute right-3 top-2.5 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full" id="damaged-parts-table">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">رقم الإيصال</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">التاريخ</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">القطعة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">المشروع</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الكمية</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">التلف</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">المعالجة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">التكلفة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @forelse($receipts as $receipt)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-bold text-blue-600">{{ $receipt->receipt_number }}</p>
                                    <p class="text-xs text-gray-600">{{ $receipt->receipt_time }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $receipt->receipt_date->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $receipt->sparePart->name }}</p>
                                    @if ($receipt->equipment)
                                        <p class="text-xs text-gray-600">{{ $receipt->equipment->name }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $receipt->project->name }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg font-semibold text-gray-700">
                                    {{ $receipt->quantity_received }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-green-100 text-green-700': '{{ $receipt->damage_condition }}' === 'repairable',
                                        'bg-red-100 text-red-700': '{{ $receipt->damage_condition }}' === 'non_repairable',
                                        'bg-yellow-100 text-yellow-700': '{{ $receipt->damage_condition }}' === 'replacement_needed',
                                        'bg-blue-100 text-blue-700': '{{ $receipt->damage_condition }}' === 'for_evaluation',
                                    }">
                                    {{ $receipt->damage_condition_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-blue-100 text-blue-700': '{{ $receipt->processing_status }}' === 'received',
                                        'bg-cyan-100 text-cyan-700': '{{ $receipt->processing_status }}' === 'under_evaluation',
                                        'bg-yellow-100 text-yellow-700': '{{ $receipt->processing_status }}' === 'approved_repair' || '{{ $receipt->processing_status }}' === 'approved_replace',
                                        'bg-green-100 text-green-700': '{{ $receipt->processing_status }}' === 'returned_fixed',
                                        'bg-red-100 text-red-700': '{{ $receipt->processing_status }}' === 'disposed',
                                    }">
                                    {{ $receipt->processing_status_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    @if ($receipt->estimated_repair_cost)
                                        <p class="text-green-600 font-medium">إصلاح: {{ number_format($receipt->estimated_repair_cost, 2) }} ر.س</p>
                                    @endif
                                    @if ($receipt->replacement_cost)
                                        <p class="text-yellow-600 font-medium">استبدال: {{ number_format($receipt->replacement_cost, 2) }} ر.س</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('damaged-parts-receipts.show', $receipt) }}"
                                        class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors"
                                        title="عرض">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('damaged-parts-receipts.edit', $receipt) }}"
                                        class="px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition-colors"
                                        title="تعديل">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <button type="button" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors"
                                        onclick="deleteReceipt({{ $receipt->id }})" title="حذف">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="ri-inbox-line text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-600 font-medium mb-4">لا توجد استلامات للقطع التالفة بعد</p>
                                    <a href="{{ route('damaged-parts-receipts.create') }}"
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors inline-flex items-center gap-2">
                                        <i class="ri-add-circle-line"></i>
                                        إضافة أول استلام
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($receipts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $receipts->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <i class="ri-alert-line text-2xl text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">تأكيد الحذف</h3>
        </div>

        <p class="text-gray-700 mb-6">
            هل أنت متأكد من حذف هذا الإيصال؟ لا يمكن التراجع عن هذا الإجراء.
        </p>

        <div class="flex items-center gap-3">
            <button type="button" onclick="closeDeleteModal()"
                class="flex-1 px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                إلغاء
            </button>
            <form id="deleteForm" method="POST" style="display: contents">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    حذف
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        function deleteReceipt(id) {
            document.getElementById('deleteForm').action = `/damaged-parts-receipts/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // فلاتر البحث
        document.addEventListener('DOMContentLoaded', function() {
            const damageConditionFilter = document.getElementById('damage-condition-filter');
            const processingStatusFilter = document.getElementById('processing-status-filter');
            const projectFilter = document.getElementById('project-filter');
            const searchInput = document.getElementById('search-input');

            function applyFilters() {
                const tbody = document.getElementById('tbody');
                const rows = tbody.getElementsByTagName('tr');

                let visibleCount = 0;

                Array.from(rows).forEach(row => {
                    if (row.cells.length === 1) return;

                    let show = true;

                    // فلتر حالة التلف
                    if (damageConditionFilter.value && !row.innerHTML.includes(damageConditionFilter.value)) {
                        show = false;
                    }

                    // فلتر حالة المعالجة
                    if (processingStatusFilter.value && !row.innerHTML.includes(processingStatusFilter.value)) {
                        show = false;
                    }

                    // فلتر البحث
                    if (searchInput.value && !row.innerHTML.toLowerCase().includes(searchInput.value.toLowerCase())) {
                        show = false;
                    }

                    row.style.display = show ? '' : 'none';
                    if (show) visibleCount++;
                });

                // عرض رسالة عدم وجود نتائج
                if (visibleCount === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = `
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="ri-search-x-line text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-600 font-medium">لا توجد نتائج بحث</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(emptyRow);
                } else {
                    // إزالة رسالة عدم وجود نتائج إن وجدت
                    const emptyRows = tbody.querySelectorAll('tr:has(td[colspan="9"])');
                    emptyRows.forEach(row => row.remove());
                }
            }

            damageConditionFilter.addEventListener('change', applyFilters);
            processingStatusFilter.addEventListener('change', applyFilters);
            projectFilter.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);
        });
    </script>
@endpush
