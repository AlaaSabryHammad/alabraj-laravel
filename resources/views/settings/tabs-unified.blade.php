<!-- Unified Modern Settings Tabs -->
<div class="relative">
    <!-- Tab Navigation Container -->
    <div class="bg-white rounded-t-2xl shadow-sm border border-b-0 border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <nav class="flex space-x-1 px-6 py-0" role="tablist" aria-label="Settings Tabs">
                <!-- Equipment Types Tab -->
                <a href="#equipment-types" role="tab"
                    aria-selected="{{ request()->routeIs('settings.equipment-types') ? 'true' : 'false' }}"
                    class="group relative px-4 py-4 text-sm font-medium transition-all duration-200 inline-flex items-center whitespace-nowrap
                           {{ request()->routeIs('settings.equipment-types')
                               ? 'text-blue-600 border-b-2 border-blue-600'
                               : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300' }}">
                    <i class="ri-tools-line ml-2"></i>
                    <span>أنواع المعدات</span>
                    @if (isset($stats['equipmentTypes']))
                        <span
                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $stats['equipmentTypes'] }}
                        </span>
                    @endif
                </a>

                <!-- Location Types Tab -->
                <a href="#location-types" role="tab"
                    aria-selected="{{ request()->routeIs('settings.location-types') ? 'true' : 'false' }}"
                    class="group relative px-4 py-4 text-sm font-medium transition-all duration-200 inline-flex items-center whitespace-nowrap
                           {{ request()->routeIs('settings.location-types')
                               ? 'text-blue-600 border-b-2 border-blue-600'
                               : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300' }}">
                    <i class="ri-map-pin-line ml-2"></i>
                    <span>أنواع المواقع</span>
                    @if (isset($stats['locationTypes']))
                        <span
                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $stats['locationTypes'] }}
                        </span>
                    @endif
                </a>

                <!-- Materials Tab -->
                <a href="#materials" role="tab"
                    aria-selected="{{ request()->routeIs('settings.materials') ? 'true' : 'false' }}"
                    class="group relative px-4 py-4 text-sm font-medium transition-all duration-200 inline-flex items-center whitespace-nowrap
                           {{ request()->routeIs('settings.materials')
                               ? 'text-blue-600 border-b-2 border-blue-600'
                               : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300' }}">
                    <i class="ri-box-3-line ml-2"></i>
                    <span>إدارة المواد</span>
                    @if (isset($stats['materials']))
                        <span
                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $stats['materials'] }}
                        </span>
                    @endif
                </a>

                <!-- Suppliers Tab -->
                <a href="#suppliers" role="tab"
                    aria-selected="{{ request()->routeIs('suppliers.index') ? 'true' : 'false' }}"
                    class="group relative px-4 py-4 text-sm font-medium transition-all duration-200 inline-flex items-center whitespace-nowrap
                           {{ request()->routeIs('suppliers.index')
                               ? 'text-blue-600 border-b-2 border-blue-600'
                               : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300' }}">
                    <i class="ri-truck-line ml-2"></i>
                    <span>الموردون</span>
                    @if (isset($stats['suppliers']))
                        <span
                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                            {{ $stats['suppliers'] }}
                        </span>
                    @endif
                </a>

                <!-- Expense Categories Tab -->
                <a href="#expense-categories" role="tab"
                    aria-selected="{{ request()->routeIs('settings.expense-categories') ? 'true' : 'false' }}"
                    class="group relative px-4 py-4 text-sm font-medium transition-all duration-200 inline-flex items-center whitespace-nowrap
                           {{ request()->routeIs('settings.expense-categories')
                               ? 'text-blue-600 border-b-2 border-blue-600'
                               : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300' }}">
                    <i class="ri-money-dollar-circle-line ml-2"></i>
                    <span>فئات المصروفات</span>
                    @if (isset($stats['expenseCategories']))
                        <span
                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $stats['expenseCategories'] }}
                        </span>
                    @endif
                </a>

                <!-- Revenue Types Tab -->
                <a href="#revenue-types" role="tab"
                    aria-selected="{{ request()->routeIs('settings.revenue-types') ? 'true' : 'false' }}"
                    class="group relative px-4 py-4 text-sm font-medium transition-all duration-200 inline-flex items-center whitespace-nowrap
                           {{ request()->routeIs('settings.revenue-types')
                               ? 'text-blue-600 border-b-2 border-blue-600'
                               : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300' }}">
                    <i class="ri-hand-coin-line ml-2"></i>
                    <span>أنواع الإيرادات</span>
                    @if (isset($stats['revenueTypes']))
                        <span
                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            {{ $stats['revenueTypes'] }}
                        </span>
                    @endif
                </a>

                <!-- Roles & Permissions Tab -->
                <a href="{{ route('settings.roles-permissions') }}" role="tab"
                    class="group relative px-4 py-4 text-sm font-medium transition-all duration-200 inline-flex items-center whitespace-nowrap
                           {{ request()->routeIs('settings.roles-permissions')
                               ? 'text-blue-600 border-b-2 border-blue-600'
                               : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300' }}">
                    <i class="ri-shield-user-line ml-2"></i>
                    <span>الأدوار والصلاحيات</span>
                    @if (isset($stats['roles']))
                        <span
                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $stats['roles'] }}
                        </span>
                    @endif
                </a>
            </nav>
        </div>
    </div>

    <!-- Tab Content Container -->
    <div class="bg-white rounded-b-2xl shadow-sm border border-gray-100 border-t-0">
        <!-- Tab Content Area -->
        <div class="p-6 md:p-8">
            @yield('tab-content')
        </div>
    </div>
</div>

<style>
    /* Smooth tab transitions */
    [role="tab"] {
        position: relative;
    }

    [role="tab"]::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #1e40af);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
    }

    [role="tab"][aria-selected="true"]::after {
        transform: scaleX(1);
    }
</style>
