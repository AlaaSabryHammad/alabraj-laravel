@extends('layouts.app')

@section('title', 'عرض سند الصرف - ' . $expenseVoucher->voucher_number)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">سند صرف رقم: {{ $expenseVoucher->voucher_number }}</h1>
                <p class="text-gray-600 mt-1">تاريخ الإنشاء: {{ $expenseVoucher->voucher_date->format('Y-m-d') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('expense-vouchers.edit', $expenseVoucher) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-edit-line"></i>
                    تعديل
                </a>
                <a href="{{ route('finance.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-arrow-right-line"></i>
                    العودة للمالية
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Voucher Details -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <i class="ri-file-text-line text-2xl"></i>
                            تفاصيل سند الصرف
                        </h3>
                    </div>
                    <div class="text-left">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if ($expenseVoucher->status_color === 'green') bg-green-100 text-green-800
                        @elseif($expenseVoucher->status_color === 'blue') bg-blue-100 text-blue-800
                        @elseif($expenseVoucher->status_color === 'yellow') bg-yellow-100 text-yellow-800
                        @elseif($expenseVoucher->status_color === 'red') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                            {{ $expenseVoucher->status_text }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 border-b pb-2">المعلومات الأساسية</h4>

                        <div>
                            <label class="text-sm font-medium text-gray-600">رقم السند</label>
                            <p class="text-gray-900 font-mono">{{ $expenseVoucher->voucher_number }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">تاريخ السند</label>
                            <p class="text-gray-900">{{ $expenseVoucher->voucher_date->format('Y-m-d') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">فئة الصرف</label>
                            <p class="text-gray-900">{{ $expenseVoucher->expenseCategory->name ?? 'غير محدد' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">المبلغ</label>
                            <p class="text-gray-900 font-bold text-lg text-green-600">
                                {{ $expenseVoucher->formatted_amount }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">طريقة الصرف</label>
                            <p class="text-gray-900">{{ $expenseVoucher->payment_method_text }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">نوع الضريبة</label>
                            <p class="text-gray-900">{{ $expenseVoucher->tax_type_text }}</p>
                        </div>
                    </div>

                    <!-- Related Info -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 border-b pb-2">الجهات والمواقع</h4>

                        <div>
                            <label class="text-sm font-medium text-gray-600">الموظف معتمد الصرف</label>
                            <p class="text-gray-900">{{ $expenseVoucher->employee->name ?? 'غير محدد' }}</p>
                            @if ($expenseVoucher->employee)
                                <p class="text-sm text-gray-500">{{ $expenseVoucher->employee->position }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">جهة الصرف</label>
                            <p class="text-gray-900">{{ $expenseVoucher->expenseEntity->name ?? 'غير محدد' }}</p>
                            @if ($expenseVoucher->expenseEntity)
                                <p class="text-sm text-gray-500">{{ $expenseVoucher->expenseEntity->type_text }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">المشروع</label>
                            <p class="text-gray-900">{{ $expenseVoucher->project->name ?? 'غير محدد' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">الموقع</label>
                            <p class="text-gray-900">{{ $expenseVoucher->location->name ?? 'غير محدد' }}</p>
                        </div>

                        @if ($expenseVoucher->reference_number)
                            <div>
                                <label class="text-sm font-medium text-gray-600">رقم الفاتورة</label>
                                <p class="text-gray-900 font-mono">{{ $expenseVoucher->reference_number }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- System Info -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 border-b pb-2">معلومات النظام</h4>

                        <div>
                            <label class="text-sm font-medium text-gray-600">منشئ السند</label>
                            <p class="text-gray-900">{{ $expenseVoucher->creator->name ?? 'غير محدد' }}</p>
                            <p class="text-sm text-gray-500">{{ $expenseVoucher->created_at->format('Y-m-d H:i') }}</p>
                        </div>

                        @if ($expenseVoucher->approved_by)
                            <div>
                                <label class="text-sm font-medium text-gray-600">معتمد السند</label>
                                <p class="text-gray-900">{{ $expenseVoucher->approver->name }}</p>
                                <p class="text-sm text-gray-500">{{ $expenseVoucher->approved_at->format('Y-m-d H:i') }}
                                </p>
                            </div>
                        @endif

                        <div>
                            <label class="text-sm font-medium text-gray-600">آخر تحديث</label>
                            <p class="text-gray-900">{{ $expenseVoucher->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-900 border-b pb-2 mb-4">البيان</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $expenseVoucher->description }}</p>
                    </div>
                </div>

                <!-- Notes -->
                @if ($expenseVoucher->notes)
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 border-b pb-2 mb-4">الملاحظات</h4>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $expenseVoucher->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Attachment -->
                @if ($expenseVoucher->attachment_path)
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 border-b pb-2 mb-4">الملف المرفق</h4>
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-center gap-3">
                                <i class="ri-attachment-line text-2xl text-blue-600"></i>
                                <div>
                                    <p class="text-gray-900 font-medium">{{ basename($expenseVoucher->attachment_path) }}
                                    </p>
                                    <p class="text-sm text-gray-600">ملف مرفق</p>
                                </div>
                                <a href="{{ asset('storage/' . $expenseVoucher->attachment_path) }}" target="_blank"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                    <i class="ri-download-line ml-1"></i>
                                    تحميل
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                @if ($expenseVoucher->status === 'pending')
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-semibold text-gray-900 mb-4">إجراءات السند</h4>
                        <div class="flex gap-3">
                            <button type="button" onclick="openApprovalModal()"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-check-line"></i>
                                اعتماد السند
                            </button>
                        </div>
                    </div>
                @endif


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
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">اعتماد سند الصرف</h4>
                    <p class="text-gray-600">هل أنت متأكد من اعتماد سند الصرف رقم:</p>
                    <p class="font-bold text-blue-600 text-lg mt-2">{{ $expenseVoucher->voucher_number }}</p>
                </div>

                <!-- معلومات السند -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">المبلغ:</span>
                            <span class="font-medium text-gray-900">{{ $expenseVoucher->formatted_amount }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">التاريخ:</span>
                            <span
                                class="font-medium text-gray-900">{{ $expenseVoucher->voucher_date->format('Y-m-d') }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-600">الفئة:</span>
                            <span
                                class="font-medium text-gray-900">{{ $expenseVoucher->expenseCategory->name ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                </div>

                <!-- تحذير -->
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                    <div class="flex items-start gap-3">
                        <i class="ri-warning-line text-yellow-600 text-lg mt-0.5"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium mb-1">تنبيه مهم:</p>
                            <p>بعد اعتماد السند لن يمكن التراجع عن هذا الإجراء، وسيصبح السند جاهزاً للدفع.</p>
                        </div>
                    </div>
                </div>

                <!-- أزرار التحكم -->
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeApprovalModal()"
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <form action="{{ route('expense-vouchers.approve', $expenseVoucher) }}" method="POST"
                        class="inline">
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



        // إغلاق Modal عند النقر خارجه
        document.getElementById('approvalModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeApprovalModal();
            }
        });



        // إغلاق Modal بالضغط على Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeApprovalModal();

            }
        });
    </script>

@endsection
