<div class="space-y-6">
    <!-- Search and Filter Bar -->
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" id="search-expense-categories"
                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="البحث في فئات المصروفات...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="ri-search-line text-gray-400"></i>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="filterExpenseCategories('all')"
                class="expense-filter-btn active px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                data-filter="all">الكل</button>
            <button onclick="filterExpenseCategories('active')"
                class="expense-filter-btn px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                data-filter="active">فعال</button>
            <button onclick="filterExpenseCategories('inactive')"
                class="expense-filter-btn px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                data-filter="inactive">غير فعال</button>
        </div>
    </div>

    <!-- Categories Table -->
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
                <tbody id="expense-categories-tbody" class="bg-white divide-y divide-gray-200">
                    @forelse($expenseCategories ?? [] as $category)
                        <tr class="expense-category-row hover:bg-gray-50"
                            data-status="{{ $category->is_active ? 'active' : 'inactive' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span
                                    class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">{{ $category->code }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="max-w-xs truncate">{{ $category->description ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($category->is_active)
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
                                    <button onclick="editExpenseCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->code) }}', '{{ addslashes($category->description ?? '') }}', {{ $category->is_active ? 'true' : 'false' }})"
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="تعديل">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button onclick="toggleExpenseCategoryStatus({{ $category->id }})"
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors"
                                        title="تبديل الحالة">
                                        <i class="ri-toggle-line"></i>
                                    </button>
                                    <button onclick="deleteExpenseCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->code) }}')"
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
                                    <p class="text-lg font-medium mb-2">لا توجد فئات مصروفات</p>
                                    <p class="text-sm">ابدأ بإضافة فئة مصروفات جديدة</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-expense-category-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">تأكيد الحذف</h3>
            <p class="text-sm text-gray-500 mb-4">
                هل أنت متأكد أنك تريد حذف فئة المصروفات التالية؟
            </p>
            <p class="text-sm font-medium text-gray-900" id="delete-expense-category-name"></p>
            <p class="text-sm text-gray-500" id="delete-expense-category-code"></p>
            <div class="flex justify-end gap-2 mt-4">
                <button onclick="closeDeleteExpenseCategoryModal()"
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                    إلغاء
                </button>
                <button id="confirm-delete-expense-category-btn"
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors">
                    حذف
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Expense Categories JavaScript Functions
    function filterExpenseCategories(status) {
        const rows = document.querySelectorAll('.expense-category-row');
        const buttons = document.querySelectorAll('.expense-filter-btn');

        // Update button states
        buttons.forEach(btn => {
            btn.classList.remove('active', 'bg-blue-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-600');
        });

        const activeBtn = document.querySelector(`[data-filter="${status}"]`);
        activeBtn.classList.remove('bg-gray-200', 'text-gray-600');
        activeBtn.classList.add('active', 'bg-blue-600', 'text-white');

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

    function editExpenseCategory(id, name, code, description, isActive) {
        console.log('Edit expense category called with:', {id, name, code, description, isActive});
        
        // Set modal title and button text
        const modalTitle = document.getElementById('expense-category-modal-title');
        const submitText = document.getElementById('expense-category-submit-text');
        const form = document.getElementById('expense-category-form');
        const methodField = document.getElementById('expense-category-form-method');
        
        if (modalTitle && submitText && form && methodField) {
            modalTitle.textContent = 'تعديل فئة المصروف';
            submitText.textContent = 'تحديث';
            
            // Set form action and method
            form.action = `/settings/expense-categories/${id}`;
            methodField.value = 'PUT';
            
            // Fill form fields
            document.getElementById('expense-category-id').value = id;
            document.getElementById('expense_category_name').value = name;
            document.getElementById('expense_category_code').value = code;
            document.getElementById('expense_category_description').value = description;
            document.getElementById('expense_category_is_active').checked = isActive;
            
            // Clear errors and show modal
            clearExpenseCategoryErrors();
            document.getElementById('expense-category-modal').classList.remove('hidden');
        } else {
            console.error('Required elements not found for expense category edit modal');
        }
    }

    function toggleExpenseCategoryStatus(id) {
        console.log('Toggle expense category status:', id);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('لم يتم العثور على رمز CSRF');
            return;
        }
        
        // Show loading state on the button
        const toggleBtn = document.querySelector(`button[onclick="toggleExpenseCategoryStatus(${id})"]`);
        if (toggleBtn) {
            toggleBtn.disabled = true;
            toggleBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';
        }
        
        fetch(`/settings/expense-categories/${id}/toggle-status`, {
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
                    loadSectionContent('{{ route('settings.expense-categories.content') }}');
                } else {
                    // Fallback: reload the page
                    window.location.reload();
                }
            } else {
                alert(data.message || 'حدث خطأ أثناء تغيير حالة فئة المصروف');
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

    function deleteExpenseCategory(id, name = '', code = '') {
        console.log('Delete expense category called with:', {id, name, code});
        
        // Store expense category info for deletion
        window.expenseCategoryToDelete = { id, name, code };
        
        // Update modal content 
        document.getElementById('delete-expense-category-name').textContent = name || 'فئة مصروف غير محددة';
        document.getElementById('delete-expense-category-code').textContent = code || 'كود غير محدد';
        
        // Show delete modal
        document.getElementById('delete-expense-category-modal').classList.remove('hidden');
    }

    function closeDeleteExpenseCategoryModal() {
        document.getElementById('delete-expense-category-modal').classList.add('hidden');
    }

    function confirmDeleteExpenseCategory() {
        console.log('Confirm delete expense category called, expenseCategoryToDelete:', window.expenseCategoryToDelete);
        
        if (!window.expenseCategoryToDelete) {
            console.log('No expense category to delete found');
            return;
        }
        
        const id = window.expenseCategoryToDelete.id;
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        if (!csrfToken) {
            alert('لم يتم العثور على رمز CSRF');
            return;
        }
        
        // Show loading state
        const confirmBtn = document.getElementById('confirm-delete-expense-category-btn');
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = 'جاري الحذف...';
        confirmBtn.disabled = true;
        
        fetch(`/settings/expense-categories/${id}`, {
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
                closeDeleteExpenseCategoryModal();
                // Reload the content to remove the deleted item
                if (currentSection === 'expense-categories') {
                    loadSectionContent('{{ route('settings.expense-categories.content') }}');
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
        const searchInput = document.getElementById('search-expense-categories');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.expense-category-row');

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
