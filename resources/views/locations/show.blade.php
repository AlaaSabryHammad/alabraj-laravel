@extends('layouts.app')

@section('title', 'تفاصيل الموقع: ' . $location->name)

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('locations.index') }}"
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $location->name }}</h1>
                <p class="text-gray-600 mt-1">تفاصيل الموقع</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('locations.edit', $location) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-edit-line"></i>
                تعديل
            </a>
            <form action="{{ route('locations.destroy', $location) }}" method="POST" class="inline"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الموقع؟')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-delete-bin-line"></i>
                    حذف
                </button>
            </form>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full
                        @if($location->status === 'active') bg-green-500
                        @elseif($location->status === 'inactive') bg-gray-500
                        @elseif($location->status === 'under_construction') bg-yellow-500
                        @else bg-red-500
                        @endif">
                    </div>
                    <span class="text-lg font-semibold text-gray-900">
                        @switch($location->status)
                            @case('active')
                                موقع نشط
                                @break
                            @case('inactive')
                                موقع غير نشط
                                @break
                            @case('under_construction')
                                تحت الإنشاء
                                @break
                            @default
                                {{ $location->status }}
                        @endswitch
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    @if($location->locationType)
                        <i class="{{ $location->locationType->icon }} text-xl"
                           style="color: {{ $location->locationType->color }}"></i>
                        <span class="font-medium"
                              style="color: {{ $location->locationType->color }}">
                            {{ $location->locationType->name }}
                        </span>
                        @if($location->locationType->description)
                            <span class="text-gray-500 text-sm">- {{ $location->locationType->description }}</span>
                        @endif
                    @else
                        <i class="ri-map-pin-line text-gray-600 text-xl"></i>
                        <span class="text-gray-700 font-medium">غير محدد</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Location Details -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل الموقع</h3>
                <div class="space-y-4">
                    @if($location->address)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">العنوان</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i class="ri-map-pin-line text-gray-600 mt-1"></i>
                                <span class="text-gray-900">{{ $location->address }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($location->city)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">المدينة</h4>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center gap-2">
                                    <i class="ri-building-2-line text-gray-600"></i>
                                    <span class="text-gray-900">{{ $location->city }}</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($location->region)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">المنطقة</h4>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center gap-2">
                                    <i class="ri-road-map-line text-gray-600"></i>
                                    <span class="text-gray-900">{{ $location->region }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($location->coordinates)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">الإحداثيات الجغرافية</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <i class="ri-navigation-line text-gray-600"></i>
                                <span class="text-gray-900 font-mono">{{ $location->coordinates }}</span>
                                <a href="https://maps.google.com/?q={{ $location->coordinates }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="ri-external-link-line"></i>
                                    عرض على الخريطة
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($location->area_size)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">المساحة</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <i class="ri-ruler-line text-gray-600"></i>
                                <span class="text-gray-900">{{ number_format($location->area_size) }} متر مربع</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($location->description)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">الوصف</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 leading-relaxed">{{ $location->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Equipment at Location -->
            @if($location->equipment && $location->equipment->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">المعدات في هذا الموقع</h3>
                <div class="space-y-3">
                    @foreach($location->equipment as $equipment)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden
                                @if($equipment->status === 'available') bg-green-100
                                @elseif($equipment->status === 'in_use') bg-blue-100
                                @elseif($equipment->status === 'maintenance') bg-yellow-100
                                @else bg-red-100
                                @endif">
                                <i class="ri-tools-line
                                    @if($equipment->status === 'available') text-green-600
                                    @elseif($equipment->status === 'in_use') text-blue-600
                                    @elseif($equipment->status === 'maintenance') text-yellow-600
                                    @else text-red-600
                                    @endif"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $equipment->name }}</div>
                                <div class="text-sm text-gray-500">{{ $equipment->type }} - {{ $equipment->serial_number }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @php
                                $statusConfig = [
                                    'available' => ['text' => 'متاحة', 'class' => 'bg-green-100 text-green-800'],
                                    'in_use' => ['text' => 'قيد الاستخدام', 'class' => 'bg-blue-100 text-blue-800'],
                                    'maintenance' => ['text' => 'في الصيانة', 'class' => 'bg-yellow-100 text-yellow-800'],
                                    'out_of_order' => ['text' => 'خارج الخدمة', 'class' => 'bg-red-100 text-red-800']
                                ];
                                $status = $statusConfig[$equipment->status] ?? ['text' => $equipment->status, 'class' => 'bg-gray-100 text-gray-800'];
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $status['class'] }}">
                                {{ $status['text'] }}
                            </span>
                            <a href="{{ route('equipment.show', $equipment) }}"
                               class="text-blue-600 hover:text-blue-800">
                                <i class="ri-eye-line"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Management Info -->
            @if($location->manager_name || $location->contact_phone)
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الإدارة</h3>
                <div class="space-y-4">
                    @if($location->manager_name)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">المسؤول</h4>
                        <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                            <div class="flex items-center gap-2">
                                <i class="ri-user-line text-blue-600"></i>
                                <span class="text-blue-900 font-medium">{{ $location->manager_name }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($location->contact_phone)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">رقم الاتصال</h4>
                        <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                            <div class="flex items-center gap-2">
                                <i class="ri-phone-line text-green-600"></i>
                                <a href="tel:{{ $location->contact_phone }}"
                                   class="text-green-900 font-medium hover:text-green-700">
                                    {{ $location->contact_phone }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إحصائيات سريعة</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">عدد المعدات</span>
                        <span class="text-lg font-bold text-gray-900">{{ $location->equipment->count() }}</span>
                    </div>
                    @if($location->equipment->count() > 0)
                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-green-600">متاحة</span>
                            <span class="font-medium">{{ $location->equipment->where('status', 'available')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-blue-600">قيد الاستخدام</span>
                            <span class="font-medium">{{ $location->equipment->where('status', 'in_use')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-yellow-600">في الصيانة</span>
                            <span class="font-medium">{{ $location->equipment->where('status', 'maintenance')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-red-600">خارج الخدمة</span>
                            <span class="font-medium">{{ $location->equipment->where('status', 'out_of_order')->count() }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
                <div class="space-y-3">
                    <button onclick="showEquipmentChoiceModal()"
                       class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 justify-center">
                        <i class="ri-tools-add-line"></i>
                        إضافة معدة لهذا الموقع
                    </button>
                    @if($location->coordinates)
                    <a href="https://maps.google.com/?q={{ $location->coordinates }}" target="_blank"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 justify-center">
                        <i class="ri-map-pin-line"></i>
                        عرض على الخريطة
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Equipment Choice Modal -->
<div id="equipmentChoiceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="hideEquipmentChoiceModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-tools-line text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">إضافة معدة للموقع</h3>
                <p class="text-gray-600 mt-2">اختر نوع المعدة التي تريد إضافتها</p>
            </div>

            <div class="space-y-3">
                <!-- New Equipment -->
                <a href="{{ route('equipment.create') }}?location_id={{ $location->id }}"
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors flex items-center gap-3 justify-center">
                    <i class="ri-add-circle-line text-xl"></i>
                    <div class="text-right">
                        <div class="font-medium">معدة جديدة</div>
                        <div class="text-sm text-blue-100">تسجيل معدة جديدة في النظام</div>
                    </div>
                </a>

                <!-- Existing Equipment -->
                <button onclick="showExistingEquipmentModal()"
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors flex items-center gap-3 justify-center">
                    <i class="ri-database-2-line text-xl"></i>
                    <div class="text-right">
                        <div class="font-medium">معدة موجودة</div>
                        <div class="text-sm text-green-100">تحديد موقع معدة مسجلة مسبقاً</div>
                    </div>
                </button>
            </div>

            <div class="mt-6">
                <button onclick="hideEquipmentChoiceModal()"
                        class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    إلغاء
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Existing Equipment Modal -->
<div id="existingEquipmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="hideExistingEquipmentModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full p-6" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">اختيار معدة موجودة</h3>
                <button onclick="hideExistingEquipmentModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div class="mb-4">
                <input type="text" id="equipmentSearch" placeholder="ابحث عن المعدة..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       onkeyup="filterEquipment()">
            </div>

            <div id="equipmentList" class="max-h-64 overflow-y-auto space-y-2">
                <!-- Equipment list will be loaded here -->
                <div class="text-center py-4">
                    <i class="ri-loader-4-line text-gray-400 text-xl animate-spin"></i>
                    <p class="text-gray-500 mt-2">جاري تحميل المعدات...</p>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button onclick="hideExistingEquipmentModal()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    إلغاء
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Equipment Assignment Confirmation Modal -->
<div id="equipmentAssignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100">
                <i class="ri-map-pin-line text-orange-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">تأكيد تحديد موقع المعدة</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    هل تريد تحديد موقع هذه المعدة إلى الموقع المحدد؟
                </p>

                <!-- Equipment Information -->
                <div class="p-3 bg-gray-50 rounded-lg text-right mb-4">
                    <div class="text-sm space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">اسم المعدة:</span>
                            <span class="font-medium text-gray-900" id="assignmentEquipmentName"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">النوع:</span>
                            <span class="font-medium text-gray-900" id="assignmentEquipmentType"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">الرقم التسلسلي:</span>
                            <span class="font-medium text-gray-900" id="assignmentEquipmentSerial"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">الحالة:</span>
                            <span id="assignmentEquipmentStatus"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">الموقع الحالي:</span>
                            <span class="font-medium text-gray-700" id="assignmentCurrentLocation"></span>
                        </div>
                    </div>
                </div>

                <!-- New Location Info -->
                <div class="p-3 bg-orange-50 rounded-lg text-right">
                    <div class="text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-orange-600 font-medium">الموقع الجديد:</span>
                            <span class="font-bold text-orange-800" id="assignmentLocationName"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex gap-3 justify-center">
                    <button type="button"
                            onclick="hideEquipmentAssignmentModal()"
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        إلغاء
                    </button>
                    <button type="button"
                            id="confirmAssignmentBtn"
                            onclick="confirmEquipmentAssignment()"
                            class="px-4 py-2 bg-orange-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300">
                        تأكيد التحديد
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showEquipmentChoiceModal() {
    document.getElementById('equipmentChoiceModal').classList.remove('hidden');
}

function hideEquipmentChoiceModal() {
    document.getElementById('equipmentChoiceModal').classList.add('hidden');
}

function showExistingEquipmentModal() {
    hideEquipmentChoiceModal();
    document.getElementById('existingEquipmentModal').classList.remove('hidden');
    loadAvailableEquipment();
}

function hideExistingEquipmentModal() {
    document.getElementById('existingEquipmentModal').classList.add('hidden');
}

function loadAvailableEquipment() {
    // Get equipment that are not assigned to any location or are at different locations
    fetch('/api/equipment/available-for-location', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const equipmentList = document.getElementById('equipmentList');
        if (data.length === 0) {
            equipmentList.innerHTML = `
                <div class="text-center py-8">
                    <i class="ri-tools-line text-gray-400 text-3xl"></i>
                    <p class="text-gray-500 mt-2">لا توجد معدات متاحة للنقل</p>
                    <p class="text-gray-400 text-sm">جميع المعدات مُحددة مواقعها بالفعل</p>
                </div>
            `;
            return;
        }

        equipmentList.innerHTML = data.map(equipment => `
            <div class="equipment-item border border-gray-200 rounded-lg p-4 hover:bg-gray-50 cursor-pointer transition-colors"
                 onclick="assignEquipmentToLocation(${equipment.id})">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            ${getStatusBgColor(equipment.status)}">
                            <i class="ri-tools-line ${getStatusTextColor(equipment.status)}"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">${equipment.name}</div>
                            <div class="text-sm text-gray-500">${equipment.type} - ${equipment.serial_number}</div>
                        </div>
                    </div>
                    <div class="text-left">
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${getStatusBadgeColor(equipment.status)}">
                            ${getStatusText(equipment.status)}
                        </span>
                        ${equipment.location ? `<div class="text-xs text-gray-500 mt-1">الموقع: ${equipment.location}</div>` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    })
    .catch(error => {
        console.error('Error loading equipment:', error);
        document.getElementById('equipmentList').innerHTML = `
            <div class="text-center py-8">
                <i class="ri-error-warning-line text-red-400 text-3xl"></i>
                <p class="text-red-500 mt-2">خطأ في تحميل المعدات</p>
            </div>
        `;
    });
}

function filterEquipment() {
    const search = document.getElementById('equipmentSearch').value.toLowerCase();
    const items = document.querySelectorAll('.equipment-item');

    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(search)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function assignEquipmentToLocation(equipmentId) {
    // Load equipment details and show confirmation modal
    fetch(`/api/equipment/${equipmentId}/details`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(equipment => {
        showEquipmentAssignmentModal(equipment);
    })
    .catch(error => {
        console.error('Error loading equipment details:', error);
        // Fallback to simple confirm
        if (confirm('هل تريد تحديد موقع هذه المعدة إلى هذا الموقع؟')) {
            performEquipmentAssignment(equipmentId);
        }
    });
}

function showEquipmentAssignmentModal(equipment) {
    // Update modal content with equipment details
    document.getElementById('assignmentEquipmentName').textContent = equipment.name;
    document.getElementById('assignmentEquipmentType').textContent = equipment.type;
    document.getElementById('assignmentEquipmentSerial').textContent = equipment.serial_number;
    document.getElementById('assignmentEquipmentStatus').textContent = getStatusText(equipment.status);
    document.getElementById('assignmentEquipmentStatus').className = `px-2 py-1 text-xs font-medium rounded-full ${getStatusBadgeColor(equipment.status)}`;
    document.getElementById('assignmentCurrentLocation').textContent = equipment.current_location || 'غير محدد';
    document.getElementById('assignmentLocationName').textContent = '{{ $location->name }}';

    // Store equipment ID for assignment
    document.getElementById('confirmAssignmentBtn').setAttribute('data-equipment-id', equipment.id);

    // Show the modal
    document.getElementById('equipmentAssignmentModal').classList.remove('hidden');
}

function hideEquipmentAssignmentModal() {
    document.getElementById('equipmentAssignmentModal').classList.add('hidden');
}

function confirmEquipmentAssignment() {
    const equipmentId = document.getElementById('confirmAssignmentBtn').getAttribute('data-equipment-id');
    hideEquipmentAssignmentModal();
    performEquipmentAssignment(equipmentId);
}

function performEquipmentAssignment(equipmentId) {
    const formData = new FormData();
    formData.append('location_id', {{ $location->id }});
    formData.append('location_update_only', '1');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'PUT');

    fetch(`/equipment/${equipmentId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideExistingEquipmentModal();
            location.reload(); // Reload page to show updated equipment list
        } else {
            alert('حدث خطأ في تحديث موقع المعدة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في تحديث موقع المعدة');
    });
}

function getStatusBgColor(status) {
    const colors = {
        'available': 'bg-green-100',
        'in_use': 'bg-blue-100',
        'maintenance': 'bg-yellow-100',
        'out_of_order': 'bg-red-100'
    };
    return colors[status] || 'bg-gray-100';
}

function getStatusTextColor(status) {
    const colors = {
        'available': 'text-green-600',
        'in_use': 'text-blue-600',
        'maintenance': 'text-yellow-600',
        'out_of_order': 'text-red-600'
    };
    return colors[status] || 'text-gray-600';
}

function getStatusBadgeColor(status) {
    const colors = {
        'available': 'bg-green-100 text-green-800',
        'in_use': 'bg-blue-100 text-blue-800',
        'maintenance': 'bg-yellow-100 text-yellow-800',
        'out_of_order': 'bg-red-100 text-red-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

function getStatusText(status) {
    const texts = {
        'available': 'متاحة',
        'in_use': 'قيد الاستخدام',
        'maintenance': 'في الصيانة',
        'out_of_order': 'خارج الخدمة'
    };
    return texts[status] || status;
}
</script>
@endsection
