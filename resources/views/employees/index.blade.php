@extends('layouts.app')

@section('title', 'إدارة الموظفين - شركة الأبراج للمقاولات')

@section('content')
    <div class="space-y-3">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">إدارة الموظفين</h1>
                    <p class="text-gray-600 text-sm">إدارة شاملة لبيانات الموظفين والحضور والانصراف</p>
                </div>
                <div class="flex space-x-2 space-x-reverse no-print">
                    <a href="{{ route('employees.attendance') }}"
                        class="bg-gradient-to-r from-teal-600 to-teal-700 text-white px-4 py-2 rounded-lg font-medium hover:from-teal-700 hover:to-teal-800 transition-all duration-200 flex items-center text-sm">
                        <i class="ri-time-line ml-2"></i>
                        متابعة الحضور
                    </a>
                    <a href="{{ route('employees.create') }}"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center text-sm">
                        <i class="ri-user-add-line ml-2"></i>
                        إضافة موظف جديد
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded-lg text-sm">
                <div class="flex items-center">
                    <i class="ri-check-circle-line text-green-600 ml-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Employees Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header with Search and Filters -->
            <div class="p-4 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
                    <div class="flex items-center gap-4">
                        <h2 class="text-lg font-semibold text-gray-900">قائمة الموظفين</h2>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                {{ $employees->total() }} موظف
                            </span>
                            @if (request()->hasAny([
                                    'department',
                                    'role',
                                    'status',
                                    'sponsorship_status',
                                    'location_id',
                                    'has_user',
                                    'hire_date_from',
                                    'hire_date_to',
                                ]))
                                @php
                                    $activeFiltersCount = collect([
                                        'department',
                                        'role',
                                        'status',
                                        'sponsorship_status',
                                        'location_id',
                                        'has_user',
                                        'hire_date_from',
                                        'hire_date_to',
                                    ])
                                        ->filter(fn($filter) => request()->filled($filter))
                                        ->count();
                                @endphp
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full font-medium">
                                    {{ $activeFiltersCount }} فلتر نشط
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Search Box and Sort -->
                    <div class="flex-shrink-0">
                        <form method="GET" action="{{ route('employees.index') }}"
                            class="flex items-center space-x-2 space-x-reverse">
                            <!-- Sort dropdown -->
                            <div class="relative">
                                <select name="sort"
                                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                                    onchange="this.form.submit()">
                                    <option value="">ترتيب حسب</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>الاسم
                                        (أ-ي)</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>الاسم
                                        (ي-أ)</option>
                                    <option value="hire_date_desc"
                                        {{ request('sort') == 'hire_date_desc' ? 'selected' : '' }}>الأحدث توظيفاً</option>
                                    <option value="hire_date_asc"
                                        {{ request('sort') == 'hire_date_asc' ? 'selected' : '' }}>الأقدم توظيفاً</option>
                                    <option value="national_id_expiry_asc"
                                        {{ request('sort') == 'national_id_expiry_asc' ? 'selected' : '' }}>الأقرب انتهاء
                                        هوية</option>
                                    <option value="national_id_expiry_desc"
                                        {{ request('sort') == 'national_id_expiry_desc' ? 'selected' : '' }}>الأبعد انتهاء
                                        هوية</option>
                                    <option value="department_asc"
                                        {{ request('sort') == 'department_asc' ? 'selected' : '' }}>القسم (أ-ي)</option>
                                    <option value="role_asc" {{ request('sort') == 'role_asc' ? 'selected' : '' }}>الدور
                                        (أ-ي)</option>
                                </select>
                            </div>

                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="البحث عن موظف..."
                                    class="w-full sm:w-64 px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    autocomplete="off">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                            </div>

                            @if (request('search') || request('sort'))
                                <a href="{{ route('employees.index') }}"
                                    class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 flex-shrink-0"
                                    title="مسح البحث والترتيب">
                                    <i class="ri-close-line"></i>
                                </a>
                            @endif

                            <button type="submit"
                                class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 flex-shrink-0">
                                بحث
                            </button>

                            <!-- Export Button -->
                            <button type="button" onclick="window.print()"
                                class="px-4 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 flex items-center flex-shrink-0"
                                title="طباعة النتائج">
                                <i class="ri-printer-line ml-1"></i>
                                <span class="hidden sm:inline">طباعة</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="border-t border-gray-100 pt-4 no-print">
                    <form method="GET" action="{{ route('employees.index') }}" id="filterForm" class="space-y-4">
                        <!-- Preserve search and sort values -->
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if (request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <!-- Filter Toggle Button -->
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="toggleFilters()"
                                class="flex items-center text-sm text-gray-600 hover:text-gray-800 font-medium">
                                <i class="ri-filter-line ml-2"></i>
                                الفلاتر المتقدمة
                                <i id="filterIcon" class="ri-arrow-down-s-line mr-1 transition-transform duration-200"></i>
                            </button>

                            @if (request()->hasAny([
                                    'department',
                                    'role',
                                    'status',
                                    'sponsorship_status',
                                    'location_id',
                                    'has_user',
                                    'hire_date_from',
                                    'hire_date_to',
                                ]))
                                <a href="{{ route('employees.index') }}"
                                    class="text-sm text-red-600 hover:text-red-800 flex items-center">
                                    <i class="ri-close-circle-line ml-1"></i>
                                    مسح جميع الفلاتر
                                </a>
                            @endif
                        </div>

                        <!-- Filters Container -->
                        <div id="filtersContainer"
                            class="space-y-4 {{ request()->hasAny(['department', 'role', 'status', 'sponsorship_status', 'location_id', 'has_user', 'hire_date_from', 'hire_date_to']) ? '' : 'hidden' }}">

                            <!-- Main Filters Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                                <!-- Department Filter -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">القسم</label>
                                    <select name="department"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="submitFilters()">
                                        <option value="">جميع الأقسام</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department }}"
                                                {{ request('department') == $department ? 'selected' : '' }}>
                                                {{ $department }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Role Filter -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">الدور الوظيفي</label>
                                    <select name="role"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="submitFilters()">
                                        <option value="">جميع الأدوار</option>
                                        @foreach ($roles as $roleName => $roleDisplayName)
                                            <option value="{{ $roleName }}"
                                                {{ request('role') == $roleName ? 'selected' : '' }}>
                                                {{ $roleDisplayName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status Filter -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">الحالة</label>
                                    <select name="status"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="submitFilters()">
                                        <option value="">جميع الحالات</option>
                                        <option value="نشط" {{ request('status') == 'نشط' ? 'selected' : '' }}>نشط
                                        </option>
                                        <option value="غير نشط" {{ request('status') == 'غير نشط' ? 'selected' : '' }}>غير
                                            نشط</option>
                                        <option value="معلق" {{ request('status') == 'معلق' ? 'selected' : '' }}>معلق
                                        </option>
                                        <option value="مفصول" {{ request('status') == 'مفصول' ? 'selected' : '' }}>مفصول
                                        </option>
                                    </select>
                                </div>

                                <!-- Sponsorship Filter -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">الكفالة</label>
                                    <select name="sponsorship_status"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="submitFilters()">
                                        <option value="">جميع الكفالات</option>
                                        @foreach ($sponsorshipStatuses as $sponsorship)
                                            <option value="{{ $sponsorship }}"
                                                {{ request('sponsorship_status') == $sponsorship ? 'selected' : '' }}>
                                                {{ $sponsorship }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Location Filter -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">الموقع</label>
                                    <select name="location_id"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="submitFilters()">
                                        <option value="">جميع المواقع</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}"
                                                {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- System Account Filter -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">حساب النظام</label>
                                    <select name="has_user"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="submitFilters()">
                                        <option value="">الكل</option>
                                        <option value="1" {{ request('has_user') == '1' ? 'selected' : '' }}>متصل
                                        </option>
                                        <option value="0" {{ request('has_user') == '0' ? 'selected' : '' }}>غير متصل
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Date Range Filter Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">تاريخ التوظيف</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <input type="date" name="hire_date_from"
                                                value="{{ request('hire_date_from') }}"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="من" onchange="submitFilters()">
                                        </div>
                                        <div>
                                            <input type="date" name="hire_date_to"
                                                value="{{ request('hire_date_to') }}"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="إلى" onchange="submitFilters()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Active Filters Display -->
                    @if (request()->hasAny([
                            'department',
                            'role',
                            'status',
                            'sponsorship_status',
                            'location_id',
                            'hire_date_from',
                            'hire_date_to',
                        ]))
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="text-xs text-gray-600 font-medium">الفلاتر النشطة:</span>

                            @if (request('department'))
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    القسم: {{ request('department') }}
                                    <a href="{{ request()->fullUrlWithQuery(['department' => null]) }}"
                                        class="mr-1 text-blue-600 hover:text-blue-800">
                                        <i class="ri-close-line"></i>
                                    </a>
                                </span>
                            @endif

                            @if (request('role'))
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    الدور: {{ $roles[request('role')] ?? request('role') }}
                                    <a href="{{ request()->fullUrlWithQuery(['role' => null]) }}"
                                        class="mr-1 text-green-600 hover:text-green-800">
                                        <i class="ri-close-line"></i>
                                    </a>
                                </span>
                            @endif

                            @if (request('status'))
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    الحالة: {{ request('status') }}
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"
                                        class="mr-1 text-yellow-600 hover:text-yellow-800">
                                        <i class="ri-close-line"></i>
                                    </a>
                                </span>
                            @endif

                            @if (request('sponsorship_status'))
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    الكفالة: {{ request('sponsorship_status') }}
                                    <a href="{{ request()->fullUrlWithQuery(['sponsorship_status' => null]) }}"
                                        class="mr-1 text-orange-600 hover:text-orange-800">
                                        <i class="ri-close-line"></i>
                                    </a>
                                </span>
                            @endif

                            @if (request('location_id'))
                                @php
                                    $selectedLocation = $locations->find(request('location_id'));
                                @endphp
                                @if ($selectedLocation)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        الموقع: {{ $selectedLocation->name }}
                                        <a href="{{ request()->fullUrlWithQuery(['location_id' => null]) }}"
                                            class="mr-1 text-purple-600 hover:text-purple-800">
                                            <i class="ri-close-line"></i>
                                        </a>
                                    </span>
                                @endif
                            @endif

                            @if (request('has_user'))
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                    الحساب: {{ request('has_user') == '1' ? 'متصل' : 'غير متصل' }}
                                    <a href="{{ request()->fullUrlWithQuery(['has_user' => null]) }}"
                                        class="mr-1 text-teal-600 hover:text-teal-800">
                                        <i class="ri-close-line"></i>
                                    </a>
                                </span>
                            @endif

                            @if (request('hire_date_from') || request('hire_date_to'))
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    التاريخ:
                                    @if (request('hire_date_from') && request('hire_date_to'))
                                        {{ request('hire_date_from') }} إلى {{ request('hire_date_to') }}
                                    @elseif(request('hire_date_from'))
                                        من {{ request('hire_date_from') }}
                                    @else
                                        حتى {{ request('hire_date_to') }}
                                    @endif
                                    <a href="{{ request()->fullUrlWithQuery(['hire_date_from' => null, 'hire_date_to' => null]) }}"
                                        class="mr-1 text-indigo-600 hover:text-indigo-800">
                                        <i class="ri-close-line"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                @if (request('search'))
                    <div class="mt-3 text-sm text-gray-600">
                        <span>نتائج البحث عن: </span>
                        <span class="font-medium text-gray-900">"{{ request('search') }}"</span>
                        <span class="mr-2">({{ $employees->total() }}
                            {{ $employees->total() == 1 ? 'نتيجة' : 'نتائج' }})</span>
                    </div>
                @endif
            </div>

            @if ($employees->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">
                                    الموظف</th>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px] hidden md:table-cell">
                                    المنصب</th>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px] hidden lg:table-cell">
                                    القسم</th>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                    الدور</th>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px] hidden xl:table-cell">
                                    الموقع</th>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px] hidden lg:table-cell">
                                    حساب النظام</th>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px] hidden md:table-cell">
                                    انتهاء الهوية</th>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">
                                    الحالة</th>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                    الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($employees as $employee)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span
                                                    class="text-white font-medium text-xs">{{ mb_substr($employee->name, 0, 1) }}</span>
                                            </div>
                                            <div class="mr-3 min-w-0">
                                                <div class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $employee->name }}</div>
                                                <div class="text-xs text-gray-500 truncate">{{ $employee->email }}</div>
                                                <!-- Mobile info -->
                                                <div class="md:hidden text-xs text-gray-600 mt-1">
                                                    <div>{{ $employee->position }}</div>
                                                    <div>
                                                        @if ($employee->national_id_expiry)
                                                            @php
                                                                $status = $employee->getDocumentStatus(
                                                                    'national_id_expiry',
                                                                );
                                                            @endphp
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $status['class'] }}">
                                                                {{ $employee->national_id_expiry->format('Y/m/d') }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400">هوية غير محددة</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                                        {{ $employee->position }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                                        {{ $employee->department }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if ($employee->role === 'مسئول رئيسي') bg-purple-100 text-purple-800
                                @elseif($employee->role === 'مهندس') bg-blue-100 text-blue-800
                                @elseif($employee->role === 'إداري') bg-indigo-100 text-indigo-800
                                @elseif($employee->role === 'مشرف موقع') bg-orange-100 text-orange-800
                                @elseif($employee->role === 'عامل') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800 @endif">
                                            {{ $employee->role ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap hidden xl:table-cell">
                                        @if ($employee->location)
                                            <div class="flex items-center">
                                                <i class="ri-map-pin-line text-green-600 text-sm ml-1"></i>
                                                <span class="text-sm text-gray-900">{{ $employee->location->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">غير محدد</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap hidden lg:table-cell">
                                        @if ($employee->user)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="ri-check-circle-line mr-1"></i>
                                                متصل
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="ri-close-circle-line mr-1"></i>
                                                غير متصل
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap hidden md:table-cell">
                                        @if ($employee->national_id_expiry)
                                            @php
                                                $status = $employee->getDocumentStatus('national_id_expiry');
                                            @endphp
                                            <div class="flex flex-col items-start">
                                                <span
                                                    class="text-sm text-gray-900">{{ $employee->national_id_expiry->format('Y/m/d') }}</span>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $status['class'] }} mt-1">
                                                    {{ $status['status'] }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">غير محددة</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $employee->status === 'active' ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-1 space-x-reverse">
                                            <a href="{{ route('employees.show', $employee) }}"
                                                class="text-blue-600 hover:text-blue-900 p-1" title="عرض">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('employees.edit', $employee) }}"
                                                class="text-indigo-600 hover:text-indigo-900 p-1" title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 p-1"
                                                    title="حذف">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $employees->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    @if (request('search'))
                        <i class="ri-search-line text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد نتائج</h3>
                        <p class="text-gray-500 mb-6">لم يتم العثور على موظفين مطابقين لكلمة البحث
                            "{{ request('search') }}"</p>
                        <div class="flex justify-center space-x-3 space-x-reverse">
                            <a href="{{ route('employees.index') }}"
                                class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 inline-flex items-center">
                                <i class="ri-arrow-right-line ml-2"></i>
                                عرض جميع الموظفين
                            </a>
                            <a href="{{ route('employees.create') }}"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 inline-flex items-center">
                                <i class="ri-user-add-line ml-2"></i>
                                إضافة موظف جديد
                            </a>
                        </div>
                    @else
                        <i class="ri-user-line text-4xl text-gray-300 mb-3"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">لا يوجد موظفين</h3>
                        <p class="text-gray-500 mb-4 text-sm">ابدأ بإضافة موظف جديد لإدارة فريق العمل</p>
                        <a href="{{ route('employees.create') }}"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 inline-flex items-center text-sm">
                            <i class="ri-user-add-line ml-2"></i>
                            إضافة موظف جديد
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-submit search form when typing (with debounce)
            let searchTimeout;
            const searchInput = document.querySelector('input[name="search"]');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (this.value.length >= 2 || this.value.length === 0) {
                            this.form.submit();
                        }
                    }, 500); // Wait 500ms after user stops typing
                });

                // Focus search input when pressing "/" key
                document.addEventListener('keydown', function(e) {
                    if (e.key === '/' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT') {
                        e.preventDefault();
                        searchInput.focus();
                    }
                });
            }

            // Filters functionality
            function toggleFilters() {
                const container = document.getElementById('filtersContainer');
                const icon = document.getElementById('filterIcon');

                if (container.classList.contains('hidden')) {
                    container.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    container.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            }

            function submitFilters() {
                document.getElementById('filterForm').submit();
            }

            // Show filters if any are active
            document.addEventListener('DOMContentLoaded', function() {
                const hasActiveFilters =
                    {{ request()->hasAny(['department', 'role', 'status', 'location_id', 'has_user', 'hire_date_from', 'hire_date_to']) ? 'true' : 'false' }};
                if (hasActiveFilters) {
                    const container = document.getElementById('filtersContainer');
                    const icon = document.getElementById('filterIcon');
                    container.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                }
            });
        </script>

        <style>
            /* Prevent horizontal scroll */
            body {
                overflow-x: hidden;
            }

            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }

            /* Hide scrollbar but keep functionality */
            .overflow-x-auto::-webkit-scrollbar {
                height: 6px;
            }

            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 3px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }

            @media print {
                .no-print {
                    display: none !important;
                }

                .print-only {
                    display: block !important;
                }

                .bg-gray-50 {
                    background-color: #f9f9f9 !important;
                }

                .shadow-sm,
                .shadow-md,
                .shadow-lg {
                    box-shadow: none !important;
                }

                .border-gray-100 {
                    border-color: #e5e7eb !important;
                }

                .hidden {
                    display: table-cell !important;
                }
            }
        </style>
    @endpush
@endsection
