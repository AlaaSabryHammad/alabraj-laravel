@extends('layouts.app')

@section('title', 'إدارة المالية')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">إدارة المالية</h1>
            <p class="text-gray-600 mt-1">إدارة الإيرادات والمصروفات المالية</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('payroll.index') }}"
               class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
                <i class="ri-team-line text-lg"></i>
                إدارة مسيرات الرواتب
            </a>
            <a href="{{ route('finance.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-add-line"></i>
                إضافة معاملة جديدة
            </a>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي الإيرادات</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalIncome, 0) }} ر.س</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-arrow-up-line text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي المصروفات</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($totalExpense, 0) }} ر.س</p>
                </div>
                <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="ri-arrow-down-line text-xl text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">صافي الربح</p>
                    <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($netProfit, 0) }} ر.س
                    </p>
                </div>
                <div class="h-12 w-12 {{ $netProfit >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                    <i class="ri-pie-chart-line text-xl {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إيرادات الشهر</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($monthlyIncome, 0) }} ر.س</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-calendar-line text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Finance Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">المعاملات المالية</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            النوع
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الفئة
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الوصف
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المبلغ
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ المعاملة
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            طريقة الدفع
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الحالة
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($finances as $finance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($finance->type_color === 'green') bg-green-100 text-green-800
                                    @elseif($finance->type_color === 'red') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($finance->type === 'income')
                                        <i class="ri-arrow-up-line ml-1"></i>
                                    @else
                                        <i class="ri-arrow-down-line ml-1"></i>
                                    @endif
                                    {{ $finance->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $finance->category }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $finance->description }}</div>
                                @if($finance->reference_number)
                                    <div class="text-xs text-gray-500">المرجع: {{ $finance->reference_number }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium {{ $finance->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $finance->formatted_amount }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $finance->transaction_date->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $finance->payment_method }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($finance->status_color === 'green') bg-green-100 text-green-800
                                    @elseif($finance->status_color === 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($finance->status_color === 'red') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $finance->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('finance.edit', $finance) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <form action="{{ route('finance.destroy', $finance) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه المعاملة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="ri-money-dollar-circle-line text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-gray-500">لا توجد معاملات مالية مسجلة</p>
                                    <a href="{{ route('finance.create') }}"
                                       class="mt-2 text-blue-600 hover:text-blue-800">
                                        إضافة أول معاملة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($finances->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $finances->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
