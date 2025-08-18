<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة سند القبض - {{ $revenueVoucher->voucher_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: white;
            color: #333;
            line-height: 1.2;
            font-size: 12px;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 10mm;
        }
        
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        
        .company-info {
            text-align: right;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #16a34a;
        }
        
        .document-title {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            text-align: center;
            background: #f0fdf4;
            padding: 6px;
            border: 1px solid #16a34a;
            margin-bottom: 8px;
        }
        
        .voucher-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            margin-bottom: 8px;
            font-size: 11px;
        }
        
        .voucher-number {
            font-size: 14px;
            font-weight: bold;
            color: #16a34a;
        }
        
        .voucher-date {
            font-size: 10px;
            color: #6b7280;
        }
        
        .amount-section {
            background: #16a34a;
            color: white;
            padding: 8px;
            text-align: center;
            margin: 8px 0;
            border: 2px solid #15803d;
        }
        
        .amount-label {
            font-size: 12px;
            margin-bottom: 2px;
        }
        
        .amount-value {
            font-size: 20px;
            font-weight: bold;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 11px;
        }
        
        .details-table td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            vertical-align: top;
        }
        
        .details-table .label {
            background: #f8f9fa;
            font-weight: bold;
            width: 25%;
            color: #374151;
        }
        
        .details-table .value {
            color: #6b7280;
            width: 25%;
        }
        
        .description-section {
            border: 1px solid #e5e7eb;
            padding: 8px;
            margin-bottom: 8px;
            font-size: 11px;
        }
        
        .description-section .label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 3px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #dbeafe; color: #1e40af; }
        .status-received { background: #d1fae5; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }
        
        .signature-box {
            text-align: center;
            padding: 8px 0;
        }
        
        .signature-line {
            border-bottom: 1px solid #374151;
            margin-bottom: 5px;
            height: 25px;
        }
        
        .signature-label {
            font-weight: bold;
            color: #374151;
            font-size: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 9px;
        }
        
        @media print {
            body { 
                font-size: 11px;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            .container { 
                padding: 5mm;
                max-width: none;
                width: 100%;
            }
            .no-print { display: none !important; }
            .amount-section { 
                background: #16a34a !important; 
                color: white !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('assets/logo.png') }}" alt="شعار الشركة" class="logo">
                <div class="company-info">
                    <div class="company-name">شركة الأبراج للمقاولات المحدودة</div>
                </div>
            </div>
            <div>
                <span class="status-badge status-{{ $revenueVoucher->status }}">
                    {{ $revenueVoucher->status_text }}
                </span>
            </div>
        </div>

        <!-- Document Title -->
        <div class="document-title">سند قبض - {{ $revenueVoucher->voucher_number }}</div>

        <!-- Voucher Info -->
        <div class="voucher-info">
            <div class="voucher-number">رقم السند: {{ $revenueVoucher->voucher_number }}</div>
            <div class="voucher-date">تاريخ السند: {{ $revenueVoucher->voucher_date->format('Y-m-d') }}</div>
            <div class="voucher-date">تاريخ الإنشاء: {{ $revenueVoucher->created_at->format('Y-m-d') }}</div>
        </div>

        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-label">إجمالي المبلغ المستلم</div>
            <div class="amount-value">{{ $revenueVoucher->formatted_amount }}</div>
        </div>

        <!-- Details Table -->
        <table class="details-table">
            <tr>
                <td class="label">جهة الإيراد</td>
                <td class="value">{{ $revenueVoucher->revenueEntity->name ?? 'غير محدد' }}</td>
                <td class="label">طريقة الدفع</td>
                <td class="value">{{ $revenueVoucher->payment_method_text }}</td>
            </tr>
            <tr>
                <td class="label">نوع الضريبة</td>
                <td class="value">{{ $revenueVoucher->tax_type_text }}</td>
                <td class="label">المشروع</td>
                <td class="value">{{ $revenueVoucher->project->name ?? 'غير محدد' }}</td>
            </tr>
            @if($revenueVoucher->location || $revenueVoucher->creator)
            <tr>
                <td class="label">الموقع</td>
                <td class="value">{{ $revenueVoucher->location->name ?? 'غير محدد' }}</td>
                <td class="label">منشئ السند</td>
                <td class="value">{{ $revenueVoucher->creator->name ?? 'غير محدد' }}</td>
            </tr>
            @endif
            @if($revenueVoucher->approver)
            <tr>
                <td class="label">معتمد السند</td>
                <td class="value">{{ $revenueVoucher->approver->name ?? 'غير محدد' }}</td>
                <td class="label">تاريخ الاعتماد</td>
                <td class="value">{{ $revenueVoucher->approved_at ? $revenueVoucher->approved_at->format('Y-m-d H:i') : 'لم يعتمد بعد' }}</td>
            </tr>
            @endif
        </table>

        <!-- Description -->
        @if($revenueVoucher->description)
        <div class="description-section">
            <div class="label">وصف المعاملة:</div>
            <div>{{ $revenueVoucher->description }}</div>
        </div>
        @endif

        <!-- Notes -->
        @if($revenueVoucher->notes)
        <div class="description-section">
            <div class="label">ملاحظات:</div>
            <div>{{ $revenueVoucher->notes }}</div>
        </div>
        @endif

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">المحاسب</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">المدير المالي</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">المدير العام</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>تم طباعة هذا السند في {{ now()->format('Y-m-d H:i') }}</p>
            <p>هذا السند صالح لمدة 30 يوماً من تاريخ الإصدار</p>
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="background: #16a34a; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            طباعة
        </button>
        <button onclick="window.close()" style="background: #6b7280; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            إغلاق
        </button>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            // Add small delay to ensure page is fully rendered
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>