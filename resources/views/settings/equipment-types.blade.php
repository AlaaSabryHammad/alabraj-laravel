@extends('layouts.app')

@section('title', 'أنواع المعدات - الإعدادات')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة أنواع المعدات</h1>
                    <p class="text-gray-600">إضافة وتعديل أنواع المعدات المختلفة في النظام</p>
                </div>
                <div class="flex items-center space-x-3 space-x-reverse">
                    <a href="{{ route('settings.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة للإعدادات
                    </a>
                    <button type="button" id="add-equipment-type-btn"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="ri-add-line ml-2"></i>
                        إضافة نوع جديد
                    </button>
                </div>
            </div>
        </div>

        <!-- Settings Tabs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Tab Content -->
            <div class="p-6">
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ri-check-circle-fill text-green-400 text-lg"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ri-error-warning-fill text-red-400 text-lg"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- AJAX Messages Area -->
                <div id="ajax-messages" class="mb-6" style="display: none;">
                    <!-- Success Message -->
                    <div id="ajax-success" class="bg-green-50 border border-green-200 rounded-md p-4 mb-4"
                        style="display: none;">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ri-check-circle-fill text-green-400 text-lg"></i>
                            </div>
                            <div class="mr-3">
                                <p id="ajax-success-message" class="text-sm font-medium text-green-800"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="ajax-error" class="bg-red-50 border border-red-200 rounded-md p-4 mb-4" style="display: none;">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ri-error-warning-fill text-red-400 text-lg"></i>
                            </div>
                            <div class="mr-3">
                                <p id="ajax-error-message" class="text-sm font-medium text-red-800"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipment Types Table -->
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    اسم النوع
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الوصف
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الحالة
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    عدد المعدات
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($equipmentTypes as $type)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $type->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $type->description ?? 'لا يوجد وصف' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($type->is_active)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="ri-check-line ml-1"></i>
                                                نشط
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="ri-close-line ml-1"></i>
                                                غير نشط
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $type->equipment_count ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <button type="button"
                                                class="edit-equipment-type-btn text-blue-600 hover:text-blue-900"
                                                data-id="{{ $type->id }}" data-name="{{ $type->name }}"
                                                data-description="{{ $type->description }}"
                                                data-active="{{ $type->is_active }}">
                                                <i class="ri-edit-line text-lg"></i>
                                            </button>
                                            @if (($type->equipment_count ?? 0) == 0)
                                                <button type="button"
                                                    class="delete-equipment-type-btn text-red-600 hover:text-red-900"
                                                    data-id="{{ $type->id }}" data-name="{{ $type->name }}">
                                                    <i class="ri-delete-bin-line text-lg"></i>
                                                </button>
                                            @else
                                                <span class="text-gray-400 cursor-not-allowed"
                                                    title="لا يمكن حذف نوع يحتوي على معدات">
                                                    <i class="ri-delete-bin-line text-lg"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="ri-tools-line text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-lg font-medium text-gray-900 mb-2">لا توجد أنواع معدات</p>
                                            <p class="text-gray-500 mb-4">ابدأ بإضافة أول نوع معدة للنظام</p>
                                            <button type="button" id="add-first-equipment-type-btn"
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                                <i class="ri-add-line ml-2"></i>
                                                إضافة نوع جديد
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Equipment Type Modal -->
    <div id="equipment-type-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modal-title">إضافة نوع معدة جديد</h3>
                    <button type="button" id="close-modal-btn" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <form id="equipment-type-form" method="POST">
                    @csrf
                    <input type="hidden" id="equipment-type-id" name="id">
                    <input type="hidden" id="form-method" name="_method">

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">اسم النوع *</label>
                            <input type="text" id="name" name="name" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="وصف اختياري لنوع المعدة"></textarea>
                            <div id="description-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <label for="is_active" class="mr-2 text-sm text-gray-700">نشط</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 space-x-reverse mt-6">
                        <button type="button" id="cancel-btn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                            إلغاء
                        </button>
                        <button type="submit" id="save-btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="ri-error-warning-line text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">تأكيد الحذف</h3>
                <p class="text-sm text-gray-500 mb-4">
                    هل أنت متأكد من حذف نوع المعدة "<span id="delete-type-name"></span>"؟
                    <br>لا يمكن التراجع عن هذا الإجراء.
                </p>

                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center space-x-3 space-x-reverse">
                        <button type="button" id="cancel-delete-btn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                            حذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('equipment-type-modal');
                const deleteModal = document.getElementById('delete-modal');
                const form = document.getElementById('equipment-type-form');
                const deleteForm = document.getElementById('delete-form');

                // Add button handlers
                const addBtn = document.getElementById('add-equipment-type-btn');
                const addFirstBtn = document.getElementById('add-first-equipment-type-btn');

                if (addBtn) {
                    addBtn.addEventListener('click', openAddModal);
                }

                if (addFirstBtn) {
                    addFirstBtn.addEventListener('click', openAddModal);
                }

                // Edit button handlers
                document.querySelectorAll('.edit-equipment-type-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        openEditModal(this.dataset);
                    });
                });

                // Delete button handlers
                document.querySelectorAll('.delete-equipment-type-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        openDeleteModal(this.dataset.id, this.dataset.name);
                    });
                });

                // Close modal handlers
                const closeBtn = document.getElementById('close-modal-btn');
                const cancelBtn = document.getElementById('cancel-btn');
                const cancelDeleteBtn = document.getElementById('cancel-delete-btn');

                if (closeBtn) closeBtn.addEventListener('click', closeModal);
                if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
                if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModal);

                // Modal background click handlers
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) closeModal();
                    });
                }

                if (deleteModal) {
                    deleteModal.addEventListener('click', function(e) {
                        if (e.target === deleteModal) closeDeleteModal();
                    });
                }

                function openAddModal() {
                    document.getElementById('modal-title').textContent = 'إضافة نوع معدة جديد';
                    document.getElementById('save-btn').textContent = 'إضافة';
                    form.action = '{{ route('settings.equipment-types.store') }}';
                    document.getElementById('form-method').value = '';
                    clearForm();
                    modal.classList.remove('hidden');
                }

                function openEditModal(data) {
                    document.getElementById('modal-title').textContent = 'تعديل نوع المعدة';
                    document.getElementById('save-btn').textContent = 'تحديث';
                    form.action = '{{ route('settings.equipment-types.update', ':id') }}'.replace(':id', data.id);
                    document.getElementById('form-method').value = 'PUT';

                    document.getElementById('equipment-type-id').value = data.id;
                    document.getElementById('name').value = data.name;
                    document.getElementById('description').value = data.description || '';
                    document.getElementById('is_active').checked = data.active === '1';

                    clearErrors();
                    modal.classList.remove('hidden');
                }

                function openDeleteModal(id, name) {
                    document.getElementById('delete-type-name').textContent = name;
                    deleteForm.action = '{{ route('settings.equipment-types.destroy', ':id') }}'.replace(':id', id);
                    deleteModal.classList.remove('hidden');
                }

                function closeModal() {
                    modal.classList.add('hidden');
                    clearForm();
                    clearErrors();
                }

                function closeDeleteModal() {
                    deleteModal.classList.add('hidden');
                }

                function clearForm() {
                    document.getElementById('equipment-type-id').value = '';
                    document.getElementById('name').value = '';
                    document.getElementById('description').value = '';
                    document.getElementById('is_active').checked = true;
                }

                function clearErrors() {
                    document.querySelectorAll('[id$="-error"]').forEach(el => {
                        el.classList.add('hidden');
                        el.textContent = '';
                    });
                    document.querySelectorAll('.border-red-500').forEach(el => {
                        el.classList.remove('border-red-500');
                        el.classList.add('border-gray-300');
                    });
                }

                // Form submission with validation
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        console.log('Form submitted!');
                        console.log('Form action:', form.action);
                        clearErrors();

                        const formData = new FormData(form);
                        console.log('Form data:', Object.fromEntries(formData));

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content');
                        console.log('CSRF token:', csrfToken);

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                console.log('Response status:', response.status);
                                console.log('Response ok:', response.ok);
                                console.log('Response headers:', response.headers.get('content-type'));

                                if (!response.ok) {
                                    // Try to parse as JSON for validation errors
                                    const contentType = response.headers.get('content-type');
                                    if (contentType && contentType.includes('application/json')) {
                                        return response.json().then(errorData => {
                                            console.log('Error response data:', errorData);
                                            if (errorData.errors) {
                                                // Format validation errors for display
                                                let errorMessages = [];
                                                for (const field in errorData.errors) {
                                                    errorMessages.push(...errorData.errors[field]);
                                                }
                                                throw new Error(errorMessages.join('\n'));
                                            } else {
                                                throw new Error(errorData.message ||
                                                    `HTTP ${response.status}: ${response.statusText}`
                                                    );
                                            }
                                        });
                                    } else {
                                        return response.text().then(text => {
                                            console.log('Response text:', text.substring(0, 200));
                                            throw new Error(
                                                `HTTP ${response.status}: ${response.statusText}`
                                                );
                                        });
                                    }
                                }

                                const contentType = response.headers.get('content-type');
                                if (contentType && contentType.includes('application/json')) {
                                    return response.json();
                                } else {
                                    return response.text().then(text => {
                                        console.log('Non-JSON response:', text.substring(0, 200));
                                        throw new Error('Expected JSON response but got: ' +
                                            contentType);
                                    });
                                }
                            })
                            .then(data => {
                                console.log('Response data:', data);
                                if (data.success) {
                                    // Show success message
                                    showAjaxMessage('success', data.message || 'تم إضافة نوع المعدة بنجاح');

                                    // Close modal and reload page after 2 seconds
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 2000);
                                } else if (data.errors) {
                                    displayErrors(data.errors);
                                } else {
                                    console.error('Unexpected response:', data);
                                    showAjaxMessage('error', 'حدث خطأ غير متوقع');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showAjaxMessage('error', error.message);
                            });
                    });
                } else {
                    console.error('Form element not found!');
                }

                function displayErrors(errors) {
                    for (const [field, messages] of Object.entries(errors)) {
                        const errorElement = document.getElementById(field + '-error');
                        const inputElement = document.getElementById(field);

                        if (errorElement && inputElement) {
                            errorElement.textContent = messages[0];
                            errorElement.classList.remove('hidden');
                            inputElement.classList.remove('border-gray-300');
                            inputElement.classList.add('border-red-500');
                        }
                    }
                }

                function showAjaxMessage(type, message) {
                    const messagesContainer = document.getElementById('ajax-messages');
                    const successDiv = document.getElementById('ajax-success');
                    const errorDiv = document.getElementById('ajax-error');
                    const successMessage = document.getElementById('ajax-success-message');
                    const errorMessage = document.getElementById('ajax-error-message');

                    // Hide both messages first
                    successDiv.style.display = 'none';
                    errorDiv.style.display = 'none';

                    // Show appropriate message
                    if (type === 'success') {
                        successMessage.textContent = message;
                        successDiv.style.display = 'block';
                    } else if (type === 'error') {
                        errorMessage.textContent = message;
                        errorDiv.style.display = 'block';
                    }

                    // Show messages container
                    messagesContainer.style.display = 'block';

                    // Scroll to top to show message
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                    // Auto-hide after 5 seconds for errors
                    if (type === 'error') {
                        setTimeout(() => {
                            messagesContainer.style.display = 'none';
                        }, 5000);
                    }
                }
            });
        </script>
    @endpush
@endsection
