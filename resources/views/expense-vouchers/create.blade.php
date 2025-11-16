@extends('layouts.app')

@section('title', 'تسجيل سند صرف جديد')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تسجيل سند صرف جديد</h1>
            <p class="text-gray-600 mt-1">إنشاء سند صرف جديد مع رقم تسلسلي تلقائي</p>
        </div>
        <a href="{{ route('finance.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
            <i class="ri-arrow-right-line"></i>
            العودة للمالية
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Errors -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white rounded-t-xl">
            <h3 class="text-xl font-bold flex items-center gap-3">
                <i class="ri-file-text-line text-2xl"></i>
                بيانات سند الصرف
            </h3>
        </div>

        <form action="{{ route('expense-vouchers.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- رقم السند (تلقائي) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-hashtag text-blue-600"></i>
                        رقم السند
                    </label>
                    <input type="text" 
                           value="سيتم توليده تلقائياً"
                           disabled
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                </div>

                <!-- تاريخ السند -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-calendar-line text-blue-600"></i>
                        تاريخ السند *
                    </label>
                    <input type="date" 
                           name="voucher_date" 
                           value="{{ date('Y-m-d') }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- فئة الصرف -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-price-tag-3-line text-blue-600"></i>
                        فئة الصرف *
                    </label>
                    <select name="expense_category_id" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر فئة الصرف</option>
                        @foreach($expenseCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- الموظف معتمد الصرف -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-user-line text-blue-600"></i>
                        الموظف معتمد الصرف
                    </label>
                    <select name="employee_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر الموظف (اختياري)</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }} - {{ $employee->position }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- المبلغ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-money-dollar-circle-line text-blue-600"></i>
                        المبلغ (ريال سعودي) *
                    </label>
                    <input type="number" 
                           name="amount" 
                           step="0.01" 
                           min="0" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="0.00">
                </div>

                <!-- طريقة الصرف -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-hand-coin-line text-blue-600"></i>
                        طريقة الصرف *
                    </label>
                    <select name="payment_method" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر طريقة الصرف</option>
                        <option value="cash">نقداً</option>
                        <option value="bank_transfer">تحويل بنكي</option>
                        <option value="check">شيك</option>
                        <option value="credit_card">بطاقة ائتمانية</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
            </div>

            <!-- نوع الضريبة -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-percent-line text-blue-600"></i>
                        نوع الضريبة *
                    </label>
                    <select name="tax_type" 
                            required
                            onchange="updateTaxCalculation()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر نوع الضريبة</option>
                        <option value="taxable">ضريبي</option>
                        <option value="non_taxable">غير ضريبي</option>
                    </select>
                </div>

                <!-- فراغ للمحاذاة -->
                <div></div>
            </div>

            <!-- معلومات الضريبة -->
            <div class="md:col-span-2">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <label class="font-medium text-gray-700">المبلغ المدخل:</label>
                            <p id="entered-amount" class="text-gray-900">0.00 ر.س</p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-700">المبلغ بدون الضريبة:</label>
                            <p id="amount-without-tax" class="text-gray-900">0.00 ر.س</p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-700">قيمة الضريبة (15%):</label>
                            <p id="tax-amount" class="text-gray-900">0.00 ر.س</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- البيان -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="ri-file-text-line text-blue-600"></i>
                    البيان *
                </label>
                <textarea name="description" 
                          rows="4" 
                          required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="اكتب تفاصيل سند الصرف..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- جهة الصرف -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-building-line text-blue-600"></i>
                        جهة الصرف
                    </label>
                    <select name="expense_entity_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر الجهة (اختياري)</option>
                        @foreach($expenseEntities as $entity)
                            <option value="{{ $entity->id }}">{{ $entity->name }} ({{ $entity->type_text }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- المشروع -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-building-2-line text-blue-600"></i>
                        المشروع
                    </label>
                    <select name="project_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر المشروع (اختياري)</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- الموقع -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-map-pin-line text-blue-600"></i>
                        الموقع
                    </label>
                    <select name="location_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر الموقع (اختياري)</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- رقم الفاتورة -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-barcode-line text-blue-600"></i>
                        رقم الفاتورة
                    </label>
                    <input type="text" 
                           name="reference_number"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="رقم الفاتورة (اختياري)">
                </div>

                <!-- فراغ للمحاذاة -->
                <div></div>
            </div>

            <!-- الملاحظات -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="ri-sticky-note-line text-blue-600"></i>
                    الملاحظات
                </label>
                <textarea name="notes" 
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="أي ملاحظات إضافية..."></textarea>
            </div>

            <!-- رفع ملف -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="ri-attachment-line text-blue-600"></i>
                    رفع ملف (اختياري)
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="attachment" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="ri-upload-cloud-2-line text-3xl text-gray-400 mb-2"></i>
                            <p class="mb-2 text-sm text-gray-500">
                                <span class="font-semibold">انقر لرفع ملف</span> أو اسحب وأفلت
                            </p>
                            <p class="text-xs text-gray-500">PDF, JPG, PNG, DOC, DOCX (الحد الأقصى 5 ميجابايت)</p>
                        </div>
                        <input id="attachment" name="attachment" type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" />
                    </label>
                </div>
                <div id="file-name" class="mt-2 text-sm text-gray-600 hidden"></div>
            </div>

            <!-- أزرار الحفظ -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('finance.index') }}" 
                   class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                    <i class="ri-save-line"></i>
                    حفظ سند الصرف
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// دالة حساب الضريبة
function updateTaxCalculation() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const taxType = document.getElementById('tax_type').value;
    
    const enteredAmountEl = document.getElementById('entered-amount');
    const amountWithoutTaxEl = document.getElementById('amount-without-tax');
    const taxAmountEl = document.getElementById('tax-amount');
    
    if (taxType === 'taxable') {
        // المبلغ شامل الضريبة (15%)
        const taxRate = 15;
        const amountWithTax = amount;
        const amountWithoutTax = amountWithTax / (1 + (taxRate / 100));
        const taxAmount = amountWithTax - amountWithoutTax;
        
        enteredAmountEl.textContent = amountWithTax.toFixed(2) + ' ر.س';
        amountWithoutTaxEl.textContent = amountWithoutTax.toFixed(2) + ' ر.س';
        taxAmountEl.textContent = taxAmount.toFixed(2) + ' ر.س';
    } else if (taxType === 'non_taxable') {
        // غير خاضع للضريبة
        enteredAmountEl.textContent = amount.toFixed(2) + ' ر.س';
        amountWithoutTaxEl.textContent = amount.toFixed(2) + ' ر.س';
        taxAmountEl.textContent = '0.00 ر.س';
    } else {
        // لم يتم اختيار نوع الضريبة
        enteredAmountEl.textContent = '0.00 ر.س';
        amountWithoutTaxEl.textContent = '0.00 ر.س';
        taxAmountEl.textContent = '0.00 ر.س';
    }
}

// معالجة رفع الملف وعرض اسم الملف المختار
document.getElementById('attachment').addEventListener('change', function(e) {
    const fileNameDiv = document.getElementById('file-name');
    const file = e.target.files[0];
    
    if (file) {
        fileNameDiv.textContent = 'ملف مختار: ' + file.name;
        fileNameDiv.classList.remove('hidden');
    } else {
        fileNameDiv.classList.add('hidden');
    }
});

// إضافة event listeners لحقول الضريبة
document.getElementById('amount').addEventListener('input', updateTaxCalculation);
document.getElementById('tax_type').addEventListener('change', updateTaxCalculation);

// تشغيل الحساب عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', updateTaxCalculation);

// إضافة تأثير السحب والإفلات
const dropArea = document.querySelector('label[for="attachment"]');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
});

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

dropArea.addEventListener('drop', handleDrop, false);

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight() {
    dropArea.classList.add('border-blue-500', 'bg-blue-50');
}

function unhighlight() {
    dropArea.classList.remove('border-blue-500', 'bg-blue-50');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        const fileInput = document.getElementById('attachment');
        fileInput.files = files;
        
        // تشغيل حدث التغيير لعرض اسم الملف
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }
}
</script>

@endsection