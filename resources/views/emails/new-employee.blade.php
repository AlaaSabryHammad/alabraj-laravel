<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إشعار بإضافة موظف جديد</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #075985;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
        }

        .employee-info {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }

        .employee-info p {
            margin: 8px 0;
        }

        .footer {
            background-color: #f3f4f6;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }

        .btn {
            display: inline-block;
            background-color: #075985;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }

        .label {
            font-weight: bold;
            color: #075985;
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>إشعار بإضافة موظف جديد</h1>
        </div>

        <div class="content">
            <p>السلام عليكم ورحمة الله وبركاته،</p>

            <p>نود إعلامكم بأنه تم إضافة موظف جديد إلى نظام شركة الأبراج للمقاولات. يرجى الاطلاع على بيانات الموظف
                وتفعيل حسابه.</p>

            <div class="employee-info">
                <p><span class="label">الاسم:</span> {{ $employee->name }}</p>
                <p><span class="label">الدور الوظيفي:</span> {{ $employee->role }}</p>
                <p><span class="label">القسم:</span> {{ $employee->department }}</p>
                <p><span class="label">الهاتف:</span> {{ $employee->phone }}</p>
                <p><span class="label">البريد الإلكتروني:</span> {{ $employee->email }}</p>
                <p><span class="label">تاريخ التعيين:</span>
                    {{ \Carbon\Carbon::parse($employee->hire_date)->format('Y-m-d') }}</p>
                <p><span class="label">رقم الهوية:</span> {{ $employee->national_id }}</p>
                <p><span class="label">الموقع:</span>
                    {{ $employee->location ? $employee->location->name : 'غير محدد' }}</p>
                <p><span class="label">الحالة:</span>
                    {{ $employee->status === 'inactive' ? 'غير نشط (بانتظار التفعيل)' : $employee->status }}</p>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('employees.show', $employee) }}" class="btn">عرض بيانات الموظف</a>
            </p>

            <p>لتفعيل حساب الموظف، يرجى الدخول إلى صفحة تفاصيل الموظف والنقر على زر "تفعيل الحساب".</p>

            <p>شكراً لكم،<br>نظام إدارة الموظفين - شركة الأبراج للمقاولات</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} شركة الأبراج للمقاولات المحدودة. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>

</html>
