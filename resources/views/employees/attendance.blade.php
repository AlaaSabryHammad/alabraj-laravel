@extends('layouts.app')

@section('title', 'متابعة الحضور - شركة الأبراج للمقاولات')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">متابعة الحضور والانصراف</h1>
                    <p class="text-gray-600">تسجيل وإدارة حضور الموظفين</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('employees.index') }}"
                        class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة للموظفين
                    </a>
                    <button onclick="openDateModal()"
                        class="bg-purple-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-purple-600 transition-all duration-200 flex items-center">
                        <i class="ri-calendar-2-line ml-2"></i>
                        تقرير يوم محدد
                    </button>
                    <a href="{{ route('employees.attendance.report') }}"
                        class="bg-blue-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-600 transition-all duration-200 flex items-center">
                        <i class="ri-file-chart-line ml-2"></i>
                        تقرير الحضور الشهري
                    </a>
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                        <div class="flex items-center">
                            <i class="ri-calendar-check-line text-green-600 ml-2"></i>
                            <span id="current-date-attendance"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium mb-1">حضور اليوم</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['present'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl">
                        <i class="ri-check-circle-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-600 text-sm font-medium mb-1">غياب اليوم</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['absent'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-gradient-to-r from-red-500 to-red-600 p-3 rounded-xl">
                        <i class="ri-close-circle-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-600 text-sm font-medium mb-1">تأخير</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['late'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-3 rounded-xl">
                        <i class="ri-time-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium mb-1">إجازة</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['leave'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl">
                        <i class="ri-calendar-event-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-medium mb-1">ساعات إضافية</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_overtime_hours'] ?? 0, 1) }}</h3>
                        <p class="text-xs text-purple-500 mt-1">ساعة اليوم</p>
                    </div>
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-3 rounded-xl">
                        <i class="ri-timer-2-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900">حضور الموظفين لليوم</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الموظف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وقت
                                الحضور</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وقت
                                الانصراف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span title="عدد ساعات العمل المحددة في عقد الموظف">ساعات العقد</span>
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span title="ساعات العمل الفعلية المنجزة">ساعات العمل الفعلية</span>
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span title="الساعات الإضافية عن ساعات العقد المحددة">ساعات إضافية</span>
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($employees as $employee)
                            @php
                                $attendance = $employee->attendances->first();
                                $attendanceStatus = $attendance ? $attendance->status_text : 'غائب';
                                $statusColors = [
                                    'حاضر' => 'bg-green-100 text-green-800',
                                    'متأخر' => 'bg-yellow-100 text-yellow-800',
                                    'غائب' => 'bg-red-100 text-red-800',
                                    'إجازة' => 'bg-blue-100 text-blue-800',
                                    'إجازة مرضية' => 'bg-purple-100 text-purple-800',
                                    'مُعتذر' => 'bg-gray-100 text-gray-800',
                                ];
                                $arrivalTime = $attendance && $attendance->check_in ? $attendance->check_in : '-';
                                $departureTime = $attendance && $attendance->check_out ? $attendance->check_out : '-';

                                // Contract hours (from employee model)
                                $contractHours = $employee->working_hours ?? 8;

                                // Actual working hours
                                $actualWorkingHours = $attendance && $attendance->working_hours ? $attendance->working_hours : 0;

                                // Calculate overtime hours
                                $overtimeHours = $actualWorkingHours > $contractHours ? $actualWorkingHours - $contractHours : 0;

                                $workingHours = $actualWorkingHours > 0 ? number_format($actualWorkingHours, 1) . ' ساعة' : '-';
                                $contractHoursDisplay = number_format($contractHours, 1) . ' ساعة';
                                $overtimeDisplay = $overtimeHours > 0 ? number_format($overtimeHours, 1) . ' ساعة' : '-';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                            <span
                                                class="text-white font-medium text-sm">{{ mb_substr($employee->name, 0, 1) }}</span>
                                        </div>
                                        <div class="mr-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $employee->position }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $arrivalTime }}
                                    @if ($attendance && $attendance->late_minutes > 0)
                                        <span class="text-red-500 text-xs">(تأخر {{ $attendance->late_minutes }}
                                            دقيقة)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $departureTime }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="text-blue-600 font-medium">{{ $contractHoursDisplay }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $workingHours }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($overtimeHours > 0)
                                        <span class="text-green-600 font-semibold bg-green-50 px-2 py-1 rounded-md">
                                            <i class="ri-time-line text-xs"></i>
                                            {{ $overtimeDisplay }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$attendanceStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $attendanceStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2 space-x-reverse">
                                        @if (!$attendance)
                                            <button type="button" class="text-blue-600 hover:text-blue-900"
                                                title="تسجيل حضور"
                                                onclick="openCheckInModal({{ $employee->id }}, '{{ $employee->name }}')">
                                                <i class="ri-login-circle-line"></i>
                                            </button>
                                        @endif
                                        @if (
                                            $attendance &&
                                                $attendance->check_in &&
                                                !$attendance->check_out &&
                                                in_array($attendance->status, ['present', 'late']))
                                            <button type="button" class="text-red-600 hover:text-red-900"
                                                title="تسجيل انصراف"
                                                onclick="openCheckOutModal({{ $employee->id }}, '{{ $employee->name }}')">
                                                <i class="ri-logout-circle-line"></i>
                                            </button>
                                        @endif
                                        @if ($attendance)
                                            @php
                                                // Ensure proper date format for input[type="date"]
                                                $formattedDate =
                                                    $attendance->date instanceof \Carbon\Carbon
                                                        ? $attendance->date->format('Y-m-d')
                                                        : date('Y-m-d', strtotime($attendance->date));

                                                // Ensure time values are in HH:MM format
                                                $checkInTime = $attendance->check_in
                                                    ? date('H:i', strtotime($attendance->check_in))
                                                    : '';
                                                $checkOutTime = $attendance->check_out
                                                    ? date('H:i', strtotime($attendance->check_out))
                                                    : '';

                                                // Clean notes for JavaScript
                                                $cleanNotes = htmlspecialchars(
                                                    $attendance->notes ?: '',
                                                    ENT_QUOTES,
                                                    'UTF-8',
                                                );
                                                $cleanEmployeeName = htmlspecialchars(
                                                    $employee->name,
                                                    ENT_QUOTES,
                                                    'UTF-8',
                                                );
                                            @endphp
                                            <button type="button" class="text-gray-600 hover:text-gray-900"
                                                title="تعديل الحضور"
                                                onclick="openEditModal({{ $employee->id }}, '{{ $cleanEmployeeName }}', '{{ $formattedDate }}', '{{ $checkInTime }}', '{{ $checkOutTime }}', '{{ $attendance->status }}', '{{ $cleanNotes }}')">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                        @else
                                            <button type="button" class="text-gray-600 hover:text-gray-900"
                                                title="إضافة حضور"
                                                onclick="openEditModal({{ $employee->id }}, '{{ htmlspecialchars($employee->name, ENT_QUOTES, 'UTF-8') }}', '{{ date('Y-m-d') }}', '', '', 'absent', '')">
                                                <i class="ri-add-line"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div id="editAttendanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeEditModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">تعديل بيانات الحضور</h3>
                        <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>

                    <form id="editAttendanceForm" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الموظف</label>
                            <input type="text" id="employeeName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">التاريخ</label>
                            <input type="date" name="date" id="attendanceDate"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">وقت الحضور</label>
                            <input type="time" name="check_in" id="checkInTime"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">وقت الانصراف</label>
                            <input type="time" name="check_out" id="checkOutTime"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                            <select name="status" id="attendanceStatus"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="present">حاضر</option>
                                <option value="absent">غائب</option>
                                <option value="late">متأخر</option>
                                <option value="leave">إجازة</option>
                                <option value="sick_leave">إجازة مرضية</option>
                                <option value="excused">مُعتذر</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                            <textarea name="notes" id="attendanceNotes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="ملاحظات إضافية..."></textarea>
                        </div>

                        <div class="flex space-x-3 space-x-reverse pt-4">
                            <button type="submit"
                                class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                حفظ التغييرات
                            </button>
                            <button type="button" onclick="closeEditModal()"
                                class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-400 transition-colors">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-in Modal -->
    <div id="checkInModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeCheckInModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">تسجيل حضور الموظف</h3>
                        <button onclick="closeCheckInModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>

                    <div class="text-center mb-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-login-circle-line text-white text-2xl"></i>
                        </div>
                        <p class="text-gray-600">هل أنت متأكد من تسجيل حضور:</p>
                        <p class="text-xl font-semibold text-gray-900 mt-2" id="checkInEmployeeName"></p>
                        <p class="text-sm text-gray-500 mt-1">الوقت الحالي: <span id="currentTime"></span></p>
                    </div>

                    <form id="checkInForm" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات (اختيارية)</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="أي ملاحظات إضافية..."></textarea>
                        </div>

                        <div class="flex space-x-3 space-x-reverse pt-4">
                            <button type="submit"
                                class="flex-1 bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center justify-center">
                                <i class="ri-check-line ml-2"></i>
                                تأكيد الحضور
                            </button>
                            <button type="button" onclick="closeCheckInModal()"
                                class="flex-1 bg-gray-300 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-400 transition-colors">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-out Modal -->
    <div id="checkOutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeCheckOutModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">تسجيل انصراف الموظف</h3>
                        <button onclick="closeCheckOutModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>

                    <div class="text-center mb-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-logout-circle-line text-white text-2xl"></i>
                        </div>
                        <p class="text-gray-600">هل أنت متأكد من تسجيل انصراف:</p>
                        <p class="text-xl font-semibold text-gray-900 mt-2" id="checkOutEmployeeName"></p>
                        <p class="text-sm text-gray-500 mt-1">الوقت الحالي: <span id="currentCheckOutTime"></span></p>
                    </div>

                    <form id="checkOutForm" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات الانصراف (اختيارية)</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="أي ملاحظات حول الانصراف..."></textarea>
                        </div>

                        <div class="flex space-x-3 space-x-reverse pt-4">
                            <button type="submit"
                                class="flex-1 bg-red-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-700 transition-colors flex items-center justify-center">
                                <i class="ri-logout-circle-line ml-2"></i>
                                تأكيد الانصراف
                            </button>
                            <button type="button" onclick="closeCheckOutModal()"
                                class="flex-1 bg-gray-300 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-400 transition-colors">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Update current date
            function updateCurrentDate() {
                const now = new Date();
                const dateOptions = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };

                document.getElementById('current-date-attendance').textContent = now.toLocaleDateString('ar-SA', dateOptions);
            }

            // Open edit modal
            function openEditModal(employeeId, employeeName, date, checkIn, checkOut, status, notes) {
                console.log('Modal data received:', {
                    employeeId: employeeId,
                    employeeName: employeeName,
                    date: date,
                    checkIn: checkIn,
                    checkOut: checkOut,
                    status: status,
                    notes: notes
                });

                // Fill form fields with current data
                const employeeNameField = document.getElementById('employeeName');
                const attendanceDateField = document.getElementById('attendanceDate');
                const checkInTimeField = document.getElementById('checkInTime');
                const checkOutTimeField = document.getElementById('checkOutTime');
                const attendanceStatusField = document.getElementById('attendanceStatus');
                const attendanceNotesField = document.getElementById('attendanceNotes');

                if (employeeNameField) employeeNameField.value = employeeName || '';
                if (attendanceDateField) attendanceDateField.value = date || '';
                if (checkInTimeField) checkInTimeField.value = checkIn || '';
                if (checkOutTimeField) checkOutTimeField.value = checkOut || '';
                if (attendanceStatusField) attendanceStatusField.value = status || 'absent';
                if (attendanceNotesField) attendanceNotesField.value = notes || '';

                console.log('Fields after setting:', {
                    employeeName: employeeNameField ? employeeNameField.value : 'not found',
                    date: attendanceDateField ? attendanceDateField.value : 'not found',
                    checkIn: checkInTimeField ? checkInTimeField.value : 'not found',
                    checkOut: checkOutTimeField ? checkOutTimeField.value : 'not found',
                    status: attendanceStatusField ? attendanceStatusField.value : 'not found',
                    notes: attendanceNotesField ? attendanceNotesField.value : 'not found'
                });

                // Set form action
                document.getElementById('editAttendanceForm').action = `/employees/${employeeId}/attendance/edit`;

                // Show modal
                document.getElementById('editAttendanceModal').classList.remove('hidden');
            }

            // Close edit modal
            function closeEditModal() {
                document.getElementById('editAttendanceModal').classList.add('hidden');
            }

            // Open check-in modal
            function openCheckInModal(employeeId, employeeName) {
                document.getElementById('checkInEmployeeName').textContent = employeeName;

                // Update current time
                updateCurrentTime();

                // Set form action
                document.getElementById('checkInForm').action = `/employees/${employeeId}/check-in`;

                // Show modal
                document.getElementById('checkInModal').classList.remove('hidden');
            }

            // Close check-in modal
            function closeCheckInModal() {
                document.getElementById('checkInModal').classList.add('hidden');
            }

            // Open check-out modal
            function openCheckOutModal(employeeId, employeeName) {
                document.getElementById('checkOutEmployeeName').textContent = employeeName;

                // Update current time
                updateCheckOutCurrentTime();

                // Set form action
                document.getElementById('checkOutForm').action = `/employees/${employeeId}/check-out`;

                // Show modal
                document.getElementById('checkOutModal').classList.remove('hidden');
            }

            // Close check-out modal
            function closeCheckOutModal() {
                document.getElementById('checkOutModal').classList.add('hidden');
            }

            // Update current checkout time display
            function updateCheckOutCurrentTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('ar-SA', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                document.getElementById('currentCheckOutTime').textContent = timeString;
            }

            // Update current time display
            function updateCurrentTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('ar-SA', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                document.getElementById('currentTime').textContent = timeString;
            }

            // Close modal when clicking outside
            document.getElementById('editAttendanceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });

            // Close check-in modal when clicking outside
            document.getElementById('checkInModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCheckInModal();
                }
            });

            // Close check-out modal when clicking outside
            document.getElementById('checkOutModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCheckOutModal();
                }
            });

            // Update time every second when check-in modal is open
            let timeInterval;
            let checkOutTimeInterval;
            document.getElementById('checkInModal').addEventListener('transitionend', function() {
                if (!this.classList.contains('hidden')) {
                    timeInterval = setInterval(updateCurrentTime, 1000);
                } else {
                    clearInterval(timeInterval);
                }
            });

            document.getElementById('checkOutModal').addEventListener('transitionend', function() {
                if (!this.classList.contains('hidden')) {
                    checkOutTimeInterval = setInterval(updateCheckOutCurrentTime, 1000);
                } else {
                    clearInterval(checkOutTimeInterval);
                }
            });

            document.getElementById('checkOutModal').addEventListener('transitionend', function() {
                if (!this.classList.contains('hidden')) {
                    checkOutTimeInterval = setInterval(updateCheckOutCurrentTime, 1000);
                } else {
                    clearInterval(checkOutTimeInterval);
                }
            });

            updateCurrentDate();
        </script>

        <!-- Date Selection Modal -->
        <div id="dateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="ri-calendar-2-line text-purple-600"></i>
                        اختيار تاريخ للتقرير اليومي
                    </h3>
                    <button onclick="closeDateModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                
                <form id="dateForm" class="space-y-4">
                    <div>
                        <label for="selected_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="ri-calendar-line text-purple-600 ml-1"></i>
                            التاريخ المطلوب
                        </label>
                        <input type="date" 
                               id="selected_date" 
                               name="selected_date"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               max="{{ date('Y-m-d') }}"
                               value="{{ date('Y-m-d') }}">
                        <p class="text-xs text-gray-500 mt-1">يمكنك اختيار أي تاريخ من الماضي حتى اليوم</p>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="button" 
                                onclick="viewDailyReport()" 
                                id="viewReportBtn"
                                class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white py-3 px-4 rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="ri-eye-line"></i>
                            <span class="btn-text">عرض التقرير</span>
                        </button>
                        <button type="button" 
                                onclick="editDailyReport()" 
                                id="editReportBtn"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-4 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="ri-edit-2-line"></i>
                            <span class="btn-text">تعديل البيانات</span>
                        </button>
                    </div>
                    
                    <button type="button" 
                            onclick="closeDateModal()" 
                            class="w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-xl font-medium hover:bg-gray-300 transition-colors">
                        إلغاء
                    </button>
                </form>
            </div>
        </div>

        <script>
            // Date Modal Functions
            function openDateModal() {
                console.log('openDateModal called');
                const modal = document.getElementById('dateModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    console.log('Modal opened successfully');
                } else {
                    console.error('Modal not found!');
                }
            }

            function closeDateModal() {
                document.getElementById('dateModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                
                // Reset button states
                const viewBtn = document.getElementById('viewReportBtn');
                const editBtn = document.getElementById('editReportBtn');
                
                if (viewBtn) {
                    viewBtn.disabled = false;
                    const viewBtnText = viewBtn.querySelector('.btn-text');
                    if (viewBtnText) viewBtnText.textContent = 'عرض التقرير';
                    viewBtn.classList.remove('opacity-75');
                }
                
                if (editBtn) {
                    editBtn.disabled = false;
                    const editBtnText = editBtn.querySelector('.btn-text');
                    if (editBtnText) editBtnText.textContent = 'تعديل البيانات';
                    editBtn.classList.remove('opacity-75');
                }
            }

            function viewDailyReport() {
                console.log('viewDailyReport called');
                const selectedDate = document.getElementById('selected_date').value;
                console.log('Selected date:', selectedDate);
                
                if (!selectedDate) {
                    alert('يرجى اختيار تاريخ أولاً');
                    return;
                }
                
                const url = `{{ route('employees.daily-attendance-report') }}?date=${selectedDate}`;
                console.log('Navigating to:', url);
                
                // Show loading state
                const btn = document.getElementById('viewReportBtn');
                if (btn) {
                    const btnText = btn.querySelector('.btn-text');
                    btn.disabled = true;
                    if (btnText) btnText.textContent = 'جاري التحميل...';
                    btn.classList.add('opacity-75');
                }
                
                // Close modal first
                closeDateModal();
                
                // Redirect
                setTimeout(() => {
                    window.location.href = url;
                }, 300);
            }

            function editDailyReport() {
                console.log('editDailyReport called');
                const selectedDate = document.getElementById('selected_date').value;
                console.log('Selected date:', selectedDate);
                
                if (!selectedDate) {
                    alert('يرجى اختيار تاريخ أولاً');
                    return;
                }
                
                const url = `{{ route('employees.daily-attendance-edit') }}?date=${selectedDate}`;
                console.log('Navigating to:', url);
                
                // Show loading state
                const btn = document.getElementById('editReportBtn');
                if (btn) {
                    const btnText = btn.querySelector('.btn-text');
                    btn.disabled = true;
                    if (btnText) btnText.textContent = 'جاري التحميل...';
                    btn.classList.add('opacity-75');
                }
                
                // Close modal first
                closeDateModal();
                
                // Redirect
                setTimeout(() => {
                    window.location.href = url;
                }, 300);
            }

            // Close modal when clicking outside
            document.getElementById('dateModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDateModal();
                }
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDateModal();
                }
            });
        </script>
    @endpush
@endsection
