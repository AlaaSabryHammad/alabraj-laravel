@extends('layouts.app')

@section('title', 'تقرير الحضور الشهري - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تقرير الحضور الشهري</h1>
                <p class="text-gray-600">تقرير شامل لحضور الموظفين</p>
            </div>
            <a href="{{ route('employees.attendance') }}"
               class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                <i class="ri-arrow-right-line ml-2"></i>
                العودة لمتابعة الحضور
            </a>
        </div>
        <form action="{{ route('employees.attendance.report') }}" method="GET">
            <div class="flex items-center space-x-4 space-x-reverse">
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">الشهر</label>
                    <select name="month" id="month" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">السنة</label>
                    <select name="year" id="year" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="bg-orange-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-orange-600 transition-all duration-200 flex items-center">
                        <i class="ri-search-line ml-2"></i>
                        عرض التقرير
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Attendance Report -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">تقرير شهر: {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموظف</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">أيام الحضور</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">أيام الغياب</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">أيام التأخير</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">أيام الإجازة</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($employees as $employee)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">{{ mb_substr($employee->name, 0, 1) }}</span>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $employee->position }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $employee->present_days }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ (int)$employee->absent_days }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $employee->late_days }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $employee->leave_days }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
