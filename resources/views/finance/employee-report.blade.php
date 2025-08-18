@extends('layouts.app')

@section('content')
    <div class="p-6" dir="rtl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">التقرير المالي للموظف: {{ $employee->name }}</h1>
                <p class="text-gray-600 mt-1">تفاصيل العهد والمصروفات</p>
            </div>
            <a href="{{ route('finance.index') }}"
                class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg transition-colors flex items-center gap-2 border">
                <i class="ri-arrow-right-line"></i>
                رجوع للوحة التحكم
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي العهد</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalCustody, 2) }} ر.س</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                        <i class="ri-wallet-3-line text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-red-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي المصروفات</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($totalExpenses, 2) }} ر.س</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
                        <i class="ri-money-dollar-circle-line text-xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm p-6 border {{ $balance >= 0 ? 'border-emerald-100' : 'border-orange-100' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">الرصيد الحالي</p>
                        <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-emerald-600' : 'text-orange-600' }} mt-1">
                            {{ number_format($balance, 2) }} ر.س
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-lg {{ $balance >= 0 ? 'bg-emerald-50' : 'bg-orange-50' }} flex items-center justify-center">
                        <i class="ri-coins-line text-xl {{ $balance >= 0 ? 'text-emerald-600' : 'text-orange-600' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custodies List -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">سجل العهد</h3>
                    <p class="text-gray-600 text-sm mt-1">جميع العهد المسجلة للموظف</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                    <i class="ri-wallet-3-line text-xl text-orange-600"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">التاريخ</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">نوع العهدة</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">المبلغ</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">تم الاعتماد بواسطة</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">الحالة</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($custodies as $custody)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm">{{ $custody->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $custody->type->name ?? 'غير محدد' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-green-600">
                                    {{ number_format($custody->amount, 2) }} ر.س</td>
                                <td class="px-4 py-3 text-sm">{{ $custody->approver->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $custody->status_color }}">
                                        {{ $custody->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('finance.custodies.show', $custody) }}"
                                        class="text-orange-600 hover:text-orange-700 inline-flex items-center gap-1 text-sm">
                                        <i class="ri-eye-line"></i>
                                        عرض
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-lg flex items-center justify-center mb-2">
                                            <i class="ri-inbox-line text-3xl text-gray-400"></i>
                                        </div>
                                        <p>لا توجد عهد مسجلة</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Expense Vouchers List -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">سندات الصرف</h3>
                    <p class="text-gray-600 text-sm mt-1">جميع المصروفات المسجلة للموظف</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="ri-file-list-3-line text-xl text-blue-600"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">التاريخ</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">المبلغ</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">البيان</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">تم الاعتماد بواسطة</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">الحالة</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($expenseVouchers as $voucher)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm">{{ $voucher->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-red-600">
                                    {{ number_format($voucher->amount, 2) }} ر.س</td>
                                <td class="px-4 py-3 text-sm">{{ $voucher->description }}</td>
                                <td class="px-4 py-3 text-sm">{{ $voucher->approver->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $voucher->status_color }}">
                                        {{ $voucher->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('expense-vouchers.show', $voucher) }}"
                                        class="text-blue-600 hover:text-blue-700 inline-flex items-center gap-1 text-sm">
                                        <i class="ri-file-text-line"></i>
                                        عرض السند
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-lg flex items-center justify-center mb-2">
                                            <i class="ri-inbox-line text-3xl text-gray-400"></i>
                                        </div>
                                        <p>لا توجد مصروفات مسجلة</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
