<div class="fixed right-0 top-0 h-full w-64 bg-white shadow-xl border-l border-gray-100 z-50 flex flex-col">
    <!-- Header -->
    <div class="p-6 border-b border-gray-100 flex-shrink-0">
        <div class="flex items-center space-x-3 space-x-reverse">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl flex items-center justify-center">
                <i class="ri-building-4-line text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900">شركة الأبراج</h1>
                <p class="text-sm text-gray-500">للمقاولات العامة</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-2">
        @php
        $menuItems = [
            [
                'id' => 'dashboard',
                'title' => 'لوحة التحكم الرئيسية',
                'icon' => 'ri-dashboard-line',
                'route' => 'dashboard',
                'color' => 'from-blue-600 to-blue-700'
            ],
            [
                'id' => 'employees',
                'title' => 'إدارة الموظفين',
                'icon' => 'ri-group-line',
                'route' => 'employees.index',
                'color' => 'from-emerald-600 to-emerald-700'
            ],
            [
                'id' => 'equipment',
                'title' => 'إدارة المعدات',
                'icon' => 'ri-tools-line',
                'route' => 'equipment.index',
                'color' => 'from-orange-600 to-orange-700'
            ],
            [
                'id' => 'locations',
                'title' => 'إدارة المواقع',
                'icon' => 'ri-map-pin-line',
                'route' => 'locations.index',
                'color' => 'from-red-600 to-red-700'
            ],
            [
                'id' => 'documents',
                'title' => 'إدارة المستندات',
                'icon' => 'ri-folder-line',
                'route' => 'documents.index',
                'color' => 'from-purple-600 to-purple-700'
            ],
            [
                'id' => 'transport',
                'title' => 'حركة النقليات',
                'icon' => 'ri-truck-line',
                'route' => 'transport.index',
                'color' => 'from-teal-600 to-teal-700'
            ],
            [
                'id' => 'external-trucks',
                'title' => 'شاحنات النقل الخارجي',
                'icon' => 'ri-truck-fill',
                'route' => 'external-trucks.index',
                'color' => 'from-cyan-600 to-cyan-700'
            ],
            [
                'id' => 'internal-trucks',
                'title' => 'شاحنات النقل الداخلي',
                'icon' => 'ri-truck-line',
                'route' => 'internal-trucks.index',
                'color' => 'from-blue-600 to-blue-700'
            ],
            [
                'id' => 'finance',
                'title' => 'المالية والفواتير',
                'icon' => 'ri-money-dollar-circle-line',
                'route' => 'finance.index',
                'color' => 'from-yellow-600 to-amber-700'
            ],
            [
                'id' => 'projects',
                'title' => 'إدارة المشاريع',
                'icon' => 'ri-building-line',
                'route' => 'projects.index',
                'color' => 'from-indigo-600 to-indigo-700'
            ],
            [
                'id' => 'correspondences',
                'title' => 'الاتصالات الإدارية',
                'icon' => 'ri-mail-line',
                'route' => 'correspondences.index',
                'color' => 'from-pink-600 to-pink-700'
            ],
            [
                'id' => 'my-tasks',
                'title' => 'المعاملات الخاصة',
                'icon' => 'ri-task-line',
                'route' => 'my-tasks.index',
                'color' => 'from-purple-600 to-purple-700'
            ],
            [
                'id' => 'suppliers',
                'title' => 'إدارة الموردين',
                'icon' => 'ri-truck-line',
                'route' => 'suppliers.index',
                'color' => 'from-green-600 to-green-700'
            ],
            [
                'id' => 'settings',
                'title' => 'الإعدادات',
                'icon' => 'ri-settings-line',
                'route' => 'settings.index',
                'color' => 'from-gray-600 to-gray-700'
            ]
        ];
        @endphp

        @foreach($menuItems as $item)
        <a href="{{ route($item['route']) }}"
           class="sidebar-item block w-full p-4 rounded-xl transition-all duration-200 group {{ request()->routeIs($item['route'].'*') ? 'bg-gradient-to-r '.$item['color'].' text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50' }}">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="{{ $item['icon'] }} text-xl {{ request()->routeIs($item['route'].'*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                </div>
                <div class="mr-4">
                    <div class="font-medium text-sm">{{ $item['title'] }}</div>
                </div>
                <div class="mr-auto">
                    <i class="ri-arrow-left-s-line text-sm {{ request()->routeIs($item['route'].'*') ? 'text-white/70' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                </div>
            </div>
        </a>
        @endforeach
    </nav>

    <!-- User Info & Logout -->
    @auth
    <div class="flex-shrink-0 p-4 border-t border-gray-100">
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-3 mb-3 border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                    <i class="ri-user-line text-white text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-600 truncate">
                        @switch(Auth::user()->role)
                            @case('admin')
                                مدير النظام
                                @break
                            @case('manager')
                                مدير المشاريع
                                @break
                            @case('finance')
                                مدير المالية
                                @break
                            @case('employee')
                                موظف ميداني
                                @break
                            @default
                                موظف
                        @endswitch
                    </p>
                </div>
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm font-medium border border-red-200 hover:border-red-300 hover:shadow-sm">
                <i class="ri-logout-box-line text-base"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
    @endauth

    <!-- Footer -->
    <div class="flex-shrink-0 border-t border-gray-100 p-3 bg-gray-50">
        <div class="text-center">
            <div class="text-xs text-gray-500 font-medium">© 2025 شركة الأبراج للمقاولات</div>
            <div class="text-xs text-gray-400">جميع الحقوق محفوظة</div>
        </div>
    </div>
</div>
