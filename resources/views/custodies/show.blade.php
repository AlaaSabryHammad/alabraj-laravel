@extends('layouts.app')

@section('title', 'عرض العهدة - ' . $custody->id)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تفاصيل العهدة</h1>
                <p class="text-gray-600 mt-1">تاريخ الصرف:
                    {{ $custody->disbursement_date ? $custody->disbursement_date->format('Y-m-d') : 'غير محدد' }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('finance.custodies.print', $custody) }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-printer-line"></i>
                    طباعة
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

        <!-- Custody Details -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <i class="ri-wallet-3-line text-2xl"></i>
                            تفاصيل العهدة
                        </h3>
                    </div>
                    <div class="text-left">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                            {{ $custody->status_text }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Employee Info -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 border-b pb-2">معلومات الموظف</h4>

                        <div class="bg-orange-50 p-4 rounded-lg border border-orange-100">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="ri-user-line text-2xl text-orange-600"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900">{{ $custody->employee->name }}</h5>
                                    <p class="text-gray-600">{{ $custody->employee->position }}</p>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p class="flex items-center gap-2">
                                            <i class="ri-phone-line"></i>
                                            {{ $custody->employee->phone ?? 'غير متوفر' }}
                                        </p>
                                        <p class="flex items-center gap-2">
                                            <i class="ri-mail-line"></i>
                                            {{ $custody->employee->email ?? 'غير متوفر' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Custody Info -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 border-b pb-2">معلومات العهدة</h4>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">قيمة العهدة</label>
                                <p class="text-lg font-bold text-orange-600">{{ number_format($custody->amount, 2) }} ر.س
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">تاريخ الصرف</label>
                                <p class="text-gray-900">{{ $custody->disbursement_date->format('Y-m-d') }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">طريقة الاستلام</label>
                                <p class="text-gray-900">{{ $custody->receipt_method_text }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-600">الحالة</label>
                                <p class="text-gray-900">{{ $custody->status_text }}</p>
                            </div>

                            @if ($custody->approved_by)
                                <div class="col-span-2 mt-2 pt-2 border-t">
                                    <label class="text-sm font-medium text-gray-600">معتمد بواسطة</label>
                                    <div class="flex items-center gap-2 mt-1">
                                        <i class="ri-user-line text-green-600"></i>
                                        <p class="text-gray-900">{{ $custody->approver->name }}</p>
                                        <span class="text-gray-400 mx-1">•</span>
                                        <span
                                            class="text-sm text-gray-600">{{ $custody->approved_at ? $custody->approved_at->format('Y-m-d H:i') : 'غير محدد' }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($custody->notes)
                    <!-- Notes -->
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 border-b pb-2 mb-4">الملاحظات</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $custody->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                @if ($custody->status === \App\Models\Custody::STATUS_PENDING)
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-semibold text-gray-900 mb-4">إجراءات العهدة</h4>
                        <div class="flex gap-3">
                            <button type="button" onclick="openApprovalModal()"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-check-line"></i>
                                اعتماد العهدة
                            </button>
                        </div>
                    </div>
                @endif

                <!-- System Info -->
                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-semibold text-gray-900 mb-4">معلومات النظام</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <label class="font-medium text-gray-600">تاريخ التسجيل</label>
                            <p class="text-gray-900">
                                {{ $custody->created_at ? $custody->created_at->format('Y-m-d H:i') : 'غير محدد' }}</p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-600">آخر تحديث</label>
                            <p class="text-gray-900">
                                {{ $custody->updated_at ? $custody->updated_at->format('Y-m-d H:i') : 'غير محدد' }}</p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-600">رقم العهدة</label>
                            <p class="text-gray-900 font-mono">{{ 'C-' . str_pad($custody->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal اعتماد العهدة -->
    <div id="approvalModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold flex items-center gap-3">
                        <i class="ri-check-double-line text-2xl"></i>
                        تأكيد اعتماد العهدة
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
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">اعتماد العهدة</h4>
                    <p class="text-gray-600">هل أنت متأكد من اعتماد العهدة للموظف:</p>
                    <p class="font-bold text-blue-600 text-lg mt-2">{{ $custody->employee->name }}</p>
                </div>

                <!-- معلومات العهدة -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">قيمة العهدة:</span>
                            <span class="font-semibold text-gray-900">{{ number_format($custody->amount, 2) }} ر.س</span>
                        </div>
                        <div>
                            <span class="text-gray-600">تاريخ الصرف:</span>
                            <span
                                class="font-medium text-gray-900">{{ $custody->disbursement_date->format('Y-m-d') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">طريقة الاستلام:</span>
                            <span class="font-medium text-gray-900">{{ $custody->receipt_method_text }}</span>
                        </div>
                    </div>
                </div>

                <!-- تحذير -->
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                    <div class="flex items-start gap-3">
                        <i class="ri-warning-line text-yellow-600 text-lg mt-0.5"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium mb-1">تنبيه مهم:</p>
                            <p>بعد اعتماد العهدة لن يمكن التراجع عن هذا الإجراء.</p>
                        </div>
                    </div>
                </div>

                <!-- أزرار التحكم -->
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeApprovalModal()"
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <form action="{{ route('finance.custodies.approve', $custody) }}" method="POST" class="inline">
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
