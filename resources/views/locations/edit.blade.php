@extends('layouts.app')

@section('title', 'تعديل الموقع: ' . $location->name)

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('locations.show', $location) }}"
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تعديل الموقع</h1>
                <p class="text-gray-600 mt-1">تحديث بيانات الموقع: {{ $location->name }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <form action="{{ route('locations.update', $location) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        اسم الموقع <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $location->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسم الموقع"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="location_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        نوع الموقع <span class="text-red-500">*</span>
                    </label>
                    <select id="location_type_id"
                            name="location_type_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('location_type_id') border-red-500 @enderror"
                            required>
                        <option value="">اختر نوع الموقع</option>
                        @foreach($locationTypes as $locationType)
                            <option value="{{ $locationType->id }}"
                                    {{ old('location_type_id', $location->location_type_id) == $locationType->id ? 'selected' : '' }}
                                    data-color="{{ $locationType->color }}"
                                    data-icon="{{ $locationType->icon }}">
                                {{ $locationType->name }}
                                @if($locationType->description)
                                    - {{ $locationType->description }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('location_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        حالة الموقع <span class="text-red-500">*</span>
                    </label>
                    <select id="status"
                            name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('status') border-red-500 @enderror"
                            required>
                        <option value="">اختر حالة الموقع</option>
                        <option value="active" {{ old('status', $location->status) === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('status', $location->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="under_construction" {{ old('status', $location->status) === 'under_construction' ? 'selected' : '' }}>تحت الإنشاء</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Area Size -->
                <div>
                    <label for="area_size" class="block text-sm font-medium text-gray-700 mb-2">
                        المساحة (متر مربع)
                    </label>
                    <input type="number"
                           id="area_size"
                           name="area_size"
                           value="{{ old('area_size', $location->area_size) }}"
                           step="0.01"
                           min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('area_size') border-red-500 @enderror"
                           placeholder="أدخل المساحة">
                    @error('area_size')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Location Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">معلومات الموقع</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            المدينة
                        </label>
                        <input type="text"
                               id="city"
                               name="city"
                               value="{{ old('city', $location->city) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('city') border-red-500 @enderror"
                               placeholder="أدخل اسم المدينة">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Region -->
                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700 mb-2">
                            المنطقة
                        </label>
                        <input type="text"
                               id="region"
                               name="region"
                               value="{{ old('region', $location->region) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('region') border-red-500 @enderror"
                               placeholder="أدخل اسم المنطقة">
                        @error('region')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        العنوان التفصيلي
                    </label>
                    <textarea id="address"
                              name="address"
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('address') border-red-500 @enderror"
                              placeholder="أدخل العنوان التفصيلي للموقع">{{ old('address', $location->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Coordinates -->
                <div>
                    <label for="coordinates" class="block text-sm font-medium text-gray-700 mb-2">
                        الإحداثيات الجغرافية
                    </label>
                    <div class="flex gap-3">
                        <input type="text"
                               id="coordinates"
                               name="coordinates"
                               value="{{ old('coordinates', $location->coordinates) }}"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('coordinates') border-red-500 @enderror"
                               placeholder="مثال: 24.7136, 46.6753"
                               dir="ltr">
                        <button type="button"
                                id="getCurrentLocation"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-map-pin-line"></i>
                            الموقع الحالي
                        </button>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">يمكنك الحصول على الإحداثيات من خرائط جوجل أو الضغط على "الموقع الحالي"</p>
                    @error('coordinates')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        الوصف
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror"
                              placeholder="أدخل وصف الموقع ومعلومات إضافية">{{ old('description', $location->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Management Information -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">معلومات الإدارة</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Manager Selection -->
                    <div>
                        <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المسؤول
                        </label>
                        <div class="relative">
                            <select id="manager_id"
                                    name="manager_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white @error('manager_id') border-red-500 @enderror"
                                    onchange="updateManagerInfo(this)">
                                <option value="">اختر المسؤول</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                            data-name="{{ $employee->name }}"
                                            data-phone="{{ $employee->phone ?? '' }}"
                                            data-position="{{ $employee->position ?? '' }}"
                                            {{ old('manager_id', $location->manager_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }} - {{ $employee->position ?? 'غير محدد' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-arrow-down-s-line text-gray-400"></i>
                            </div>
                        </div>
                        <!-- Search Box -->
                        <input type="text"
                               id="manager_search"
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg mt-2 text-sm"
                               placeholder="ابحث في القائمة..."
                               onkeyup="filterEmployees(this.value)">

                        <input type="hidden" id="manager_name" name="manager_name" value="{{ old('manager_name', $location->manager_name) }}">
                        <input type="hidden" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $location->contact_phone) }}">

                        @error('manager_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Phone Display -->
                    <div>
                        <label for="contact_phone_display" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم الاتصال
                        </label>
                        <input type="text"
                               id="contact_phone_display"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                               placeholder="سيتم ملء هذا الحقل تلقائياً"
                               value="{{ old('contact_phone', $location->contact_phone) }}"
                               readonly>
                        <p class="text-sm text-gray-500 mt-1">سيتم تعبئة هذا الحقل تلقائياً عند اختيار الموظف</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('locations.show', $location) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-close-line"></i>
                    إلغاء
                </a>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-save-line"></i>
                    حفظ التحديثات
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Location Features -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const getCurrentLocationBtn = document.getElementById('getCurrentLocation');
    const coordinatesInput = document.getElementById('coordinates');

    if (getCurrentLocationBtn && coordinatesInput) {
        getCurrentLocationBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                // Update button state
                getCurrentLocationBtn.disabled = true;
                getCurrentLocationBtn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> جاري التحديد...';

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude.toFixed(6);
                        const lng = position.coords.longitude.toFixed(6);
                        coordinatesInput.value = `${lat}, ${lng}`;

                        // Reset button
                        getCurrentLocationBtn.disabled = false;
                        getCurrentLocationBtn.innerHTML = '<i class="ri-map-pin-line"></i> الموقع الحالي';

                        // Show success message
                        showNotification('تم تحديد الموقع بنجاح', 'success');
                    },
                    function(error) {
                        console.error('Error getting location:', error);

                        // Reset button
                        getCurrentLocationBtn.disabled = false;
                        getCurrentLocationBtn.innerHTML = '<i class="ri-map-pin-line"></i> الموقع الحالي';

                        // Show error message
                        showNotification('فشل في تحديد الموقع. تأكد من السماح بالوصول للموقع.', 'error');
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                showNotification('المتصفح لا يدعم تحديد الموقع الجغرافي', 'error');
            }
        });
    }

    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 left-4 z-50 max-w-sm w-full bg-white border rounded-lg shadow-lg p-4 ${
            type === 'success' ? 'border-green-200' : 'border-red-200'
        }`;

        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="ri-${type === 'success' ? 'check-circle' : 'error-warning'}-line text-xl ${
                    type === 'success' ? 'text-green-600' : 'text-red-600'
                }"></i>
                <span class="text-gray-900">${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Update manager info when dropdown selection changes
    window.updateManagerInfo = function(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const managerNameInput = document.getElementById('manager_name');
        const contactPhoneInput = document.getElementById('contact_phone');
        const contactPhoneDisplay = document.getElementById('contact_phone_display');

        if (selectedOption.value) {
            managerNameInput.value = selectedOption.dataset.name;
            contactPhoneInput.value = selectedOption.dataset.phone;
            contactPhoneDisplay.value = selectedOption.dataset.phone;
        } else {
            managerNameInput.value = '';
            contactPhoneInput.value = '';
            contactPhoneDisplay.value = '';
        }
    };

    // Filter employees in dropdown based on search
    window.filterEmployees = function(searchText) {
        const select = document.getElementById('manager_id');
        const options = select.getElementsByTagName('option');

        for (let i = 1; i < options.length; i++) { // Skip first option (placeholder)
            const option = options[i];
            const text = option.textContent.toLowerCase();
            const shouldShow = text.includes(searchText.toLowerCase());
            option.style.display = shouldShow ? 'block' : 'none';
        }
    };

    // Set initial values if there are selected values
    @if(old('manager_id', $location->manager_id))
        updateManagerInfo(document.getElementById('manager_id'));
    @endif
});
</script>
@endsection
