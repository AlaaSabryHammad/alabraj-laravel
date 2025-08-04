@extends('layouts.app')

@section('title', 'أنواع المواقع - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة أنواع المواقع</h1>
                <p class="text-gray-600">إدارة وتصنيف أنواع المواقع في النظام</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="ri-map-pin-line text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
            <div class="flex items-center">
                <i class="ri-check-circle-line text-green-600 ml-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
            <div class="flex items-center">
                <i class="ri-error-warning-line text-red-600 ml-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Settings Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 space-x-reverse px-6" aria-label="Tabs">
                <a href="{{ route('settings.equipment-types') }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="ri-tools-line ml-2"></i>
                    أنواع المعدات
                </a>

                <a href="{{ route('settings.location-types') }}"
                   class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="ri-map-pin-line ml-2"></i>
                    أنواع المواقع
                </a>

                <a href="{{ route('suppliers.index') }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="ri-truck-line ml-2"></i>
                    إدارة الموردين
                </a>

                <a href="{{ route('settings.materials') }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="ri-box-3-line ml-2"></i>
                    إدارة المواد
                </a>

                <!-- Placeholder for future tabs -->
                <a href="#"
                   class="border-transparent text-gray-400 cursor-not-allowed whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="ri-user-settings-line ml-2"></i>
                    إدارة الصلاحيات
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full mr-2">قريباً</span>
                </a>

                <a href="#"
                   class="border-transparent text-gray-400 cursor-not-allowed whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="ri-notification-line ml-2"></i>
                    إعدادات التنبيهات
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full mr-2">قريباً</span>
                </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Add New Location Type Form -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="ri-add-line text-blue-600 ml-2"></i>
                    إضافة نوع موقع جديد
                </h3>

                <form id="addLocationTypeForm" action="{{ route('settings.location-types.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم نوع الموقع <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="مثل: مكتب، مستودع، موقع إنشاء..."
                                   required>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                الوصف
                            </label>
                            <input type="text"
                                   id="description"
                                   name="description"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="وصف مختصر لنوع الموقع">
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                                اللون
                            </label>
                            <div class="flex gap-2">
                                <input type="color"
                                       id="color"
                                       name="color"
                                       value="#3B82F6"
                                       class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                <input type="text"
                                       id="colorText"
                                       value="#3B82F6"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="#3B82F6">
                            </div>
                        </div>

                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                                الأيقونة
                            </label>
                            <select id="icon"
                                    name="icon"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="ri-map-pin-line">📍 موقع عام</option>
                                <option value="ri-building-line">🏢 مبنى</option>
                                <option value="ri-home-office-line">🏠 مكتب</option>
                                <option value="ri-store-line">🏪 مستودع</option>
                                <option value="ri-roadster-line">🚗 موقف</option>
                                <option value="ri-hammer-line">🔨 ورشة</option>
                                <option value="ri-plant-line">🏭 مصنع</option>
                                <option value="ri-community-line">🏘️ مجمع</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   checked
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-gray-600">نشط</span>
                        </label>

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center">
                            <i class="ri-add-line ml-2"></i>
                            إضافة نوع الموقع
                        </button>
                    </div>
                </form>
            </div>

            <!-- Location Types List -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-list-check text-blue-600 ml-2"></i>
                        أنواع المواقع الحالية ({{ $locationTypes->count() }})
                    </h3>
                </div>

                @if($locationTypes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اللون</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد المواقع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($locationTypes as $locationType)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3"
                                                     style="background-color: {{ $locationType->color }}20; color: {{ $locationType->color }}">
                                                    <i class="{{ $locationType->icon }}"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ $locationType->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $locationType->description ?? 'لا يوجد وصف' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded-full mr-2 border border-gray-200"
                                                     style="background-color: {{ $locationType->color }}"></div>
                                                <span class="text-sm text-gray-600">{{ $locationType->color }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $locationType->locations_count }} موقع
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $locationType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $locationType->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2 space-x-reverse">
                                                <button onclick="editLocationType({{ $locationType->id }}, '{{ $locationType->name }}', '{{ $locationType->description }}', '{{ $locationType->color }}', '{{ $locationType->icon }}', {{ $locationType->is_active ? 'true' : 'false' }})"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                @if($locationType->locations_count == 0)
                                                    <button onclick="deleteLocationType({{ $locationType->id }}, '{{ $locationType->name }}')"
                                                            class="text-red-600 hover:text-red-900">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 cursor-not-allowed" title="لا يمكن حذف نوع مرتبط بمواقع">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-map-pin-line text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد أنواع مواقع</h3>
                        <p class="text-gray-500">ابدأ بإضافة نوع موقع جديد لتنظيم المواقع في النظام</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">تعديل نوع الموقع</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="editLocationTypeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم نوع الموقع <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="edit_name"
                               name="name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">
                            الوصف
                        </label>
                        <input type="text"
                               id="edit_description"
                               name="description"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="edit_color" class="block text-sm font-medium text-gray-700 mb-2">
                            اللون
                        </label>
                        <div class="flex gap-2">
                            <input type="color"
                                   id="edit_color"
                                   name="color"
                                   class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                            <input type="text"
                                   id="edit_colorText"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="edit_icon" class="block text-sm font-medium text-gray-700 mb-2">
                            الأيقونة
                        </label>
                        <select id="edit_icon"
                                name="icon"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="ri-map-pin-line">📍 موقع عام</option>
                            <option value="ri-building-line">🏢 مبنى</option>
                            <option value="ri-home-office-line">🏠 مكتب</option>
                            <option value="ri-store-line">🏪 مستودع</option>
                            <option value="ri-roadster-line">🚗 موقف</option>
                            <option value="ri-hammer-line">🔨 ورشة</option>
                            <option value="ri-plant-line">🏭 مصنع</option>
                            <option value="ri-community-line">🏘️ مجمع</option>
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="edit_is_active"
                                   name="is_active"
                                   value="1"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-gray-600">نشط</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button"
                            onclick="closeEditModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Color picker synchronization
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('colorText').value = this.value;
});

document.getElementById('colorText').addEventListener('input', function() {
    if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
        document.getElementById('color').value = this.value;
    }
});

document.getElementById('edit_color').addEventListener('input', function() {
    document.getElementById('edit_colorText').value = this.value;
});

document.getElementById('edit_colorText').addEventListener('input', function() {
    if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
        document.getElementById('edit_color').value = this.value;
    }
});

// Edit location type
function editLocationType(id, name, description, color, icon, isActive) {
    const baseUrl = '{{ url("settings/location-types") }}';
    document.getElementById('editLocationTypeForm').action = `${baseUrl}/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_color').value = color;
    document.getElementById('edit_colorText').value = color;
    document.getElementById('edit_icon').value = icon;
    document.getElementById('edit_is_active').checked = isActive;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Delete location type
function deleteLocationType(id, name) {
    if (confirm(`هل أنت متأكد من حذف نوع الموقع "${name}"؟`)) {
        const baseUrl = '{{ url("settings/location-types") }}';
        fetch(`${baseUrl}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ أثناء الحذف');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء الحذف');
        });
    }
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endsection
