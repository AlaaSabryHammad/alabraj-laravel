<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار عرض الصور</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .image-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            cursor: pointer;
        }

        .image-info {
            padding: 10px;
            background: #f5f5f5;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }

        .modal-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <h1>اختبار عرض صور المشروع</h1>

    <div id="imagesContainer">
        <p>جاري تحميل الصور...</p>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <img id="modalImage" class="modal-image" src="" alt="">
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
    </div>

    <script>
        // Load project images
        async function loadProjectImages() {
            try {
                const response = await fetch('/api/projects/1/images');
                if (!response.ok) {
                    throw new Error('Failed to load images');
                }

                const data = await response.json();
                displayImages(data.images || []);
            } catch (error) {
                console.error('Error loading images:', error);

                // Fallback: test with some sample images
                testWithSampleImages();
            }
        }

        function testWithSampleImages() {
            const sampleImages = [{
                path: 'projects/1/images/test_image.png',
                alt_text: 'صورة اختبار'
            }];

            displayImages(sampleImages, true);
        }

        function displayImages(images, isTest = false) {
            const container = document.getElementById('imagesContainer');

            if (images.length === 0) {
                container.innerHTML = '<p>لا توجد صور متاحة للعرض</p>';
                return;
            }

            const grid = document.createElement('div');
            grid.className = 'image-grid';

            images.forEach((image, index) => {
                const imageUrl = isTest ?
                    `/storage/${image.path}` :
                    `/storage/${image.image_path}`;

                const item = document.createElement('div');
                item.className = 'image-item';

                item.innerHTML = `
                    <img src="${imageUrl}" 
                         alt="${image.alt_text || 'صورة المشروع'}" 
                         onclick="openModal('${imageUrl}', '${image.alt_text || 'صورة المشروع'}')"
                         onerror="handleImageError(this, ${index})">
                    <div class="image-info">
                        <p><strong>الرقم:</strong> ${index + 1}</p>
                        <p><strong>المسار:</strong> ${imageUrl}</p>
                        <p><strong>الوصف:</strong> ${image.alt_text || 'غير محدد'}</p>
                    </div>
                `;

                grid.appendChild(item);
            });

            container.innerHTML = '';
            container.appendChild(grid);
        }

        function handleImageError(img, index) {
            console.error(`Image ${index + 1} failed to load:`, img.src);
            img.style.background = '#f0f0f0';
            img.alt = 'فشل في تحميل الصورة';
            img.src =
                'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBmaWxsPSIjOTk5IiBmaWxsLW9wYWNpdHk9IjAuNSIvPgo8L3N2Zz4K';
        }

        function openModal(imageSrc, imageTitle) {
            console.log('Opening modal for:', imageSrc);

            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = imageSrc;
            modalImage.alt = imageTitle;
            modal.classList.add('show');
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('show');
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Load images when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadProjectImages();
        });
    </script>
</body>

</html>
