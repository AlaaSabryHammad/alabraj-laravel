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
    <div id="revenueTypeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4 animate-slide-up pointer-events-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">إضافة نوع إيراد جديد</h2>
                    <button onclick="closeRevenueTypeModal()"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="revenueTypeForm" method="POST"
                    action="{{ isset($editingId) && $editingId ? route('settings.revenue-types.update', $editingId) : route('settings.revenue-types.store') }}"
                    class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" id="revenueTypeId" name="id" value="">
                    <input type="hidden" id="revenueTypeMethod" name="_method" value="POST">

                    <div>
                        <label for="revenueTypeName" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="ri-text ml-1 text-emerald-600"></i>
                            اسم نوع الإيراد <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="revenueTypeName"
                               name="name"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                               placeholder="أدخل اسم نوع الإيراد">
                    </div>

                    <div>
                        <label for="revenueTypeDescription" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="ri-file-text-line ml-1 text-emerald-600"></i>
                            الوصف
                        </label>
                        <textarea id="revenueTypeDescription"
                                  name="description"
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none"
                                  placeholder="وصف مختصر لنوع الإيراد"></textarea>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <input type="checkbox"
                               id="revenueTypeActive"
                               name="is_active"
                               value="1"
                               class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                        <label for="revenueTypeActive" class="text-sm font-semibold text-gray-700 cursor-pointer">
                            <i class="ri-checkbox-circle-line ml-1 text-emerald-600"></i>
                            نوع الإيراد نشط
                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button"
                                onclick="closeRevenueTypeModal()"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl font-medium hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-lg">
                            <i class="ri-save-line ml-1"></i>
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteRevenueTypeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 animate-slide-up pointer-events-auto">
                <div class="p-6">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                        <i class="ri-delete-bin-line text-red-600 text-3xl"></i>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 text-center mb-2">حذف نوع الإيراد</h3>
                    <p class="text-gray-600 text-center mb-6">
                        هل أنت متأكد من حذف نوع الإيراد
                        <strong id="deleteRevenueTypeName" class="text-gray-900"></strong>؟
                        <br>
                        <span class="text-red-600 font-semibold text-sm">لا يمكن التراجع عن هذا الإجراء</span>
                    </p>

                    <div class="flex gap-3">
                        <button type="button"
                                onclick="closeDeleteRevenueTypeModal()"
                                class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="button"
                                onclick="confirmDeleteRevenueType()"
                                class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg">
                            <i class="ri-delete-bin-line ml-1"></i>
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes slide-up {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-slide-up {
            animation: slide-up 0.3s ease-out;
        }
    </style>


    <script>
        let currentRevenueTypeId = null;

        function openRevenueTypeModal() {
            clearRevenueTypeForm();
            document.getElementById('modalTitle').textContent = 'إضافة نوع إيراد جديد';
            document.getElementById('revenueTypeMethod').value = 'POST';
            document.getElementById('revenueTypeForm').action = '{{ route('settings.revenue-types.store') }}';
            document.getElementById('revenueTypeModal').classList.remove('hidden');
        }

        function closeRevenueTypeModal() {
            document.getElementById('revenueTypeModal').classList.add('hidden');
            clearRevenueTypeForm();
        }

        function clearRevenueTypeForm() {
            document.getElementById('revenueTypeForm').reset();
            document.getElementById('revenueTypeId').value = '';
            document.getElementById('revenueTypeActive').checked = false;
        }

        function editRevenueType(id, name, description, isActive) {
            currentRevenueTypeId = id;
            document.getElementById('modalTitle').textContent = 'تعديل نوع الإيراد';
            document.getElementById('revenueTypeId').value = id;
            document.getElementById('revenueTypeName').value = name;
            document.getElementById('revenueTypeDescription').value = description;
            document.getElementById('revenueTypeActive').checked = isActive;
            document.getElementById('revenueTypeMethod').value = 'PUT';
            document.getElementById('revenueTypeForm').action = `/settings/revenue-types/${id}`;
            document.getElementById('revenueTypeModal').classList.remove('hidden');
        }

        function deleteRevenueType(id, name) {
            currentRevenueTypeId = id;
            document.getElementById('deleteRevenueTypeName').textContent = name;
            document.getElementById('deleteRevenueTypeModal').classList.remove('hidden');
        }

        function closeDeleteRevenueTypeModal() {
            document.getElementById('deleteRevenueTypeModal').classList.add('hidden');
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

        // Handle form submission via AJAX
        document.getElementById('revenueTypeForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = this.action;
            const method = document.getElementById('revenueTypeMethod').value;

            // Convert FormData to JSON
            const data = {};
            formData.forEach((value, key) => {
                if (key !== '_method' && key !== 'id') {
                    data[key] = value;
                }
            });

            // Add _method for PUT requests
            if (method === 'PUT') {
                data._method = 'PUT';
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeRevenueTypeModal();
                    location.reload();
                } else {
                    alert(data.message || 'حدث خطأ أثناء الحفظ');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال');
            });
        });

        // Close modal when clicking outside
        document.getElementById('revenueTypeModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeRevenueTypeModal();
        });

        document.getElementById('deleteRevenueTypeModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteRevenueTypeModal();
        });
    </script>
@endsection
