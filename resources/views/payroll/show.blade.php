@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $payroll->title }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span>تاريخ الراتب: {{ $payroll->payroll_date->format('Y-m-d') }}</span>
                    <span>أنشئت بواسطة: {{ $payroll->creator->name ?? 'غير محدد' }}</span>
                    <span>{{ $payroll->created_at->format('Y-m-d H:i') }}</span>
                </div>
                @if($payroll->notes)
                    <p class="mt-2 text-gray-600">{{ $payroll->notes }}</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $payroll->status_badge }}">
                    {{ $payroll->status_text }}
                </span>
                <a href="{{ route('payroll.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                    رجوع للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-3">
            @if($payroll->isEditable())
                <a href="{{ route('payroll.edit', $payroll) }}"
                   class="bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    تعديل
                </a>
            @endif

            @if($payroll->canBeApproved())
                <form method="POST" action="{{ route('payroll.approve', $payroll) }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center gap-2"
                            onclick="return confirm('هل أنت متأكد من اعتماد هذه المسيرة؟')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        اعتماد
                    </button>
                </form>

                <form method="POST" action="{{ route('payroll.reject', $payroll) }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center gap-2"
                            onclick="return confirm('هل أنت متأكد من رفض هذه المسيرة؟')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        رفض
                    </button>
                </form>
            @endif

            <a href="{{ route('payroll.print', $payroll) }}"
               target="_blank"
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                طباعة
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border-r-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي الموظفين</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['total_employees'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-r-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">المستحقين للراتب</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['eligible_employees'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-r-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي الراتب الأساسي</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_base_salary'], 2) }}</p>
                    <p class="text-xs text-gray-500">ريال سعودي</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-r-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">صافي الراتب</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_net_salary'], 2) }}</p>
                    <p class="text-xs text-gray-500">ريال سعودي</p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Summary Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">ملخص الاستقطاعات والبدلات</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">إجمالي الاستقطاعات</span>
                    <span class="font-medium text-red-600">{{ number_format($summary['total_deductions'], 2) }} ريال</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">إجمالي البدلات</span>
                    <span class="font-medium text-green-600">{{ number_format($summary['total_bonuses'], 2) }} ريال</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between items-center font-bold">
                    <span class="text-gray-900">صافي المبلغ المستحق</span>
                    <span class="text-emerald-600 text-lg">{{ number_format($summary['total_net_salary'], 2) }} ريال</span>
                </div>
            </div>
        </div>

        @if($payroll->approved_at)
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">معلومات الاعتماد</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">معتمد بواسطة</span>
                        <span class="font-medium">{{ $payroll->approver->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">تاريخ الاعتماد</span>
                        <span class="font-medium">{{ $payroll->approved_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Employees Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">تفاصيل رواتب الموظفين</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموظف</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الراتب الأساسي</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البدلات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاستقطاعات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">صافي الراتب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($payroll->employees as $payrollEmployee)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $payrollEmployee->employee->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $payrollEmployee->employee->employee_id ?? 'غير محدد' }}</div>
                                        <div class="text-sm text-gray-500">{{ $payrollEmployee->employee->position ?? 'غير محدد' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($payrollEmployee->base_salary, 2) }} ريال
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                {{ number_format($payrollEmployee->total_bonuses, 2) }} ريال
                                @if($payrollEmployee->bonuses->count() > 0)
                                    <div class="text-xs text-gray-500">({{ $payrollEmployee->bonuses->count() }} بدل)</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                {{ number_format($payrollEmployee->total_deductions, 2) }} ريال
                                @if($payrollEmployee->deductions->count() > 0)
                                    <div class="text-xs text-gray-500">({{ $payrollEmployee->deductions->count() }} استقطاع)</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($payrollEmployee->net_salary, 2) }} ريال
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payrollEmployee->is_eligible)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        مستحق
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        غير مستحق
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('payroll.employee.details', [$payroll, $payrollEmployee->employee]) }}"
                                   class="text-emerald-600 hover:text-emerald-900 transition-colors duration-200">
                                    التفاصيل
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('error') }}
    </div>
@endif
@endsection
