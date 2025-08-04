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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-xl mx-auto text-sm">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ \App\Models\EquipmentType::count() }}</div>
                    <div class="text-gray-500">نوع معدة</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\LocationType::count() }}</div>
                    <div class="text-gray-500">نوع موقع</div>
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

<!-- Material Modal -->
<div id="material-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" id="material-modal-title">إضافة مادة جديدة</h3>
                <button onclick="closeMaterialModal()" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="material-form" action="{{ route('settings.materials.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="material_name" class="block text-sm font-medium text-gray-700 mb-2">اسم المادة</label>
                    <input type="text"
                           id="material_name"
                           name="name"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="أدخل اسم المادة"
                           required>
                    <div id="name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div>
                    <label for="material_unit" class="block text-sm font-medium text-gray-700 mb-2">وحدة القياس</label>
                    <select id="material_unit"
                            name="unit"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
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
                <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذا النوع؟ لا يمكن التراجع عن هذا الإجراء.</p>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                            <i class="ri-tools-line text-orange-600"></i>
                        </div>
                        <div class="mr-3">
                            <div class="text-sm font-medium text-gray-900" id="delete-equipment-type-name">اسم نوع المعدة</div>
                            <div class="text-xs text-gray-500" id="delete-equipment-type-description">الوصف</div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteEquipmentTypeModal()"
                            class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                        إلغاء
                    </button>
                    <button type="button" onclick="confirmDeleteEquipmentType()" id="confirm-delete-equipment-type-btn"
                            class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200">
                        حذف
                    </button>
                </div>
            </div>
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
                <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذا النوع؟ لا يمكن التراجع عن هذا الإجراء.</p>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="ri-map-pin-line text-green-600"></i>
                        </div>
                        <div class="mr-3">
                            <div class="text-sm font-medium text-gray-900" id="delete-location-type-name">اسم نوع الموقع</div>
                            <div class="text-xs text-gray-500" id="delete-location-type-description">الوصف</div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteLocationTypeModal()"
                            class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                        إلغاء
                    </button>
                    <button type="button" onclick="confirmDeleteLocationType()" id="confirm-delete-location-type-btn"
                            class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200">
                        حذف
                    </button>
                </div>
            </div>
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
                <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذه المادة؟ لا يمكن التراجع عن هذا الإجراء.</p>

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
        } else if (section === 'materials') {
            openMaterialModal();
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

        // Re-initialize JavaScript for dynamically loaded content
        setTimeout(() => {
            // Trigger DOMContentLoaded event for newly loaded content
            const scripts = contentDiv.querySelectorAll('script');
            scripts.forEach(script => {
                if (script.textContent.trim()) {
                    try {
                        eval(script.textContent);
                    } catch (e) {
                        console.error('Error executing script:', e);
                    }
                }
            });
        }, 100);
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

function deleteEquipmentType(id, name = '', description = '') {
    console.log('Delete equipment type called with:', { id, name, description });

    // Store equipment type info for deletion
    window.equipmentTypeToDelete = { id, name, description };

    // Update modal content
    document.getElementById('delete-equipment-type-name').textContent = name || 'نوع معدة غير محدد';
    document.getElementById('delete-equipment-type-description').textContent = description || 'بدون وصف';

    // Show delete modal
    document.getElementById('delete-equipment-type-modal').classList.remove('hidden');
}

function closeDeleteEquipmentTypeModal() {
    document.getElementById('delete-equipment-type-modal').classList.add('hidden');
    window.equipmentTypeToDelete = null;
}

function confirmDeleteEquipmentType() {
    console.log('Confirm delete equipment type called, equipmentTypeToDelete:', window.equipmentTypeToDelete);

    if (!window.equipmentTypeToDelete) {
        console.log('No equipment type to delete found');
        return;
    }

    const id = window.equipmentTypeToDelete.id;
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    console.log('Equipment Type ID:', id);
    console.log('CSRF Token:', csrfToken ? csrfToken.getAttribute('content') : 'NOT FOUND');

    if (csrfToken) {
        // Show loading state
        const confirmBtn = document.getElementById('confirm-delete-equipment-type-btn');
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = 'جاري الحذف...';
        confirmBtn.disabled = true;

        const deleteUrl = '/settings/equipment-types/' + id;
        console.log('Delete URL:', deleteUrl);

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Delete equipment type response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete equipment type response data:', data);
            if (data.success) {
                console.log('Delete equipment type successful, closing modal and reloading...');
                closeDeleteEquipmentTypeModal();
                // Reload equipment types content
                if (currentSection === 'equipment-types') {
                    loadSectionContent('{{ route("settings.equipment-types.content") }}');
                }
            } else {
                alert('حدث خطأ أثناء حذف نوع المعدة: ' + (data.message || 'خطأ غير معروف'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = false;
        });
    } else {
        alert('لم يتم العثور على رمز CSRF');
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

function deleteLocationType(id, name = '', description = '') {
    console.log('Delete location type called with:', { id, name, description });

    // Store location type info for deletion
    window.locationTypeToDelete = { id, name, description };

    // Update modal content
    document.getElementById('delete-location-type-name').textContent = name || 'نوع موقع غير محدد';
    document.getElementById('delete-location-type-description').textContent = description || 'بدون وصف';

    // Show delete modal
    document.getElementById('delete-location-type-modal').classList.remove('hidden');
}

function closeDeleteLocationTypeModal() {
    document.getElementById('delete-location-type-modal').classList.add('hidden');
    window.locationTypeToDelete = null;
}

function confirmDeleteLocationType() {
    console.log('Confirm delete location type called, locationTypeToDelete:', window.locationTypeToDelete);

    if (!window.locationTypeToDelete) {
        console.log('No location type to delete found');
        return;
    }

    const id = window.locationTypeToDelete.id;
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    console.log('Location Type ID:', id);
    console.log('CSRF Token:', csrfToken ? csrfToken.getAttribute('content') : 'NOT FOUND');

    if (csrfToken) {
        // Show loading state
        const confirmBtn = document.getElementById('confirm-delete-location-type-btn');
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = 'جاري الحذف...';
        confirmBtn.disabled = true;

        const deleteUrl = '/settings/location-types/' + id;
        console.log('Delete URL:', deleteUrl);

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Delete location type response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete location type response data:', data);
            if (data.success) {
                console.log('Delete location type successful, closing modal and reloading...');
                closeDeleteLocationTypeModal();
                // Reload location types content
                if (currentSection === 'location-types') {
                    loadSectionContent('{{ route("settings.location-types.content") }}');
                }
            } else {
                alert('حدث خطأ أثناء حذف نوع الموقع: ' + (data.message || 'خطأ غير معروف'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = false;
        });
    } else {
        alert('لم يتم العثور على رمز CSRF');
    }
}

// Form submission handlers
document.addEventListener('DOMContentLoaded', function() {
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

    document.getElementById('material-form').addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Material form submitted');
        console.log('Form action:', this.action);
        console.log('Form method:', this.method);

        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Log form data
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }

        // Clear previous errors
        clearMaterialErrors();

        // Show loading state
        const submitBtn = document.getElementById('material-save-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'جاري الحفظ...';
        submitBtn.disabled = true;

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Material form response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Material form response data:', data);
            if (data.success) {
                console.log('Material operation successful');
                closeMaterialModal();
                // Use the dedicated reload function
                reloadMaterialsContent();
            } else if (data.errors) {
                console.log('Material form validation errors:', data.errors);
                displayMaterialErrors(data.errors);
            } else {
                console.log('Unknown response format:', data);
                alert('حدث خطأ غير معروف');
            }
        })
        .catch(error => {
            console.error('Material form error:', error);
            alert('حدث خطأ في الاتصال: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
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

    // Reset form to add mode
    document.getElementById('material-form').action = '{{ route("settings.materials.store") }}';
    document.getElementById('material-modal-title').textContent = 'إضافة مادة جديدة';

    // Remove method field if exists
    const methodField = document.querySelector('input[name="_method"]');
    if (methodField) {
        methodField.remove();
    }
}

function clearMaterialErrors() {
    document.querySelectorAll('[id$="_error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-300');
    });
}

function displayMaterialErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const errorElement = document.getElementById(field + '_error');
        const inputElement = document.getElementById('material_' + field);

        if (errorElement && inputElement) {
            errorElement.textContent = messages[0];
            errorElement.classList.remove('hidden');
            inputElement.classList.remove('border-gray-300');
            inputElement.classList.add('border-red-500');
        }
    }
}

// Material Management Functions
function editMaterial(id, name, unit) {
    console.log('Edit material called with:', { id, name, unit });

    // Set modal title to edit mode
    const modalTitle = document.getElementById('material-modal-title');
    const nameInput = document.getElementById('material_name');
    const unitSelect = document.getElementById('material_unit');
    const form = document.getElementById('material-form');

    if (modalTitle && nameInput && unitSelect && form) {
        modalTitle.textContent = 'تعديل المادة';

        // Fill form with existing data
        nameInput.value = name;
        unitSelect.value = unit;

        // Update form action to edit route
        form.action = '/settings/materials/' + id;
        console.log('Form action set to:', form.action);

        // Add method field for PUT request
        let methodField = document.querySelector('#material-form input[name="_method"]');
        if (!methodField) {
            methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            form.appendChild(methodField);
            console.log('Created new method field');
        }
        methodField.value = 'PUT';
        console.log('Method field set to:', methodField.value);

        // Clear any previous errors
        clearMaterialErrors();

        // Show modal
        const modal = document.getElementById('material-modal');
        if (modal) {
            modal.classList.remove('hidden');
            console.log('Modal opened for editing');
        }
    } else {
        console.error('Some form elements not found:', {
            modalTitle: !!modalTitle,
            nameInput: !!nameInput,
            unitSelect: !!unitSelect,
            form: !!form
        });
    }
}

function deleteMaterial(id, name = '', unit = '') {
    console.log('Delete material called with:', { id, name, unit });

    // Store material info for deletion
    window.materialToDelete = { id, name, unit };

    // Update modal content
    document.getElementById('delete-material-name').textContent = name || 'مادة غير محددة';
    document.getElementById('delete-material-unit').textContent = unit || 'وحدة غير محددة';

    // Show delete modal
    document.getElementById('delete-material-modal').classList.remove('hidden');
}function closeDeleteMaterialModal() {
    document.getElementById('delete-material-modal').classList.add('hidden');
    window.materialToDelete = null;
}

function confirmDeleteMaterial() {
    console.log('Confirm delete called, materialToDelete:', window.materialToDelete);

    if (!window.materialToDelete) {
        console.log('No material to delete found');
        return;
    }

    const id = window.materialToDelete.id;
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    console.log('Material ID:', id);
    console.log('CSRF Token:', csrfToken ? csrfToken.getAttribute('content') : 'NOT FOUND');

    if (csrfToken) {
        // Show loading state
        const confirmBtn = document.getElementById('confirm-delete-btn');
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = 'جاري الحذف...';
        confirmBtn.disabled = true;

        const deleteUrl = '/settings/materials/' + id;
        console.log('Delete URL:', deleteUrl);

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Delete response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete response data:', data);
            if (data.success) {
                console.log('Delete successful, closing modal and reloading...');
                closeDeleteMaterialModal();
                // Reload materials content
                reloadMaterialsContent();
            } else {
                alert('حدث خطأ أثناء حذف المادة: ' + (data.message || 'خطأ غير معروف'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = false;
        });
    } else {
        alert('لم يتم العثور على رمز CSRF');
    }
}

// Function to reload materials content with current filters
function reloadMaterialsContent() {
    console.log('Reloading materials content, current section:', currentSection);

    // Check if materials section is currently active
    if (currentSection === 'materials') {
        // Materials tab is currently active, reload it
        console.log('Materials section is active, reloading...');
        loadSectionContent('{{ route("settings.materials.content") }}');
    } else {
        console.log('Materials section is not active, checking for content...');
        // Check if we have materials content loaded
        const sectionContent = document.getElementById('section-content');
        if (sectionContent && sectionContent.innerHTML.includes('materials-filter-form')) {
            console.log('Found materials content, reloading...');
            loadSectionContent('{{ route("settings.materials.content") }}');
        }
    }
}

// Global pagination handler to prevent new page navigation
document.addEventListener('click', function(e) {
    const paginationLink = e.target.closest('.pagination a');
    if (paginationLink && paginationLink.href) {
        // Check if we're inside a section content that should be loaded via AJAX
        const sectionContent = e.target.closest('#section-content');
        if (sectionContent) {
            e.preventDefault();
            e.stopPropagation();

            let url = paginationLink.href;

            // Convert different pagination URLs to their content equivalents
            if (url.includes('/settings/materials?')) {
                url = url.replace('/settings/materials?', '/settings/materials/content?');
            } else if (url.includes('/settings/materials')) {
                url = url.replace('/settings/materials', '/settings/materials/content');
            }

            console.log('Global pagination handler:', url);
            loadSectionContent(url);
        }
    }

    // Close delete modal when clicking outside
    if (e.target.id === 'delete-material-modal') {
        closeDeleteMaterialModal();
    }

    // Close delete location type modal when clicking outside
    if (e.target.id === 'delete-location-type-modal') {
        closeDeleteLocationTypeModal();
    }

    // Close delete equipment type modal when clicking outside
    if (e.target.id === 'delete-equipment-type-modal') {
        closeDeleteEquipmentTypeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const deleteModal = document.getElementById('delete-material-modal');
        if (deleteModal && !deleteModal.classList.contains('hidden')) {
            closeDeleteMaterialModal();
        }

        const deleteLocationTypeModal = document.getElementById('delete-location-type-modal');
        if (deleteLocationTypeModal && !deleteLocationTypeModal.classList.contains('hidden')) {
            closeDeleteLocationTypeModal();
        }

        const deleteEquipmentTypeModal = document.getElementById('delete-equipment-type-modal');
        if (deleteEquipmentTypeModal && !deleteEquipmentTypeModal.classList.contains('hidden')) {
            closeDeleteEquipmentTypeModal();
        }
    }
});
</script>
@endsection
