<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير استهلاك المحروقات</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, 'Tajawal', sans-serif;
            background: white;
            color: #000;
            line-height: 1.4;
            padding: 20px;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        h1 {
            font-size: 18pt;
            margin-bottom: 5px;
            color: #000;
        }

        h2 {
            font-size: 14pt;
            margin: 15px 0 10px 0;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            color: #000;
        }

        p {
            font-size: 10pt;
            margin-bottom: 5px;
            color: #333;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #999;
            padding: 8px;
            background: white;
            page-break-inside: avoid;
        }

        .card p:first-child {
            font-size: 9pt;
            color: #666;
            margin-bottom: 3px;
        }

        .card-value {
            font-size: 16pt;
            font-weight: bold;
            color: #000;
            margin-bottom: 3px;
        }

        .card-unit {
            font-size: 8pt;
            color: #666;
        }

        .fuel-type-section {
            margin-bottom: 20px;
        }

        .fuel-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 10px;
        }

        .fuel-card {
            border: 1px solid #999;
            padding: 8px;
            background: white;
            page-break-inside: avoid;
        }

        .fuel-card h4 {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
        }

        .fuel-card-row {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            margin-bottom: 3px;
        }

        .fuel-card-label {
            color: #666;
        }

        .fuel-card-value {
            font-weight: bold;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9pt;
        }

        thead {
            display: table-header-group;
        }

        tbody {
            display: table-row-group;
        }

        th {
            background: #e8e8e8;
            color: #000;
            border: 1px solid #999;
            padding: 5px;
            text-align: right;
            font-weight: bold;
        }

        td {
            border: 1px solid #999;
            padding: 5px;
            color: #000;
            background: white;
            text-align: right;
        }

        tr {
            page-break-inside: avoid;
        }

        .status-approved {
            border: 1px solid #059669;
            color: #059669;
            padding: 1px 3px;
        }

        .status-rejected {
            border: 1px solid #dc2626;
            color: #dc2626;
            padding: 1px 3px;
        }

        .status-pending {
            border: 1px solid #d97706;
            color: #d97706;
            padding: 1px 3px;
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
        <!-- Header -->
        <div class="header">
            <h1>تقرير استهلاك المحروقات</h1>
            <p>عرض مفصل لاستهلاك المحروقات والزيوت بجميع أنواعها</p>
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
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
