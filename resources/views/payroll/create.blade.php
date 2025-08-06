@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .balance-alert {
        transition: all 0.3s ease;
        animation: slideIn 0.5s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .balance-alert.border-green-400 {
        border-left-color: #10b981;
        background-color: #ecfdf5;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);
    }
    
    .balance-alert.border-red-400 {
        border-left-color: #ef4444;
        background-color: #fef2f2;
        box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.1);
    }
    
    .balance-alert.border-gray-400 {
        border-left-color: #6b7280;
        background-color: #f9fafb;
        box-shadow: 0 4px 6px -1px rgba(107, 114, 128, 0.1);
    }
    
    .employee-stats {
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .employee-card:hover .employee-stats {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }
    
    .attendance-rate {
        font-weight: 600;
    }
    
    #loading-overlay {
        backdrop-filter: blur(3px);
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Custom animations for form fields */
    .form-field-update {
        animation: formFieldPulse 0.6s ease;
    }
    
    @keyframes formFieldPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3); }
        100% { transform: scale(1); }
    }
    
    /* Database indicator styles */
    .db-indicator {
        position: relative;
        overflow: hidden;
    }
    
    .db-indicator::after {
        content: "📊";
        position: absolute;
        top: 2px;
        right: 2px;
        font-size: 10px;
        opacity: 0.7;
    }
    
    /* Enhanced visual feedback */
    .value-updated {
        animation: valueFlash 0.8s ease;
    }
    
    @keyframes valueFlash {
        0% { background-color: rgba(16, 185, 129, 0.2); }
        50% { background-color: rgba(16, 185, 129, 0.4); }
        100% { background-color: transparent; }
    }
    
    /* Balance Action Button Styles */
    .balance-action-btn {
        font-weight: 600;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .balance-action-btn.credit {
        background-color: #059669;
        color: white;
        border-color: #047857;
    }
    
    .balance-action-btn.credit:hover {
        background-color: #047857;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
    }
    
    .balance-action-btn.debit {
        background-color: #dc2626;
        color: white;
        border-color: #b91c1c;
    }
    
    .balance-action-btn.debit:hover {
        background-color: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
    }
    
    .balance-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .balance-action-btn:hover::before {
        left: 100%;
    }
</style>
@endsection

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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Month Selection -->
                <div>
                    <label for="payroll_month" class="block text-sm font-medium text-gray-700 mb-2">شهر المسيرة</label>
                    <input type="month"
                           id="payroll_month"
                           name="payroll_month"
                           value="{{ old('payroll_month', now()->format('Y-m')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('payroll_month') border-red-500 @enderror"
                           onchange="updatePayrollData()"
                           required>
                    @error('payroll_month')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                                        
                                        <!-- Attendance Statistics -->
                                        <div class="mt-2 employee-stats" id="stats-{{ $employee->id }}" style="display: none;">
                                            <!-- Database Info Card -->
                                            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg mb-2">
                                                <div class="text-xs font-bold text-blue-800 mb-2">📊 بيانات من قاعدة البيانات</div>
                                                <div class="grid grid-cols-2 gap-2 text-xs">
                                                    <div class="flex justify-between">
                                                        <span class="text-green-600 font-medium">أيام الحضور:</span>
                                                        <span class="attendance-days font-bold text-green-700">-</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-red-600 font-medium">أيام الغياب:</span>
                                                        <span class="absence-days font-bold text-red-700">-</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-blue-600 font-medium">ساعات إضافية:</span>
                                                        <span class="overtime-hours font-bold text-blue-700">-</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-purple-600 font-medium">معدل الحضور:</span>
                                                        <span class="attendance-rate font-bold text-purple-700">-</span>
                                                    </div>
                                                </div>
                                                <div class="mt-2 p-2 bg-gray-100 rounded text-xs">
                                                    <div class="font-medium text-gray-700 mb-1">📐 طريقة الحساب:</div>
                                                    <div class="text-gray-600">
                                                        • بدل الساعات الإضافية = ساعات × راتب ÷ 208<br>
                                                        • خصم الغياب = أيام × راتب ÷ 26 يوم عمل
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Balance Alert -->
                                            <div class="balance-alert p-3 rounded-lg border-l-4 hidden" id="balance-alert-{{ $employee->id }}">
                                                <div class="text-xs">
                                                    <div class="font-bold balance-message mb-2 flex items-center">
                                                        <span class="balance-icon mr-1">💰</span>
                                                        <span>رصيد الموظف</span>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2">
                                                        <div class="text-center p-2 bg-white rounded border">
                                                            <div class="text-green-600 font-medium">دائن</div>
                                                            <div class="credit-amount font-bold text-lg text-green-700">0</div>
                                                        </div>
                                                        <div class="text-center p-2 bg-white rounded border">
                                                            <div class="text-red-600 font-medium">مدين</div>
                                                            <div class="debit-amount font-bold text-lg text-red-700">0</div>
                                                        </div>
                                                        <div class="text-center p-2 bg-white rounded border">
                                                            <div class="font-medium text-gray-700">الصافي</div>
                                                            <div class="net-amount font-bold text-xl">0</div>
                                                        </div>
                                                    </div>
                                                    <button type="button" 
                                                            class="mt-2 w-full bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs hover:bg-blue-200 transition-colors view-balance-details"
                                                            onclick="showBalanceDetails({{ $employee->id }})">
                                                        📋 عرض تفاصيل الرصيد
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Salary Configuration -->
                                    <div class="lg:col-span-3 employee-fields" id="fields-{{ $employee->id }}" style="display: none;">
                                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                            <!-- Base Salary -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">💰 الراتب الأساسي</label>
                                                <input type="number"
                                                       name="salary[{{ $employee->id }}]"
                                                       value="{{ old('salary.' . $employee->id, $employee->salary ?? 0) }}"
                                                       step="0.01"
                                                       min="0"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-center font-bold"
                                                       placeholder="0.00"
                                                       onchange="recalculateAllAdjustments({{ $employee->id }})">
                                            </div>

                                            <!-- Working Days -->
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 mb-1">✅ أيام العمل</label>
                                                <div class="relative">
                                                    <input type="number"
                                                           name="working_days[{{ $employee->id }}]"
                                                           id="working_days_{{ $employee->id }}"
                                                           value="{{ old('working_days.' . $employee->id, 0) }}"
                                                           min="0"
                                                           max="31"
                                                           class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-center font-bold text-green-700 bg-green-50"
                                                           readonly>
                                                    <div class="absolute top-0 right-0 -mr-1 -mt-1 w-3 h-3 bg-green-500 rounded-full animate-pulse" id="working-days-indicator-{{ $employee->id }}" style="display: none;"></div>
                                                </div>
                                                <div class="text-xs text-green-600 mt-1 text-center">من قاعدة البيانات</div>
                                            </div>

                                            <!-- Absent Days -->
                                            <div>
                                                <label class="block text-sm font-medium text-red-700 mb-1">❌ أيام الغياب</label>
                                                <div class="relative">
                                                    <input type="number"
                                                           name="absent_days[{{ $employee->id }}]"
                                                           id="absent_days_{{ $employee->id }}"
                                                           value="{{ old('absent_days.' . $employee->id, 0) }}"
                                                           min="0"
                                                           max="31"
                                                           class="w-full px-3 py-2 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-center font-bold text-red-700 bg-red-50"
                                                           onchange="calculateSalaryDeduction({{ $employee->id }})"
                                                           placeholder="0">
                                                    <div class="absolute top-0 right-0 -mr-1 -mt-1 w-3 h-3 bg-red-500 rounded-full animate-pulse" id="absent-days-indicator-{{ $employee->id }}" style="display: none;"></div>
                                                </div>
                                                <div class="text-xs text-red-600 mt-1 text-center">من قاعدة البيانات</div>
                                            </div>

                                            <!-- Overtime Hours -->
                                            <div>
                                                <label class="block text-sm font-medium text-blue-700 mb-1">⏰ ساعات إضافية</label>
                                                <div class="relative">
                                                    <input type="number"
                                                           name="overtime_hours[{{ $employee->id }}]"
                                                           id="overtime_hours_{{ $employee->id }}"
                                                           value="{{ old('overtime_hours.' . $employee->id, 0) }}"
                                                           step="0.5"
                                                           min="0"
                                                           class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center font-bold text-blue-700 bg-blue-50"
                                                           onchange="calculateOvertimeBonus({{ $employee->id }})"
                                                           readonly>
                                                    <div class="absolute top-0 right-0 -mr-1 -mt-1 w-3 h-3 bg-blue-500 rounded-full animate-pulse" id="overtime-indicator-{{ $employee->id }}" style="display: none;"></div>
                                                </div>
                                                <div class="text-xs text-blue-600 mt-1 text-center">من قاعدة البيانات</div>
                                                <div class="text-xs text-gray-500 mt-1 text-center" title="المعادلة: ساعات إضافية × الراتب الأساسي ÷ 208">
                                                    🧮 حساب: ساعات × راتب ÷ 208
                                                </div>
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
                                                <!-- Dynamic Balance Button -->
                                                <button type="button"
                                                        id="balance-btn-{{ $employee->id }}"
                                                        onclick="handleBalanceAction({{ $employee->id }})"
                                                        class="balance-action-btn px-2 py-1 rounded text-xs transition-colors"
                                                        style="display: none;">
                                                    💰 رصيد
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
    
    // Update title when month changes
    updateTitleFromMonth();
    
    // Auto-load current month data
    const monthInput = document.getElementById('payroll_month');
    if (monthInput && monthInput.value) {
        setTimeout(() => {
            updatePayrollData();
        }, 1000);
    }
});

// Update payroll data when month is selected
function updatePayrollData() {
    const monthInput = document.getElementById('payroll_month');
    const selectedMonth = monthInput.value;
    
    if (!selectedMonth) return;
    
    // Update title automatically
    updateTitleFromMonth();
    
    // Show loading indicator
    showLoadingIndicator();
    
    // Reset all employee fields first
    resetAllEmployeeFields();
    
    // Fetch attendance data for all employees
    fetch('/payroll/get-monthly-attendance', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            month: selectedMonth
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('تم جلب البيانات بنجاح!', 'success');
            updateEmployeeAttendanceData(data.attendance);
        } else {
            showNotification('حدث خطأ في جلب بيانات الحضور: ' + (data.message || 'خطأ غير معروف'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('حدث خطأ في الاتصال بالخادم', 'error');
    })
    .finally(() => {
        hideLoadingIndicator();
    });
}

function resetAllEmployeeFields() {
    // Reset all employee statistics and fields
    document.querySelectorAll('.employee-stats').forEach(stats => {
        stats.querySelectorAll('.attendance-days, .absence-days, .overtime-hours, .attendance-rate').forEach(span => {
            span.textContent = '-';
        });
    });
    
    // Reset form fields
    document.querySelectorAll('[id^="working_days_"], [id^="absent_days_"], [id^="overtime_hours_"]').forEach(input => {
        input.value = '0';
        input.classList.remove('value-updated');
    });
    
    // Hide all balance alerts
    document.querySelectorAll('.balance-alert').forEach(alert => {
        alert.classList.add('hidden');
    });
}

function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: '✅',
        error: '❌',
        info: 'ℹ️'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2 animate-pulse`;
    notification.innerHTML = `
        <span>${icons[type]}</span>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function updateTitleFromMonth() {
    const monthInput = document.getElementById('payroll_month');
    const titleInput = document.getElementById('title');
    
    if (monthInput.value && !titleInput.value) {
        const date = new Date(monthInput.value + '-01');
        const monthNames = [
            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
        ];
        const monthName = monthNames[date.getMonth()];
        const year = date.getFullYear();
        titleInput.value = `راتب شهر ${monthName} ${year}`;
    }
}

function updateEmployeeAttendanceData(attendanceData) {
    Object.keys(attendanceData).forEach(employeeId => {
        const data = attendanceData[employeeId];
        
        // Update statistics display with animations
        const statsDiv = document.getElementById(`stats-${employeeId}`);
        if (statsDiv) {
            // Add pulse animation to show data is being updated
            statsDiv.classList.add('animate-pulse');
            
            setTimeout(() => {
                statsDiv.querySelector('.attendance-days').textContent = data.working_days;
                statsDiv.querySelector('.absence-days').textContent = data.absent_days;
                statsDiv.querySelector('.overtime-hours').textContent = data.overtime_hours.toFixed(1) + ' ساعة';
                statsDiv.querySelector('.attendance-rate').textContent = data.attendance_summary.attendance_rate + '%';
                
                // Remove pulse animation
                statsDiv.classList.remove('animate-pulse');
            }, 500);
        }
        
        // Update balance alert with enhanced display
        updateBalanceAlert(employeeId, data);
        
        // Update form fields with visual indicators
        updateFormFieldWithIndicator(employeeId, 'working_days', data.working_days, 'working-days-indicator');
        updateFormFieldWithIndicator(employeeId, 'absent_days', data.absent_days, 'absent-days-indicator');
        updateFormFieldWithIndicator(employeeId, 'overtime_hours', data.overtime_hours.toFixed(1), 'overtime-indicator');
        
        // Store balance data for later use
        window.employeeBalanceData = window.employeeBalanceData || {};
        window.employeeBalanceData[employeeId] = data;
        
        // Update balance action button with real data
        updateBalanceActionButton(employeeId, data.net_balance, data.credit_balance, data.debit_balance);
        
        // Calculate salary adjustments
        setTimeout(() => {
            calculateSalaryDeduction(employeeId);
            calculateOvertimeBonus(employeeId);
        }, 700);
    });
}

function updateFormFieldWithIndicator(employeeId, fieldName, value, indicatorId) {
    const input = document.getElementById(`${fieldName}_${employeeId}`);
    const indicator = document.getElementById(`${indicatorId}-${employeeId}`);
    
    if (input && indicator) {
        // Show indicator
        indicator.style.display = 'block';
        
        // Add loading effect
        input.classList.add('animate-pulse');
        
        setTimeout(() => {
            // Update value
            input.value = value;
            
            // Remove loading effect
            input.classList.remove('animate-pulse');
            
            // Flash effect to show update
            input.classList.add('ring-2', 'ring-emerald-400');
            
            setTimeout(() => {
                input.classList.remove('ring-2', 'ring-emerald-400');
                indicator.style.display = 'none';
            }, 1500);
        }, 300);
    }
}

function updateBalanceAlert(employeeId, data) {
    const alertDiv = document.getElementById(`balance-alert-${employeeId}`);
    if (!alertDiv) return;
    
    const balanceStatus = data.balance_status;
    
    // Always show balance alert for better visibility
    alertDiv.classList.remove('hidden');
    
    // Set colors based on balance type
    let bgColor, borderColor;
    switch(balanceStatus.color) {
        case 'green':
            bgColor = 'bg-green-50';
            borderColor = 'border-green-400';
            break;
        case 'red':
            bgColor = 'bg-red-50';
            borderColor = 'border-red-400';
            break;
        default:
            bgColor = 'bg-gray-50';
            borderColor = 'border-gray-400';
    }
    
    alertDiv.className = `balance-alert p-3 rounded-lg border-l-4 ${borderColor} ${bgColor}`;
    
    // Update amounts with formatting and animations
    updateBalanceValue(alertDiv, '.credit-amount', data.credit_balance, 'green');
    updateBalanceValue(alertDiv, '.debit-amount', data.debit_balance, 'red');
    updateBalanceValue(alertDiv, '.net-amount', Math.abs(data.net_balance), balanceStatus.color);
    
    // Update balance action button
    updateBalanceActionButton(employeeId, data.net_balance, data.credit_balance, data.debit_balance);
}

function updateBalanceValue(container, selector, value, colorClass) {
    const element = container.querySelector(selector);
    if (element) {
        element.classList.add('animate-pulse');
        
        setTimeout(() => {
            element.textContent = formatCurrency(value);
            element.classList.remove('animate-pulse');
            element.classList.add(`text-${colorClass}-700`);
            
            // Flash effect
            element.classList.add('scale-110', 'font-extrabold');
            setTimeout(() => {
                element.classList.remove('scale-110', 'font-extrabold');
            }, 300);
        }, 200);
    }
}

function updateBalanceAlert(employeeId, data) {
    const alertDiv = document.getElementById(`balance-alert-${employeeId}`);
    if (!alertDiv) return;
    
    const balanceStatus = data.balance_status;
    
    // Show/hide alert based on balance
    if (data.net_balance !== 0) {
        alertDiv.classList.remove('hidden');
        
        // Set colors based on balance type
        alertDiv.className = `balance-alert mt-2 p-2 rounded border-l-4 border-${balanceStatus.color}-400 bg-${balanceStatus.color}-50`;
        
        // Update message and amounts
        alertDiv.querySelector('.balance-message').textContent = balanceStatus.message;
        alertDiv.querySelector('.balance-message').className = `font-medium balance-message text-${balanceStatus.color}-800`;
        
        alertDiv.querySelector('.credit-amount').textContent = formatCurrency(data.credit_balance);
        alertDiv.querySelector('.debit-amount').textContent = formatCurrency(data.debit_balance);
        alertDiv.querySelector('.net-amount').textContent = formatCurrency(Math.abs(data.net_balance));
        alertDiv.querySelector('.net-amount').className = `net-amount font-bold text-${balanceStatus.color}-700`;
    } else {
        alertDiv.classList.add('hidden');
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR',
        minimumFractionDigits: 2
    }).format(amount);
}

function showBalanceDetails(employeeId) {
    const data = window.employeeBalanceData?.[employeeId];
    if (!data || !data.recent_transactions) return;
    
    let detailsHTML = `
        <div class="mb-4">
            <h4 class="text-lg font-bold text-gray-900 mb-2">تفاصيل رصيد الموظف</h4>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center p-3 bg-green-50 rounded">
                    <div class="text-green-600 font-medium">إجمالي الدائن</div>
                    <div class="text-xl font-bold text-green-700">${formatCurrency(data.credit_balance)}</div>
                </div>
                <div class="text-center p-3 bg-red-50 rounded">
                    <div class="text-red-600 font-medium">إجمالي المدين</div>
                    <div class="text-xl font-bold text-red-700">${formatCurrency(data.debit_balance)}</div>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded">
                    <div class="text-gray-600 font-medium">الصافي</div>
                    <div class="text-xl font-bold ${data.net_balance >= 0 ? 'text-green-700' : 'text-red-700'}">
                        ${formatCurrency(Math.abs(data.net_balance))}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    if (data.recent_transactions.length > 0) {
        detailsHTML += `
            <div>
                <h5 class="font-medium text-gray-900 mb-2">آخر المعاملات:</h5>
                <div class="space-y-2">
        `;
        
        data.recent_transactions.forEach(transaction => {
            const typeClass = transaction.type === 'credit' ? 'text-green-600' : 'text-red-600';
            const typeLabel = transaction.type === 'credit' ? 'دائن' : 'مدين';
            
            detailsHTML += `
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded text-sm">
                    <div>
                        <span class="${typeClass} font-medium">${typeLabel}: ${formatCurrency(transaction.amount)}</span>
                        <div class="text-gray-600">${transaction.notes || 'بدون ملاحظات'}</div>
                    </div>
                    <div class="text-gray-500">${transaction.date}</div>
                </div>
            `;
        });
        
        detailsHTML += '</div></div>';
    }
    
    // Show in modal
    showModal('تفاصيل رصيد الموظف', detailsHTML);
}

function showModal(title, content) {
    const modalHTML = `
        <div id="balance-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-full mx-4 max-h-96 overflow-y-auto">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-bold">${title}</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    ${content}
                </div>
                <div class="p-4 border-t text-right">
                    <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function closeModal() {
    const modal = document.getElementById('balance-modal');
    if (modal) {
        modal.remove();
    }
}

function calculateSalaryDeduction(employeeId) {
    const salaryInput = document.querySelector(`input[name="salary[${employeeId}]"]`);
    const absentDaysInput = document.getElementById(`absent_days_${employeeId}`);
    
    if (!salaryInput || !absentDaysInput) return;
    
    const baseSalary = parseFloat(salaryInput.value) || 0;
    const absentDays = parseInt(absentDaysInput.value) || 0;
    
    if (baseSalary > 0 && absentDays > 0) {
        // Calculate deduction using daily rate based on 208 working hours per month
        // Assuming 8 hours per working day: 208 ÷ 8 = 26 working days per month
        const workingDaysPerMonth = 26;
        const dailySalary = baseSalary / workingDaysPerMonth;
        const deductionAmount = dailySalary * absentDays;
        
        // Auto-add absence deduction
        addAutomaticDeduction(employeeId, 'خصم غياب', deductionAmount, `${absentDays} أيام غياب × ${dailySalary.toFixed(2)} ريال/يوم`);
    }
}

function calculateOvertimeBonus(employeeId) {
    const salaryInput = document.querySelector(`input[name="salary[${employeeId}]"]`);
    const overtimeHoursInput = document.getElementById(`overtime_hours_${employeeId}`);
    
    if (!salaryInput || !overtimeHoursInput) return;
    
    const baseSalary = parseFloat(salaryInput.value) || 0;
    const overtimeHours = parseFloat(overtimeHoursInput.value) || 0;
    
    if (baseSalary > 0 && overtimeHours > 0) {
        // Calculate overtime bonus using the formula: overtime_hours × base_salary / 208
        const bonusAmount = (overtimeHours * baseSalary) / 208;
        
        // Auto-add overtime bonus
        addAutomaticBonus(employeeId, 'بدل ساعات إضافية', bonusAmount, `${overtimeHours} ساعة إضافية × ${baseSalary} ÷ 208`);
    }
}

function addAutomaticDeduction(employeeId, type, amount, notes) {
    // Remove existing automatic deduction of the same type
    removeAutomaticItem(employeeId, type, 'deduction');
    
    const container = document.querySelector(`#deductions-${employeeId} .deductions-list`);
    if (!container) return;
    
    const deductionId = `auto_deduction_${employeeId}_${type.replace(/\s+/g, '_')}`;
    
    // Special styling for balance-related deductions
    const isBalanceRelated = type.includes('رصيد');
    const specialClass = isBalanceRelated ? 'border-orange-200 bg-orange-50' : 'border-red-200';
    const specialIcon = isBalanceRelated ? '💸' : '';
    
    const deductionHTML = `
        <div id="${deductionId}" class="flex items-center gap-2 bg-red-50 p-2 rounded border ${specialClass}">
            <input type="text"
                   name="deductions[${employeeId}][type][]"
                   value="${specialIcon} ${type}"
                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm"
                   readonly>
            <input type="number"
                   name="deductions[${employeeId}][amount][]"
                   value="${amount.toFixed(2)}"
                   step="0.01"
                   min="0"
                   class="w-24 px-2 py-1 border border-gray-300 rounded text-sm">
            <input type="text"
                   name="deductions[${employeeId}][notes][]"
                   value="${notes}"
                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm"
                   readonly>
            <span class="text-xs text-blue-600 px-2">${isBalanceRelated ? 'رصيد' : 'تلقائي'}</span>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', deductionHTML);
}

function addAutomaticBonus(employeeId, type, amount, notes) {
    // Remove existing automatic bonus of the same type
    removeAutomaticItem(employeeId, type, 'bonus');
    
    const container = document.querySelector(`#bonuses-${employeeId} .bonuses-list`);
    if (!container) return;
    
    const bonusId = `auto_bonus_${employeeId}_${type.replace(/\s+/g, '_')}`;
    
    // Special styling for balance-related bonuses
    const isBalanceRelated = type.includes('رصيد');
    const specialClass = isBalanceRelated ? 'border-orange-200 bg-orange-50' : 'border-green-200';
    const specialIcon = isBalanceRelated ? '💰' : '';
    
    const bonusHTML = `
        <div id="${bonusId}" class="flex items-center gap-2 bg-green-50 p-2 rounded border ${specialClass}">
            <input type="text"
                   name="bonuses[${employeeId}][type][]"
                   value="${specialIcon} ${type}"
                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm"
                   readonly>
            <input type="number"
                   name="bonuses[${employeeId}][amount][]"
                   value="${amount.toFixed(2)}"
                   step="0.01"
                   min="0"
                   class="w-24 px-2 py-1 border border-gray-300 rounded text-sm">
            <input type="text"
                   name="bonuses[${employeeId}][notes][]"
                   value="${notes}"
                   class="flex-1 px-2 py-1 border border-gray-300 rounded text-sm"
                   readonly>
            <span class="text-xs text-blue-600 px-2">${isBalanceRelated ? 'رصيد' : 'تلقائي'}</span>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', bonusHTML);
}

function removeAutomaticItem(employeeId, type, itemType) {
    const elementId = `auto_${itemType}_${employeeId}_${type.replace(/\s+/g, '_')}`;
    const element = document.getElementById(elementId);
    if (element) {
        element.remove();
    }
}

function recalculateAllAdjustments(employeeId) {
    // Recalculate both salary deduction and overtime bonus when base salary changes
    setTimeout(() => {
        calculateSalaryDeduction(employeeId);
        calculateOvertimeBonus(employeeId);
    }, 100);
}

function updateBalanceActionButton(employeeId, netBalance, creditBalance, debitBalance) {
    const button = document.getElementById(`balance-btn-${employeeId}`);
    
    if (!button) {
        console.warn(`Balance button not found for employee ${employeeId}`);
        return;
    }
    
    // Store balance data for later use
    button.setAttribute('data-net-balance', netBalance);
    button.setAttribute('data-credit-balance', creditBalance);
    button.setAttribute('data-debit-balance', debitBalance);
    
    if (netBalance === 0) {
        // Hide button if balance is zero
        button.style.display = 'none';
    } else if (netBalance > 0) {
        // Employee has credit balance - show "Add Credit Balance" button
        button.style.display = 'inline-block';
        button.className = 'balance-action-btn credit px-2 py-1 rounded text-xs transition-colors';
        button.innerHTML = '💰 إضافة رصيد دائن';
        button.title = `الموظف له رصيد دائن: ${formatCurrency(netBalance)}`;
    } else {
        // Employee has debit balance - show "Deduct Debit Balance" button
        button.style.display = 'inline-block';
        button.className = 'balance-action-btn debit px-2 py-1 rounded text-xs transition-colors';
        button.innerHTML = '💸 استقطاع رصيد مدين';
        button.title = `على الموظف رصيد مدين: ${formatCurrency(Math.abs(netBalance))}`;
    }
}

function handleBalanceAction(employeeId) {
    const button = document.getElementById(`balance-btn-${employeeId}`);
    if (!button) return;
    
    const netBalance = parseFloat(button.getAttribute('data-net-balance')) || 0;
    const creditBalance = parseFloat(button.getAttribute('data-credit-balance')) || 0;
    const debitBalance = parseFloat(button.getAttribute('data-debit-balance')) || 0;
    
    if (netBalance > 0) {
        // Add credit balance as bonus
        showBalanceActionModal(employeeId, 'credit', netBalance, creditBalance, debitBalance);
    } else if (netBalance < 0) {
        // Add debit balance as deduction
        showBalanceActionModal(employeeId, 'debit', Math.abs(netBalance), creditBalance, debitBalance);
    }
}

function showBalanceActionModal(employeeId, type, amount, creditBalance, debitBalance) {
    const isCredit = type === 'credit';
    const title = isCredit ? 'إضافة رصيد دائن' : 'استقطاع رصيد مدين';
    const actionText = isCredit ? 'إضافة كبدل' : 'إضافة كاستقطاع';
    const colorClass = isCredit ? 'green' : 'red';
    
    const modalHTML = `
        <div id="balance-action-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-bold text-${colorClass}-700">${title}</h3>
                    <button onclick="closeBalanceActionModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <div class="grid grid-cols-3 gap-2 text-sm mb-4">
                            <div class="text-center p-2 bg-green-50 rounded border">
                                <div class="text-green-600 font-medium">رصيد دائن</div>
                                <div class="font-bold text-green-700">${formatCurrency(creditBalance)}</div>
                            </div>
                            <div class="text-center p-2 bg-red-50 rounded border">
                                <div class="text-red-600 font-medium">رصيد مدين</div>
                                <div class="font-bold text-red-700">${formatCurrency(debitBalance)}</div>
                            </div>
                            <div class="text-center p-2 bg-${colorClass}-50 rounded border">
                                <div class="text-${colorClass}-600 font-medium">المبلغ</div>
                                <div class="font-bold text-${colorClass}-700">${formatCurrency(amount)}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">المبلغ</label>
                            <input type="number" 
                                   id="balance-amount" 
                                   value="${amount.toFixed(2)}"
                                   step="0.01" 
                                   min="0" 
                                   max="${amount.toFixed(2)}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-${colorClass}-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                            <input type="text" 
                                   id="balance-notes" 
                                   value="${isCredit ? 'إضافة رصيد دائن من سجل الموظف' : 'استقطاع رصيد مدين من سجل الموظف'}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-${colorClass}-500">
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t flex justify-end gap-2">
                    <button onclick="closeBalanceActionModal()" 
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        إلغاء
                    </button>
                    <button onclick="confirmBalanceAction(${employeeId}, '${type}')" 
                            class="bg-${colorClass}-500 text-white px-4 py-2 rounded hover:bg-${colorClass}-600">
                        ${actionText}
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function confirmBalanceAction(employeeId, type) {
    const amountInput = document.getElementById('balance-amount');
    const notesInput = document.getElementById('balance-notes');
    
    if (!amountInput || !notesInput) return;
    
    const amount = parseFloat(amountInput.value) || 0;
    const notes = notesInput.value || '';
    
    if (amount <= 0) {
        alert('يرجى إدخال مبلغ صحيح');
        return;
    }
    
    if (type === 'credit') {
        // Add as bonus
        addAutomaticBonus(employeeId, 'رصيد دائن', amount, notes);
    } else {
        // Add as deduction
        addAutomaticDeduction(employeeId, 'رصيد مدين', amount, notes);
    }
    
    // Record the balance transaction
    recordBalanceTransaction(employeeId, type, amount, notes);
    
    closeBalanceActionModal();
    
    showNotification(
        type === 'credit' ? 'تم إضافة الرصيد الدائن كبدل' : 'تم إضافة الرصيد المدين كاستقطاع', 
        'success'
    );
}

function recordBalanceTransaction(employeeId, type, amount, notes) {
    // Record the balance transaction in the database
    fetch('/employee-balances/record-transaction', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            employee_id: employeeId,
            type: type === 'credit' ? 'debit' : 'credit', // Reverse the type because we're using the balance
            amount: amount,
            notes: notes + ' - تم التطبيق في مسيرة الراتب'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Balance transaction recorded successfully');
        } else {
            console.error('Failed to record balance transaction:', data.message);
        }
    })
    .catch(error => {
        console.error('Error recording balance transaction:', error);
    });
}

function closeBalanceActionModal() {
    const modal = document.getElementById('balance-action-modal');
    if (modal) {
        modal.remove();
    }
}

function testBalanceButtons() {
    console.log('=== Testing Balance Buttons ===');
    
    const checkboxes = document.querySelectorAll('input[name="employees[]"]:checked');
    console.log(`Found ${checkboxes.length} selected employees`);
    
    checkboxes.forEach((checkbox, index) => {
        const employeeId = checkbox.value;
        console.log(`Testing employee ID: ${employeeId}`);
        
        // Test different balance scenarios
        let netBalance, creditBalance, debitBalance;
        
        switch(index % 3) {
            case 0:
                // Credit balance
                creditBalance = 2000;
                debitBalance = 500;
                netBalance = creditBalance - debitBalance;
                break;
            case 1:
                // Debit balance
                creditBalance = 300;
                debitBalance = 800;
                netBalance = creditBalance - debitBalance;
                break;
            case 2:
                // Zero balance
                creditBalance = 1000;
                debitBalance = 1000;
                netBalance = 0;
                break;
        }
        
        console.log(`Employee ${employeeId}: Credit=${creditBalance}, Debit=${debitBalance}, Net=${netBalance}`);
        updateBalanceActionButton(employeeId, netBalance, creditBalance, debitBalance);
    });
    
    showNotification('تم اختبار أزرار الرصيد - تحقق من وحدة التحكم للتفاصيل', 'info');
}

function showLoadingIndicator() {
    // Add loading overlay
    const loadingHTML = `
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
                <div>
                    <div class="font-medium text-gray-900">جاري تحميل البيانات...</div>
                    <div class="text-sm text-gray-600">جلب بيانات الحضور والرصيد للموظفين</div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', loadingHTML);
}

function hideLoadingIndicator() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

function toggleEmployeeFields(employeeId) {
    const checkbox = document.querySelector(`input[name="employees[]"][value="${employeeId}"]`);
    const fields = document.getElementById(`fields-${employeeId}`);
    const stats = document.getElementById(`stats-${employeeId}`);
    
    if (checkbox && fields) {
        if (checkbox.checked) {
            fields.style.display = 'block';
            if (stats) stats.style.display = 'block';
            
            // Show balance button - use existing data if available, otherwise fetch
            const existingData = window.employeeBalanceData?.[employeeId];
            if (existingData) {
                console.log(`Using existing data for employee ${employeeId}:`, existingData);
                updateBalanceActionButton(employeeId, existingData.net_balance, existingData.credit_balance, existingData.debit_balance);
            } else {
                console.log(`No existing data for employee ${employeeId}, will be updated when month is selected`);
                // Don't show button until we have real data - it will be shown when month data loads
            }
        } else {
            fields.style.display = 'none';
            if (stats) stats.style.display = 'none';
            
            // Hide balance button when employee is deselected
            const button = document.getElementById(`balance-btn-${employeeId}`);
            if (button) button.style.display = 'none';
        }
    }
}
</script>
@endsection
