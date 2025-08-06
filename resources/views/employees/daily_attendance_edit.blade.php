@extends('layouts.app')

@section('title', 'تعديل الحضور اليومي - ' . $selectedDate->format('Y/m/d'))

@section('content')
<div class="space-y-6">
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="ri-check-circle-line text-green-600 text-xl ml-2"></i>
            <span class="text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="ri-error-warning-line text-red-600 text-xl ml-2"></i>
            <span class="text-red-800 font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="ri-error-warning-line text-red-600 text-xl ml-2 mt-0.5"></i>
            <div>
                <h4 class="text-red-800 font-medium mb-2">يرجى تصحيح الأخطاء التالية:</h4>
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">✏️ تعديل الحضور اليومي</h1>
                <p class="text-blue-100">تعديل وإدارة بيانات حضور الموظفين ليوم 
                    @php
                        $arabicDays = [
                            'Monday' => 'الاثنين',
                            'Tuesday' => 'الثلاثاء', 
                            'Wednesday' => 'الأربعاء',
                            'Thursday' => 'الخميس',
                            'Friday' => 'الجمعة',
                            'Saturday' => 'السبت',
                            'Sunday' => 'الأحد'
                        ];
                        $arabicMonths = [
                            'January' => 'يناير', 'February' => 'فبراير', 'March' => 'مارس',
                            'April' => 'أبريل', 'May' => 'مايو', 'June' => 'يونيو',
                            'July' => 'يوليو', 'August' => 'أغسطس', 'September' => 'سبتمبر',
                            'October' => 'أكتوبر', 'November' => 'نوفمبر', 'December' => 'ديسمبر'
                        ];
                        $dayName = $arabicDays[$selectedDate->format('l')] ?? $selectedDate->format('l');
                        $monthName = $arabicMonths[$selectedDate->format('F')] ?? $selectedDate->format('F');
                    @endphp
                    {{ $dayName }}، {{ $selectedDate->format('d') }} {{ $monthName }} {{ $selectedDate->format('Y') }}
                </p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="window.close()" 
                        class="bg-white text-blue-600 px-6 py-3 rounded-xl font-medium hover:bg-blue-50 transition-all duration-200 flex items-center">
                    <i class="ri-close-line ml-2"></i>
                    إغلاق بدون حفظ
                </button>
                <a href="/employees/daily-attendance-report?date={{ $date }}" 
                   target="_blank"
                   class="bg-blue-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-400 transition-all duration-200 flex items-center">
                    <i class="ri-eye-line ml-2"></i>
                    عرض التقرير
                </a>
            </div>
        </div>
        
        <!-- Date Info -->
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
            <div class="flex items-center justify-center">
                <div class="text-center">
                    <i class="ri-edit-2-line text-4xl text-white mb-2"></i>
                    <h2 class="text-2xl font-bold">{{ $selectedDate->format('d/m/Y') }}</h2>
                    <p class="text-blue-100">{{ $dayName }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                        <i class="ri-edit-box-line text-blue-600"></i>
                        تعديل بيانات الحضور لتاريخ: {{ $selectedDate->format('d/m/Y') }}
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">يمكنك تعديل جميع بيانات الحضور لهذا اليوم</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                        {{ count($employees) }} موظف
                    </span>
                    <button type="button" onclick="fillAllAsPresent()" class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm">
                        <i class="ri-check-double-line mr-1"></i>
                        تعيين الكل حاضر
                    </button>
                </div>
            </div>
        </div>

        <form action="{{ route('employees.daily-attendance-update') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900 text-white">
                        <tr>
                            <th class="px-4 py-3 text-right font-semibold">الموظف</th>
                            <th class="px-4 py-3 text-center font-semibold">الحالة</th>
                            <th class="px-4 py-3 text-center font-semibold">وقت الحضور</th>
                            <th class="px-4 py-3 text-center font-semibold">وقت الانصراف</th>
                            <th class="px-4 py-3 text-center font-semibold">ساعات العمل</th>
                            <th class="px-4 py-3 text-center font-semibold">دقائق التأخير</th>
                            <th class="px-4 py-3 text-center font-semibold">ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($employees as $index => $employee)
                        @php $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-gray-50'; @endphp
                        <tr class="{{ $rowClass }} hover:bg-blue-50 transition-colors">
                            <!-- Employee Info -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($employee->photo)
                                        <img src="{{ asset('storage/' . $employee->photo) }}"
                                             alt="{{ $employee->name }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-medium text-xs">{{ mb_substr($employee->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="mr-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $employee->position ?? 'غير محدد' }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-4 py-3 text-center">
                                <select name="attendance[{{ $employee->id }}][status]" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                        onchange="updateRowVisibility({{ $employee->id }}, this.value)">
                                    <option value="absent" {{ $employee->attendance_status === 'absent' ? 'selected' : '' }}>غائب</option>
                                    <option value="present" {{ $employee->attendance_status === 'present' ? 'selected' : '' }}>حاضر</option>
                                    <option value="late" {{ $employee->attendance_status === 'late' ? 'selected' : '' }}>متأخر</option>
                                    <option value="leave" {{ $employee->attendance_status === 'leave' ? 'selected' : '' }}>إجازة اعتيادية</option>
                                    <option value="sick_leave" {{ $employee->attendance_status === 'sick_leave' ? 'selected' : '' }}>إجازة مرضية</option>
                                </select>
                            </td>
                            
                            <!-- Check In Time -->
                            <td class="px-4 py-3 text-center">
                                <input type="time" 
                                       name="attendance[{{ $employee->id }}][check_in]" 
                                       value="{{ $employee->check_in }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                       onchange="calculateWorkingHours({{ $employee->id }})">
                            </td>
                            
                            <!-- Check Out Time -->
                            <td class="px-4 py-3 text-center">
                                <input type="time" 
                                       name="attendance[{{ $employee->id }}][check_out]" 
                                       value="{{ $employee->check_out }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                       onchange="calculateWorkingHours({{ $employee->id }})">
                            </td>
                            
                            <!-- Working Hours -->
                            <td class="px-4 py-3 text-center">
                                <input type="number" 
                                       name="attendance[{{ $employee->id }}][working_hours]" 
                                       value="{{ $employee->working_hours }}"
                                       step="0.1" 
                                       min="0" 
                                       max="24"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            </td>
                            
                            <!-- Late Minutes -->
                            <td class="px-4 py-3 text-center">
                                <input type="number" 
                                       name="attendance[{{ $employee->id }}][late_minutes]" 
                                       value="{{ $employee->late_minutes }}"
                                       min="0" 
                                       max="480"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            </td>
                            
                            <!-- Notes -->
                            <td class="px-4 py-3 text-center">
                                <input type="text" 
                                       name="attendance[{{ $employee->id }}][notes]" 
                                       value="{{ $employee->notes }}"
                                       placeholder="ملاحظات..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                <div class="flex gap-3">
                    <button type="button" onclick="resetForm()" class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-colors">
                        <i class="ri-refresh-line mr-2"></i>
                        إعادة تعيين
                    </button>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="window.close()" class="bg-red-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-red-600 transition-colors">
                        <i class="ri-close-line mr-2"></i>
                        إلغاء
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-green-700 transition-colors">
                        <i class="ri-save-line mr-2"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Fill all employees as present with default times
function fillAllAsPresent() {
    if (confirm('هل تريد تعيين جميع الموظفين كحاضرين مع أوقات افتراضية؟')) {
        const statusSelects = document.querySelectorAll('select[name*="[status]"]');
        const checkInInputs = document.querySelectorAll('input[name*="[check_in]"]');
        const checkOutInputs = document.querySelectorAll('input[name*="[check_out]"]');
        const workingHoursInputs = document.querySelectorAll('input[name*="[working_hours]"]');
        
        statusSelects.forEach((select, index) => {
            select.value = 'present';
            if (checkInInputs[index]) checkInInputs[index].value = '08:00';
            if (checkOutInputs[index]) checkOutInputs[index].value = '17:00';
            if (workingHoursInputs[index]) workingHoursInputs[index].value = '8';
        });
    }
}

// Calculate working hours automatically
function calculateWorkingHours(employeeId) {
    const checkIn = document.querySelector(`input[name="attendance[${employeeId}][check_in]"]`).value;
    const checkOut = document.querySelector(`input[name="attendance[${employeeId}][check_out]"]`).value;
    
    if (checkIn && checkOut) {
        const checkInTime = new Date(`2000-01-01T${checkIn}:00`);
        const checkOutTime = new Date(`2000-01-01T${checkOut}:00`);
        
        if (checkOutTime > checkInTime) {
            let totalMinutes = (checkOutTime - checkInTime) / (1000 * 60);
            
            // Subtract 1 hour for lunch break if working more than 6 hours
            if (totalMinutes > 360) {
                totalMinutes -= 60;
            }
            
            const workingHours = Math.max(0, totalMinutes / 60);
            document.querySelector(`input[name="attendance[${employeeId}][working_hours]"]`).value = workingHours.toFixed(1);
            
            // Calculate late minutes if check-in is after 8:00 AM
            const standardStart = new Date(`2000-01-01T08:00:00`);
            if (checkInTime > standardStart) {
                const lateMinutes = (checkInTime - standardStart) / (1000 * 60);
                document.querySelector(`input[name="attendance[${employeeId}][late_minutes]"]`).value = Math.round(lateMinutes);
                
                // Change status to late if more than 15 minutes late
                if (lateMinutes > 15) {
                    document.querySelector(`select[name="attendance[${employeeId}][status]"]`).value = 'late';
                }
            }
        }
    }
}

// Update row visibility based on status
function updateRowVisibility(employeeId, status) {
    const checkInInput = document.querySelector(`input[name="attendance[${employeeId}][check_in]"]`);
    const checkOutInput = document.querySelector(`input[name="attendance[${employeeId}][check_out]"]`);
    const workingHoursInput = document.querySelector(`input[name="attendance[${employeeId}][working_hours]"]`);
    const lateMinutesInput = document.querySelector(`input[name="attendance[${employeeId}][late_minutes]"]`);
    
    if (status === 'absent') {
        // Clear time inputs for absent employees
        checkInInput.value = '';
        checkOutInput.value = '';
        workingHoursInput.value = '0';
        lateMinutesInput.value = '0';
    } else if (status === 'leave' || status === 'sick_leave') {
        // Clear time inputs for employees on leave
        checkInInput.value = '';
        checkOutInput.value = '';
        workingHoursInput.value = '0';
        lateMinutesInput.value = '0';
    }
}

// Reset form to original values
function resetForm() {
    if (confirm('هل تريد إعادة تعيين النموذج إلى القيم الأصلية؟')) {
        location.reload();
    }
}

// Auto-calculate working hours when times change
document.addEventListener('DOMContentLoaded', function() {
    const checkInInputs = document.querySelectorAll('input[name*="[check_in]"]');
    const checkOutInputs = document.querySelectorAll('input[name*="[check_out]"]');
    
    checkInInputs.forEach(input => {
        input.addEventListener('change', function() {
            const employeeId = this.name.match(/\d+/)[0];
            calculateWorkingHours(employeeId);
        });
    });
    
    checkOutInputs.forEach(input => {
        input.addEventListener('change', function() {
            const employeeId = this.name.match(/\d+/)[0];
            calculateWorkingHours(employeeId);
        });
    });
});

// Confirm before leaving if form has been modified
let formModified = false;
document.addEventListener('change', function() {
    formModified = true;
});

window.addEventListener('beforeunload', function(e) {
    if (formModified) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Don't show warning after form submission
document.querySelector('form').addEventListener('submit', function() {
    formModified = false;
});
</script>

@endsection
