@extends('layouts.app')

@section('title', 'أنواع الإيرادات - الإعدادات')

@section('content')
    <div class="space-y-6">
        <!-- Header with Breadcrumb and Back Button -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-2 space-x-reverse mb-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">
                        <i class="ri-home-line"></i>
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('settings.index') }}"
                        class="text-gray-600 hover:text-emerald-600 transition-colors">الإعدادات</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-emerald-600 font-medium">أنواع الإيرادات</span>
                </div>
                <div class="flex items-center space-x-reverse space-x-4">
                    <a href="{{ route('settings.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">أنواع الإيرادات</h1>
                </div>
                <p class="text-gray-600 mt-2">إدارة شاملة لأنواع ومصادر الإيرادات</p>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div
                    class="w-24 h-24 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-hand-coin-line text-white text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-600">{{ $types->total() }}</div>
                    <div class="text-gray-600 mt-2">إجمالي الأنواع</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-600">{{ $types->where('is_active', true)->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">أنواع فعّالة</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $types->where('is_active', false)->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">أنواع غير فعّالة</div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Header with Add Button -->
            <div class="flex items-center justify-between mb-6 p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">قائمة أنواع الإيرادات</h2>
                <button onclick="openRevenueTypeModal()"
                    class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-3 rounded-xl font-medium hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 flex items-center">
                    <i class="ri-add-line ml-2"></i>
                    <span>إضافة نوع إيراد جديد</span>
                </button>
            </div>

            <!-- Table -->
            @if ($types->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الاسم</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الوصف</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الحالة</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">التاريخ</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($types as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div
                                                class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-hand-coin-line text-emerald-600"></i>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $item->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="text-sm">{{ Str::limit($item->description, 50) ?? 'بدون وصف' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($item->is_active)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                                <i class="ri-check-line ml-1"></i>
                                                فعّال
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="ri-close-line ml-1"></i>
                                                غير فعّال
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600 text-sm">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2 space-x-reverse">
                                            <button
                                                onclick="editRevenueType({{ $item->id }}, '{{ $item->name }}', '{{ $item->description }}', {{ $item->is_active ? 'true' : 'false' }})"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button
                                                onclick="deleteRevenueType({{ $item->id }}, '{{ $item->name }}')"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="حذف">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($types->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $types->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-hand-coin-line text-emerald-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد أنواع إيرادات حالياً</h3>
                    <p class="text-gray-600 mb-6 text-center max-w-md">ابدأ بإضافة نوع إيراد جديد لتنظيم إيراداتك</p>
                    <button onclick="openRevenueTypeModal()"
                        class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-8 py-3 rounded-xl font-medium hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 flex items-center">
                        <i class="ri-add-line ml-2"></i>
                        <span>إضافة نوع إيراد جديد</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="revenueTypeModal" class="modal">
        <div class="modal-content" style="border-top: 4px solid #059669;">
            <div class="modal-header">
                <h2 id="modalTitle">إضافة نوع إيراد جديد</h2>
                <button onclick="closeRevenueTypeModal()" class="close-btn">&times;</button>
            </div>

            <form id="revenueTypeForm" method="POST"
                action="{{ isset($editingId) && $editingId ? route('settings.revenue-types.update', $editingId) : route('settings.revenue-types.store') }}">
                @csrf
                <input type="hidden" id="revenueTypeId" name="id" value="">
                <input type="hidden" id="revenueTypeMethod" name="_method" value="POST">

                <div class="form-group">
                    <label for="revenueTypeName">اسم نوع الإيراد *</label>
                    <input type="text" id="revenueTypeName" name="name" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="revenueTypeDescription">الوصف</label>
                    <textarea id="revenueTypeDescription" name="description" rows="3" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="revenueTypeActive" name="is_active" value="1">
                        <span>نشط</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeRevenueTypeModal()" class="btn btn-secondary">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteRevenueTypeModal" class="modal">
        <div class="modal-content modal-danger">
            <div class="modal-header">
                <h2>تأكيد الحذف</h2>
                <button onclick="closeDeleteRevenueTypeModal()" class="close-btn">&times;</button>
            </div>

            <div class="modal-body">
                <p>هل أنت متأكد من حذف نوع الإيراد <strong id="deleteRevenueTypeName"></strong>؟</p>
                <p class="warning-text">هذا الإجراء لا يمكن التراجع عنه</p>
            </div>

            <div class="form-actions">
                <button type="button" onclick="closeDeleteRevenueTypeModal()" class="btn btn-secondary">إلغاء</button>
                <button type="button" onclick="confirmDeleteRevenueType()" class="btn btn-danger">حذف</button>
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
        let currentRevenueTypeId = null;

        function openRevenueTypeModal() {
            clearRevenueTypeForm();
            document.getElementById('modalTitle').textContent = 'إضافة نوع إيراد جديد';
            document.getElementById('revenueTypeMethod').value = 'POST';
            document.getElementById('revenueTypeForm').action = '{{ route('settings.revenue-types.store') }}';
            document.getElementById('revenueTypeModal').style.display = 'block';
        }

        function closeRevenueTypeModal() {
            document.getElementById('revenueTypeModal').style.display = 'none';
            clearRevenueTypeForm();
        }

        function clearRevenueTypeForm() {
            document.getElementById('revenueTypeForm').reset();
            document.getElementById('revenueTypeId').value = '';
            document.getElementById('revenueTypeActive').checked = false;
        }

        function editRevenueType(id, name, description, isActive) {
            currentRevenueTypeId = id;
            document.getElementById('modalTitle').textContent = 'تحرير نوع الإيراد';
            document.getElementById('revenueTypeId').value = id;
            document.getElementById('revenueTypeName').value = name;
            document.getElementById('revenueTypeDescription').value = description;
            document.getElementById('revenueTypeActive').checked = isActive;
            document.getElementById('revenueTypeMethod').value = 'PUT';
            document.getElementById('revenueTypeForm').action = `/settings/revenue-types/${id}`;
            document.getElementById('revenueTypeModal').style.display = 'block';
        }

        function deleteRevenueType(id, name) {
            currentRevenueTypeId = id;
            document.getElementById('deleteRevenueTypeName').textContent = name;
            document.getElementById('deleteRevenueTypeModal').style.display = 'block';
        }

        function closeDeleteRevenueTypeModal() {
            document.getElementById('deleteRevenueTypeModal').style.display = 'none';
        }

        function confirmDeleteRevenueType() {
            if (!currentRevenueTypeId) return;

            fetch(`/settings/revenue-types/${currentRevenueTypeId}`, {
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

            closeDeleteRevenueTypeModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('revenueTypeModal');
            let deleteModal = document.getElementById('deleteRevenueTypeModal');

            if (event.target === modal) {
                closeRevenueTypeModal();
            }
            if (event.target === deleteModal) {
                closeDeleteRevenueTypeModal();
            }
        }
    </script>
@endsection
