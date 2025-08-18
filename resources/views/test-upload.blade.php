<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار رفع الصور</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .btn {
            background: #3b82f6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>اختبار رفع الصور للمشروع</h1>

        @if (session('success'))
            <div style="background: #10b981; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background: #ef4444; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('projects.images.store', 1) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>اختر الصور:</label>
                <input type="file" name="images[]" multiple accept="image/*" required>
                <small>يمكن اختيار عدة صور</small>
            </div>

            <div class="form-group">
                <label>وصف الصور (اختياري):</label>
                <textarea name="description" rows="3" placeholder="أضف وصفاً للصور..."></textarea>
            </div>

            <button type="submit" class="btn">رفع الصور</button>
        </form>

        <hr style="margin: 30px 0;">

        <h2>معلومات الـ Route:</h2>
        <p><strong>Route Name:</strong> projects.images.store</p>
        <p><strong>Route URL:</strong> {{ route('projects.images.store', 1) }}</p>
        <p><strong>Method:</strong> POST</p>
        <p><strong>Project ID:</strong> 1</p>
    </div>
</body>

</html>
