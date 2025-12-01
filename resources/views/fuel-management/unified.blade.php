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
                            @if($truck->fuelTruck)
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
                                        <span class="text-gray-600">الكمية الحالية:</span>
                                        <span class="font-bold text-blue-600">
                                            {{ number_format($truck->fuelTruck->current_quantity, 2) }} لتر
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">السعة:</span>
                                        <span class="font-medium">{{ number_format($truck->fuelTruck->capacity, 2) }} لتر</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">المتاح للإضافة:</span>
                                        <span class="font-medium text-orange-600">{{ number_format($truck->fuelTruck->capacity - $truck->fuelTruck->current_quantity, 2) }} لتر</span>
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
                                    <button onclick="event.stopPropagation(); openAddFuelModal({{ $truck->fuelTruck->id }}, '{{ $truck->name }}', {{ $truck->fuelTruck->capacity }}, {{ $truck->fuelTruck->current_quantity }})"
                                        class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors cursor-pointer">
                                        <i class="ri-add-circle-line ml-1"></i>
                                        إضافة كمية محروقات
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
                            @else
                            <div class="border-2 border-orange-300 rounded-lg p-6 bg-orange-50 hover:border-orange-500 transition-all duration-200">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $truck->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $truck->type }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-200 text-orange-800 font-semibold">
                                        <i class="ri-alert-fill ml-1"></i>
                                        غير مكتملة
                                    </span>
                                </div>

                                <!-- Warning Message -->
                                <div class="p-4 bg-orange-100 rounded-lg border border-orange-300 mb-4">
                                    <p class="text-sm text-orange-800 leading-relaxed">
                                        <i class="ri-alert-line ml-2"></i>
                                        لم يتم إضافة بيانات المحروقات لهذه السيارة بعد. يجب إضافة بيانات السعة ونوع المحروقات والكمية الحالية قبل استخدامها.
                                    </p>
                                </div>

                                <!-- Status Info -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">الموقع:</span>
                                        <span class="text-gray-900 font-medium">{{ $truck->location->name ?? 'غير محدد' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">السائق:</span>
                                        <span class="text-gray-900 font-medium">{{ $truck->driver->name ?? 'لم يتم التعيين' }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <a href="{{ route('fuel-truck.create', $truck->id) }}"
                                       class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                        <i class="ri-edit-line ml-1"></i>
                                        إضافة بيانات المحروقات
                                    </a>
                                    <button onclick="showIncompleteInfo({{ $truck->id }}, '{{ $truck->name }}')"
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                        <i class="ri-information-line ml-1"></i>
                                        المزيد
                                    </button>
                                </div>
                            </div>
                            @endif
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
                <!-- معلومات سيارة المحروقات -->
                <div class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                    <div class="flex items-center text-blue-800 mb-3">
                        <i class="ri-truck-line ml-2"></i>
                        <span class="font-semibold">معلومات سيارة المحروقات</span>
                    </div>
                    <div id="truckInfo" class="text-sm text-blue-800 space-y-2">
                        <p class="text-gray-500">جاري تحميل البيانات...</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- اختيار نوع المحروقات -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="ri-droplet-line ml-1"></i>نوع المحروقات
                        </label>
                        <select name="fuel_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            id="fuelTypeSelect">
                            <option value="">-- اختر نوع المحروقات --</option>
                            <option value="diesel">ديزل</option>
                            <option value="gasoline">بنزين</option>
                            <option value="engine_oil">زيت ماكينة</option>
                            <option value="hydraulic_oil">زيت هيدروليك</option>
                            <option value="radiator_water">ماء ردياتير</option>
                            <option value="brake_oil">زيت فرامل</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>

                    <!-- إدخال الكمية -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                            <i class="ri-measure-line ml-1"></i>الكمية (لتر)
                        </label>
                        <input type="number" name="quantity" step="0.01" min="0.1" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="أدخل الكمية المراد توزيعها"
                            id="quantityInput"
                            oninput="validateQuantityInput()">
                        <p class="text-xs text-gray-500 mt-1">الكمية المتاحة: <span id="availableQty" class="font-semibold text-green-600">0</span> لتر</p>
                        <p id="quantityWarning" class="text-xs text-red-600 mt-1 hidden">الكمية المدخلة تتجاوز الكمية المتاحة</p>
                    </div>
                </div>

                <!-- اختيار المعدة المستهدفة -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">
                        <i class="ri-building-line ml-1"></i>المعدة المستهدفة
                    </label>
                    <select name="target_equipment_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- اختر المعدة المستهدفة --</option>
                        @foreach($targetEquipments as $equipment)
                            <option value="{{ $equipment->id }}">
                                {{ $equipment->name }}
                                {{ $equipment->location ? '(' . $equipment->location->name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
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

    <!-- Add Fuel Modal -->
    <div id="addFuelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 shadow-lg rounded-lg bg-white" dir="rtl">
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">إضافة كمية محروقات</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600"
                    onclick="closeAddFuelModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="addFuelForm" class="mt-4 space-y-4">
                @csrf
                <!-- Truck Info Display -->
                <div class="bg-blue-50 rounded-lg p-4 space-y-2 border border-blue-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">اسم السيارة:</span>
                        <span id="addFuelTruckName" class="font-semibold text-blue-900"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">السعة:</span>
                        <span id="addFuelCapacity" class="font-semibold text-blue-900"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">الكمية الحالية:</span>
                        <span id="addFuelCurrent" class="font-semibold text-blue-900"></span>
                    </div>
                    <div class="flex justify-between items-center border-t pt-2">
                        <span class="text-sm font-medium text-gray-700">المتاح للإضافة:</span>
                        <span id="addFuelAvailable" class="font-semibold text-orange-600"></span>
                    </div>
                </div>

                <!-- Add Quantity Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="ri-droplet-line ml-1"></i>
                        الكمية المراد إضافتها (لتر)
                    </label>
                    <input type="number" id="addFuelQuantity" name="quantity" step="0.01" min="0.01" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        placeholder="أدخل الكمية المراد إضافتها"
                        oninput="validateAddFuelQuantity()">
                    <p id="addFuelError" class="text-red-600 text-sm mt-1 hidden"></p>
                    <p id="addFuelSuccess" class="text-green-600 text-sm mt-1 hidden"></p>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات (اختيارية)</label>
                    <textarea name="notes" rows="3" id="addFuelNotes"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                        placeholder="أضف أي ملاحظات..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeAddFuelModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg">
                        إلغاء
                    </button>
                    <button type="submit" id="addFuelSubmitBtn"
                        class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg flex items-center">
                        <i class="ri-add-circle-line ml-1"></i>
                        إضافة الكمية
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Incomplete Truck Info Modal -->
    <div id="incompleteInfoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white" dir="rtl">
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">بيانات السيارة الناقصة</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600"
                    onclick="closeIncompleteInfoModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div id="incompleteInfoContent" class="mt-4 space-y-4">
                <div class="text-center">
                    <i class="ri-loader-4-line text-2xl animate-spin"></i>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t mt-4">
                <button type="button" onclick="closeIncompleteInfoModal()"
                    class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    إغلاق
                </button>
                <a id="completeDataLink" href="#" target="_blank"
                    class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg flex items-center">
                    <i class="ri-edit-line ml-1"></i>
                    استكمال البيانات
                </a>
            </div>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">سيارة المحروقات</label>
                        <select name="fuel_truck_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <option value="">اختر سيارة المحروقات</option>
                            @foreach($fuelTrucks as $truck)
                                @if($truck->fuelTruck)
                                    <option value="{{ $truck->id }}">
                                        {{ $truck->name }} - {{ $truck->fuelTruck->fuel_type_text }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الكمية (لتر)</label>
                        <input type="number" name="quantity" step="0.01" min="0.1" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                            placeholder="أدخل الكمية">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                    // بناء معلومات السيارة بشكل احترافي
                    let info = '<div class="space-y-2">';
                    info += '<div class="flex justify-between items-center border-b pb-2">';
                    info += '<span class="text-gray-700">اسم السيارة:</span>';
                    info += '<span class="font-semibold text-blue-700">' + data.truck.name + '</span>';
                    info += '</div>';

                    info += '<div class="flex justify-between items-center border-b pb-2">';
                    info += '<span class="text-gray-700">نوع المحروقات:</span>';
                    info += '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">' + data.truck.fuel_type + '</span>';
                    info += '</div>';

                    info += '<div class="flex justify-between items-center border-b pb-2">';
                    info += '<span class="text-gray-700">السعة الكلية:</span>';
                    info += '<span class="font-semibold">' + parseFloat(data.truck.capacity).toFixed(2) + ' لتر</span>';
                    info += '</div>';

                    info += '<div class="flex justify-between items-center border-b pb-2">';
                    info += '<span class="text-gray-700">الكمية الحالية:</span>';
                    info += '<span class="font-semibold">' + parseFloat(data.truck.current_quantity).toFixed(2) + ' لتر</span>';
                    info += '</div>';

                    info += '<div class="flex justify-between items-center bg-green-50 -mx-4 px-4 py-2 rounded-b-lg">';
                    info += '<span class="text-green-800 font-semibold">الكمية المتاحة:</span>';
                    info += '<span class="text-green-700 font-bold text-lg">' + parseFloat(data.truck.remaining_quantity).toFixed(2) + ' لتر</span>';
                    info += '</div>';
                    info += '</div>';

                    document.getElementById('truckInfo').innerHTML = info;
                    document.getElementById('availableQty').textContent = parseFloat(data.truck.remaining_quantity).toFixed(2);
                    document.getElementById('quantityInput').max = availableQuantity;

                    // تحديث قيمة Max للحقل
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

        function showIncompleteInfo(truckId, truckName) {
            // بناء محتوى معلومات السيارة الناقصة
            let content = '<div class="space-y-4">';

            // رسالة تحذيرية
            content += '<div class="p-4 bg-orange-50 border border-orange-300 rounded-lg">';
            content += '<div class="flex items-start">';
            content += '<i class="ri-alert-fill text-2xl text-orange-600 ml-3 mt-1"></i>';
            content += '<div>';
            content += '<h4 class="font-bold text-orange-900 mb-1">بيانات ناقصة</h4>';
            content += '<p class="text-sm text-orange-800">السيارة "' + truckName + '" تحتاج إلى استكمال البيانات التالية:</p>';
            content += '</div>';
            content += '</div>';
            content += '</div>';

            // قائمة البيانات الناقصة
            content += '<div class="bg-gray-50 rounded-lg p-4">';
            content += '<h4 class="font-semibold text-gray-900 mb-3">المتطلبات:</h4>';
            content += '<ul class="space-y-2">';
            content += '<li class="flex items-center text-gray-700">';
            content += '<i class="ri-checkbox-blank-circle-fill text-xl text-orange-500 ml-2"></i>';
            content += 'بيانات سيارة المحروقات (السعة، نوع المحروقات)';
            content += '</li>';
            content += '<li class="flex items-center text-gray-700">';
            content += '<i class="ri-checkbox-blank-circle-fill text-xl text-orange-500 ml-2"></i>';
            content += 'الكمية الحالية والكمية المتبقية';
            content += '</li>';
            content += '<li class="flex items-center text-gray-700">';
            content += '<i class="ri-checkbox-blank-circle-fill text-xl text-orange-500 ml-2"></i>';
            content += 'معلومات الموقع والسائق';
            content += '</li>';
            content += '</ul>';
            content += '</div>';

            // الفوائد
            content += '<div class="bg-blue-50 rounded-lg p-4">';
            content += '<h4 class="font-semibold text-blue-900 mb-2">فوائد إكمال البيانات:</h4>';
            content += '<ul class="space-y-1 text-sm text-blue-800">';
            content += '<li><i class="ri-check-line ml-1"></i>تفعيل عمليات التوزيع والاستهلاك</li>';
            content += '<li><i class="ri-check-line ml-1"></i>تتبع دقيق لمستويات المحروقات</li>';
            content += '<li><i class="ri-check-line ml-1"></i>إدارة أفضل للسيارات والموارد</li>';
            content += '</ul>';
            content += '</div>';

            content += '</div>';

            document.getElementById('incompleteInfoContent').innerHTML = content;
            document.getElementById('completeDataLink').href = '/equipment/' + truckId + '/edit';
            document.getElementById('incompleteInfoModal').classList.remove('hidden');
        }

        function closeIncompleteInfoModal() {
            document.getElementById('incompleteInfoModal').classList.add('hidden');
        }

        // Validate quantity input in real-time
        function validateQuantityInput() {
            const quantityInput = document.getElementById('quantityInput');
            const quantityWarning = document.getElementById('quantityWarning');
            const quantity = parseFloat(quantityInput.value) || 0;

            if (quantity > availableQuantity && quantity > 0) {
                quantityWarning.classList.remove('hidden');
                quantityInput.classList.add('border-red-500');
            } else {
                quantityWarning.classList.add('hidden');
                quantityInput.classList.remove('border-red-500');
            }
        }

        // Handle distribute form submission
        document.getElementById('distributeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!currentFuelTruckId) return;

            const formData = new FormData(this);
            const quantity = parseFloat(formData.get('quantity'));
            const fuelType = formData.get('fuel_type');
            const targetEquipmentId = formData.get('target_equipment_id');

            // Validate required fields
            if (!fuelType || fuelType.trim() === '') {
                showNotification('الرجاء اختيار نوع المحروقات', 'error');
                return;
            }

            if (!targetEquipmentId || targetEquipmentId.trim() === '') {
                showNotification('الرجاء اختيار المعدة المستهدفة', 'error');
                return;
            }

            if (!quantity || quantity <= 0) {
                showNotification('الرجاء إدخال كمية صحيحة', 'error');
                return;
            }

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
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error('Server responded with status ' + response.status + ': ' + text);
                    });
                }
                return response.json();
            })
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
                showNotification('خطأ: ' + error.message, 'error');
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error('Server responded with status ' + response.status + ': ' + text);
                    });
                }
                return response.json();
            })
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
                showNotification('خطأ: ' + error.message, 'error');
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
        document.getElementById('incompleteInfoModal').addEventListener('click', function(e) {
            if (e.target === this) closeIncompleteInfoModal();
        });

        // Add Fuel Functions
        let addFuelTruckId = null;
        let addFuelCapacity = 0;
        let addFuelCurrent = 0;

        function openAddFuelModal(fuelTruckId, truckName, capacity, current) {
            addFuelTruckId = fuelTruckId;
            addFuelCapacity = parseFloat(capacity);
            addFuelCurrent = parseFloat(current);

            document.getElementById('addFuelTruckName').innerText = truckName;
            document.getElementById('addFuelCapacity').innerText = capacity.toFixed(2) + ' لتر';
            document.getElementById('addFuelCurrent').innerText = current.toFixed(2) + ' لتر';
            document.getElementById('addFuelAvailable').innerText = (capacity - current).toFixed(2) + ' لتر';

            document.getElementById('addFuelQuantity').value = '';
            document.getElementById('addFuelNotes').value = '';
            document.getElementById('addFuelError').classList.add('hidden');
            document.getElementById('addFuelSuccess').classList.add('hidden');

            document.getElementById('addFuelModal').classList.remove('hidden');
        }

        function closeAddFuelModal() {
            document.getElementById('addFuelModal').classList.add('hidden');
        }

        function validateAddFuelQuantity() {
            const quantity = parseFloat(document.getElementById('addFuelQuantity').value) || 0;
            const available = addFuelCapacity - addFuelCurrent;
            const errorEl = document.getElementById('addFuelError');
            const successEl = document.getElementById('addFuelSuccess');
            const submitBtn = document.getElementById('addFuelSubmitBtn');

            errorEl.classList.add('hidden');
            successEl.classList.add('hidden');

            if (quantity > available) {
                errorEl.innerText = `الكمية المدخلة (${quantity.toFixed(2)} لتر) تتجاوز المتاح (${available.toFixed(2)} لتر)`;
                errorEl.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else if (quantity > 0) {
                const newTotal = addFuelCurrent + quantity;
                successEl.innerText = `✓ الكمية الجديدة ستكون: ${newTotal.toFixed(2)} لتر (من سعة ${addFuelCapacity.toFixed(2)} لتر)`;
                successEl.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        // Handle Add Fuel Form Submit
        document.getElementById('addFuelForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const quantity = parseFloat(document.getElementById('addFuelQuantity').value);
            const notes = document.getElementById('addFuelNotes').value;

            if (!quantity || quantity <= 0) {
                alert('يجب إدخال كمية صحيحة');
                return;
            }

            const available = addFuelCapacity - addFuelCurrent;
            if (quantity > available) {
                alert('الكمية تتجاوز المتاح');
                return;
            }

            try {
                const response = await fetch('/fuel-management/fuel-truck/' + addFuelTruckId + '/add-quantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        quantity: quantity,
                        notes: notes
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showNotification(data.message || 'تم إضافة الكمية بنجاح', 'success');
                    closeAddFuelModal();
                    // Reload the page after 1 second
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'حدث خطأ', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('حدث خطأ في الإضافة', 'error');
            }
        });

        // Close modals on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTruckModal();
                closeDistributeModal();
                closeConsumptionModal();
                closeIncompleteInfoModal();
                closeAddFuelModal();
            }
        });

        // Close Add Fuel Modal on outside click
        document.getElementById('addFuelModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddFuelModal();
        });
    </script>
@endsection
