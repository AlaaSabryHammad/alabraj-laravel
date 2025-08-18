@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-eye me-2"></i>
                            تفاصيل استلام القطعة التالفة - {{ $damagedPartsReceipt->receipt_number }}
                        </h4>
                        <div>
                            <a href="{{ route('damaged-parts-receipts.edit', $damagedPartsReceipt) }}"
                                class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i>
                                تعديل
                            </a>
                            <a href="{{ route('damaged-parts-receipts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i>
                                العودة للقائمة
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- معلومات الاستلام الأساسية -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    معلومات الاستلام الأساسية
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">رقم الإيصال</label>
                                    <p class="h6 text-primary">{{ $damagedPartsReceipt->receipt_number }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">تاريخ الاستلام</label>
                                    <p class="h6">{{ $damagedPartsReceipt->receipt_date->format('Y-m-d') }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">وقت الاستلام</label>
                                    <p class="h6">{{ $damagedPartsReceipt->receipt_time }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">موظف الاستلام</label>
                                    <p class="h6">{{ $damagedPartsReceipt->receivedByEmployee->name }}</p>
                                    <small
                                        class="text-muted">{{ $damagedPartsReceipt->receivedByEmployee->position }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات المشروع والمعدة -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-project-diagram me-2"></i>
                                    معلومات المشروع والمعدة
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">المشروع</label>
                                    <p class="h6">{{ $damagedPartsReceipt->project->name }}</p>
                                    @if ($damagedPartsReceipt->project->description)
                                        <small class="text-muted">{{ $damagedPartsReceipt->project->description }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">المعدة</label>
                                    @if ($damagedPartsReceipt->equipment)
                                        <p class="h6">{{ $damagedPartsReceipt->equipment->name }}</p>
                                        <small
                                            class="text-muted">{{ $damagedPartsReceipt->equipment->serial_number }}</small>
                                    @else
                                        <p class="text-muted">غير محددة</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">موظف الإرسال</label>
                                    @if ($damagedPartsReceipt->sentByEmployee)
                                        <p class="h6">{{ $damagedPartsReceipt->sentByEmployee->name }}</p>
                                        <small
                                            class="text-muted">{{ $damagedPartsReceipt->sentByEmployee->position }}</small>
                                    @else
                                        <p class="text-muted">غير محدد</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- معلومات قطعة الغيار -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-cogs me-2"></i>
                                    معلومات قطعة الغيار
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">قطعة الغيار</label>
                                    <p class="h6">{{ $damagedPartsReceipt->sparePart->name }}</p>
                                    <small class="text-muted">رقم القطعة:
                                        {{ $damagedPartsReceipt->sparePart->part_number }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">الكمية المستلمة</label>
                                    <p class="h6">{{ $damagedPartsReceipt->quantity_received }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- حالة التلف والتقييم -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    تقييم حالة التلف
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">حالة التلف</label>
                                    <p>
                                        <span
                                            class="badge bg-{{ getDamageConditionColor($damagedPartsReceipt->damage_condition) }} fs-6">
                                            {{ $damagedPartsReceipt->damage_condition_text }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">حالة المعالجة</label>
                                    <p>
                                        <span
                                            class="badge bg-{{ getProcessingStatusColor($damagedPartsReceipt->processing_status) }} fs-6">
                                            {{ $damagedPartsReceipt->processing_status_text }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- نموذج تحديث حالة المعالجة -->
                                <div class="info-box">
                                    <label class="fw-bold text-muted">تحديث الحالة</label>
                                    <form method="POST"
                                        action="{{ route('damaged-parts-receipts.update-status', $damagedPartsReceipt) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group">
                                            <select name="processing_status" class="form-control form-control-sm">
                                                <option value="received"
                                                    {{ $damagedPartsReceipt->processing_status == 'received' ? 'selected' : '' }}>
                                                    تم الاستلام</option>
                                                <option value="under_evaluation"
                                                    {{ $damagedPartsReceipt->processing_status == 'under_evaluation' ? 'selected' : '' }}>
                                                    تحت التقييم</option>
                                                <option value="approved_repair"
                                                    {{ $damagedPartsReceipt->processing_status == 'approved_repair' ? 'selected' : '' }}>
                                                    موافقة على الإصلاح</option>
                                                <option value="approved_replace"
                                                    {{ $damagedPartsReceipt->processing_status == 'approved_replace' ? 'selected' : '' }}>
                                                    موافقة على الاستبدال</option>
                                                <option value="disposed"
                                                    {{ $damagedPartsReceipt->processing_status == 'disposed' ? 'selected' : '' }}>
                                                    تم التخلص منها</option>
                                                <option value="returned_fixed"
                                                    {{ $damagedPartsReceipt->processing_status == 'returned_fixed' ? 'selected' : '' }}>
                                                    تم إرجاعها بعد الإصلاح</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- الأوصاف والملاحظات -->
                        @if (
                            $damagedPartsReceipt->damage_description ||
                                $damagedPartsReceipt->damage_cause ||
                                $damagedPartsReceipt->technician_notes)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        الأوصاف والملاحظات
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                @if ($damagedPartsReceipt->damage_description)
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <label class="fw-bold text-muted">وصف التلف</label>
                                            <p class="border p-3 bg-light rounded">
                                                {{ $damagedPartsReceipt->damage_description }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($damagedPartsReceipt->damage_cause)
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <label class="fw-bold text-muted">سبب التلف</label>
                                            <p class="border p-3 bg-light rounded">
                                                {{ $damagedPartsReceipt->damage_cause }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($damagedPartsReceipt->technician_notes)
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <label class="fw-bold text-muted">ملاحظات الفني</label>
                                            <p class="border p-3 bg-light rounded">
                                                {{ $damagedPartsReceipt->technician_notes }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- معلومات التخزين -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-warehouse me-2"></i>
                                    معلومات التخزين
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">المخزن</label>
                                    <p class="h6">{{ $damagedPartsReceipt->warehouse->name }}</p>
                                    <small class="text-muted">كود المخزن:
                                        {{ $damagedPartsReceipt->warehouse->code }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">موقع التخزين</label>
                                    @if ($damagedPartsReceipt->storage_location)
                                        <p class="h6">{{ $damagedPartsReceipt->storage_location }}</p>
                                    @else
                                        <p class="text-muted">غير محدد</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- التكاليف -->
                        @if ($damagedPartsReceipt->estimated_repair_cost || $damagedPartsReceipt->replacement_cost)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-dollar-sign me-2"></i>
                                        التكاليف المقدرة
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                @if ($damagedPartsReceipt->estimated_repair_cost)
                                    <div class="col-md-6">
                                        <div class="card bg-success text-white">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="fas fa-tools me-2"></i>
                                                    التكلفة المقدرة للإصلاح
                                                </h6>
                                                <h4 class="mb-0">
                                                    {{ number_format($damagedPartsReceipt->estimated_repair_cost, 2) }} ر.س
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($damagedPartsReceipt->replacement_cost)
                                    <div class="col-md-6">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="fas fa-exchange-alt me-2"></i>
                                                    تكلفة الاستبدال
                                                </h6>
                                                <h4 class="mb-0">
                                                    {{ number_format($damagedPartsReceipt->replacement_cost, 2) }} ر.س</h4>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- التواريخ المهمة -->
                        @if (
                            $damagedPartsReceipt->evaluation_date ||
                                $damagedPartsReceipt->decision_date ||
                                $damagedPartsReceipt->completion_date)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-calendar me-2"></i>
                                        التواريخ المهمة
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                @if ($damagedPartsReceipt->evaluation_date)
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <label class="fw-bold text-muted">تاريخ التقييم</label>
                                            <p class="h6">
                                                {{ $damagedPartsReceipt->evaluation_date->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($damagedPartsReceipt->decision_date)
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <label class="fw-bold text-muted">تاريخ اتخاذ القرار</label>
                                            <p class="h6">
                                                {{ $damagedPartsReceipt->decision_date->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($damagedPartsReceipt->completion_date)
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <label class="fw-bold text-muted">تاريخ إكمال المعالجة</label>
                                            <p class="h6">
                                                {{ $damagedPartsReceipt->completion_date->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- المرفقات -->
                        @if ($damagedPartsReceipt->photos || $damagedPartsReceipt->documents)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-paperclip me-2"></i>
                                        المرفقات
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                @if ($damagedPartsReceipt->photos)
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <label class="fw-bold text-muted">الصور</label>
                                            <div class="row">
                                                @foreach ($damagedPartsReceipt->photos as $photo)
                                                    <div class="col-md-4 mb-2">
                                                        <img src="{{ Storage::url($photo) }}"
                                                            class="img-fluid rounded shadow-sm cursor-pointer"
                                                            alt="صورة القطعة التالفة"
                                                            onclick="showImageModal('{{ Storage::url($photo) }}')">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($damagedPartsReceipt->documents)
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <label class="fw-bold text-muted">المستندات</label>
                                            <div class="list-group">
                                                @foreach ($damagedPartsReceipt->documents as $document)
                                                    <a href="{{ Storage::url($document['path']) }}"
                                                        class="list-group-item list-group-item-action" target="_blank">
                                                        <i class="fas fa-file-alt me-2"></i>
                                                        {{ $document['original_name'] }}
                                                        <small class="text-muted float-end">
                                                            {{ formatBytes($document['size']) }}
                                                        </small>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- معلومات النظام -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-info me-2"></i>
                                    معلومات النظام
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">تاريخ الإنشاء</label>
                                    <p class="h6">{{ $damagedPartsReceipt->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <label class="fw-bold text-muted">آخر تحديث</label>
                                    <p class="h6">{{ $damagedPartsReceipt->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
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

        function formatBytes($size)
        {
            $units = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($size >= 1024 && $i < count($units) - 1) {
                $size /= 1024;
                $i++;
            }
            return round($size, 2) . ' ' . $units[$i];
        }
    @endphp

    <!-- Modal لعرض الصور -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">صورة القطعة التالفة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="صورة القطعة">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .info-box {
            margin-bottom: 20px;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .cursor-pointer:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease-in-out;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function showImageModal(imageSrc) {
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            document.getElementById('modalImage').src = imageSrc;
            modal.show();
        }
    </script>
@endpush
