@extends('layouts.app')

@section('title', 'إضافة رحلة جديدة - شركة الأبراج للمقاولات')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('transport.index') }}"
                   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-sm border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors ml-4">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900">إنشاء رحلة نقل جديدة</h1>
                    <p class="text-gray-600 mt-1">أدخل تفاصيل الرحلة بدقة لضمان سير العمل بسلاسة</p>
                </div>
            </div>

            <!-- Progress Stepper -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-8 space-x-reverse">
                        <div class="flex items-center">
                            <div class="step-circle active" data-step="1">
                                <i class="ri-truck-line"></i>
                            </div>
                            <span class="mr-3 text-sm font-medium text-blue-600">نوع النقل</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-200">
                            <div class="step-line" data-step="1"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="step-circle" data-step="2">
                                <i class="ri-map-pin-line"></i>
                            </div>
                            <span class="mr-3 text-sm font-medium text-gray-500">المواقع</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-200">
                            <div class="step-line" data-step="2"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="step-circle" data-step="3">
                                <i class="ri-box-line"></i>
                            </div>
                            <span class="mr-3 text-sm font-medium text-gray-500">تفاصيل الرحلة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('transport.store') }}" method="POST" id="trip-form">
            @csrf

            <!-- Step 1: Transport Type -->
            <div class="form-step active" data-step="1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="ri-truck-line text-white text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">اختر نوع النقل</h2>
                        <p class="text-gray-600">حدد نوع النقل المناسب لرحلتك</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                        <label class="transport-option group cursor-pointer">
                            <input type="radio" name="transport_type" value="internal" class="sr-only" onchange="handleTransportTypeChange()">
                            <div class="relative bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-8 text-center transition-all duration-300 group-hover:shadow-lg group-hover:scale-105">
                                <div class="absolute top-4 left-4 w-6 h-6 rounded-full border-2 border-green-300 bg-white flex items-center justify-center">
                                    <div class="w-3 h-3 rounded-full bg-green-500 hidden radio-dot"></div>
                                </div>
                                <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ri-building-line text-white text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">النقل الداخلي</h3>
                                <p class="text-gray-600 text-sm leading-relaxed">استخدام معدات وشاحنات الشركة الخاصة مع سائقين مدربين</p>
                                <div class="mt-4 flex items-center justify-center text-green-600">
                                    <i class="ri-shield-check-line ml-1"></i>
                                    <span class="text-sm font-medium">موثوق ومضمون</span>
                                </div>
                            </div>
                        </label>

                        <label class="transport-option group cursor-pointer">
                            <input type="radio" name="transport_type" value="external" class="sr-only" onchange="handleTransportTypeChange()">
                            <div class="relative bg-gradient-to-br from-orange-50 to-amber-50 border-2 border-orange-200 rounded-2xl p-8 text-center transition-all duration-300 group-hover:shadow-lg group-hover:scale-105">
                                <div class="absolute top-4 left-4 w-6 h-6 rounded-full border-2 border-orange-300 bg-white flex items-center justify-center">
                                    <div class="w-3 h-3 rounded-full bg-orange-500 hidden radio-dot"></div>
                                </div>
                                <div class="w-20 h-20 bg-gradient-to-r from-orange-400 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ri-truck-line text-white text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">النقل الخارجي</h3>
                                <p class="text-gray-600 text-sm leading-relaxed">استخدام شاحنات المقاولين والموردين الخارجيين</p>
                                <div class="mt-4 flex items-center justify-center text-orange-600">
                                    <i class="ri-price-tag-line ml-1"></i>
                                    <span class="text-sm font-medium">مرونة في التكلفة</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    @error('transport_type')
                        <div class="mt-6 max-w-md mx-auto">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center">
                                <i class="ri-error-warning-line text-red-500 ml-3"></i>
                                <span class="text-red-700">{{ $message }}</span>
                            </div>
                        </div>
                    @enderror

                    <div class="mt-8 text-center">
                        <button type="button" id="step1-next" class="btn-primary disabled" disabled onclick="nextStep(2)">
                            <span>المتابعة</span>
                            <i class="ri-arrow-left-line mr-2"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Step 2: Vehicle Selection & Locations -->
            <div class="form-step" data-step="2">
                <div class="space-y-6">

                    <!-- Vehicle Selection -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center ml-3">
                                <i class="ri-car-line text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">اختيار المركبة</h3>
                                <p class="text-gray-600 text-sm">حدد المركبة المناسبة للنقل</p>
                            </div>
                        </div>



                        <!-- Internal Vehicles -->
                        <div id="internal-vehicles" class="vehicle-section" data-transport-type="internal">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach(\App\Models\Equipment::where('status', 'available')->where('category', 'شاحنة')->whereNotNull('truck_id')->with(['driver', 'locationDetail', 'internalTruck'])->get() as $vehicle)
                                <label class="vehicle-card cursor-pointer group">
                                    <input type="radio" name="internal_vehicle_id" value="{{ $vehicle->id }}" class="sr-only"
                                           data-driver="{{ $vehicle->driver->name ?? 'غير محدد' }}"
                                           data-phone="{{ $vehicle->driver->phone ?? 'غير محدد' }}"
                                           data-location="{{ $vehicle->locationDetail->name ?? 'غير محدد' }}"
                                           onchange="handleVehicleSelection()">
                                    <div class="bg-white border-2 border-gray-200 rounded-xl p-4 transition-all duration-200 group-hover:border-blue-300 group-hover:shadow-md">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3 space-x-reverse">
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="ri-truck-line text-green-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">{{ $vehicle->name }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $vehicle->type }} - {{ $vehicle->driver->name ?? 'بدون سائق' }}</p>
                                                </div>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center vehicle-radio">
                                                <div class="w-3 h-3 rounded-full bg-blue-600 hidden"></div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <!-- Vehicle Details -->
                            <div id="vehicle-details" class="hidden mt-6">
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="ri-information-line text-green-600 ml-2"></i>
                                        تفاصيل المركبة
                                    </h4>
                                    <div id="vehicle-info" class="space-y-3"></div>
                                </div>
                            </div>
                        </div>

                        <!-- External Vehicles -->
                        <div id="external-vehicles" class="vehicle-section" data-transport-type="external">
                            <div class="grid grid-cols-1 gap-4">
                                @foreach(\App\Models\ExternalTruck::where('status', 'active')->with('supplier')->get() as $truck)
                                <label class="vehicle-card cursor-pointer group">
                                    <input type="radio" name="external_truck_id" value="{{ $truck->id }}" class="sr-only"
                                           data-driver="{{ $truck->driver_name ?? 'غير محدد' }}"
                                           data-phone="{{ $truck->driver_phone ?? 'غير محدد' }}"
                                           data-plate="{{ $truck->plate_number ?? 'غير محدد' }}"
                                           data-supplier="{{ $truck->supplier->name ?? 'غير محدد' }}"
                                           onchange="handleVehicleSelection()">
                                    <div class="border-2 border-gray-200 rounded-xl p-4 transition-all duration-200 group-hover:border-orange-400 group-hover:shadow-md">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-r from-orange-100 to-orange-200 rounded-lg flex items-center justify-center ml-4">
                                                    <i class="ri-truck-line text-orange-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">{{ $truck->plate_number }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $truck->loading_type ?? 'شاحنة خارجية' }} - {{ $truck->supplier->name ?? 'مورد خارجي' }}</p>
                                                </div>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center vehicle-radio">
                                                <div class="w-3 h-3 bg-orange-500 rounded-full hidden"></div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            <!-- Truck Details -->
                            <div id="truck-details" class="hidden mt-6">
                                <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-xl p-6">
                                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="ri-information-line text-orange-600 ml-2"></i>
                                        تفاصيل الشاحنة
                                    </h4>
                                    <div id="truck-info" class="space-y-3"></div>
                                </div>
                            </div>
                        </div>

                        @error('internal_vehicle_id')
                            <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3 flex items-center">
                                <i class="ri-error-warning-line text-red-500 ml-2"></i>
                                <span class="text-red-700 text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                        @error('external_truck_id')
                            <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3 flex items-center">
                                <i class="ri-error-warning-line text-red-500 ml-2"></i>
                                <span class="text-red-700 text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Locations -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center ml-3">
                                <i class="ri-map-pin-line text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">مواقع النقل</h3>
                                <p class="text-gray-600 text-sm">حدد نقطة التحميل ونقطة التفريغ</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="ri-upload-line text-green-600 ml-1"></i>
                                    موقع التحميل
                                </label>
                                <select name="loading_location_id" id="loading_location_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('loading_location_id') border-red-300 @enderror">
                                    <option value="">اختر موقع التحميل</option>
                                    @foreach(\App\Models\Location::where('status', 'active')->get() as $location)
                                        <option value="{{ $location->id }}" {{ old('loading_location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }} - {{ $location->address ?? $location->city }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('loading_location_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="ri-download-line text-red-600 ml-1"></i>
                                    موقع التفريغ
                                </label>
                                <select name="unloading_location_id" id="unloading_location_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('unloading_location_id') border-red-300 @enderror">
                                    <option value="">اختر موقع التفريغ</option>
                                    @foreach(\App\Models\Location::where('status', 'active')->get() as $location)
                                        <option value="{{ $location->id }}" {{ old('unloading_location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }} - {{ $location->address ?? $location->city }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unloading_location_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Route Preview -->
                        <div id="route-preview" class="hidden mt-6">
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-4">
                                <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="ri-route-line text-blue-600 ml-2"></i>
                                    معاينة المسار
                                </h4>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <span id="loading-preview" class="mr-2 text-sm font-medium text-gray-700">نقطة التحميل</span>
                                    </div>
                                    <div class="flex-1 mx-4 border-t-2 border-dashed border-gray-300"></div>
                                    <div class="flex items-center">
                                        <span id="unloading-preview" class="ml-2 text-sm font-medium text-gray-700">نقطة التفريغ</span>
                                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between">
                        <button type="button" class="btn-secondary" onclick="prevStep(1)">
                            <i class="ri-arrow-right-line ml-2"></i>
                            <span>السابق</span>
                        </button>
                        <button type="button" id="step2-next" class="btn-primary disabled" disabled onclick="nextStep(3)">
                            <span>المتابعة</span>
                            <i class="ri-arrow-left-line mr-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Trip Details -->
            <div class="form-step" data-step="3">
                <div class="space-y-6">

                    <!-- Cargo Details -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center ml-3">
                                <i class="ri-box-line text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">تفاصيل البضاعة</h3>
                                <p class="text-gray-600 text-sm">أدخل معلومات البضاعة المنقولة</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="ri-box-3-line text-purple-600 ml-1"></i>
                                    نوع المادة
                                </label>
                                <select name="material_id" id="material_id" required onchange="updateQuantityUnit()"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('material_id') border-red-300 @enderror">
                                    <option value="">اختر نوع المادة</option>
                                    @foreach(\App\Models\Material::all() as $material)
                                        <option value="{{ $material->id }}" data-unit="{{ $material->unit ?? '' }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }}@if($material->unit) ({{ $material->unit }})@else (بدون وحدة محددة)@endif
                                        </option>
                                    @endforeach
                                </select>
                                @if(\App\Models\Material::whereNull('material_unit_id')->count() > 0)
                                    <div class="mt-2 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                        <div class="flex items-start">
                                            <i class="ri-warning-line text-amber-600 mt-0.5 ml-2"></i>
                                            <div>
                                                <p class="text-sm text-amber-800 font-medium">تنبيه</p>
                                                <p class="text-xs text-amber-700 mt-1">
                                                    بعض المواد لا تحتوي على وحدة قياس محددة. يمكنك تحديث وحدات القياس من
                                                    <a href="{{ route('settings.materials') }}" class="underline font-medium hover:text-amber-900">إدارة المواد</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @error('material_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="ri-scales-line text-purple-600 ml-1"></i>
                                    الكمية <span id="unit-display" class="text-gray-500 font-normal"></span>
                                </label>
                                <div class="flex">
                                    <input type="number" name="quantity" id="quantity" required step="0.01" min="0"
                                           value="{{ old('quantity') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-r-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('quantity') border-red-300 @enderror"
                                           placeholder="أدخل الكمية">
                                    <div id="quantity-unit" class="flex items-center px-4 py-3 bg-gray-50 border border-l-0 border-gray-300 rounded-l-xl text-gray-700 font-medium min-w-[100px] justify-center">
                                        الوحدة
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    <i class="ri-information-line ml-1"></i>
                                    سيتم عرض وحدة القياس المحددة للمادة المختارة
                                </p>
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Trip Details -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-lg flex items-center justify-center ml-3">
                                <i class="ri-calendar-line text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">تفاصيل الرحلة</h3>
                                <p class="text-gray-600 text-sm">حدد وقت الوصول</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="ri-time-line text-indigo-600 ml-1"></i>
                                    وقت الوصول
                                </label>
                                <input type="datetime-local" name="arrival_time" id="arrival_time" required
                                       value="{{ old('arrival_time', now()->format('Y-m-d\TH:i')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('arrival_time') border-red-300 @enderror">
                                @error('arrival_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="ri-file-text-line text-indigo-600 ml-1"></i>
                                ملاحظات إضافية
                            </label>
                            <textarea name="notes" id="notes" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('notes') border-red-300 @enderror"
                                      placeholder="أدخل أي ملاحظات إضافية حول الرحلة...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Final Review -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="ri-check-line text-green-600 ml-2"></i>
                            مراجعة نهائية
                        </h3>
                        <div id="trip-summary" class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600">نوع النقل:</span>
                                <span id="summary-transport-type" class="font-medium text-gray-900">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600">المركبة:</span>
                                <span id="summary-vehicle" class="font-medium text-gray-900">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600">المسار:</span>
                                <span id="summary-route" class="font-medium text-gray-900">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">وقت الوصول:</span>
                                <span id="summary-arrival" class="font-medium text-gray-900">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between">
                        <button type="button" class="btn-secondary" onclick="prevStep(2)">
                            <i class="ri-arrow-right-line ml-2"></i>
                            <span>السابق</span>
                        </button>
                        <button type="submit" class="btn-success">
                            <i class="ri-save-line ml-2"></i>
                            <span>حفظ الرحلة</span>
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@push('styles')
<style>
    /* Form Steps */
    .form-step {
        display: none;
    }
    .form-step.active {
        display: block;
        animation: fadeInUp 0.5s ease-out;
    }

    /* Progress Stepper */
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    .step-circle.active {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: white;
        transform: scale(1.1);
    }
    .step-circle.completed {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .step-line {
        height: 100%;
        background: #e5e7eb;
        transition: all 0.3s ease;
    }
    .step-line.active {
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    }

    /* Transport Options */
    .transport-option input:checked + div {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff, #f0f9ff);
        transform: scale(1.02);
    }
    .transport-option input:checked + div .radio-dot {
        display: block !important;
    }

    /* Vehicle Cards */
    .vehicle-card input:checked + div {
        border-color: #3b82f6;
        background: #f8fafc;
    }
    .vehicle-card input:checked + div .vehicle-radio div {
        display: block !important;
    }

    /* Buttons */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .btn-primary:hover:not(.disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }
    .btn-primary.disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }

    /* Vehicle Cards */
    .vehicle-card input[type="radio"]:checked + div {
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .vehicle-card:hover div {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        background: #f8fafc;
        color: #64748b;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .btn-secondary:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .btn-success {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 32px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Inputs Focus */
    input:focus, select:focus, textarea:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .step-circle {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .btn-primary, .btn-secondary, .btn-success {
            padding: 10px 20px;
            font-size: 14px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
let currentStep = 1;
let selectedTransportType = null;
let selectedVehicle = null;
let selectedVehicleType = null;

// Handle transport type selection
function handleTransportTypeChange() {
    const transportInputs = document.querySelectorAll('input[name="transport_type"]');
    selectedTransportType = null;

    transportInputs.forEach(input => {
        if (input.checked) {
            selectedTransportType = input.value;
        }
    });

    // Enable/disable next button
    const nextBtn = document.getElementById('step1-next');
    if (selectedTransportType) {
        nextBtn.classList.remove('disabled');
        nextBtn.disabled = false;
    } else {
        nextBtn.classList.add('disabled');
        nextBtn.disabled = true;
    }

    // Update summary
    updateSummary();
}

// Show vehicles based on transport type
function showVehiclesByTransportType() {
    // Hide all vehicle sections first
    document.querySelectorAll('.vehicle-section').forEach(section => {
        section.classList.add('hidden');
    });

    // Show the appropriate section based on selected transport type
    if (selectedTransportType === 'internal') {
        document.getElementById('internal-vehicles').classList.remove('hidden');
    } else if (selectedTransportType === 'external') {
        document.getElementById('external-vehicles').classList.remove('hidden');
    }

    // Reset vehicle selection
    selectedVehicle = null;
    document.getElementById('vehicle-details').classList.add('hidden');
    document.getElementById('truck-details').classList.add('hidden');

    // Clear previous vehicle selections
    document.querySelectorAll('input[name="internal_vehicle_id"], input[name="external_truck_id"]').forEach(input => {
        input.checked = false;
    });

    // Update vehicle cards styling
    document.querySelectorAll('.vehicle-card').forEach(card => {
        const indicator = card.querySelector('.vehicle-radio div');
        const border = card.querySelector('div div');
        if (indicator) indicator.classList.add('hidden');
        if (border) {
            border.classList.remove('border-green-400', 'bg-green-50', 'border-orange-400', 'bg-orange-50', 'border-blue-400', 'bg-blue-50');
            border.classList.add('border-gray-200');
        }
    });

    updateStepValidation(2);
}
function handleVehicleSelection() {
    const internalInputs = document.querySelectorAll('input[name="internal_vehicle_id"]');
    const externalInputs = document.querySelectorAll('input[name="external_truck_id"]');

    selectedVehicle = null;

    // Reset all vehicle cards
    document.querySelectorAll('.vehicle-card').forEach(card => {
        const indicator = card.querySelector('.vehicle-radio div');
        const border = card.querySelector('div');
        indicator.classList.add('hidden');
        border.classList.remove('border-green-400', 'bg-green-50', 'border-orange-400', 'bg-orange-50');
        border.classList.add('border-gray-200');
    });

    // Check internal vehicles
    internalInputs.forEach(input => {
        if (input.checked) {
            selectedVehicle = {
                type: 'internal',
                id: input.value,
                driver: input.dataset.driver,
                phone: input.dataset.phone,
                location: input.dataset.location
            };

            // Update visual state
            const card = input.closest('.vehicle-card');
            const indicator = card.querySelector('.vehicle-radio div');
            const border = card.querySelector('div');
            indicator.classList.remove('hidden');
            border.classList.remove('border-gray-200');
            border.classList.add('border-green-400', 'bg-green-50');
        }
    });

    // Check external vehicles
    externalInputs.forEach(input => {
        if (input.checked) {
            selectedVehicle = {
                type: 'external',
                id: input.value,
                driver: input.dataset.driver,
                phone: input.dataset.phone,
                plate: input.dataset.plate,
                supplier: input.dataset.supplier
            };

            // Update visual state
            const card = input.closest('.vehicle-card');
            const indicator = card.querySelector('.vehicle-radio div');
            const border = card.querySelector('div');
            indicator.classList.remove('hidden');
            border.classList.remove('border-gray-200');
            border.classList.add('border-orange-400', 'bg-orange-50');
        }
    });

    // Show vehicle details
    if (selectedVehicle) {
        showVehicleDetails();
        validateStep2();
    } else {
        hideVehicleDetails();
    }

    updateSummary();
}

// Show vehicle details
function showVehicleDetails() {
    if (!selectedVehicle) return;

    if (selectedVehicle.type === 'internal') {
        const vehicleDetails = document.getElementById('vehicle-details');
        const vehicleInfo = document.getElementById('vehicle-info');

        vehicleInfo.innerHTML = `
            <div class="flex items-center">
                <i class="ri-user-line text-green-600 ml-2"></i>
                <span><strong>السائق:</strong> ${selectedVehicle.driver}</span>
            </div>
            <div class="flex items-center">
                <i class="ri-phone-line text-green-600 ml-2"></i>
                <span><strong>رقم الهاتف:</strong> ${selectedVehicle.phone}</span>
            </div>
            <div class="flex items-center">
                <i class="ri-map-pin-line text-green-600 ml-2"></i>
                <span><strong>الموقع الحالي:</strong> ${selectedVehicle.location}</span>
            </div>
        `;
        vehicleDetails.classList.remove('hidden');
    } else {
        const truckDetails = document.getElementById('truck-details');
        const truckInfo = document.getElementById('truck-info');

        truckInfo.innerHTML = `
            <div class="flex items-center">
                <i class="ri-license-line text-orange-600 ml-2"></i>
                <span><strong>رقم اللوحة:</strong> ${selectedVehicle.plate}</span>
            </div>
            <div class="flex items-center">
                <i class="ri-user-line text-orange-600 ml-2"></i>
                <span><strong>السائق:</strong> ${selectedVehicle.driver}</span>
            </div>
            <div class="flex items-center">
                <i class="ri-phone-line text-orange-600 ml-2"></i>
                <span><strong>رقم الهاتف:</strong> ${selectedVehicle.phone}</span>
            </div>
            <div class="flex items-center">
                <i class="ri-building-line text-orange-600 ml-2"></i>
                <span><strong>المورد:</strong> ${selectedVehicle.supplier}</span>
            </div>
        `;
        truckDetails.classList.remove('hidden');
    }
}

// Hide vehicle details
function hideVehicleDetails() {
    document.getElementById('vehicle-details').classList.add('hidden');
    document.getElementById('truck-details').classList.add('hidden');
}

// Validate step 2
function validateStep2() {
    const loadingLocation = document.getElementById('loading_location_id').value;
    const unloadingLocation = document.getElementById('unloading_location_id').value;
    const hasVehicle = selectedVehicle !== null;

    const nextBtn = document.getElementById('step2-next');

    if (hasVehicle && loadingLocation && unloadingLocation) {
        nextBtn.classList.remove('disabled');
        nextBtn.disabled = false;
        showRoutePreview();
    } else {
        nextBtn.classList.add('disabled');
        nextBtn.disabled = true;
        hideRoutePreview();
    }
}

// General step validation
function updateStepValidation(step) {
    if (step === 2) {
        validateStep2();
    }
}

// Show route preview
function showRoutePreview() {
    const loadingSelect = document.getElementById('loading_location_id');
    const unloadingSelect = document.getElementById('unloading_location_id');
    const routePreview = document.getElementById('route-preview');
    const loadingPreview = document.getElementById('loading-preview');
    const unloadingPreview = document.getElementById('unloading-preview');

    if (loadingSelect.value && unloadingSelect.value) {
        loadingPreview.textContent = loadingSelect.selectedOptions[0].text;
        unloadingPreview.textContent = unloadingSelect.selectedOptions[0].text;
        routePreview.classList.remove('hidden');
    }
}

// Hide route preview
function hideRoutePreview() {
    document.getElementById('route-preview').classList.add('hidden');
}

// Next step
function nextStep(step) {
    if (step === 2) {
        // Moving from step 1 to 2 - show vehicles based on transport type
        showVehiclesByTransportType();
    }

    // Hide current step
    document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.remove('active');

    // Show next step
    currentStep = step;
    document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');

    // Update stepper
    updateStepper();

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Update summary if on step 3
    if (step === 3) {
        updateSummary();
    }
}

// Previous step
function prevStep(step) {
    // Hide current step
    document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.remove('active');

    // Show previous step
    currentStep = step;
    document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');

    // Update stepper
    updateStepper();

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Update stepper
function updateStepper() {
    // Update step circles
    for (let i = 1; i <= 3; i++) {
        const stepCircle = document.querySelector(`.step-circle[data-step="${i}"]`);
        const stepLine = document.querySelector(`.step-line[data-step="${i}"]`);

        if (i < currentStep) {
            stepCircle.classList.add('completed');
            stepCircle.classList.remove('active');
            if (stepLine) stepLine.classList.add('active');
        } else if (i === currentStep) {
            stepCircle.classList.add('active');
            stepCircle.classList.remove('completed');
        } else {
            stepCircle.classList.remove('active', 'completed');
            if (stepLine) stepLine.classList.remove('active');
        }
    }

    // Update step text colors
    const steps = [
        { step: 1, text: 'نوع النقل' },
        { step: 2, text: 'المواقع' },
        { step: 3, text: 'تفاصيل الرحلة' }
    ];

    steps.forEach(({ step, text }) => {
        const stepText = document.querySelector(`.step-circle[data-step="${step}"]`).nextElementSibling;
        if (step <= currentStep) {
            stepText.classList.remove('text-gray-500');
            stepText.classList.add('text-blue-600');
        } else {
            stepText.classList.remove('text-blue-600');
            stepText.classList.add('text-gray-500');
        }
    });
}

// Update quantity unit based on selected material
function updateQuantityUnit() {
    const materialSelect = document.getElementById('material_id');
    const unitDisplay = document.getElementById('unit-display');
    const quantityUnit = document.getElementById('quantity-unit');

    if (materialSelect.value) {
        const selectedOption = materialSelect.selectedOptions[0];
        const unit = selectedOption.getAttribute('data-unit');

        if (unit && unit.trim() !== '') {
            unitDisplay.textContent = `(${unit})`;
            quantityUnit.textContent = unit;
        } else {
            unitDisplay.textContent = '(بدون وحدة محددة)';
            quantityUnit.textContent = 'الكمية';
        }
    } else {
        unitDisplay.textContent = '';
        quantityUnit.textContent = 'الوحدة';
    }
}

// Update summary
function updateSummary() {
    // Transport type
    const transportTypeElement = document.getElementById('summary-transport-type');
    if (selectedTransportType && transportTypeElement) {
        transportTypeElement.textContent = selectedTransportType === 'internal' ? 'النقل الداخلي' : 'النقل الخارجي';
    }

    // Vehicle
    const vehicleElement = document.getElementById('summary-vehicle');
    if (selectedVehicle && vehicleElement) {
        if (selectedVehicle.type === 'internal') {
            const vehicleCard = document.querySelector(`input[value="${selectedVehicle.id}"]`).closest('.vehicle-card');
            const vehicleNumber = vehicleCard.querySelector('h4').textContent;
            vehicleElement.textContent = vehicleNumber + ' - ' + selectedVehicle.driver;
        } else {
            vehicleElement.textContent = selectedVehicle.plate + ' - ' + selectedVehicle.supplier;
        }
    }

    // Route
    const routeElement = document.getElementById('summary-route');
    const loadingSelect = document.getElementById('loading_location_id');
    const unloadingSelect = document.getElementById('unloading_location_id');
    if (loadingSelect.value && unloadingSelect.value && routeElement) {
        const loadingText = loadingSelect.selectedOptions[0].text.split(' - ')[0];
        const unloadingText = unloadingSelect.selectedOptions[0].text.split(' - ')[0];
        routeElement.textContent = `${loadingText} ← ${unloadingText}`;
    }

    // Arrival time
    const arrivalElement = document.getElementById('summary-arrival');
    const arrivalTime = document.getElementById('arrival_time').value;
    if (arrivalTime && arrivalElement) {
        const date = new Date(arrivalTime);
        arrivalElement.textContent = date.toLocaleString('ar-SA');
    }

    // Material and quantity
    const materialElement = document.getElementById('summary-material');
    const materialSelect = document.getElementById('material_id');
    const quantityInput = document.getElementById('quantity');
    if (materialSelect && materialSelect.value && materialElement) {
        const materialName = materialSelect.selectedOptions[0].text;
        const quantity = quantityInput.value;
        const unit = materialSelect.selectedOptions[0].getAttribute('data-unit');

        if (quantity) {
            if (unit && unit.trim() !== '') {
                materialElement.textContent = `${quantity} ${unit} - ${materialName}`;
            } else {
                materialElement.textContent = `${quantity} وحدة - ${materialName}`;
            }
        } else {
            materialElement.textContent = materialName;
        }
    }
}

// Set default arrival time
function setDefaultArrivalTime() {
    const arrivalInput = document.getElementById('arrival_time');
    if (!arrivalInput.value) {
        const now = new Date();
        const formatted = now.toISOString().slice(0, 16);
        arrivalInput.value = formatted;
        updateSummary(); // Update summary when setting default time
    }
}

// Document ready
document.addEventListener('DOMContentLoaded', function() {
    // Set default arrival time to current time
    setDefaultArrivalTime();

    // Add event listeners for location selection
    document.getElementById('loading_location_id').addEventListener('change', function() {
        validateStep2();
        updateSummary();
    });

    document.getElementById('unloading_location_id').addEventListener('change', function() {
        validateStep2();
        updateSummary();
    });

    // Add event listeners for trip details
    document.getElementById('arrival_time').addEventListener('change', updateSummary);

    // Add event listeners for material and quantity
    document.getElementById('material_id').addEventListener('change', function() {
        updateQuantityUnit();
        updateSummary();
    });

    document.getElementById('quantity').addEventListener('input', updateSummary);
});
</script>
@endpush
@endsection
