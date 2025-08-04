@extends('layouts.app')

@section('title', 'الإعدادات - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">الإعدادات</h1>
                <p class="text-gray-600">إدارة إعدادات النظام والبيانات الأساسية</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="ri-settings-3-line text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Settings Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Equipment Types Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
             onclick="showSettingsSection('equipment-types')">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-100 to-orange-200 rounded-xl flex items-center justify-center">
                        <i class="ri-tools-line text-orange-600 text-xl"></i>
                    </div>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\EquipmentType::count() }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">أنواع المعدات</h3>
                <p class="text-sm text-gray-500">إدارة أنواع المعدات المختلفة</p>
            </div>
        </div>

        <!-- Location Types Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
             onclick="showSettingsSection('location-types')">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                        <i class="ri-map-pin-line text-green-600 text-xl"></i>
                    </div>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\LocationType::count() }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">أنواع المواقع</h3>
                <p class="text-sm text-gray-500">إدارة أنواع المواقع والمشاريع</p>
            </div>
        </div>

        <!-- Suppliers Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
             onclick="showSettingsSection('suppliers')">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <i class="ri-truck-line text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\Supplier::count() }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">إدارة الموردين</h3>
                <p class="text-sm text-gray-500">إدارة الموردين والمتعاقدين</p>
            </div>
        </div>

        <!-- Materials Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
             onclick="showSettingsSection('materials')">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                        <i class="ri-box-3-line text-purple-600 text-xl"></i>
                    </div>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\Material::count() }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">إدارة المواد</h3>
                <p class="text-sm text-gray-500">إدارة المواد ووحدات القياس</p>
            </div>
        </div>
    </div>

    <!-- Settings Content Area -->
    <div id="settings-content" class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <!-- Default Welcome Message -->
        <div id="welcome-section" class="p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ri-settings-3-line text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">مرحباً بك في الإعدادات</h3>
            <p class="text-gray-500 mb-6">اختر أحد الأقسام أعلاه لبدء الإدارة</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto text-sm">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ \App\Models\EquipmentType::count() }}</div>
                    <div class="text-gray-500">نوع معدة</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\LocationType::count() }}</div>
                    <div class="text-gray-500">نوع موقع</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Supplier::count() }}</div>
                    <div class="text-gray-500">مورد</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Material::count() }}</div>
                    <div class="text-gray-500">مادة</div>
                </div>
            </div>
        </div>

        <!-- Dynamic Content Loading Area -->
        <div id="dynamic-content" class="hidden">
            <!-- Header with back button -->
            <div class="border-b border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <button onclick="showWelcomeSection()" class="ml-4 p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="ri-arrow-right-line text-xl"></i>
                        </button>
                        <div>
                            <h2 id="section-title" class="text-2xl font-bold text-gray-900"></h2>
                            <p id="section-description" class="text-gray-600"></p>
                        </div>
                    </div>
                    <button id="add-new-btn" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
                        <i class="ri-add-line ml-2"></i>
                        <span id="add-btn-text">إضافة جديد</span>
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div id="section-content" class="p-6">
                <!-- Content will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Equipment Type Modal -->
<div id="equipment-type-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900" id="equipment-modal-title">إضافة نوع معدة جديد</h3>
                    <button onclick="closeEquipmentTypeModal()" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="equipment-type-form" method="POST" class="p-6">
                @csrf
                <input type="hidden" id="equipment-type-id" name="id">
                <input type="hidden" id="equipment-form-method" name="_method">

                <div class="space-y-4">
                    <div>
                        <label for="equipment_name" class="block text-sm font-medium text-gray-700 mb-2">اسم النوع *</label>
                        <input type="text" id="equipment_name" name="name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <div id="equipment_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="equipment_description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
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

<!-- Location Type Modal -->
<div id="location-type-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900" id="location-modal-title">إضافة نوع موقع جديد</h3>
                    <button onclick="closeLocationTypeModal()" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="location-type-form" method="POST" class="p-6">
                @csrf
                <input type="hidden" id="location-type-id" name="id">
                <input type="hidden" id="location-form-method" name="_method">

                <div class="space-y-4">
                    <div>
                        <label for="location_name" class="block text-sm font-medium text-gray-700 mb-2">اسم النوع *</label>
                        <input type="text" id="location_name" name="name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <div id="location_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="location_description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea id="location_description" name="description" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                  placeholder="وصف اختياري لنوع الموقع"></textarea>
                        <div id="location_description_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="location_is_active" name="is_active" value="1" checked
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="location_is_active" class="mr-3 text-sm font-medium text-gray-700">نشط</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeLocationTypeModal()"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" id="location-save-btn"
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentSection = null;

// Show settings section
function showSettingsSection(section) {
    currentSection = section;

    // Hide welcome section and show dynamic content
    document.getElementById('welcome-section').classList.add('hidden');
    document.getElementById('dynamic-content').classList.remove('hidden');

    // Update section info
    const sectionConfig = {
        'equipment-types': {
            title: 'أنواع المعدات',
            description: 'إدارة أنواع المعدات المختلفة في النظام',
            addText: 'إضافة نوع معدة',
            loadUrl: '{{ route("settings.equipment-types.content") }}'
        },
        'location-types': {
            title: 'أنواع المواقع',
            description: 'إدارة أنواع المواقع والمشاريع',
            addText: 'إضافة نوع موقع',
            loadUrl: '{{ route("settings.location-types.content") }}'
        },
        'suppliers': {
            title: 'إدارة الموردين',
            description: 'إدارة الموردين والمتعاقدين',
            addText: 'إضافة مورد',
            loadUrl: '{{ route("suppliers.content") }}'
        },
        'materials': {
            title: 'إدارة المواد',
            description: 'إدارة المواد ووحدات القياس',
            addText: 'إضافة مادة',
            loadUrl: '{{ route("settings.materials.content") }}'
        }
    };

    const config = sectionConfig[section];
    document.getElementById('section-title').textContent = config.title;
    document.getElementById('section-description').textContent = config.description;
    document.getElementById('add-btn-text').textContent = config.addText;

    // Configure add button
    const addBtn = document.getElementById('add-new-btn');
    addBtn.onclick = function() {
        if (section === 'equipment-types') {
            openEquipmentTypeModal();
        } else if (section === 'location-types') {
            openLocationTypeModal();
        } else if (section === 'suppliers') {
            // Add supplier functionality
            window.location.href = '{{ route("suppliers.create") }}';
        } else if (section === 'materials') {
            // Add material functionality
            window.location.href = '{{ route("settings.materials.create") }}';
        }
    };

    // Load content
    loadSectionContent(config.loadUrl);
}

// Show welcome section
function showWelcomeSection() {
    document.getElementById('dynamic-content').classList.add('hidden');
    document.getElementById('welcome-section').classList.remove('hidden');
    currentSection = null;
}

// Load section content
function loadSectionContent(url) {
    const contentDiv = document.getElementById('section-content');

    // Show loading
    contentDiv.innerHTML = `
        <div class="text-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-500">جاري التحميل...</p>
        </div>
    `;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => response.text())
    .then(html => {
        contentDiv.innerHTML = html;
    })
    .catch(error => {
        contentDiv.innerHTML = `
            <div class="text-center py-12">
                <i class="ri-error-warning-line text-red-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">حدث خطأ في التحميل</h3>
                <p class="text-gray-500 mb-4">تعذر تحميل المحتوى</p>
                <button onclick="loadSectionContent('${url}')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    إعادة المحاولة
                </button>
            </div>
        `;
    });
}

// Equipment Type Modal Functions
function openEquipmentTypeModal() {
    document.getElementById('equipment-modal-title').textContent = 'إضافة نوع معدة جديد';
    document.getElementById('equipment-save-btn').textContent = 'إضافة';
    document.getElementById('equipment-type-form').action = '{{ route("settings.equipment-types.store") }}';
    document.getElementById('equipment-form-method').value = '';
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
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-300');
    });
}

// Location Type Modal Functions
function openLocationTypeModal() {
    document.getElementById('location-modal-title').textContent = 'إضافة نوع موقع جديد';
    document.getElementById('location-save-btn').textContent = 'إضافة';
    document.getElementById('location-type-form').action = '{{ route("settings.location-types.store") }}';
    document.getElementById('location-form-method').value = '';
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
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-300');
    });
}

// Global edit functions for equipment types
function editEquipmentType(id, name, description, isActive) {
    document.getElementById('equipment-modal-title').textContent = 'تعديل نوع المعدة';
    document.getElementById('equipment-save-btn').textContent = 'تحديث';
    document.getElementById('equipment-type-form').action = '{{ route("settings.equipment-types.update", ":id") }}'.replace(':id', id);
    document.getElementById('equipment-form-method').value = 'PUT';

    document.getElementById('equipment-type-id').value = id;
    document.getElementById('equipment_name').value = name;
    document.getElementById('equipment_description').value = description || '';
    document.getElementById('equipment_is_active').checked = isActive;

    clearEquipmentTypeErrors();
    document.getElementById('equipment-type-modal').classList.remove('hidden');
}

function deleteEquipmentType(id) {
    if (confirm('هل أنت متأكد من حذف هذا النوع؟')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('{{ route("settings.equipment-types.destroy", ":id") }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload current section
                if (currentSection === 'equipment-types') {
                    loadSectionContent('{{ route("settings.equipment-types.content") }}');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

// Global edit functions for location types
function editLocationType(id, name, description, isActive) {
    document.getElementById('location-modal-title').textContent = 'تعديل نوع الموقع';
    document.getElementById('location-save-btn').textContent = 'تحديث';
    document.getElementById('location-type-form').action = '{{ route("settings.location-types.update", ":id") }}'.replace(':id', id);
    document.getElementById('location-form-method').value = 'PUT';

    document.getElementById('location-type-id').value = id;
    document.getElementById('location_name').value = name;
    document.getElementById('location_description').value = description || '';
    document.getElementById('location_is_active').checked = isActive;

    clearLocationTypeErrors();
    document.getElementById('location-type-modal').classList.remove('hidden');
}

function deleteLocationType(id) {
    if (confirm('هل أنت متأكد من حذف هذا النوع؟')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('{{ route("settings.location-types.destroy", ":id") }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload current section
                if (currentSection === 'location-types') {
                    loadSectionContent('{{ route("settings.location-types.content") }}');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

// Form submission handlers
document.getElementById('equipment-type-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEquipmentTypeModal();
            if (currentSection === 'equipment-types') {
                loadSectionContent('{{ route("settings.equipment-types.content") }}');
            }
        } else if (data.errors) {
            displayEquipmentTypeErrors(data.errors);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

document.getElementById('location-type-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeLocationTypeModal();
            if (currentSection === 'location-types') {
                loadSectionContent('{{ route("settings.location-types.content") }}');
            }
        } else if (data.errors) {
            displayLocationTypeErrors(data.errors);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

function displayEquipmentTypeErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const errorElement = document.getElementById(field + '_error');
        const inputElement = document.getElementById('equipment_' + field);

        if (errorElement && inputElement) {
            errorElement.textContent = messages[0];
            errorElement.classList.remove('hidden');
            inputElement.classList.remove('border-gray-300');
            inputElement.classList.add('border-red-500');
        }
    }
}

function displayLocationTypeErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const errorElement = document.getElementById(field + '_error');
        const inputElement = document.getElementById('location_' + field);

        if (errorElement && inputElement) {
            errorElement.textContent = messages[0];
            errorElement.classList.remove('hidden');
            inputElement.classList.remove('border-gray-300');
            inputElement.classList.add('border-red-500');
        }
    }
}
</script>
@endsection
                        class="border-transparent text-gray-400 cursor-not-allowed whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="ri-user-settings-line ml-2"></i>
                    إدارة الصلاحيات
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full mr-2">قريباً</span>
                </button>

                <button type="button" disabled
                        class="border-transparent text-gray-400 cursor-not-allowed whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="ri-notification-line ml-2"></i>
                    إعدادات التنبيهات
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full mr-2">قريباً</span>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Equipment Types Tab -->
            <div id="equipment-types-tab" class="tab-content">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">إدارة أنواع المعدات</h3>
                        <p class="text-gray-600">إضافة وتعديل أنواع المعدات المختلفة في النظام</p>
                    </div>
                    <button type="button" id="add-equipment-type-btn"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="ri-add-line ml-2"></i>
                        إضافة نوع جديد
                    </button>
                </div>
                <div id="equipment-types-content">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-500">جاري التحميل...</p>
                    </div>
                </div>
            </div>

            <!-- Location Types Tab -->
            <div id="location-types-tab" class="tab-content hidden">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">إدارة أنواع المواقع</h3>
                        <p class="text-gray-600">إضافة وتعديل أنواع المواقع المختلفة في النظام</p>
                    </div>
                    <button type="button" id="add-location-type-btn"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="ri-add-line ml-2"></i>
                        إضافة نوع جديد
                    </button>
                </div>
                <div id="location-types-content">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-500">جاري التحميل...</p>
                    </div>
                </div>
            </div>

            <!-- Suppliers Tab -->
            <div id="suppliers-tab" class="tab-content hidden">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">إدارة الموردين</h3>
                        <p class="text-gray-600">إدارة معلومات الموردين وشروط التعامل معهم</p>
                    </div>
                    <a href="{{ route('suppliers.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="ri-add-line ml-2"></i>
                        إضافة مورد جديد
                    </a>
                </div>
                <div id="suppliers-content">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-500">جاري التحميل...</p>
                    </div>
                </div>
            </div>

            <!-- Materials Tab -->
            <div id="materials-tab" class="tab-content hidden">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">إدارة المواد</h3>
                        <p class="text-gray-600">إدارة المواد والمخزون في النظام</p>
                    </div>
                    <a href="{{ route('settings.materials.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="ri-add-line ml-2"></i>
                        إضافة مادة جديدة
                    </a>
                </div>
                <div id="materials-content">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-500">جاري التحميل...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Equipment Type Modal -->
<div id="equipment-type-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
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

<!-- Add/Edit Location Type Modal -->
<div id="location-type-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="location-modal-title">إضافة نوع موقع جديد</h3>
                <button type="button" id="close-location-modal-btn" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="location-type-form" method="POST">
                @csrf
                <input type="hidden" id="location-type-id" name="id">
                <input type="hidden" id="location-form-method" name="_method">

                <div class="space-y-4">
                    <div>
                        <label for="location_name" class="block text-sm font-medium text-gray-700 mb-1">اسم النوع *</label>
                        <input type="text" id="location_name" name="name" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div id="location_name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="location_description" class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                        <textarea id="location_description" name="description" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="وصف اختياري لنوع الموقع"></textarea>
                        <div id="location_description-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                        <div class="flex items-center">
                            <input type="checkbox" id="location_is_active" name="is_active" value="1" checked
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="location_is_active" class="mr-2 text-sm text-gray-700">نشط</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse mt-6">
                    <button type="button" id="location-cancel-btn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                        إلغاء
                    </button>
                    <button type="submit" id="location-save-btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentTab = 'equipment-types';

function showTab(tabName) {
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Show selected tab
    const selectedBtn = event.target.closest('.tab-btn');
    if (selectedBtn) {
        selectedBtn.classList.remove('border-transparent', 'text-gray-500');
        selectedBtn.classList.add('border-blue-500', 'text-blue-600');
    }

    document.getElementById(tabName + '-tab').classList.remove('hidden');
    currentTab = tabName;

    // Load content for the tab
    loadTabContent(tabName);
}

function loadTabContent(tabName) {
    const contentDiv = document.getElementById(tabName + '-content');

    // Show loading
    contentDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-2 text-gray-500">جاري التحميل...</p>
        </div>
    `;

    let url = '';
    switch(tabName) {
        case 'equipment-types':
            url = '{{ route("settings.equipment-types.content") }}';
            break;
        case 'location-types':
            url = '{{ route("settings.location-types.content") }}';
            break;
        case 'suppliers':
            url = '{{ route("suppliers.content") }}';
            break;
        case 'materials':
            url = '{{ route("settings.materials.content") }}';
            break;
    }

    if (url) {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                contentDiv.innerHTML = html;
                // Reinitialize event listeners after content is loaded
                initializeTabEventListeners(tabName);
            })
            .catch(error => {
                contentDiv.innerHTML = `
                    <div class="text-center py-8">
                        <i class="ri-error-warning-line text-red-500 text-3xl mb-2"></i>
                        <p class="text-red-600">حدث خطأ في تحميل المحتوى</p>
                    </div>
                `;
            });
    }
}

function initializeTabEventListeners(tabName) {
    if (tabName === 'equipment-types') {
        initializeEquipmentTypeEvents();
    } else if (tabName === 'location-types') {
        initializeLocationTypeEvents();
    }
}

function initializeEquipmentTypeEvents() {
    // Equipment type modal and form handlers
    const modal = document.getElementById('equipment-type-modal');
    const form = document.getElementById('equipment-type-form');

    // Add button click handler
    const addBtn = document.getElementById('add-equipment-type-btn');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            openEquipmentTypeModal();
        });
    }

    // Close modal handlers
    const closeBtn = document.getElementById('close-modal-btn');
    const cancelBtn = document.getElementById('cancel-btn');

    if (closeBtn) closeBtn.addEventListener('click', closeEquipmentTypeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeEquipmentTypeModal);

    // Modal background click handler
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeEquipmentTypeModal();
        });
    }

    // Form submission handler
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeEquipmentTypeModal();
                    loadTabContent('equipment-types'); // Reload content
                } else if (data.errors) {
                    displayEquipmentTypeErrors(data.errors);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
}

function initializeLocationTypeEvents() {
    // Location type modal and form handlers
    const modal = document.getElementById('location-type-modal');
    const form = document.getElementById('location-type-form');

    // Add button click handler
    const addBtn = document.getElementById('add-location-type-btn');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            openLocationTypeModal();
        });
    }

    // Close modal handlers
    const closeBtn = document.getElementById('close-location-modal-btn');
    const cancelBtn = document.getElementById('location-cancel-btn');

    if (closeBtn) closeBtn.addEventListener('click', closeLocationTypeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeLocationTypeModal);

    // Modal background click handler
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeLocationTypeModal();
        });
    }

    // Form submission handler
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeLocationTypeModal();
                    loadTabContent('location-types'); // Reload content
                } else if (data.errors) {
                    displayLocationTypeErrors(data.errors);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
}

// Equipment Type Modal Functions
function openEquipmentTypeModal() {
    document.getElementById('modal-title').textContent = 'إضافة نوع معدة جديد';
    document.getElementById('save-btn').textContent = 'إضافة';
    document.getElementById('equipment-type-form').action = '{{ route('settings.equipment-types.store') }}';
    document.getElementById('form-method').value = '';
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
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('is_active').checked = true;
}

function clearEquipmentTypeErrors() {
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-300');
    });
}

function displayEquipmentTypeErrors(errors) {
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

// Location Type Modal Functions
function openLocationTypeModal() {
    document.getElementById('location-modal-title').textContent = 'إضافة نوع موقع جديد';
    document.getElementById('location-save-btn').textContent = 'إضافة';
    document.getElementById('location-type-form').action = '{{ route('settings.location-types.store') }}';
    document.getElementById('location-form-method').value = '';
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
    document.querySelectorAll('[id^="location_"][id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-300');
    });
}

function displayLocationTypeErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const errorElement = document.getElementById('location_' + field + '-error');
        const inputElement = document.getElementById('location_' + field);

        if (errorElement && inputElement) {
            errorElement.textContent = messages[0];
            errorElement.classList.remove('hidden');
            inputElement.classList.remove('border-gray-300');
            inputElement.classList.add('border-red-500');
        }
    }
}

// Global functions for edit and delete (called from loaded content)
function editEquipmentType(id, name, description, isActive) {
    document.getElementById('modal-title').textContent = 'تعديل نوع المعدة';
    document.getElementById('save-btn').textContent = 'تحديث';
    document.getElementById('equipment-type-form').action = '{{ route('settings.equipment-types.update', ':id') }}'.replace(':id', id);
    document.getElementById('form-method').value = 'PUT';

    document.getElementById('equipment-type-id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('description').value = description || '';
    document.getElementById('is_active').checked = isActive;

    clearEquipmentTypeErrors();
    document.getElementById('equipment-type-modal').classList.remove('hidden');
}

function deleteEquipmentType(id) {
    if (confirm('هل أنت متأكد من حذف هذا النوع؟')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('{{ route('settings.equipment-types.destroy', ':id') }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTabContent('equipment-types'); // Reload content
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

function editLocationType(id, name, description, isActive) {
    document.getElementById('location-modal-title').textContent = 'تعديل نوع الموقع';
    document.getElementById('location-save-btn').textContent = 'تحديث';
    document.getElementById('location-type-form').action = '{{ route('settings.location-types.update', ':id') }}'.replace(':id', id);
    document.getElementById('location-form-method').value = 'PUT';

    document.getElementById('location-type-id').value = id;
    document.getElementById('location_name').value = name;
    document.getElementById('location_description').value = description || '';
    document.getElementById('location_is_active').checked = isActive;

    clearLocationTypeErrors();
    document.getElementById('location-type-modal').classList.remove('hidden');
}

function deleteLocationType(id) {
    if (confirm('هل أنت متأكد من حذف هذا النوع؟')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('{{ route('settings.location-types.destroy', ':id') }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTabContent('location-types'); // Reload content
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

// Load initial content
document.addEventListener('DOMContentLoaded', function() {
    loadTabContent('equipment-types');
});
</script>
@endsection
