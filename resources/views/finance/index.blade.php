@extends('layouts.app')

@section('title', 'إدارة المالية')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إدارة المالية</h1>
                <p class="text-gray-600 mt-1">إدارة الإيرادات والمصروفات المالية</p>
                <p class="text-emerald-600 text-sm mt-1"><i class="ri-information-line"></i> يتم احتساب المبالغ في الإجماليات
                    فقط بعد اعتماد السندات</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('finance.all-transactions') }}"
                    class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
                    <i class="ri-file-list-3-line text-lg"></i>
                    عرض جميع السندات والعهد
                </a>
                <a href="{{ route('revenue-vouchers.create') }}"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-add-line"></i>
                    إضافة إيراد جديد
                </a>
                <a href="{{ route('payroll.index') }}"
                    class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
                    <i class="ri-team-line text-lg"></i>
                    إدارة مسيرات الرواتب
                </a>
                <a href="{{ route('expense-vouchers.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-file-text-line"></i>
                    تسجيل سند صرف
                </a>
                <button onclick="openCustodyModal()"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-wallet-3-line"></i>
                    تسجيل عهدة جديدة
                </button>
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
                    <div
                        class="h-12 w-12 {{ $netProfit >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
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
        @if (session('success'))
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
                                رقم السند
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
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($finance['type_color'] === 'green') bg-green-100 text-green-800
                                    @elseif($finance['type_color'] === 'red') bg-red-100 text-red-800
                                    @elseif($finance['type_color'] === 'blue') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        @if ($finance['type'] === 'revenue_voucher')
                                            <i class="ri-arrow-up-line ml-1"></i>
                                        @elseif($finance['type'] === 'expense_voucher')
                                            <i class="ri-arrow-down-line ml-1"></i>
                                        @else
                                            <i class="ri-wallet-3-line ml-1"></i>
                                        @endif
                                        {{ $finance['type_label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $finance['reference_number'] ?? 'غير محدد' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $finance['description'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm font-medium {{ $finance['type'] === 'revenue_voucher' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $finance['formatted_amount'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($finance['transaction_date'])->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $finance['payment_method'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($finance['status_color'] === 'green') bg-green-100 text-green-800
                                    @elseif($finance['status_color'] === 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($finance['status_color'] === 'blue') bg-blue-100 text-blue-800
                                    @elseif($finance['status_color'] === 'red') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ $finance['status_label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-1">
                                        @if ($finance['type'] === 'revenue_voucher')
                                            <a href="{{ route('revenue-vouchers.show', $finance['id']) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-white hover:bg-blue-600 transition-all duration-200 rounded-lg border border-blue-200 hover:border-blue-600"
                                                title="عرض سند القبض">
                                                <i class="ri-eye-line text-sm"></i>
                                            </a>
                                        @elseif($finance['type'] === 'expense_voucher')
                                            <a href="{{ route('expense-vouchers.show', $finance['id']) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-white hover:bg-blue-600 transition-all duration-200 rounded-lg border border-blue-200 hover:border-blue-600"
                                                title="عرض سند الصرف">
                                                <i class="ri-eye-line text-sm"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('finance.custodies.show', $finance['id']) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-white hover:bg-blue-600 transition-all duration-200 rounded-lg border border-blue-200 hover:border-blue-600"
                                                title="عرض تفاصيل العهدة">
                                                <i class="ri-eye-line text-sm"></i>
                                            </a>
                                        @endif
                                        <button onclick="printTransaction('{{ $finance['type'] }}', {{ $finance['id'] }})"
                                            class="inline-flex items-center justify-center w-8 h-8 text-purple-600 hover:text-white hover:bg-purple-600 transition-all duration-200 rounded-lg border border-purple-200 hover:border-purple-600"
                                            title="طباعة السند">
                                            <i class="ri-printer-line text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="ri-money-dollar-circle-line text-4xl text-gray-300 mb-2"></i>
                                        <p class="text-gray-500">لا توجد معاملات مالية حديثة</p>
                                        <p class="text-gray-400 text-sm mt-1">يعرض هذا الجدول آخر 10 معاملات فقط</p>
                                        <a href="{{ route('finance.all-transactions') }}"
                                            class="mt-2 text-purple-600 hover:text-purple-800">
                                            عرض جميع المعاملات
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $finances->links() }}
            </div>

            @if (count($finances) > 0)
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="text-center">
                        <a href="{{ route('finance.all-transactions') }}"
                            class="text-purple-600 hover:text-purple-800 font-medium">
                            عرض جميع المعاملات والسندات →
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Active Custodies -->
        <div class="mt-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">العهد النشطة</h2>
                <button onclick="openCustodyModal()"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded-lg transition-colors flex items-center gap-1 text-sm">
                    <i class="ri-add-line"></i>
                    عهدة جديدة
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($employeeBalances as $balance)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-orange-100">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                                    <i class="ri-user-3-line text-orange-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $balance['employee']->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $balance['employee']->position }}</p>
                                </div>
                            </div>
                            <div class="text-left">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $balance['current_balance'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($balance['current_balance'], 2) }} ر.س
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">إجمالي العهد:</span>
                                <span
                                    class="font-semibold text-green-600">{{ number_format($balance['total_custodies'], 2) }}
                                    ر.س</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">إجمالي المصروفات:</span>
                                <span
                                    class="font-semibold text-red-600">{{ number_format($balance['total_expenses'], 2) }}
                                    ر.س</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">عدد العهد النشطة:</span>
                                <span class="text-gray-900">{{ $balance['custodies_count'] }}</span>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('finance.employee-report', $balance['employee']) }}"
                                    class="text-orange-600 hover:text-orange-700 text-sm inline-flex items-center gap-1">
                                    <span>عرض التقرير التفصيلي</span>
                                    <i class="ri-arrow-left-line"></i>
                                </a>
                            </div>
                            @if ($balance['last_custody'])
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>آخر عهدة:</span>
                                    <span>{{ optional($balance['last_custody']->disbursement_date)->format('Y-m-d') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-4 border-t flex justify-end gap-2">
                            <a href="{{ route('finance.employee-report', $balance['employee']) }}"
                                class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors text-sm">
                                <i class="ri-file-list-3-line"></i>
                                تقرير مفصل
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3">
                        <div class="bg-orange-50 rounded-xl p-6 text-center">
                            <div
                                class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-wallet-3-line text-2xl text-orange-600"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">لا توجد عهد نشطة</h3>
                            <p class="text-gray-600">لم يتم تسجيل أي عهد نشطة في النظام حتى الآن</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal تسجيل العهدة -->
    <div id="custodyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 p-6 text-white rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold flex items-center gap-3">
                        <i class="ri-wallet-3-line text-2xl"></i>
                        تسجيل عهدة جديدة
                    </h3>
                    <button type="button" onclick="closeCustodyModal()"
                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                        <i class="ri-close-line text-white"></i>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form id="custodyForm" action="{{ route('finance.custodies.store') }}" method="POST" class="p-6">
                @csrf

                <!-- اختيار الموظف -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-user-line text-orange-600"></i>
                        اختيار الموظف *
                    </label>
                    <div class="relative">
                        <!-- حقل البحث والاختيار -->
                        <div class="relative">
                            <input type="text" id="employeeSearchInput" placeholder="ابحث عن الموظف بالاسم..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 pr-10"
                                onclick="toggleEmployeeDropdown()" onkeyup="filterEmployees(this.value)"
                                autocomplete="off">
                            <i class="ri-search-line absolute right-3 top-3.5 text-gray-400"></i>
                            <i class="ri-arrow-down-s-line absolute left-3 top-3.5 text-gray-400 cursor-pointer"
                                onclick="toggleEmployeeDropdown()"></i>
                        </div>

                        <!-- القائمة المنسدلة -->
                        <div id="employeeDropdown"
                            class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg mt-1 hidden max-h-60 overflow-y-auto shadow-lg">
                            <div class="py-2">
                                @foreach ($employees as $employee)
                                    <div class="employee-option px-4 py-3 hover:bg-orange-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                        data-employee-id="{{ $employee->id }}"
                                        data-employee-name="{{ $employee->name }}"
                                        data-employee-number="{{ $employee->employee_number ?? 'غير محدد' }}"
                                        data-employee-phone="{{ $employee->phone ?? 'غير متوفر' }}"
                                        data-employee-email="{{ $employee->email ?? 'غير متوفر' }}"
                                        onclick="selectEmployeeFromDropdown(this)">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                                <i class="ri-user-line text-orange-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900">{{ $employee->name }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    {{ $employee->position ?? 'غير محدد' }}
                                                    @if ($employee->employee_number)
                                                        - رقم: {{ $employee->employee_number }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-right text-sm text-gray-500">
                                                <div>{{ $employee->phone ?? 'غير متوفر' }}</div>
                                                <div>{{ $employee->email ?? 'غير متوفر' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- الموظف المختار -->
                    <div id="selectedEmployeeCard"
                        class="hidden mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                <i class="ri-user-line text-xl text-orange-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900" id="selectedEmployeeName">اسم الموظف</h4>
                                <p class="text-sm text-gray-600" id="selectedEmployeeInfo">معلومات الموظف</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">
                                    <i class="ri-phone-line"></i>
                                    <span id="selectedEmployeePhone">رقم الجوال</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="ri-mail-line"></i>
                                    <span id="selectedEmployeeEmail">البريد الإلكتروني</span>
                                </p>
                            </div>
                            <button type="button" onclick="clearEmployeeSelection()"
                                class="text-red-600 hover:text-red-800">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" id="selectedEmployeeId" name="employee_id" required>
                </div>

                <!-- المبلغ -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-money-dollar-circle-line text-orange-600"></i>
                        المبلغ (ريال سعودي) *
                    </label>
                    <input type="number" name="amount" step="0.01" min="0" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="0.00">
                </div>

                <!-- التاريخ -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-calendar-line text-orange-600"></i>
                        تاريخ الصرف *
                    </label>
                    <input type="date" name="disbursement_date" required value="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <!-- طريقة الاستلام -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-hand-coin-line text-orange-600"></i>
                        طريقة استلام المبلغ *
                    </label>
                    <select name="receipt_method" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">اختر طريقة الاستلام</option>
                        <option value="cash">نقداً</option>
                        <option value="bank_transfer">تحويل بنكي</option>
                        <option value="check">شيك</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>

                <!-- الملاحظات -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-file-text-line text-orange-600"></i>
                        الملاحظات
                    </label>
                    <textarea name="notes" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="اكتب أي ملاحظات إضافية..."></textarea>
                </div>

                <!-- أزرار الحفظ -->
                <div class="flex justify-end gap-4 pt-6 border-t">
                    <button type="button" onclick="closeCustodyModal()"
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                        <i class="ri-save-line"></i>
                        حفظ العهدة
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // فتح modal العهدة
        function openCustodyModal() {
            document.getElementById('custodyModal').classList.remove('hidden');
        }

        // إغلاق modal العهدة
        function closeCustodyModal() {
            document.getElementById('custodyModal').classList.add('hidden');
            clearEmployeeSelection();
            document.getElementById('custodyForm').reset();
        }

        // تبديل إظهار/إخفاء القائمة المنسدلة
        function toggleEmployeeDropdown() {
            const dropdown = document.getElementById('employeeDropdown');
            dropdown.classList.toggle('hidden');

            // إذا كانت القائمة مفتوحة، ركز على حقل البحث
            if (!dropdown.classList.contains('hidden')) {
                document.getElementById('employeeSearchInput').focus();
            }
        }

        // فلترة الموظفين بناءً على نص البحث
        function filterEmployees(searchText) {
            const dropdown = document.getElementById('employeeDropdown');
            const employeeOptions = dropdown.querySelectorAll('.employee-option');

            // إظهار القائمة عند البدء في الكتابة
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            }

            searchText = searchText.toLowerCase().trim();

            employeeOptions.forEach(option => {
                const employeeName = option.dataset.employeeName.toLowerCase();
                const employeeNumber = option.dataset.employeeNumber.toLowerCase();

                // البحث في الاسم أو رقم الموظف
                if (employeeName.includes(searchText) || employeeNumber.includes(searchText)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        // اختيار موظف من القائمة المنسدلة
        function selectEmployeeFromDropdown(optionElement) {
            const employeeId = optionElement.dataset.employeeId;
            const employeeName = optionElement.dataset.employeeName;
            const employeeNumber = optionElement.dataset.employeeNumber;
            const employeePhone = optionElement.dataset.employeePhone;
            const employeeEmail = optionElement.dataset.employeeEmail;

            // تحديث حقل البحث
            document.getElementById('employeeSearchInput').value = employeeName;

            // تحديث المعرف المخفي
            document.getElementById('selectedEmployeeId').value = employeeId;

            // عرض بطاقة الموظف المختار
            document.getElementById('selectedEmployeeName').textContent = employeeName;
            document.getElementById('selectedEmployeeInfo').textContent = `رقم الموظف: ${employeeNumber}`;
            document.getElementById('selectedEmployeePhone').textContent = employeePhone;
            document.getElementById('selectedEmployeeEmail').textContent = employeeEmail;

            document.getElementById('selectedEmployeeCard').classList.remove('hidden');

            // إخفاء القائمة المنسدلة
            document.getElementById('employeeDropdown').classList.add('hidden');
        }

        // مسح اختيار الموظف
        function clearEmployeeSelection() {
            document.getElementById('employeeSearchInput').value = '';
            document.getElementById('selectedEmployeeId').value = '';
            document.getElementById('selectedEmployeeCard').classList.add('hidden');
            document.getElementById('employeeDropdown').classList.add('hidden');

            // إظهار جميع الخيارات مرة أخرى
            const employeeOptions = document.querySelectorAll('.employee-option');
            employeeOptions.forEach(option => {
                option.style.display = 'block';
            });
        }

        // إخفاء القائمة المنسدلة عند النقر خارجها
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('employeeDropdown');
            const searchInput = document.getElementById('employeeSearchInput');
            const dropdownContainer = dropdown?.parentElement;

            // التحقق من أن العناصر موجودة وأن النقر ليس داخل منطقة البحث
            if (dropdown && searchInput && dropdownContainer) {
                if (!dropdownContainer.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            }
        });

        // منع إرسال النموذج عند الضغط على Enter في حقل البحث
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('employeeSearchInput');
            if (searchInput) {
                searchInput.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();

                        // إذا كان هناك خيار واحد فقط ظاهر، اختره تلقائياً
                        const visibleOptions = Array.from(document.querySelectorAll('.employee-option'))
                            .filter(option => option.style.display !== 'none');

                        if (visibleOptions.length === 1) {
                            selectEmployeeFromDropdown(visibleOptions[0]);
                        }
                    }
                });
            }
        });

        // Handle view click with error handling
        function handleViewClick(event, element) {
            try {
                // Allow normal link behavior
                return true;
            } catch (error) {
                console.error('Error:', error);
                event.preventDefault();
                alert('حدث خطأ في فتح الرابط. يرجى المحاولة مرة أخرى.');
                return false;
            }
        }

        // View custody details
        function viewCustody(custodyId) {
            try {
                window.location.href = '/finance/custodies/' + custodyId;
            } catch (error) {
                console.error('Error viewing custody:', error);
                alert('حدث خطأ في عرض العهدة.');
            }
        }

        // Print transaction
        function printTransaction(type, id) {
            try {
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
                    default:
                        alert('نوع المعاملة غير مدعوم للطباعة.');
                        return;
                }

                if (url) {
                    // إضافة تأثير بصري للزر
                    const button = event.target.closest('button');
                    if (button) {
                        button.style.opacity = '0.6';
                        setTimeout(() => {
                            button.style.opacity = '1';
                        }, 300);
                    }

                    // فتح نافذة الطباعة
                    window.open(url, '_blank', 'width=900,height=700,scrollbars=yes,resizable=yes');
                } else {
                    alert('رابط الطباعة غير متوفر.');
                }
            } catch (error) {
                console.error('Error printing:', error);
                alert('حدث خطأ في الطباعة: ' + error.message);
            }
        }

        // Add click event listeners when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Add visual feedback for all buttons
            const buttons = document.querySelectorAll('button, a');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Add a small visual feedback
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 100);
                });
            });

            // Check if we have any console errors
            window.addEventListener('error', function(e) {
                console.error('JavaScript Error:', e.error);
            });
        });
    </script>

@endsection
