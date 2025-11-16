@extends('layouts.app')

@section('title', 'إضافة سند إيراد جديد')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #059669;
        --primary-color-dark: #047857;
        --secondary-color: #f0fdf4;
        --text-color: #333;
        --text-color-light: #666;
        --border-color: #e5e7eb;
    }
    body {
        background-color: #f8fafc;
    }
    .form-container {
        background-color: white;
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    .form-header {
        background-color: var(--secondary-color);
        padding: 2rem;
        border-bottom: 1px solid var(--border-color);
        text-align: center;
    }
    .form-header h1 {
        color: var(--primary-color);
        font-weight: 800;
        font-size: 2.25rem;
    }
    .form-header p {
        color: var(--text-color-light);
    }
    .form-body {
        padding: 2.5rem;
    }
    .form-input-group {
        position: relative;
    }
    .form-input-group .form-icon {
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        color: white;
        opacity: 0.8;
    }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 1rem 2.5rem 1rem 1rem;
        border: 2px solid var(--primary-color-dark);
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        font-weight: 600;
        background-color: var(--primary-color);
        color: white;
    }
    .form-input::placeholder, .form-textarea::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--primary-color-dark);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }
    .form-label {
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        display: block;
    }
    .btn-primary {
        background-color: var(--primary-color);
        color: white;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        font-weight: 700;
        transition: background-color 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary:hover {
        background-color: var(--primary-color-dark);
    }
    .btn-secondary {
        background-color: #e5e7eb;
        color: #4b5563;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        font-weight: 700;
        transition: background-color 0.3s ease;
        border: none;
    }
    .btn-secondary:hover {
        background-color: #d1d5db;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto p-4 md:p-8" dir="rtl">
    <div class="max-w-5xl mx-auto">
        <div class="mb-4 flex justify-start">
            <a href="{{ route('finance.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-color transition-colors font-semibold">
                <i class="ri-arrow-right-s-line text-xl"></i>
                <span>العودة للمالية</span>
            </a>
        </div>
        <div class="form-container">
            <div class="form-header">
                <img src="{{ asset('assets/logo.png') }}" alt="شعار الأبراج" class="w-20 h-20 mx-auto mb-4 rounded-full border-4 border-white shadow-lg" onerror="this.style.display='none'">
                <h1>إنشاء سند إيراد جديد</h1>
                <p>قم بملء الحقول التالية لتسجيل إيراد جديد في النظام المالي</p>
            </div>

            <form action="{{ route('revenue-vouchers.store') }}" method="POST" enctype="multipart/form-data" class="form-body">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    <!-- Voucher Date -->
                    <div>
                        <label for="voucher_date" class="form-label">تاريخ السند <span class="text-red-500">*</span></label>
                        <div class="form-input-group">
                            <input type="date" name="voucher_date" id="voucher_date" value="{{ old('voucher_date', date('Y-m-d')) }}" class="form-input" required>
                            <i class="ri-calendar-2-line form-icon"></i>
                        </div>
                        @error('voucher_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="form-label">المبلغ <span class="text-red-500">*</span></label>
                        <div class="form-input-group">
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" class="form-input" required>
                            <i class="ri-money-dollar-circle-line form-icon"></i>
                        </div>
                        @error('amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Revenue Entity -->
                    <div class="md:col-span-2">
                        <label for="revenue_entity_id" class="form-label">جهة مصدر الإيراد <span class="text-red-500">*</span></label>
                        <select name="revenue_entity_id" id="revenue_entity_id" class="form-select" required>
                            <option value="">-- اختر الجهة --</option>
                            @foreach ($revenueEntities as $entity)
                                <option value="{{ $entity->id }}" {{ old('revenue_entity_id') == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
                            @endforeach
                        </select>
                        @error('revenue_entity_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="form-label">البيان <span class="text-red-500">*</span></label>
                        <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-input" required>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="form-label">طريقة الدفع <span class="text-red-500">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="">-- اختر طريقة الدفع --</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>نقداً</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                            <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمانية</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tax Type -->
                    <div>
                        <label for="tax_type" class="form-label">نوع الضريبة <span class="text-red-500">*</span></label>
                        <select name="tax_type" id="tax_type" class="form-select" required onchange="updateTaxCalculation()">
                            <option value="">-- اختر نوع الضريبة --</option>
                            <option value="taxable" {{ old('tax_type') == 'taxable' ? 'selected' : '' }}>خاضع للضريبة</option>
                            <option value="non_taxable" {{ old('tax_type') == 'non_taxable' ? 'selected' : '' }}>غير خاضع للضريبة</option>
                        </select>
                        @error('tax_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tax Information Display -->
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

                    <!-- Project -->
                    <div>
                        <label for="project_id" class="form-label">المشروع (اختياري)</label>
                        <select name="project_id" id="project_id" class="form-select">
                            <option value="">-- اختر المشروع --</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location_id" class="form-label">الموقع (اختياري)</label>
                        <select name="location_id" id="location_id" class="form-select">
                            <option value="">-- اختر الموقع --</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Attachment -->
                    <div class="md:col-span-2">
                        <label for="attachment" class="form-label">رفع مرفق (اختياري)</label>
                        <input type="file" name="attachment" id="attachment" class="form-input" style="padding: 0.5rem;">
                        @error('attachment')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                        <textarea name="notes" id="notes" rows="3" class="form-textarea">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200 flex items-center justify-end gap-4">
                    <a href="{{ route('revenue-vouchers.index') }}" class="btn-secondary">
                        <i class="ri-close-line"></i>
                        إلغاء
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="ri-save-3-line"></i>
                        حفظ السند
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateTaxCalculation() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const taxType = document.getElementById('tax_type').value;
        const taxRate = 15; // معدل الضريبة 15%
        
        // تحديث المبلغ المدخل
        document.getElementById('entered-amount').textContent = amount.toFixed(2) + ' ر.س';
        
        if (taxType === 'taxable') {
            // المبلغ شامل الضريبة، نحسب المبلغ بدون الضريبة
            const amountWithoutTax = amount / (1 + (taxRate / 100));
            const taxAmount = amount - amountWithoutTax;
            
            document.getElementById('amount-without-tax').textContent = amountWithoutTax.toFixed(2) + ' ر.س';
            document.getElementById('tax-amount').textContent = taxAmount.toFixed(2) + ' ر.س';
        } else if (taxType === 'non_taxable') {
            // غير خاضع للضريبة
            document.getElementById('amount-without-tax').textContent = amount.toFixed(2) + ' ر.س';
            document.getElementById('tax-amount').textContent = '0.00 ر.س';
        } else {
            // لم يتم اختيار نوع الضريبة
            document.getElementById('amount-without-tax').textContent = '0.00 ر.س';
            document.getElementById('tax-amount').textContent = '0.00 ر.س';
        }
    }
    
    // تحديث الحساب عند تغيير المبلغ
    document.getElementById('amount').addEventListener('input', updateTaxCalculation);
    
    // تحديث الحساب عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        updateTaxCalculation();
    });
</script>
@endpush