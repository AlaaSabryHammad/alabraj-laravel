@extends('layouts.app')

@section('title', 'جميع السندات والعهد')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">جميع السندات والعهد</h1>
                <p class="text-gray-600 mt-1">عرض موحد لجميع سندات القبض والصرف والعهد</p>
                <p class="text-emerald-600 text-sm mt-1"><i class="ri-information-line"></i> يتم احتساب المبالغ في الإجماليات
                    فقط بعد اعتماد السندات</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('finance.daily-report') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-file-chart-line"></i>
                    التقرير اليومي
                </a>
                <a href="{{ route('finance.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    العودة للمالية
                </a>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <form method="GET" action="{{ route('finance.all-transactions') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                    <input type="date" name="from_date" value="{{ $fromDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                    <input type="date" name="to_date" value="{{ $toDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع المعاملة</label>
                    <select name="type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>جميع المعاملات</option>
                        <option value="revenue" {{ $type == 'revenue' ? 'selected' : '' }}>سندات القبض فقط</option>
                        <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>سندات الصرف فقط</option>
                        <option value="custody" {{ $type == 'custody' ? 'selected' : '' }}>العهد فقط</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i class="ri-search-line"></i>
                        بحث
                    </button>
                </div>
            </form>
        </div>

        <!-- ملخص الإحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">الرصيد المرحل</p>
                        <p class="text-2xl font-bold {{ $carriedBalance >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                            {{ number_format($carriedBalance, 2) }} ر.س
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-history-line text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الإيرادات</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($totalRevenue, 2) }} ر.س</p>
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
                        <p class="text-2xl font-bold text-red-600">{{ number_format($totalExpense, 2) }} ر.س</p>
                    </div>
                    <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ri-arrow-down-line text-xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">الرصيد النهائي</p>
                        <p
                            class="text-2xl font-bold {{ $carriedBalance + $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($carriedBalance + $netBalance, 2) }} ر.س
                        </p>
                    </div>
                    <div
                        class="h-12 w-12 {{ $carriedBalance + $netBalance >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                        <i
                            class="ri-wallet-line text-xl {{ $carriedBalance + $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول المعاملات الموحد -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">جميع المعاملات المالية</h2>
                    <span class="text-sm text-gray-500">{{ count($transactions) }} معاملة</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                رقم السند
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                نوع المعاملة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                التاريخ
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الوصف
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الجهة/الموظف
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                طريقة الدفع
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المبلغ
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
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction['voucher_number'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($transaction['type'] == 'revenue_voucher') bg-green-100 text-green-800
                                        @elseif($transaction['type'] == 'expense_voucher') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        @if ($transaction['type'] == 'revenue_voucher')
                                            <i class="ri-arrow-up-line ml-1"></i>
                                        @elseif($transaction['type'] == 'expense_voucher')
                                            <i class="ri-arrow-down-line ml-1"></i>
                                        @else
                                            <i class="ri-wallet-3-line ml-1"></i>
                                        @endif
                                        {{ $transaction['type_text'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaction['date'])->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $transaction['description'] }}</div>
                                    @if ($transaction['notes'])
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($transaction['notes'], 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $transaction['entity'] }}</div>
                                    @if ($transaction['project'] != 'غير محدد')
                                        <div class="text-xs text-gray-500">المشروع: {{ $transaction['project'] }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction['payment_method'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm font-medium {{ $transaction['is_income'] ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction['is_income'] ? '+' : '-' }}{{ number_format($transaction['amount'], 2) }}
                                        ر.س
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($transaction['status_color'] == 'green') bg-green-100 text-green-800
                                        @elseif($transaction['status_color'] == 'yellow') bg-yellow-100 text-yellow-800
                                        @elseif($transaction['status_color'] == 'blue') bg-blue-100 text-blue-800
                                        @elseif($transaction['status_color'] == 'red') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $transaction['status_text'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        @if ($transaction['type'] == 'revenue_voucher')
                                            <a href="{{ route('revenue-vouchers.show', $transaction['id']) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="عرض">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        @elseif($transaction['type'] == 'expense_voucher')
                                            <a href="{{ route('expense-vouchers.show', $transaction['id']) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="عرض">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('finance.custodies.show', $transaction['id']) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="عرض العهدة">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        @endif

                                        <button
                                            onclick="printVoucher('{{ $transaction['type'] }}', {{ $transaction['id'] }})"
                                            class="text-purple-600 hover:text-purple-900 transition-colors"
                                            title="طباعة">
                                            <i class="ri-printer-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="ri-file-list-3-line text-4xl text-gray-300 mb-2"></i>
                                        <p class="text-gray-500">لا توجد معاملات في الفترة المحددة</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (count($transactions) > 0)
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium">إجمالي الإيرادات:</span>
                            <span class="text-green-600 font-bold">+{{ number_format($totalRevenue, 2) }} ر.س</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">إجمالي المصروفات:</span>
                            <span class="text-red-600 font-bold">-{{ number_format($totalExpense, 2) }} ر.س</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">صافي الحركة:</span>
                            <span class="font-bold {{ $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $netBalance >= 0 ? '+' : '' }}{{ number_format($netBalance, 2) }} ر.س
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function printVoucher(type, id) {
            let url = '';
            switch (type) {
                case 'revenue_voucher':
                    url = `/revenue-vouchers/${id}/print`;
                    break;
                case 'expense_voucher':
                    url = `/expense-vouchers/${id}/print`;
                    break;
                case 'custody':
                    url = `/finance/custodies/${id}/print`;
                    break;
            }

            if (url) {
                window.open(url, '_blank', 'width=900,height=700,scrollbars=yes,resizable=yes');
            }
        }
    </script>
@endsection
