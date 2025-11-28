@extends('layouts.app')

@section('title', 'إدارة المواد - الإعدادات')

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
                    <span class="text-blue-600 font-medium">إدارة المواد</span>
                </div>
                <div class="flex items-center space-x-reverse space-x-4">
                    <a href="{{ route('settings.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">إدارة المواد</h1>
                </div>
                <p class="text-gray-600 mt-2">إدارة المواد ووحدات القياس</p>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div
                    class="w-24 h-24 bg-gradient-to-r from-purple-500 via-purple-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-box-3-line text-white text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $materials->total() }}</div>
                    <div class="text-gray-600 mt-2">إجمالي المواد</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $materials->where('status', 'active')->count() }}</div>
                    <div class="text-gray-600 mt-2">مواد فعّالة</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $materials->count() }}</div>
                    <div class="text-gray-600 mt-2">أنواع قياس</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $materials->where('status', '!=', 'active')->count() }}</div>
                    <div class="text-gray-600 mt-2">مواد غير فعّالة</div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Header with Add Button -->
            <div class="flex items-center justify-between mb-6 p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">قائمة المواد</h2>
                <button onclick="openMaterialModal()"
                    class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center">
                    <i class="ri-add-line ml-2"></i>
                    <span>إضافة مادة جديدة</span>
                </button>
            </div>

            <!-- Table -->
            @if ($materials->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الاسم</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">وحدة القياس</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الحالة</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">التاريخ</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($materials as $material)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div
                                                class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-box-3-line text-purple-600"></i>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $material->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $unitColors = [
                                                'م3' => 'green',
                                                'طن' => 'blue',
                                                'لتر' => 'purple',
                                            ];
                                            $color = $unitColors[$material->unit] ?? 'gray';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                            {{ $material->unit }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusClasses = [
                                                'active' => 'bg-green-100 text-green-800',
                                                'inactive' => 'bg-gray-100 text-gray-800',
                                                'out_of_stock' => 'bg-red-100 text-red-800',
                                                'discontinued' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $statusClass = $statusClasses[$material->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                            {{ $material->getStatusTextAttribute() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600 text-sm">
                                        {{ $material->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2 space-x-reverse">
                                            <button
                                                onclick="editMaterial({{ $material->id }}, '{{ $material->name }}', '{{ $material->unit }}')"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button
                                                onclick="deleteMaterial({{ $material->id }}, '{{ $material->name }}', '{{ $material->unit }}')"
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
                @if ($materials->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $materials->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-box-3-line text-purple-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد مواد حالياً</h3>
                    <p class="text-gray-600 mb-6 text-center max-w-md">ابدأ بإضافة مادة جديدة لتنظيم مواد مشروعك</p>
                    <button onclick="openMaterialModal()"
                        class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-3 rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center">
                        <i class="ri-add-line ml-2"></i>
                        <span>إضافة مادة</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Material Modal -->
    <div id="material-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900" id="material-modal-title">إضافة مادة جديدة</h3>
                    <button onclick="closeMaterialModal()"
                        class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <form id="material-form" action="{{ route('settings.materials.store') }}" method="POST"
                    class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label for="material_name" class="block text-sm font-medium text-gray-700 mb-2">اسم المادة</label>
                        <input type="text" id="material_name" name="name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            placeholder="أدخل اسم المادة" required>
                        <div id="name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="material_unit" class="block text-sm font-medium text-gray-700 mb-2">وحدة
                            القياس</label>
                        <select id="material_unit" name="unit"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            required>
                            <option value="">اختر وحدة القياس</option>
                            <option value="م3">م3 (متر مكعب)</option>
                            <option value="طن">طن</option>
                            <option value="لتر">لتر</option>
                        </select>
                        <div id="unit_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeMaterialModal()"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit" id="material-save-btn"
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-200">
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Material Modal -->
    <div id="delete-material-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <i class="ri-delete-bin-line text-red-600 text-xl"></i>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">حذف المادة</h3>
                    <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذه المادة؟ لا يمكن التراجع عن هذا
                        الإجراء.</p>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="ri-box-3-line text-blue-600"></i>
                            </div>
                            <div class="mr-3">
                                <div class="text-sm font-medium text-gray-900" id="delete-material-name">اسم المادة</div>
                                <div class="text-xs text-gray-500" id="delete-material-unit">وحدة القياس</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteMaterialModal()"
                            class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="button" onclick="confirmDeleteMaterial()" id="confirm-delete-btn"
                            class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200">
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Material Modal Functions
        function openMaterialModal() {
            document.getElementById('material-modal-title').textContent = 'إضافة مادة جديدة';
            clearMaterialForm();
            clearMaterialErrors();
            document.getElementById('material-modal').classList.remove('hidden');
        }

        function closeMaterialModal() {
            document.getElementById('material-modal').classList.add('hidden');
            clearMaterialForm();
            clearMaterialErrors();
        }

        function clearMaterialForm() {
            document.getElementById('material_name').value = '';
            document.getElementById('material_unit').value = '';
            document.getElementById('material-form').action = '{{ route('settings.materials.store') }}';
            document.getElementById('material-modal-title').textContent = 'إضافة مادة جديدة';

            const methodField = document.querySelector('input[name="_method"]');
            if (methodField) methodField.remove();
        }

        function clearMaterialErrors() {
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
        }

        function editMaterial(id, name, unit) {
            const modalTitle = document.getElementById('material-modal-title');
            const nameInput = document.getElementById('material_name');
            const unitSelect = document.getElementById('material_unit');
            const form = document.getElementById('material-form');

            modalTitle.textContent = 'تعديل المادة';
            nameInput.value = name;
            unitSelect.value = unit;
            form.action = `/settings/materials/${id}`;

            let methodField = document.querySelector('#material-form input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                form.appendChild(methodField);
            }
            methodField.value = 'PUT';

            clearMaterialErrors();
            document.getElementById('material-modal').classList.remove('hidden');
        }

        function deleteMaterial(id, name = '', unit = '') {
            window.materialToDelete = {
                id,
                name,
                unit
            };
            document.getElementById('delete-material-name').textContent = name || 'مادة غير محددة';
            document.getElementById('delete-material-unit').textContent = unit || 'وحدة غير محددة';
            document.getElementById('delete-material-modal').classList.remove('hidden');
        }

        function closeDeleteMaterialModal() {
            document.getElementById('delete-material-modal').classList.add('hidden');
            window.materialToDelete = null;
        }

        function confirmDeleteMaterial() {
            if (!window.materialToDelete) return;

            const id = window.materialToDelete.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) {
                alert('لم يتم العثور على رمز CSRF');
                return;
            }

            const confirmBtn = document.getElementById('confirm-delete-btn');
            const originalText = confirmBtn.textContent;
            confirmBtn.textContent = 'جاري الحذف...';
            confirmBtn.disabled = true;

            fetch(`/settings/materials/${id}`, {
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
                        closeDeleteMaterialModal();
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
        document.getElementById('material-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeMaterialModal();
        });

        document.getElementById('delete-material-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteMaterialModal();
        });
    </script>
@endsection
