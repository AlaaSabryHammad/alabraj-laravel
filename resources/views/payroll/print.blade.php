<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة مسيرة الراتب - {{ $payroll->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background: white;
            font-size: 14px;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 15px 15px 0 0;
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            z-index: 1;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .company-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .company-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }

        .document-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin: 20px 0 10px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        /* Info Card */
        .info-card {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 0 0 15px 15px;
            padding: 25px;
            margin-bottom: 25px;
            border-top: none;
        }

        .payroll-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #10b981;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-draft {
            background: #e5e7eb;
            color: #374151;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }

        .summary-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .summary-title {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }

        .summary-currency {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Table Styles */
        .table-container {
            margin: 25px 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .table-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #f8fafc;
            color: #374151;
            font-weight: 600;
            padding: 12px 8px;
            text-align: center;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 12px 8px;
            text-align: center;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }

        tr:nth-child(even) {
            background: #fafbfc;
        }

        tr:hover {
            background: #f0fdf4;
        }

        .employee-name {
            font-weight: bold;
            color: #1f2937;
        }

        .employee-id {
            font-size: 11px;
            color: #6b7280;
        }

        .salary-amount {
            font-weight: 600;
            color: #059669;
        }

        .bonus-amount {
            color: #059669;
            font-weight: 500;
        }

        .deduction-amount {
            color: #dc2626;
            font-weight: 500;
        }

        .eligible-badge {
            background: #dcfce7;
            color: #166534;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .not-eligible-badge {
            background: #fee2e2;
            color: #991b1b;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .signature-section {
            text-align: center;
        }

        .signature-title {
            font-weight: bold;
            color: #374151;
            margin-bottom: 40px;
            font-size: 14px;
        }

        .signature-line {
            border-bottom: 2px solid #374151;
            width: 200px;
            margin: 0 auto;
        }

        .print-info {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }

        /* Print specific styles */
        @media print {
            body {
                background: white;
            }

            .container {
                padding: 10px;
                max-width: none;
            }

            .print-info {
                position: static;
                margin-top: 20px;
            }

            .table-container {
                break-inside: avoid;
            }

            .summary-grid {
                break-inside: avoid;
            }
        }

        /* Page break styles */
        .page-break {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="company-logo">
                    <div class="logo-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="company-name">شركة الأبراج للمقاولات</div>
                        <div class="company-subtitle">نظام إدارة الموارد البشرية والرواتب</div>
                    </div>
                </div>
                <div class="document-title">مسيرة الرواتب الشهرية</div>
            </div>
        </div>

        <!-- Payroll Information -->
        <div class="info-card">
            <div class="payroll-info">
                <div class="info-item">
                    <div class="info-label">عنوان المسيرة</div>
                    <div class="info-value">{{ $payroll->title }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ الراتب</div>
                    <div class="info-value">{{ $payroll->payroll_date->format('Y-m-d') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ الإنشاء</div>
                    <div class="info-value">{{ $payroll->created_at->format('Y-m-d H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">حالة المسيرة</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $payroll->status }}">
                            @if($payroll->status === 'draft')
                                مسودة
                            @elseif($payroll->status === 'pending')
                                في انتظار المراجعة
                            @elseif($payroll->status === 'approved')
                                معتمدة
                            @endif
                        </span>
                    </div>
                </div>
                @if($payroll->notes)
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">ملاحظات</div>
                    <div class="info-value">{{ $payroll->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-title">إجمالي الموظفين</div>
                <div class="summary-value">{{ $payroll->employees->count() }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-title">المستحقين للراتب</div>
                <div class="summary-value">{{ $payroll->employees->where('is_eligible', true)->count() }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-title">إجمالي الرواتب</div>
                <div class="summary-value">{{ number_format($payroll->employees->sum('base_salary'), 0) }}</div>
                <div class="summary-currency">ريال سعودي</div>
            </div>
            <div class="summary-card">
                <div class="summary-title">صافي المستحق</div>
                <div class="summary-value">{{ number_format($payroll->employees->sum('net_salary'), 0) }}</div>
                <div class="summary-currency">ريال سعودي</div>
            </div>
        </div>

        <!-- Employees Table -->
        <div class="table-container">
            <div class="table-header">تفاصيل رواتب الموظفين</div>
            <table>
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>الراتب الأساسي</th>
                        <th>البدلات</th>
                        <th>الاستقطاعات</th>
                        <th>صافي الراتب</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payroll->employees as $payrollEmployee)
                        <tr>
                            <td>
                                <div class="employee-name">{{ $payrollEmployee->employee->name }}</div>
                                @if($payrollEmployee->employee->employee_id)
                                    <div class="employee-id">{{ $payrollEmployee->employee->employee_id }}</div>
                                @endif
                            </td>
                            <td class="salary-amount">{{ number_format($payrollEmployee->base_salary, 2) }} ريال</td>
                            <td class="bonus-amount">{{ number_format($payrollEmployee->total_bonuses, 2) }} ريال</td>
                            <td class="deduction-amount">{{ number_format($payrollEmployee->total_deductions, 2) }} ريال</td>
                            <td class="salary-amount">{{ number_format($payrollEmployee->net_salary, 2) }} ريال</td>
                            <td>
                                @if($payrollEmployee->is_eligible)
                                    <span class="eligible-badge">مستحق</span>
                                @else
                                    <span class="not-eligible-badge">غير مستحق</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Detailed Breakdown -->
        @php
            $eligibleEmployees = $payroll->employees->where('is_eligible', true);
            $hasDeductions = $eligibleEmployees->sum(function($emp) { return $emp->deductions->count(); }) > 0;
            $hasBonuses = $eligibleEmployees->sum(function($emp) { return $emp->bonuses->count(); }) > 0;
        @endphp
        @if($hasDeductions || $hasBonuses)
            <div class="page-break">
                <div class="table-container">
                    <div class="table-header">تفصيل الاستقطاعات والبدلات</div>
                    @foreach($eligibleEmployees as $payrollEmployee)
                        @if($payrollEmployee->deductions->count() > 0 || $payrollEmployee->bonuses->count() > 0)
                            <div style="margin: 20px 0; padding: 15px; background: #f8fafc; border-radius: 10px;">
                                <h3 style="color: #1f2937; margin-bottom: 15px; font-size: 16px;">{{ $payrollEmployee->employee->name }}</h3>

                                @if($payrollEmployee->bonuses->count() > 0)
                                    <div style="margin-bottom: 15px;">
                                        <h4 style="color: #059669; margin-bottom: 10px;">البدلات</h4>
                                        <table style="width: 100%; margin: 0;">
                                            <thead>
                                                <tr>
                                                    <th style="background: #dcfce7; color: #166534;">نوع البدل</th>
                                                    <th style="background: #dcfce7; color: #166534;">المبلغ</th>
                                                    <th style="background: #dcfce7; color: #166534;">ملاحظات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($payrollEmployee->bonuses as $bonus)
                                                    <tr>
                                                        <td>{{ $bonus->type }}</td>
                                                        <td class="bonus-amount">{{ number_format($bonus->amount, 2) }} ريال</td>
                                                        <td>{{ $bonus->notes ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if($payrollEmployee->deductions->count() > 0)
                                    <div>
                                        <h4 style="color: #dc2626; margin-bottom: 10px;">الاستقطاعات</h4>
                                        <table style="width: 100%; margin: 0;">
                                            <thead>
                                                <tr>
                                                    <th style="background: #fee2e2; color: #991b1b;">نوع الاستقطاع</th>
                                                    <th style="background: #fee2e2; color: #991b1b;">المبلغ</th>
                                                    <th style="background: #fee2e2; color: #991b1b;">ملاحظات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($payrollEmployee->deductions as $deduction)
                                                    <tr>
                                                        <td>{{ $deduction->type }}</td>
                                                        <td class="deduction-amount">{{ number_format($deduction->amount, 2) }} ريال</td>
                                                        <td>{{ $deduction->notes ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="signature-section">
                <div class="signature-title">إعداد وتدقيق</div>
                <div class="signature-line"></div>
                <div style="margin-top: 10px; font-size: 12px; color: #6b7280;">قسم الموارد البشرية</div>
            </div>
            <div class="signature-section">
                <div class="signature-title">اعتماد</div>
                <div class="signature-line"></div>
                <div style="margin-top: 10px; font-size: 12px; color: #6b7280;">
                    @if($payroll->approved_at)
                        معتمد بتاريخ: {{ $payroll->approved_at->format('Y-m-d') }}
                    @else
                        الإدارة المالية
                    @endif
                </div>
            </div>
        </div>

        <!-- Print Info -->
        <div class="print-info">
            <p>تم إنشاء هذا التقرير في {{ now()->format('Y-m-d H:i:s') }} - نظام إدارة الموارد البشرية - شركة الأبراج للمقاولات</p>
        </div>
    </div>
</body>
</html>
