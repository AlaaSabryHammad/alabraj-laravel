<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختبار رفع الصور</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
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
            padding: 10px;
            border: 1px solid #ccc;
            width: 100%;
        }

        button {
            padding: 10px 20px;
            background: #007cba;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #005a8b;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <h1>اختبار رفع صور المشروع</h1>

    <form id="testForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="project_id">رقم المشروع:</label>
            <input type="number" id="project_id" name="project_id" value="1" required>
        </div>

        <div class="form-group">
            <label for="images">اختر الصور:</label>
            <input type="file" id="images" name="images[]" multiple accept="image/*" required>
        </div>

        <div class="form-group">
            <label for="description">الوصف:</label>
            <textarea id="description" name="description" rows="3" placeholder="وصف اختياري للصور"></textarea>
        </div>

        <button type="submit">رفع الصور</button>
    </form>

    <div id="result"></div>

    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const projectId = document.getElementById('project_id').value;
            const formData = new FormData();
            const images = document.getElementById('images').files;
            const description = document.getElementById('description').value;

            // Add images
            for (let i = 0; i < images.length; i++) {
                formData.append('images[]', images[i]);
            }

            // Add description if provided
            if (description) {
                formData.append('description', description);
            }

            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);

                    // Check content type
                    const contentType = response.headers.get('content-type');
                    console.log('Content type:', contentType);

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        return response.text().then(text => {
                            throw new Error('Server returned HTML instead of JSON: ' + text.substring(0,
                                200));
                        });
                    }
                })
                .then(data => {
                    console.log('Success:', data);
                    document.getElementById('result').innerHTML =
                        '<div class="result success">نجح الرفع: ' + data.message + '</div>';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('result').innerHTML =
                        '<div class="result error">خطأ: ' + error.message + '</div>';
                });
        });
    </script>
</body>

</html>
