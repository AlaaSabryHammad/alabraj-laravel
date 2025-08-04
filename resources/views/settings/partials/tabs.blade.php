<!-- Settings Tabs -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8 space-x-reverse px-6" aria-label="Tabs">
            <a href="{{ route('settings.equipment-types') }}"
               class="{{ request()->routeIs('settings.equipment-types') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="ri-tools-line ml-2"></i>
                أنواع المعدات
            </a>

            <a href="{{ route('settings.location-types') }}"
               class="{{ request()->routeIs('settings.location-types') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="ri-map-pin-line ml-2"></i>
                أنواع المواقع
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
        @yield('tab-content')
    </div>
</div>
