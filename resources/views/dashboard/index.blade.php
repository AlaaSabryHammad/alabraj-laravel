@extends('layouts.app')

@section('title', 'لوحة التحكم الرئيسية - شركة الأبراج للمقاولات')

@section('content')
    <div class="space-y-6 animate-fadeIn">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        مرحباً بك، {{ Auth::user()->name }}
                    </h1>
                    <p class="text-gray-600">نظرة شاملة على أداء شركة الأبراج للمقاولات</p>
                    <div class="mt-3 flex items-center gap-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="ri-check-line ml-1"></i>
                            متصل
                        </span>
                        <span class="text-sm text-gray-500">
                            آخر دخول: {{ Auth::user()->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                <div class="text-left">
                    <div class="text-2xl font-bold text-blue-600" id="current-time"></div>
                    <div class="text-gray-500" id="current-date"></div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Employees -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium mb-1">إجمالي الموظفين</p>
                        <div class="flex items-end space-x-2 space-x-reverse">
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_employees'] }}</h3>
                            <span class="text-green-600 text-sm font-medium">+12%</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl">
                        <i class="ri-group-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Available Equipment -->
            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-2xl p-6 border border-emerald-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-emerald-600 text-sm font-medium mb-1">المعدات المتاحة</p>
                        <div class="flex items-end space-x-2 space-x-reverse">
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['available_equipment'] }}</h3>
                            <span class="text-green-600 text-sm font-medium">+5%</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-3 rounded-xl">
                        <i class="ri-tools-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl p-6 border border-orange-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-orange-600 text-sm font-medium mb-1">المشاريع النشطة</p>
                        <div class="flex items-end space-x-2 space-x-reverse">
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['active_projects'] }}</h3>
                            <span class="text-green-600 text-sm font-medium">+8%</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-3 rounded-xl">
                        <i class="ri-building-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-2xl p-6 border border-amber-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-amber-600 text-sm font-medium mb-1">إيرادات الشهر</p>
                        <div class="flex items-end space-x-2 space-x-reverse">
                            <h3 class="text-3xl font-bold text-gray-900">
                                {{ number_format($stats['monthly_revenue'] / 1000000, 1) }}M ر.س</h3>
                            <span class="text-green-600 text-sm font-medium">+15%</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-3 rounded-xl">
                        <i class="ri-money-dollar-circle-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Daily Trips -->
            <div class="bg-gradient-to-r from-teal-50 to-teal-100 rounded-2xl p-6 border border-teal-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-teal-600 text-sm font-medium mb-1">رحلات اليوم</p>
                        <div class="flex items-end space-x-2 space-x-reverse">
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['daily_trips'] }}</h3>
                            <span class="text-green-600 text-sm font-medium">+3%</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-3 rounded-xl">
                        <i class="ri-truck-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- New Documents -->
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-medium mb-1">المستندات الجديدة</p>
                        <div class="flex items-end space-x-2 space-x-reverse">
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['new_documents'] }}</h3>
                            <span class="text-green-600 text-sm font-medium">+22%</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-3 rounded-xl">
                        <i class="ri-folder-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <a href="javascript:void(0)" onclick="scrollToNotifications()" class="bg-gradient-to-r from-rose-50 to-rose-100 rounded-2xl p-6 border border-rose-200 hover:border-rose-300 hover:shadow-md transition-all duration-200 cursor-pointer">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-rose-600 text-sm font-medium mb-1">الإشعارات</p>
                        <div class="flex items-end space-x-2 space-x-reverse">
                            <h3 class="text-3xl font-bold text-gray-900" id="notificationCount">0</h3>
                            <span class="text-rose-600 text-sm font-medium">جديد</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-rose-500 to-rose-600 p-3 rounded-xl relative">
                        <i class="ri-notification-fill text-white text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center" id="notificationBadge" style="display: none;">0</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Financial Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">التحليل المالي (آخر 6 أشهر)</h2>
                <div class="chart-container">
                    <canvas id="financialChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">النشاطات الأخيرة</h2>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="ri-time-line ml-1"></i>
                        آخر التحديثات
                    </div>
                </div>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($recentActivities as $activity)
                        <div
                            class="flex items-start space-x-3 space-x-reverse p-4 rounded-xl hover:bg-gray-50 transition-all duration-200 border border-gray-100 hover:border-gray-200">
                            <div class="flex-shrink-0">
                                @php
                                    $colorClasses = [
                                        'blue' => 'bg-blue-100 text-blue-600',
                                        'green' => 'bg-green-100 text-green-600',
                                        'purple' => 'bg-purple-100 text-purple-600',
                                        'orange' => 'bg-orange-100 text-orange-600',
                                        'yellow' => 'bg-yellow-100 text-yellow-600',
                                        'indigo' => 'bg-indigo-100 text-indigo-600',
                                        'cyan' => 'bg-cyan-100 text-cyan-600',
                                        'red' => 'bg-red-100 text-red-600',
                                    ];
                                    $colorClass =
                                        $colorClasses[$activity['color'] ?? 'blue'] ?? 'bg-blue-100 text-blue-600';
                                @endphp
                                <div
                                    class="w-10 h-10 rounded-full {{ $colorClass }} flex items-center justify-center shadow-sm">
                                    <i class="{{ $activity['icon'] }} text-lg"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 leading-relaxed">{{ $activity['message'] }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        {{ $activity['time'] }}
                                    </span>
                                    @if (isset($activity['type']))
                                        <span class="text-xs text-gray-400 mr-2">
                                            @switch($activity['type'])
                                                @case('employee')
                                                    الموظفون
                                                @break

                                                @case('equipment')
                                                    المعدات
                                                @break

                                                @case('project')
                                                    المشاريع
                                                @break

                                                @case('material')
                                                    المواد
                                                @break

                                                @case('maintenance')
                                                    الصيانة
                                                @break

                                                @case('document')
                                                    المستندات
                                                @break

                                                @case('warehouse')
                                                    المخازن
                                                @break

                                                @case('user')
                                                    المستخدمون
                                                @break

                                                @default
                                                    عام
                                            @endswitch
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div
                                    class="w-2 h-2 rounded-full {{ str_replace(['100', 'text-'], ['500', 'bg-'], $colorClass) }}">
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ri-notification-off-line text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">لا توجد أنشطة حديثة</p>
                                <p class="text-gray-400 text-sm mt-1">ستظهر الأنشطة الجديدة هنا عند حدوثها</p>
                            </div>
                        @endforelse
                    </div>
                    @if (count($recentActivities) > 0)
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-center">
                                <button onclick="showAllActivities()"
                                    class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center transition-colors">
                                    <span>عرض جميع النشاطات</span>
                                    <i class="ri-arrow-left-s-line mr-1"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>

        <!-- Notifications Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" id="notificationsSection">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="ri-notification-fill text-rose-500"></i>
                    الإشعارات والتنبيهات
                </h2>
                <button onclick="refreshNotifications()" class="text-gray-500 hover:text-gray-700 transition-colors p-2 hover:bg-gray-100 rounded-lg" title="تحديث">
                    <i class="ri-refresh-line text-xl"></i>
                </button>
            </div>
            <div id="notificationsList" class="space-y-3 max-h-96 overflow-y-auto">
                <div class="text-center py-8">
                    <i class="ri-loader-4-line text-2xl text-gray-300 animate-spin"></i>
                    <p class="text-gray-500 mt-2">جاري تحميل الإشعارات...</p>
                </div>
            </div>
        </div>

        <!-- All Activities Modal -->
        <div id="activitiesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900">جميع النشاطات الأخيرة</h3>
                        <button onclick="closeActivitiesModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="ri-close-line text-2xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto max-h-[70vh]">
                    <div id="allActivitiesList" class="space-y-4">
                        <!-- Activities will be loaded here -->
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-end">
                        <button onclick="closeActivitiesModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            إغلاق
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>

        @push('scripts')
            <script>
                // Update time and date
                function updateDateTime() {
                    const now = new Date();
                    const timeOptions = {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    };
                    const dateOptions = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };

                    document.getElementById('current-time').textContent = now.toLocaleTimeString('ar-SA', timeOptions);
                    document.getElementById('current-date').textContent = now.toLocaleDateString('ar-SA', dateOptions);
                }

                updateDateTime();
                setInterval(updateDateTime, 1000);

                // Load notifications
                function loadNotifications() {
                    // Simulate notifications - في المستقبل سيتم جلبها من قاعدة البيانات
                    const sampleNotifications = [
                        {
                            id: 1,
                            title: 'إشعار بصيانة معدة',
                            message: 'المعدة #5 تحتاج إلى صيانة دورية',
                            type: 'maintenance',
                            time: 'منذ 5 دقائق',
                            icon: 'ri-tools-line',
                            color: 'orange',
                            read: false
                        },
                        {
                            id: 2,
                            title: 'موظف جديد انضم',
                            message: 'الموظف أحمد محمد انضم إلى فريق المشروع',
                            type: 'employee',
                            time: 'منذ ساعة',
                            icon: 'ri-user-add-line',
                            color: 'green',
                            read: false
                        },
                        {
                            id: 3,
                            title: 'مستند جديد تم رفعه',
                            message: 'تم رفع مستند جديد في المشروع الأساسي',
                            type: 'document',
                            time: 'منذ 2 ساعة',
                            icon: 'ri-file-add-line',
                            color: 'purple',
                            read: true
                        }
                    ];

                    const notificationsList = document.getElementById('notificationsList');
                    const notificationCount = document.getElementById('notificationCount');
                    const notificationBadge = document.getElementById('notificationBadge');

                    if (sampleNotifications.length > 0) {
                        const unreadCount = sampleNotifications.filter(n => !n.read).length;
                        notificationCount.textContent = unreadCount;
                        
                        if (unreadCount > 0) {
                            notificationBadge.textContent = unreadCount;
                            notificationBadge.style.display = 'flex';
                        }

                        let notificationsHTML = '';
                        sampleNotifications.forEach(notification => {
                            const colorClasses = {
                                'orange': 'bg-orange-100 text-orange-600 border-orange-200',
                                'green': 'bg-green-100 text-green-600 border-green-200',
                                'purple': 'bg-purple-100 text-purple-600 border-purple-200',
                                'blue': 'bg-blue-100 text-blue-600 border-blue-200',
                                'red': 'bg-red-100 text-red-600 border-red-200',
                            };
                            const colorClass = colorClasses[notification.color] || 'bg-blue-100 text-blue-600 border-blue-200';
                            
                            notificationsHTML += `
                                <div class="flex items-start space-x-3 space-x-reverse p-4 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-200 bg-${notification.read ? 'white' : 'gray-50'} hover:shadow-sm ${!notification.read ? 'border-l-4 border-l-rose-500' : ''}">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full ${colorClass} flex items-center justify-center shadow-sm border">
                                            <i class="${notification.icon} text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">${notification.title}</p>
                                        <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                                        <span class="text-xs text-gray-500 mt-2 inline-block">${notification.time}</span>
                                    </div>
                                    ${!notification.read ? '<div class="flex-shrink-0"><div class="w-2 h-2 bg-rose-500 rounded-full"></div></div>' : ''}
                                </div>
                            `;
                        });
                        
                        notificationsList.innerHTML = notificationsHTML;
                    } else {
                        notificationsList.innerHTML = `
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ri-notification-off-line text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">لا توجد إشعارات</p>
                                <p class="text-gray-400 text-sm mt-1">ستظهر الإشعارات الجديدة هنا</p>
                            </div>
                        `;
                    }
                }

                // Load notifications on page load
                loadNotifications();

                // Refresh notifications
                function refreshNotifications() {
                    loadNotifications();
                }

                // Scroll to notifications section
                function scrollToNotifications() {
                    const notificationsSection = document.getElementById('notificationsSection');
                    if (notificationsSection) {
                        notificationsSection.scrollIntoView({ behavior: 'smooth' });
                    }
                }

                // Financial Chart
                const ctx = document.getElementById('financialChart').getContext('2d');
                const chartData = @json($chartData);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.map(item => item.name),
                        datasets: [{
                                label: 'الإيرادات',
                                data: chartData.map(item => item.revenue),
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'المصروفات',
                                data: chartData.map(item => item.expenses),
                                borderColor: 'rgb(239, 68, 68)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return (value / 1000000).toFixed(1) + 'M';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' +
                                            (context.parsed.y / 1000000).toFixed(1) + 'M ر.س';
                                    }
                                }
                            }
                        }
                    }
                });

                // Activities Modal Functions
                function showAllActivities() {
                    // Show modal
                    document.getElementById('activitiesModal').classList.remove('hidden');
                    document.getElementById('activitiesModal').classList.add('flex');
                    document.body.style.overflow = 'hidden';

                    // Load all activities
                    loadAllActivities();
                }

                function closeActivitiesModal() {
                    document.getElementById('activitiesModal').classList.add('hidden');
                    document.getElementById('activitiesModal').classList.remove('flex');
                    document.body.style.overflow = 'auto';
                }

                function loadAllActivities() {
                    const activitiesList = document.getElementById('allActivitiesList');
                    activitiesList.innerHTML =
                        '<div class="text-center py-4"><i class="ri-loader-4-line text-2xl text-gray-400 animate-spin"></i><p class="text-gray-500 mt-2">جاري تحميل النشاطات...</p></div>';

                    // Get all activities from current page data
                    const allActivities = @json($recentActivities);

                    // Generate expanded activities HTML
                    let activitiesHTML = '';

                    if (allActivities.length > 0) {
                        allActivities.forEach(function(activity, index) {
                            const colorClasses = {
                                'blue': 'bg-blue-100 text-blue-600 border-blue-200',
                                'green': 'bg-green-100 text-green-600 border-green-200',
                                'purple': 'bg-purple-100 text-purple-600 border-purple-200',
                                'orange': 'bg-orange-100 text-orange-600 border-orange-200',
                                'yellow': 'bg-yellow-100 text-yellow-600 border-yellow-200',
                                'indigo': 'bg-indigo-100 text-indigo-600 border-indigo-200',
                                'cyan': 'bg-cyan-100 text-cyan-600 border-cyan-200',
                                'red': 'bg-red-100 text-red-600 border-red-200',
                            };

                            const colorClass = colorClasses[activity.color] || 'bg-blue-100 text-blue-600 border-blue-200';

                            const typeLabels = {
                                'employee': 'الموظفون',
                                'equipment': 'المعدات',
                                'project': 'المشاريع',
                                'material': 'المواد',
                                'maintenance': 'الصيانة',
                                'document': 'المستندات',
                                'warehouse': 'المخازن',
                                'user': 'المستخدمون'
                            };

                            const typeLabel = typeLabels[activity.type] || 'عام';

                            activitiesHTML += `
                                <div class="flex items-start space-x-3 space-x-reverse p-4 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-200 bg-white hover:shadow-sm">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full ${colorClass} flex items-center justify-center shadow-sm border">
                                            <i class="${activity.icon} text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 leading-relaxed mb-2">${activity.message}</p>
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                                ${activity.time}
                                            </span>
                                            <span class="text-xs text-gray-600 bg-gray-50 px-2 py-1 rounded-full border">
                                                ${typeLabel}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="text-xs text-gray-400 font-mono">#${index + 1}</div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        activitiesHTML = `
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ri-notification-off-line text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">لا توجد أنشطة لعرضها</p>
                                <p class="text-gray-400 text-sm mt-1">ستظهر الأنشطة الجديدة هنا عند حدوثها</p>
                            </div>
                        `;
                    }

                    // Update the activities list
                    setTimeout(() => {
                        activitiesList.innerHTML = activitiesHTML;
                    }, 500);
                }

                // Close modal when clicking outside
                document.addEventListener('click', function(e) {
                    if (e.target === document.getElementById('activitiesModal')) {
                        closeActivitiesModal();
                    }
                });

                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeActivitiesModal();
                    }
                });
            </script>
        @endpush
    @endsection
