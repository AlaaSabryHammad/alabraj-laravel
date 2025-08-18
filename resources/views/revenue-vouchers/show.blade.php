@extends('layouts.app')

@section('title', 'عرض سند القبض - ' . $revenueVoucher->voucher_number)

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">سند قبض رقم: {{ $revenueVoucher->voucher_number }}</h1>
            <p class="text-gray-600 mt-1">تاريخ الإنشاء: {{ $revenueVoucher->voucher_date->format('Y-m-d') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('revenue-vouchers.edit', $revenueVoucher) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-edit-line"></i>
                تعديل
            </a>
            <button onclick="window.print()" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-printer-line"></i>
                طباعة
            </button>
            <a href="{{ route('finance.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-arrow-right-line"></i>
                العودة للمالية
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Voucher Details -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold flex items-center gap-3">
                        <i class="ri-money-dollar-circle-line text-2xl"></i>
                        تفاصيل سند القبض
                    </h3>
                </div>
                <div class="text-left">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($revenueVoucher->status_color === 'green') bg-green-100 text-green-800
                        @elseif($revenueVoucher->status_color === 'blue') bg-blue-100 text-blue-800
                        @elseif($revenueVoucher->status_color === 'yellow') bg-yellow-100 text-yellow-800
                        @elseif($revenueVoucher->status_color === 'red') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $revenueVoucher->status_text }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2">المعلومات الأساسية</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">رقم السند</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $revenueVoucher->voucher_number }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">تاريخ السند</label>
                            <p class="text-lg text-gray-900">{{ $revenueVoucher->voucher_date->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">جهة الإيراد</label>
                        <p class="text-lg text-gray-900">{{ $revenueVoucher->revenueEntity->name ?? 'غير محدد' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">المبلغ</label>
                        <p class="text-2xl font-bold text-green-600">{{ $revenueVoucher->formatted_amount }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">طريقة الدفع</label>
                        <p class="text-lg text-gray-900">{{ $revenueVoucher->payment_method_text }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">نوع الضريبة</label>
                        <p class="text-lg text-gray-900">{{ $revenueVoucher->tax_type_text }}</p>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2">معلومات إضافية</h4>
                    
                    @if($revenueVoucher->project)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">المشروع</label>
                        <p class="text-lg text-gray-900">{{ $revenueVoucher->project->name }}</p>
                    </div>
                    @endif

                    @if($revenueVoucher->location)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">الموقع</label>
                        <p class="text-lg text-gray-900">{{ $revenueVoucher->location->name }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-600">تاريخ الإنشاء</label>
                        <p class="text-lg text-gray-900">{{ $revenueVoucher->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    @if($revenueVoucher->creator)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">منشئ السند</label>
                        <p class="text-lg text-gray-900">{{ $revenueVoucher->creator->name ?? 'غير محدد' }}</p>
                    </div>
                    @endif

                    @if($revenueVoucher->approver)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">معتمد السند</label>
                        <p class="text-lg text-gray-900">{{ $revenueVoucher->approver->name ?? 'غير محدد' }}</p>
                        @if($revenueVoucher->approved_at)
                        <p class="text-sm text-gray-500">{{ $revenueVoucher->approved_at->format('Y-m-d H:i') }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($revenueVoucher->description)
            <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">الوصف</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-900 leading-relaxed">{{ $revenueVoucher->description }}</p>
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($revenueVoucher->notes)
            <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">ملاحظات</h4>
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                    <p class="text-gray-900 leading-relaxed">{{ $revenueVoucher->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Attachment -->
            @if($revenueVoucher->attachment_path)
            <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-4">المرفقات</h4>
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                    <a href="{{ Storage::url($revenueVoucher->attachment_path) }}" 
                       target="_blank"
                       class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
                        <i class="ri-attachment-line"></i>
                        عرض المرفق
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    آخر تحديث: {{ $revenueVoucher->updated_at->format('Y-m-d H:i') }}
                </div>
                <div class="flex gap-3">
                    @if($revenueVoucher->status === 'pending')
                    <div class="flex items-center gap-3">
                        <div class="text-sm text-gray-600">
                            <i class="ri-information-line text-blue-500"></i>
                            السند في انتظار الاعتماد
                        </div>
                        <button type="button" 
                            onclick="openApprovalModal()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
                            <i class="ri-check-line"></i>
                            اعتماد السند
                        </button>
                    </div>
                    @elseif($revenueVoucher->status === 'approved')
                    <div class="flex items-center gap-3">
                        <div class="text-sm text-gray-600">
                            <i class="ri-information-line text-blue-500"></i>
                            السند معتمد ومجهز للاستلام
                        </div>
                        <button type="button"
                            onclick="openReceiptModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
                            <i class="ri-money-dollar-circle-line"></i>
                            تأكيد الاستلام
                        </button>
                    </div>
                    @elseif($revenueVoucher->status === 'received')
                    <div class="flex items-center gap-2 text-green-600">
                        <i class="ri-check-double-line text-lg"></i>
                        <span class="font-medium">تم استلام المبلغ بنجاح</span>
                    </div>
                    @elseif($revenueVoucher->status === 'cancelled')
                    <div class="flex items-center gap-2 text-red-600">
                        <i class="ri-close-circle-line text-lg"></i>
                        <span class="font-medium">السند ملغى</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal اعتماد السند -->
<div id="approvalModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold flex items-center gap-3">
                    <i class="ri-check-double-line text-2xl"></i>
                    تأكيد اعتماد السند
                </h3>
                <button type="button" onclick="closeApprovalModal()" 
                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                    <i class="ri-close-line text-white"></i>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <i class="ri-check-line text-3xl text-green-600"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">اعتماد سند القبض</h4>
                <p class="text-gray-600">هل أنت متأكد من اعتماد سند القبض رقم:</p>
                <p class="font-bold text-green-600 text-lg mt-2">{{ $revenueVoucher->voucher_number }}</p>
            </div>

            <!-- معلومات السند -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">المبلغ:</span>
                        <span class="font-medium text-green-600">{{ $revenueVoucher->formatted_amount }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">التاريخ:</span>
                        <span class="font-medium text-gray-900">{{ $revenueVoucher->voucher_date->format('Y-m-d') }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-600">جهة الإيراد:</span>
                        <span class="font-medium text-gray-900">{{ $revenueVoucher->revenueEntity->name ?? 'غير محدد' }}</span>
                    </div>
                </div>
            </div>

            <!-- تحذير -->
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                <div class="flex items-start gap-3">
                    <i class="ri-warning-line text-yellow-600 text-lg mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-medium mb-1">تنبيه مهم:</p>
                        <p>بعد اعتماد السند سيتم تسجيل اسمك كمعتمد وسيصبح السند جاهزاً لتأكيد الاستلام.</p>
                    </div>
                </div>
            </div>

            <!-- أزرار التحكم -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeApprovalModal()" 
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    إلغاء
                </button>
                <form action="{{ route('revenue-vouchers.approve', $revenueVoucher) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                        <i class="ri-check-line"></i>
                        تأكيد الاعتماد
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal تأكيد الاستلام -->
<div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold flex items-center gap-3">
                    <i class="ri-money-dollar-circle-line text-2xl"></i>
                    تأكيد استلام المبلغ
                </h3>
                <button type="button" onclick="closeReceiptModal()" 
                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                    <i class="ri-close-line text-white"></i>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <i class="ri-money-dollar-circle-line text-3xl text-blue-600"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">تأكيد استلام المبلغ</h4>
                <p class="text-gray-600">هل أنت متأكد من تأكيد استلام مبلغ سند القبض؟</p>
                <p class="font-bold text-blue-600 text-lg mt-2">{{ $revenueVoucher->voucher_number }}</p>
            </div>

            <!-- معلومات الاستلام -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">المبلغ المستلم:</span>
                        <span class="font-bold text-green-600 text-lg">{{ $revenueVoucher->formatted_amount }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">طريقة الدفع:</span>
                        <span class="font-medium text-gray-900">{{ $revenueVoucher->payment_method_text }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">نوع الضريبة:</span>
                        <span class="font-medium text-gray-900">{{ $revenueVoucher->tax_type_text }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">الحالة الحالية:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $revenueVoucher->status_text }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- معلومات إضافية -->
            @if($revenueVoucher->revenueEntity)
            <div class="bg-green-50 border border-green-200 p-4 rounded-lg mb-6">
                <div class="text-sm">
                    <p class="font-medium text-gray-900 mb-1">جهة الإيراد:</p>
                    <p class="text-gray-700">{{ $revenueVoucher->revenueEntity->name }}</p>
                    @if($revenueVoucher->project)
                        <p class="text-gray-600 text-xs mt-1">المشروع: {{ $revenueVoucher->project->name }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- تحذير -->
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                <div class="flex items-start gap-3">
                    <i class="ri-warning-line text-yellow-600 text-lg mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-medium mb-1">تنبيه مهم:</p>
                        <p>بعد تأكيد الاستلام لن يمكن التراجع عن هذا الإجراء، وسيتم اعتبار المبلغ مستلم نهائياً.</p>
                    </div>
                </div>
            </div>

            <!-- أزرار التحكم -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeReceiptModal()" 
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    إلغاء
                </button>
                <form action="{{ route('revenue-vouchers.mark-received', $revenueVoucher) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                        <i class="ri-money-dollar-circle-line"></i>
                        تأكيد الاستلام
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// فتح modal الاعتماد
function openApprovalModal() {
    document.getElementById('approvalModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // منع التمرير في الخلفية
}

// إغلاق modal الاعتماد
function closeApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
    document.body.style.overflow = 'auto'; // استعادة التمرير
}

// فتح modal تأكيد الاستلام
function openReceiptModal() {
    document.getElementById('receiptModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // منع التمرير في الخلفية
}

// إغلاق modal تأكيد الاستلام
function closeReceiptModal() {
    document.getElementById('receiptModal').classList.add('hidden');
    document.body.style.overflow = 'auto'; // استعادة التمرير
}

// إغلاق Modal عند النقر خارجه
document.getElementById('approvalModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeApprovalModal();
    }
});

document.getElementById('receiptModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeReceiptModal();
    }
});

// إغلاق Modal بالضغط على Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeApprovalModal();
        closeReceiptModal();
    }
});
</script>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            font-size: 12px;
        }
        
        .bg-white {
            background: white !important;
        }
        
        .text-white {
            color: black !important;
        }
        
        .bg-gradient-to-r {
            background: #f8f9fa !important;
            color: black !important;
        }
    }
</style>
@endsection