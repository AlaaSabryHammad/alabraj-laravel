<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-900">فئات المصروفات</h3>
            <p class="text-gray-600 text-sm mt-1">إدارة فئات المصروفات والتصنيفات</p>
        </div>
        <button onclick="openExpenseCategoryModal()"
            class="flex items-center px-6 py-2.5 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-medium text-sm rounded-lg hover:from-orange-700 hover:to-orange-800 transition-all duration-200 shadow-sm hover:shadow-md">
            <i class="ri-add-line ml-2"></i>
            <span>إضافة فئة</span>
        </button>
    </div>

    <!-- Table -->
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
                <tbody id="expense-categories-tbody" class="divide-y divide-gray-100">
                    @forelse($expenseCategories ?? [] as $category)
                        <tr class="expense-category-row hover:bg-gray-50 transition-colors duration-150"
                            data-status="{{ $category->is_active ? 'active' : 'inactive' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-orange-100 to-orange-200 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                        <i class="ri-price-tag-3-line text-orange-600"></i>
                                    </div>
                                    <div class="font-medium text-gray-900">{{ $category->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-mono bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $category->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $category->description ?: '—' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($category->is_active)
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
                                        onclick="editExpenseCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->code) }}', '{{ addslashes($category->description ?? '') }}', {{ $category->is_active ? 'true' : 'false' }})"
                                        class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-150"
                                        title="تعديل">
                                        <i class="ri-edit-line text-lg"></i>
                                    </button>
                                    <button onclick="toggleExpenseCategoryStatus({{ $category->id }})"
                                        class="inline-flex items-center justify-center p-2 text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-150"
                                        title="تبديل الحالة">
                                        <i class="ri-toggle-line text-lg"></i>
                                    </button>
                                    <button
                                        onclick="deleteExpenseCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->code) }}')"
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
                                        class="w-20 h-20 bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl flex items-center justify-center mb-6">
                                        <i class="ri-price-tag-3-line text-orange-600 text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">لا توجد فئات مصروفات</h3>
                                    <p class="text-gray-600 text-sm mb-8">ابدأ بإضافة أول فئة مصروف في النظام</p>
                                    <button onclick="openExpenseCategoryModal()"
                                        class="flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-medium rounded-xl hover:from-orange-700 hover:to-orange-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="ri-add-line ml-2"></i>
                                        <span>إضافة أول فئة</span>
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
        console.log('Edit expense category called with:', {
            id,
            name,
            code,
            description,
            isActive
        });

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
        console.log('Delete expense category called with:', {
            id,
            name,
            code
        });

        // Store expense category info for deletion
        window.expenseCategoryToDelete = {
            id,
            name,
            code
        };

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
