@extends('layouts.app')

@section('title', 'الأدوار والصلاحيات')

@push('styles')
    <style>
        .role-filter-btn {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .role-filter-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .user-row {
            transition: background-color 0.2s ease;
        }

        .user-row:hover {
            background-color: #f9fafb;
        }

        #userSearch:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-highlight {
            background-color: #fef3c7;
        }

        /* Role Card Animations */
        .role-card {
            transition: all 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .role-card .btn-edit:hover {
            background-color: #dbeafe !important;
            transform: scale(1.02);
        }

        .role-card .btn-delete:hover {
            background-color: #fee2e2 !important;
            transform: scale(1.02);
        }

        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Loading animation */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .loading-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        padding: 1px 2px;
        border-radius: 2px;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إدارة الأدوار والصلاحيات</h1>
                <p class="text-gray-600 mt-1">إدارة أدوار المستخدمين والصلاحيات المختلفة</p>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showTab('roles')" id="roles-tab"
                    class="tab-button active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-user-shield ml-2"></i>
                    الأدوار
                </button>
                <button onclick="showTab('permissions')" id="permissions-tab"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-key ml-2"></i>
                    الصلاحيات
                </button>
                <button onclick="showTab('users')" id="users-tab"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-users ml-2"></i>
                    المستخدمون
                </button>
            </nav>
        </div>

        <!-- Roles Tab -->
        <div id="roles-content" class="tab-content">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">الأدوار المتاحة</h2>
                        <button onclick="openRoleModal()"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus ml-2"></i>
                            إضافة دور جديد
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($roles as $role)
                            <div
                                class="border border-gray-200 rounded-lg p-4 hover:shadow-lg hover:border-blue-300 transition-all duration-200 bg-white role-card">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 text-lg">{{ $role->display_name }}</h3>
                                        <p class="text-sm text-gray-500 font-mono">{{ $role->name }}</p>
                                        @if ($role->category)
                                            <span
                                                class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full mt-1">
                                                {{ $role->category }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        @if ($role->is_active)
                                            <span
                                                class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full flex items-center">
                                                <i class="fas fa-check-circle ml-1"></i>
                                                نشط
                                            </span>
                                        @else
                                            <span
                                                class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full flex items-center">
                                                <i class="fas fa-times-circle ml-1"></i>
                                                غير نشط
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if ($role->description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $role->description }}</p>
                                @endif

                                <div
                                    class="flex justify-between items-center text-sm text-gray-500 mb-4 bg-gray-50 rounded-md p-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-key ml-1"></i>
                                        <span>{{ $role->permissions ? $role->permissions->count() : 0 }} صلاحية</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-users ml-1"></i>
                                        <span>{{ $role->users_count ?? 0 }} مستخدم</span>
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-2 space-x-reverse">
                                    <button onclick="editRole({{ $role->id }})"
                                        class="bg-blue-100 text-blue-700 px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-200 transition-all duration-200 flex items-center btn-edit"
                                        title="تعديل الدور">
                                        <i class="fas fa-edit ml-1"></i>
                                        تعديل
                                    </button>
                                    @if ($role->users_count == 0 && !in_array($role->name, ['super_admin']))
                                        <button onclick="deleteRole({{ $role->id }}, '{{ $role->display_name }}')"
                                            class="bg-red-100 text-red-700 px-3 py-2 rounded-md text-sm font-medium hover:bg-red-200 transition-all duration-200 flex items-center btn-delete"
                                            title="حذف الدور">
                                            <i class="fas fa-trash ml-1"></i>
                                            حذف
                                        </button>
                                    @else
                                        @if (in_array($role->name, ['super_admin']))
                                            <span
                                                class="text-xs text-gray-400 px-2 py-1 bg-gray-50 rounded-md flex items-center">
                                                <i class="fas fa-shield-alt ml-1"></i>
                                                دور محمي
                                            </span>
                                        @else
                                            <span
                                                class="text-xs text-gray-400 px-2 py-1 bg-gray-50 rounded-md flex items-center">
                                                <i class="fas fa-users ml-1"></i>
                                                يحتوي على مستخدمين ({{ $role->users_count }})
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Tab -->
        <div id="permissions-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">الصلاحيات المتاحة</h2>
                        <div class="flex gap-2">
                            <button onclick="openPermissionCategoriesModal()"
                                class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                                <i class="fas fa-tags ml-2"></i>
                                إدارة فئات الصلاحيات
                            </button>
                            <button onclick="openPermissionModal()"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                <i class="fas fa-plus ml-2"></i>
                                إضافة صلاحية جديدة
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @foreach ($permissions as $category => $categoryPermissions)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                {{ $category }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($categoryPermissions as $permission)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $permission->display_name }}</h4>
                                            @if ($permission->is_active)
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">نشط</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">غير
                                                    نشط</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 mb-2">{{ $permission->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $permission->description }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Users Tab -->
        <div id="users-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <h2 class="text-lg font-semibold text-gray-900">المستخدمون وأدوارهم</h2>

                        <!-- Search Box -->
                        <div class="relative max-w-md w-full">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="userSearch" placeholder="البحث عن مستخدم... (Ctrl+F)"
                                class="w-full pr-10 pl-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <button type="button" id="clearSearch" class="text-gray-400 hover:text-gray-600 hidden">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filter by Role -->
                    <div class="mt-4 flex flex-wrap gap-2 items-center">
                        <span class="text-sm text-gray-600 ml-4">تصفية حسب الدور:</span>
                        <button onclick="filterUsersByRole('all')"
                            class="role-filter-btn active bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-blue-200">
                            جميع المستخدمين
                        </button>
                        @foreach ($roles as $role)
                            <button onclick="filterUsersByRole('{{ $role->name }}')"
                                class="role-filter-btn bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium hover:bg-gray-200">
                                {{ $role->display_name }} <span
                                    class="bg-white rounded-full px-1 ml-1 text-xs">{{ $role->users_count }}</span>
                            </button>
                        @endforeach
                        <button onclick="filterUsersByRole('no-role')"
                            class="role-filter-btn bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium hover:bg-red-200">
                            بدون أدوار
                        </button>
                    </div>

                    <!-- Results count -->
                    <div class="mt-4 text-sm text-gray-600">
                        <span id="usersCount">{{ count($users) }}</span> من المستخدمين
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المستخدم</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        البريد الإلكتروني</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الأدوار</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                                @foreach ($users as $user)
                                    <tr class="user-row" data-user-name="{{ strtolower($user->name) }}"
                                        data-user-email="{{ strtolower($user->email) }}"
                                        data-user-roles="{{ implode(',', $user->roles->pluck('name')->toArray()) }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="{{ $user->avatar_url }}"
                                                        alt="">
                                                </div>
                                                <div class="mr-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($user->roles as $role)
                                                    <span
                                                        class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $role->display_name }}</span>
                                                @empty
                                                    <span class="text-gray-400 text-sm">لا توجد أدوار</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="editUserRoles({{ $user->id }})"
                                                class="text-blue-600 hover:text-blue-900">
                                                تعديل الأدوار
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Modal -->
    <div id="roleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
            <form id="roleForm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900" id="roleModalTitle">إضافة دور جديد</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم الدور (بالإنجليزية)</label>
                            <input type="text" name="name" id="roleName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required placeholder="مثال: site_manager">
                            <p class="text-xs text-gray-500 mt-1">اسم فريد بالأحرف الإنجليزية والأرقام فقط (بدون مسافات)
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الاسم المعروض</label>
                            <input type="text" name="display_name" id="roleDisplayName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required placeholder="مثال: مدير موقع">
                            <p class="text-xs text-gray-500 mt-1">الاسم الذي سيظهر للمستخدمين في الواجهة</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">فئة الدور</label>
                        <select name="category" id="roleCategory"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">اختر فئة الدور</option>
                            <option value="الإدارة">الإدارة</option>
                            <option value="التشغيل">التشغيل</option>
                            <option value="المالية">المالية</option>
                            <option value="اللوجستيات">اللوجستيات</option>
                            <option value="المراقبة">المراقبة</option>
                            <option value="التقنية">التقنية</option>
                            <option value="الموارد البشرية">الموارد البشرية</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea name="description" id="roleDescription" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            placeholder="وصف مختصر لمسؤوليات هذا الدور..."></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الصلاحيات</label>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-4">
                            @foreach ($permissions as $category => $categoryPermissions)
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-800 mb-2 border-b border-gray-200 pb-1">
                                        {{ $category }}</h4>
                                    @foreach ($categoryPermissions as $permission)
                                        <div class="flex items-center mb-1">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                id="perm_{{ $permission->id }}"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label for="perm_{{ $permission->id }}"
                                                class="mr-2 text-sm text-gray-700">{{ $permission->display_name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center mb-6">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="roleIsActive" checked
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="roleIsActive" class="mr-2 text-sm text-gray-700">دور نشط</label>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeRoleModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <span id="roleSubmitText">حفظ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Roles Modal -->
    <div id="userRolesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <form id="userRolesForm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">تعديل أدوار المستخدم</h3>
                    <p class="text-sm text-gray-600 mt-1" id="userName"></p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($roles as $role)
                            <div class="flex items-center">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                    id="role_{{ $role->id }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="role_{{ $role->id }}" class="mr-2 text-sm">
                                    <span class="font-medium">{{ $role->display_name }}</span>
                                    <span class="text-gray-500 block text-xs">{{ $role->description }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeUserRolesModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Permission Modal -->
    <div id="permissionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <form id="permissionForm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">إضافة صلاحية جديدة</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم الصلاحية (بالإنجليزية)</label>
                            <input type="text" name="name" id="permissionName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required placeholder="مثال: users.create">
                            <p class="text-xs text-gray-500 mt-1">اسم فريد بالأحرف الإنجليزية والنقطة فقط</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الاسم المعروض</label>
                            <input type="text" name="display_name" id="permissionDisplayName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required placeholder="مثال: إنشاء المستخدمين">
                            <p class="text-xs text-gray-500 mt-1">الاسم الذي سيظهر في الواجهة</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">فئة الصلاحية</label>
                        <select name="category" id="permissionCategory"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">اختر الفئة...</option>
                            <option value="المستخدمون">المستخدمون</option>
                            <option value="الموظفون">الموظفون</option>
                            <option value="المعدات">المعدات</option>
                            <option value="المخازن">المخازن</option>
                            <option value="المواد">المواد</option>
                            <option value="المحروقات">المحروقات</option>
                            <option value="المستندات">المستندات</option>
                            <option value="المشاريع">المشاريع</option>
                            <option value="التقارير">التقارير</option>
                            <option value="الإعدادات">الإعدادات</option>
                            <option value="عام">عام</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">تصنيف الصلاحية حسب النشاط</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea name="description" id="permissionDescription" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            placeholder="وصف مختصر عن وظيفة هذه الصلاحية..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">وصف اختياري لتوضيح الغرض من الصلاحية</p>
                    </div>

                    <div class="flex items-center mb-6">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="permissionIsActive" checked
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="permissionIsActive" class="mr-2 text-sm text-gray-700">صلاحية نشطة</label>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closePermissionModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        حفظ الصلاحية
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Permission Categories Modal -->
    <div id="permissionCategoriesModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">إدارة فئات الصلاحيات</h3>
                    <button onclick="closePermissionCategoriesModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <!-- Add New Category Section -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3">إضافة فئة جديدة</h4>
                    <form id="newCategoryForm" class="flex gap-3">
                        <div class="flex-1">
                            <input type="text" id="newCategoryName" placeholder="اسم الفئة الجديدة..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                required>
                        </div>
                        <button type="submit"
                            class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-plus ml-1"></i>
                            إضافة
                        </button>
                    </form>
                </div>

                <!-- Current Categories -->
                <div class="mb-4">
                    <h4 class="text-md font-medium text-gray-900 mb-3">الفئات الحالية</h4>
                    <div id="categoriesList" class="space-y-2">
                        <!-- Categories will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end">
                <button onclick="closePermissionCategoriesModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    إغلاق
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let currentRoleId = null;
        let currentUserId = null;

        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');

            // Add active class to selected tab
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
        }

        // Role Modal Functions
        function openRoleModal() {
            currentRoleId = null;
            document.getElementById('roleModalTitle').textContent = 'إضافة دور جديد';
            document.getElementById('roleSubmitText').textContent = 'حفظ';
            document.getElementById('roleForm').reset();
            document.getElementById('roleIsActive').checked = true;
            document.getElementById('roleModal').classList.remove('hidden');
            document.getElementById('roleModal').classList.add('flex');
        }

        function closeRoleModal() {
            document.getElementById('roleModal').classList.add('hidden');
            document.getElementById('roleModal').classList.remove('flex');
        }

        function editRole(roleId) {
            currentRoleId = roleId;
            document.getElementById('roleModalTitle').textContent = 'تعديل الدور';
            document.getElementById('roleSubmitText').textContent = 'تحديث';

            // Show loading state on edit button
            const editButton = document.querySelector(`button[onclick="editRole(${roleId})"]`);
            const originalEditContent = editButton.innerHTML;
            editButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i>جارٍ التحميل...';
            editButton.disabled = true;

            // Fetch role data and populate form
            fetch(`/settings/roles/${roleId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('roleName').value = data.name;
                        document.getElementById('roleDisplayName').value = data.display_name;
                        document.getElementById('roleCategory').value = data.category || '';
                        document.getElementById('roleDescription').value = data.description || '';
                        document.getElementById('roleIsActive').checked = data.is_active;

                        // Clear all permissions first
                        document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                            checkbox.checked = false;
                        });

                        // Check role permissions
                        if (data.permissions && Array.isArray(data.permissions)) {
                            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                                checkbox.checked = data.permissions.includes(checkbox.value);
                            });
                        }

                        // Open modal
                        document.getElementById('roleModal').classList.remove('hidden');
                        document.getElementById('roleModal').classList.add('flex');
                    } else {
                        showAlert(data.message || 'حدث خطأ أثناء جلب بيانات الدور', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error fetching role data:', error);
                    showAlert('حدث خطأ أثناء جلب بيانات الدور', 'error');
                })
                .finally(() => {
                    // Restore button state
                    editButton.innerHTML = originalEditContent;
                    editButton.disabled = false;
                });
        }

        function deleteRole(roleId, roleName) {
            if (!confirm(`هل أنت متأكد من حذف الدور "${roleName}"؟\n\nلا يمكن التراجع عن هذا الإجراء.`)) {
                return;
            }

            // Show loading state
            const deleteButton = document.querySelector(`button[onclick="deleteRole(${roleId}, '${roleName}')"]`);
            const originalContent = deleteButton.innerHTML;
            deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i>جارٍ الحذف...';
            deleteButton.disabled = true;

            fetch(`/settings/roles/${roleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('تم حذف الدور بنجاح', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert(data.message || 'حدث خطأ أثناء حذف الدور', 'error');
                        // Restore button state
                        deleteButton.innerHTML = originalContent;
                        deleteButton.disabled = false;
                    }
                })
                .catch(error => {
                    showAlert('حدث خطأ أثناء حذف الدور', 'error');
                    // Restore button state
                    deleteButton.innerHTML = originalContent;
                    deleteButton.disabled = false;
                });
        }

        // User Roles Modal Functions
        function editUserRoles(userId) {
            currentUserId = userId;

            // Find user data
            const userRow = document.querySelector(`button[onclick="editUserRoles(${userId})"]`).closest('tr');
            const userName = userRow.querySelector('.text-sm.font-medium').textContent;

            document.getElementById('userName').textContent = userName;

            // Clear all checkboxes first
            document.querySelectorAll('input[name="roles[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Check user's current roles
            const roleSpans = userRow.querySelectorAll('.bg-blue-100');
            roleSpans.forEach(span => {
                const roleName = span.textContent.trim();
                // Find the corresponding checkbox by role display name
                document.querySelectorAll('input[name="roles[]"]').forEach(checkbox => {
                    const label = checkbox.nextElementSibling.querySelector('.font-medium');
                    if (label && label.textContent === roleName) {
                        checkbox.checked = true;
                    }
                });
            });

            document.getElementById('userRolesModal').classList.remove('hidden');
            document.getElementById('userRolesModal').classList.add('flex');
        }

        function closeUserRolesModal() {
            document.getElementById('userRolesModal').classList.add('hidden');
            document.getElementById('userRolesModal').classList.remove('flex');
        }

        // Permission Modal Functions
        function openPermissionModal() {
            document.getElementById('permissionForm').reset();
            document.getElementById('permissionIsActive').checked = true;
            document.getElementById('permissionModal').classList.remove('hidden');
            document.getElementById('permissionModal').classList.add('flex');
        }

        function closePermissionModal() {
            document.getElementById('permissionModal').classList.add('hidden');
            document.getElementById('permissionModal').classList.remove('flex');
        }

        // Auto-suggest permission name based on display name
        document.getElementById('permissionDisplayName').addEventListener('input', function() {
            const displayName = this.value;
            if (displayName && !document.getElementById('permissionName').value) {
                // Generate a suggestion for the permission name
                let suggestedName = displayName
                    .toLowerCase()
                    .replace(/[أ-ي]/g, function(match) {
                        const arabicToEnglish = {
                            'إنشاء': 'create',
                            'إضافة': 'create',
                            'جديد': 'create',
                            'تحديث': 'update',
                            'تعديل': 'update',
                            'تغيير': 'update',
                            'حذف': 'delete',
                            'إزالة': 'delete',
                            'مسح': 'delete',
                            'عرض': 'view',
                            'مشاهدة': 'view',
                            'قراءة': 'view',
                            'إدارة': 'manage',
                            'تحكم': 'manage',
                            'تنسيق': 'manage',
                            'مستخدم': 'user',
                            'مستخدمين': 'users',
                            'موظف': 'employee',
                            'موظفين': 'employees',
                            'معدة': 'equipment',
                            'معدات': 'equipment',
                            'مخزن': 'warehouse',
                            'مخازن': 'warehouses',
                            'مادة': 'material',
                            'مواد': 'materials',
                            'تقرير': 'report',
                            'تقارير': 'reports',
                            'مستند': 'document',
                            'مستندات': 'documents',
                            'مشروع': 'project',
                            'مشاريع': 'projects',
                            'فريق': 'team',
                            'مهمة': 'task',
                            'مهام': 'tasks',
                            'ميزانية': 'budget',
                            'موارد': 'resources',
                            'تقدم': 'progress',
                            'تتبع': 'track',
                            'جدولة': 'schedule',
                            'اجتماع': 'meeting',
                            'مخاطر': 'risk',
                            'جودة': 'quality',
                            'تحليلات': 'analytics',
                            'رفع': 'upload',
                            'تحميل': 'download',
                            'مشاركة': 'share',
                            'اعتماد': 'approve',
                            'أرشفة': 'archive',
                            'إصدار': 'version',
                            'مراجعة': 'audit',
                            'فئات': 'categories',
                            'تصنيف': 'category',
                            'نظام': 'system',
                            'إعدادات': 'settings',
                            'إعداد': 'setting'
                        };

                        for (const [arabic, english] of Object.entries(arabicToEnglish)) {
                            if (displayName.includes(arabic)) {
                                return english;
                            }
                        }
                        return match;
                    })
                    .replace(/\s+/g, '.')
                    .replace(/[^a-z0-9.]/g, '')
                    .replace(/\.+/g, '.');

                // Default pattern if no translation found
                if (suggestedName === displayName.toLowerCase()) {
                    const category = document.getElementById('permissionCategory').value;
                    if (category) {
                        const categoryMap = {
                            'المستخدمون': 'users',
                            'الموظفون': 'employees',
                            'المعدات': 'equipment',
                            'المخازن': 'warehouses',
                            'المواد': 'materials',
                            'المحروقات': 'fuel',
                            'المستندات': 'documents',
                            'المشاريع': 'projects',
                            'التقارير': 'reports',
                            'الإعدادات': 'settings'
                        };
                        suggestedName = (categoryMap[category] || 'general') + '.action';
                    } else {
                        suggestedName = 'general.action';
                    }
                }

                document.getElementById('permissionName').value = suggestedName;
            }
        });

        // Auto-update permission name when category changes
        document.getElementById('permissionCategory').addEventListener('change', function() {
            const nameField = document.getElementById('permissionName');
            const displayName = document.getElementById('permissionDisplayName').value;

            if (this.value && !nameField.value && displayName) {
                // Trigger the auto-suggestion
                document.getElementById('permissionDisplayName').dispatchEvent(new Event('input'));
            }
        });

        // Form Submissions
        document.getElementById('roleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = currentRoleId ? `/settings/roles/${currentRoleId}` : '/settings/roles';
            const method = currentRoleId ? 'PUT' : 'POST';

            if (currentRoleId) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        closeRoleModal();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        if (data.errors) {
                            let errorMessages = Object.values(data.errors).flat().join('\n');
                            showAlert(errorMessages, 'error');
                        } else {
                            showAlert(data.message || 'حدث خطأ أثناء حفظ الدور', 'error');
                        }
                    }
                })
                .catch(error => {
                    showAlert('حدث خطأ أثناء حفظ الدور', 'error');
                });
        });

        document.getElementById('userRolesForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentUserId) {
                showAlert('خطأ: لم يتم تحديد المستخدم', 'error');
                return;
            }

            const formData = new FormData(this);

            // إنشاء object للبيانات بدلاً من FormData
            const data = {
                roles: formData.getAll('roles[]')
            };

            console.log('Updating user roles for user ID:', currentUserId);
            console.log('Selected roles:', data.roles);

            fetch(`/settings/users/${currentUserId}/roles`, {
                    method: 'PUT',
                    body: JSON.stringify(data),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(`HTTP ${response.status}: ${JSON.stringify(errorData)}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        showAlert(data.message, 'success');
                        closeUserRolesModal();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert(data.message || 'حدث خطأ أثناء تحديث الأدوار', 'error');
                        if (data.errors) {
                            console.error('Validation errors:', data.errors);
                        }
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    showAlert(`خطأ في التحديث: ${error.message}`, 'error');
                });
        });

        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/settings/permissions', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        closePermissionModal();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        if (data.errors) {
                            let errorMessages = Object.values(data.errors).flat().join('\n');
                            showAlert(errorMessages, 'error');
                        } else {
                            showAlert(data.message || 'حدث خطأ أثناء إنشاء الصلاحية', 'error');
                        }
                    }
                })
                .catch(error => {
                    showAlert('حدث خطأ أثناء إنشاء الصلاحية', 'error');
                });
        });

        // Alert function
        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 left-4 right-4 p-4 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
            alertDiv.textContent = message;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        // Permission Categories Modal Functions
        function openPermissionCategoriesModal() {
            loadPermissionCategories();
            document.getElementById('permissionCategoriesModal').classList.remove('hidden');
            document.getElementById('permissionCategoriesModal').classList.add('flex');
        }

        function closePermissionCategoriesModal() {
            document.getElementById('permissionCategoriesModal').classList.add('hidden');
            document.getElementById('permissionCategoriesModal').classList.remove('flex');
        }

        function loadPermissionCategories() {
            // Get unique categories from existing permissions
            const categories = new Set();

            // Add default categories
            const defaultCategories = [
                'المستخدمون', 'الموظفون', 'المعدات', 'المخازن', 'المواد',
                'المحروقات', 'المستندات', 'المشاريع', 'التقارير', 'الإعدادات', 'عام', 'أخرى'
            ];

            defaultCategories.forEach(cat => categories.add(cat));

            // Get categories from DOM (existing permissions display)
            document.querySelectorAll('#permissions-content h3').forEach(header => {
                if (header.textContent.trim()) {
                    categories.add(header.textContent.trim());
                }
            });

            const categoriesList = document.getElementById('categoriesList');
            categoriesList.innerHTML = '';

            Array.from(categories).sort().forEach(category => {
                const categoryDiv = document.createElement('div');
                categoryDiv.className =
                    'flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg';
                categoryDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-tag text-purple-500 ml-2"></i>
                        <span class="font-medium text-gray-900">${category}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="editCategory('${category}')" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteCategory('${category}')" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                categoriesList.appendChild(categoryDiv);
            });
        }

        function editCategory(oldName) {
            const newName = prompt('تعديل اسم الفئة:', oldName);
            if (newName && newName !== oldName) {
                // Here you would typically make an API call to update the category
                showAlert(`تم تعديل الفئة من "${oldName}" إلى "${newName}"`, 'success');
                loadPermissionCategories();

                // Update the permission category dropdown
                updatePermissionCategoryDropdown();
            }
        }

        function deleteCategory(categoryName) {
            if (confirm(
                    `هل أنت متأكد من حذف فئة "${categoryName}"؟\nسيتم نقل جميع الصلاحيات في هذه الفئة إلى فئة "أخرى".`)) {
                // Here you would typically make an API call to delete the category
                showAlert(`تم حذف فئة "${categoryName}"`, 'success');
                loadPermissionCategories();

                // Update the permission category dropdown
                updatePermissionCategoryDropdown();
            }
        }

        function updatePermissionCategoryDropdown() {
            const select = document.getElementById('permissionCategory');
            const currentCategories = new Set();

            // Collect current categories
            document.querySelectorAll('#categoriesList span.font-medium').forEach(span => {
                currentCategories.add(span.textContent);
            });

            // Clear and rebuild select options
            select.innerHTML = '<option value="">اختر الفئة...</option>';
            Array.from(currentCategories).sort().forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                select.appendChild(option);
            });
        }

        // Handle new category form submission
        document.getElementById('newCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const categoryName = document.getElementById('newCategoryName').value.trim();
            if (categoryName) {
                // Check if category already exists
                const existingCategories = Array.from(document.querySelectorAll('#categoriesList span.font-medium'))
                    .map(span => span.textContent);

                if (existingCategories.includes(categoryName)) {
                    showAlert('هذه الفئة موجودة بالفعل', 'error');
                    return;
                }

                // Here you would typically make an API call to create the category
                showAlert(`تم إضافة فئة "${categoryName}" بنجاح`, 'success');

                // Clear the input
                document.getElementById('newCategoryName').value = '';

                // Reload categories
                loadPermissionCategories();

                // Update the permission category dropdown
                updatePermissionCategoryDropdown();
            }
        });

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target === document.getElementById('roleModal')) {
                closeRoleModal();
            }
            if (e.target === document.getElementById('userRolesModal')) {
                closeUserRolesModal();
            }
            if (e.target === document.getElementById('permissionModal')) {
                closePermissionModal();
            }
            if (e.target === document.getElementById('permissionCategoriesModal')) {
                closePermissionCategoriesModal();
            }
        });

        // User Search and Filter Functions
        document.getElementById('userSearch').addEventListener('input', function() {
            const clearBtn = document.getElementById('clearSearch');
            if (this.value.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
            filterUsers();
        });

        document.getElementById('clearSearch').addEventListener('click', function() {
            const searchInput = document.getElementById('userSearch');
            searchInput.value = '';
            searchInput.focus();
            this.classList.add('hidden');

            // Remove all highlights and reset display
            document.querySelectorAll('.user-row').forEach(row => {
                removeHighlight(row);
                row.style.display = '';
            });

            filterUsers();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+F to focus search
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                document.getElementById('userSearch').focus();
            }

            // Escape to clear search
            if (e.key === 'Escape' && document.getElementById('userSearch') === document.activeElement) {
                document.getElementById('clearSearch').click();
            }
        });

        function filterUsers() {
            const searchTerm = document.getElementById('userSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.user-row');
            const activeRoleFilter = document.querySelector('.role-filter-btn.active').textContent.trim();

            let visibleCount = 0;

            rows.forEach(row => {
                const userName = row.dataset.userName;
                const userEmail = row.dataset.userEmail;
                const userRoles = row.dataset.userRoles;

                // Check search term match
                const matchesSearch = userName.includes(searchTerm) || userEmail.includes(searchTerm);

                // Check role filter
                let matchesRole = true;
                if (activeRoleFilter !== 'جميع المستخدمين') {
                    if (activeRoleFilter === 'بدون أدوار') {
                        matchesRole = userRoles === '';
                    } else {
                        // Find the role name from display name
                        const roleButtons = document.querySelectorAll('.role-filter-btn');
                        let targetRoleName = '';
                        roleButtons.forEach(btn => {
                            if (btn.textContent.trim().startsWith(activeRoleFilter) && btn.onclick) {
                                const onclickStr = btn.onclick.toString();
                                const match = onclickStr.match(/filterUsersByRole\('([^']+)'\)/);
                                if (match) targetRoleName = match[1];
                            }
                        });
                        matchesRole = userRoles.includes(targetRoleName);
                    }
                }

                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                    visibleCount++;

                    // Highlight search term
                    if (searchTerm && searchTerm.length > 0) {
                        highlightSearchTerm(row, searchTerm);
                    } else {
                        removeHighlight(row);
                    }
                } else {
                    row.style.display = 'none';
                }
            });

            // Update count
            document.getElementById('usersCount').textContent = visibleCount;

            // Show/hide empty state
            showEmptyState(visibleCount === 0);
        }

        function highlightSearchTerm(row, searchTerm) {
            const nameCell = row.querySelector('.text-sm.font-medium');
            const emailCell = row.cells[1];

            if (nameCell && nameCell.dataset.originalText) {
                nameCell.innerHTML = highlightText(nameCell.dataset.originalText, searchTerm);
            } else if (nameCell) {
                nameCell.dataset.originalText = nameCell.textContent;
                nameCell.innerHTML = highlightText(nameCell.textContent, searchTerm);
            }

            if (emailCell && emailCell.dataset.originalText) {
                emailCell.innerHTML = highlightText(emailCell.dataset.originalText, searchTerm);
            } else if (emailCell) {
                emailCell.dataset.originalText = emailCell.textContent;
                emailCell.innerHTML = highlightText(emailCell.textContent, searchTerm);
            }
        }

        function removeHighlight(row) {
            const nameCell = row.querySelector('.text-sm.font-medium');
            const emailCell = row.cells[1];

            if (nameCell && nameCell.dataset.originalText) {
                nameCell.innerHTML = nameCell.dataset.originalText;
            }

            if (emailCell && emailCell.dataset.originalText) {
                emailCell.innerHTML = emailCell.dataset.originalText;
            }
        }

        function highlightText(text, searchTerm) {
            if (!searchTerm || searchTerm.length === 0) return text;

            const regex = new RegExp(`(${searchTerm})`, 'gi');
            return text.replace(regex, '<span class="search-highlight">$1</span>');
        }

        function filterUsersByRole(roleFilter) {
            // Update active button
            document.querySelectorAll('.role-filter-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-blue-100', 'text-blue-800');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });

            event.target.classList.remove('bg-gray-100', 'text-gray-700');
            event.target.classList.add('active', 'bg-blue-100', 'text-blue-800');

            // Clear search input
            document.getElementById('userSearch').value = '';

            // Apply filter
            filterUsers();
        }

        function showEmptyState(show) {
            let emptyState = document.getElementById('usersEmptyState');

            if (show && !emptyState) {
                emptyState = document.createElement('tr');
                emptyState.id = 'usersEmptyState';
                emptyState.innerHTML = `
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-search text-4xl text-gray-300 mb-2"></i>
                            <p class="text-lg font-medium">لا توجد نتائج</p>
                            <p class="text-sm">جرب البحث بكلمات أخرى أو اختر مرشح مختلف</p>
                        </div>
                    </td>
                `;
                document.getElementById('usersTableBody').appendChild(emptyState);
            } else if (!show && emptyState) {
                emptyState.remove();
            }
        }
    </script>
@endpush
