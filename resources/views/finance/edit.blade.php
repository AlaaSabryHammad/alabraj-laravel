@extends('layouts.app')

@section('title', 'تعديل المعاملة المالية')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('finance.index') }}"
           class="text-gray-600 hover:text-gray-900 transition-colors">
            <i class="ri-arrow-right-line text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تعديل المعاملة المالية</h1>
            <p class="text-gray-600 mt-1">تعديل {{ $finance->description }}</p>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">بيانات المعاملة</h2>
            </div>

            <form action="{{ route('finance.update', $finance) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Transaction Type and Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            نوع المعاملة <span class="text-red-500">*</span>
                        </label>
                        <select name="type"
                                id="type"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('type') border-red-300 @enderror"
                                required>
                            <option value="">اختر نوع المعاملة</option>
                            <option value="income" {{ (old('type', $finance->type) == 'income') ? 'selected' : '' }}>إيراد</option>
                            <option value="expense" {{ (old('type', $finance->type) == 'expense') ? 'selected' : '' }}>مصروف</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            الفئة <span class="text-red-500">*</span>
                        </label>
                        <select name="category"
                                id="category"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category') border-red-300 @enderror"
                                required>
                            <option value="">اختر الفئة</option>
                            <!-- Income Categories -->
                            <optgroup label="فئات الإيرادات" id="income-categories">
                                <option value="مشاريع البناء" {{ (old('category', $finance->category) == 'مشاريع البناء') ? 'selected' : '' }}>مشاريع البناء</option>
                                <option value="استشارات هندسية" {{ (old('category', $finance->category) == 'استشارات هندسية') ? 'selected' : '' }}>استشارات هندسية</option>
                                <option value="مبيعات المواد" {{ (old('category', $finance->category) == 'مبيعات المواد') ? 'selected' : '' }}>مبيعات المواد</option>
                                <option value="إيجارات" {{ (old('category', $finance->category) == 'إيجارات') ? 'selected' : '' }}>إيجارات</option>
                                <option value="أخرى" {{ (old('category', $finance->category) == 'أخرى') ? 'selected' : '' }}>أخرى</option>
                            </optgroup>
                            <!-- Expense Categories -->
                            <optgroup label="فئات المصروفات" id="expense-categories">
                                <option value="المواد والمعدات" {{ (old('category', $finance->category) == 'المواد والمعدات') ? 'selected' : '' }}>المواد والمعدات</option>
                                <option value="الرواتب والأجور" {{ (old('category', $finance->category) == 'الرواتب والأجور') ? 'selected' : '' }}>الرواتب والأجور</option>
                                <option value="النقل والمواصلات" {{ (old('category', $finance->category) == 'النقل والمواصلات') ? 'selected' : '' }}>النقل والمواصلات</option>
                                <option value="صيانة وإصلاح" {{ (old('category', $finance->category) == 'صيانة وإصلاح') ? 'selected' : '' }}>صيانة وإصلاح</option>
                                <option value="مصاريف إدارية" {{ (old('category', $finance->category) == 'مصاريف إدارية') ? 'selected' : '' }}>مصاريف إدارية</option>
                                <option value="تأمين" {{ (old('category', $finance->category) == 'تأمين') ? 'selected' : '' }}>تأمين</option>
                                <option value="ضرائب ورسوم" {{ (old('category', $finance->category) == 'ضرائب ورسوم') ? 'selected' : '' }}>ضرائب ورسوم</option>
                                <option value="أخرى" {{ (old('category', $finance->category) == 'أخرى') ? 'selected' : '' }}>أخرى</option>
                            </optgroup>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description and Amount -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            وصف المعاملة <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="description"
                               id="description"
                               value="{{ old('description', $finance->description) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-300 @enderror"
                               placeholder="أدخل وصف المعاملة"
                               required>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            المبلغ (ر.س) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="amount"
                               id="amount"
                               value="{{ old('amount', $finance->amount) }}"
                               step="0.01"
                               min="0"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('amount') border-red-300 @enderror"
                               placeholder="0.00"
                               required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date, Payment Method, and Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ المعاملة <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="transaction_date"
                               id="transaction_date"
                               value="{{ old('transaction_date', $finance->transaction_date?->format('Y-m-d')) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('transaction_date') border-red-300 @enderror"
                               required>
                        @error('transaction_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            طريقة الدفع <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method"
                                id="payment_method"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('payment_method') border-red-300 @enderror"
                                required>
                            <option value="">اختر طريقة الدفع</option>
                            <option value="نقداً" {{ (old('payment_method', $finance->payment_method) == 'نقداً') ? 'selected' : '' }}>نقداً</option>
                            <option value="تحويل بنكي" {{ (old('payment_method', $finance->payment_method) == 'تحويل بنكي') ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="شيك" {{ (old('payment_method', $finance->payment_method) == 'شيك') ? 'selected' : '' }}>شيك</option>
                            <option value="بطاقة ائتمان" {{ (old('payment_method', $finance->payment_method) == 'بطاقة ائتمان') ? 'selected' : '' }}>بطاقة ائتمان</option>
                            <option value="بطاقة مدين" {{ (old('payment_method', $finance->payment_method) == 'بطاقة مدين') ? 'selected' : '' }}>بطاقة مدين</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            حالة المعاملة <span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                                id="status"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-300 @enderror"
                                required>
                            <option value="pending" {{ (old('status', $finance->status) == 'pending') ? 'selected' : '' }}>معلق</option>
                            <option value="completed" {{ (old('status', $finance->status) == 'completed') ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ (old('status', $finance->status) == 'cancelled') ? 'selected' : '' }}>ملغي</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reference Number and Notes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم المرجع
                        </label>
                        <input type="text"
                               name="reference_number"
                               id="reference_number"
                               value="{{ old('reference_number', $finance->reference_number) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('reference_number') border-red-300 @enderror"
                               placeholder="مثال: INV-2025-001">
                        @error('reference_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات
                        </label>
                        <textarea name="notes"
                                  id="notes"
                                  rows="1"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-300 @enderror"
                                  placeholder="ملاحظات إضافية">{{ old('notes', $finance->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('finance.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-save-line"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Show/hide categories based on transaction type
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category');
    const incomeCategories = document.getElementById('income-categories');
    const expenseCategories = document.getElementById('expense-categories');

    function updateCategories() {
        const selectedType = typeSelect.value;

        // Hide all optgroups
        incomeCategories.style.display = 'none';
        expenseCategories.style.display = 'none';

        // Show relevant categories
        if (selectedType === 'income') {
            incomeCategories.style.display = 'block';
        } else if (selectedType === 'expense') {
            expenseCategories.style.display = 'block';
        }
    }

    typeSelect.addEventListener('change', updateCategories);

    // Initialize on page load
    updateCategories();
});
</script>
@endsection
