@extends('layouts.app')

@section('title', 'إدارة المحروقات')

@section('content')
    <div class="space-y-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إدارة المحروقات</h1>
                <p class="mt-1 text-sm text-gray-600">إدارة سيارات دعم المحروقات وتوزيع الوقود</p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-truck-line text-2xl text-blue-600"></i>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">عدد التانكرات</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $summary['total_trucks'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-gas-station-line text-2xl text-green-600"></i>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">السعة الإجمالية</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ number_format($summary['total_capacity'], 0) }} لتر</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-drop-line text-2xl text-blue-600"></i>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">الكمية الحالية</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ number_format($summary['total_current'], 0) }} لتر</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-battery-2-line text-2xl text-orange-600"></i>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">الكمية المتاحة للتوزيع</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ number_format($summary['total_remaining'], 0) }} لتر</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fuel Trucks Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($fuelTrucksEquipment as $equipment)
                <div class="bg-white overflow-hidden shadow rounded-lg border">
                    <div class="p-6">
                        <!-- Equipment Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-truck-line text-2xl text-blue-600"></i>
                                </div>
                                <div class="mr-3">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $equipment->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $equipment->type }}</p>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $equipment->status === 'available'
                                ? 'bg-green-100 text-green-800'
                                : ($equipment->status === 'in_use'
                                    ? 'bg-blue-100 text-blue-800'
                                    : ($equipment->status === 'maintenance'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-red-100 text-red-800')) }}">
                                {{ $equipment->status === 'available'
                                    ? 'متاح'
                                    : ($equipment->status === 'in_use'
                                        ? 'قيد الاستخدام'
                                        : ($equipment->status === 'maintenance'
                                            ? 'تحت الصيانة'
                                            : 'خارج الخدمة')) }}
                            </span>
                        </div>

                        <!-- Equipment Details -->
                        <div class="space-y-2 mb-4">
                            @if ($equipment->driver)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="ri-user-line ml-2"></i>
                                    <span>السائق: {{ $equipment->driver->name }}</span>
                                </div>
                            @endif
                            @if ($equipment->location)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="ri-map-pin-line ml-2"></i>
                                    <span>الموقع: {{ $equipment->location->name }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Fuel Information -->
                        @if ($equipment->fuelTruck)
                            <div class="border-t pt-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">معلومات المحروقات</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $equipment->fuelTruck->fuel_type_text }}
                                    </span>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">السعة الكلية:</span>
                                        <span class="font-medium">{{ number_format($equipment->fuelTruck->capacity, 2) }}
                                            لتر</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">الكمية الحالية:</span>
                                        <span
                                            class="font-medium text-green-600">{{ number_format($equipment->fuelTruck->current_quantity, 2) }}
                                            لتر</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">المتاح للتوزيع:</span>
                                        <span
                                            class="font-medium text-blue-600">{{ number_format($equipment->fuelTruck->remaining_quantity, 2) }}
                                            لتر</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    @php
                                        $percentage =
                                            $equipment->fuelTruck->capacity > 0
                                                ? ($equipment->fuelTruck->remaining_quantity /
                                                        $equipment->fuelTruck->capacity) *
                                                    100
                                                : 0;
                                    @endphp
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}% متاح</p>
                                </div>
                            </div>
                        @else
                            <div class="border-t pt-4">
                                <p class="text-sm text-gray-500 text-center py-4">لم يتم تحديد معلومات المحروقات</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="space-y-2 mt-4">
                            <!-- Add Fuel Quantity Button -->
                            @if ($equipment->fuelTruck)
                                <button onclick="openAddFuelQuantityModal({{ $equipment->id }}, {{ $equipment->fuelTruck->id }}, {{ $equipment->fuelTruck->current_quantity }})"
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                    <i class="ri-add-circle-line ml-2"></i>
                                    <span>إضافة كمية محروقات</span>
                                    <span class="mr-2 text-xs opacity-90">(الحالية: {{ number_format($equipment->fuelTruck->current_quantity, 2) }} لتر)</span>
                                </button>
                            @endif

                            <div class="flex gap-2">
                                <button onclick="openAddFuelModal({{ $equipment->id }})"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                    <i class="ri-gas-station-line ml-1"></i>
                                    {{ $equipment->fuelTruck ? 'تحديث المحروقات' : 'إضافة محروقات' }}
                                </button>
                                @if ($equipment->fuelTruck)
                                    <button onclick="viewDistributions({{ $equipment->fuelTruck->id }})"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                        <i class="ri-eye-line ml-1"></i>
                                        عرض التوزيعات
                                    </button>
                                @endif
                            </div>

                            <!-- Driver Distribution Button -->
                            @if ($equipment->fuelTruck && Auth::user()->employee && $equipment->driver_id === Auth::user()->employee->id)
                                <a href="{{ route('fuel-management.driver') }}"
                                    class="block w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                    <i class="ri-share-line ml-1"></i>
                                    صفحة التوزيع (السائق)
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <i class="ri-truck-line text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد سيارات محروقات</h3>
                        <p class="text-gray-500">لم يتم العثور على معدات من نوع سيارة دعم المحروقات</p>
                        <a href="{{ route('equipment.create') }}"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="ri-add-line ml-1"></i>
                            إضافة معدة جديدة
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add Fuel Modal -->
    <div id="addFuelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-lg bg-white"
            dir="rtl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">إضافة/تحديث المحروقات</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="closeAddFuelModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="addFuelForm" class="mt-4">
                @csrf
                <div class="space-y-4">
                    <!-- Fuel Type -->
                    <div>
                        <label for="fuelType" class="block text-sm font-medium text-gray-700 mb-2">نوع المحروقات</label>
                        <select id="fuelType" name="fuel_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">اختر نوع المحروقات</option>
                            <option value="diesel">ديزل</option>
                            <option value="gasoline">بنزين</option>
                            <option value="engine_oil">زيت ماكينة</option>
                            <option value="hydraulic_oil">زيت هيدروليك</option>
                            <option value="radiator_water">ماء ردياتير</option>
                            <option value="brake_oil">زيت فرامل</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">السعة الكلية
                            (لتر)</label>
                        <input type="number" id="capacity" name="capacity" step="0.01" min="0" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="أدخل السعة الكلية">
                    </div>

                    <!-- Current Quantity -->
                    <div>
                        <label for="currentQuantity" class="block text-sm font-medium text-gray-700 mb-2">الكمية الحالية
                            (لتر)</label>
                        <input type="number" id="currentQuantity" name="current_quantity" step="0.01"
                            min="0" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="أدخل الكمية الحالية">
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="fuelNotes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات
                            (اختيارية)</label>
                        <textarea id="fuelNotes" name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="أضف أي ملاحظات..."></textarea>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t mt-6">
                    <button type="button" onclick="closeAddFuelModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center">
                        <i class="ri-check-line ml-1"></i>
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Fuel Quantity Modal -->
    <div id="addFuelQuantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-lg bg-white"
            dir="rtl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">إضافة كمية محروقات</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="closeAddFuelQuantityModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="addFuelQuantityForm" class="mt-4">
                @csrf
                <div class="space-y-4">
                    <!-- Fuel Type Selection -->
                    <div>
                        <label for="fuelTypeSelect" class="block text-sm font-medium text-gray-700 mb-2">نوع المحروقات</label>
                        <select id="fuelTypeSelect" name="fuel_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">اختر نوع المحروقات</option>
                            <option value="diesel">ديزل</option>
                            <option value="gasoline">بنزين</option>
                            <option value="engine_oil">زيت ماكينة</option>
                            <option value="hydraulic_oil">زيت هيدروليك</option>
                            <option value="radiator_water">ماء ردياتير</option>
                            <option value="brake_oil">زيت فرامل</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>

                    <!-- Quantity Input -->
                    <div>
                        <label for="quantityInput" class="block text-sm font-medium text-gray-700 mb-2">الكمية (لتر)</label>
                        <input type="number" id="quantityInput" name="quantity" step="0.01" min="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="أدخل الكمية المراد إضافتها">
                    </div>

                    <!-- Current Quantity Display -->
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                        <p class="text-sm text-gray-600">الكمية الحالية:</p>
                        <p id="currentQuantityDisplay" class="text-lg font-semibold text-blue-600">0.00 لتر</p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="fuelQuantityNotes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات (اختيارية)</label>
                        <textarea id="fuelQuantityNotes" name="notes" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="أضف أي ملاحظات..."></textarea>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t mt-6">
                    <button type="button" onclick="closeAddFuelQuantityModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center">
                        <i class="ri-check-line ml-1"></i>
                        إضافة
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Distributions Modal -->
    <div id="distributionsModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-lg bg-white"
            dir="rtl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">توزيعات المحروقات</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="closeDistributionsModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                <div id="distributionsContent">
                    <!-- سيتم ملء المحتوى بواسطة JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentEquipmentId = null;
        let currentFuelTruckId = null;
        let currentFuelQuantity = null;

        // Notification function
        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 left-4 z-50 p-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="ri-${type === 'success' ? 'check' : 'error-warning'}-line ml-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 3000);
        }

        function openAddFuelModal(equipmentId) {
            currentEquipmentId = equipmentId;
            document.getElementById('addFuelModal').classList.remove('hidden');
        }

        function closeAddFuelModal() {
            document.getElementById('addFuelModal').classList.add('hidden');
            document.getElementById('addFuelForm').reset();
            currentEquipmentId = null;
        }

        function openAddFuelQuantityModal(equipmentId, fuelTruckId, currentQuantity) {
            currentEquipmentId = equipmentId;
            currentFuelTruckId = fuelTruckId;
            currentFuelQuantity = currentQuantity;
            document.getElementById('currentQuantityDisplay').textContent =
                (parseFloat(currentQuantity) || 0).toFixed(2) + ' لتر';
            document.getElementById('addFuelQuantityModal').classList.remove('hidden');
        }

        function closeAddFuelQuantityModal() {
            document.getElementById('addFuelQuantityModal').classList.add('hidden');
            document.getElementById('addFuelQuantityForm').reset();
            currentEquipmentId = null;
            currentFuelTruckId = null;
            currentFuelQuantity = null;
        }

        function closeDistributionsModal() {
            document.getElementById('distributionsModal').classList.add('hidden');
        }

        // Handle add fuel form submission
        document.getElementById('addFuelForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentEquipmentId) return;

            const formData = new FormData(this);

            fetch(`/fuel-management/equipment/${currentEquipmentId}/add-fuel`, {
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
                        closeAddFuelModal();
                        showNotification(data.message, 'success');
                        location.reload();
                    } else {
                        showNotification(data.message || 'حدث خطأ', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('حدث خطأ', 'error');
                });
        });

        // Handle add fuel quantity form submission
        document.getElementById('addFuelQuantityForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentFuelTruckId) return;

            const formData = new FormData(this);

            fetch(`/fuel-management/fuel-truck/${currentFuelTruckId}/add-quantity`, {
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
                        closeAddFuelQuantityModal();
                        showNotification(data.message, 'success');
                        location.reload();
                    } else {
                        showNotification(data.message || 'حدث خطأ', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('حدث خطأ', 'error');
                });
        });

        function viewDistributions(fuelTruckId) {
            console.log('جاري تحميل التوزيعات للتانكر:', fuelTruckId);

            fetch(`/api/fuel-truck/${fuelTruckId}/distributions`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    console.log('البيانات المستلمة:', data);

                    let content = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المعدة المستهدفة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ التوزيع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">موزع بواسطة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الموافقة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
        `;

                    if (data.length === 0) {
                        content += `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <i class="ri-information-line text-4xl mb-2"></i>
                        <p>لا توجد توزيعات</p>
                    </td>
                </tr>
            `;
                    } else {
                        data.forEach(distribution => {
                            content += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${distribution.target_equipment.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${distribution.quantity} لتر</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${distribution.distribution_date_formatted || distribution.distribution_date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${distribution.distributed_by.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${distribution.approval_status_color}">
                                ${distribution.approval_status_text}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${distribution.approval_status === 'pending' ?
                                `<div class="flex items-center gap-2">
                                                    <button onclick="approveDistribution(${distribution.id})"
                                                            class="text-green-600 hover:text-green-900" title="اعتماد">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                    <button onclick="rejectDistribution(${distribution.id})"
                                                            class="text-red-600 hover:text-red-900" title="رفض">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                    <button onclick="cancelDistribution(${distribution.id})"
                                                            class="text-gray-600 hover:text-gray-900" title="إلغاء">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>` : '-'
                            }
                        </td>
                    </tr>
                `;
                        });
                    }

                    content += `
                    </tbody>
                </table>
            </div>
        `;

                    document.getElementById('distributionsContent').innerHTML = content;
                    document.getElementById('distributionsModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('خطأ في تحميل التوزيعات:', error);
                    showNotification('حد خطأ في تحميل التوزيعات', 'error');
                });
        }

        function approveDistribution(distributionId) {
            const notes = prompt('ملاحظات الموافقة (اختيارية):');

            if (notes !== null) {
                fetch(`/fuel-management/distribution/${distributionId}/approve`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            approval_notes: notes
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            // Refresh distributions view
                            location.reload();
                        } else {
                            showNotification(data.message || 'حدث خطأ', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('حدث خطأ', 'error');
                    });
            }
        }

        function rejectDistribution(distributionId) {
            const notes = prompt('سبب الرفض (مطلوب):');

            if (notes && notes.trim()) {
                fetch(`/fuel-management/distribution/${distributionId}/reject`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            approval_notes: notes
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            // Refresh distributions view
                            location.reload();
                        } else {
                            showNotification(data.message || 'حدث خطأ', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('حدث خطأ', 'error');
                    });
            }
        }

        function cancelDistribution(distributionId) {
            if (confirm('هل أنت متأكد من رغبتك في إلغاء هذا التوزيع؟')) {
                fetch(`/fuel-management/distribution/${distributionId}/cancel`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            // Refresh distributions view
                            location.reload();
                        } else {
                            showNotification(data.message || 'حدث خطأ', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('حدث خطأ', 'error');
                    });
            }
        }

        // Close modal when clicking outside
        document.getElementById('addFuelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddFuelModal();
            }
        });

        document.getElementById('addFuelQuantityModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddFuelQuantityModal();
            }
        });

        document.getElementById('distributionsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDistributionsModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('addFuelModal').classList.contains('hidden')) {
                    closeAddFuelModal();
                }
                if (!document.getElementById('addFuelQuantityModal').classList.contains('hidden')) {
                    closeAddFuelQuantityModal();
                }
                if (!document.getElementById('distributionsModal').classList.contains('hidden')) {
                    closeDistributionsModal();
                }
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
