@extends('layouts.app')

@section('title', 'تقرير الحضور اليومي - ' . $selectedDate->format('Y/m/d'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">📋 تقرير الحضور اليومي</h1>
                <p class="text-purple-100">تقرير تفصيلي لحضور الموظفين ليوم 
                    @php
                        $arabicDays = [
                            'Monday' => 'الاثنين',
                            'Tuesday' => 'الثلاثاء', 
                            'Wednesday' => 'الأربعاء',
                            'Thursday' => 'الخميس',
                            'Friday' => 'الجمعة',
                            'Saturday' => 'السبت',
                            'Sunday' => 'الأحد'
                        ];
                        $arabicMonths = [
                            'January' => 'يناير', 'February' => 'فبراير', 'March' => 'مارس',
                            'April' => 'أبريل', 'May' => 'مايو', 'June' => 'يونيو',
                            'July' => 'يوليو', 'August' => 'أغسطس', 'September' => 'سبتمبر',
                            'October' => 'أكتوبر', 'November' => 'نوفمبر', 'December' => 'ديسمبر'
                        ];
                        $dayName = $arabicDays[$selectedDate->format('l')] ?? $selectedDate->format('l');
                        $monthName = $arabicMonths[$selectedDate->format('F')] ?? $selectedDate->format('F');
                    @endphp
                    {{ $dayName }}، {{ $selectedDate->format('d') }} {{ $monthName }} {{ $selectedDate->format('Y') }}
                </p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="window.close()" 
                        class="bg-white text-purple-600 px-6 py-3 rounded-xl font-medium hover:bg-purple-50 transition-all duration-200 flex items-center">
                    <i class="ri-close-line ml-2"></i>
                    إغلاق
                </button>
                <button onclick="printReport()"
                        class="bg-purple-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-purple-400 transition-all duration-200 flex items-center">
                    <i class="ri-printer-line ml-2"></i>
                    طباعة التقرير
                </button>
            </div>
        </div>
        
        <!-- Date Info -->
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
            <div class="flex items-center justify-center">
                <div class="text-center">
                    <i class="ri-calendar-2-line text-4xl text-white mb-2"></i>
                    <h2 class="text-2xl font-bold">{{ $selectedDate->format('d/m/Y') }}</h2>
                    <p class="text-purple-100">{{ $dayName }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">الحضور</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['present'] }}</h3>
                    <p class="text-xs text-green-600 mt-1">موظف</p>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl">
                    <i class="ri-check-circle-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium mb-1">الغياب</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['absent'] }}</h3>
                    <p class="text-xs text-red-600 mt-1">موظف</p>
                </div>
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-3 rounded-xl">
                    <i class="ri-close-circle-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium mb-1">التأخير</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['late'] }}</h3>
                    <p class="text-xs text-yellow-600 mt-1">موظف</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-3 rounded-xl">
                    <i class="ri-time-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">الإجازات</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['leave'] }}</h3>
                    <p class="text-xs text-blue-600 mt-1">موظف</p>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl">
                    <i class="ri-calendar-event-fill text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl p-6 border border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium mb-1">ساعات إضافية</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_overtime_hours'], 1) }}</h3>
                    <p class="text-xs text-orange-600 mt-1">ساعة</p>
                </div>
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-3 rounded-xl">
                    <i class="ri-time-add-fill text-white text-xl"></i>
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
                        <i class="ri-list-check-2 text-purple-600"></i>
                        تفاصيل الحضور لتاريخ: {{ $selectedDate->format('d/m/Y') }}
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">جميع الموظفين وحالة حضورهم لهذا اليوم</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
                        {{ $stats['total_employees'] }} موظف
                    </span>
                    <a href="/employees/daily-attendance-edit?date={{ $date }}" 
                       target="_blank"
                       class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2 text-sm">
                        <i class="ri-edit-2-line"></i>
                        تعديل البيانات
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="attendanceTable">
                <thead class="bg-gradient-to-r from-gray-800 to-gray-900 text-white">
                    <tr>
                        <th class="px-6 py-4 text-right font-semibold">الموظف</th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-login-circle-line text-green-400 mr-1"></i>
                            وقت الحضور
                        </th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-logout-circle-line text-red-400 mr-1"></i>
                            وقت الانصراف
                        </th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-time-line text-blue-400 mr-1"></i>
                            ساعات العمل
                        </th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-timer-line text-yellow-400 mr-1"></i>
                            دقائق التأخير
                        </th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-checkbox-circle-line text-purple-400 mr-1"></i>
                            الحالة
                        </th>
                        <th class="px-6 py-4 text-center font-semibold">
                            <i class="ri-sticky-note-line text-orange-400 mr-1"></i>
                            ملاحظات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($employees as $index => $employee)
                    @php
                        $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-gray-50';
                        
                        // Status styling
                        $statusClass = 'bg-gray-100 text-gray-800';
                        $statusText = 'غائب';
                        
                        switch($employee->attendance_status) {
                            case 'present':
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'حاضر';
                                break;
                            case 'late':
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'متأخر';
                                break;
                            case 'leave':
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusText = 'إجازة اعتيادية';
                                break;
                            case 'sick_leave':
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusText = 'إجازة مرضية';
                                break;
                            case 'absent':
                            default:
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'غائب';
                        }
                    @endphp
                    <tr class="{{ $rowClass }} hover:bg-purple-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}"
                                         alt="{{ $employee->name }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
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
                            @if($employee->check_in)
                                <span class="text-sm font-mono text-gray-900">{{ $employee->check_in }}</span>
                            @else
                                <span class="text-sm text-gray-400">--:--</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($employee->check_out)
                                <span class="text-sm font-mono text-gray-900">{{ $employee->check_out }}</span>
                            @else
                                <span class="text-sm text-gray-400">--:--</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($employee->working_hours > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ number_format($employee->working_hours, 1) }} ساعة
                                </span>
                            @else
                                <span class="text-sm text-gray-400">0 ساعة</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($employee->late_minutes > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    {{ $employee->late_minutes }} دقيقة
                                </span>
                            @else
                                <span class="text-sm text-gray-400">0 دقيقة</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            @if($employee->notes)
                                <span class="text-sm text-gray-700 max-w-xs truncate block">{{ $employee->notes }}</span>
                            @else
                                <span class="text-sm text-gray-400">لا توجد ملاحظات</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Footer -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-center">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['present'] }}</div>
                    <div class="text-sm text-gray-600">حاضر</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['absent'] }}</div>
                    <div class="text-sm text-gray-600">غائب</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['late'] }}</div>
                    <div class="text-sm text-gray-600">متأخر</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['leave'] }}</div>
                    <div class="text-sm text-gray-600">إجازة</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-2xl font-bold text-orange-600">{{ number_format($stats['total_overtime_hours'], 1) }}</div>
                    <div class="text-sm text-gray-600">ساعات إضافية</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printReport() {
    window.print();
}
</script>

@push('styles')
<style>
@media print {
    .bg-gradient-to-r {
        background: #f8f9fa !important;
    }
    
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endpush

@endsection
