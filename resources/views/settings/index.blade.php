@extends('layouts.app')

@section('title', 'الإعدادات - شركة الأبراج للمقاولات')

@section('content')
    <div class="space-y-6">
        <!-- Header with Breadcrumb -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-2 space-x-reverse mb-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="ri-home-line"></i>
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-blue-600 font-medium">الإعدادات</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">الإعدادات والتكوينات</h1>
                <p class="text-gray-600">إدارة شاملة لإعدادات النظام والبيانات الأساسية</p>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div
                    class="w-24 h-24 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-settings-3-line text-white text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Settings Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Revenue Entities Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                onclick="window.location.href='{{ route('settings.revenue-entities.show') }}'">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center">
                            <i class="ri-building-4-line text-emerald-600 text-xl"></i>
                        </div>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\RevenueEntity::count() }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">جهات الإيرادات</h3>
                    <p class="text-sm text-gray-500">إدارة جهات مصدر الإيرادات</p>
                </div>
            </div>
            <!-- Equipment Types Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                onclick="window.location.href='{{ route('settings.equipment-types.show') }}'">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-orange-100 to-orange-200 rounded-xl flex items-center justify-center">
                            <i class="ri-tools-line text-orange-600 text-xl"></i>
                        </div>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\EquipmentType::count() }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">أنواع المعدات</h3>
                    <p class="text-sm text-gray-500">إدارة أنواع المعدات المختلفة</p>
                </div>
            </div>

            <!-- Location Types Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                onclick="window.location.href='{{ route('settings.location-types.show') }}'">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                            <i class="ri-map-pin-line text-green-600 text-xl"></i>
                        </div>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\LocationType::count() }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">أنواع المواقع</h3>
                    <p class="text-sm text-gray-500">إدارة أنواع المواقع والمشاريع</p>
                </div>
            </div>

            <!-- Materials Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                onclick="window.location.href='{{ route('settings.materials.show') }}'">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                            <i class="ri-box-3-line text-purple-600 text-xl"></i>
                        </div>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\Material::count() }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">إدارة المواد</h3>
                    <p class="text-sm text-gray-500">إدارة المواد ووحدات القياس</p>
                </div>
            </div>

            <!-- Roles & Permissions Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                onclick="window.location.href='{{ route('settings.roles-permissions') }}'">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                            <i class="ri-shield-user-line text-blue-600 text-xl"></i>
                        </div>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\Role::count() }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">الأدوار والصلاحيات</h3>
                    <p class="text-sm text-gray-500">إدارة أدوار المستخدمين والصلاحيات</p>
                </div>
            </div>

            <!-- Suppliers Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                onclick="window.location.href='{{ route('suppliers.show-page') }}'">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center">
                            <i class="ri-truck-line text-cyan-600 text-xl"></i>
                        </div>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\Supplier::count() }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">موردي قطع الغيار</h3>
                    <p class="text-sm text-gray-500">إدارة الموردين والشركات</p>
                </div>
            </div>

            <!-- Expense Categories Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                onclick="window.location.href='{{ route('settings.expense-categories.show') }}'">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-red-100 to-red-200 rounded-xl flex items-center justify-center">
                            <i class="ri-money-dollar-circle-line text-red-600 text-xl"></i>
                        </div>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\ExpenseCategory::count() }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">فئات المصروفات</h3>
                    <p class="text-sm text-gray-500">إدارة فئات وأنواع المصروفات</p>
                </div>
            </div>

            <!-- Revenue Types Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                onclick="window.location.href='{{ route('settings.revenue-types.show') }}'">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center">
                            <i class="ri-hand-coin-line text-emerald-600 text-xl"></i>
                        </div>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\RevenueType::count() }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">أنواع الإيرادات</h3>
                    <p class="text-sm text-gray-500">إدارة أنواع ومصادر الإيرادات</p>
                </div>
            </div>

            <!-- Expense Entities Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                onclick="window.location.href='{{ route('settings.expense-entities.show') }}'">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                            <i class="ri-building-line text-blue-600 text-xl"></i>
                        </div>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ \App\Models\ExpenseEntity::count() }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">جهات الصرف</h3>
                    <p class="text-sm text-gray-500">إدارة الموردين والمقاولين والجهات</p>
                </div>
            </div>
        </div>

        <!-- Settings Content Area -->
        <div id="settings-content" class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Default Welcome Message -->
            <div id="welcome-section" class="p-8 text-center">
                <div
                    class="w-20 h-20 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ri-settings-3-line text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">مرحباً بك في الإعدادات</h3>
                <p class="text-gray-500 mb-6">اختر أحد الأقسام أعلاه لبدء الإدارة</p>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 max-w-6xl mx-auto text-sm">
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
                    <div class="text-center">
                        <div class="text-2xl font-bold text-cyan-600">{{ \App\Models\Supplier::count() }}</div>
                        <div class="text-gray-500">مورد</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ \App\Models\ExpenseCategory::count() }}</div>
                        <div class="text-gray-500">فئة مصروف</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-emerald-600">{{ \App\Models\RevenueType::count() }}</div>
                        <div class="text-gray-500">نوع إيراد</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ \App\Models\ExpenseEntity::count() }}</div>
                        <div class="text-gray-500">جهة صرف</div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Content Loading Area -->
            <div id="dynamic-content" class="hidden">
                <!-- Header with back button -->
                <div class="border-b border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <button onclick="showWelcomeSection()"
                                class="ml-4 p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="ri-arrow-right-line text-xl"></i>
                            </button>
                            <div>
                                <h2 id="section-title" class="text-2xl font-bold text-gray-900"></h2>
                                <p id="section-description" class="text-gray-600"></p>
                            </div>
                        </div>
                        <button id="add-new-btn"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
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
                        <h3 class="text-lg font-semibold text-gray-900" id="equipment-modal-title">إضافة نوع معدة جديد
                        </h3>
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <div id="location_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="location_description"
                                class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="أدخل اسم المادة" required>
                        <div id="name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="material_unit" class="block text-sm font-medium text-gray-700 mb-2">وحدة
                            القياس</label>
                        <select id="material_unit" name="unit"
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

                <form id="expense-category-form" action="{{ route('settings.expense-categories.store') }}"
                    method="POST" class="p-6">
                    @csrf
                    <input type="hidden" id="expense-category-id" name="id">
                    <input type="hidden" id="expense-category-form-method" name="_method">

                    <div class="space-y-4">
                        <div>
                            <label for="expense_category_name" class="block text-sm font-medium text-gray-700 mb-2">اسم
                                فئة المصروف *</label>
                            <input type="text" id="expense_category_name" name="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors">
                            <div id="expense_category_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="expense_category_code" class="block text-sm font-medium text-gray-700 mb-2">كود
                                فئة المصروف *</label>
                            <input type="text" id="expense_category_code" name="code" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors">
                            <div id="expense_category_code_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="expense_category_description"
                                class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                            <textarea id="expense_category_description" name="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors resize-none"
                                placeholder="وصف اختياري لفئة المصروف"></textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="expense_category_is_active" name="is_active" value="1"
                                checked
                                class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                            <label for="expense_category_is_active" class="mr-2 text-sm text-gray-700">فئة مصروف
                                فعالة</label>
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

    <!-- Revenue Type Modal -->
    <div id="revenue-type-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900" id="revenue-type-modal-title">إضافة نوع إيراد جديد
                        </h3>
                        <button onclick="closeRevenueTypeModal()"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="revenue-type-form" action="{{ route('settings.revenue-types.store') }}" method="POST"
                    class="p-6">
                    @csrf
                    <input type="hidden" id="revenue-type-id" name="id">
                    <input type="hidden" id="revenue-type-form-method" name="_method">

                    <div class="space-y-4">
                        <div>
                            <label for="revenue_type_name" class="block text-sm font-medium text-gray-700 mb-2">اسم نوع
                                الإيراد *</label>
                            <input type="text" id="revenue_type_name" name="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors">
                            <div id="revenue_type_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="revenue_type_code" class="block text-sm font-medium text-gray-700 mb-2">كود نوع
                                الإيراد *</label>
                            <input type="text" id="revenue_type_code" name="code" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors">
                            <div id="revenue_type_code_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="revenue_type_description"
                                class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                            <textarea id="revenue_type_description" name="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors resize-none"
                                placeholder="وصف اختياري لنوع الإيراد"></textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="revenue_type_is_active" name="is_active" value="1" checked
                                class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500">
                            <label for="revenue_type_is_active" class="mr-2 text-sm text-gray-700">نوع إيراد فعال</label>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeRevenueTypeModal()"
                            class="flex-1 px-6 py-3 text-gray-700 bg-gray-100 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl font-medium hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200">
                            <span id="revenue-type-submit-text">إضافة</span>
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
                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">حذف فئة المصروف</h3>
                    <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذه الفئة؟ لا يمكن التراجع عن هذا
                        الإجراء.</p>
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="ri-money-dollar-circle-line text-red-600"></i>
                            </div>
                            <div class="mr-3">
                                <div class="text-sm font-medium text-gray-900" id="delete-expense-category-name">اسم الفئة
                                </div>
                                <div class="text-xs text-gray-500" id="delete-expense-category-code">كود الفئة</div>
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

    <!-- Delete Revenue Type Modal -->
    <div id="delete-revenue-type-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <i class="ri-delete-bin-line text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">حذف نوع الإيراد</h3>
                    <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذا النوع؟ لا يمكن التراجع عن هذا
                        الإجراء.</p>
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                <i class="ri-hand-coin-line text-emerald-600"></i>
                            </div>
                            <div class="mr-3">
                                <div class="text-sm font-medium text-gray-900" id="delete-revenue-type-name">اسم النوع
                                </div>
                                <div class="text-xs text-gray-500" id="delete-revenue-type-code">كود النوع</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteRevenueTypeModal()"
                            class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="button" onclick="confirmDeleteRevenueType()" id="confirm-delete-revenue-type-btn"
                            class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200">
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
                    loadUrl: '{{ route('settings.equipment-types.content') }}'
                },
                'location-types': {
                    title: 'أنواع المواقع',
                    description: 'إدارة أنواع المواقع والمشاريع',
                    addText: 'إضافة نوع موقع',
                    loadUrl: '{{ route('settings.location-types.content') }}'
                },
                'materials': {
                    title: 'إدارة المواد',
                    description: 'إدارة المواد ووحدات القياس',
                    addText: 'إضافة مادة',
                    loadUrl: '{{ route('settings.materials.content') }}'
                },
                'suppliers': {
                    title: 'موردي قطع الغيار',
                    description: 'إدارة الموردين والشركات المزودة للقطع والمواد',
                    addText: 'إضافة مورد',
                    loadUrl: '{{ route('suppliers.content') }}'
                },
                'expense-categories': {
                    title: 'فئات المصروفات',
                    description: 'إدارة فئات وأنواع المصروفات المالية',
                    addText: 'إضافة فئة مصروف',
                    loadUrl: '{{ route('settings.expense-categories.content') }}'
                },
                'revenue-types': {
                    title: 'أنواع الإيرادات',
                    description: 'إدارة أنواع ومصادر الإيرادات المالية',
                    addText: 'إضافة نوع إيراد',
                    loadUrl: '{{ route('settings.revenue-types.content') }}'
                },
                'expense-entities': {
                    title: 'جهات الصرف',
                    description: 'إدارة الموردين والمقاولين والجهات المختلفة',
                    addText: 'إضافة جهة صرف',
                    loadUrl: '{{ route('expense-entities.index') }}'
                },
                'revenue-entities': {
                    title: 'جهات الإيرادات',
                    description: 'إدارة جهات مصدر الإيرادات والزبائن',
                    addText: 'إضافة جهة إيراد',
                    loadUrl: '{{ route('settings.revenue-entities.content') }}'
                }
            };

            const config = sectionConfig[section];
            document.getElementById('section-title').textContent = config.title;
            document.getElementById('section-description').textContent = config.description;
            document.getElementById('add-btn-text').textContent = config.addText;

            // Configure add button
            const addBtn = document.getElementById('add-new-btn');
            addBtn.onclick = function() {
                console.log('Add button clicked for section:', section);
                try {
                    if (section === 'equipment-types') {
                        console.log('Calling openEquipmentTypeModal...');
                        openEquipmentTypeModal();
                    } else if (section === 'location-types') {
                        console.log('Calling openLocationTypeModal...');
                        openLocationTypeModal();
                    } else if (section === 'materials') {
                        console.log('Calling openMaterialModal...');
                        openMaterialModal();
                    } else if (section === 'suppliers') {
                        console.log('Redirecting to suppliers create page...');
                        window.location.href = '{{ route('suppliers.create') }}';
                    } else if (section === 'expense-categories') {
                        console.log('Calling openExpenseCategoryModal...');
                        openExpenseCategoryModal();
                    } else if (section === 'revenue-types') {
                        console.log('Calling openRevenueTypeModal...');
                        openRevenueTypeModal();
                    } else if (section === 'expense-entities') {
                        console.log('Redirecting to expense entities create page...');
                        window.location.href = '{{ route('expense-entities.create') }}';
                    } else if (section === 'revenue-entities') {
                        console.log('Redirecting to revenue entities create page...');
                        window.location.href = '{{ route('settings.revenue-entities.create') }}';
                    }
                } catch (error) {
                    console.error('Error in add button click handler:', error);
                    alert('حدث خطأ: ' + error.message);
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
            console.log('Opening equipment type modal...');
            try {
                const modalTitle = document.getElementById('equipment-modal-title');
                const saveBtn = document.getElementById('equipment-save-btn');
                const form = document.getElementById('equipment-type-form');
                const methodField = document.getElementById('equipment-form-method');
                const modal = document.getElementById('equipment-type-modal');

                if (!modalTitle || !saveBtn || !form || !methodField || !modal) {
                    console.error('Missing elements for equipment type modal:', {
                        modalTitle: !!modalTitle,
                        saveBtn: !!saveBtn,
                        form: !!form,
                        methodField: !!methodField,
                        modal: !!modal
                    });
                    alert('حدث خطأ في فتح النافذة: عناصر النافذة غير موجودة');
                    return;
                }

                modalTitle.textContent = 'إضافة نوع معدة جديد';
                saveBtn.textContent = 'إضافة';
                form.action = '{{ route('settings.equipment-types.store') }}';
                methodField.value = '';
                clearEquipmentTypeForm();
                modal.classList.remove('hidden');
                console.log('Equipment type modal opened successfully');
            } catch (error) {
                console.error('Error opening equipment type modal:', error);
                alert('حدث خطأ في فتح النافذة: ' + error.message);
            }
        }

        function closeEquipmentTypeModal() {
            document.getElementById('equipment-type-modal').classList.add('hidden');
            clearEquipmentTypeForm();
            clearEquipmentTypeErrors();
        }

        function clearEquipmentTypeForm() {
            try {
                const form = document.getElementById('equipment-type-form');
                if (form) form.action = '{{ route('settings.equipment-types.store') }}';

                const equipmentTypeId = document.getElementById('equipment-type-id');
                const equipmentName = document.getElementById('equipment_name');
                const equipmentDescription = document.getElementById('equipment_description');
                const equipmentIsActive = document.getElementById('equipment_is_active');
                const methodField = document.getElementById('equipment-form-method');

                if (equipmentTypeId) equipmentTypeId.value = '';
                if (equipmentName) equipmentName.value = '';
                if (equipmentDescription) equipmentDescription.value = '';
                if (equipmentIsActive) equipmentIsActive.checked = true;
                if (methodField) methodField.value = '';
            } catch (error) {
                console.error('Error clearing equipment type form:', error);
            }
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
            console.log('Opening location type modal...');
            try {
                const modalTitle = document.getElementById('location-modal-title');
                const saveBtn = document.getElementById('location-save-btn');
                const form = document.getElementById('location-type-form');
                const methodField = document.getElementById('location-form-method');
                const modal = document.getElementById('location-type-modal');

                if (!modalTitle || !saveBtn || !form || !methodField || !modal) {
                    console.error('Missing elements for location type modal:', {
                        modalTitle: !!modalTitle,
                        saveBtn: !!saveBtn,
                        form: !!form,
                        methodField: !!methodField,
                        modal: !!modal
                    });
                    alert('حدث خطأ في فتح النافذة: عناصر النافذة غير موجودة');
                    return;
                }

                modalTitle.textContent = 'إضافة نوع موقع جديد';
                saveBtn.textContent = 'إضافة';
                form.action = '{{ route('settings.location-types.store') }}';
                methodField.value = '';
                clearLocationTypeForm();
                modal.classList.remove('hidden');
                console.log('Location type modal opened successfully');
            } catch (error) {
                console.error('Error opening location type modal:', error);
                alert('حدث خطأ في فتح النافذة: ' + error.message);
            }
        }

        function closeLocationTypeModal() {
            document.getElementById('location-type-modal').classList.add('hidden');
            clearLocationTypeForm();
            clearLocationTypeErrors();
        }

        function clearLocationTypeForm() {
            try {
                const form = document.getElementById('location-type-form');
                if (form) form.action = '{{ route('settings.location-types.store') }}';

                const locationTypeId = document.getElementById('location-type-id');
                const locationName = document.getElementById('location_name');
                const locationDescription = document.getElementById('location_description');
                const locationIsActive = document.getElementById('location_is_active');
                const methodField = document.getElementById('location-form-method');

                if (locationTypeId) locationTypeId.value = '';
                if (locationName) locationName.value = '';
                if (locationDescription) locationDescription.value = '';
                if (locationIsActive) locationIsActive.checked = true;
                if (methodField) methodField.value = '';
            } catch (error) {
                console.error('Error clearing location type form:', error);
            }
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
            console.log('Delete equipment type called with:', {
                id,
                name,
                description
            });

            // Store equipment type info for deletion
            window.equipmentTypeToDelete = {
                id,
                name,
                description
            };

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
                                loadSectionContent('{{ route('settings.equipment-types.content') }}');
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
            console.log('Delete location type called with:', {
                id,
                name,
                description
            });

            // Store location type info for deletion
            window.locationTypeToDelete = {
                id,
                name,
                description
            };

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
                                loadSectionContent('{{ route('settings.location-types.content') }}');
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
            document.getElementById('material-form').action = '{{ route('settings.materials.store') }}';
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
            console.log('Edit material called with:', {
                id,
                name,
                unit
            });

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
            console.log('Delete material called with:', {
                id,
                name,
                unit
            });

            // Store material info for deletion
            window.materialToDelete = {
                id,
                name,
                unit
            };

            // Update modal content
            document.getElementById('delete-material-name').textContent = name || 'مادة غير محددة';
            document.getElementById('delete-material-unit').textContent = unit || 'وحدة غير محددة';

            // Show delete modal
            document.getElementById('delete-material-modal').classList.remove('hidden');
        }

        function closeDeleteMaterialModal() {
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

        // Expense Category Modal Functions
        function openExpenseCategoryModal() {
            const modal = document.getElementById('expense-category-modal');
            const modalTitle = document.getElementById('expense-category-modal-title');
            const submitText = document.getElementById('expense-category-submit-text');

            modalTitle.textContent = 'إضافة فئة مصروف جديدة';
            submitText.textContent = 'إضافة';

            clearExpenseCategoryForm();
            clearExpenseCategoryErrors();
            modal.classList.remove('hidden');
        }

        function closeExpenseCategoryModal() {
            document.getElementById('expense-category-modal').classList.add('hidden');
            clearExpenseCategoryForm();
            clearExpenseCategoryErrors();
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

        function clearExpenseCategoryErrors() {
            const errorElements = ['expense_category_name_error', 'expense_category_code_error'];
            errorElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.classList.add('hidden');
                    element.textContent = '';
                }
            });
        }

        // Global expense category management functions
        function editExpenseCategory(id, name, code, description, isActive) {
            const modalTitle = document.getElementById('expense-category-modal-title');
            const submitText = document.getElementById('expense-category-submit-text');
            const form = document.getElementById('expense-category-form');
            const methodField = document.getElementById('expense-category-form-method');

            if (modalTitle && submitText && form && methodField) {
                modalTitle.textContent = 'تعديل فئة المصروف';
                submitText.textContent = 'تحديث';
                form.action = `/settings/expense-categories/${id}`;
                methodField.value = 'PUT';

                document.getElementById('expense-category-id').value = id;
                document.getElementById('expense_category_name').value = name;
                document.getElementById('expense_category_code').value = code;
                document.getElementById('expense_category_description').value = description;
                document.getElementById('expense_category_is_active').checked = isActive;

                clearExpenseCategoryErrors();
                document.getElementById('expense-category-modal').classList.remove('hidden');
            }
        }

        function deleteExpenseCategory(id, name = '', code = '') {
            window.expenseCategoryToDelete = {
                id,
                name,
                code
            };
            document.getElementById('delete-expense-category-name').textContent = name || 'فئة مصروف غير محددة';
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
                    confirmBtn.textContent = originalText;
                    confirmBtn.disabled = false;
                });
        }

        function toggleExpenseCategoryStatus(id) {
            console.log('Toggle expense category status (global) called with ID:', id);

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF Token found:', !!csrfToken);
            if (!csrfToken) {
                alert('لم يتم العثور على رمز CSRF');
                return;
            }

            const tokenValue = csrfToken.getAttribute('content');
            console.log('CSRF Token value length:', tokenValue ? tokenValue.length : 'null');

            // Show loading state on the button
            const toggleBtn = document.querySelector(`button[onclick="toggleExpenseCategoryStatus(${id})"]`);
            console.log('Toggle button found:', !!toggleBtn);
            if (toggleBtn) {
                toggleBtn.disabled = true;
                toggleBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';
            }

            const url = `/settings/expense-categories/${id}/toggle-status`;
            console.log('Making request to:', url);

            fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': tokenValue,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Toggle status response status:', response.status);
                    console.log('Toggle status response headers:', [...response.headers.entries()]);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.log('Error response body:', text);
                            throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Toggle status response data:', data);
                    if (data.success) {
                        console.log('Toggle successful, reloading content...');
                        if (currentSection === 'expense-categories') {
                            loadSectionContent('{{ route('settings.expense-categories.content') }}');
                        } else {
                            console.log('Current section is not expense-categories:', currentSection);
                            // Force reload anyway
                            loadSectionContent('{{ route('settings.expense-categories.content') }}');
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
                    console.error('Error in toggleExpenseCategoryStatus:', error);
                    alert('حدث خطأ في الاتصال: ' + error.message);
                    // Reset button state
                    if (toggleBtn) {
                        toggleBtn.disabled = false;
                        toggleBtn.innerHTML = '<i class="ri-toggle-line"></i>';
                    }
                });
        }

        // Revenue Type Modal Functions
        function openRevenueTypeModal() {
            const modal = document.getElementById('revenue-type-modal');
            const modalTitle = document.getElementById('revenue-type-modal-title');
            const submitText = document.getElementById('revenue-type-submit-text');

            modalTitle.textContent = 'إضافة نوع إيراد جديد';
            submitText.textContent = 'إضافة';

            clearRevenueTypeForm();
            clearRevenueTypeErrors();
            modal.classList.remove('hidden');
        }

        function closeRevenueTypeModal() {
            document.getElementById('revenue-type-modal').classList.add('hidden');
            clearRevenueTypeForm();
            clearRevenueTypeErrors();
        }

        function clearRevenueTypeForm() {
            const form = document.getElementById('revenue-type-form');
            form.action = '{{ route('settings.revenue-types.store') }}';
            document.getElementById('revenue-type-id').value = '';
            document.getElementById('revenue_type_name').value = '';
            document.getElementById('revenue_type_code').value = '';
            document.getElementById('revenue_type_description').value = '';
            document.getElementById('revenue_type_is_active').checked = true;
            document.getElementById('revenue-type-form-method').value = '';
        }

        function clearRevenueTypeErrors() {
            const errorElements = ['revenue_type_name_error', 'revenue_type_code_error'];
            errorElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.classList.add('hidden');
                    element.textContent = '';
                }
            });
        }

        // Global revenue type management functions
        function editRevenueType(id, name, code, description, isActive) {
            const modalTitle = document.getElementById('revenue-type-modal-title');
            const submitText = document.getElementById('revenue-type-submit-text');
            const form = document.getElementById('revenue-type-form');
            const methodField = document.getElementById('revenue-type-form-method');

            if (modalTitle && submitText && form && methodField) {
                modalTitle.textContent = 'تعديل نوع الإيراد';
                submitText.textContent = 'تحديث';
                form.action = `/settings/revenue-types/${id}`;
                methodField.value = 'PUT';

                document.getElementById('revenue-type-id').value = id;
                document.getElementById('revenue_type_name').value = name;
                document.getElementById('revenue_type_code').value = code;
                document.getElementById('revenue_type_description').value = description;
                document.getElementById('revenue_type_is_active').checked = isActive;

                clearRevenueTypeErrors();
                document.getElementById('revenue-type-modal').classList.remove('hidden');
            }
        }

        function deleteRevenueType(id, name = '', code = '') {
            window.revenueTypeToDelete = {
                id,
                name,
                code
            };
            document.getElementById('delete-revenue-type-name').textContent = name || 'نوع إيراد غير محدد';
            document.getElementById('delete-revenue-type-code').textContent = code || 'كود غير محدد';
            document.getElementById('delete-revenue-type-modal').classList.remove('hidden');
        }

        function closeDeleteRevenueTypeModal() {
            document.getElementById('delete-revenue-type-modal').classList.add('hidden');
            window.revenueTypeToDelete = null;
        }

        function confirmDeleteRevenueType() {
            if (!window.revenueTypeToDelete) return;

            const id = window.revenueTypeToDelete.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) {
                alert('لم يتم العثور على رمز CSRF');
                return;
            }

            const confirmBtn = document.getElementById('confirm-delete-revenue-type-btn');
            const originalText = confirmBtn.textContent;
            confirmBtn.textContent = 'جاري الحذف...';
            confirmBtn.disabled = true;

            fetch(`/settings/revenue-types/${id}`, {
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
                        closeDeleteRevenueTypeModal();
                        if (currentSection === 'revenue-types') {
                            loadSectionContent('{{ route('settings.revenue-types.content') }}');
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
                    confirmBtn.textContent = originalText;
                    confirmBtn.disabled = false;
                });
        }

        function toggleRevenueTypeStatus(id) {
            console.log('Toggle revenue type status (global) called with ID:', id);

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF Token found:', !!csrfToken);
            if (!csrfToken) {
                alert('لم يتم العثور على رمز CSRF');
                return;
            }

            const tokenValue = csrfToken.getAttribute('content');
            console.log('CSRF Token value length:', tokenValue ? tokenValue.length : 'null');

            // Show loading state on the button
            const toggleBtn = document.querySelector(`button[onclick="toggleRevenueTypeStatus(${id})"]`);
            console.log('Toggle button found:', !!toggleBtn);
            if (toggleBtn) {
                toggleBtn.disabled = true;
                toggleBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';
            }

            const url = `/settings/revenue-types/${id}/toggle-status`;
            console.log('Making request to:', url);

            fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': tokenValue,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Toggle status response status:', response.status);
                    console.log('Toggle status response headers:', [...response.headers.entries()]);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.log('Error response body:', text);
                            throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Toggle status response data:', data);
                    if (data.success) {
                        console.log('Toggle successful, reloading content...');
                        if (currentSection === 'revenue-types') {
                            loadSectionContent('{{ route('settings.revenue-types.content') }}');
                        } else {
                            console.log('Current section is not revenue-types:', currentSection);
                            // Force reload anyway
                            loadSectionContent('{{ route('settings.revenue-types.content') }}');
                        }
                    } else {
                        alert(data.message || 'حدث خطأ أثناء تغيير حالة نوع الإيراد');
                        // Reset button state
                        if (toggleBtn) {
                            toggleBtn.disabled = false;
                            toggleBtn.innerHTML = '<i class="ri-toggle-line"></i>';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error in toggleRevenueTypeStatus:', error);
                    alert('حدث خطأ في الاتصال: ' + error.message);
                    // Reset button state
                    if (toggleBtn) {
                        toggleBtn.disabled = false;
                        toggleBtn.innerHTML = '<i class="ri-toggle-line"></i>';
                    }
                });
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
                    } else if (url.includes('/suppliers?')) {
                        url = url.replace('/suppliers?', '/suppliers/content?');
                    } else if (url.includes('/suppliers')) {
                        url = url.replace('/suppliers', '/suppliers/content');
                    }

                    console.log('Global pagination handler:', url);
                    loadSectionContent(url);
                }
            }

            // Handle suppliers filter form submission
            const suppliersFilterForm = e.target.closest('#suppliers-filter-form');
            if (suppliersFilterForm && e.type === 'submit') {
                e.preventDefault();
                e.stopPropagation();

                const formData = new FormData(suppliersFilterForm);
                const params = new URLSearchParams(formData);
                const url = '{{ route('suppliers.content') }}?' + params.toString();

                console.log('Suppliers filter form submission:', url);
                loadSectionContent(url);
                return;
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

            // Close delete expense category modal when clicking outside
            if (e.target.id === 'delete-expense-category-modal') {
                closeDeleteExpenseCategoryModal();
            }

            // Close delete revenue type modal when clicking outside
            if (e.target.id === 'delete-revenue-type-modal') {
                closeDeleteRevenueTypeModal();
            }
        });

        // Add event delegation for suppliers forms
        document.addEventListener('submit', function(e) {
            // Handle suppliers filter form
            if (e.target.id === 'suppliers-filter-form') {
                e.preventDefault();

                const formData = new FormData(e.target);
                const params = new URLSearchParams(formData);
                const url = '{{ route('suppliers.content') }}?' + params.toString();

                console.log('Suppliers filter form submission:', url);
                loadSectionContent(url);
            }

            // Handle expense category form submission
            if (e.target.id === 'expense-category-form') {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                submitBtn.textContent = 'جاري الحفظ...';
                submitBtn.disabled = true;

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeExpenseCategoryModal();
                            if (currentSection === 'expense-categories') {
                                loadSectionContent('{{ route('settings.expense-categories.content') }}');
                            }
                        } else if (data.errors) {
                            // Display validation errors
                            for (const [field, messages] of Object.entries(data.errors)) {
                                const errorElement = document.getElementById(`expense_category_${field}_error`);
                                const inputElement = document.getElementById(`expense_category_${field}`);

                                if (errorElement && inputElement) {
                                    errorElement.textContent = messages[0];
                                    errorElement.classList.remove('hidden');
                                    inputElement.classList.remove('border-gray-300');
                                    inputElement.classList.add('border-red-500');
                                }
                            }
                        } else {
                            alert(data.message || 'حدث خطأ أثناء الحفظ');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ في الاتصال');
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
            }

            // Handle revenue type form submission
            if (e.target.id === 'revenue-type-form') {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                submitBtn.textContent = 'جاري الحفظ...';
                submitBtn.disabled = true;

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeRevenueTypeModal();
                            if (currentSection === 'revenue-types') {
                                loadSectionContent('{{ route('settings.revenue-types.content') }}');
                            }
                        } else if (data.errors) {
                            // Display validation errors
                            for (const [field, messages] of Object.entries(data.errors)) {
                                const errorElement = document.getElementById(`revenue_type_${field}_error`);
                                const inputElement = document.getElementById(`revenue_type_${field}`);

                                if (errorElement && inputElement) {
                                    errorElement.textContent = messages[0];
                                    errorElement.classList.remove('hidden');
                                    inputElement.classList.remove('border-gray-300');
                                    inputElement.classList.add('border-red-500');
                                }
                            }
                        } else {
                            alert(data.message || 'حدث خطأ أثناء الحفظ');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ في الاتصال');
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
            }

            // Handle equipment type form submission
            if (e.target.id === 'equipment-type-form') {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                submitBtn.textContent = 'جاري الحفظ...';
                submitBtn.disabled = true;

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeEquipmentTypeModal();
                            if (currentSection === 'equipment-types') {
                                loadSectionContent('{{ route('settings.equipment-types.content') }}');
                            }
                        } else if (data.errors) {
                            // Display validation errors
                            for (const [field, messages] of Object.entries(data.errors)) {
                                const errorElement = document.getElementById(`equipment_${field}_error`);
                                const inputElement = document.getElementById(`equipment_${field}`);

                                if (errorElement && inputElement) {
                                    errorElement.textContent = messages[0];
                                    errorElement.classList.remove('hidden');
                                    inputElement.classList.remove('border-gray-300');
                                    inputElement.classList.add('border-red-500');
                                }
                            }
                        } else {
                            alert(data.message || 'حدث خطأ أثناء الحفظ');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ في الاتصال');
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
            }

            // Handle location type form submission
            if (e.target.id === 'location-type-form') {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                submitBtn.textContent = 'جاري الحفظ...';
                submitBtn.disabled = true;

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeLocationTypeModal();
                            if (currentSection === 'location-types') {
                                loadSectionContent('{{ route('settings.location-types.content') }}');
                            }
                        } else if (data.errors) {
                            // Display validation errors
                            for (const [field, messages] of Object.entries(data.errors)) {
                                const errorElement = document.getElementById(`location_${field}_error`);
                                const inputElement = document.getElementById(`location_${field}`);

                                if (errorElement && inputElement) {
                                    errorElement.textContent = messages[0];
                                    errorElement.classList.remove('hidden');
                                    inputElement.classList.remove('border-gray-300');
                                    inputElement.classList.add('border-red-500');
                                }
                            }
                        } else {
                            alert(data.message || 'حدث خطأ أثناء الحفظ');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ في الاتصال');
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
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

                const deleteExpenseCategoryModal = document.getElementById('delete-expense-category-modal');
                if (deleteExpenseCategoryModal && !deleteExpenseCategoryModal.classList.contains('hidden')) {
                    closeDeleteExpenseCategoryModal();
                }

                const deleteRevenueTypeModal = document.getElementById('delete-revenue-type-modal');
                if (deleteRevenueTypeModal && !deleteRevenueTypeModal.classList.contains('hidden')) {
                    closeDeleteRevenueTypeModal();
                }
            }
        });

        // Debug: Test if functions are properly loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Settings page JavaScript loaded');
            console.log('openEquipmentTypeModal function exists:', typeof openEquipmentTypeModal === 'function');
            console.log('showSettingsSection function exists:', typeof showSettingsSection === 'function');

            // Test modal element exists
            const modal = document.getElementById('equipment-type-modal');
            console.log('Equipment type modal element exists:', modal !== null);

            // Test add button exists
            const addBtn = document.getElementById('add-new-btn');
            console.log('Add new button exists:', addBtn !== null);
        });
    </script>
@endsection
