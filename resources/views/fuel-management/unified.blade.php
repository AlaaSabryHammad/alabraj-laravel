@extends('layouts.app')

@section('title', 'إدارة المحروقات الموحدة')

@section('content')
    <div class="space-y-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">إدارة المحروقات</h1>
                <p class="mt-1 text-sm text-gray-600">إدارة شاملة لسيارات المحروقات والتوزيعات والاستهلاك</p>
            </div>
            <a href="{{ route('fuel-management.consumption-report') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                <i class="ri-bar-chart-line ml-2"></i>
                تقرير الاستهلاك
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">إجمالي السيارات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $summary['total_trucks'] }}</p>
                    </div>
                    <i class="ri-truck-line text-4xl text-blue-500 opacity-20"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">السعة الكلية</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_capacity'], 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">لتر</p>
                    </div>
                    <i class="ri-database-line text-4xl text-purple-500 opacity-20"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">الكمية الحالية</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_current'], 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">لتر</p>
                    </div>
                    <i class="ri-gas-station-fill text-4xl text-green-500 opacity-20"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">الكمية المتبقية</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($summary['total_remaining'], 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($summary['utilization_percentage'], 1) }}%</p>
                    </div>
                    <i class="ri-drop-line text-4xl text-blue-500 opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Fuel Trucks Grid -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">سيارات المحروقات</h2>
            </div>

            <div class="p-6">
                @if($fuelTrucks->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($fuelTrucks as $truck)
                            <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 hover:shadow-lg transition-all duration-200"
                                 onclick="openTruckModal({{ $truck->id }}, '{{ $truck->name }}')">

                                <!-- Truck Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $truck->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $truck->type }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $truck->fuelTruck->fuel_type_text }}
                                    </span>
                                </div>

                                <!-- Truck Info -->
                                <div class="space-y-3 mb-4 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">الموقع:</span>
                                        <span class="font-medium">{{ $truck->location->name ?? 'غير محدد' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">السائق:</span>
                                        <span class="font-medium">{{ $truck->driver->name ?? 'غير معين' }}</span>
                                    </div>
                                </div>

                                <!-- Fuel Level Info -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">الكمية المتاحة:</span>
                                        <span class="font-bold text-green-600">
                                            {{ number_format($truck->fuelTruck->remaining_quantity, 2) }} لتر
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600">الكمية الكلية:</span>
                                        <span class="font-medium">{{ number_format($truck->fuelTruck->current_quantity, 2) }} لتر</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">السعة:</span>
                                        <span class="font-medium">{{ number_format($truck->fuelTruck->capacity, 2) }} لتر</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                @php
                                    $percentage = $truck->fuelTruck->capacity > 0
                                        ? ($truck->fuelTruck->remaining_quantity / $truck->fuelTruck->capacity) * 100
                                        : 0;
                                @endphp
                                <div class="mb-4">
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-300"
                                            style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 text-center">{{ number_format($percentage, 1) }}% متاح</p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button onclick="event.stopPropagation(); openDistributeModal({{ $truck->fuelTruck->id }}, '{{ $truck->name }}')"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                        {{ $truck->fuelTruck->remaining_quantity <= 0 ? 'disabled' : '' }}>
                                        <i class="ri-gas-station-line ml-1"></i>
                                        توزيع
                                    </button>
                                    <button onclick="event.stopPropagation(); openConsumptionModal()"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <i class="ri-bar-chart-line ml-1"></i>
                                        استهلاك
                                    </button>
                                    <button onclick="event.stopPropagation(); openTruckModal({{ $truck->id }}, '{{ $truck->name }}')"
                                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <i class="ri-eye-line ml-1"></i>
                                        التفاصيل
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="ri-truck-line text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد سيارات محروقات</h3>
                        <p class="text-gray-500">لم يتم إضافة أي سيارات محروقات حتى الآن</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Truck Details Modal -->
    <div id="truckDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white" dir="rtl">
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">تفاصيل السيارة</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600"
                    onclick="closeTruckModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div id="truckDetailsContent" class="mt-4 space-y-4">
                <div class="text-center">
                    <i class="ri-loader-4-line text-2xl animate-spin"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribute Fuel Modal -->
    <div id="distributeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 shadow-lg rounded-lg bg-white" dir="rtl">
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">توزيع المحروقات</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600"
                    onclick="closeDistributeModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="distributeForm" class="mt-4 space-y-4">
                @csrf
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center text-blue-800 mb-2">
                        <i class="ri-truck-line ml-2"></i>
                        <span class="font-medium">معلومات سيارة المحروقات</span>
                    </div>
                    <div id="truckInfo" class="text-sm text-blue-700"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المعدة المستهدفة</label>
                        <select name="target_equipment_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">اختر المعدة</option>
                            @foreach($targetEquipments as $equipment)
                                <option value="{{ $equipment->id }}">
                                    {{ $equipment->name }}
                                    {{ $equipment->location ? '- ' . $equipment->location->name : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الكمية (لتر)</label>
                        <input type="number" name="quantity" step="0.01" min="0.1" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="أدخل الكمية">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ التوزيع</label>
                    <input type="date" name="distribution_date" required value="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات (اختيارية)</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="أضف أي ملاحظات..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeDistributeModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center">
                        <i class="ri-gas-station-line ml-1"></i>
                        توزيع المحروقات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Consumption Modal -->
    <div id="consumptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 shadow-lg rounded-lg bg-white" dir="rtl">
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">تسجيل استهلاك المحروقات</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600"
                    onclick="closeConsumptionModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="consumptionForm" class="mt-4 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المعدة</label>
                        <select name="equipment_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">اختر المعدة</option>
                            @foreach($targetEquipments as $equipment)
                                <option value="{{ $equipment->id }}">
                                    {{ $equipment->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع المحروقات</label>
                        <select name="fuel_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">اختر النوع</option>
                            <option value="diesel">ديزل</option>
                            <option value="gasoline">بنزين</option>
                            <option value="engine_oil">زيت ماكينة</option>
                            <option value="hydraulic_oil">زيت هيدروليك</option>
                            <option value="radiator_water">ماء ردياتير</option>
                            <option value="brake_oil">زيت فرامل</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الكمية (لتر)</label>
                        <input type="number" name="quantity" step="0.01" min="0.1" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                            placeholder="أدخل الكمية">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الاستهلاك</label>
                        <input type="date" name="consumption_date" required value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات (اختيارية)</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                        placeholder="أضف أي ملاحظات..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeConsumptionModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center">
                        <i class="ri-check-line ml-1"></i>
                        تسجيل الاستهلاك
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentFuelTruckId = null;
        let availableQuantity = 0;

        function openTruckModal(truckId, truckName) {
            fetch('/fuel-management/truck/' + truckId + '/details')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load truck details: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        document.getElementById('truckDetailsContent').innerHTML = '<p class="text-red-600">خطأ: ' + data.error + '</p>';
                        return;
                    }

                    const truck = data.truck;
                    const distributions = data.distributions;

                    let html = '<div class="space-y-4">';
                    html += '<div class="grid grid-cols-2 gap-4">';
                    html += '<div><p class="text-sm text-gray-600">الاسم</p><p class="text-lg font-bold">' + truck.name + '</p></div>';
                    html += '<div><p class="text-sm text-gray-600">نوع المحروقات</p><p class="text-lg font-bold">' + truck.fuel_type + '</p></div>';
                    html += '</div>';

                    html += '<div class="grid grid-cols-3 gap-4 p-4 bg-blue-50 rounded-lg">';
                    html += '<div><p class="text-xs text-gray-600">السعة</p><p class="font-bold text-blue-900">' + parseFloat(truck.capacity).toFixed(2) + '</p><p class="text-xs text-gray-500">لتر</p></div>';
                    html += '<div><p class="text-xs text-gray-600">الحالية</p><p class="font-bold text-blue-900">' + parseFloat(truck.current_quantity).toFixed(2) + '</p><p class="text-xs text-gray-500">لتر</p></div>';
                    html += '<div><p class="text-xs text-gray-600">المتبقية</p><p class="font-bold text-green-600">' + parseFloat(truck.remaining_quantity).toFixed(2) + '</p><p class="text-xs text-gray-500">لتر</p></div>';
                    html += '</div>';

                    html += '<div><div class="w-full bg-gray-200 rounded-full h-3 mb-2"><div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full" style="width: ' + truck.percentage + '%"></div></div>';
                    html += '<p class="text-xs text-center text-gray-600">' + parseFloat(truck.percentage).toFixed(1) + '% متاح</p></div>';

                    html += '<div><h4 class="font-bold text-gray-900 mb-3">التوزيعات الأخيرة</h4>';
                    if (distributions.length > 0) {
                        html += '<div class="space-y-2 max-h-64 overflow-y-auto">';
                        distributions.forEach(dist => {
                            html += '<div class="flex items-center justify-between p-2 bg-gray-50 rounded">';
                            html += '<div><p class="text-sm font-medium">' + dist.equipment_name + '</p><p class="text-xs text-gray-500">' + dist.date_formatted + '</p></div>';
                            html += '<div class="text-right"><p class="text-sm font-bold">' + parseFloat(dist.quantity).toFixed(2) + ' لتر</p>';
                            html += '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ' + dist.status_color + '">' + dist.status_text + '</span></div>';
                            html += '</div>';
                        });
                        html += '</div>';
                    } else {
                        html += '<p class="text-sm text-gray-500 text-center py-4">لا توجد توزيعات</p>';
                    }
                    html += '</div></div>';

                    document.getElementById('truckDetailsContent').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('truckDetailsContent').innerHTML = '<p class="text-red-600">حدث خطأ في تحميل البيانات: ' + error.message + '</p>';
                });

            document.getElementById('truckDetailsModal').classList.remove('hidden');
        }

        function closeTruckModal() {
            document.getElementById('truckDetailsModal').classList.add('hidden');
        }

        function openDistributeModal(fuelTruckId, truckName) {
            currentFuelTruckId = fuelTruckId;
            fetch('/fuel-management/truck/' + fuelTruckId + '/details')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load truck details');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        showNotification(data.error, 'error');
                        return;
                    }
                    availableQuantity = parseFloat(data.truck.remaining_quantity);
                    let info = '<div><strong>اسم السيارة:</strong> ' + data.truck.name + '</div>';
                    info += '<div><strong>نوع المحروقات:</strong> ' + data.truck.fuel_type + '</div>';
                    info += '<div><strong>الكمية المتاحة:</strong> ' + parseFloat(data.truck.remaining_quantity).toFixed(2) + ' لتر</div>';
                    document.getElementById('truckInfo').innerHTML = info;
                    document.querySelector('[name="quantity"]').max = availableQuantity;
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('خطأ في تحميل بيانات السيارة', 'error');
                });
            document.getElementById('distributeModal').classList.remove('hidden');
        }

        function closeDistributeModal() {
            document.getElementById('distributeModal').classList.add('hidden');
            document.getElementById('distributeForm').reset();
            currentFuelTruckId = null;
        }

        function openConsumptionModal() {
            document.getElementById('consumptionModal').classList.remove('hidden');
        }

        function closeConsumptionModal() {
            document.getElementById('consumptionModal').classList.add('hidden');
            document.getElementById('consumptionForm').reset();
        }

        // Handle distribute form submission
        document.getElementById('distributeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!currentFuelTruckId) return;

            const formData = new FormData(this);
            const quantity = parseFloat(formData.get('quantity'));

            if (quantity > availableQuantity) {
                showNotification('الكمية المطلوبة أكبر من الكمية المتاحة', 'error');
                return;
            }

            fetch('/fuel-management/fuel-truck/' + currentFuelTruckId + '/distribute', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDistributeModal();
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'حدث خطأ', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('حدث خطأ', 'error');
            });
        });

        // Handle consumption form submission
        document.getElementById('consumptionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/equipment-fuel-consumption', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || !data.error) {
                    closeConsumptionModal();
                    showNotification('تم تسجيل الاستهلاك بنجاح', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'حدث خطأ', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('حدث خطأ', 'error');
            });
        });

        // Notification function
        function showNotification(message, type) {
            if (!type) type = 'info';
            const notification = document.createElement('div');
            let bgColor = 'bg-blue-500';
            let icon = 'information';
            if (type === 'success') { bgColor = 'bg-green-500'; icon = 'check'; }
            if (type === 'error') { bgColor = 'bg-red-500'; icon = 'error-warning'; }

            notification.className = 'fixed top-4 left-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ' + bgColor + ' text-white';
            notification.innerHTML = '<div class="flex items-center"><i class="ri-' + icon + '-line ml-2"></i><span>' + message + '</span></div>';
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }

        // Close modals when clicking outside
        document.getElementById('truckDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) closeTruckModal();
        });
        document.getElementById('distributeModal').addEventListener('click', function(e) {
            if (e.target === this) closeDistributeModal();
        });
        document.getElementById('consumptionModal').addEventListener('click', function(e) {
            if (e.target === this) closeConsumptionModal();
        });

        // Close modals on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTruckModal();
                closeDistributeModal();
                closeConsumptionModal();
            }
        });
    </script>
@endsection
