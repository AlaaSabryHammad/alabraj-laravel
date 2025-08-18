<div class="space-y-6">
    <!-- Search and Filter Bar -->
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" id="search-revenue-types"
                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="البحث في أنواع الإيرادات...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="ri-search-line text-gray-400"></i>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="filterRevenueTypes('all')"
                class="revenue-filter-btn active px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                data-filter="all">الكل</button>
            <button onclick="filterRevenueTypes('active')"
                class="revenue-filter-btn px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                data-filter="active">فعال</button>
            <button onclick="filterRevenueTypes('inactive')"
                class="revenue-filter-btn px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                data-filter="inactive">غير فعال</button>
        </div>
    </div>

    <!-- Revenue Types Table -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الرقم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الاسم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الكود</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الوصف</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="revenue-types-tbody" class="bg-white divide-y divide-gray-200">
                    @forelse($revenueTypes ?? [] as $type)
                        <tr class="revenue-type-row hover:bg-gray-50"
                            data-status="{{ $type->is_active ? 'active' : 'inactive' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $type->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $type->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">{{ $type->code }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="max-w-xs truncate">{{ $type->description ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($type->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="ri-checkbox-circle-line ml-1"></i>
                                        فعال
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="ri-close-circle-line ml-1"></i>
                                        غير فعال
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button onclick="editRevenueType({{ $type->id }}, '{{ addslashes($type->name) }}', '{{ addslashes($type->code) }}', '{{ addslashes($type->description ?? '') }}', {{ $type->is_active ? 'true' : 'false' }})"
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="تعديل">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button onclick="toggleRevenueTypeStatus({{ $type->id }})"
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors"
                                        title="تبديل الحالة">
                                        <i class="ri-toggle-line"></i>
                                    </button>
                                    <button onclick="deleteRevenueType({{ $type->id }}, '{{ addslashes($type->name) }}', '{{ addslashes($type->code) }}')"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="حذف">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="ri-folder-open-line text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium mb-2">لا توجد أنواع إيرادات</p>
                                    <p class="text-sm">ابدأ بإضافة نوع إيراد جديد</p>
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
        console.log('Edit revenue type called with:', {id, name, code, description, isActive});
        
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
        console.log('Delete revenue type called with:', {id, name, code});
        
        // Store revenue type info for deletion
        window.revenueTypeToDelete = { id, name, code };
        
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
