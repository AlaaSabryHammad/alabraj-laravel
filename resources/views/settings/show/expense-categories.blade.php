@extends('layouts.app')

@section('title', 'فئات المصروفات - الإعدادات')

@section('content')
    <div class="space-y-6">
        <!-- Header with Breadcrumb and Back Button -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-2 space-x-reverse mb-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="ri-home-line"></i>
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('settings.index') }}"
                        class="text-gray-600 hover:text-blue-600 transition-colors">الإعدادات</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-blue-600 font-medium">فئات المصروفات</span>
                </div>
                <div class="flex items-center space-x-reverse space-x-4">
                    <a href="{{ route('settings.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">فئات المصروفات</h1>
                </div>
                <p class="text-gray-600 mt-2">إدارة فئات وأنواع المصروفات المالية</p>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div
                    class="w-24 h-24 bg-gradient-to-r from-red-500 via-red-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-money-dollar-circle-line text-white text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $categories->total() }}</div>
                    <div class="text-gray-600 mt-2">إجمالي الفئات</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $categories->where('is_active', true)->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">فئات فعّالة</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-600">{{ $categories->where('is_active', false)->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">فئات معطلة</div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Header with Add Button -->
            <div class="flex items-center justify-between mb-6 p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">قائمة الفئات</h2>
                <button onclick="openExpenseCategoryModal()"
                    class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl font-medium hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center">
                    <i class="ri-add-line ml-2"></i>
                    <span>إضافة فئة جديدة</span>
                </button>
            </div>

            <!-- Table -->
            @if ($categories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الاسم</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الكود</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الوصف</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الحالة</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">التاريخ</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-price-tag-line text-red-600"></i>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $category->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600 text-sm">
                                        <code class="bg-gray-100 px-2 py-1 rounded">{{ $category->code }}</code>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm">
                                        {{ Str::limit($category->description, 40) ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($category->is_active)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="ri-check-line ml-1"></i>
                                                فعّال
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="ri-close-line ml-1"></i>
                                                معطل
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600 text-sm">
                                        {{ $category->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2 space-x-reverse">
                                            <button
                                                onclick="editExpenseCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->code }}', '{{ $category->description }}', {{ $category->is_active ? 'true' : 'false' }})"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button
                                                onclick="deleteExpenseCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->code }}')"
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
                @if ($categories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $categories->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-money-dollar-circle-line text-red-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد فئات مصروفات حالياً</h3>
                    <p class="text-gray-600 mb-6 text-center max-w-md">ابدأ بإضافة فئة مصروف جديدة</p>
                    <button onclick="openExpenseCategoryModal()"
                        class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-xl font-medium hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center">
                        <i class="ri-add-line ml-2"></i>
                        <span>إضافة فئة</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Expense Category Modal -->
    <div id="expense-category-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900" id="expense-category-modal-title">إضافة فئة مصروف
                            جديدة</h3>
                        <button onclick="closeExpenseCategoryModal()"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="expense-category-form" action="{{ route('settings.expense-categories.store') }}" method="POST"
                    class="p-6">
                    @csrf
                    <input type="hidden" id="expense-category-id" name="id">
                    <input type="hidden" id="expense-category-form-method" name="_method">

                    <div class="space-y-4">
                        <div>
                            <label for="expense_category_name" class="block text-sm font-medium text-gray-700 mb-2">اسم
                                الفئة *</label>
                            <input type="text" id="expense_category_name" name="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors">
                            <div id="expense_category_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="expense_category_code" class="block text-sm font-medium text-gray-700 mb-2">الكود
                                *</label>
                            <input type="text" id="expense_category_code" name="code" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors">
                            <div id="expense_category_code_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="expense_category_description"
                                class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                            <textarea id="expense_category_description" name="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors resize-none"
                                placeholder="وصف اختياري"></textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="expense_category_is_active" name="is_active" value="1"
                                checked
                                class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                            <label for="expense_category_is_active" class="mr-2 text-sm text-gray-700">فعّال</label>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeExpenseCategoryModal()"
                            class="flex-1 px-6 py-3 text-gray-700 bg-gray-100 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-medium hover:from-red-700 hover:to-red-800 transition-all duration-200">
                            <span id="expense-category-submit-text">إضافة</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Expense Category Modal -->
    <div id="delete-expense-category-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <i class="ri-delete-bin-line text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">حذف الفئة</h3>
                    <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذه الفئة؟</p>
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="ri-price-tag-line text-red-600"></i>
                            </div>
                            <div class="mr-3">
                                <div class="text-sm font-medium text-gray-900" id="delete-expense-category-name">اسم الفئة
                                </div>
                                <div class="text-xs text-gray-500" id="delete-expense-category-code">الكود</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteExpenseCategoryModal()"
                            class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="button" onclick="confirmDeleteExpenseCategory()"
                            id="confirm-delete-expense-category-btn"
                            class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200">
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openExpenseCategoryModal() {
            const modal = document.getElementById('expense-category-modal');
            const modalTitle = document.getElementById('expense-category-modal-title');
            const submitText = document.getElementById('expense-category-submit-text');

            modalTitle.textContent = 'إضافة فئة مصروف جديدة';
            submitText.textContent = 'إضافة';

            clearExpenseCategoryForm();
            modal.classList.remove('hidden');
        }

        function closeExpenseCategoryModal() {
            document.getElementById('expense-category-modal').classList.add('hidden');
            clearExpenseCategoryForm();
        }

        function clearExpenseCategoryForm() {
            const form = document.getElementById('expense-category-form');
            form.action = '{{ route('settings.expense-categories.store') }}';
            document.getElementById('expense-category-id').value = '';
            document.getElementById('expense_category_name').value = '';
            document.getElementById('expense_category_code').value = '';
            document.getElementById('expense_category_description').value = '';
            document.getElementById('expense_category_is_active').checked = true;
            document.getElementById('expense-category-form-method').value = '';
        }

        function editExpenseCategory(id, name, code, description, isActive) {
            const modalTitle = document.getElementById('expense-category-modal-title');
            const submitText = document.getElementById('expense-category-submit-text');
            const form = document.getElementById('expense-category-form');
            const methodField = document.getElementById('expense-category-form-method');

            modalTitle.textContent = 'تعديل الفئة';
            submitText.textContent = 'تحديث';
            form.action = `/settings/expense-categories/${id}`;
            methodField.value = 'PUT';

            document.getElementById('expense-category-id').value = id;
            document.getElementById('expense_category_name').value = name;
            document.getElementById('expense_category_code').value = code;
            document.getElementById('expense_category_description').value = description;
            document.getElementById('expense_category_is_active').checked = isActive;

            document.getElementById('expense-category-modal').classList.remove('hidden');
        }

        function deleteExpenseCategory(id, name = '', code = '') {
            window.expenseCategoryToDelete = {
                id,
                name,
                code
            };
            document.getElementById('delete-expense-category-name').textContent = name || 'فئة غير محددة';
            document.getElementById('delete-expense-category-code').textContent = code || 'كود غير محدد';
            document.getElementById('delete-expense-category-modal').classList.remove('hidden');
        }

        function closeDeleteExpenseCategoryModal() {
            document.getElementById('delete-expense-category-modal').classList.add('hidden');
            window.expenseCategoryToDelete = null;
        }

        function confirmDeleteExpenseCategory() {
            if (!window.expenseCategoryToDelete) return;

            const id = window.expenseCategoryToDelete.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) {
                alert('لم يتم العثور على رمز CSRF');
                return;
            }

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
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ في الاتصال');
                })
                .finally(() => {
                    confirmBtn.textContent = originalText;
                    confirmBtn.disabled = false;
                });
        }

        document.getElementById('expense-category-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeExpenseCategoryModal();
        });

        document.getElementById('delete-expense-category-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteExpenseCategoryModal();
        });
    </script>
@endsection
