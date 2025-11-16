<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-900">أنواع الإيرادات</h3>
            <p class="text-gray-600 text-sm mt-1">إدارة أنواع الإيرادات والمصادر</p>
        </div>
        <button onclick="openRevenueTypeModal()"
            class="flex items-center px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-medium text-sm rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-sm hover:shadow-md">
            <i class="ri-add-line ml-2"></i>
            <span>إضافة نوع</span>
        </button>
    </div>

    <!-- Revenue Types Table -->
    <div class="overflow-hidden border border-gray-200 rounded-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الاسم</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الكود</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الوصف</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="revenue-types-tbody" class="divide-y divide-gray-100">
                    @forelse($revenueTypes ?? [] as $type)
                        <tr class="revenue-type-row hover:bg-gray-50 transition-colors duration-150"
                            data-status="{{ $type->is_active ? 'active' : 'inactive' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-emerald-100 to-emerald-200 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                        <i class="ri-coin-line text-emerald-600"></i>
                                    </div>
                                    <div class="font-medium text-gray-900">{{ $type->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-mono bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $type->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $type->description ?: '—' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($type->is_active)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-2 h-2 ml-2 bg-green-500 rounded-full"></span>
                                        نشط
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-300">
                                        <span class="w-2 h-2 ml-2 bg-gray-400 rounded-full"></span>
                                        معطل
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <button
                                        onclick="editRevenueType({{ $type->id }}, '{{ addslashes($type->name) }}', '{{ addslashes($type->code) }}', '{{ addslashes($type->description ?? '') }}', {{ $type->is_active ? 'true' : 'false' }})"
                                        class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-150"
                                        title="تعديل">
                                        <i class="ri-edit-line text-lg"></i>
                                    </button>
                                    <button onclick="toggleRevenueTypeStatus({{ $type->id }})"
                                        class="inline-flex items-center justify-center p-2 text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-150"
                                        title="تبديل الحالة">
                                        <i class="ri-toggle-line text-lg"></i>
                                    </button>
                                    <button
                                        onclick="deleteRevenueType({{ $type->id }}, '{{ addslashes($type->name) }}', '{{ addslashes($type->code) }}')"
                                        class="inline-flex items-center justify-center p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
                                        title="حذف">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                                        <i class="ri-coin-line text-emerald-600 text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">لا توجد أنواع إيرادات</h3>
                                    <p class="text-gray-600 text-sm mb-8">ابدأ بإضافة أول نوع إيراد في النظام</p>
                                    <button onclick="openRevenueTypeModal()"
                                        class="flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="ri-add-line ml-2"></i>
                                        <span>إضافة أول نوع</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-revenue-type-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="bg-black bg-opacity-50 absolute inset-0"></div>
        <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-sm w-full z-10">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">تأكيد الحذف</h3>
                <p class="text-sm text-gray-700 mb-4">
                    هل أنت متأكد أنك تريد حذف نوع الإيراد التالي؟
                </p>
                <p class="text-sm font-medium text-gray-900 mb-2">
                    الاسم: <span id="delete-revenue-type-name" class="font-normal"></span>
                </p>
                <p class="text-sm font-medium text-gray-900 mb-4">
                    الكود: <span id="delete-revenue-type-code" class="font-normal"></span>
                </p>
                <div class="flex gap-2">
                    <button id="confirm-delete-revenue-type-btn"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors">
                        حذف
                    </button>
                    <button onclick="closeDeleteRevenueTypeModal()"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Revenue Types JavaScript Functions
    function filterRevenueTypes(status) {
        const rows = document.querySelectorAll('.revenue-type-row');
        const buttons = document.querySelectorAll('.revenue-filter-btn');

        // Update button states
        buttons.forEach(btn => {
            btn.classList.remove('active', 'bg-emerald-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-600');
        });

        const activeBtn = document.querySelector(`[data-filter="${status}"]`);
        activeBtn.classList.remove('bg-gray-200', 'text-gray-600');
        activeBtn.classList.add('active', 'bg-emerald-600', 'text-white');

        // Filter rows
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else {
                const rowStatus = row.getAttribute('data-status');
                row.style.display = rowStatus === status ? '' : 'none';
            }
        });
    }

    function editRevenueType(id, name, code, description, isActive) {
        console.log('Edit revenue type called with:', {
            id,
            name,
            code,
            description,
            isActive
        });

        // Set modal title and button text
        const modalTitle = document.getElementById('revenue-type-modal-title');
        const submitText = document.getElementById('revenue-type-submit-text');
        const form = document.getElementById('revenue-type-form');
        const methodField = document.getElementById('revenue-type-form-method');

        if (modalTitle && submitText && form && methodField) {
            modalTitle.textContent = 'تعديل نوع الإيراد';
            submitText.textContent = 'تحديث';

            // Set form action and method
            form.action = `/settings/revenue-types/${id}`;
            methodField.value = 'PUT';

            // Fill form fields
            document.getElementById('revenue-type-id').value = id;
            document.getElementById('revenue_type_name').value = name;
            document.getElementById('revenue_type_code').value = code;
            document.getElementById('revenue_type_description').value = description;
            document.getElementById('revenue_type_is_active').checked = isActive;

            // Clear errors and show modal
            clearRevenueTypeErrors();
            document.getElementById('revenue-type-modal').classList.remove('hidden');
        } else {
            console.error('Required elements not found for revenue type edit modal');
        }
    }

    function toggleRevenueTypeStatus(id) {
        console.log('Toggle revenue type status:', id);

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('لم يتم العثور على رمز CSRF');
            return;
        }

        // Show loading state on the button
        const toggleBtn = document.querySelector(`button[onclick="toggleRevenueTypeStatus(${id})"]`);
        if (toggleBtn) {
            toggleBtn.disabled = true;
            toggleBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';
        }

        fetch(`/settings/revenue-types/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Toggle status response:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Toggle status response data:', data);
                if (data.success) {
                    // Check if we're in the global context and have access to loadSectionContent
                    if (typeof loadSectionContent === 'function') {
                        loadSectionContent('{{ route('settings.revenue-types.content') }}');
                    } else {
                        // Fallback: reload the page
                        window.location.reload();
                    }
                } else {
                    alert(data.message || 'حدث خطأ أثناء تغيير حالة نوع الإيراد');
                    // Reset button state
                    if (toggleBtn) {
                        toggleBtn.disabled = false;
                        toggleBtn.innerHTML = '<i class="ri-toggle-line"></i>';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال: ' + error.message);
                // Reset button state
                if (toggleBtn) {
                    toggleBtn.disabled = false;
                    toggleBtn.innerHTML = '<i class="ri-toggle-line"></i>';
                }
            });
    }

    function deleteRevenueType(id, name = '', code = '') {
        console.log('Delete revenue type called with:', {
            id,
            name,
            code
        });

        // Store revenue type info for deletion
        window.revenueTypeToDelete = {
            id,
            name,
            code
        };

        // Update modal content 
        document.getElementById('delete-revenue-type-name').textContent = name || 'نوع إيراد غير محدد';
        document.getElementById('delete-revenue-type-code').textContent = code || 'كود غير محدد';

        // Show delete modal
        document.getElementById('delete-revenue-type-modal').classList.remove('hidden');
    }

    function closeDeleteRevenueTypeModal() {
        document.getElementById('delete-revenue-type-modal').classList.add('hidden');
    }

    function confirmDeleteRevenueType() {
        console.log('Confirm delete revenue type called, revenueTypeToDelete:', window.revenueTypeToDelete);

        if (!window.revenueTypeToDelete) {
            console.log('No revenue type to delete found');
            return;
        }

        const id = window.revenueTypeToDelete.id;
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        if (!csrfToken) {
            alert('لم يتم العثور على رمز CSRF');
            return;
        }

        // Show loading state
        const confirmBtn = document.getElementById('confirm-delete-revenue-type-btn');
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = 'جاري الحذف...';
        confirmBtn.disabled = true;

        fetch(`/settings/revenue-types/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDeleteRevenueTypeModal();
                    // Reload the content to remove the deleted item
                    if (typeof loadSectionContent === 'function') {
                        loadSectionContent('{{ route('settings.revenue-types.content') }}');
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert(data.message || 'حدث خطأ أثناء الحذف');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال');
            })
            .finally(() => {
                // Reset button state
                confirmBtn.textContent = originalText;
                confirmBtn.disabled = false;
            });
    }

    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-revenue-types');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.revenue-type-row');

                rows.forEach(row => {
                    const name = row.children[1].textContent.toLowerCase();
                    const code = row.children[2].textContent.toLowerCase();
                    const description = row.children[3].textContent.toLowerCase();

                    if (name.includes(searchTerm) || code.includes(searchTerm) || description
                        .includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
