@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="fixed right-0 top-0 h-full w-64 bg-white shadow-xl border-l border-gray-100 z-50 flex flex-col">
    <!-- Header -->
    <div class="p-6 border-b border-gray-100 flex-shrink-0">
        <div class="flex items-center space-x-3 space-x-reverse">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center">
                <img src="{{ asset('assets/logo.png') }}" alt="شركة الأبراج" class="w-12 h-12 object-contain">
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
                    'color' => 'from-blue-600 to-blue-700',
                ],
                [
                    'id' => 'employees',
                    'title' => 'إدارة الموظفين',
                    'icon' => 'ri-group-line',
                    'route' => 'employees.index',
                    'color' => 'from-emerald-600 to-emerald-700',
                ],
                [
                    'id' => 'equipment',
                    'title' => 'إدارة المعدات',
                    'icon' => 'ri-tools-line',
                    'route' => 'equipment.index',
                    'color' => 'from-orange-600 to-orange-700',
                ],
                [
                    'id' => 'equipment-maintenance',
                    'title' => 'صيانة المعدات',
                    'icon' => 'ri-settings-2-line',
                    'route' => 'equipment-maintenance.index',
                    'color' => 'from-amber-600 to-amber-700',
                ],
                [
                    'id' => 'fuel-management',
                    'title' => 'إدارة المحروقات',
                    'icon' => 'ri-gas-station-line',
                    'route' => 'fuel-management.index',
                    'color' => 'from-violet-600 to-violet-700',
                ],
                [
                    'id' => 'locations',
                    'title' => 'إدارة المواقع',
                    'icon' => 'ri-map-pin-line',
                    'route' => 'locations.index',
                    'color' => 'from-red-600 to-red-700',
                ],
                [
                    'id' => 'documents',
                    'title' => 'إدارة المستندات',
                    'icon' => 'ri-folder-line',
                    'route' => 'documents.index',
                    'color' => 'from-purple-600 to-purple-700',
                ],
                [
                    'id' => 'transport',
                    'title' => 'حركة النقليات',
                    'icon' => 'ri-truck-line',
                    'route' => 'transport.index',
                    'color' => 'from-teal-600 to-teal-700',
                ],
                [
                    'id' => 'external-trucks',
                    'title' => 'شاحنات النقل الخارجي',
                    'icon' => 'ri-truck-fill',
                    'route' => 'external-trucks.index',
                    'color' => 'from-cyan-600 to-cyan-700',
                ],
                [
                    'id' => 'internal-trucks',
                    'title' => 'شاحنات النقل الداخلي',
                    'icon' => 'ri-truck-line',
                    'route' => 'internal-trucks.index',
                    'color' => 'from-blue-600 to-blue-700',
                ],
                [
                    'id' => 'finance',
                    'title' => 'المالية والفواتير',
                    'icon' => 'ri-money-dollar-circle-line',
                    'route' => 'finance.index',
                    'color' => 'from-yellow-600 to-amber-700',
                ],
                [
                    'id' => 'projects',
                    'title' => 'إدارة المشاريع',
                    'icon' => 'ri-building-line',
                    'route' => 'projects.index',
                    'color' => 'from-indigo-600 to-indigo-700',
                ],
                [
                    'id' => 'correspondences',
                    'title' => 'الاتصالات الإدارية',
                    'icon' => 'ri-mail-line',
                    'route' => 'correspondences.index',
                    'color' => 'from-pink-600 to-pink-700',
                ],
                [
                    'id' => 'warehouses',
                    'title' => 'إدارة المستودعات',
                    'icon' => 'ri-store-3-line',
                    'route' => 'warehouses.index',
                    'color' => 'from-orange-600 to-orange-700',
                ],
                [
                    'id' => 'suppliers',
                    'title' => 'إدارة الموردين',
                    'icon' => 'ri-truck-line',
                    'route' => 'suppliers.index',
                    'color' => 'from-green-600 to-green-700',
                ],
                [
                    'id' => 'spare-part-suppliers',
                    'title' => 'موردو قطع الغيار',
                    'icon' => 'ri-store-3-line',
                    'route' => 'spare-part-suppliers.index',
                    'color' => 'from-emerald-600 to-emerald-700',
                ],
                [
                    'id' => 'reports',
                    'title' => 'تقارير قطع الغيار',
                    'icon' => 'ri-bar-chart-line',
                    'route' => 'reports.spare-parts.index',
                    'color' => 'from-purple-600 to-purple-700',
                ],
                [
                    'id' => 'settings',
                    'title' => 'الإعدادات',
                    'icon' => 'ri-settings-line',
                    'route' => 'settings.index',
                    'color' => 'from-gray-600 to-gray-700',
                ],
            ];
        @endphp

        @foreach ($menuItems as $item)
            <a href="{{ route($item['route']) }}"
                class="sidebar-item block w-full p-4 rounded-xl transition-all duration-200 group {{ request()->routeIs($item['route'] . '*') ? 'bg-gradient-to-r ' . $item['color'] . ' text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i
                            class="{{ $item['icon'] }} text-xl {{ request()->routeIs($item['route'] . '*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    </div>
                    <div class="mr-4">
                        <div class="font-medium text-sm">{{ $item['title'] }}</div>
                    </div>
                    <div class="mr-auto">
                        <i
                            class="ri-arrow-left-s-line text-sm {{ request()->routeIs($item['route'] . '*') ? 'text-white/70' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                    </div>
                </div>
            </a>
        @endforeach
    </nav>

    <!-- User Info & Logout -->
    @auth
        <div class="flex-shrink-0 p-4 border-t border-gray-100">
            <a href="{{ route('profile.show') }}"
                class="block bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-3 mb-3 border border-gray-200 hover:shadow-md hover:border-blue-300 transition-all duration-200">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm hover:scale-105 transition-transform">
                        @if (Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                class="w-10 h-10 rounded-full object-cover border-2 border-white">
                        @else
                            <i class="ri-user-line text-white text-base"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate hover:text-blue-600 transition-colors">
                            {{ Auth::user()->name }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            <i class="ri-user-star-line text-blue-500 text-xs"></i>
                            <p class="text-xs font-medium text-blue-600 truncate bg-blue-50 px-2 py-0.5 rounded-full">
                                {{ Auth::user()->getCurrentRoleDisplayName() }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col items-center space-y-1">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <i class="ri-arrow-left-s-line text-gray-400 text-xs"></i>
                    </div>
                </div>
            </a>

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
