<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة العهدة - C-{{ str_pad($custody->id, 6, '0', STR_PAD_LEFT) }}</title>
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
            border-bottom: 2px solid #2563eb;
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
            color: #2563eb;
        }
        
        .document-title {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            text-align: center;
            background: #eff6ff;
            padding: 6px;
            border: 1px solid #2563eb;
            margin-bottom: 8px;
        }
        
        .custody-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            margin-bottom: 8px;
            font-size: 11px;
        }
        
        .custody-number {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .custody-date {
            font-size: 10px;
            color: #6b7280;
        }
        
        .amount-section {
            background: #2563eb;
            color: white;
            padding: 8px;
            text-align: center;
            margin: 8px 0;
            border: 2px solid #1d4ed8;
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
        
        .employee-section {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 8px;
            margin-bottom: 8px;
            font-size: 11px;
        }
        
        .employee-section .section-title {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 6px;
            font-size: 12px;
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
            background: #dbeafe;
            color: #1e40af;
        }
        
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
        
        .terms-section {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            padding: 8px;
            margin: 8px 0;
            font-size: 10px;
        }
        
        .terms-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
        }
        
        .terms-list {
            color: #92400e;
            line-height: 1.3;
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
                background: #2563eb !important; 
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
                <span class="status-badge">
                    {{ $custody->status_text ?? 'تم الصرف' }}
                </span>
            </div>
        </div>

        <!-- Document Title -->
        <div class="document-title">إيصال عهدة - C-{{ str_pad($custody->id, 6, '0', STR_PAD_LEFT) }}</div>

        <!-- Custody Info -->
        <div class="custody-info">
            <div class="custody-number">رقم العهدة: C-{{ str_pad($custody->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="custody-date">تاريخ الصرف: {{ $custody->disbursement_date->format('Y-m-d') }}</div>
            <div class="custody-date">تاريخ الإنشاء: {{ $custody->created_at->format('Y-m-d') }}</div>
        </div>

        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-label">مبلغ العهدة</div>
            <div class="amount-value">{{ number_format($custody->amount, 2) }} ر.س</div>
        </div>

        <!-- Employee Section -->
        <div class="employee-section">
            <div class="section-title">معلومات الموظف المستلم</div>
            <table class="details-table" style="background: transparent; border: none;">
                <tr>
                    <td class="label" style="background: transparent; border: none;">اسم الموظف</td>
                    <td class="value" style="border: none; font-weight: 600; color: #374151;">{{ $custody->employee->name }}</td>
                    <td class="label" style="background: transparent; border: none;">رقم الموظف</td>
                    <td class="value" style="border: none;">{{ $custody->employee->employee_number ?? 'غير محدد' }}</td>
                </tr>
                <tr>
                    <td class="label" style="background: transparent; border: none;">المنصب</td>
                    <td class="value" style="border: none;">{{ $custody->employee->position ?? 'غير محدد' }}</td>
                    <td class="label" style="background: transparent; border: none;">رقم الجوال</td>
                    <td class="value" style="border: none;">{{ $custody->employee->phone ?? 'غير متوفر' }}</td>
                </tr>
            </table>
        </div>

        <!-- Details Table -->
        <table class="details-table">
            <tr>
                <td class="label">تاريخ الصرف</td>
                <td class="value">{{ $custody->disbursement_date->format('Y-m-d') }}</td>
                <td class="label">طريقة الاستلام</td>
                <td class="value">{{ $custody->receipt_method_text }}</td>
            </tr>
            <tr>
                <td class="label">تاريخ الإنشاء</td>
                <td class="value">{{ $custody->created_at->format('Y-m-d H:i') }}</td>
                <td class="label">الحالة</td>
                <td class="value">{{ $custody->status_text ?? 'تم الصرف' }}</td>
            </tr>
        </table>

        <!-- Notes -->
        @if($custody->notes)
        <div class="description-section">
            <div class="detail-label">ملاحظات</div>
            <div class="detail-value">{{ $custody->notes }}</div>
        </div>
        @endif

        <!-- Terms and Conditions -->
        <div class="terms-section">
            <div class="terms-title">شروط وأحكام العهدة:</div>
            <div class="terms-list">
                • يتعهد الموظف بالحفاظ على المبلغ المستلم واستخدامه للغرض المحدد<br>
                • يجب إرجاع المبلغ المتبقي مع المستندات المطلوبة خلال المدة المحددة<br>
                • في حالة عدم تسوية العهدة، سيتم خصم المبلغ من راتب الموظف<br>
                • هذا الإيصال مُلزم قانونياً ويجب الاحتفاظ به
            </div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">الموظف المستلم</div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                    {{ $custody->employee->name }}
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">المدير المالي</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">مدير الموارد البشرية</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>تم طباعة هذا الإيصال في {{ now()->format('Y-m-d H:i') }}</p>
            <p>يجب على الموظف الاحتفاظ بنسخة من هذا الإيصال</p>
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
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