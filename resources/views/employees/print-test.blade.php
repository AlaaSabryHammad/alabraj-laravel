<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار - {{ $employee->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            padding: 20px;
            direction: rtl;
        }
        .test-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .info {
            font-size: 16px;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="test-card">
        <h1>🧪 صفحة اختبار الطباعة</h1>
        <div class="info">
            <p><strong>اسم الموظف:</strong> {{ $employee->name }}</p>
            <p><strong>المنصب:</strong> {{ $employee->position ?? 'غير محدد' }}</p>
            <p><strong>رقم الموظف:</strong> {{ $employee->id }}</p>
            <p><strong>القسم:</strong> {{ $employee->department ?? 'غير محدد' }}</p>
            <p><strong>رقم الهاتف:</strong> {{ $employee->phone ?? 'غير محدد' }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $employee->email ?? 'غير محدد' }}</p>
            <p><strong>تاريخ التوظيف:</strong> {{ $employee->hire_date ? $employee->hire_date->format('Y/m/d') : 'غير محدد' }}</p>
            <p><strong>الراتب:</strong> {{ $employee->salary ? number_format($employee->salary, 0) . ' ريال سعودي' : 'غير محدد' }}</p>
            <p><strong>الحالة:</strong> {{ $employee->status === 'active' ? 'نشط' : 'غير نشط' }}</p>
        </div>

        <hr style="margin: 30px 0;">

        <p style="text-align: center; color: #666; font-size: 14px;">
            إذا كنت ترى هذا النص، فإن الصفحة تعمل بشكل صحيح!<br>
            تم إنشاء هذه الصفحة في: {{ now()->format('Y/m/d H:i:s') }}
        </p>
    </div>
</body>
</html>
