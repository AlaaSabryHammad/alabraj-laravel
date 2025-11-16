@extends('layouts.app')

@section('title', 'كيانات الإيرادات - الإعدادات')

@section('content')
    <div class="settings-container">
        <!-- Header -->
        <div class="settings-header">
            <a href="{{ route('settings.index') }}" class="back-button">
                <i class="ri-arrow-left-line"></i>
            </a>
            <div class="header-content">
                <h1>كيانات الإيرادات</h1>
                <p class="breadcrumb">الإعدادات / كيانات الإيرادات</p>
            </div>
            <i class="ri-building-line section-icon" style="color: #059669;"></i>
        </div>

        <!-- Statistics Card -->
        <div class="statistics-card" style="border-top: 4px solid #059669;">
            <div class="stat-item">
                <span class="stat-label">إجمالي الكيانات</span>
                <span class="stat-value" style="color: #059669;">{{ $entities->total() }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">نشط</span>
                <span class="stat-value" style="color: #10B981;">{{ $entities->count() }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">الصفحة الحالية</span>
                <span class="stat-value" style="color: #059669;">{{ $entities->currentPage() }}</span>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            @if ($entities->count() > 0)
                <div class="section-header">
                    <h2>قائمة كيانات الإيرادات</h2>
                    <button onclick="openRevenueEntityModal()" class="btn btn-primary">
                        <i class="ri-add-line"></i> إضافة كيان إيراد جديد
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>اسم الكيان</th>
                                <th>نوع الإيراد</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($entities as $entity)
                                <tr>
                                    <td><strong>{{ $entity->name }}</strong></td>
                                    <td>{{ $entity->revenueType?->name ?? '-' }}</td>
                                    <td>
                                        @if ($entity->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ $entity->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <button
                                            onclick="editRevenueEntity({{ $entity->id }}, '{{ $entity->name }}', {{ $entity->revenue_type_id ?? 'null' }}, {{ $entity->is_active ? 'true' : 'false' }})"
                                            class="btn btn-sm btn-info">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button onclick="deleteRevenueEntity({{ $entity->id }}, '{{ $entity->name }}')"
                                            class="btn btn-sm btn-danger">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($entities->hasPages())
                    <div class="pagination-wrapper">
                        {{ $entities->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="ri-building-line"></i>
                    <h3>لا توجد كيانات إيرادات</h3>
                    <p>ابدأ بإضافة كيان إيراد جديد</p>
                    <button onclick="openRevenueEntityModal()" class="btn btn-primary">
                        <i class="ri-add-line"></i> إضافة كيان إيراد
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="revenueEntityModal" class="modal">
        <div class="modal-content" style="border-top: 4px solid #059669;">
            <div class="modal-header">
                <h2 id="modalTitle">إضافة كيان إيراد جديد</h2>
                <button onclick="closeRevenueEntityModal()" class="close-btn">&times;</button>
            </div>

            <form id="revenueEntityForm" method="POST" action="{{ route('settings.revenue-entities.store') }}">
                @csrf
                <input type="hidden" id="revenueEntityId" name="id" value="">
                <input type="hidden" id="revenueEntityMethod" name="_method" value="POST">

                <div class="form-group">
                    <label for="revenueEntityName">اسم الكيان *</label>
                    <input type="text" id="revenueEntityName" name="name" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="revenueEntityTypeId">نوع الإيراد *</label>
                    <select id="revenueEntityTypeId" name="revenue_type_id" required class="form-control">
                        <option value="">-- اختر نوع الإيراد --</option>
                        @if (isset($revenueTypes))
                            @foreach ($revenueTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="revenueEntityActive" name="is_active" value="1">
                        <span>نشط</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeRevenueEntityModal()" class="btn btn-secondary">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteRevenueEntityModal" class="modal">
        <div class="modal-content modal-danger">
            <div class="modal-header">
                <h2>تأكيد الحذف</h2>
                <button onclick="closeDeleteRevenueEntityModal()" class="close-btn">&times;</button>
            </div>

            <div class="modal-body">
                <p>هل أنت متأكد من حذف كيان الإيراد <strong id="deleteRevenueEntityName"></strong>؟</p>
                <p class="warning-text">هذا الإجراء لا يمكن التراجع عنه</p>
            </div>

            <div class="form-actions">
                <button type="button" onclick="closeDeleteRevenueEntityModal()" class="btn btn-secondary">إلغاء</button>
                <button type="button" onclick="confirmDeleteRevenueEntity()" class="btn btn-danger">حذف</button>
            </div>
        </div>
    </div>

    <style>
        .settings-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .settings-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            position: relative;
        }

        .back-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f0f0f0;
            color: #059669;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 20px;
        }

        .back-button:hover {
            background: #059669;
            color: white;
            transform: translateX(-3px);
        }

        .header-content h1 {
            margin: 0;
            font-size: 28px;
            color: #1f2937;
            font-weight: 600;
        }

        .header-content .breadcrumb {
            color: #6b7280;
            font-size: 14px;
            margin: 5px 0 0 0;
        }

        .section-icon {
            margin-left: auto;
            font-size: 48px;
            opacity: 0.2;
        }

        .statistics-card {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
        }

        .content-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-header h2 {
            margin: 0;
            font-size: 20px;
            color: #1f2937;
            font-weight: 600;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table thead tr {
            background: #f8f9fa;
            border-bottom: 2px solid #e5e7eb;
        }

        .data-table th {
            padding: 15px;
            text-align: right;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s ease;
        }

        .data-table tbody tr:hover {
            background: #f9fafb;
        }

        .data-table td {
            padding: 15px;
            text-align: right;
            color: #4b5563;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d1fae5;
            color: #059669;
        }

        .badge-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: #059669;
            color: white;
        }

        .btn-primary:hover {
            background: #047857;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .btn-info {
            background: #dbeafe;
            color: #0284c7;
        }

        .btn-info:hover {
            background: #bfdbfe;
        }

        .btn-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-danger:hover {
            background: #fecaca;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 64px;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            margin: 20px 0 10px 0;
            color: #374151;
            font-size: 20px;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-danger {
            border-top: 4px solid #dc2626 !important;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
            color: #1f2937;
            font-weight: 600;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            color: #9ca3af;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: #374151;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-body p {
            margin: 10px 0;
            color: #4b5563;
            line-height: 1.6;
        }

        .warning-text {
            color: #dc2626;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        select.form-control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23374151' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 10px center;
            padding-left: 30px;
            appearance: none;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #059669;
        }

        .checkbox-label span {
            color: #374151;
            font-weight: 500;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .form-actions button {
            padding: 10px 20px;
        }
    </style>

    <script>
        let currentRevenueEntityId = null;

        function openRevenueEntityModal() {
            clearRevenueEntityForm();
            document.getElementById('modalTitle').textContent = 'إضافة كيان إيراد جديد';
            document.getElementById('revenueEntityMethod').value = 'POST';
            document.getElementById('revenueEntityForm').action = '{{ route('settings.revenue-entities.store') }}';
            document.getElementById('revenueEntityModal').style.display = 'block';
        }

        function closeRevenueEntityModal() {
            document.getElementById('revenueEntityModal').style.display = 'none';
            clearRevenueEntityForm();
        }

        function clearRevenueEntityForm() {
            document.getElementById('revenueEntityForm').reset();
            document.getElementById('revenueEntityId').value = '';
            document.getElementById('revenueEntityActive').checked = false;
        }

        function editRevenueEntity(id, name, typeId, isActive) {
            currentRevenueEntityId = id;
            document.getElementById('modalTitle').textContent = 'تحرير كيان الإيراد';
            document.getElementById('revenueEntityId').value = id;
            document.getElementById('revenueEntityName').value = name;
            if (typeId) {
                document.getElementById('revenueEntityTypeId').value = typeId;
            }
            document.getElementById('revenueEntityActive').checked = isActive;
            document.getElementById('revenueEntityMethod').value = 'PUT';
            document.getElementById('revenueEntityForm').action = `/settings/revenue-entities/${id}`;
            document.getElementById('revenueEntityModal').style.display = 'block';
        }

        function deleteRevenueEntity(id, name) {
            currentRevenueEntityId = id;
            document.getElementById('deleteRevenueEntityName').textContent = name;
            document.getElementById('deleteRevenueEntityModal').style.display = 'block';
        }

        function closeDeleteRevenueEntityModal() {
            document.getElementById('deleteRevenueEntityModal').style.display = 'none';
        }

        function confirmDeleteRevenueEntity() {
            if (!currentRevenueEntityId) return;

            fetch(`/settings/revenue-entities/${currentRevenueEntityId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ في الاتصال');
                });

            closeDeleteRevenueEntityModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('revenueEntityModal');
            let deleteModal = document.getElementById('deleteRevenueEntityModal');

            if (event.target === modal) {
                closeRevenueEntityModal();
            }
            if (event.target === deleteModal) {
                closeDeleteRevenueEntityModal();
            }
        }
    </script>
@endsection
