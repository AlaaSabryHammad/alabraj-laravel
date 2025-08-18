@extends('layouts.app')

@section('title', 'تفاصيل المعاملة المالية')

@section('content')
    <div class="p-6" dir="rtl">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('finance.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تفاصيل المعاملة المالية</h1>
                <p class="text-gray-600 mt-1">عرض تفاصيل وتاريخ المعاملة</p>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border-2 border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if ($transaction->type === 'revenue') bg-emerald-100 text-emerald-800
                                    @elseif($transaction->type === 'expense') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $transaction->type_text }}
                                </span>
                                <h2 class="text-xl font-bold text-gray-900 mt-2">{{ $transaction->reference_number }}</h2>
                            </div>
                            <div class="text-left">
                                <div class="text-sm text-gray-600">المبلغ</div>
                                <div
                                    class="text-xl font-bold @if ($transaction->type === 'revenue') text-emerald-600 @else text-red-600 @endif">
                                    {{ number_format($transaction->amount, 2) }} ر.س
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-sm font-medium text-gray-600">الوصف</div>
                                <div class="text-gray-900 mt-1">{{ $transaction->description }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-600">طريقة الدفع</div>
                                <div class="text-gray-900 mt-1">{{ $transaction->payment_method }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-600">تاريخ المعاملة</div>
                                <div class="text-gray-900 mt-1">{{ $transaction->date }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-600">الحالة</div>
                                <div
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium mt-1
                                    @if ($transaction->status_color === 'green') bg-green-100 text-green-800
                                    @elseif($transaction->status_color === 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status_color === 'red') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $transaction->status_text }}
                                </div>
                            </div>
                            @if ($transaction->notes)
                                <div class="col-span-2">
                                    <div class="text-sm font-medium text-gray-600">ملاحظات</div>
                                    <div class="text-gray-900 mt-1">{{ $transaction->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-6 bg-gray-50 rounded-b-xl flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('finance.edit', $transaction) }}"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                                <i class="ri-edit-line ml-1"></i>
                                تعديل المعاملة
                            </a>
                            <form action="{{ route('finance.destroy', $transaction) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('هل أنت متأكد من حذف هذه المعاملة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                    <i class="ri-delete-bin-line ml-1"></i>
                                    حذف المعاملة
                                </button>
                            </form>
                        </div>
                        <button onclick="printTransaction()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                            <i class="ri-printer-line ml-1"></i>
                            طباعة المعاملة
                        </button>
                    </div>
                </div>
            </div>

            <!-- Side Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border-2 border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">معلومات إضافية</h3>

                    <div class="space-y-4">
                        @if ($transaction->project)
                            <div>
                                <div class="text-sm font-medium text-gray-600">المشروع</div>
                                <div class="text-gray-900 mt-1">{{ $transaction->project }}</div>
                            </div>
                        @endif

                        @if ($transaction->entity)
                            <div>
                                <div class="text-sm font-medium text-gray-600">
                                    @if ($transaction->type === 'revenue')
                                        جهة الإيراد
                                    @elseif($transaction->type === 'expense')
                                        جهة الصرف
                                    @else
                                        الموظف المستلم
                                    @endif
                                </div>
                                <div class="text-gray-900 mt-1">{{ $transaction->entity }}</div>
                            </div>
                        @endif

                        <div>
                            <div class="text-sm font-medium text-gray-600">تاريخ الإنشاء</div>
                            <div class="text-gray-900 mt-1">{{ $transaction->created_at }}</div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-600">آخر تحديث</div>
                            <div class="text-gray-900 mt-1">{{ $transaction->updated_at }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printTransaction() {
            window.print();
        }
    </script>
@endsection
