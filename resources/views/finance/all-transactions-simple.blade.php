@extends('layouts.app')

@section('title', 'جميع السندات والعهد')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">جميع السندات والعهد</h1>
                <p class="text-gray-600 mt-1">عرض مبسط لجميع سندات القبض والصرف والعهد</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('finance.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    العودة للمالية
                </a>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <form method="GET" action="{{ route('finance.all-transactions') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                        <p class="text-sm font-medium text-gray-600">إجمالي العهد</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($totalCustodies, 2) }} ر.س</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-wallet-line text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">صافي الحركة</p>
                        <p class="text-2xl font-bold {{ $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($netBalance, 2) }} ر.س
                        </p>
                    </div>
                    <div class="h-12 w-12 {{ $netBalance >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                        <i class="ri-calculator-line text-xl {{ $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- سندات القبض -->
        @if($type == 'all' || $type == 'revenue')
        <div class="bg-white rounded-xl shadow-sm border mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">سندات القبض ({{ $revenueVouchers instanceof \Illuminate\Pagination\LengthAwarePaginator ? $revenueVouchers->total() : $revenueVouchers->count() }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم السند</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوصف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($revenueVouchers as $voucher)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $voucher->voucher_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $voucher->voucher_date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    +{{ number_format($voucher->amount, 2) }} ر.س
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $voucher->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $voucher->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('revenue-vouchers.show', $voucher->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('revenue-vouchers.print', $voucher->id) }}" target="_blank" class="text-purple-600 hover:text-purple-900">
                                            <i class="ri-printer-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    لا توجد سندات قبض في الفترة المحددة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($revenueVouchers instanceof \Illuminate\Pagination\LengthAwarePaginator && $revenueVouchers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $revenueVouchers->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
        @endif

        <!-- سندات الصرف -->
        @if($type == 'all' || $type == 'expense')
        <div class="bg-white rounded-xl shadow-sm border mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">سندات الصرف ({{ $expenseVouchers instanceof \Illuminate\Pagination\LengthAwarePaginator ? $expenseVouchers->total() : $expenseVouchers->count() }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم السند</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوصف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($expenseVouchers as $voucher)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $voucher->voucher_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $voucher->voucher_date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                    -{{ number_format($voucher->amount, 2) }} ر.س
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $voucher->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $voucher->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('expense-vouchers.show', $voucher->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('expense-vouchers.print', $voucher->id) }}" target="_blank" class="text-purple-600 hover:text-purple-900">
                                            <i class="ri-printer-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    لا توجد سندات صرف في الفترة المحددة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expenseVouchers instanceof \Illuminate\Pagination\LengthAwarePaginator && $expenseVouchers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $expenseVouchers->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
        @endif

        <!-- العهد -->
        @if($type == 'all' || $type == 'custody')
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">العهد ({{ $custodies->count() }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم العهدة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموظف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($custodies as $custody)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    C-{{ str_pad($custody->id, 6, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $custody->disbursement_date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                    -{{ number_format($custody->amount, 2) }} ر.س
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $custody->employee->name ?? 'غير محدد' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        تم الصرف
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button class="text-blue-600 hover:text-blue-900">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <a href="{{ route('custodies.print', $custody->id) }}" target="_blank" class="text-purple-600 hover:text-purple-900">
                                            <i class="ri-printer-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    لا توجد عهد في الفترة المحددة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($custodies instanceof \Illuminate\Pagination\LengthAwarePaginator && $custodies->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $custodies->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
        @endif
    </div>
@endsection