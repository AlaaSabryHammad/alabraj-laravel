@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تفاصيل راتب الموظف</h1>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span>المسيرة: {{ $payroll->title }}</span>
                    <span>الموظف: {{ $employee->name }}</span>
                    <span>{{ $payroll->payroll_date->format('Y-m-d') }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('payroll.show', $payroll) }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                    رجوع للمسيرة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Employee Info & Basic Salary -->
        <div class="lg:col-span-1">
            <!-- Employee Information -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">معلومات الموظف</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600">الاسم</label>
                        <p class="text-gray-900">{{ $employee->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">رقم الموظف</label>
                        <p class="text-gray-900">{{ $employee->employee_id ?? 'غير محدد' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">المنصب</label>
                        <p class="text-gray-900">{{ $employee->position ?? 'غير محدد' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">القسم</label>
                        <p class="text-gray-900">{{ $employee->department ?? 'غير محدد' }}</p>
                    </div>
                </div>
            </div>

            <!-- Basic Salary Settings -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">إعدادات الراتب</h2>

                @if($payroll->status !== 'approved')
                    <form method="POST" action="{{ route('payroll.employee.update', [$payroll, $employee]) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="base_salary" class="block text-sm font-medium text-gray-700 mb-2">
                                    الراتب الأساسي
                                </label>
                                <input type="number"
                                       id="base_salary"
                                       name="base_salary"
                                       value="{{ old('base_salary', $payrollEmployee->base_salary) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('base_salary') border-red-500 @enderror"
                                       required>
                                @error('base_salary')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="is_eligible"
                                       name="is_eligible"
                                       value="1"
                                       {{ $payrollEmployee->is_eligible ? 'checked' : '' }}
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="is_eligible" class="mr-3 text-sm text-gray-700">
                                    الموظف مستحق للراتب
                                </label>
                            </div>

                            <button type="submit"
                                    class="w-full bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition-colors duration-200">
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                @else
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">الراتب الأساسي</label>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($payrollEmployee->base_salary, 2) }} ريال</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">الحالة</label>
                            <p class="text-gray-900">
                                @if($payrollEmployee->is_eligible)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        مستحق للراتب
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        غير مستحق للراتب
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Deductions & Bonuses -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Salary Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">ملخص الراتب</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">الراتب الأساسي</p>
                        <p class="text-xl font-bold text-blue-600">{{ number_format($payrollEmployee->base_salary, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600">إجمالي البدلات</p>
                        <p class="text-xl font-bold text-green-600">{{ number_format($payrollEmployee->total_bonuses, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-gray-600">إجمالي الاستقطاعات</p>
                        <p class="text-xl font-bold text-red-600">{{ number_format($payrollEmployee->total_deductions, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-emerald-50 rounded-lg">
                        <p class="text-sm text-gray-600">صافي الراتب</p>
                        <p class="text-xl font-bold text-emerald-600">{{ number_format($payrollEmployee->net_salary, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Bonuses Section -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">البدلات والإضافات</h2>
                    @if($payroll->status !== 'approved')
                        <button onclick="toggleAddBonusForm()"
                                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            إضافة بدل
                        </button>
                    @endif
                </div>

                @if($payroll->status !== 'approved')
                    <!-- Add Bonus Form -->
                    <div id="addBonusForm" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                        <form method="POST" action="{{ route('payroll.employee.bonus.add', [$payroll, $employee]) }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="bonus_type" class="block text-sm font-medium text-gray-700 mb-1">نوع البدل</label>
                                    <select id="bonus_type"
                                            name="type"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                            required>
                                        <option value="">اختر نوع البدل</option>
                                        @foreach(\App\Models\PayrollBonus::getCommonTypes() as $type)
                                            <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="bonus_amount" class="block text-sm font-medium text-gray-700 mb-1">المبلغ</label>
                                    <input type="number"
                                           id="bonus_amount"
                                           name="amount"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           required>
                                </div>
                                <div>
                                    <label for="bonus_notes" class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                    <input type="text"
                                           id="bonus_notes"
                                           name="notes"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           placeholder="ملاحظات اختيارية">
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button"
                                        onclick="toggleAddBonusForm()"
                                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                    إلغاء
                                </button>
                                <button type="submit"
                                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200">
                                    إضافة البدل
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Bonuses List -->
                @if($payrollEmployee->bonuses->count() > 0)
                    <div class="space-y-3">
                        @foreach($payrollEmployee->bonuses as $bonus)
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $bonus->type }}</p>
                                    <p class="text-sm text-gray-600">{{ $bonus->notes }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-green-600">{{ number_format($bonus->amount, 2) }} ريال</span>
                                    @if($payroll->status !== 'approved')
                                        <form method="POST" action="{{ route('payroll.bonus.delete', $bonus) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا البدل؟')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">لا توجد بدلات مضافة</p>
                @endif
            </div>

            <!-- Deductions Section -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">الاستقطاعات</h2>
                    @if($payroll->status !== 'approved')
                        <button onclick="toggleAddDeductionForm()"
                                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            إضافة استقطاع
                        </button>
                    @endif
                </div>

                @if($payroll->status !== 'approved')
                    <!-- Add Deduction Form -->
                    <div id="addDeductionForm" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                        <form method="POST" action="{{ route('payroll.employee.deduction.add', [$payroll, $employee]) }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="deduction_type" class="block text-sm font-medium text-gray-700 mb-1">نوع الاستقطاع</label>
                                    <select id="deduction_type"
                                            name="type"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                            required>
                                        <option value="">اختر نوع الاستقطاع</option>
                                        @foreach(\App\Models\PayrollDeduction::getCommonTypes() as $type)
                                            <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="deduction_amount" class="block text-sm font-medium text-gray-700 mb-1">المبلغ</label>
                                    <input type="number"
                                           id="deduction_amount"
                                           name="amount"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           required>
                                </div>
                                <div>
                                    <label for="deduction_notes" class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                    <input type="text"
                                           id="deduction_notes"
                                           name="notes"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           placeholder="ملاحظات اختيارية">
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button"
                                        onclick="toggleAddDeductionForm()"
                                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                    إلغاء
                                </button>
                                <button type="submit"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200">
                                    إضافة الاستقطاع
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Deductions List -->
                @if($payrollEmployee->deductions->count() > 0)
                    <div class="space-y-3">
                        @foreach($payrollEmployee->deductions as $deduction)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $deduction->type }}</p>
                                    <p class="text-sm text-gray-600">{{ $deduction->notes }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-red-600">{{ number_format($deduction->amount, 2) }} ريال</span>
                                    @if($payroll->status !== 'approved')
                                        <form method="POST" action="{{ route('payroll.deduction.delete', $deduction) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا الاستقطاع؟')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">لا توجد استقطاعات مضافة</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleAddBonusForm() {
    const form = document.getElementById('addBonusForm');
    form.classList.toggle('hidden');
}

function toggleAddDeductionForm() {
    const form = document.getElementById('addDeductionForm');
    form.classList.toggle('hidden');
}
</script>

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
