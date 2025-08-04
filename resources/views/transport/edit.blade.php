@extends('layouts.app')

@section('title', 'تعديل الرحلة')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 p-6" dir="rtl">
    <!-- Header -->
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('transport.index') }}"
               class="bg-white p-3 rounded-xl shadow-sm border text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div class="bg-white/80 backdrop-blur-sm rounded-xl px-6 py-4 border shadow-sm flex-1">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-2 rounded-lg text-white">
                        <i class="ri-edit-line text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            تعديل الرحلة
                        </h1>
                        <p class="text-gray-600 mt-1 flex items-center gap-2">
                            <i class="ri-truck-line text-sm"></i>
                            تعديل بيانات رحلة {{ $transport->vehicle_number }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                <div class="flex items-center gap-3 text-white">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="ri-truck-line text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">بيانات الرحلة</h2>
                        <p class="opacity-90 text-sm">إدارة وتحديث معلومات رحلة النقل</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('transport.update', $transport) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Vehicle Information Section -->
                <div class="relative bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-2xl p-8 border-2 border-blue-200/50 shadow-lg backdrop-blur-sm">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 rounded-t-2xl"></div>

                    <div class="flex items-center gap-4 mb-8">
                        <div class="relative">
                            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-4 rounded-2xl text-white shadow-lg">
                                <i class="ri-truck-line text-2xl"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                معلومات المركبة
                            </h3>
                            <p class="text-gray-600 text-sm mt-1">تفاصيل المركبة والسائق</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="vehicle_type" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <i class="ri-truck-line text-blue-600"></i>
                                نوع المركبة <span class="text-red-500 text-lg">*</span>
                            </label>
                            <div class="relative group">
                                <select name="vehicle_type" id="vehicle_type"
                                        class="w-full h-14 pr-12 pl-4 text-base rounded-2xl border-2 border-gray-200 bg-white/80 backdrop-blur-sm
                                               shadow-sm hover:shadow-md focus:shadow-lg focus:border-blue-500 focus:bg-white
                                               transition-all duration-300 appearance-none cursor-pointer
                                               @error('vehicle_type') border-red-400 bg-red-50/30 @enderror" required>
                                    <option value="" class="text-gray-400">🚚 اختر نوع المركبة...</option>
                                    <option value="شاحنة صغيرة" {{ (old('vehicle_type', $transport->vehicle_type) == 'شاحنة صغيرة') ? 'selected' : '' }}>🚚 شاحنة صغيرة</option>
                                    <option value="شاحنة متوسطة" {{ (old('vehicle_type', $transport->vehicle_type) == 'شاحنة متوسطة') ? 'selected' : '' }}>🚛 شاحنة متوسطة</option>
                                    <option value="شاحنة كبيرة" {{ (old('vehicle_type', $transport->vehicle_type) == 'شاحنة كبيرة') ? 'selected' : '' }}>� شاحنة كبيرة</option>
                                    <option value="شاحنة خارجية" {{ (old('vehicle_type', $transport->vehicle_type) == 'شاحنة خارجية') ? 'selected' : '' }}>🚛 شاحنة خارجية</option>
                                    <option value="قلاب" {{ (old('vehicle_type', $transport->vehicle_type) == 'قلاب') ? 'selected' : '' }}>🏗️ قلاب</option>
                                    <option value="خلاطة خرسانة" {{ (old('vehicle_type', $transport->vehicle_type) == 'خلاطة خرسانة') ? 'selected' : '' }}>🚧 خلاطة خرسانة</option>
                                    <option value="رافعة" {{ (old('vehicle_type', $transport->vehicle_type) == 'رافعة') ? 'selected' : '' }}>🏗️ رافعة</option>
                                    <option value="حفار" {{ (old('vehicle_type', $transport->vehicle_type) == 'حفار') ? 'selected' : '' }}>🚜 حفار</option>
                                    <option value="أخرى" {{ (old('vehicle_type', $transport->vehicle_type) == 'أخرى') ? 'selected' : '' }}>🔧 أخرى</option>
                                </select>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-arrow-down-s-line text-gray-400 text-xl group-hover:text-blue-500 transition-colors duration-200"></i>
                                </div>
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-truck-line text-blue-500 text-lg"></i>
                                </div>
                            </div>
                            @error('vehicle_type')
                                <div class="flex items-center gap-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="vehicle_number" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
                                <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                <i class="ri-hashtag text-indigo-600"></i>
                                رقم المركبة <span class="text-red-500 text-lg">*</span>
                            </label>
                            <div class="relative group">
                                <input type="text" name="vehicle_number" id="vehicle_number"
                                       value="{{ old('vehicle_number', $transport->vehicle_number) }}"
                                       class="w-full h-14 pr-12 pl-4 text-base rounded-2xl border-2 border-gray-200 bg-white/80 backdrop-blur-sm
                                              shadow-sm hover:shadow-md focus:shadow-lg focus:border-indigo-500 focus:bg-white
                                              transition-all duration-300 placeholder-gray-400
                                              @error('vehicle_number') border-red-400 bg-red-50/30 @enderror"
                                       placeholder="مثال: أ ب ج 1234" required>
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-hashtag text-indigo-500 text-lg"></i>
                                </div>
                            </div>
                            @error('vehicle_number')
                                <div class="flex items-center gap-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <div class="lg:col-span-2 space-y-2">
                            <label for="driver_name" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
                                <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                <i class="ri-user-line text-purple-600"></i>
                                اسم السائق <span class="text-red-500 text-lg">*</span>
                            </label>
                            <div class="relative group">
                                <input type="text" name="driver_name" id="driver_name"
                                       value="{{ old('driver_name', $transport->driver_name) }}"
                                       class="w-full h-14 pr-12 pl-4 text-base rounded-2xl border-2 border-gray-200 bg-white/80 backdrop-blur-sm
                                              shadow-sm hover:shadow-md focus:shadow-lg focus:border-purple-500 focus:bg-white
                                              transition-all duration-300 placeholder-gray-400
                                              @error('driver_name') border-red-400 bg-red-50/30 @enderror"
                                       placeholder="أدخل اسم السائق كاملاً" required>
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-user-line text-purple-500 text-lg"></i>
                                </div>
                            </div>
                            @error('driver_name')
                                <div class="flex items-center gap-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>                <!-- Location Information Section -->
                <div class="relative bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 rounded-2xl p-8 border-2 border-emerald-200/50 shadow-lg backdrop-blur-sm">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 rounded-t-2xl"></div>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="relative">
                            <div class="bg-gradient-to-br from-emerald-600 to-green-700 p-4 rounded-2xl text-white shadow-lg">
                                <i class="ri-map-pin-line text-2xl"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">
                                مسار الرحلة
                            </h3>
                            <p class="text-gray-600 text-sm mt-1">نقاط التحميل والتفريغ</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="loading_location_id" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <i class="ri-upload-line text-emerald-600"></i>
                                موقع التحميل <span class="text-red-500 text-lg">*</span>
                            </label>
                            <div class="relative group">
                                <select name="loading_location_id" id="loading_location_id"
                                        class="w-full h-14 pr-12 pl-4 text-base rounded-2xl border-2 border-gray-200 bg-white/80 backdrop-blur-sm
                                               shadow-sm hover:shadow-md focus:shadow-lg focus:border-emerald-500 focus:bg-white
                                               transition-all duration-300 appearance-none cursor-pointer
                                               @error('loading_location_id') border-red-400 bg-red-50/30 @enderror" required>
                                    <option value="" class="text-gray-400">📍 اختر موقع التحميل...</option>
                                    <option value="1" {{ (old('loading_location_id', $transport->loading_location_id) == 1) ? 'selected' : '' }}>🏢 الفرع الرئيسي</option>
                                    <option value="2" {{ (old('loading_location_id', $transport->loading_location_id) == 2) ? 'selected' : '' }}>🏗️ كسارة البطيحانية</option>
                                    <option value="3" {{ (old('loading_location_id', $transport->loading_location_id) == 3) ? 'selected' : '' }}>📍 مدخل الذيبية</option>
                                    <option value="4" {{ (old('loading_location_id', $transport->loading_location_id) == 4) ? 'selected' : '' }}>🚧 خلاطة طريق رفحاء</option>
                                    <option value="5" {{ (old('loading_location_id', $transport->loading_location_id) == 5) ? 'selected' : '' }}>🏪 مستودع الرياض الرئيسي</option>
                                    <option value="7" {{ (old('loading_location_id', $transport->loading_location_id) == 7) ? 'selected' : '' }}>🏪 مستودع جدة</option>
                                    <option value="8" {{ (old('loading_location_id', $transport->loading_location_id) == 8) ? 'selected' : '' }}>🏗️ موقع مشروع الدمام</option>
                                </select>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-arrow-down-s-line text-gray-400 text-xl group-hover:text-emerald-500 transition-colors duration-200"></i>
                                </div>
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-upload-line text-emerald-500 text-lg"></i>
                                </div>
                            </div>
                            @error('loading_location_id')
                                <div class="flex items-center gap-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="unloading_location_id" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <i class="ri-download-line text-green-600"></i>
                                موقع التفريغ <span class="text-red-500 text-lg">*</span>
                            </label>
                            <div class="relative group">
                                <select name="unloading_location_id" id="unloading_location_id"
                                        class="w-full h-14 pr-12 pl-4 text-base rounded-2xl border-2 border-gray-200 bg-white/80 backdrop-blur-sm
                                               shadow-sm hover:shadow-md focus:shadow-lg focus:border-green-500 focus:bg-white
                                               transition-all duration-300 appearance-none cursor-pointer
                                               @error('unloading_location_id') border-red-400 bg-red-50/30 @enderror" required>
                                    <option value="" class="text-gray-400">📍 اختر موقع التفريغ...</option>
                                    <option value="1" {{ (old('unloading_location_id', $transport->unloading_location_id) == 1) ? 'selected' : '' }}>🏢 الفرع الرئيسي</option>
                                    <option value="2" {{ (old('unloading_location_id', $transport->unloading_location_id) == 2) ? 'selected' : '' }}>🏗️ كسارة البطيحانية</option>
                                    <option value="3" {{ (old('unloading_location_id', $transport->unloading_location_id) == 3) ? 'selected' : '' }}>📍 مدخل الذيبية</option>
                                    <option value="4" {{ (old('unloading_location_id', $transport->unloading_location_id) == 4) ? 'selected' : '' }}>🚧 خلاطة طريق رفحاء</option>
                                    <option value="5" {{ (old('unloading_location_id', $transport->unloading_location_id) == 5) ? 'selected' : '' }}>🏪 مستودع الرياض الرئيسي</option>
                                    <option value="7" {{ (old('unloading_location_id', $transport->unloading_location_id) == 7) ? 'selected' : '' }}>🏪 مستودع جدة</option>
                                    <option value="8" {{ (old('unloading_location_id', $transport->unloading_location_id) == 8) ? 'selected' : '' }}>🏗️ موقع مشروع الدمام</option>
                                </select>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-arrow-down-s-line text-gray-400 text-xl group-hover:text-green-500 transition-colors duration-200"></i>
                                </div>
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-download-line text-green-500 text-lg"></i>
                                </div>
                            </div>
                            @error('unloading_location_id')
                                <div class="flex items-center gap-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Cargo Information Section -->
                <!-- Cargo Information Section -->
                <div class="relative bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 rounded-2xl p-8 border-2 border-amber-200/50 shadow-lg backdrop-blur-sm">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-yellow-500 rounded-t-2xl"></div>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="relative">
                            <div class="bg-gradient-to-br from-amber-600 to-orange-700 p-4 rounded-2xl text-white shadow-lg">
                                <i class="ri-package-line text-2xl"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                                معلومات البضاعة والمواد
                            </h3>
                            <p class="text-gray-600 text-sm mt-1">تفاصيل المواد المنقولة</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="material_id" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
                                <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                                <i class="ri-stack-line text-amber-600"></i>
                                نوع المادة
                            </label>
                            <div class="relative group">
                                <select name="material_id" id="material_id"
                                        class="w-full h-14 pr-12 pl-4 text-base rounded-2xl border-2 border-gray-200 bg-white/80 backdrop-blur-sm
                                               shadow-sm hover:shadow-md focus:shadow-lg focus:border-amber-500 focus:bg-white
                                               transition-all duration-300 appearance-none cursor-pointer
                                               @error('material_id') border-red-400 bg-red-50/30 @enderror">
                                    <option value="" class="text-gray-400">🧱 اختر نوع المادة (اختياري)...</option>
                                    <option value="16" {{ (old('material_id', $transport->material_id) == 16) ? 'selected' : '' }}>🛣️ اسفلت</option>
                                    <option value="17" {{ (old('material_id', $transport->material_id) == 17) ? 'selected' : '' }}>🪨 بحص 3/8</option>
                                    <option value="18" {{ (old('material_id', $transport->material_id) == 18) ? 'selected' : '' }}>⛽ ديزل</option>
                                    <option value="19" {{ (old('material_id', $transport->material_id) == 19) ? 'selected' : '' }}>🪨 بحص 3/4</option>
                                    <option value="20" {{ (old('material_id', $transport->material_id) == 20) ? 'selected' : '' }}>🏖️ رمل</option>
                                    <option value="21" {{ (old('material_id', $transport->material_id) == 21) ? 'selected' : '' }}>🏗️ خرسانة</option>
                                    <option value="23" {{ (old('material_id', $transport->material_id) == 23) ? 'selected' : '' }}>🏗️ أسمنت</option>
                                    <option value="24" {{ (old('material_id', $transport->material_id) == 24) ? 'selected' : '' }}>🪨 حصى</option>
                                    <option value="25" {{ (old('material_id', $transport->material_id) == 25) ? 'selected' : '' }}>🏖️ الرمل الاحمر</option>
                                </select>
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-arrow-down-s-line text-gray-400 text-xl group-hover:text-amber-500 transition-colors duration-200"></i>
                                </div>
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-stack-line text-amber-500 text-lg"></i>
                                </div>
                            </div>
                            @error('material_id')
                                <div class="flex items-center gap-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="quantity" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
                                <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                <i class="ri-calculator-line text-orange-600"></i>
                                الكمية (بالطن أو المتر المكعب)
                            </label>
                            <div class="relative group">
                                <input type="number" name="quantity" id="quantity"
                                       value="{{ old('quantity', $transport->quantity) }}"
                                       step="0.01" min="0"
                                       class="w-full h-14 pr-12 pl-4 text-base rounded-2xl border-2 border-gray-200 bg-white/80 backdrop-blur-sm
                                              shadow-sm hover:shadow-md focus:shadow-lg focus:border-orange-500 focus:bg-white
                                              transition-all duration-300 placeholder-gray-400
                                              @error('quantity') border-red-400 bg-red-50/30 @enderror"
                                       placeholder="0.00">
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-calculator-line text-orange-500 text-lg"></i>
                                </div>
                            </div>
                            @error('quantity')
                                <div class="flex items-center gap-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                        <div class="lg:col-span-2 space-y-2">
                            <label for="cargo_description" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                <i class="ri-file-text-line text-yellow-600"></i>
                                وصف البضاعة/المواد
                            </label>
                            <div class="relative group">
                                <textarea name="cargo_description" id="cargo_description" rows="4"
                                          class="w-full pr-12 pl-4 text-base rounded-2xl border-2 border-gray-200 bg-white/80 backdrop-blur-sm
                                                 shadow-sm hover:shadow-md focus:shadow-lg focus:border-yellow-500 focus:bg-white
                                                 transition-all duration-300 placeholder-gray-400
                                                 @error('cargo_description') border-red-400 bg-red-50/30 @enderror"
                                          placeholder="أدخل وصف تفصيلي للمواد المنقولة...">{{ old('cargo_description', $transport->cargo_description) }}</textarea>
                                <div class="absolute right-4 top-4 pointer-events-none">
                                    <i class="ri-file-text-line text-yellow-500 text-lg"></i>
                                </div>
                            </div>
                            @error('cargo_description')
                                <div class="flex items-center gap-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Timing Information Section -->
                <div class="relative bg-gradient-to-br from-violet-50 via-purple-50 to-indigo-50 rounded-2xl p-8 border-2 border-violet-200/50 shadow-lg backdrop-blur-sm">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-violet-500 via-purple-500 to-indigo-500 rounded-t-2xl"></div>

                    <div class="flex items-center gap-4 mb-8">
                        <div class="relative">
                            <div class="bg-gradient-to-br from-violet-600 to-purple-700 p-4 rounded-2xl text-white shadow-lg">
                                <i class="ri-time-line text-2xl"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-orange-400 rounded-full animate-pulse"></div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold bg-gradient-to-r from-violet-600 to-purple-600 bg-clip-text text-transparent">
                                موعد الوصول
                            </h3>
                            <p class="text-gray-600 text-sm mt-1">الوقت المتوقع للوصول</p>
                        </div>
                    </div>

                    <div class="max-w-md">
                        <div class="space-y-2">
                            <label for="arrival_time" class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
                                <div class="w-2 h-2 bg-violet-500 rounded-full"></div>
                                <i class="ri-login-circle-line text-violet-600"></i>
                                موعد الوصول المتوقع
                            </label>
                            <div class="relative group">
                                <input type="datetime-local" name="arrival_time" id="arrival_time"
                                       value="{{ old('arrival_time', $transport->arrival_time?->format('Y-m-d\TH:i')) }}"
                                       class="w-full h-14 pr-12 pl-4 text-base rounded-2xl border-2 border-gray-200 bg-white/80 backdrop-blur-sm
                                              shadow-sm hover:shadow-md focus:shadow-lg focus:border-violet-500 focus:bg-white
                                              transition-all duration-300
                                              @error('arrival_time') border-red-400 bg-red-50/30 @enderror">
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="ri-calendar-event-line text-violet-500 text-lg"></i>
                                </div>
                            </div>
                            @error('arrival_time')
                                <div class="flex items-center gap-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Notes Section -->
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-gradient-to-r from-gray-500 to-slate-600 p-2 rounded-lg text-white">
                            <i class="ri-sticky-note-line text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">ملاحظات إضافية</h3>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="ri-chat-3-line ml-1"></i>
                            ملاحظات خاصة بالرحلة
                        </label>
                        <textarea name="notes"
                                  id="notes"
                                  rows="4"
                                  class="w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition-all duration-200 @error('notes') border-red-300 @enderror"
                                  placeholder="أضف أي ملاحظات أو تعليمات خاصة بهذه الرحلة...">{{ old('notes', $transport->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-8 border-t-2 border-gray-200">
                    <a href="{{ route('transport.index') }}"
                       class="bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600 text-white px-8 py-3 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center gap-2">
                        <i class="ri-close-line"></i>
                        إلغاء
                    </a>
                    <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-3 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center gap-2">
                        <i class="ri-save-line"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification for Success -->
@if(session('success'))
<div id="successToast" class="fixed top-4 left-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-xl shadow-xl border border-green-300 z-50 transform translate-x-full transition-transform duration-300">
    <div class="flex items-center gap-3">
        <div class="bg-white/20 p-2 rounded-lg">
            <i class="ri-check-line text-xl"></i>
        </div>
        <div>
            <h4 class="font-bold">تم بنجاح!</h4>
            <p class="text-sm opacity-90">{{ session('success') }}</p>
        </div>
        <button onclick="closeToast()" class="mr-4 text-white/80 hover:text-white">
            <i class="ri-close-line"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.getElementById('successToast');
        if (toast) {
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 300);

            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 5000);
        }
    });

    function closeToast() {
        document.getElementById('successToast').classList.add('translate-x-full');
    }
</script>
@endif

<!-- Form Enhancement Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form field animations
    const inputs = document.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('transform', 'scale-[1.02]');
            this.parentElement.style.transition = 'all 0.2s ease';
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('transform', 'scale-[1.02]');
        });
    });

    // Loading Location and Unloading Location interaction
    const loadingSelect = document.getElementById('loading_location_id');
    const unloadingSelect = document.getElementById('unloading_location_id');

    loadingSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        // Highlight different option in unloading location
        if (selectedValue && unloadingSelect.value === selectedValue) {
            showLocationWarning();
        }
    });

    unloadingSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        // Highlight different option in loading location
        if (selectedValue && loadingSelect.value === selectedValue) {
            showLocationWarning();
        }
    });

    function showLocationWarning() {
        const warningDiv = document.createElement('div');
        warningDiv.className = 'fixed top-4 right-4 bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        warningDiv.innerHTML = '<i class="ri-alert-line mr-2"></i>تنبيه: موقع التحميل والتفريغ متشابهان';
        document.body.appendChild(warningDiv);

        setTimeout(() => {
            warningDiv.remove();
        }, 3000);
    }

    // Auto-save draft functionality
    let saveTimeout;
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                saveDraft();
            }, 2000);
        });
    });

    function saveDraft() {
        const formData = new FormData(form);
        const draftData = {};

        for (let [key, value] of formData.entries()) {
            draftData[key] = value;
        }

        localStorage.setItem('transport_edit_draft_{{ $transport->id }}', JSON.stringify(draftData));

        // Show draft saved indicator
        const indicator = document.createElement('div');
        indicator.className = 'fixed bottom-4 left-4 bg-blue-500 text-white px-3 py-2 rounded-lg text-sm opacity-0 transition-opacity duration-300';
        indicator.innerHTML = '<i class="ri-save-line mr-1"></i>تم حفظ المسودة';
        document.body.appendChild(indicator);

        setTimeout(() => indicator.style.opacity = '1', 100);
        setTimeout(() => {
            indicator.style.opacity = '0';
            setTimeout(() => indicator.remove(), 300);
        }, 2000);
    }

    // Load draft on page load
    const savedDraft = localStorage.getItem('transport_edit_draft_{{ $transport->id }}');
    if (savedDraft) {
        const draftData = JSON.parse(savedDraft);

        for (let [key, value] of Object.entries(draftData)) {
            const element = form.querySelector(`[name="${key}"]`);
            if (element && !element.value) {
                element.value = value;
            }
        }
    }

    // Clear draft on successful form submission
    form.addEventListener('submit', function() {
        localStorage.removeItem('transport_edit_draft_{{ $transport->id }}');
    });
});
</script>
@endsection
