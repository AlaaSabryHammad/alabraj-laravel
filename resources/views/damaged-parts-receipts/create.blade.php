@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            إنشاء استلام قطعة تالفة جديدة
                        </h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('damaged-parts-receipts.store') }}"
                            enctype="multipart/form-data">
                            @csrf

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
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="receipt_date" class="form-label">تاريخ الاستلام *</label>
                                        <input type="date"
                                            class="form-control @error('receipt_date') is-invalid @enderror"
                                            id="receipt_date" name="receipt_date"
                                            value="{{ old('receipt_date', now()->format('Y-m-d')) }}" required>
                                        @error('receipt_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="receipt_time" class="form-label">وقت الاستلام *</label>
                                        <input type="time"
                                            class="form-control @error('receipt_time') is-invalid @enderror"
                                            id="receipt_time" name="receipt_time"
                                            value="{{ old('receipt_time', now()->format('H:i')) }}" required>
                                        @error('receipt_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="received_by" class="form-label">موظف الاستلام *</label>
                                        <select class="form-control @error('received_by') is-invalid @enderror"
                                            id="received_by" name="received_by" required>
                                            <option value="">اختر الموظف</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ old('received_by') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }} - {{ $employee->position }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('received_by')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات المشروع والمعدة -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-project-diagram me-2"></i>
                                        معلومات المشروع والمعدة
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="project_id" class="form-label">المشروع *</label>
                                        <select class="form-control @error('project_id') is-invalid @enderror"
                                            id="project_id" name="project_id" required>
                                            <option value="">اختر المشروع</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}"
                                                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                    {{ $project->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="equipment_id" class="form-label">المعدة (اختياري)</label>
                                        <select class="form-control @error('equipment_id') is-invalid @enderror"
                                            id="equipment_id" name="equipment_id">
                                            <option value="">لا توجد معدة محددة</option>
                                            @foreach ($equipment as $equip)
                                                <option value="{{ $equip->id }}"
                                                    {{ old('equipment_id') == $equip->id ? 'selected' : '' }}>
                                                    {{ $equip->name }} - {{ $equip->serial_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('equipment_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sent_by" class="form-label">موظف الإرسال من المشروع</label>
                                        <select class="form-control @error('sent_by') is-invalid @enderror" id="sent_by"
                                            name="sent_by">
                                            <option value="">غير محدد</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ old('sent_by') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }} - {{ $employee->position }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sent_by')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات قطعة الغيار -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-cogs me-2"></i>
                                        معلومات قطعة الغيار
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="spare_part_id" class="form-label">قطعة الغيار *</label>
                                        <select class="form-control @error('spare_part_id') is-invalid @enderror"
                                            id="spare_part_id" name="spare_part_id" required>
                                            <option value="">اختر قطعة الغيار</option>
                                            @foreach ($spareParts as $sparePart)
                                                <option value="{{ $sparePart->id }}"
                                                    {{ old('spare_part_id') == $sparePart->id ? 'selected' : '' }}>
                                                    {{ $sparePart->name }} - {{ $sparePart->part_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('spare_part_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="quantity_received" class="form-label">الكمية المستلمة *</label>
                                        <input type="number"
                                            class="form-control @error('quantity_received') is-invalid @enderror"
                                            id="quantity_received" name="quantity_received"
                                            value="{{ old('quantity_received', 1) }}" min="1" required>
                                        @error('quantity_received')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- حالة التلف -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        تقييم حالة التلف
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="damage_condition" class="form-label">حالة التلف *</label>
                                        <select class="form-control @error('damage_condition') is-invalid @enderror"
                                            id="damage_condition" name="damage_condition" required>
                                            <option value="">اختر حالة التلف</option>
                                            <option value="repairable"
                                                {{ old('damage_condition') == 'repairable' ? 'selected' : '' }}>
                                                قابلة للإصلاح
                                            </option>
                                            <option value="non_repairable"
                                                {{ old('damage_condition') == 'non_repairable' ? 'selected' : '' }}>
                                                غير قابلة للإصلاح
                                            </option>
                                            <option value="replacement_needed"
                                                {{ old('damage_condition') == 'replacement_needed' ? 'selected' : '' }}>
                                                تحتاج لاستبدال
                                            </option>
                                            <option value="for_evaluation"
                                                {{ old('damage_condition') == 'for_evaluation' ? 'selected' : '' }}>
                                                تحتاج لتقييم
                                            </option>
                                        </select>
                                        @error('damage_condition')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="damage_description" class="form-label">وصف التلف</label>
                                        <textarea class="form-control @error('damage_description') is-invalid @enderror" id="damage_description"
                                            name="damage_description" rows="3" placeholder="اكتب وصف مفصل عن نوع التلف...">{{ old('damage_description') }}</textarea>
                                        @error('damage_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="damage_cause" class="form-label">سبب التلف</label>
                                        <textarea class="form-control @error('damage_cause') is-invalid @enderror" id="damage_cause" name="damage_cause"
                                            rows="3" placeholder="اكتب السبب المحتمل للتلف...">{{ old('damage_cause') }}</textarea>
                                        @error('damage_cause')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="technician_notes" class="form-label">ملاحظات الفني</label>
                                        <textarea class="form-control @error('technician_notes') is-invalid @enderror" id="technician_notes"
                                            name="technician_notes" rows="3" placeholder="ملاحظات إضافية من الفني المختص...">{{ old('technician_notes') }}</textarea>
                                        @error('technician_notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات التخزين -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-warehouse me-2"></i>
                                        معلومات التخزين
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="warehouse_id" class="form-label">المخزن *</label>
                                        <select class="form-control @error('warehouse_id') is-invalid @enderror"
                                            id="warehouse_id" name="warehouse_id" required>
                                            <option value="">اختر المخزن</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }} ({{ $warehouse->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('warehouse_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="storage_location" class="form-label">موقع التخزين</label>
                                        <input type="text"
                                            class="form-control @error('storage_location') is-invalid @enderror"
                                            id="storage_location" name="storage_location"
                                            value="{{ old('storage_location') }}" placeholder="مثل: A-01-25 أو رف رقم 3">
                                        @error('storage_location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- التكاليف المقدرة -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-dollar-sign me-2"></i>
                                        التكاليف المقدرة
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="estimated_repair_cost" class="form-label">التكلفة المقدرة للإصلاح
                                            (ر.س)</label>
                                        <input type="number"
                                            class="form-control @error('estimated_repair_cost') is-invalid @enderror"
                                            id="estimated_repair_cost" name="estimated_repair_cost"
                                            value="{{ old('estimated_repair_cost') }}" min="0" step="0.01"
                                            placeholder="0.00">
                                        @error('estimated_repair_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="replacement_cost" class="form-label">تكلفة الاستبدال (ر.س)</label>
                                        <input type="number"
                                            class="form-control @error('replacement_cost') is-invalid @enderror"
                                            id="replacement_cost" name="replacement_cost"
                                            value="{{ old('replacement_cost') }}" min="0" step="0.01"
                                            placeholder="0.00">
                                        @error('replacement_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- المرفقات -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-paperclip me-2"></i>
                                        المرفقات
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="photos" class="form-label">صور القطعة التالفة</label>
                                        <input type="file"
                                            class="form-control @error('photos.*') is-invalid @enderror" id="photos"
                                            name="photos[]" multiple accept="image/*">
                                        <small class="form-text text-muted">يمكنك اختيار عدة صور (JPG, PNG, GIF - حد أقصى
                                            5MB لكل صورة)</small>
                                        @error('photos.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="documents" class="form-label">مستندات مرفقة</label>
                                        <input type="file"
                                            class="form-control @error('documents.*') is-invalid @enderror"
                                            id="documents" name="documents[]" multiple accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">PDF, DOC, DOCX - حد أقصى 10MB لكل ملف</small>
                                        @error('documents.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- أزرار الإجراءات -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('damaged-parts-receipts.index') }}"
                                            class="btn btn-secondary me-2">
                                            <i class="fas fa-times me-1"></i>
                                            إلغاء
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            حفظ الاستلام
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث حقول التكلفة حسب حالة التلف
            const damageConditionSelect = document.getElementById('damage_condition');
            const repairCostField = document.getElementById('estimated_repair_cost');
            const replacementCostField = document.getElementById('replacement_cost');

            damageConditionSelect.addEventListener('change', function() {
                const condition = this.value;

                if (condition === 'non_repairable') {
                    repairCostField.disabled = true;
                    repairCostField.value = '';
                    replacementCostField.disabled = false;
                    replacementCostField.focus();
                } else if (condition === 'replacement_needed') {
                    repairCostField.disabled = true;
                    repairCostField.value = '';
                    replacementCostField.disabled = false;
                    replacementCostField.focus();
                } else if (condition === 'repairable') {
                    repairCostField.disabled = false;
                    replacementCostField.disabled = true;
                    replacementCostField.value = '';
                    repairCostField.focus();
                } else {
                    repairCostField.disabled = false;
                    replacementCostField.disabled = false;
                }
            });

            // معاينة الصور المحددة
            document.getElementById('photos').addEventListener('change', function() {
                const files = this.files;
                let preview = '';

                for (let i = 0; i < files.length; i++) {
                    if (files[i].type.startsWith('image/')) {
                        preview += `<span class="badge bg-success me-1">${files[i].name}</span>`;
                    }
                }

                if (preview) {
                    let previewDiv = document.getElementById('photos-preview');
                    if (!previewDiv) {
                        previewDiv = document.createElement('div');
                        previewDiv.id = 'photos-preview';
                        previewDiv.className = 'mt-2';
                        this.parentNode.appendChild(previewDiv);
                    }
                    previewDiv.innerHTML = preview;
                }
            });
        });
    </script>
@endpush
