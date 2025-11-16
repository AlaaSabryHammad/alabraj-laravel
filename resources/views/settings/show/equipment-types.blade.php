@extends('layouts.app')

@section('title', 'أنواع المعدات - الإعدادات')

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
                    <span class="text-blue-600 font-medium">أنواع المعدات</span>
                </div>
                <div class="flex items-center space-x-reverse space-x-4">
                    <a href="{{ route('settings.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">أنواع المعدات</h1>
                </div>
                <p class="text-gray-600 mt-2">إدارة شاملة لأنواع المعدات المختلفة في النظام</p>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div
                    class="w-24 h-24 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-tools-line text-white text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $equipmentTypes->total() }}</div>
                    <div class="text-gray-600 mt-2">إجمالي الأنواع</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $equipmentTypes->where('is_active', true)->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">أنواع فعّالة</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $equipmentTypes->where('is_active', false)->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">أنواع غير فعّالة</div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Header with Add Button -->
            <div class="flex items-center justify-between mb-6 p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">قائمة أنواع المعدات</h2>
                <button onclick="openEquipmentTypeModal()"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
                    <i class="ri-add-line ml-2"></i>
                    <span>إضافة نوع معدة جديد</span>
                </button>
            </div>

            <!-- Table -->
            @if ($equipmentTypes->count() > 0)
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
                            @foreach ($equipmentTypes as $type)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-tools-line text-blue-600"></i>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $type->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="text-sm">{{ Str::limit($type->description, 50) ?? 'بدون وصف' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($type->is_active)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
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
                                        {{ $type->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2 space-x-reverse">
                                            <button
                                                onclick="editEquipmentType({{ $type->id }}, '{{ $type->name }}', '{{ $type->description }}', {{ $type->is_active ? 'true' : 'false' }})"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button
                                                onclick="deleteEquipmentType({{ $type->id }}, '{{ $type->name }}', '{{ $type->description }}')"
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
                @if ($equipmentTypes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $equipmentTypes->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-tools-line text-blue-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد أنواع معدات حالياً</h3>
                    <p class="text-gray-600 mb-6 text-center max-w-md">ابدأ بإضافة نوع معدة جديد لتنظيم معداتك</p>
                    <button onclick="openEquipmentTypeModal()"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
                        <i class="ri-add-line ml-2"></i>
                        <span>إضافة نوع معدة</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Equipment Type Modal -->
    <div id="equipment-type-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900" id="equipment-modal-title">إضافة نوع معدة جديد</h3>
                        <button onclick="closeEquipmentTypeModal()"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="equipment-type-form" action="{{ route('settings.equipment-types.store') }}" method="POST"
                    class="p-6">
                    @csrf
                    <input type="hidden" id="equipment-type-id" name="id">
                    <input type="hidden" id="equipment-form-method" name="_method">

                    <div class="space-y-4">
                        <div>
                            <label for="equipment_name" class="block text-sm font-medium text-gray-700 mb-2">اسم النوع
                                *</label>
                            <input type="text" id="equipment_name" name="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <div id="equipment_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="equipment_description"
                                class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                            <textarea id="equipment_description" name="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                placeholder="وصف اختياري لنوع المعدة"></textarea>
                            <div id="equipment_description_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="equipment_is_active" name="is_active" value="1" checked
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="equipment_is_active" class="mr-3 text-sm font-medium text-gray-700">نشط</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 space-x-reverse mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeEquipmentTypeModal()"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit" id="equipment-save-btn"
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Equipment Type Modal -->
    <div id="delete-equipment-type-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <i class="ri-delete-bin-line text-red-600 text-xl"></i>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">حذف نوع المعدة</h3>
                    <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذا النوع؟ لا يمكن التراجع عن هذا
                        الإجراء.</p>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                                <i class="ri-tools-line text-orange-600"></i>
                            </div>
                            <div class="mr-3">
                                <div class="text-sm font-medium text-gray-900" id="delete-equipment-type-name">اسم نوع
                                    المعدة</div>
                                <div class="text-xs text-gray-500" id="delete-equipment-type-description">الوصف</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteEquipmentTypeModal()"
                            class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="button" onclick="confirmDeleteEquipmentType()"
                            id="confirm-delete-equipment-type-btn"
                            class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200">
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Equipment Type Modal Functions
        function openEquipmentTypeModal() {
            const modalTitle = document.getElementById('equipment-modal-title');
            const saveBtn = document.getElementById('equipment-save-btn');
            const form = document.getElementById('equipment-type-form');
            const methodField = document.getElementById('equipment-form-method');

            modalTitle.textContent = 'إضافة نوع معدة جديد';
            saveBtn.textContent = 'إضافة';
            form.action = '{{ route('settings.equipment-types.store') }}';
            methodField.value = '';
            clearEquipmentTypeForm();
            document.getElementById('equipment-type-modal').classList.remove('hidden');
        }

        function closeEquipmentTypeModal() {
            document.getElementById('equipment-type-modal').classList.add('hidden');
            clearEquipmentTypeForm();
            clearEquipmentTypeErrors();
        }

        function clearEquipmentTypeForm() {
            document.getElementById('equipment-type-id').value = '';
            document.getElementById('equipment_name').value = '';
            document.getElementById('equipment_description').value = '';
            document.getElementById('equipment_is_active').checked = true;
        }

        function clearEquipmentTypeErrors() {
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
        }

        function editEquipmentType(id, name, description, isActive) {
            document.getElementById('equipment-modal-title').textContent = 'تعديل نوع المعدة';
            document.getElementById('equipment-save-btn').textContent = 'تحديث';
            document.getElementById('equipment-type-form').action = '{{ route('settings.equipment-types.update', ':id') }}'
                .replace(':id', id);
            document.getElementById('equipment-form-method').value = 'PUT';

            document.getElementById('equipment-type-id').value = id;
            document.getElementById('equipment_name').value = name;
            document.getElementById('equipment_description').value = description || '';
            document.getElementById('equipment_is_active').checked = isActive;

            clearEquipmentTypeErrors();
            document.getElementById('equipment-type-modal').classList.remove('hidden');
        }

        function deleteEquipmentType(id, name = '', description = '') {
            window.equipmentTypeToDelete = {
                id,
                name,
                description
            };
            document.getElementById('delete-equipment-type-name').textContent = name || 'نوع معدة غير محدد';
            document.getElementById('delete-equipment-type-description').textContent = description || 'بدون وصف';
            document.getElementById('delete-equipment-type-modal').classList.remove('hidden');
        }

        function closeDeleteEquipmentTypeModal() {
            document.getElementById('delete-equipment-type-modal').classList.add('hidden');
            window.equipmentTypeToDelete = null;
        }

        function confirmDeleteEquipmentType() {
            if (!window.equipmentTypeToDelete) return;

            const id = window.equipmentTypeToDelete.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) {
                alert('لم يتم العثور على رمز CSRF');
                return;
            }

            const confirmBtn = document.getElementById('confirm-delete-equipment-type-btn');
            const originalText = confirmBtn.textContent;
            confirmBtn.textContent = 'جاري الحذف...';
            confirmBtn.disabled = true;

            fetch(`/settings/equipment-types/${id}`, {
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
                        closeDeleteEquipmentTypeModal();
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

        // Handle modal close on background click
        document.getElementById('equipment-type-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeEquipmentTypeModal();
        });

        document.getElementById('delete-equipment-type-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteEquipmentTypeModal();
        });
    </script>
@endsection
