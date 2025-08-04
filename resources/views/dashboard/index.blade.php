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
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
                        <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['monthly_revenue']/1000000, 1) }}M ر.س</h3>
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
            <h2 class="text-xl font-bold text-gray-900 mb-6">النشاطات الأخيرة</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3 space-x-reverse p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="{{ $activity['icon'] }} text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="ri-notification-off-line text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">لا توجد أنشطة حديثة</p>
                </div>
                @endforelse
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

    // Financial Chart
    const ctx = document.getElementById('financialChart').getContext('2d');
    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(item => item.name),
            datasets: [
                {
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
</script>
@endpush
@endsection
