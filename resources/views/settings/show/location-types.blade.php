@extends('layouts.app')

@section('title', 'أنواع المواقع - الإعدادات')

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
                    <span class="text-blue-600 font-medium">أنواع المواقع</span>
                </div>
                <div class="flex items-center space-x-reverse space-x-4">
                    <a href="{{ route('settings.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">أنواع المواقع</h1>
                </div>
                <p class="text-gray-600 mt-2">إدارة شاملة لأنواع المواقع والمشاريع</p>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div
                    class="w-24 h-24 bg-gradient-to-r from-green-500 via-green-600 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-map-pin-line text-white text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $locationTypes->total() }}</div>
                    <div class="text-gray-600 mt-2">إجمالي الأنواع</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $locationTypes->where('is_active', true)->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">أنواع فعّالة</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $locationTypes->where('is_active', false)->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">أنواع غير فعّالة</div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Header with Add Button -->
            <div class="flex items-center justify-between mb-6 p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">قائمة أنواع المواقع</h2>
                <button onclick="openLocationTypeModal()"
                    class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-xl font-medium hover:from-green-700 hover:to-green-800 transition-all duration-200 flex items-center">
                    <i class="ri-add-line ml-2"></i>
                    <span>إضافة نوع موقع جديد</span>
                </button>
            </div>

            <!-- Table -->
            @if ($locationTypes->count() > 0)
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
                            @foreach ($locationTypes as $type)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-map-pin-line text-green-600"></i>
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
                                                onclick="editLocationType({{ $type->id }}, '{{ $type->name }}', '{{ $type->description }}', {{ $type->is_active ? 'true' : 'false' }})"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button
                                                onclick="deleteLocationType({{ $type->id }}, '{{ $type->name }}', '{{ $type->description }}')"
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
                @if ($locationTypes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $locationTypes->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-map-pin-line text-green-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد أنواع مواقع حالياً</h3>
                    <p class="text-gray-600 mb-6 text-center max-w-md">ابدأ بإضافة نوع موقع جديد لتنظيم مواقعك</p>
                    <button onclick="openLocationTypeModal()"
                        class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-xl font-medium hover:from-green-700 hover:to-green-800 transition-all duration-200 flex items-center">
                        <i class="ri-add-line ml-2"></i>
                        <span>إضافة نوع موقع</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Location Type Modal -->
    <div id="location-type-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900" id="location-modal-title">إضافة نوع موقع جديد</h3>
                        <button onclick="closeLocationTypeModal()"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="location-type-form" action="{{ route('settings.location-types.store') }}" method="POST"
                    class="p-6">
                    @csrf
                    <input type="hidden" id="location-type-id" name="id">
                    <input type="hidden" id="location-form-method" name="_method">

                    <div class="space-y-4">
                        <div>
                            <label for="location_name" class="block text-sm font-medium text-gray-700 mb-2">اسم النوع
                                *</label>
                            <input type="text" id="location_name" name="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                            <div id="location_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="location_description"
                                class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                            <textarea id="location_description" name="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                placeholder="وصف اختياري لنوع الموقع"></textarea>
                            <div id="location_description_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="location_is_active" name="is_active" value="1" checked
                                class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                            <label for="location_is_active" class="mr-3 text-sm font-medium text-gray-700">نشط</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 space-x-reverse mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeLocationTypeModal()"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit" id="location-save-btn"
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-200">
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Location Type Modal -->
    <div id="delete-location-type-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <i class="ri-delete-bin-line text-red-600 text-xl"></i>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">حذف نوع الموقع</h3>
                    <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذا النوع؟ لا يمكن التراجع عن هذا
                        الإجراء.</p>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="ri-map-pin-line text-green-600"></i>
                            </div>
                            <div class="mr-3">
                                <div class="text-sm font-medium text-gray-900" id="delete-location-type-name">اسم نوع
                                    الموقع</div>
                                <div class="text-xs text-gray-500" id="delete-location-type-description">الوصف</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteLocationTypeModal()"
                            class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="button" onclick="confirmDeleteLocationType()"
                            id="confirm-delete-location-type-btn"
                            class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200">
                            حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Location Type Modal Functions
        function openLocationTypeModal() {
            const modalTitle = document.getElementById('location-modal-title');
            const saveBtn = document.getElementById('location-save-btn');
            const form = document.getElementById('location-type-form');
            const methodField = document.getElementById('location-form-method');

            modalTitle.textContent = 'إضافة نوع موقع جديد';
            saveBtn.textContent = 'إضافة';
            form.action = '{{ route('settings.location-types.store') }}';
            methodField.value = '';
            clearLocationTypeForm();
            document.getElementById('location-type-modal').classList.remove('hidden');
        }

        function closeLocationTypeModal() {
            document.getElementById('location-type-modal').classList.add('hidden');
            clearLocationTypeForm();
            clearLocationTypeErrors();
        }

        function clearLocationTypeForm() {
            document.getElementById('location-type-id').value = '';
            document.getElementById('location_name').value = '';
            document.getElementById('location_description').value = '';
            document.getElementById('location_is_active').checked = true;
        }

        function clearLocationTypeErrors() {
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
        }

        function editLocationType(id, name, description, isActive) {
            document.getElementById('location-modal-title').textContent = 'تعديل نوع الموقع';
            document.getElementById('location-save-btn').textContent = 'تحديث';
            document.getElementById('location-type-form').action = '{{ route('settings.location-types.update', ':id') }}'
                .replace(':id', id);
            document.getElementById('location-form-method').value = 'PUT';

            document.getElementById('location-type-id').value = id;
            document.getElementById('location_name').value = name;
            document.getElementById('location_description').value = description || '';
            document.getElementById('location_is_active').checked = isActive;

            clearLocationTypeErrors();
            document.getElementById('location-type-modal').classList.remove('hidden');
        }

        function deleteLocationType(id, name = '', description = '') {
            window.locationTypeToDelete = {
                id,
                name,
                description
            };
            document.getElementById('delete-location-type-name').textContent = name || 'نوع موقع غير محدد';
            document.getElementById('delete-location-type-description').textContent = description || 'بدون وصف';
            document.getElementById('delete-location-type-modal').classList.remove('hidden');
        }

        function closeDeleteLocationTypeModal() {
            document.getElementById('delete-location-type-modal').classList.add('hidden');
            window.locationTypeToDelete = null;
        }

        function confirmDeleteLocationType() {
            if (!window.locationTypeToDelete) return;

            const id = window.locationTypeToDelete.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) {
                alert('لم يتم العثور على رمز CSRF');
                return;
            }

            const confirmBtn = document.getElementById('confirm-delete-location-type-btn');
            const originalText = confirmBtn.textContent;
            confirmBtn.textContent = 'جاري الحذف...';
            confirmBtn.disabled = true;

            fetch(`/settings/location-types/${id}`, {
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
                        closeDeleteLocationTypeModal();
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
        document.getElementById('location-type-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeLocationTypeModal();
        });

        document.getElementById('delete-location-type-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteLocationTypeModal();
        });
    </script>
@endsection
