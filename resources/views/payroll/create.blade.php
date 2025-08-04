@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إنشاء مسيرة راتب جديدة</h1>
                <p class="text-gray-600">إنشاء مسيرة راتب شهرية للموظفين</p>
            </div>
            <a href="{{ route('payroll.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                رجوع للقائمة
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('payroll.store') }}" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">المعلومات الأساسية</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان المسيرة</label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('title') border-red-500 @enderror"
                           placeholder="مثال: راتب شهر يناير 2025"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payroll_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الراتب</label>
                    <input type="date"
                           id="payroll_date"
                           name="payroll_date"
                           value="{{ old('payroll_date', now()->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('payroll_date') border-red-500 @enderror"
                           required>
                    @error('payroll_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                <textarea id="notes"
                          name="notes"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                          placeholder="ملاحظات إضافية حول هذه المسيرة...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Employee Selection and Configuration -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">إعداد رواتب الموظفين</h2>
                <div class="flex gap-2">
                    <button type="button"
                            onclick="selectAllEmployees()"
                            class="bg-emerald-500 text-white px-3 py-1 rounded text-sm hover:bg-emerald-600 transition-colors duration-200">
                        تحديد الكل
                    </button>
                    <button type="button"
                            onclick="deselectAllEmployees()"
                            class="bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600 transition-colors duration-200">
                        إلغاء التحديد
                    </button>
                </div>
            </div>

            @error('employees')
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ $message }}
                </div>
            @enderror

            <div class="space-y-4">
                @foreach($employees as $employee)
                    <div class="border border-gray-200 rounded-lg p-4 employee-card" data-employee-id="{{ $employee->id }}">
                        <div class="flex items-start gap-4">
                            <!-- Employee Selection -->
                            <div class="flex items-center pt-2">
                                <input type="checkbox"
                                       name="employees[]"
                                       value="{{ $employee->id }}"
                                       class="form-checkbox h-4 w-4 text-emerald-600 rounded focus:ring-emerald-500 border-gray-300 employee-checkbox"
                                       onchange="toggleEmployeeFields({{ $employee->id }})"
                                       {{ in_array($employee->id, old('employees', [])) ? 'checked' : '' }}>
                            </div>

                            <!-- Employee Info -->
                            <div class="flex-1">
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                    <!-- Employee Details -->
                                    <div class="lg:col-span-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-medium text-gray-900">{{ $employee->name }}</h4>
                                            @if($employee->employee_id)
                                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                                    {{ $employee->employee_id }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600">{{ $employee->position ?? 'غير محدد' }}</p>
                                    </div>

                                    <!-- Salary Configuration -->
                                    <div class="lg:col-span-3 employee-fields" id="fields-{{ $employee->id }}" style="display: none;">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Base Salary -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">الراتب الأساسي</label>
                                                <input type="number"
                                                       name="salary[{{ $employee->id }}]"
                                                       value="{{ old('salary.' . $employee->id, $employee->salary ?? 0) }}"
                                                       step="0.01"
                                                       min="0"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                                       placeholder="0.00">
                                            </div>

                                            <!-- Eligibility -->
                                            <div class="flex items-center pt-6">
                                                <label class="flex items-center">
                                                    <input type="checkbox"
                                                           name="is_eligible[{{ $employee->id }}]"
                                                           value="1"
                                                           class="form-checkbox h-4 w-4 text-emerald-600 rounded focus:ring-emerald-500 border-gray-300"
                                                           {{ old('is_eligible.' . $employee->id, true) ? 'checked' : '' }}>
                                                    <span class="mr-2 text-sm text-gray-700">مستحق للراتب</span>
                                                </label>
                                            </div>

                                            <!-- Add Bonus/Deduction Buttons -->
                                            <div class="flex items-center gap-2 pt-4">
                                                <button type="button"
                                                        onclick="addBonus({{ $employee->id }})"
                                                        class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition-colors">
                                                    + بدل
                                                </button>
                                                <button type="button"
                                                        onclick="addDeduction({{ $employee->id }})"
                                                        class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition-colors">
                                                    + استقطاع
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Bonuses Container -->
                                        <div id="bonuses-{{ $employee->id }}" class="mt-4 space-y-2">
                                            <h5 class="text-sm font-medium text-green-700">البدلات</h5>
                                            <div class="bonuses-list space-y-2"></div>
                                        </div>

                                        <!-- Deductions Container -->
                                        <div id="deductions-{{ $employee->id }}" class="mt-4 space-y-2">
                                            <h5 class="text-sm font-medium text-red-700">الاستقطاعات</h5>
                                            <div class="deductions-list space-y-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($employees->isEmpty())
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">لا يوجد موظفين نشطين</h3>
                    <p class="mt-1 text-sm text-gray-500">يرجى إضافة موظفين أولاً لإنشاء مسيرة راتب</p>
                </div>
            @endif
        </div>

        <!-- Submit Buttons -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-end gap-4">
                <a href="{{ route('payroll.index') }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-3 rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    إنشاء المسيرة
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let bonusCounter = 0;
let deductionCounter = 0;

function selectAllEmployees() {
    const checkboxes = document.querySelectorAll('input[name="employees[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
        const employeeId = checkbox.value;
        toggleEmployeeFields(employeeId);
    });
}

function deselectAllEmployees() {
    const checkboxes = document.querySelectorAll('input[name="employees[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
        const employeeId = checkbox.value;
        toggleEmployeeFields(employeeId);
    });
}

function toggleEmployeeFields(employeeId) {
    const checkbox = document.querySelector(`input[name="employees[]"][value="${employeeId}"]`);
    const fieldsContainer = document.getElementById(`fields-${employeeId}`);

    if (checkbox.checked) {
        fieldsContainer.style.display = 'block';
    } else {
        fieldsContainer.style.display = 'none';
    }
}

function addBonus(employeeId) {
    const container = document.querySelector(`#bonuses-${employeeId} .bonuses-list`);
    const bonusId = `bonus_${employeeId}_${bonusCounter++}`;

    const bonusHTML = `
        <div id="${bonusId}" class="flex items-center gap-2 bg-green-50 p-2 rounded border">
            <input type="text"
                   name="bonuses[${employeeId}][type][]"
                   placeholder="نوع البدل (مثل: بدل مواصلات)"
                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm">
            <input type="number"
                   name="bonuses[${employeeId}][amount][]"
                   placeholder="المبلغ"
                   step="0.01"
                   min="0"
                   class="w-24 px-2 py-1 border border-gray-300 rounded text-sm">
            <input type="text"
                   name="bonuses[${employeeId}][notes][]"
                   placeholder="ملاحظات"
                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm">
            <button type="button"
                    onclick="removeElement('${bonusId}')"
                    class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">
                حذف
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', bonusHTML);
}

function addDeduction(employeeId) {
    const container = document.querySelector(`#deductions-${employeeId} .deductions-list`);
    const deductionId = `deduction_${employeeId}_${deductionCounter++}`;

    const deductionHTML = `
        <div id="${deductionId}" class="flex items-center gap-2 bg-red-50 p-2 rounded border">
            <input type="text"
                   name="deductions[${employeeId}][type][]"
                   placeholder="نوع الاستقطاع (مثل: تأمينات اجتماعية)"
                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm">
            <input type="number"
                   name="deductions[${employeeId}][amount][]"
                   placeholder="المبلغ"
                   step="0.01"
                   min="0"
                   class="w-24 px-2 py-1 border border-gray-300 rounded text-sm">
            <input type="text"
                   name="deductions[${employeeId}][notes][]"
                   placeholder="ملاحظات"
                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm">
            <button type="button"
                    onclick="removeElement('${deductionId}')"
                    class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">
                حذف
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', deductionHTML);
}

function removeElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.remove();
    }
}

// Initialize fields visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="employees[]"]');
    checkboxes.forEach(checkbox => {
        const employeeId = checkbox.value;
        toggleEmployeeFields(employeeId);
    });
});
</script>
@endsection
