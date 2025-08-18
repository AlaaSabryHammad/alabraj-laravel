
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>التقرير اليومي</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            background: white;
        }
        .container {
            width: 95%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            height: 80px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
        }
        .header p {
            font-size: 14px;
            margin: 5px 0;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            text-align: center;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            width: 22%;
        }
        .summary-box h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #555;
        }
        .summary-box p {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .balance-equation {
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .totals {
            display: flex;
            justify-content: space-around;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .footer div {
            width: 30%;
        }
        .footer p {
            font-weight: bold;
            margin-bottom: 40px;
        }
        .footer .signature {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="شعار الشركة">
            <h1>شركة الأبراج العالمية</h1>
            <p>التقرير اليومي للمعاملات المالية</p>
            <p>تاريخ التقرير: {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</p>
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
        </div>

        <div class="summary">
            <div class="summary-box">
                <h3>الرصيد المرحل</h3>
                <p style="color: {{ $carriedBalance >= 0 ? 'blue' : 'red' }};">{{ number_format($carriedBalance, 2) }} ر.س</p>
            </div>
            <div class="summary-box">
                <h3>إيرادات اليوم</h3>
                <p style="color: green;">{{ number_format($dayRevenue, 2) }} ر.س</p>
            </div>
            <div class="summary-box">
                <h3>مصروفات اليوم</h3>
                <p style="color: red;">{{ number_format($dayExpense, 2) }} ر.س</p>
            </div>
            <div class="summary-box">
                <h3>الرصيد النهائي</h3>
                <p style="color: {{ $finalBalance >= 0 ? 'green' : 'red' }};">{{ number_format($finalBalance, 2) }} ر.س</p>
            </div>
        </div>

        <div class="balance-equation">
            {{ number_format($carriedBalance, 2) }} + {{ number_format($dayRevenue, 2) }} - {{ number_format($dayExpense, 2) }} = {{ number_format($finalBalance, 2) }} ر.س
        </div>

        <h3>تفاصيل معاملات يوم {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>رقم السند</th>
                    <th>النوع</th>
                    <th>الوصف</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction['number'] }}</td>
                        <td>{{ $transaction['type'] }}</td>
                        <td>{{ $transaction['description'] }}</td>
                        <td style="color: {{ $transaction['is_income'] ? 'green' : 'red' }};">
                            {{ $transaction['is_income'] ? '+' : '-' }}{{ number_format($transaction['amount'], 2) }} ر.س
                        </td>
                        <td>{{ $transaction['status'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">لا توجد معاملات في هذا اليوم</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="totals">
            <div><strong>مجموع الإيرادات:</strong> <span style="color: green;">+{{ number_format($dayRevenue, 2) }} ر.س</span></div>
            <div><strong>مجموع المصروفات:</strong> <span style="color: red;">-{{ number_format($dayExpense, 2) }} ر.س</span></div>
            <div><strong>صافي الحركة:</strong> <span style="color: {{ $dayNet >= 0 ? 'green' : 'red' }};">{{ $dayNet >= 0 ? '+' : '' }}{{ number_format($dayNet, 2) }} ر.س</span></div>
        </div>

        <div class="footer">
            <div>
                <p>المدير المالي</p>
                <div class="signature"></div>
            </div>
            <div>
                <p>المحاسب</p>
                <div class="signature"></div>
            </div>
            <div>
                <p>أمين الصندوق</p>
                <div class="signature"></div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
