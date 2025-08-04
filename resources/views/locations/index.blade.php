@extends('layouts.app')

@section('title', 'إدارة المواقع - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة المواقع</h1>
                <p class="text-gray-600">إدارة شاملة لجميع مواقع الشركة ومشاريعها</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('locations.create') }}"
                   class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl font-medium hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center">
                    <i class="ri-map-pin-add-line ml-2"></i>
                    إضافة موقع جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
        <div class="flex items-center">
            <i class="ri-check-circle-line text-green-600 ml-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Location Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">نشطة</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</h3>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl">
                    <i class="ri-map-pin-fill text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">مواقع المشاريع</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['site'] }}</h3>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl">
                    <i class="ri-building-line text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl p-6 border border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium mb-1">المستودعات</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['warehouse'] }}</h3>
                </div>
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-3 rounded-xl">
                    <i class="ri-store-line text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium mb-1">المكاتب</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['office'] }}</h3>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-3 rounded-xl">
                    <i class="ri-community-line text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Locations Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">قائمة المواقع</h3>
        </div>

        @if($locations->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الموقع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المدينة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المسؤول</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المساحة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($locations as $location)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden"
                                     style="background-color: {{ $location->locationType ? $location->locationType->color . '20' : '#F3F4F6' }}">
                                    <i class="{{ $location->locationType ? $location->locationType->icon : 'ri-map-pin-line' }}"
                                       style="color: {{ $location->locationType ? $location->locationType->color : '#6B7280' }}"></i>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $location->name }}</div>
                                    @if($location->address)
                                    <div class="text-sm text-gray-500">{{ Str::limit($location->address, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($location->locationType)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      style="background-color: {{ $location->locationType->color }}20; color: {{ $location->locationType->color }}">
                                    <i class="{{ $location->locationType->icon }} ml-1"></i>
                                    {{ $location->locationType->name }}
                                </span>
                            @else
                                <span class="text-gray-500">غير محدد</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $location->city ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $location->manager_name ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = [
                                    'active' => ['text' => 'نشط', 'class' => 'bg-green-100 text-green-800'],
                                    'inactive' => ['text' => 'غير نشط', 'class' => 'bg-gray-100 text-gray-800'],
                                    'under_construction' => ['text' => 'تحت الإنشاء', 'class' => 'bg-yellow-100 text-yellow-800']
                                ];
                                $status = $statusConfig[$location->status] ?? ['text' => $location->status, 'class' => 'bg-gray-100 text-gray-800'];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status['class'] }}">
                                {{ $status['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($location->area_size)
                                {{ number_format($location->area_size) }} م²
                            @else
                                غير محدد
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('locations.show', $location) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('locations.edit', $location) }}"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button type="button" onclick="showDeleteModal({{ $location->id }}, '{{ $location->name }}', '{{ $location->locationType ? $location->locationType->name : 'غير محدد' }}', '{{ $location->city ?? 'غير محدد' }}', '{{ $location->manager_name ?? 'غير محدد' }}')" class="text-red-600 hover:text-red-900">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $locations->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="ri-map-pin-line text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مواقع مسجلة</h3>
            <p class="text-gray-500 mb-6">ابدأ بإضافة الموقع الأول للشركة</p>
            <a href="{{ route('locations.create') }}"
               class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-colors">
                إضافة موقع جديد
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="ri-delete-bin-line text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">حذف الموقع</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    هل أنت متأكد من حذف هذا الموقع؟ هذا الإجراء لا يمكن التراجع عنه.
                </p>

                <!-- Location Information -->
                <div class="p-3 bg-gray-50 rounded-lg text-right">
                    <div class="text-xs text-gray-600 space-y-1">
                        <div class="flex justify-between">
                            <span>اسم الموقع:</span>
                            <span id="locationName" class="font-medium text-gray-900"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>النوع:</span>
                            <span id="locationType" class="font-medium text-gray-900"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>المدينة:</span>
                            <span id="locationCity" class="font-medium text-gray-900"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>المسؤول:</span>
                            <span id="locationManager" class="font-medium text-gray-900"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3 justify-center">
                        <button type="button"
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            حذف الموقع
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showDeleteModal(locationId, name, type, city, manager) {
    // Set location information in modal
    document.getElementById('locationName').textContent = name;
    document.getElementById('locationType').textContent = type;
    document.getElementById('locationCity').textContent = city;
    document.getElementById('locationManager').textContent = manager;

    // Set form action
    document.getElementById('deleteForm').action = `/locations/${locationId}`;

    // Show modal
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeDeleteModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
