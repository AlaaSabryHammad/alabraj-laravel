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
        alert(title + '\n\n' + message);
        return;
    }

    titleEl.textContent = title;
    messageEl.textContent = message;

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
        default:
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

    const modal = document.getElementById('transportModal');
    if (!modal) {
        console.error('❌ Transport modal element not found!');
        showNotificationModal('خطأ', 'نافذة التفاصيل غير موجودة!', 'error');
        return;
    }

    modal.classList.remove('hidden');

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

    modal.classList.remove('hidden');

    console.log('✅ Delete modal shown for transport:', transportId, 'vehicle:', vehicleNumber);
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentDeleteId = null;
}

function confirmDelete() {
    if (currentDeleteId) {
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

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('❌ CSRF token غير موجود');
        showNotificationModal('خطأ', 'CSRF token غير موجود', 'error');
        return;
    }

    console.log('✅ CSRF Token:', csrfToken.getAttribute('content'));

    showNotificationModal('جاري الطباعة', 'يتم تجهيز سند التحميل للطباعة...', 'info');

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
        closeNotificationModal();
        if (data.success) {
            generateLoadingSlipPrint(data.transport);
        } else {
            showNotificationModal('خطأ في الطباعة', 'حدث خطأ في تحميل بيانات الرحلة: ' + (data.message || 'خطأ غير معروف'), 'error');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        closeNotificationModal();
        showNotificationModal('خطأ في الاتصال', 'حدث خطأ في الاتصال: ' + error.message, 'error');
    });
}

function generateLoadingSlipPrint(transport) {
    const printWindow = window.open('', '_blank');
    const currentDate = new Date().toLocaleDateString('ar-SA');
    const currentTime = new Date().toLocaleTimeString('ar-SA', { hour12: false });

    const printContent = `
<!DOCTYPE html>
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
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: 'Arial', 'Tahoma', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
            direction: rtl;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            min-height: 297mm;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .company-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .company-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .document-info {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            text-align: center;
        }

        .document-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .document-number {
            font-size: 18px;
            color: #fbbf24;
            font-weight: bold;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-section {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-header {
            background: #3b82f6;
            color: white;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 14px;
        }

        .section-content {
            padding: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #cbd5e1;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #475569;
        }

        .info-value {
            color: #1e293b;
            background: white;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        /* Route Section */
        .route-section {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .route-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #0c4a6e;
            margin-bottom: 15px;
        }

        .route-flow {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .route-point {
            background: white;
            border: 2px solid #0ea5e9;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            min-width: 150px;
        }

        .route-point-title {
            font-size: 12px;
            color: #0c4a6e;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .route-point-value {
            font-size: 14px;
            color: #1e293b;
            font-weight: bold;
        }

        .route-arrow {
            font-size: 24px;
            color: #0ea5e9;
            font-weight: bold;
        }

        /* Signatures */
        .signatures {
            background: #f0fdf4;
            border: 2px solid #22c55e;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .signatures-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #15803d;
            margin-bottom: 20px;
        }

        .signatures-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .signature-box {
            background: white;
            border: 2px solid #22c55e;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            min-height: 80px;
        }

        .signature-title {
            font-weight: bold;
            color: #15803d;
            font-size: 12px;
            margin-bottom: 35px;
        }

        .signature-line {
            border-top: 2px solid #22c55e;
            padding-top: 5px;
            font-size: 10px;
            color: #6b7280;
        }

        /* Footer */
        .footer {
            background: #1f2937;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 11px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .footer-highlight {
            color: #fbbf24;
            font-weight: bold;
        }

        /* Print Optimizations */
        @media print {
            body {
                font-size: 11px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                margin: 10mm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-title">شركة الأبراج للمقاولات</div>
            <div class="company-subtitle">المملكة العربية السعودية - الرياض</div>
            <div class="company-subtitle">هاتف: 011-4567890 | البريد الإلكتروني: info@abraj-contracting.com</div>

            <div class="document-info">
                <div class="document-title">سند تحميل</div>
                <div class="document-number">رقم المرجع: #${transport.id}</div>
                <div style="margin-top: 10px; font-size: 12px;">التاريخ: ${currentDate} | الوقت: ${currentTime}</div>
            </div>
        </div>

        <!-- Route Information -->
        <div class="route-section">
            <div class="route-title">🗺️ مسار الرحلة</div>
            <div class="route-flow">
                <div class="route-point">
                    <div class="route-point-title">نقطة التحميل</div>
                    <div class="route-point-value">${transport.loading_location}</div>
                </div>
                <div class="route-arrow">←</div>
                <div class="route-point">
                    <div class="route-point-title">نقطة التفريغ</div>
                    <div class="route-point-value">${transport.unloading_location}</div>
                </div>
            </div>
        </div>

        <!-- Main Information Grid -->
        <div class="content-grid">
            <!-- Vehicle Information -->
            <div class="info-section">
                <div class="section-header">🚛 معلومات المركبة</div>
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

            <!-- Cargo Information -->
            <div class="info-section">
                <div class="section-header">📦 معلومات البضاعة</div>
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
                        <span class="info-label">الوصف:</span>
                        <span class="info-value">${transport.cargo_description || 'غير محدد'}</span>
                    </div>
                </div>
            </div>

            <!-- Time Information -->
            <div class="info-section">
                <div class="section-header">⏰ الأوقات والمواعيد</div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="info-label">وقت المغادرة:</span>
                        <span class="info-value">${transport.departure_time || 'غير محدد'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">وقت الوصول:</span>
                        <span class="info-value">${transport.arrival_time || 'غير محدد'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">تاريخ السند:</span>
                        <span class="info-value">${currentDate}</span>
                    </div>
                </div>
            </div>

            <!-- Administrative Information -->
            <div class="info-section">
                <div class="section-header">👤 المعلومات الإدارية</div>
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
                        <span class="info-label">رقم المرجع:</span>
                        <span class="info-value">ABRAJ-${transport.id}-2025</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signatures Section -->
        <div class="signatures">
            <div class="signatures-title">✍️ التوقيعات والاعتمادات</div>
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
            <span class="footer-highlight">هذا المستند تم إنشاؤه إلكترونياً بواسطة نظام إدارة النقل</span><br>
            شركة الأبراج للمقاولات - المملكة العربية السعودية<br>
            تاريخ ووقت الطباعة: <span class="footer-highlight">${currentDate} - ${currentTime}</span> |
            رقم المرجع: <span class="footer-highlight">ABRAJ-TRANS-${transport.id}</span>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);

            window.onafterprint = function() {
                setTimeout(function() {
                    window.close();
                }, 500);
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
