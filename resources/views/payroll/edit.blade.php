@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تعديل مسيرة الراتب</h1>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span>المسيرة: {{ $payroll->title }}</span>
                    <span>الحالة: {{ $payroll->status_text }}</span>
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

    @if($payroll->status === 'approved')
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="mr-3">
                    <p class="text-sm">
                        لا يمكن تعديل مسيرة راتب معتمدة. يمكنك فقط عرض التفاصيل.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('payroll.update', $payroll) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">المعلومات الأساسية</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        عنوان المسيرة <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $payroll->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('title') border-red-500 @enderror {{ $payroll->status === 'approved' ? 'bg-gray-100' : '' }}"
                           {{ $payroll->status === 'approved' ? 'readonly' : '' }}
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payroll_date" class="block text-sm font-medium text-gray-700 mb-2">
                        تاريخ الراتب <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           id="payroll_date"
                           name="payroll_date"
                           value="{{ old('payroll_date', $payroll->payroll_date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('payroll_date') border-red-500 @enderror {{ $payroll->status === 'approved' ? 'bg-gray-100' : '' }}"
                           {{ $payroll->status === 'approved' ? 'readonly' : '' }}
                           required>
                    @error('payroll_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    ملاحظات
                </label>
                <textarea id="notes"
                          name="notes"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('notes') border-red-500 @enderror {{ $payroll->status === 'approved' ? 'bg-gray-100' : '' }}"
                          {{ $payroll->status === 'approved' ? 'readonly' : '' }}
                          placeholder="ملاحظات اختيارية حول هذه المسيرة">{{ old('notes', $payroll->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Employee List -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">الموظفين في المسيرة</h2>

            @if($payroll->employees->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموظف</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الراتب الأساسي</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البدلات</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاستقطاعات</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">صافي الراتب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payroll->employees as $payrollEmployee)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $payrollEmployee->employee->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $payrollEmployee->employee->employee_id ?? 'غير محدد' }}</div>
                                                <div class="text-sm text-gray-500">{{ $payrollEmployee->employee->position ?? 'غير محدد' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($payrollEmployee->base_salary, 2) }} ريال
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                        {{ number_format($payrollEmployee->total_bonuses, 2) }} ريال
                                        @if($payrollEmployee->bonuses->count() > 0)
                                            <div class="text-xs text-gray-500">({{ $payrollEmployee->bonuses->count() }} بدل)</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                        {{ number_format($payrollEmployee->total_deductions, 2) }} ريال
                                        @if($payrollEmployee->deductions->count() > 0)
                                            <div class="text-xs text-gray-500">({{ $payrollEmployee->deductions->count() }} استقطاع)</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($payrollEmployee->net_salary, 2) }} ريال
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payrollEmployee->is_eligible)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                مستحق
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                غير مستحق
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('payroll.employee.details', [$payroll, $payrollEmployee->employee]) }}"
                                           class="text-emerald-600 hover:text-emerald-900 transition-colors duration-200">
                                            تعديل التفاصيل
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <p class="text-sm text-gray-600">إجمالي الموظفين</p>
                            <p class="text-lg font-bold text-gray-900">{{ $payroll->employees->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">المستحقين</p>
                            <p class="text-lg font-bold text-green-600">{{ $payroll->employees->where('is_eligible', true)->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">إجمالي الرواتب الأساسية</p>
                            <p class="text-lg font-bold text-blue-600">{{ number_format($payroll->employees->sum('base_salary'), 2) }} ريال</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">صافي المبلغ المستحق</p>
                            <p class="text-lg font-bold text-emerald-600">{{ number_format($payroll->employees->sum('net_salary'), 2) }} ريال</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">لا يوجد موظفين</h3>
                    <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي موظفين لهذه المسيرة بعد</p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($payroll->status !== 'approved')
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        @if($payroll->status === 'draft')
                            <button type="button"
                                    onclick="showApprovalModal()"
                                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                تجهيز للمراجعة
                            </button>
                        @elseif($payroll->status === 'pending')
                            <button type="button"
                                    onclick="showFinalApprovalModal()"
                                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                اعتماد نهائي
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('payroll.show', $payroll) }}"
                           class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            إلغاء
                        </a>
                        <button type="submit"
                                class="bg-emerald-500 text-white px-6 py-3 rounded-lg hover:bg-emerald-600 transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            حفظ التغييرات
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-center">
                    <a href="{{ route('payroll.show', $payroll) }}"
                       class="bg-emerald-500 text-white px-6 py-3 rounded-lg hover:bg-emerald-600 transition-colors duration-200">
                        عرض المسيرة
                    </a>
                </div>
            </div>
        @endif
    </form>
</div>

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

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">تجهيز للمراجعة</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    هل أنت متأكد من تجهيز هذه المسيرة للمراجعة؟<br>
                    سيتم تغيير حالة المسيرة إلى "في انتظار المراجعة" ولن يمكن تعديلها إلا بعد الموافقة أو الرفض.
                </p>

                <!-- Summary Information -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg text-right">
                    <div class="text-xs text-gray-600 space-y-1">
                        <div class="flex justify-between">
                            <span>إجمالي الموظفين:</span>
                            <span class="font-medium">{{ $payroll->employees->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>المستحقين للراتب:</span>
                            <span class="font-medium text-green-600">{{ $payroll->employees->where('is_eligible', true)->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>صافي المبلغ المستحق:</span>
                            <span class="font-medium text-emerald-600">{{ number_format($payroll->employees->sum('net_salary'), 2) }} ريال</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <form method="POST" action="{{ route('payroll.approve', $payroll) }}" id="approvalForm">
                    @csrf
                    <div class="flex gap-3 justify-center">
                        <button type="button"
                                onclick="closeApprovalModal()"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                            تأكيد التجهيز
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Final Approval Modal -->
<div id="finalApprovalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100">
                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">اعتماد نهائي</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    هل أنت متأكد من الاعتماد النهائي لهذه المسيرة؟<br>
                    <span class="font-medium text-red-600">تحذير:</span> بعد الاعتماد النهائي لن يمكن تعديل أو حذف هذه المسيرة نهائياً.
                </p>

                <!-- Summary Information -->
                <div class="mt-4 p-3 bg-emerald-50 rounded-lg text-right">
                    <div class="text-xs text-gray-600 space-y-1">
                        <div class="flex justify-between">
                            <span>إجمالي الموظفين:</span>
                            <span class="font-medium">{{ $payroll->employees->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>المستحقين للراتب:</span>
                            <span class="font-medium text-green-600">{{ $payroll->employees->where('is_eligible', true)->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>صافي المبلغ المستحق:</span>
                            <span class="font-medium text-emerald-600">{{ number_format($payroll->employees->sum('net_salary'), 2) }} ريال</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <form method="POST" action="{{ route('payroll.approve', $payroll) }}" id="finalApprovalForm">
                    @csrf
                    <div class="flex gap-3 justify-center">
                        <button type="button"
                                onclick="closeFinalApprovalModal()"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-emerald-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                            اعتماد نهائي
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showApprovalModal() {
    document.getElementById('approvalModal').classList.remove('hidden');
}

function closeApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
}

function showFinalApprovalModal() {
    document.getElementById('finalApprovalModal').classList.remove('hidden');
}

function closeFinalApprovalModal() {
    document.getElementById('finalApprovalModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const approvalModal = document.getElementById('approvalModal');
    const finalApprovalModal = document.getElementById('finalApprovalModal');

    if (event.target == approvalModal) {
        closeApprovalModal();
    }
    if (event.target == finalApprovalModal) {
        closeFinalApprovalModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeApprovalModal();
        closeFinalApprovalModal();
    }
});
</script>
@endsection
