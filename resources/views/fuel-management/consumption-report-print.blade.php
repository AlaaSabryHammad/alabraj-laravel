<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير استهلاك المحروقات - شركة الأبراج للمقاولات</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', Arial, sans-serif;
            background: white;
            color: #1a1a1a;
            line-height: 1.6;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 10mm 8mm;
            page-break-after: always;
        }

        /* Header Professional */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 10px 8px;
            border-bottom: 2px solid #2c5a7e;
            background: white;
            border-radius: 0;
            gap: 8px;
        }

        .logo-section {
            flex: 0 0 auto;
            text-align: center;
        }

        .logo-section img {
            height: 50px;
            width: auto;
            object-fit: contain;
        }

        .company-info {
            flex: 1;
            text-align: center;
            margin: 0 8px;
        }

        .company-name {
            font-size: 13pt;
            font-weight: 700;
            color: #2c5a7e;
            margin-bottom: 1px;
        }

        .company-subtitle {
            font-size: 9pt;
            color: #5a6c7d;
            font-weight: 500;
        }

        .report-title {
            flex: 0 0 auto;
            text-align: center;
        }

        h1 {
            font-size: 15pt;
            margin: 0;
            color: #2c5a7e;
            font-weight: 700;
        }

        .report-date {
            font-size: 8pt;
            color: #666;
        }

        .header p {
            color: #666;
            font-size: 11pt;
            margin-top: 8px;
        }

        h2 {
            font-size: 11pt;
            margin: 8px 0 6px 0;
            border-right: 3px solid #2c5a7e;
            padding-right: 6px;
            color: #2c5a7e;
            font-weight: 700;
        }

        p {
            font-size: 9pt;
            margin-bottom: 3px;
            color: #333;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-bottom: 10px;
        }

        .card {
            border: 1px solid #d0dce8;
            padding: 8px;
            background: white;
            page-break-inside: avoid;
            border-radius: 3px;
            border-top: 3px solid #2c5a7e;
            box-shadow: none;
        }

        .card p:first-child {
            font-size: 8pt;
            color: #5a6c7d;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .card-value {
            font-size: 16pt;
            font-weight: 700;
            color: #2c5a7e;
            margin-bottom: 2px;
        }

        .card-unit {
            font-size: 7pt;
            color: #7a8a9d;
            font-weight: 500;
        }

        .fuel-type-section {
            margin-bottom: 10px;
        }

        .fuel-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            margin-bottom: 8px;
        }

        .fuel-card {
            border: 1px solid #d0dce8;
            padding: 8px;
            background: white;
            page-break-inside: avoid;
            border-radius: 3px;
            border-right: 2px solid #2c5a7e;
            box-shadow: none;
        }

        .fuel-card h4 {
            font-size: 9pt;
            font-weight: 700;
            margin-bottom: 4px;
            color: #2c5a7e;
            border-bottom: 1px solid #d0dce8;
            padding-bottom: 2px;
        }

        .fuel-card-row {
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            margin-bottom: 2px;
            padding: 1px 0;
        }

        .fuel-card-label {
            color: #5a6c7d;
            font-weight: 500;
        }

        .fuel-card-value {
            font-weight: 700;
            color: #2c5a7e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 8pt;
        }

        thead {
            display: table-header-group;
        }

        tbody {
            display: table-row-group;
        }

        th {
            background: #2c5a7e;
            color: #fff;
            border: 1px solid #1f3f5a;
            padding: 5px 6px;
            text-align: right;
            font-weight: 700;
            font-size: 8pt;
            letter-spacing: 0px;
        }

        td {
            border: 1px solid #e0e0e0;
            padding: 4px 6px;
            color: #1a1a1a;
            background: white;
            text-align: right;
            font-size: 8pt;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fc;
        }

        tbody tr:nth-child(odd) {
            background: white;
        }

        tr {
            page-break-inside: avoid;
        }

        .status-approved {
            background: #d1e7dd;
            border: 1px solid #0a6e47;
            color: #0a3622;
            padding: 2px 4px;
            border-radius: 1px;
            font-weight: 600;
            font-size: 7pt;
            display: inline-block;
        }

        .status-rejected {
            background: #f8d7da;
            border: 1px solid #842029;
            color: #842029;
            padding: 2px 4px;
            border-radius: 1px;
            font-weight: 600;
            font-size: 7pt;
            display: inline-block;
        }

        .status-pending {
            background: #fff3cd;
            border: 1px solid #664d03;
            color: #664d03;
            padding: 2px 4px;
            border-radius: 1px;
            font-weight: 600;
            font-size: 7pt;
            display: inline-block;
        }

        @media print {
            body {
                margin: 0;
                padding: 10mm;
            }

            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Professional Header with Logo -->
        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('assets/logo.png') }}" alt="شركة الأبراج">
            </div>
            <div class="company-info">
                <div class="company-name">شركة الأبراج للمقاولات</div>
                <div class="company-subtitle">للمقاولات المتخصصة</div>
            </div>
            <div class="report-title">
                <h1>تقرير استهلاك المحروقات</h1>
                <div class="report-date">{{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="card">
                <p>إجمالي الاستهلاك</p>
                <div class="card-value">{{ number_format($totalConsumption, 2) }}</div>
                <div class="card-unit">لتر</div>
            </div>

            <div class="card">
                <p>عدد السجلات</p>
                <div class="card-value">{{ count($consumptions) }}</div>
                <div class="card-unit">عملية استهلاك</div>
            </div>

            <div class="card">
                <p>الفترة الزمنية</p>
                <div class="card-value" style="font-size: 12pt;">{{ $startDate->locale('ar')->isoFormat('D MMMM') }}</div>
                <div class="card-unit">إلى {{ $endDate->locale('ar')->isoFormat('D MMMM') }}</div>
            </div>

            <div class="card">
                <p>عدد أنواع المحروقات</p>
                <div class="card-value">{{ count($byFuelType) }}</div>
                <div class="card-unit">نوع</div>
            </div>
        </div>

        <!-- Consumption by Fuel Type -->
        @php $byFuelTypeCount = is_array($byFuelType) ? count($byFuelType) : $byFuelType->count(); @endphp
        @if($byFuelTypeCount > 0)
            <h2>الاستهلاك حسب نوع المحروقات</h2>
            <div class="fuel-type-section">
                <div class="fuel-cards">
                    @forelse($byFuelType as $fuelType => $quantity)
                        @php
                            $fuelTypeMap = [
                                'diesel' => 'ديزل',
                                'gasoline' => 'بنزين',
                                'engine_oil' => 'زيت ماكينة',
                                'hydraulic_oil' => 'زيت هيدروليك',
                                'radiator_water' => 'ماء ردياتير',
                                'brake_oil' => 'زيت فرامل',
                                'other' => 'أخرى',
                            ];
                            $fuelTypeText = $fuelTypeMap[$fuelType] ?? $fuelType;
                        @endphp
                        <div class="fuel-card">
                            <h4>{{ $fuelTypeText }}</h4>
                            <div class="fuel-card-row">
                                <span class="fuel-card-label">الكمية الإجمالية:</span>
                                <span class="fuel-card-value">{{ number_format($quantity, 2) }} لتر</span>
                            </div>
                            <div class="fuel-card-row">
                                <span class="fuel-card-label">النسبة المئوية:</span>
                                <span class="fuel-card-value">{{ $totalConsumption > 0 ? number_format(($quantity / $totalConsumption) * 100, 1) : 0 }}%</span>
                            </div>
                        </div>
                    @empty
                        <p style="text-align: center; padding: 20px; color: #666;">لا توجد بيانات استهلاك</p>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Detailed Table -->
        <h2>سجلات الاستهلاك التفصيلية</h2>

        @if(count($consumptions) > 0)
            <table>
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المعدة</th>
                        <th>نوع المحروقات</th>
                        <th>الكمية</th>
                        <th>المسجل</th>
                        <th>الحالة</th>
                        <th>الملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consumptions as $consumption)
                        <tr>
                            <td>{{ $consumption['date_formatted'] ?? '-' }}</td>
                            <td>{{ $consumption['equipment_name'] ?? '-' }}</td>
                            <td>{{ $consumption['fuel_type'] ?? '-' }}</td>
                            <td>{{ $consumption['quantity'] ?? 0 }} لتر</td>
                            <td>{{ $consumption['user_name'] ?? '-' }}</td>
                            <td>
                                @if(isset($consumption['approval_status']))
                                    @if($consumption['approval_status'] === 'approved')
                                        <span class="status-approved">{{ $consumption['status'] }}</span>
                                    @elseif($consumption['approval_status'] === 'rejected')
                                        <span class="status-rejected">{{ $consumption['status'] }}</span>
                                    @else
                                        <span class="status-pending">{{ $consumption['status'] }}</span>
                                    @endif
                                @else
                                    <span class="status-pending">-</span>
                                @endif
                            </td>
                            <td>{{ $consumption['notes'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">لم يتم تسجيل أي استهلاك للمحروقات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <p style="text-align: center; padding: 20px; color: #666;">لم يتم تسجيل أي استهلاك للمحروقات في الفترة المحددة</p>
        @endif

        <!-- Footer -->
        <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #d0dce8; font-size: 7pt; color: #7a8a9d; text-align: center;">
            <p style="margin: 1px 0;">شركة الأبراج للمقاولات - نظام إدارة المحروقات والمعدات</p>
            <p style="margin: 1px 0;">{{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY HH:mm') }}</p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
