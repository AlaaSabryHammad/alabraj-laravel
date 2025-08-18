<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختبار سريع لرفع الصور</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        button {
            padding: 10px;
            font-size: 16px;
        }

        input[type="file"] {
            width: 100%;
        }

        button {
            background: #007cba;
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background: #005a8b;
        }

        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .info {
            background: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>

<body>
    <h1>اختبار رفع صور المشروع</h1>

    <div class="form-group">
        <label>اختر مشروع:</label>
        @if (isset($projects) && $projects->count() > 0)
            <select id="projectSelect">
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        @else
            <div class="message info">لا توجد مشاريع متاحة للاختبار. سيتم استخدام المشروع رقم 1 افتراضياً.</div>
            <input type="hidden" id="projectSelect" value="1">
        @endif
    </div>

    <div class="form-group">
        <label>اختر الصور:</label>
        <input type="file" id="imageFiles" multiple accept="image/*">
    </div>

    <button onclick="testUpload()" id="uploadBtn">اختبار الرفع</button>

    <div id="results"></div>

    <script>
        function testUpload() {
            const files = document.getElementById('imageFiles').files;
            const projectId = document.getElementById('projectSelect').value;
            const btn = document.getElementById('uploadBtn');
            const results = document.getElementById('results');

            if (files.length === 0) {
                results.innerHTML = '<div class="message error">الرجاء اختيار ملف واحد على الأقل</div>';
                return;
            }

            // Disable button and show loading
            btn.disabled = true;
            btn.textContent = 'جاري الرفع...';
            results.innerHTML = '<div class="message info">جاري الرفع...</div>';

            // Prepare form data
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }

            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            console.log('Starting upload to project ID:', projectId);
            console.log('Files to upload:', files.length);
            console.log('CSRF Token:', token);

            // Make request
            fetch(`/projects/${projectId}/images`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response received:', response);
                    console.log('Status:', response.status);
                    console.log('Content-Type:', response.headers.get('content-type'));

                    if (!response.ok) {
                        if (response.status === 422) {
                            return response.json().then(data => {
                                throw new Error('خطأ في البيانات: ' + JSON.stringify(data.errors || data
                                    .message));
                            });
                        } else if (response.status === 403) {
                            throw new Error('غير مسموح بالوصول');
                        } else if (response.status === 404) {
                            throw new Error('المشروع غير موجود');
                        } else {
                            throw new Error(`خطأ HTTP ${response.status}`);
                        }
                    }

                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        return response.text().then(text => {
                            console.error('HTML Response:', text);
                            throw new Error('الخادم أرجع HTML بدلاً من JSON');
                        });
                    }
                })
                .then(data => {
                    console.log('Success:', data);
                    results.innerHTML = `<div class="message success">تم الرفع بنجاح! ${data.message}</div>`;
                })
                .catch(error => {
                    console.error('Error:', error);
                    results.innerHTML = `<div class="message error">خطأ: ${error.message}</div>`;
                })
                .finally(() => {
                    // Reset button
                    btn.disabled = false;
                    btn.textContent = 'اختبار الرفع';
                });
        }
    </script>
</body>

</html>
