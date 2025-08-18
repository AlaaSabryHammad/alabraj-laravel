@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-tools me-2"></i>
                            إدارة القطع التالفة
                        </h4>
                        <a href="{{ route('damaged-parts-receipts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            استلام قطعة تالفة جديدة
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- فلاتر البحث -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>حالة التلف</label>
                                    <select class="form-control" id="damage-condition-filter">
                                        <option value="">جميع الحالات</option>
                                        <option value="repairable">قابلة للإصلاح</option>
                                        <option value="non_repairable">غير قابلة للإصلاح</option>
                                        <option value="replacement_needed">تحتاج لاستبدال</option>
                                        <option value="for_evaluation">تحتاج لتقييم</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>حالة المعالجة</label>
                                    <select class="form-control" id="processing-status-filter">
                                        <option value="">جميع الحالات</option>
                                        <option value="received">تم الاستلام</option>
                                        <option value="under_evaluation">تحت التقييم</option>
                                        <option value="approved_repair">موافقة على الإصلاح</option>
                                        <option value="approved_replace">موافقة على الاستبدال</option>
                                        <option value="disposed">تم التخلص منها</option>
                                        <option value="returned_fixed">تم إرجاعها بعد الإصلاح</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>المشروع</label>
                                    <select class="form-control" id="project-filter">
                                        <option value="">جميع المشاريع</option>
                                        <!-- سيتم ملؤها بالمشاريع الموجودة -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>البحث</label>
                                    <input type="text" class="form-control" id="search-input"
                                        placeholder="رقم الإيصال أو اسم القطعة...">
                                </div>
                            </div>
                        </div>

                        <!-- إحصائيات سريعة -->
                        <div class="row mb-4">
                            <div class="col-md-2">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $receipts->total() }}</h5>
                                        <small>إجمالي الاستلامات</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $receipts->where('processing_status', 'received')->count() }}</h5>
                                        <small>في الانتظار</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $receipts->where('processing_status', 'under_evaluation')->count() }}</h5>
                                        <small>تحت التقييم</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $receipts->whereIn('processing_status', ['approved_repair', 'returned_fixed'])->count() }}
                                        </h5>
                                        <small>تم الإصلاح</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $receipts->where('processing_status', 'approved_replace')->count() }}</h5>
                                        <small>للاستبدال</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $receipts->where('processing_status', 'disposed')->count() }}</h5>
                                        <small>تم التخلص منها</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- جدول القطع التالفة -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="damaged-parts-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>رقم الإيصال</th>
                                        <th>تاريخ الاستلام</th>
                                        <th>القطعة</th>
                                        <th>المشروع</th>
                                        <th>الكمية</th>
                                        <th>حالة التلف</th>
                                        <th>حالة المعالجة</th>
                                        <th>المخزن</th>
                                        <th>التكلفة المقدرة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($receipts as $receipt)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-primary">{{ $receipt->receipt_number }}</span>
                                                <br>
                                                <small class="text-muted">{{ $receipt->receipt_time }}</small>
                                            </td>
                                            <td>{{ $receipt->receipt_date->format('Y-m-d') }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $receipt->sparePart->name }}</strong>
                                                    @if ($receipt->equipment)
                                                        <br><small
                                                            class="text-muted">{{ $receipt->equipment->name }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $receipt->project->name }}</td>
                                            <td>{{ $receipt->quantity_received }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ getDamageConditionColor($receipt->damage_condition) }}">
                                                    {{ $receipt->damage_condition_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ getProcessingStatusColor($receipt->processing_status) }}">
                                                    {{ $receipt->processing_status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $receipt->warehouse->name }}
                                                @if ($receipt->storage_location)
                                                    <br><small class="text-muted">{{ $receipt->storage_location }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($receipt->estimated_repair_cost)
                                                    <div class="text-success">
                                                        إصلاح: {{ number_format($receipt->estimated_repair_cost, 2) }} ر.س
                                                    </div>
                                                @endif
                                                @if ($receipt->replacement_cost)
                                                    <div class="text-warning">
                                                        استبدال: {{ number_format($receipt->replacement_cost, 2) }} ر.س
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('damaged-parts-receipts.show', $receipt) }}"
                                                        class="btn btn-sm btn-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('damaged-parts-receipts.edit', $receipt) }}"
                                                        class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="deleteReceipt({{ $receipt->id }})" title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">لا توجد استلامات للقطع التالفة بعد</p>
                                                <a href="{{ route('damaged-parts-receipts.create') }}"
                                                    class="btn btn-primary">
                                                    إضافة أول استلام
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- التصفح -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $receipts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        function getDamageConditionColor($condition)
        {
            return match ($condition) {
                'repairable' => 'success',
                'non_repairable' => 'danger',
                'replacement_needed' => 'warning',
                'for_evaluation' => 'info',
                default => 'secondary',
            };
        }

        function getProcessingStatusColor($status)
        {
            return match ($status) {
                'received' => 'primary',
                'under_evaluation' => 'info',
                'approved_repair' => 'warning',
                'approved_replace' => 'secondary',
                'disposed' => 'danger',
                'returned_fixed' => 'success',
                default => 'secondary',
            };
        }
    @endphp

    <!-- Modal لحذف الإيصال -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد من حذف هذا الإيصال؟ لا يمكن التراجع عن هذا الإجراء.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST" style="display: inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteReceipt(id) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            document.getElementById('deleteForm').action = `/damaged-parts-receipts/${id}`;
            modal.show();
        }

        // فلاتر البحث
        document.addEventListener('DOMContentLoaded', function() {
            const damageConditionFilter = document.getElementById('damage-condition-filter');
            const processingStatusFilter = document.getElementById('processing-status-filter');
            const projectFilter = document.getElementById('project-filter');
            const searchInput = document.getElementById('search-input');

            // تطبيق الفلاتر
            function applyFilters() {
                const table = document.getElementById('damaged-parts-table');
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

                Array.from(rows).forEach(row => {
                    if (row.cells.length === 1) return; // تجاهل صف "لا توجد بيانات"

                    let show = true;

                    // فلتر حالة التلف
                    if (damageConditionFilter.value && !row.innerHTML.includes(damageConditionFilter
                        .value)) {
                        show = false;
                    }

                    // فلتر حالة المعالجة  
                    if (processingStatusFilter.value && !row.innerHTML.includes(processingStatusFilter
                            .value)) {
                        show = false;
                    }

                    // فلتر البحث
                    if (searchInput.value && !row.innerHTML.toLowerCase().includes(searchInput.value
                            .toLowerCase())) {
                        show = false;
                    }

                    row.style.display = show ? '' : 'none';
                });
            }

            damageConditionFilter.addEventListener('change', applyFilters);
            processingStatusFilter.addEventListener('change', applyFilters);
            projectFilter.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);
        });
    </script>
@endpush
