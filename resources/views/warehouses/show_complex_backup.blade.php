@extends('layouts.app')

@section('title', 'مستودع ' . $warehouse->name)

@push('scripts')
    <script>
        // دوال Modals الأساسية
        function showDevelopmentModal(title, message) {
            const modalHTML = `
        <div id="developmentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white">${title}</h3>
                        <button type="button" onclick="closeDevelopmentModal()" class="text-white hover:text-gray-200">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-tools-line text-2xl text-blue-600"></i>
                    </div>
                    <p class="text-gray-700 text-lg">${message}</p>
                    <button onclick="closeDevelopmentModal()" 
                            class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        حسناً
                    </button>
                </div>
            </div>
        </div>
    `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function closeDevelopmentModal() {
            const modal = document.getElementById('developmentModal');
            if (modal) {
                modal.remove();
            }
        }

        function showErrorModal(title, message) {
            const modalHTML = `
        <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white">${title}</h3>
                        <button type="button" onclick="closeErrorModal()" class="text-white hover:text-gray-200">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-error-warning-line text-2xl text-red-600"></i>
                    </div>
                    <p class="text-gray-700 text-lg">${message}</p>
                    <button onclick="closeErrorModal()" 
                            class="mt-6 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            if (modal) {
                modal.remove();
            }
        }

        // تعريف الدوال مباشرة في الرأس
        window.showSparePartDetails = function(sparePartId) {
            console.log('استدعاء showSparePartDetails من head:', sparePartId);
            if (!sparePartId) {
                showErrorModal('خطأ في البيانات', 'معرف قطعة الغيار غير صالح');
                return;
            }
            showDevelopmentModal('تفاصيل قطعة الغيار', 'معرف القطعة: ' + sparePartId);
        };

        window.openExportModal = function(sparePartId) {
            console.log('استدعاء openExportModal من head:', sparePartId);
            showDevelopmentModal('تصدير قطع غيار', 'معرف القطعة: ' + sparePartId);
        };

        window.openReceiveModalWithData = function(type, sparePartId, sparePartName) {
            console.log('استدعاء openReceiveModalWithData من head:', type, sparePartId, sparePartName);
            showDevelopmentModal('استلام قطع غيار', 'القطعة: ' + sparePartName);
        };

        // دالة فتح modal نوع الاستلام
        window.openReceiveTypeModal = function() {
            const modalHTML = `
                <div id="receiveTypeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl">
                        <div class="bg-gradient-to-r from-green-600 to-blue-600 p-6 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold">اختر نوع الاستلام</h3>
                                <button type="button" onclick="closeReceiveTypeModal()" 
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <i class="ri-close-line text-white"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-8 space-y-4">
                            <p class="text-gray-600 text-center mb-6">حدد نوع الاستلام المناسب لقطع الغيار</p>
                            
                            <button type="button" onclick="openNewPartsReceiveModal(); closeReceiveTypeModal();" 
                                    class="w-full bg-blue-50 hover:bg-blue-100 border-2 border-blue-200 hover:border-blue-300 p-6 rounded-xl transition-all duration-300 flex items-center gap-4 text-right">
                                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="ri-file-text-line text-2xl text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-gray-900">قطع غيار جديدة</div>
                                    <div class="text-sm text-gray-600">من فاتورة شراء أو مورد جديد</div>
                                </div>
                            </button>

                            <button type="button" onclick="showDevelopmentModal('استلام قطع غيار تالفة', 'سيتم تطوير وظيفة استلام القطع التالفة من المشاريع قريباً'); closeReceiveTypeModal();" 
                                    class="w-full bg-orange-50 hover:bg-orange-100 border-2 border-orange-200 hover:border-orange-300 p-6 rounded-xl transition-all duration-300 flex items-center gap-4 text-right">
                                <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center">
                                    <i class="ri-error-warning-line text-2xl text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-gray-900">قطع غيار تالفة</div>
                                    <div class="text-sm text-gray-600">استلام قطع غيار تحتاج لإصلاح</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        };

        // دالة إغلاق modal نوع الاستلام
        window.closeReceiveTypeModal = function() {
            const modal = document.getElementById('receiveTypeModal');
            if (modal) {
                modal.remove();
            }
        };

        // دالة استلام قطع غيار جديدة
        window.openNewPartsReceiveModal = function() {
            const modalHTML = `
                <div id="newPartsReceiveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                        <div class="bg-gradient-to-r from-blue-600 to-green-600 p-6 text-white rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold flex items-center gap-3">
                                    <i class="ri-add-box-line text-3xl"></i>
                                    استلام قطع غيار جديدة
                                </h3>
                                <button type="button" onclick="closeNewPartsReceiveModal()" 
                                        class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                                    <i class="ri-close-line text-white text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <form id="newPartsForm" class="p-8">
                            <!-- معلومات المورد -->
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="ri-building-line text-blue-600"></i>
                                    معلومات المورد
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم المورد *</label>
                                        <input type="text" id="supplierName" name="supplier_name" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="أدخل اسم المورد">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الفاتورة *</label>
                                        <input type="text" id="invoiceNumber" name="invoice_number" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="رقم الفاتورة">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الفاتورة *</label>
                                        <input type="date" id="invoiceDate" name="invoice_date" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">المبلغ الإجمالي</label>
                                        <input type="number" id="totalAmount" name="total_amount" step="0.01"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="المبلغ الإجمالي">
                                    </div>
                                </div>
                            </div>

                            <!-- قطع الغيار -->
                            <div class="mb-8">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                        <i class="ri-tools-line text-green-600"></i>
                                        قطع الغيار
                                    </h4>
                                    <button type="button" onclick="addSparePartRow()" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                        <i class="ri-add-line"></i>
                                        إضافة قطعة
                                    </button>
                                </div>
                                
                                <div id="sparePartsContainer">
                                    <div class="spare-part-row bg-gray-50 p-4 rounded-lg mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">اسم القطعة *</label>
                                                <input type="text" name="spare_parts[0][name]" required
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="اسم قطعة الغيار">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الكود</label>
                                                <input type="text" name="spare_parts[0][code]"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="كود القطعة">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية *</label>
                                                <input type="number" name="spare_parts[0][quantity]" required min="1"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="الكمية" onchange="calculateRowTotal(0)">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">سعر الوحدة *</label>
                                                <input type="number" name="spare_parts[0][unit_price]" required step="0.01"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="سعر الوحدة" onchange="calculateRowTotal(0)">
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                                                <textarea name="spare_parts[0][description]" rows="2"
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                          placeholder="وصف قطعة الغيار"></textarea>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">إجمالي السعر</label>
                                                    <input type="text" id="rowTotal_0" readonly
                                                           class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                                                           placeholder="0.00 ريال">
                                                </div>
                                                <button type="button" onclick="removeSparePartRow(this)" 
                                                        class="mt-6 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ملاحظات -->
                            <div class="mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="أي ملاحظات إضافية"></textarea>
                            </div>

                            <!-- أزرار الحفظ -->
                            <div class="flex justify-end gap-4 pt-6 border-t">
                                <button type="button" onclick="closeNewPartsReceiveModal()" 
                                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                    إلغاء
                                </button>
                                <button type="submit" 
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                                    <i class="ri-save-line"></i>
                                    حفظ وإضافة إلى المخزون
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // إضافة معالج النموذج بعد إنشاء المودال
            setTimeout(() => {
                const form = document.getElementById('newPartsForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(form);
                        const submitButton = form.querySelector('button[type="submit"]');

                        // تعطيل الزر أثناء الإرسال
                        submitButton.disabled = true;
                        submitButton.innerHTML =
                            '<i class="ri-loader-4-line animate-spin"></i> جاري الحفظ...';

                        // للتجربة الآن - عرض رسالة نجاح مؤقتة
                        setTimeout(() => {
                            showSuccessModal('تم بنجاح',
                                'تم حفظ البيانات بنجاح وإضافتها إلى المخزون');
                            closeNewPartsReceiveModal();
                        }, 2000);
                    });
                }
            }, 100);
        };

        // دالة إغلاق modal استلام قطع جديدة
        window.closeNewPartsReceiveModal = function() {
            const modal = document.getElementById('newPartsReceiveModal');
            if (modal) {
                modal.remove();
            }
        };

        // دوال إدارة قطع الغيار في النموذج
        window.addSparePartRow = function() {
            const container = document.getElementById('sparePartsContainer');
            const rowCount = container.children.length;

            const newRowHTML = `
                <div class="spare-part-row bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم القطعة *</label>
                            <input type="text" name="spare_parts[${rowCount}][name]" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="اسم قطعة الغيار">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الكود</label>
                            <input type="text" name="spare_parts[${rowCount}][code]"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="كود القطعة">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الكمية *</label>
                            <input type="number" name="spare_parts[${rowCount}][quantity]" required min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="الكمية" onchange="calculateRowTotal(${rowCount})">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">سعر الوحدة *</label>
                            <input type="number" name="spare_parts[${rowCount}][unit_price]" required step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="سعر الوحدة" onchange="calculateRowTotal(${rowCount})">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                            <textarea name="spare_parts[${rowCount}][description]" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="وصف قطعة الغيار"></textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">إجمالي السعر</label>
                                <input type="text" id="rowTotal_${rowCount}" readonly
                                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                                       placeholder="0.00 ريال">
                            </div>
                            <button type="button" onclick="removeSparePartRow(this)" 
                                    class="mt-6 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', newRowHTML);
        };

        window.removeSparePartRow = function(button) {
            const container = document.getElementById('sparePartsContainer');
            if (container.children.length > 1) {
                button.closest('.spare-part-row').remove();
            } else {
                showErrorModal('تحذير', 'يجب أن تكون هناك قطعة غيار واحدة على الأقل');
            }
        };

        window.calculateRowTotal = function(rowIndex) {
            const quantityInput = document.querySelector(`input[name="spare_parts[${rowIndex}][quantity]"]`);
            const priceInput = document.querySelector(`input[name="spare_parts[${rowIndex}][unit_price]"]`);
            const totalInput = document.getElementById(`rowTotal_${rowIndex}`);

            if (quantityInput && priceInput && totalInput) {
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = quantity * price;

                totalInput.value = total.toFixed(2) + ' ريال';
            }
        };

        // دالة رسالة النجاح
        window.showSuccessModal = function(title, message) {
            const modalHTML = `
                <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-t-xl">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white">${title}</h3>
                                <button type="button" onclick="closeSuccessModal()" class="text-white hover:text-gray-200">
                                    <i class="ri-close-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-check-line text-2xl text-green-600"></i>
                            </div>
                            <p class="text-gray-700 text-lg">${message}</p>
                            <button onclick="closeSuccessModal()" 
                                    class="mt-6 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                                تمام
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);
        };

        window.closeSuccessModal = function() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.remove();
            }
        };
    </script>
@endpush

@section('content')
    <style>
        .action-btn {
            pointer-events: auto !important;
            user-select: none;
        }

        .action-btn:hover {
            transform: scale(1.1) !important;
        }

        .action-btn i {
            pointer-events: none !important;
        }
    </style>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50" dir="rtl">
        <!-- Header Section -->
        <div class="bg-white shadow-lg border-b-4 border-blue-600 mb-8">
            <div class="container mx-auto px-6 py-6">
                <div class="flex items-center justify-between">
                    <!-- Left Side - Navigation & Title -->
                    <div class="flex items-center space-x-reverse space-x-6">
                        <a href="{{ route('warehouses.index') }}"
                            class="flex items-center gap-3 text-gray-600 hover:text-blue-600 transition-colors group">
                            <div
                                class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                <i class="ri-arrow-right-line text-lg"></i>
                            </div>
                            <span class="font-medium">العودة للمستودعات</span>
                        </a>

                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-600 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="ri-building-2-line text-2xl text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">{{ $warehouse->name }}</h1>
                                <p class="text-gray-600 flex items-center gap-2">
                                    <i class="ri-map-pin-line text-green-600"></i>
                                    شركة الأبراج للمقاولات - إدارة المستودعات
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Actions -->
                    <div class="flex items-center gap-4">
                        <!-- Status Filter -->
                        <div class="relative">
                            <select id="filterStatus"
                                class="appearance-none bg-white border-2 border-gray-200 rounded-xl px-4 py-3 pr-10 text-gray-700 focus:border-blue-500 focus:outline-none shadow-sm">
                                <option value="all">جميع الحالات</option>
                                <option value="available">متوفر</option>
                                <option value="low">كمية منخفضة</option>
                                <option value="out">نفد المخزون</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="ri-filter-line text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Add New Button -->
                        <button type="button" onclick="openReceiveTypeModal()"
                            class="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-6 py-3 rounded-xl transition-all duration-300 flex items-center gap-3 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="ri-add-circle-line text-xl"></i>
                            <span class="font-semibold">استلام قطع غيار</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="container mx-auto px-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Items -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">إجمالي القطع</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $newInventory->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="ri-tools-line text-xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Available Stock -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">المخزون المتاح</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">{{ $newInventory->sum('current_stock') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="ri-check-circle-line text-xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Low Stock -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">كمية منخفضة</p>
                            <p class="text-3xl font-bold text-orange-600 mt-1">
                                {{ $newInventory->filter(function ($item) {return ($item->available_stock ?? $item->current_stock) <= 10 && ($item->available_stock ?? $item->current_stock) > 0;})->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="ri-error-warning-line text-xl text-orange-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Value -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">إجمالي القيمة</p>
                            <p class="text-3xl font-bold text-purple-600 mt-1">
                                {{ number_format($newInventory->sum('total_value'), 0) }}</p>
                            <p class="text-xs text-gray-500 mt-1">ريال سعودي</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="ri-money-dollar-circle-line text-xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-6">
            <!-- New Spare Parts Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Section Header -->
                <div class="bg-gradient-to-r from-blue-600 to-green-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                <i class="ri-tools-fill text-xl text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold">قطع الغيار الجديدة</h2>
                                <p class="text-blue-100">إجمالي {{ $newInventory->count() }} قطعة غيار</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="button" onclick="printWarehouseReport()"
                                class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-printer-line"></i>
                                طباعة التقرير
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    @if ($newInventory->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-100">
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700">قطعة الغيار</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700">الكود</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700">المتوفر</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700">الإجمالي</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700">سعر الوحدة</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700">القيمة</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700">الحالة</th>
                                        <th class="text-right py-4 px-4 font-semibold text-gray-700">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($newInventory as $item)
                                        <tr class="hover:bg-gray-50 transition-colors group">
                                            <!-- Spare Part Info -->
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-12 h-12 bg-gradient-to-br from-blue-100 to-green-100 rounded-xl flex items-center justify-center">
                                                        <i class="ri-tools-line text-blue-600"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900">{{ $item->sparePart->name }}
                                                        </p>
                                                        @if ($item->sparePart->description)
                                                            <p class="text-sm text-gray-600">
                                                                {{ Str::limit($item->sparePart->description, 40) }}</p>
                                                        @endif
                                                        @if ($item->sparePart->brand)
                                                            <span
                                                                class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mt-1">{{ $item->sparePart->brand }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Code -->
                                            <td class="py-4 px-4">
                                                <span
                                                    class="bg-gray-100 text-gray-800 px-3 py-2 rounded-lg font-mono text-sm">{{ $item->sparePart->code }}</span>
                                            </td>

                                            <!-- Available Stock -->
                                            <td class="py-4 px-4">
                                                @php $availableStock = $item->available_stock ?? $item->current_stock; @endphp
                                                <span
                                                    class="text-lg font-bold {{ $availableStock > 10 ? 'text-green-600' : ($availableStock > 0 ? 'text-orange-600' : 'text-red-600') }}">
                                                    {{ $availableStock }}
                                                </span>
                                            </td>

                                            <!-- Total Stock -->
                                            <td class="py-4 px-4">
                                                <span class="text-gray-900 font-semibold">{{ $item->current_stock }}</span>
                                            </td>

                                            <!-- Unit Price -->
                                            <td class="py-4 px-4">
                                                <span
                                                    class="text-gray-900">{{ number_format($item->average_cost ?? $item->sparePart->unit_price, 2) }}
                                                    ر.س</span>
                                            </td>

                                            <!-- Total Value -->
                                            <td class="py-4 px-4">
                                                <span
                                                    class="font-bold text-gray-900">{{ number_format($item->total_value, 2) }}
                                                    ر.س</span>
                                            </td>

                                            <!-- Status -->
                                            <td class="py-4 px-4">
                                                @if ($availableStock > 10)
                                                    <span
                                                        class="inline-flex items-center gap-1 bg-green-100 text-green-800 px-3 py-2 rounded-full text-sm font-semibold">
                                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                                        متوفر
                                                    </span>
                                                @elseif($availableStock > 0)
                                                    <span
                                                        class="inline-flex items-center gap-1 bg-orange-100 text-orange-800 px-3 py-2 rounded-full text-sm font-semibold">
                                                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                                        منخفض
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1 bg-red-100 text-red-800 px-3 py-2 rounded-full text-sm font-semibold">
                                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                                        نفد
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Actions -->
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-2 justify-center">
                                                    <!-- View Details -->
                                                    <button type="button"
                                                        onclick="showSparePartDetails({{ $item->sparePart->id }})"
                                                        class="action-btn w-9 h-9 bg-purple-100 hover:bg-purple-200 text-purple-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm hover:shadow-md"
                                                        title="عرض التفاصيل">
                                                        <i class="ri-information-line text-sm"></i>
                                                    </button>

                                                    <!-- Export -->
                                                    <button type="button"
                                                        onclick="openExportModal({{ $item->sparePart->id }})"
                                                        class="action-btn w-9 h-9 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm hover:shadow-md"
                                                        title="تصدير">
                                                        <i class="ri-download-line text-sm"></i>
                                                    </button>

                                                    <!-- Receive More -->
                                                    <button type="button"
                                                        onclick="openReceiveModalWithData('new', {{ $item->sparePart->id }}, '{{ addslashes($item->sparePart->name) }}')"
                                                        class="action-btn w-9 h-9 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-sm hover:shadow-md"
                                                        title="استلام كمية إضافية">
                                                        <i class="ri-add-line text-sm"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div
                                class="w-32 h-32 bg-gradient-to-br from-blue-100 to-green-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <i class="ri-tools-line text-5xl text-blue-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">لا توجد قطع غيار</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">لم يتم استلام أي قطع غيار في هذا المستودع بعد.
                                ابدأ بإضافة قطع الغيار الأولى.</p>
                            <button type="button" onclick="openReceiveTypeModal()"
                                class="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-8 py-4 rounded-xl transition-all duration-300 flex items-center gap-3 mx-auto shadow-lg hover:shadow-xl transform hover:scale-105">
                                <i class="ri-add-circle-line text-xl"></i>
                                <span class="font-semibold">استلام قطع غيار</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Damaged Parts Section -->
            @if ($damagedInventory->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mt-8">
                    <div class="bg-gradient-to-r from-red-500 to-orange-500 p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                <i class="ri-error-warning-line text-xl text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold">قطع الغيار التالفة</h2>
                                <p class="text-red-100">{{ $damagedInventory->count() }} قطعة تحتاج لمراجعة</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($damagedInventory as $item)
                                <div
                                    class="bg-red-50 border-2 border-red-200 rounded-xl p-5 hover:shadow-lg transition-shadow">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-tools-line text-red-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $item->sparePart->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $item->sparePart->code }}</p>
                                            </div>
                                        </div>
                                        <span
                                            class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">تالفة</span>
                                    </div>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">الكمية:</span>
                                            <span class="font-semibold text-gray-900">{{ $item->current_stock }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">القيمة:</span>
                                            <span
                                                class="font-semibold text-gray-900">{{ number_format($item->total_value, 2) }}
                                                ر.س</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Spare Part Details Modal -->
    <div id="sparePartDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                <div class="bg-gradient-to-r from-blue-600 to-green-600 p-6 text-white rounded-t-2xl">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold" id="modalTitle">تفاصيل قطعة الغيار</h3>
                        <button onclick="closeSparePartDetailsModal()"
                            class="w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg flex items-center justify-center transition-colors">
                            <i class="ri-close-line text-xl text-white"></i>
                        </button>
                    </div>
                </div>
                <div class="p-8" id="modalContent">
                    <div class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        <span class="mr-4 text-gray-600 text-lg">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // تعريف جميع الدوال في البداية
        function showSparePartDetails(sparePartId) {
            console.log('تم استدعاء showSparePartDetails مع معرف:', sparePartId);
            if (!sparePartId) {
                showErrorModal('خطأ في البيانات', 'معرف قطعة الغيار غير صالح');
                return;
            }

            const modal = document.getElementById('sparePartDetailsModal');
            const modalContent = document.getElementById('modalContent');
            const modalTitle = document.getElementById('modalTitle');

            if (!modal || !modalContent || !modalTitle) {
                showErrorModal('خطأ في النظام', 'حدث خطأ في عرض النافذة المنبثقة');
                return;
            }

            modal.classList.remove('hidden');
            modalContent.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <span class="mr-4 text-gray-600 text-lg">جاري التحميل...</span>
        </div>
    `;

            const url = \`{{ route('warehouses.spare-part-details', ['warehouse' => $warehouse->id, 'sparePart' => ':sparePartId']) }}\`.replace(':sparePartId', sparePartId);
                
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(\`HTTP \${response.status}: \${response.statusText}\`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.sparePart) {
                            modalTitle.textContent = \`تفاصيل \${data.sparePart.name || 'قطعة الغيار'}\`;
                            modalContent.innerHTML = createSparePartDetailsContent(data);
                        } else {
                            throw new Error('بيانات قطعة الغيار غير صالحة');
                        }
                    })
                    .catch(error => {
                        console.error('خطأ في تحميل تفاصيل قطعة الغيار:', error);
                        modalContent.innerHTML = \`
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="ri-error-warning-line text-2xl text-red-600"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">خطأ في تحميل البيانات</h4>
                    <p class="text-gray-600 mb-4">\${error.message || 'حدث خطأ غير متوقع'}</p>
                    <button onclick="closeSparePartDetailsModal()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        إغلاق
                    </button>
                </div>
            \`;
                    });
            }

            function openExportModal(sparePartId) {
                console.log('تم استدعاء openExportModal مع معرف:', sparePartId);
                showDevelopmentModal('تصدير قطع غيار', 'سيتم تطوير وظيفة التصدير قريباً.' + (sparePartId ? ' معرف القطعة: ' + sparePartId : ' لجميع القطع'));
            }

            function openReceiveModalWithData(type, sparePartId, sparePartName) {
                console.log('تم استدعاء openReceiveModalWithData:', type, sparePartId, sparePartName);
                showDevelopmentModal('استلام قطع غيار', 'سيتم تطوير وظيفة استلام كمية إضافية قريباً. القطعة: ' + sparePartName);
            }

            function closeSparePartDetailsModal() {
                document.getElementById('sparePartDetailsModal').classList.add('hidden');
            }

            // تعيين الدوال كمتغيرات عالمية
            window.showSparePartDetails = showSparePartDetails;
            window.openExportModal = openExportModal; 
            window.openReceiveModalWithData = openReceiveModalWithData;
            window.closeSparePartDetailsModal = closeSparePartDetailsModal;

            // تسجيل حالة الدوال للتأكد من التحميل
            console.log('JavaScript Functions Status:');
            console.log('showSparePartDetails loaded:', typeof showSparePartDetails);
            console.log('openExportModal loaded:', typeof openExportModal);
            console.log('openReceiveModalWithData loaded:', typeof openReceiveModalWithData);

            function createSparePartDetailsContent(data) {
                if (!data || !data.sparePart) {
                    return '<div class="text-center py-8"><p class="text-red-600">بيانات غير صالحة</p></div>';
                }

                const sparePart = data.sparePart;
                const inventory = data.inventory;
                const serialNumbers = data.serialNumbers || [];
                const transactions = data.transactions || [];

                // تأمين البيانات من XSS
                const safeName = (sparePart.name || '').replace(/[<>]/g, '');
                const safeCode = (sparePart.code || '').replace(/[<>]/g, '');
                const safeDescription = (sparePart.description || '').replace(/[<>]/g, '');
                const safeBrand = (sparePart.brand || '').replace(/[<>]/g, '');

                return \`
        <div class="space-y-8">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-6 rounded-2xl border border-blue-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="ri-information-line text-blue-600"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">معلومات القطعة</h4>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div><strong>الاسم:</strong> \${safeName}</div>
                        <div><strong>الكود:</strong> <span class="bg-gray-100 px-2 py-1 rounded font-mono">\${safeCode}</span></div>
                        \${safeDescription ? \`<div><strong>الوصف:</strong> \${safeDescription}</div>\` : ''}
                        \${safeBrand ? \`<div><strong>العلامة التجارية:</strong> <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">\${safeBrand}</span></div>\` : ''}
                    </div>
                </div>

                <div class="bg-green-50 p-6 rounded-2xl border border-green-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="ri-database-line text-green-600"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">معلومات المخزون</h4>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div><strong>الكمية المتوفرة:</strong> <span class="text-lg font-bold text-green-600">\${inventory ? (inventory.available_stock ?? inventory.current_stock) : 0}</span></div>
                        <div><strong>الكمية الإجمالية:</strong> \${inventory ? inventory.current_stock : 0}</div>
                        <div><strong>متوسط التكلفة:</strong> \${inventory ? (inventory.average_cost || 0) : 0} ر.س</div>
                        <div><strong>القيمة الإجمالية:</strong> <span class="font-bold">\${inventory ? (inventory.total_value || 0) : 0} ر.س</span></div>
                    </div>
                </div>

                <div class="bg-purple-50 p-6 rounded-2xl border border-purple-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="ri-qr-code-line text-purple-600"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">الأرقام التسلسلية</h4>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div><strong>العدد الإجمالي:</strong> <span class="text-lg font-bold text-purple-600">\${serialNumbers.length}</span></div>
                        <div><strong>المتاحة:</strong> \${serialNumbers.filter(s => s.status === 'available').length}</div>
                        <div><strong>قيد الاستخدام:</strong> \${serialNumbers.filter(s => s.status === 'in_use').length}</div>
                        <div><strong>التالفة:</strong> \${serialNumbers.filter(s => s.status === 'damaged').length}</div>
                    </div>
                </div>
            </div>

            <!-- Serial Numbers Section -->
            \${serialNumbers.length > 0 ? \`
                            <div class="bg-white border-2 border-gray-100 rounded-2xl">
                                <div class="bg-gradient-to-r from-purple-500 to-blue-500 p-6 text-white rounded-t-2xl">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                                <i class="ri-qr-code-line text-white"></i>
                                            </div>
                                            <h4 class="text-xl font-bold">الأرقام التسلسلية (\${serialNumbers.length})</h4>
                                        </div>
                                        <button onclick="printAllSparePartBarcodes(\${JSON.stringify(serialNumbers).replace(/"/g, '&quot;')})" 
                                                class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                            <i class="ri-printer-line"></i>
                                            طباعة جميع الباركودات
                                        </button>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                        \${serialNumbers.map(serial => \`
                                <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-200 hover:border-blue-300 transition-colors">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="text-sm font-bold text-gray-900 font-mono">\${serial.serial_number}</span>
                                        <span class="px-2 py-1 text-xs rounded-full \${getStatusClass(serial.status)}">
                                            \${getStatusText(serial.status)}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-600 mb-4">
                                        <div>الباركود: <span class="font-mono">\${serial.barcode}</span></div>
                                        \${serial.condition_notes ? \`<div class="mt-1 text-orange-600 bg-orange-50 p-1 rounded">\${serial.condition_notes}</div>\` : ''}
                                    </div>
                                    <button onclick="printBarcode('\${serial.serial_number}', '\${serial.barcode}')"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs transition-colors flex items-center justify-center gap-2">
                                        <i class="ri-printer-line"></i>
                                        طباعة الباركود
                                    </button>
                                </div>
                            \`).join('')}
                                    </div>
                                </div>
                            </div>
                        \` : '<div class="text-center py-12 bg-gray-50 rounded-2xl"><div class="w-16 h-16 bg-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4"><i class="ri-qr-code-line text-2xl text-gray-400"></i></div><p class="text-gray-500 text-lg">لا توجد أرقام تسلسلية لهذه القطعة</p></div>'}

            <!-- Recent Transactions -->
            \${transactions.length > 0 ? \`
                            <div class="bg-white border-2 border-gray-100 rounded-2xl">
                                <div class="bg-gradient-to-r from-green-500 to-blue-500 p-6 text-white rounded-t-2xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                            <i class="ri-history-line text-white"></i>
                                        </div>
                                        <h4 class="text-xl font-bold">المعاملات الأخيرة</h4>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-4">
                                        \${transactions.map(transaction => \`
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="ri-exchange-line text-blue-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">\${transaction.transaction_type}</div>
                                            <div class="text-sm text-gray-600">الكمية: \${transaction.quantity}</div>
                                            \${transaction.notes ? \`<div class="text-xs text-gray-500 mt-1">\${transaction.notes}</div>\` : ''}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-gray-900">\${transaction.total_amount} ر.س</div>
                                        <div class="text-xs text-gray-500">\${new Date(transaction.transaction_date).toLocaleDateString('ar-SA')}</div>
                                    </div>
                                </div>
                            \`).join('')}
                                    </div>
                                </div>
                            </div>
                        \` : ''}
        </div>
    \`;
            }

            // Status helper functions
            function getStatusClass(status) {
                switch(status) {
                    case 'available': return 'bg-green-100 text-green-800';
                    case 'in_use': return 'bg-blue-100 text-blue-800';
                    case 'damaged': return 'bg-red-100 text-red-800';
                    case 'disposed': return 'bg-gray-100 text-gray-800';
                    default: return 'bg-gray-100 text-gray-800';
                }
            }

            function getStatusText(status) {
                switch(status) {
                    case 'available': return 'متاح';
                    case 'in_use': return 'قيد الاستخدام';
                    case 'damaged': return 'تالف';
                    case 'disposed': return 'تم التخلص';
                    default: return 'غير محدد';
                }
            }

            // Printing functions
            window.printBarcode = function(serialNumber, barcode) {
                try {
                    const printWindow = window.open('', '_blank', 'width=400,height=300');
                    if (!printWindow) {
                        showErrorModal('تعذر الطباعة', 'يرجى السماح بالنوافذ المنبثقة للطباعة');
                        return;
                    }
                    
                    printWindow.document.write(` <
            !DOCTYPE html >
            <
            html >
            <
            head >
            <
            title > طباعة باركود $ {
                serialNumber
            } < /title> <
        script src = "https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js" > < \/script> <
        style >
            body {
                font - family: 'Tajawal', Arial, sans - serif;
                text - align: center;
                margin: 20 px;
            }
            .barcode - container {
                border: 2 px solid #000; padding: 20px; margin: 20px; background: white; }
                </style>
            </head>
            <body>
                <div class= "barcode-container" >
                    <
                    h3 > شركة الأبراج للمقاولات < /h3> <
                h4 > مستودع {{ $warehouse->name }} < /h4> <
                p > الرقم التسلسلي: $ {
                    serialNumber
                } < /p> <
                p > الباركود: $ {
                    barcode
                } < /p> <
                svg id = "barcode" > < /svg> <
                p > تاريخ الطباعة: $ {
                    new Date().toLocaleDateString('ar-SA')
                } < /p> < /
                div > <
                script >
                window.onload = function() {
                    try {
                        JsBarcode("#barcode", "${barcode}", {
                            format: "CODE128",
                            width: 2,
                            height: 80,
                            displayValue: true
                        });
                        setTimeout(() => {
                            window.print();
                            setTimeout(() => window.close(), 1000);
                        }, 1500);
                    } catch (error) {
                        console.error('خطأ في إنشاء الباركود:', error);
                        showErrorModal('خطأ في الباركود', 'حدث خطأ في إنشاء الباركود');
                    }
                }; < \/script> < /
                body > <
                /html>
                `);
                    printWindow.document.close();
                } catch(error) {
                    console.error('خطأ في فتح نافذة الطباعة:', error);
                    showErrorModal('خطأ في الطباعة', 'حدث خطأ في فتح نافذة الطباعة للقطعة المحددة');
                }
            }

            window.printAllSparePartBarcodes = function(serialNumbers) {
                try {
                    if (!serialNumbers || serialNumbers.length === 0) {
                        showErrorModal('لا توجد بيانات', 'لا توجد أرقام تسلسلية للطباعة');
                        return;
                    }

                    const printWindow = window.open('', '_blank', 'width=800,height=600');
                    if (!printWindow) {
                        showErrorModal('تعذر الطباعة', 'يرجى السماح بالنوافذ المنبثقة للطباعة الجماعية');
                        return;
                    }
                    
                    printWindow.document.write(\`
            <!DOCTYPE html>
            <html>
            <head>
                <title>طباعة جميع الباركودات</title>
                <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
                <style>
                    body { font-family: 'Tajawal', Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; border-bottom: 2px solid #1e40af; padding-bottom: 10px; margin-bottom: 20px; }
                    .barcode-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
                    .barcode-item { border: 1px solid #ddd; padding: 15px; text-align: center; break-inside: avoid; }
                    @media print { .barcode-grid { grid-template-columns: repeat(2, 1fr); } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>شركة الأبراج للمقاولات</h2>
                    <h3>مستودع {{ $warehouse->name }} - الأرقام التسلسلية</h3>
                </div>
                <div class="barcode-grid">
                    \${serialNumbers.map((serial, index) => \`
                                    <div class="barcode-item">
                                        <h4>الرقم التسلسلي: \${serial.serial_number}</h4>
                                        <p>الباركود: \${serial.barcode}</p>
                                        <svg id="barcode\${index}"></svg>
                                        <p style="font-size: 12px;">تاريخ الطباعة: \${new Date().toLocaleDateString('ar-SA')}</p>
                                    </div>
                                \`).join('')}
                </div>
                <script>
                    window.onload = function() {
                        try {
                            \${serialNumbers.map((serial, index) => \`
                                            JsBarcode("#barcode\${index}", "\${serial.barcode}", {
                                                format: "CODE128",
                                                width: 1.5,
                                                height: 60,
                                                displayValue: true
                                            });
                                        \`).join('')}
                            setTimeout(() => {
                                window.print();
                                setTimeout(() => window.close(), 1000);
                            }, 2000);
                        } catch(error) {
                            console.error('خطأ في إنشاء الباركودات:', error);
                            showErrorModal('خطأ في إنشاء الباركودات', 'حدث خطأ أثناء إنشاء الباركودات للطباعة الجماعية');
                        }
                    };
                <\/script>
            </body>
            </html>
        \`);
                    printWindow.document.close();
                } catch(error) {
                    console.error('خطأ في فتح نافذة الطباعة:', error);
                    showErrorModal('خطأ في الطباعة', 'حدث خطأ في فتح نافذة الطباعة الجماعية');
                }
            }

            // Print warehouse report function
            window.printWarehouseReport = function() {
                window.print();
            }

            // Filter functionality
            document.addEventListener('DOMContentLoaded', function() {
                // التأكد من تحميل جميع الدوال بنجاح
                console.log('تم تحميل الصفحة - التحقق من الدوال:');
                console.log('showSparePartDetails:', typeof window.showSparePartDetails);
                console.log('openExportModal:', typeof window.openExportModal);
                console.log('openReceiveModalWithData:', typeof window.openReceiveModalWithData);
                console.log('openReceiveTypeModal:', typeof window.openReceiveTypeModal);

                const filterSelect = document.getElementById('filterStatus');
                if (filterSelect) {
                    filterSelect.addEventListener('change', function() {
                        const status = this.value;
                        const rows = document.querySelectorAll('tbody tr');
                        
                        rows.forEach(row => {
                            if (status === 'all') {
                                row.style.display = '';
                            } else {
                                const statusElement = row.querySelector('.inline-flex');
                                if (statusElement) {
                                    const statusText = statusElement.textContent.trim();
                                    let showRow = false;
                                    
                                    switch(status) {
                                        case 'available':
                                            showRow = statusText.includes('متوفر');
                                            break;
                                        case 'low':
                                            showRow = statusText.includes('منخفض');
                                            break;
                                        case 'out':
                                            showRow = statusText.includes('نفد');
                                            break;
                                    }
                                    
                                    row.style.display = showRow ? '' : 'none';
                                }
                            }
                        });
                    });
                }

                // إزالة أي حدث طباعة غير مرغوب فيه عند تحميل الصفحة
                window.addEventListener('beforeprint', function(e) {
                    if (!e.target.printRequested) {
                        // منع الطباعة التلقائية
                        return;
                    }
                });

                // إضافة معالج إغلاق المودال عند النقر خارجه
                const modal = document.getElementById('sparePartDetailsModal');
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            closeSparePartDetailsModal();
                        }
                    });
                }

                // إضافة معالج مفتاح Escape لإغلاق المودال
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        const modal = document.getElementById('sparePartDetailsModal');
                        if (modal && !modal.classList.contains('hidden')) {
                            closeSparePartDetailsModal();
                        }
                    }
                });

                // إضافة معالج النقر على مستوى الجدول للتأكد من عمل الأزرار
                document.addEventListener('click', function(e) {
                    if (e.target.closest('button[onclick]')) {
                        console.log('تم النقر على زر:', e.target.closest('button'));
                        console.log('محتوى onclick:', e.target.closest('button').getAttribute('onclick'));
                    }
                });

                // معالج بديل للأزرار في حالة فشل onclick
                document.addEventListener('click', function(e) {
                    const button = e.target.closest('.action-btn');
                    if (button) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // استخراج معرف القطعة من البيانات
                        const sparePartId = button.getAttribute('data-spare-part-id');
                        const sparePartName = button.getAttribute('data-spare-part-name');
                        
                        if (button.title === 'عرض التفاصيل') {
                            console.log('معالج بديل: عرض التفاصيل', sparePartId);
                            window.showSparePartDetails(sparePartId);
                        } else if (button.title === 'تصدير') {
                            console.log('معالج بديل: تصدير', sparePartId);
                            window.openExportModal(sparePartId);
                        } else if (button.title === 'استلام كمية إضافية') {
                            console.log('معالج بديل: استلام', sparePartId, sparePartName);
                            window.openReceiveModalWithData('new', sparePartId, sparePartName);
                        }
                    }
                });
            });
    </script>

@endsection
