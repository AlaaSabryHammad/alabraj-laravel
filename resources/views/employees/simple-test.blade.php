<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Page</title>
</head>
<body>
    <h1>اختبار الاتصال</h1>
    <p>اسم الموظف: {{ $employee->name }}</p>
    <p>رقم الموظف: {{ $employee->id }}</p>
    <p>الوقت الحالي: {{ now() }}</p>
</body>
</html>
