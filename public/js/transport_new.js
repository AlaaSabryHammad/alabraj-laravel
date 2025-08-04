// Transport Management JavaScript Functions
console.log('🚀 Transport JavaScript Module Loaded');

// Global variables
let currentDeleteId = null;

// Notification Modal Functions
function showNotificationModal(title, message, type = 'info') {
    const modal = document.getElementById('notificationModal');
    const titleEl = document.getElementById('notificationTitle');
    const messageEl = document.getElementById('notificationMessage');
    const iconEl = document.getElementById('notificationIcon');
    const iconClassEl = document.getElementById('notificationIconClass');

    if (!modal || !titleEl || !messageEl || !iconEl || !iconClassEl) {
        console.error('Notification modal elements not found');
        // Fallback to alert if modal elements are missing
        alert(title + '\n\n' + message);
        return;
    }

    titleEl.textContent = title;
    messageEl.textContent = message;

    // Set icon and color based on type
    iconEl.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4';

    switch (type) {
        case 'success':
            iconEl.classList.add('bg-green-100');
            iconClassEl.className = 'ri-check-line text-green-600 text-xl';
            break;
        case 'error':
            iconEl.classList.add('bg-red-100');
            iconClassEl.className = 'ri-error-warning-line text-red-600 text-xl';
            break;
        case 'warning':
            iconEl.classList.add('bg-yellow-100');
            iconClassEl.className = 'ri-alert-line text-yellow-600 text-xl';
            break;
        default: // info
            iconEl.classList.add('bg-blue-100');
            iconClassEl.className = 'ri-information-line text-blue-600 text-xl';
    }

    modal.classList.remove('hidden');
}

function closeNotificationModal() {
    const modal = document.getElementById('notificationModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// View Transport Function
function viewTransport(transportId) {
    console.log('👁️ viewTransport called with ID:', transportId);

    // Show modal
    const modal = document.getElementById('transportModal');
    if (!modal) {
        console.error('❌ Transport modal element not found!');
        showNotificationModal('خطأ', 'نافذة التفاصيل غير موجودة!', 'error');
        return;
    }

    modal.classList.remove('hidden');

    // Show loading
    const detailsContainer = document.getElementById('transportDetails');
    if (!detailsContainer) {
        console.error('❌ Transport details container not found!');
        showNotificationModal('خطأ', 'حاوية التفاصيل غير موجودة!', 'error');
        return;
    }

    detailsContainer.innerHTML = `
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2">جاري التحميل...</span>
        </div>
    `;

    // Check if CSRF token exists
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        document.getElementById('transportDetails').innerHTML = `
            <div class="text-center py-8 text-red-600">
                <i class="ri-error-warning-line text-4xl mb-2"></i>
                <p>CSRF token غير موجود</p>
            </div>
        `;
        return;
    }

    // Fetch transport details
    const baseUrl = window.location.origin;
    const url = `${baseUrl}/transport/${transportId}/details`;
    console.log('Fetching URL:', url);

    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                displayTransportDetails(data.transport);
            } else {
                document.getElementById('transportDetails').innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <i class="ri-error-warning-line text-4xl mb-2"></i>
                        <p>حدث خطأ في تحميل البيانات</p>
                        <p class="text-sm mt-2">${data.message || 'خطأ غير معروف'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('transportDetails').innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <i class="ri-error-warning-line text-4xl mb-2"></i>
                    <p>حدث خطأ في الاتصال</p>
                    <p class="text-sm mt-2">${error.message}</p>
                </div>
            `;
        });
}

function displayTransportDetails(transport) {
    const detailsHtml = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="ri-truck-line ml-2"></i>
                        معلومات المركبة
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">نوع المركبة:</span> <span class="font-medium">${transport.vehicle_type}</span></div>
                        <div><span class="text-gray-600">رقم المركبة:</span> <span class="font-medium">${transport.vehicle_number}</span></div>
                        <div><span class="text-gray-600">اسم السائق:</span> <span class="font-medium">${transport.driver_name}</span></div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="ri-map-pin-line ml-2"></i>
                        معلومات الرحلة
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">محطة التحميل:</span> <span class="font-medium">${transport.loading_location}</span></div>
                        <div><span class="text-gray-600">محطة التفريغ:</span> <span class="font-medium">${transport.unloading_location}</span></div>
                        <div><span class="text-gray-600">وقت المغادرة:</span> <span class="font-medium">${transport.departure_time || 'غير محدد'}</span></div>
                        <div><span class="text-gray-600">وقت الوصول:</span> <span class="font-medium">${transport.arrival_time || 'غير محدد'}</span></div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="ri-package-line ml-2"></i>
                        معلومات البضاعة
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">وصف البضاعة:</span> <span class="font-medium">${transport.cargo_description || 'غير محدد'}</span></div>
                        <div><span class="text-gray-600">الكمية:</span> <span class="font-medium">${transport.quantity || 'غير محدد'}</span></div>
                        ${transport.material ? `<div><span class="text-gray-600">المادة:</span> <span class="font-medium">${transport.material}</span></div>` : ''}
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="ri-information-line ml-2"></i>
                        معلومات إضافية
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">مسجل بواسطة:</span> <span class="font-medium">${transport.created_by || 'غير محدد'}</span></div>
                        <div><span class="text-gray-600">تاريخ التسجيل:</span> <span class="font-medium">${transport.created_at}</span></div>
                        ${transport.notes ? `<div><span class="text-gray-600">ملاحظات:</span> <span class="font-medium">${transport.notes}</span></div>` : ''}
                        ${transport.fuel_cost ? `<div><span class="text-gray-600">تكلفة الوقود:</span> <span class="font-medium">${transport.fuel_cost} ر.س</span></div>` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;

    document.getElementById('transportDetails').innerHTML = detailsHtml;
}

function closeModal() {
    document.getElementById('transportModal').classList.add('hidden');
}

// Delete Modal Functions
function showDeleteModal(transportId, vehicleNumber) {
    console.log('🗑️ showDeleteModal called with:', { transportId, vehicleNumber });
    currentDeleteId = transportId;

    // Update modal content with vehicle number
    const modal = document.getElementById('deleteModal');
    if (!modal) {
        console.error('❌ Delete modal element not found!');
        showNotificationModal('خطأ', 'نافذة الحذف غير موجودة!', 'error');
        return;
    }

    const message = modal.querySelector('#deleteMessage');
    if (!message) {
        console.error('❌ Delete message element not found!');
        showNotificationModal('خطأ', 'عنصر رسالة الحذف غير موجود!', 'error');
        return;
    }

    message.innerHTML = `هل أنت متأكد من حذف رحلة المركبة <strong>${vehicleNumber}</strong>؟<br>لا يمكن التراجع عن هذا الإجراء.`;

    // Show modal
    modal.classList.remove('hidden');

    console.log('✅ Delete modal shown for transport:', transportId, 'vehicle:', vehicleNumber);
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentDeleteId = null;
}

function confirmDelete() {
    if (currentDeleteId) {
        // Try desktop form first, then mobile form
        const form = document.getElementById('deleteForm' + currentDeleteId) ||
                    document.getElementById('deleteFormMobile' + currentDeleteId);

        if (form) {
            form.submit();
        } else {
            console.error('Delete form not found for ID:', currentDeleteId);
            showNotificationModal('خطأ', 'حدث خطأ في العثور على نموذج الحذف', 'error');
        }
    }
    closeDeleteModal();
}

// Print Functions
function printLoadingSlip(transportId) {
    console.log('🖨️ === Print Loading Slip Debug ===');
    console.log('Transport ID:', transportId);

    // Check if CSRF token exists
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('❌ CSRF token غير موجود');
        showNotificationModal('خطأ', 'CSRF token غير موجود', 'error');
        return;
    }

    console.log('✅ CSRF Token:', csrfToken.getAttribute('content'));

    // Show loading notification
    showNotificationModal('جاري الطباعة', 'يتم تجهيز سند التحميل للطباعة...', 'info');

    // Fetch transport details for printing
    const baseUrl = window.location.origin;
    const url = `${baseUrl}/transport/${transportId}/details`;
    console.log('Fetching from URL:', url);

    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        closeNotificationModal(); // Close loading modal
        if (data.success) {
            generateLoadingSlipPrint(data.transport);
        } else {
            showNotificationModal('خطأ في الطباعة', 'حدث خطأ في تحميل بيانات الرحلة: ' + (data.message || 'خطأ غير معروف'), 'error');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        closeNotificationModal(); // Close loading modal
        showNotificationModal('خطأ في الاتصال', 'حدث خطأ في الاتصال: ' + error.message, 'error');
    });
}

function generateLoadingSlipPrint(transport) {
    const printWindow = window.open('', '_blank');
    const currentDate = new Date().toLocaleDateString('ar-SA');
    const currentTime = new Date().toLocaleTimeString('ar-SA', { hour12: false });

    const printContent = `<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سند تحميل - رقم ${transport.id}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: 'Arial', 'Tahoma', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            border: 3px solid #2c3e50;
            background: white;
            min-height: 210mm;
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #2c3e50;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 10px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .company-info h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .company-info p {
            font-size: 14px;
            opacity: 0.9;
            margin: 2px 0;
        }

        .document-info {
            text-align: left;
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .document-info h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .document-info p {
            font-size: 13px;
            margin: 3px 0;
        }

        /* Main Content */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
        }

        .info-section {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .section-header {
            background: #007bff;
            color: white;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-content {
            padding: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
            min-width: 100px;
        }

        .info-value {
            font-weight: normal;
            color: #212529;
            text-align: left;
            flex: 1;
            margin-left: 10px;
            padding: 3px 8px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        /* Full Width Sections */
        .full-width-section {
            grid-column: 1 / -1;
            margin-top: 10px;
        }

        .notes-section {
            background: #fff3cd;
            border: 2px solid #ffc107;
        }

        .notes-section .section-header {
            background: #ffc107;
            color: #212529;
        }

        .notes-content {
            min-height: 60px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin: 10px;
            font-style: italic;
        }

        /* Signatures Section */
        .signatures {
            background: #e8f4fd;
            border-top: 3px solid #2c3e50;
            padding: 20px;
            margin-top: 20px;
        }

        .signatures-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
        }

        .signatures-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .signature-box {
            background: white;
            border: 2px solid #2c3e50;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .signature-title {
            font-weight: bold;
            color: #2c3e50;
            font-size: 13px;
            margin-bottom: 40px;
        }

        .signature-line {
            border-top: 2px solid #2c3e50;
            padding-top: 5px;
            font-size: 10px;
            color: #6c757d;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 15px 20px;
            text-align: center;
            font-size: 11px;
            line-height: 1.6;
        }

        .footer strong {
            font-size: 12px;
        }

        /* Print Styles */
        @media print {
            body {
                font-size: 11px;
            }
            .container {
                border: 2px solid #000;
                box-shadow: none;
            }
            .signatures-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">
                    <img src="${window.location.origin}/assets/logo.png" alt="شعار الشركة" onerror="this.style.display='none'">
                </div>
                <div class="company-info">
                    <h1>شركة الأبراج للمقاولات</h1>
                    <p>📍 المملكة العربية السعودية - الرياض</p>
                    <p>📞 هاتف: 011-4567890 | فاكس: 011-4567891</p>
                    <p>✉️ البريد الإلكتروني: info@abraj-contracting.com</p>
                </div>
            </div>
            <div class="document-info">
                <h2>سند تحميل</h2>
                <p><strong>رقم السند:</strong> ${transport.id}</p>
                <p><strong>تاريخ الإصدار:</strong> ${currentDate}</p>
                <p><strong>وقت الإصدار:</strong> ${currentTime}</p>
                <p><strong>المرجع:</strong> ABRAJ-${transport.id}-${new Date().getFullYear()}</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Vehicle Information -->
            <div class="info-section">
                <div class="section-header">
                    🚛 معلومات المركبة
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="info-label">نوع المركبة:</span>
                        <span class="info-value">${transport.vehicle_type}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">رقم اللوحة:</span>
                        <span class="info-value">${transport.vehicle_number}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">اسم السائق:</span>
                        <span class="info-value">${transport.driver_name}</span>
                    </div>
                </div>
            </div>

            <!-- Route Information -->
            <div class="info-section">
                <div class="section-header">
                    🗺️ معلومات المسار
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="info-label">نقطة التحميل:</span>
                        <span class="info-value">${transport.loading_location}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">نقطة التفريغ:</span>
                        <span class="info-value">${transport.unloading_location}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">وقت المغادرة:</span>
                        <span class="info-value">${transport.departure_time || 'غير محدد'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">وقت الوصول:</span>
                        <span class="info-value">${transport.arrival_time || 'غير محدد'}</span>
                    </div>
                </div>
            </div>

            <!-- Cargo Information -->
            <div class="info-section">
                <div class="section-header">
                    📦 معلومات البضاعة
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="info-label">نوع المادة:</span>
                        <span class="info-value">${transport.material || 'غير محدد'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">الكمية:</span>
                        <span class="info-value">${transport.quantity || 'غير محدد'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">وصف البضاعة:</span>
                        <span class="info-value">${transport.cargo_description || 'غير محدد'}</span>
                    </div>
                </div>
            </div>

            <!-- Admin Information -->
            <div class="info-section">
                <div class="section-header">
                    👤 المعلومات الإدارية
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="info-label">مسجل بواسطة:</span>
                        <span class="info-value">${transport.created_by}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">تاريخ التسجيل:</span>
                        <span class="info-value">${transport.created_at}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">حالة السند:</span>
                        <span class="info-value" style="color: #28a745; font-weight: bold;">ساري المفعول</span>
                    </div>
                </div>
            </div>

            <!-- Notes Section (Full Width) -->
            ${transport.notes ? `
            <div class="full-width-section notes-section">
                <div class="section-header">
                    📝 ملاحظات إضافية
                </div>
                <div class="notes-content">
                    ${transport.notes}
                </div>
            </div>
            ` : ''}
        </div>

        <!-- Signatures Section -->
        <div class="signatures">
            <div class="signatures-title">
                ✍️ التوقيعات والاعتمادات
            </div>
            <div class="signatures-grid">
                <div class="signature-box">
                    <div class="signature-title">مسؤول التحميل</div>
                    <div class="signature-line">التوقيع والختم</div>
                </div>
                <div class="signature-box">
                    <div class="signature-title">السائق</div>
                    <div class="signature-line">التوقيع والتاريخ</div>
                </div>
                <div class="signature-box">
                    <div class="signature-title">مسؤول التفريغ</div>
                    <div class="signature-line">التوقيع والختم</div>
                </div>
                <div class="signature-box">
                    <div class="signature-title">المدير المختص</div>
                    <div class="signature-line">الاعتماد والختم</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>هذا المستند تم إنشاؤه إلكترونياً بواسطة نظام إدارة النقل</strong><br>
            شركة الأبراج للمقاولات - المملكة العربية السعودية<br>
            تاريخ ووقت الطباعة: ${currentDate} - ${currentTime} | رقم المرجع: ABRAJ-TRANS-${transport.id}
        </div>
    </div>

    <script>
        window.onload = function() {
            // Auto print after load
            setTimeout(function() {
                window.print();
            }, 500);

            // Close after print
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</body>
</html>`;

    printWindow.document.write(printContent);
    printWindow.document.close();
}

// Initialize all functions on window object
window.showNotificationModal = showNotificationModal;
window.closeNotificationModal = closeNotificationModal;
window.viewTransport = viewTransport;
window.displayTransportDetails = displayTransportDetails;
window.closeModal = closeModal;
window.showDeleteModal = showDeleteModal;
window.closeDeleteModal = closeDeleteModal;
window.confirmDelete = confirmDelete;
window.printLoadingSlip = printLoadingSlip;
window.generateLoadingSlipPrint = generateLoadingSlipPrint;

// Event listeners setup
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Transport JavaScript Module Ready');

    // Add event listeners for modal close buttons
    const transportModal = document.getElementById('transportModal');
    const deleteModal = document.getElementById('deleteModal');
    const notificationModal = document.getElementById('notificationModal');

    if (transportModal) {
        transportModal.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    }

    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
    }

    if (notificationModal) {
        notificationModal.addEventListener('click', function(e) {
            if (e.target === this) closeNotificationModal();
        });
    }

    console.log('✅ All event listeners attached');
});
