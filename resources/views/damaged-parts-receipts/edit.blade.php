@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>
                            تعديل استلام القطعة التالفة - {{ $damagedPartsReceipt->receipt_number }}
                        </h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('damaged-parts-receipts.update', $damagedPartsReceipt) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

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
                                            value="{{ old('receipt_date', $damagedPartsReceipt->receipt_date->format('Y-m-d')) }}"
                                            required>
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
                                            value="{{ old('receipt_time', $damagedPartsReceipt->receipt_time) }}" required>
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
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ old('received_by', $damagedPartsReceipt->received_by) == $employee->id ? 'selected' : '' }}>
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
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}"
                                                    {{ old('project_id', $damagedPartsReceipt->project_id) == $project->id ? 'selected' : '' }}>
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
                                                    {{ old('equipment_id', $damagedPartsReceipt->equipment_id) == $equip->id ? 'selected' : '' }}>
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
                                            @foreach ($spareParts as $sparePart)
                                                <option value="{{ $sparePart->id }}"
                                                    {{ old('spare_part_id', $damagedPartsReceipt->spare_part_id) == $sparePart->id ? 'selected' : '' }}>
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
                                            value="{{ old('quantity_received', $damagedPartsReceipt->quantity_received) }}"
                                            min="1" required>
                                        @error('quantity_received')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- حالة التلف والمعالجة -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        حالة التلف والمعالجة
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="damage_condition" class="form-label">حالة التلف *</label>
                                        <select class="form-control @error('damage_condition') is-invalid @enderror"
                                            id="damage_condition" name="damage_condition" required>
                                            <option value="repairable"
                                                {{ old('damage_condition', $damagedPartsReceipt->damage_condition) == 'repairable' ? 'selected' : '' }}>
                                                قابلة للإصلاح
                                            </option>
                                            <option value="non_repairable"
                                                {{ old('damage_condition', $damagedPartsReceipt->damage_condition) == 'non_repairable' ? 'selected' : '' }}>
                                                غير قابلة للإصلاح
                                            </option>
                                            <option value="replacement_needed"
                                                {{ old('damage_condition', $damagedPartsReceipt->damage_condition) == 'replacement_needed' ? 'selected' : '' }}>
                                                تحتاج لاستبدال
                                            </option>
                                            <option value="for_evaluation"
                                                {{ old('damage_condition', $damagedPartsReceipt->damage_condition) == 'for_evaluation' ? 'selected' : '' }}>
                                                تحتاج لتقييم
                                            </option>
                                        </select>
                                        @error('damage_condition')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="processing_status" class="form-label">حالة المعالجة *</label>
                                        <select class="form-control @error('processing_status') is-invalid @enderror"
                                            id="processing_status" name="processing_status" required>
                                            <option value="received"
                                                {{ old('processing_status', $damagedPartsReceipt->processing_status) == 'received' ? 'selected' : '' }}>
                                                تم الاستلام
                                            </option>
                                            <option value="under_evaluation"
                                                {{ old('processing_status', $damagedPartsReceipt->processing_status) == 'under_evaluation' ? 'selected' : '' }}>
                                                تحت التقييم
                                            </option>
                                            <option value="approved_repair"
                                                {{ old('processing_status', $damagedPartsReceipt->processing_status) == 'approved_repair' ? 'selected' : '' }}>
                                                موافقة على الإصلاح
                                            </option>
                                            <option value="approved_replace"
                                                {{ old('processing_status', $damagedPartsReceipt->processing_status) == 'approved_replace' ? 'selected' : '' }}>
                                                موافقة على الاستبدال
                                            </option>
                                            <option value="disposed"
                                                {{ old('processing_status', $damagedPartsReceipt->processing_status) == 'disposed' ? 'selected' : '' }}>
                                                تم التخلص منها
                                            </option>
                                            <option value="returned_fixed"
                                                {{ old('processing_status', $damagedPartsReceipt->processing_status) == 'returned_fixed' ? 'selected' : '' }}>
                                                تم إرجاعها بعد الإصلاح
                                            </option>
                                        </select>
                                        @error('processing_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- الأوصاف والملاحظات -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        الأوصاف والملاحظات
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="damage_description" class="form-label">وصف التلف</label>
                                        <textarea class="form-control @error('damage_description') is-invalid @enderror" id="damage_description"
                                            name="damage_description" rows="4">{{ old('damage_description', $damagedPartsReceipt->damage_description) }}</textarea>
                                        @error('damage_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="damage_cause" class="form-label">سبب التلف</label>
                                        <textarea class="form-control @error('damage_cause') is-invalid @enderror" id="damage_cause" name="damage_cause"
                                            rows="4">{{ old('damage_cause', $damagedPartsReceipt->damage_cause) }}</textarea>
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
                                            name="technician_notes" rows="3">{{ old('technician_notes', $damagedPartsReceipt->technician_notes) }}</textarea>
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
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ old('warehouse_id', $damagedPartsReceipt->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
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
                                            value="{{ old('storage_location', $damagedPartsReceipt->storage_location) }}">
                                        @error('storage_location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- التكاليف -->
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
                                            value="{{ old('estimated_repair_cost', $damagedPartsReceipt->estimated_repair_cost) }}"
                                            min="0" step="0.01">
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
                                            value="{{ old('replacement_cost', $damagedPartsReceipt->replacement_cost) }}"
                                            min="0" step="0.01">
                                        @error('replacement_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- المرفقات الجديدة -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-paperclip me-2"></i>
                                        إضافة مرفقات جديدة
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="photos" class="form-label">صور إضافية</label>
                                        <input type="file"
                                            class="form-control @error('photos.*') is-invalid @enderror" id="photos"
                                            name="photos[]" multiple accept="image/*">
                                        <small class="form-text text-muted">ستتم إضافة هذه الصور للمرفقات الموجودة</small>
                                        @error('photos.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="documents" class="form-label">مستندات إضافية</label>
                                        <input type="file"
                                            class="form-control @error('documents.*') is-invalid @enderror"
                                            id="documents" name="documents[]" multiple accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">ستتم إضافة هذه المستندات للمرفقات
                                            الموجودة</small>
                                        @error('documents.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- المرفقات الحالية -->
                            @if ($damagedPartsReceipt->photos || $damagedPartsReceipt->documents)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i class="fas fa-folder me-2"></i>
                                            المرفقات الحالية
                                        </h5>
                                    </div>
                                </div>

                                <div class="row">
                                    @if ($damagedPartsReceipt->photos)
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">الصور الحالية</label>
                                                <div class="d-flex flex-wrap">
                                                    @foreach ($damagedPartsReceipt->photos as $index => $photo)
                                                        <div class="position-relative me-2 mb-2">
                                                            <img src="{{ Storage::url($photo) }}" class="img-thumbnail"
                                                                width="100" alt="صورة {{ $index + 1 }}">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                                style="transform: translate(50%, -50%); border-radius: 50%;"
                                                                onclick="removePhoto({{ $index }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($damagedPartsReceipt->documents)
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">المستندات الحالية</label>
                                                <div class="list-group">
                                                    @foreach ($damagedPartsReceipt->documents as $index => $document)
                                                        <div
                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span>
                                                                <i class="fas fa-file-alt me-2"></i>
                                                                {{ $document['original_name'] }}
                                                            </span>
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="removeDocument({{ $index }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- أزرار الإجراءات -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('damaged-parts-receipts.show', $damagedPartsReceipt) }}"
                                            class="btn btn-secondary me-2">
                                            <i class="fas fa-times me-1"></i>
                                            إلغاء
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            حفظ التعديلات
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
        function removePhoto(index) {
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                // هنا يمكن إضافة كود لحذف الصورة من السيرفر
                // أو إضافة input مخفي لتمرير معلومات الحذف
                alert('سيتم حذف الصورة عند حفظ التعديلات');
            }
        }

        function removeDocument(index) {
            if (confirm('هل أنت متأكد من حذف هذا المستند؟')) {
                // هنا يمكن إضافة كود لحذف المستند من السيرفر
                alert('سيتم حذف المستند عند حفظ التعديلات');
            }
        }

        // تحديث حقول التكلفة حسب حالة التلف (نفس الكود من صفحة الإنشاء)
        document.addEventListener('DOMContentLoaded', function() {
            const damageConditionSelect = document.getElementById('damage_condition');
            const repairCostField = document.getElementById('estimated_repair_cost');
            const replacementCostField = document.getElementById('replacement_cost');

            damageConditionSelect.addEventListener('change', function() {
                const condition = this.value;

                if (condition === 'non_repairable') {
                    repairCostField.disabled = true;
                    repairCostField.value = '';
                    replacementCostField.disabled = false;
                } else if (condition === 'replacement_needed') {
                    repairCostField.disabled = true;
                    repairCostField.value = '';
                    replacementCostField.disabled = false;
                } else if (condition === 'repairable') {
                    repairCostField.disabled = false;
                    replacementCostField.disabled = true;
                    replacementCostField.value = '';
                } else {
                    repairCostField.disabled = false;
                    replacementCostField.disabled = false;
                }
            });
        });
    </script>
@endpush
