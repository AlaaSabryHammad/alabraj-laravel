@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة مسيرات الرواتب</h1>
                <p class="text-gray-600">إدارة وتنظيم رواتب الموظفين والاستقطاعات والبدلات</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('payroll.create') }}"
                   class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-3 rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 flex items-center gap-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إنشاء مسيرة راتب جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border-r-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي المسيرات</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_payrolls'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-r-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">في الانتظار</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_payrolls'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-r-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">معتمدة</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['approved_payrolls'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-r-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي هذا الشهر</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_amount_this_month'], 2) }}</p>
                    <p class="text-xs text-gray-500">ريال سعودي</p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Payrolls Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">مسيرات الرواتب</h2>
        </div>

        @if($payrolls->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العنوان</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الراتب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الموظفين</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجمالي</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payrolls as $payroll)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $payroll->title }}</div>
                                    @if($payroll->notes)
                                        <div class="text-sm text-gray-500">{{ Str::limit($payroll->notes, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payroll->payroll_date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payroll->employees_count }} موظف
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($payroll->total_amount, 2) }} ريال
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payroll->status_badge }}">
                                        {{ $payroll->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payroll->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('payroll.show', $payroll) }}"
                                           class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                            عرض
                                        </a>

                                        @if($payroll->isEditable())
                                            <a href="{{ route('payroll.edit', $payroll) }}"
                                               class="text-emerald-600 hover:text-emerald-900 transition-colors duration-200">
                                                تعديل
                                            </a>
                                        @endif

                                        @if($payroll->canBeApproved())
                                            <form method="POST" action="{{ route('payroll.approve', $payroll) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                        onclick="return confirm('هل أنت متأكد من اعتماد هذه المسيرة؟')">
                                                    اعتماد
                                                </button>
                                            </form>
                                        @endif

                                        @if($payroll->status !== 'approved')
                                            <button type="button"
                                                    onclick="showDeleteModal({{ $payroll->id }}, '{{ $payroll->title }}', {{ $payroll->employees_count }}, {{ $payroll->total_amount }})"
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                                حذف
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $payrolls->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد مسيرات رواتب</h3>
                <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء مسيرة راتب جديدة</p>
                <div class="mt-6">
                    <a href="{{ route('payroll.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        إنشاء مسيرة راتب
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('error') }}
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">حذف مسيرة الراتب</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    هل أنت متأكد من حذف مسيرة الراتب هذه؟<br>
                    <span class="font-medium text-red-600">تحذير:</span> هذا الإجراء لا يمكن التراجع عنه.
                </p>

                <!-- Payroll Information -->
                <div class="p-3 bg-red-50 rounded-lg text-right">
                    <div class="text-sm space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-600">عنوان المسيرة:</span>
                            <span class="font-medium text-gray-900" id="deletePayrollTitle"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">عدد الموظفين:</span>
                            <span class="font-medium text-red-600" id="deleteEmployeeCount"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">إجمالي المبلغ:</span>
                            <span class="font-medium text-red-600" id="deleteTotalAmount"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <form method="POST" action="" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3 justify-center">
                        <button type="button"
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            حذف نهائي
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showDeleteModal(payrollId, title, employeeCount, totalAmount) {
    // Set the form action URL
    document.getElementById('deleteForm').action = `/payroll/${payrollId}`;

    // Fill in the payroll information
    document.getElementById('deletePayrollTitle').textContent = title;
    document.getElementById('deleteEmployeeCount').textContent = employeeCount + ' موظف';
    document.getElementById('deleteTotalAmount').textContent = new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR'
    }).format(totalAmount) + ' ريال';

    // Show the modal
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const deleteModal = document.getElementById('deleteModal');
    if (event.target == deleteModal) {
        closeDeleteModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
