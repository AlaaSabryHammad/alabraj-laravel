@extends('layouts.app')

@section('title', 'توزيع المحروقات - السائق')

@section('content')
    <div class="space-y-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">توزيع المحروقات</h1>
                <p class="mt-1 text-sm text-gray-600">إدارة توزيع المحروقات على المعدات</p>
            </div>
        </div>

        @if ($driverFuelTrucks->count() > 0)
            <!-- Driver's Fuel Trucks -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">سيارات المحروقات المعينة لك</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach ($driverFuelTrucks as $truck)
                            <div class="border rounded-lg p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $truck->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $truck->type }}</p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $truck->fuelTruck->fuel_type_text }}
                                    </span>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">الكمية المتاحة:</span>
                                        <span
                                            class="font-medium text-green-600">{{ number_format($truck->fuelTruck->remaining_quantity, 2) }}
                                            لتر</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">الكمية الكلية:</span>
                                        <span
                                            class="font-medium">{{ number_format($truck->fuelTruck->current_quantity, 2) }}
                                            لتر</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">السعة:</span>
                                        <span class="font-medium">{{ number_format($truck->fuelTruck->capacity, 2) }}
                                            لتر</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                @php
                                    $percentage =
                                        $truck->fuelTruck->capacity > 0
                                            ? ($truck->fuelTruck->remaining_quantity / $truck->fuelTruck->capacity) *
                                                100
                                            : 0;
                                @endphp
                                <div class="mb-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}% متاح</p>
                                </div>

                                <div class="flex gap-2">
                                    <button
                                        onclick="openDistributeModal({{ $truck->fuelTruck->id }}, '{{ $truck->name }}', '{{ $truck->fuelTruck->fuel_type_text }}', {{ $truck->fuelTruck->remaining_quantity }})"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center"
                                        {{ $truck->fuelTruck->remaining_quantity <= 0 ? 'disabled' : '' }}>
                                        <i class="ri-gas-station-line ml-1"></i>
                                        توزيع المحروقات
                                    </button>
                                    <button onclick="viewTruckDistributions({{ $truck->fuelTruck->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Filled Equipment Table -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">المعدات المعبأة من سيارة المحروقات</h3>
                    <p class="mt-1 text-sm text-gray-600">جميع التوزيعات التي تم إجراؤها من شاحنة المحروقات الخاصة بك</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المعدة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية المعبأة (لتر)</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ التوزيع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الموافقة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="filledEquipmentBody">
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="ri-loader-4-line text-2xl animate-spin mb-2"></i>
                                    <p>جاري تحميل البيانات...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <i class="ri-truck-line text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد سيارات محروقات معينة</h3>
                <p class="text-gray-500">لم يتم تعيين أي سيارة محروقات لك</p>
            </div>
        @endif
    </div>

    <!-- Distribute Fuel Modal -->
    <div id="distributeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-2/3 shadow-lg rounded-lg bg-white"
            dir="rtl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">توزيع المحروقات</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="closeDistributeModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="distributeForm" class="mt-4">
                @csrf
                <div class="space-y-4">
                    <!-- Fuel Truck Info -->
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center text-blue-800 mb-2">
                            <i class="ri-truck-line ml-2"></i>
                            <span class="font-medium">معلومات سيارة المحروقات</span>
                        </div>
                        <div id="truckInfo" class="text-sm text-blue-700">
                            <!-- سيتم ملء هذا القسم بالـ JavaScript -->
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Target Equipment -->
                        <div>
                            <label for="targetEquipment" class="block text-sm font-medium text-gray-700 mb-2">المعدة
                                المستهدفة</label>
                            <select id="targetEquipment" name="target_equipment_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">اختر المعدة</option>
                                @foreach ($targetEquipments as $equipment)
                                    <option value="{{ $equipment->id }}">
                                        {{ $equipment->name }}
                                        @if ($equipment->location)
                                            - {{ $equipment->location->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">الكمية (لتر)</label>
                            <input type="number" id="quantity" name="quantity" step="0.01" min="0.1" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="أدخل الكمية">
                        </div>
                    </div>

                    <!-- Distribution Date -->
                    <div>
                        <label for="distributionDate" class="block text-sm font-medium text-gray-700 mb-2">تاريخ
                            التوزيع</label>
                        <input type="date" id="distributionDate" name="distribution_date" required
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="distributionNotes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات
                            (اختيارية)</label>
                        <textarea id="distributionNotes" name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="أضف أي ملاحظات حول التوزيع..."></textarea>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t mt-6">
                    <button type="button" onclick="closeDistributeModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center">
                        <i class="ri-gas-station-line ml-1"></i>
                        توزيع المحروقات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentFuelTruckId = null;
        let availableQuantity = 0;

        function openDistributeModal(fuelTruckId, truckName, fuelType, remainingQuantity) {
            currentFuelTruckId = fuelTruckId;
            availableQuantity = remainingQuantity;

            // Fill truck info
            document.getElementById('truckInfo').innerHTML = `
        <div><strong>اسم السيارة:</strong> ${truckName}</div>
        <div><strong>نوع المحروقات:</strong> ${fuelType}</div>
        <div><strong>الكمية المتاحة:</strong> ${remainingQuantity.toFixed(2)} لتر</div>
    `;

            // Set max quantity
            document.getElementById('quantity').max = remainingQuantity;

            // Show modal
            document.getElementById('distributeModal').classList.remove('hidden');
        }

        function closeDistributeModal() {
            document.getElementById('distributeModal').classList.add('hidden');
            document.getElementById('distributeForm').reset();
            currentFuelTruckId = null;
            availableQuantity = 0;
        }

        // Handle distribute form submission
        document.getElementById('distributeForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentFuelTruckId) return;

            const formData = new FormData(this);
            const quantity = parseFloat(formData.get('quantity'));

            // Check quantity
            if (quantity > availableQuantity) {
                showNotification('الكمية المطلوبة أكبر من الكمية المتاحة', 'error');
                return;
            }

            fetch(`/fuel-management/fuel-truck/${currentFuelTruckId}/distribute`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeDistributeModal();
                        showNotification(data.message, 'success');
                        // Reload distributions table
                        setTimeout(() => {
                            loadAllDistributions();
                        }, 500);
                    } else {
                        showNotification(data.message || 'حدث خطأ', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('حدث خطأ', 'error');
                });
        });

        function viewTruckDistributions(fuelTruckId) {
            // Redirect to main fuel management page with truck selected
            window.location.href = `/fuel-management?truck=${fuelTruckId}`;
        }

        // Load all fuel distributions from driver's fuel trucks
        function loadAllDistributions() {
            @if ($driverFuelTrucks->count() > 0)
                // Get all fuel truck IDs from the page
                const fuelTruckIds = [{{ implode(',', $driverFuelTrucks->pluck('fuelTruck.id')->toArray()) }}];

                Promise.all(fuelTruckIds.map(id =>
                    fetch(`/fuel-management/fuel-truck/${id}/distributions`)
                        .then(response => response.json())
                ))
                .then(results => {
                    // Flatten all distributions
                    const allDistributions = results.flat();
                    renderDistributionsTable(allDistributions);
                })
                .catch(error => {
                    console.error('Error loading distributions:', error);
                    showNotification('خطأ في تحميل التوزيعات', 'error');
                });
            @endif
        }

        // Render distributions in table
        function renderDistributionsTable(distributions) {
            const tbody = document.getElementById('filledEquipmentBody');

            if (!distributions || distributions.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="ri-inbox-line text-4xl mb-2"></i>
                            <p>لا توجد توزيعات محروقات حتى الآن</p>
                        </td>
                    </tr>
                `;
                return;
            }

            const rows = distributions.map(dist => {
                let statusClass = 'bg-yellow-100 text-yellow-800';
                if (dist.approval_status === 'approved') {
                    statusClass = 'bg-green-100 text-green-800';
                } else if (dist.approval_status === 'rejected') {
                    statusClass = 'bg-red-100 text-red-800';
                }

                return `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-medium">${dist.target_equipment.name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${parseFloat(dist.quantity).toFixed(2)} لتر
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            ${dist.distribution_date_formatted || dist.distribution_date}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                ${dist.approval_status_text}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            ${dist.notes ? `<span title="${dist.notes}" class="truncate block max-w-xs">${dist.notes}</span>` : '-'}
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = rows.join('');
        }

        // Load distributions when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadAllDistributions();
        });

        // Close modal when clicking outside
        document.getElementById('distributeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDistributeModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('distributeModal').classList.contains('hidden')) {
                closeDistributeModal();
            }
        });

        // Notification function
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 left-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
            notification.innerHTML = `
        <div class="flex items-center">
            <i class="ri-${type === 'success' ? 'check' : type === 'error' ? 'error-warning' : 'information'}-line ml-2"></i>
            <span>${message}</span>
        </div>
    `;

            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
@endsection
