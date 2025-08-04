@extends('layouts.app')

@section('title', 'تقرير الحضور الشهري - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">📊 تقرير الحضور الشهري</h1>
                <p class="text-blue-100">تقرير شامل ومفصل لحضور الموظفين من يناير 2024 حتى اليوم</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('employees.attendance') }}"
                   class="bg-white text-blue-600 px-6 py-3 rounded-xl font-medium hover:bg-blue-50 transition-all duration-200 flex items-center">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة لمتابعة الحضور
                </a>
                <button onclick="printReport()"
                        class="bg-blue-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-400 transition-all duration-200 flex items-center">
                    <i class="ri-printer-line ml-2"></i>
                    طباعة التقرير
                </button>
            </div>
        </div>
        <form action="{{ route('employees.attendance.report') }}" method="GET">
            <div class="flex items-center space-x-4 space-x-reverse bg-white/10 backdrop-blur-sm rounded-xl p-4">
                <div>
                    <label for="month" class="block text-sm font-medium text-blue-100 mb-2">الشهر</label>
                    <select name="month" id="month" class="w-full px-4 py-3 border-0 rounded-xl bg-white text-gray-900 focus:ring-2 focus:ring-blue-300">
                        @foreach(range(1, 12) as $m)
                            @php
                                $monthNames = [
                                    1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
                                    5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
                                    9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
                                ];
                            @endphp
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $monthNames[$m] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-blue-100 mb-2">السنة</label>
                    <select name="year" id="year" class="w-full px-4 py-3 border-0 rounded-xl bg-white text-gray-900 focus:ring-2 focus:ring-blue-300">
                        @foreach(range(2025, 2024) as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pt-7">
                    <button type="submit" class="bg-orange-500 text-white px-8 py-3 rounded-xl font-medium hover:bg-orange-600 transition-all duration-200 flex items-center shadow-lg">
                        <i class="ri-search-line ml-2"></i>
                        عرض التقرير
                    </button>
                </div>
            </div>
        </form>
    </div>

    @php
        $monthNames = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];
        $currentMonthName = isset($monthNames[$month]) ? $monthNames[$month] : '';

        // حساب إحصائيات إضافية
        $totalEmployees = $employees->count();
        $totalPresentDays = $employees->sum('present_days');
        $totalAbsentDays = $employees->sum('absent_days');
        $totalLateDays = $employees->sum('late_days');
        $totalLeaveDays = $employees->sum('leave_days');

        $overallAttendanceRate = $totalEmployees > 0 ? round(($totalPresentDays / ($totalEmployees * 22)) * 100, 1) : 0;
    @endphp

    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">إجمالي أيام الحضور</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalPresentDays) }}</h3>
                    <p class="text-xs text-green-600 mt-1">معدل الحضور: {{ $overallAttendanceRate }}%</p>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl">
                    <i class="ri-check-circle-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium mb-1">إجمالي أيام الغياب</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalAbsentDays) }}</h3>
                    <p class="text-xs text-red-600 mt-1">معدل الغياب: {{ $totalEmployees > 0 ? round(($totalAbsentDays / ($totalEmployees * 22)) * 100, 1) : 0 }}%</p>
                </div>
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-3 rounded-xl">
                    <i class="ri-close-circle-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium mb-1">إجمالي أيام التأخير</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalLateDays) }}</h3>
                    <p class="text-xs text-yellow-600 mt-1">معدل التأخير: {{ $totalEmployees > 0 ? round(($totalLateDays / ($totalEmployees * 22)) * 100, 1) : 0 }}%</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-3 rounded-xl">
                    <i class="ri-time-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">إجمالي أيام الإجازة</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalLeaveDays) }}</h3>
                    <p class="text-xs text-blue-600 mt-1">معدل الإجازات: {{ $totalEmployees > 0 ? round(($totalLeaveDays / ($totalEmployees * 22)) * 100, 1) : 0 }}%</p>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl">
                    <i class="ri-calendar-event-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium mb-1">إجمالي الموظفين</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalEmployees) }}</h3>
                    <p class="text-xs text-purple-600 mt-1">موظف نشط</p>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-3 rounded-xl">
                    <i class="ri-team-fill text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Report -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ri-calendar-check-line text-blue-600"></i>
                        تقرير شهر: {{ isset($monthNames[$month]) ? $monthNames[$month] : 'غير محدد' }} {{ $year }}
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">تفاصيل حضور وغياب جميع الموظفين لهذا الشهر</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                        {{ $totalEmployees }} موظف
                    </span>
                    <button onclick="exportToExcel()"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2 text-sm">
                        <i class="ri-file-excel-2-line"></i>
                        تصدير Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="attendanceTable">
                <thead class="bg-gradient-to-r from-gray-800 to-gray-900 text-white">
                    <tr>
                        <th class="px-6 py-4 text-right font-semibold">الموظف</th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-check-circle-line text-green-400 mr-1"></i>
                            أيام الحضور
                        </th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-close-circle-line text-red-400 mr-1"></i>
                            أيام الغياب
                        </th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-time-line text-yellow-400 mr-1"></i>
                            أيام التأخير
                        </th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-calendar-event-line text-blue-400 mr-1"></i>
                            أيام الإجازة
                        </th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-percent-line text-purple-400 mr-1"></i>
                            معدل الحضور
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($employees as $index => $employee)
                    @php
                        $attendanceRate = $employee->present_days > 0 ? round(($employee->present_days / ($employee->present_days + $employee->absent_days + $employee->late_days + $employee->leave_days)) * 100, 1) : 0;
                        $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-gray-50';
                    @endphp
                    <tr class="{{ $rowClass }} hover:bg-blue-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}"
                                         alt="{{ $employee->name }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">{{ mb_substr($employee->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $employee->position ?? 'غير محدد' }} - {{ $employee->department ?? 'غير محدد' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ $employee->present_days }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ (int)$employee->absent_days }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                {{ $employee->late_days }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $employee->leave_days }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center">
                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full"
                                         style="width: {{ $attendanceRate }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $attendanceRate }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer with summary -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($totalPresentDays) }}</div>
                    <div class="text-sm text-gray-600">إجمالي أيام الحضور</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-2xl font-bold text-red-600">{{ number_format($totalAbsentDays) }}</div>
                    <div class="text-sm text-gray-600">إجمالي أيام الغياب</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($totalLateDays) }}</div>
                    <div class="text-sm text-gray-600">إجمالي أيام التأخير</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($totalLeaveDays) }}</div>
                    <div class="text-sm text-gray-600">إجمالي أيام الإجازة</div>
                </div>
            </div>
        </div>
    </div>

    <!-- تقرير إضافي للتحليلات -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="ri-bar-chart-line text-blue-600"></i>
            تحليل أداء الحضور
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- أفضل 5 موظفين في الحضور -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6">
                <h4 class="font-semibold text-green-800 mb-4 flex items-center gap-2">
                    <i class="ri-trophy-line"></i>
                    أفضل 5 موظفين في الحضور
                </h4>
                <div class="space-y-3">
                    @foreach($employees->sortByDesc('present_days')->take(5) as $index => $employee)
                    <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <div class="font-medium text-gray-900">{{ $employee->name }}</div>
                                <div class="text-xs text-gray-500">{{ $employee->department ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        <span class="bg-green-500 text-white px-2 py-1 rounded-full text-sm font-medium">
                            {{ $employee->present_days }} يوم
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- الموظفين الذين يحتاجون متابعة -->
            <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-6">
                <h4 class="font-semibold text-red-800 mb-4 flex items-center gap-2">
                    <i class="ri-alert-line"></i>
                    الموظفين الذين يحتاجون متابعة
                </h4>
                <div class="space-y-3">
                    @foreach($employees->sortByDesc('absent_days')->take(5) as $index => $employee)
                    <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <div class="font-medium text-gray-900">{{ $employee->name }}</div>
                                <div class="text-xs text-gray-500">{{ $employee->department ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-sm font-medium">
                            {{ (int)$employee->absent_days }} غياب
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printReport() {
    window.print();
}

function exportToExcel() {
    // تصدير الجدول إلى Excel
    const table = document.getElementById('attendanceTable');
    const wb = XLSX.utils.table_to_book(table, {sheet: "تقرير الحضور"});
    const filename = `تقرير_الحضور_{{ isset($monthNames[$month]) ? $monthNames[$month] : 'غير_محدد' }}_{{ $year }}.xlsx`;
    XLSX.writeFile(wb, filename);
}

// إضافة مكتبة XLSX للتصدير
const script = document.createElement('script');
script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
document.head.appendChild(script);
</script>

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }

    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .bg-gradient-to-r {
        background: #f8f9fa !important;
    }
}
</style>
@endpush

@endsection
