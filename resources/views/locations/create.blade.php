@extends('layouts.app')

@section('title', 'إضافة موقع جديد - شركة الأبراج للمقاولات')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('locations.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="ri-arrow-right-line text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة موقع جديد</h1>
                        <p class="text-gray-600">أضف موقع جديد لإدارة المواقع والمشاريع</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('locations.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Basic Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">المعلومات الأساسية</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم الموقع <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="مثال: مشروع الرياض الجديد" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                                نوع الموقع <span class="text-red-500">*</span>
                            </label>
                            <select id="location_type_id" name="location_type_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('location_type_id') border-red-500 @enderror"
                                required>
                                <option value="">اختر نوع الموقع</option>
                                @foreach ($locationTypes as $locationType)
                                    <option value="{{ $locationType->id }}"
                                        {{ old('location_type_id') == $locationType->id ? 'selected' : '' }}
                                        data-color="{{ $locationType->color }}" data-icon="{{ $locationType->icon }}">
                                        {{ $locationType->name }}
                                        @if ($locationType->description)
                                            - {{ $locationType->description }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('location_type_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Selection - Only shown for "موقع" type -->
                        <div id="project_selection" style="display: none;">
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                المشروع <span class="text-red-500">*</span>
                            </label>
                            <select id="project_id" name="project_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('project_id') border-red-500 @enderror">
                                <option value="">اختر المشروع</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                        @if ($project->client_name)
                                            - {{ $project->client_name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Details -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل الموقع</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                placeholder="العنوان التفصيلي للموقع">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">المدينة</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('city') border-red-500 @enderror"
                                placeholder="مثال: الرياض">
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="region" class="block text-sm font-medium text-gray-700 mb-2">المنطقة</label>
                            <input type="text" id="region" name="region" value="{{ old('region') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('region') border-red-500 @enderror"
                                placeholder="مثال: منطقة الرياض">
                            @error('region')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="coordinates" class="block text-sm font-medium text-gray-700 mb-2">الإحداثيات
                                (GPS)</label>
                            <input type="text" id="coordinates" name="coordinates" value="{{ old('coordinates') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('coordinates') border-red-500 @enderror"
                                placeholder="مثال: 24.7136, 46.6753">
                            @error('coordinates')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="area_size" class="block text-sm font-medium text-gray-700 mb-2">المساحة (متر
                                مربع)</label>
                            <input type="number" id="area_size" name="area_size" value="{{ old('area_size') }}"
                                min="0" step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('area_size') border-red-500 @enderror"
                                placeholder="مثال: 5000">
                            @error('area_size')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Management Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الإدارة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم المسؤول
                            </label>
                            <div class="relative">
                                <!-- Search Input -->
                                <input type="text" id="manager_search" placeholder="ابحث عن المسؤول..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('manager_id') border-red-500 @enderror"
                                    autocomplete="off" onclick="toggleManagerDropdown(true)"
                                    oninput="filterManagers(this.value)">

                                <!-- Hidden Select for Form Submission -->
                                <select id="manager_id" name="manager_id" class="hidden">
                                    <option value="">اختر المسؤول</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" data-name="{{ $employee->name }}"
                                            data-phone="{{ $employee->phone ?? '' }}"
                                            data-position="{{ $employee->position ?? '' }}"
                                            {{ old('manager_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} - {{ $employee->position ?? 'غير محدد' }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Dropdown Icon -->
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>

                                <!-- Dropdown List -->
                                <div id="manager_dropdown"
                                    class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg hidden max-h-60 overflow-y-auto">
                                    <div class="p-2">
                                        @foreach ($employees as $employee)
                                            <div class="manager-option cursor-pointer hover:bg-gray-50 rounded-lg p-3 transition-colors"
                                                data-value="{{ $employee->id }}" data-name="{{ $employee->name }}"
                                                data-phone="{{ $employee->phone ?? '' }}"
                                                data-position="{{ $employee->position ?? '' }}"
                                                data-search-text="{{ strtolower($employee->name . ' ' . ($employee->position ?? '')) }}"
                                                onclick="selectManager(this)">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $employee->name }}</div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $employee->position ?? 'غير محدد' }}</div>
                                                    </div>
                                                    @if ($employee->phone)
                                                        <div class="text-sm text-gray-400">{{ $employee->phone }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- Search Box -->
                            <input type="text" id="manager_search"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg mt-2 text-sm"
                                placeholder="ابحث في القائمة..." onkeyup="filterEmployees(this.value)">

                            <input type="hidden" id="manager_name" name="manager_name"
                                value="{{ old('manager_name') }}">
                            <input type="hidden" id="contact_phone" name="contact_phone"
                                value="{{ old('contact_phone') }}">

                            @error('manager_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @error('manager_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_phone_display" class="block text-sm font-medium text-gray-700 mb-2">رقم
                            الاتصال</label>
                        <input type="text" id="contact_phone_display"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50"
                            placeholder="سيتم ملء هذا الحقل تلقائياً" readonly>
                        <p class="text-sm text-gray-500 mt-1">سيتم تعبئة هذا الحقل تلقائياً عند اختيار الموظف</p>
                    </div>
                </div>
        </div>

        <!-- Description -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات إضافية</h3>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('description') border-red-500 @enderror"
                    placeholder="وصف مفصل عن الموقع ومميزاته">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('locations.index') }}"
                class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                إلغاء
            </a>
            <button type="submit"
                class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-xl font-medium hover:from-red-700 hover:to-red-800 transition-all duration-200">
                إضافة الموقع
            </button>
        </div>
        </form>
    </div>
    </div>

    <script>
        // Update manager info when dropdown selection changes
        function updateManagerInfo(selectElement) {
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
        }

        // Manager Search Functions
        function toggleManagerDropdown(show) {
            const dropdown = document.getElementById('manager_dropdown');
            if (show) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        function filterManagers(searchText) {
            const options = document.querySelectorAll('.manager-option');
            const searchLower = searchText.toLowerCase();

            options.forEach(option => {
                const searchableText = option.dataset.searchText;
                if (searchableText.includes(searchLower)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        function selectManager(optionElement) {
            const managerSearch = document.getElementById('manager_search');
            const managerSelect = document.getElementById('manager_id');

            // Update search input
            managerSearch.value = optionElement.dataset.name + ' - ' + optionElement.dataset.position;

            // Update hidden select
            managerSelect.value = optionElement.dataset.value;

            // Hide dropdown
            toggleManagerDropdown(false);

            // Update manager info
            updateManagerInfo(managerSelect);
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const managerSearch = document.getElementById('manager_search');
            const dropdown = document.getElementById('manager_dropdown');

            if (!managerSearch.contains(event.target) && !dropdown.contains(event.target)) {
                toggleManagerDropdown(false);
            }
        });

        // Set initial value if old value exists
        document.addEventListener('DOMContentLoaded', function() {
            const managerSelect = document.getElementById('manager_id');
            const managerSearch = document.getElementById('manager_search');

            if (managerSelect.value) {
                const selectedOption = managerSelect.querySelector('option[value="' + managerSelect.value + '"]');
                if (selectedOption) {
                    managerSearch.value = selectedOption.dataset.name + ' - ' + selectedOption.dataset.position;
                }
            }
        });

        // Filter employees in dropdown based on search
        function filterEmployees(searchText) {
            const select = document.getElementById('manager_id');
            const options = select.getElementsByTagName('option');

            for (let i = 1; i < options.length; i++) { // Skip first option (placeholder)
                const option = options[i];
                const text = option.textContent.toLowerCase();
                const shouldShow = text.includes(searchText.toLowerCase());
                option.style.display = shouldShow ? 'block' : 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Set initial values if there are old values
            @if (old('manager_id'))
                updateManagerInfo(document.getElementById('manager_id'));
            @endif

            // Handle location type change to show/hide project selection and filter managers
            const locationTypeSelect = document.getElementById('location_type_id');
            const projectSelection = document.getElementById('project_selection');
            const projectSelect = document.getElementById('project_id');

            // Site managers data from server
            const siteManagers = @json($siteManagers->pluck('id')->toArray());

            function toggleProjectSelection() {
                const selectedOption = locationTypeSelect.options[locationTypeSelect.selectedIndex];
                const selectedText = selectedOption.text.toLowerCase();

                // Check if the selected location type contains "موقع" or "site"
                if (selectedText.includes('موقع') || selectedText.includes('site')) {
                    projectSelection.style.display = 'block';
                    projectSelect.setAttribute('required', 'required');

                    // Filter to show only site managers
                    filterManagersByLocationType(true);
                } else {
                    projectSelection.style.display = 'none';
                    projectSelect.removeAttribute('required');
                    projectSelect.value = ''; // Clear selection

                    // Show all managers
                    filterManagersByLocationType(false);
                }
            }

            function filterManagersByLocationType(isLocationSite) {
                const managerOptions = document.querySelectorAll('.manager-option');
                const managerSelect = document.getElementById('manager_id');

                managerOptions.forEach(option => {
                    const managerId = parseInt(option.dataset.value);

                    if (isLocationSite) {
                        // Show only site managers for "موقع" type
                        if (siteManagers.includes(managerId)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    } else {
                        // Show all managers for other types
                        option.style.display = 'block';
                    }
                });

                // Also filter the hidden select options
                const selectOptions = managerSelect.querySelectorAll('option');
                selectOptions.forEach(option => {
                    if (option.value === '') return; // Skip placeholder

                    const managerId = parseInt(option.value);

                    if (isLocationSite) {
                        if (siteManagers.includes(managerId)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    } else {
                        option.style.display = 'block';
                    }
                });

                // Clear current selection if it's not valid for the new filter
                const currentValue = parseInt(managerSelect.value);
                if (currentValue && isLocationSite && !siteManagers.includes(currentValue)) {
                    managerSelect.value = '';
                    document.getElementById('manager_search').value = '';
                }
            }

            // Initial check
            toggleProjectSelection();

            // Listen for changes
            locationTypeSelect.addEventListener('change', toggleProjectSelection);
        });
    </script>
@endsection
