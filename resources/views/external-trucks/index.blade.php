@extends('layouts.app')

@section('title', 'إدارة شاحنات النقل الخارجي - شركة الأبراج للمقاولات')

@push('styles')
    <style>
        /* Modal Animation */
        #deleteModal .relative {
            transform: scale(0.95);
            transition: transform 0.3s ease-out;
        }

        #deleteModal .relative.scale-100 {
            transform: scale(1);
        }

        /* Fade in animation for modal backdrop */
        #deleteModal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Hover effects for delete button */
        .delete-btn {
            transition: all 0.2s ease;
        }

        .delete-btn:hover {
            transform: scale(1.1);
        }

        /* Loading animation */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة شاحنات النقل الخارجي</h1>
                    <p class="text-gray-600">إدارة شاملة لشاحنات النقل الخارجي والسائقين</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('external-trucks.create') }}"
                        class="bg-gradient-to-r from-cyan-600 to-cyan-700 text-white px-6 py-3 rounded-xl font-medium hover:from-cyan-700 hover:to-cyan-800 transition-all duration-200 flex items-center">
                        <i class="ri-truck-fill ml-2"></i>
                        إضافة شاحنة جديدة
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                <div class="flex items-center">
                    <i class="ri-check-circle-line text-green-600 ml-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Filters and Search -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ route('external-trucks.index') }}" class="flex flex-wrap items-end gap-4"
                id="filterForm">
                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="رقم اللوحة، اسم السائق، أو رقم الجوال..."
                            class="w-full px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                            autocomplete="off">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ri-search-line text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="min-w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                    <select name="status"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                        onchange="this.form.submit()">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>قيد الصيانة
                        </option>
                    </select>
                </div>

                <!-- Supplier Filter -->
                <div class="min-w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">المورد</label>
                    <select name="supplier_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"
                        onchange="this.form.submit()">
                        <option value="">جميع الموردين</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-2 space-x-reverse">
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-cyan-600 rounded-lg hover:bg-cyan-700 focus:ring-2 focus:ring-cyan-500">
                        بحث
                    </button>
                    @if (request()->hasAny(['search', 'status', 'supplier_id']))
                        <a href="{{ route('external-trucks.index') }}"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50"
                            title="مسح المرشحات">
                            <i class="ri-close-line"></i>
                        </a>
                    @endif
                </div>
            </form>

            @if (request()->hasAny(['search', 'status', 'supplier_id']))
                <div class="mt-3 text-sm text-gray-600">
                    <span>النتائج المفلترة: </span>
                    <span class="mr-2">({{ $trucks->total() }} {{ $trucks->total() == 1 ? 'نتيجة' : 'نتائج' }})</span>
                </div>
            @endif
        </div>

        <!-- Trucks Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900">قائمة الشاحنات</h2>
            </div>

            @if ($trucks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الشاحنة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    السائق</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    اسم المورد</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($trucks as $truck)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center">
                                                <i class="ri-truck-fill text-white text-lg"></i>
                                            </div>
                                            <div class="mr-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $truck->plate_number }}
                                                </div>
                                                @if ($truck->contract_number)
                                                    <div class="text-sm text-gray-500">عقد رقم:
                                                        {{ $truck->contract_number }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $truck->driver_name }}</div>
                                        <div class="text-sm text-gray-500 dir-ltr">{{ $truck->driver_phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                                <i class="ri-building-line text-white text-sm"></i>
                                            </div>
                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $truck->supplier->name ?? 'غير محدد' }}</div>
                                                @if ($truck->supplier && $truck->supplier->phone)
                                                    <div class="text-xs text-gray-500 dir-ltr">
                                                        {{ $truck->supplier->phone }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'active' => 'bg-green-100 text-green-800',
                                                'inactive' => 'bg-red-100 text-red-800',
                                                'maintenance' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$truck->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $truck->status_text }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2 space-x-reverse">
                                            <a href="{{ route('external-trucks.show', $truck) }}"
                                                class="text-cyan-600 hover:text-cyan-900" title="عرض التفاصيل">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('external-trucks.edit', $truck) }}"
                                                class="text-indigo-600 hover:text-indigo-900" title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <form action="{{ route('external-trucks.destroy', $truck) }}" method="POST"
                                                class="inline delete-form" data-truck-name="{{ $truck->plate_number }}"
                                                data-driver-name="{{ $truck->driver_name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="text-red-600 hover:text-red-900 delete-btn"
                                                    title="حذف" data-truck-id="{{ $truck->id }}"
                                                    data-truck-name="{{ $truck->plate_number }}"
                                                    data-driver-name="{{ $truck->driver_name }}">
                                                    <i class="ri-delete-bin-line"></i>
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
                    {{ $trucks->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    @if (request()->hasAny(['search', 'status', 'supplier_id']))
                        <i class="ri-search-line text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد نتائج</h3>
                        <p class="text-gray-500 mb-6">لم يتم العثور على شاحنات مطابقة للمرشحات المحددة</p>
                        <div class="flex justify-center space-x-3 space-x-reverse">
                            <a href="{{ route('external-trucks.index') }}"
                                class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 inline-flex items-center">
                                <i class="ri-arrow-right-line ml-2"></i>
                                عرض جميع الشاحنات
                            </a>
                            <a href="{{ route('external-trucks.create') }}"
                                class="bg-gradient-to-r from-cyan-600 to-cyan-700 text-white px-6 py-3 rounded-xl font-medium hover:from-cyan-700 hover:to-cyan-800 transition-all duration-200 inline-flex items-center">
                                <i class="ri-truck-fill ml-2"></i>
                                إضافة شاحنة جديدة
                            </a>
                        </div>
                    @else
                        <i class="ri-truck-fill text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد شاحنات</h3>
                        <p class="text-gray-500 mb-6">ابدأ بإضافة شاحنة جديدة لإدارة أسطول النقل الخارجي</p>
                        <a href="{{ route('external-trucks.create') }}"
                            class="bg-gradient-to-r from-cyan-600 to-cyan-700 text-white px-6 py-3 rounded-xl font-medium hover:from-cyan-700 hover:to-cyan-800 transition-all duration-200 inline-flex items-center">
                            <i class="ri-truck-fill ml-2"></i>
                            إضافة شاحنة جديدة
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="ri-error-warning-line text-red-600 text-2xl"></i>
                </div>

                <!-- Title -->
                <h3 class="text-lg font-medium text-gray-900 mt-4">تأكيد حذف الشاحنة</h3>

                <!-- Message -->
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        هل أنت متأكد من حذف الشاحنة رقم
                        <span id="truckName" class="font-semibold text-gray-900"></span>
                        الخاصة بالسائق
                        <span id="driverName" class="font-semibold text-gray-900"></span>؟
                    </p>
                    <p class="text-sm text-red-600 mt-2">
                        <i class="ri-information-line"></i>
                        هذا الإجراء لا يمكن التراجع عنه
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-center space-x-4 space-x-reverse mt-4">
                    <button id="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <i class="ri-delete-bin-line ml-1"></i>
                        حذف الشاحنة
                    </button>
                    <button id="cancelDelete"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-submit search form when typing (with debounce)
            let searchTimeout;
            const searchInput = document.querySelector('input[name="search"]');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (this.value.length >= 2 || this.value.length === 0) {
                            this.form.submit();
                        }
                    }, 500); // Wait 500ms after user stops typing
                });

                // Focus search input when pressing "/" key
                document.addEventListener('keydown', function(e) {
                    if (e.key === '/' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT') {
                        e.preventDefault();
                        searchInput.focus();
                    }
                });
            }

            // Delete Modal functionality
            const deleteModal = document.getElementById('deleteModal');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const cancelDeleteBtn = document.getElementById('cancelDelete');
            const truckNameSpan = document.getElementById('truckName');
            const driverNameSpan = document.getElementById('driverName');
            let currentDeleteForm = null;

            // Show modal when delete button is clicked
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn')) {
                    e.preventDefault();

                    const deleteBtn = e.target.closest('.delete-btn');
                    const form = deleteBtn.closest('.delete-form');

                    currentDeleteForm = form;
                    truckNameSpan.textContent = deleteBtn.dataset.truckName;
                    driverNameSpan.textContent = deleteBtn.dataset.driverName;

                    // Show modal
                    deleteModal.classList.remove('hidden');

                    // Add animation
                    setTimeout(() => {
                        deleteModal.querySelector('.relative').classList.add('scale-100');
                    }, 10);
                }
            });

            // Hide modal function
            function hideDeleteModal() {
                deleteModal.classList.add('hidden');
                deleteModal.querySelector('.relative').classList.remove('scale-100');
                currentDeleteForm = null;
            }

            // Confirm delete
            confirmDeleteBtn.addEventListener('click', function() {
                if (currentDeleteForm) {
                    // Add loading state
                    confirmDeleteBtn.innerHTML = '<i class="ri-loader-4-line animate-spin ml-1"></i> جاري الحذف...';
                    confirmDeleteBtn.disabled = true;

                    // Submit the form
                    currentDeleteForm.submit();
                }
            });

            // Cancel delete
            cancelDeleteBtn.addEventListener('click', hideDeleteModal);

            // Close modal when clicking outside
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    hideDeleteModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                    hideDeleteModal();
                }
            });

            // Debug function to check current filters
            function showCurrentFilters() {
                const search = document.querySelector('input[name="search"]').value;
                const status = document.querySelector('select[name="status"]').value;
                const loadingType = document.querySelector('select[name="loading_type"]').value;

                console.log('Current filters:', {
                    search: search,
                    status: status,
                    loading_type: loadingType,
                    url: window.location.href
                });
            }

            // Call debug function on page load
            document.addEventListener('DOMContentLoaded', function() {
                showCurrentFilters();
            });
        </script>
    @endpush
@endsection
