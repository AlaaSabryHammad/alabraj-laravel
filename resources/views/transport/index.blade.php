@extends('layouts.app')

@section('title', 'إدارة النقل')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">إدارة النقل</h1>
            <p class="text-gray-600 mt-1">إدارة رحلات النقل والمركبات</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('transport.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-add-line"></i>
                إضافة رحلة جديدة
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي الرحلات</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $transports->total() }}</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-truck-line text-xl text-blue-600"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">
                عرض {{ $transports->count() }} من {{ $transports->total() }} رحلة
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">رحلات هذا الشهر</p>
                    <p class="text-2xl font-bold text-green-600">{{ $transports->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-road-map-line text-xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Transports Table -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">قائمة الرحلات</h2>

            <!-- Filters -->
            <form method="GET" action="{{ route('transport.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <!-- Loading Location Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">محطة التحميل</label>
                    <select name="loading_location" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">جميع المحطات</option>
                        @foreach(\App\Models\Location::where('status', 'active')->orderBy('name')->get() as $location)
                            <option value="{{ $location->id }}" {{ request('loading_location') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Unloading Location Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">محطة التفريغ</label>
                    <select name="unloading_location" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">جميع المحطات</option>
                        @foreach(\App\Models\Location::where('status', 'active')->orderBy('name')->get() as $location)
                            <option value="{{ $location->id }}" {{ request('unloading_location') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الوصول</label>
                    <input type="date" name="arrival_date" value="{{ request('arrival_date') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <!-- Vehicle Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">نوع المركبة</label>
                    <select name="vehicle_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">جميع الأنواع</option>
                        <option value="شاحنة خارجية" {{ request('vehicle_type') == 'شاحنة خارجية' ? 'selected' : '' }}>شاحنة خارجية</option>
                        <option value="مركبة داخلية" {{ request('vehicle_type') == 'مركبة داخلية' ? 'selected' : '' }}>مركبة داخلية</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <i class="ri-search-line ml-1"></i>
                        فلتر
                    </button>
                    <a href="{{ route('transport.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <i class="ri-refresh-line ml-1"></i>
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden">
            <!-- Mobile View -->
            <div class="block md:hidden">
                <div class="space-y-4 p-4">
                    @forelse($transports as $transport)
                        <div class="bg-gray-50 rounded-lg p-4 border">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="flex items-center mb-1">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded ml-2">
                                            #{{ $loop->iteration + ($transports->currentPage() - 1) * $transports->perPage() }}
                                        </span>
                                        <i class="ri-truck-line text-gray-400 ml-2"></i>
                                        <span class="font-medium text-gray-900">{{ $transport->vehicle_number }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600">{{ $transport->vehicle_type }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                <div>
                                    <span class="text-gray-500">السائق:</span>
                                    <div class="font-medium">{{ $transport->driver_name }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">محطة التحميل:</span>
                                    <div class="font-medium">{{ $transport->loadingLocation ? $transport->loadingLocation->name : 'غير محدد' }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">محطة التفريغ:</span>
                                    <div class="font-medium">{{ $transport->unloadingLocation ? $transport->unloadingLocation->name : 'غير محدد' }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">وقت الوصول:</span>
                                    <div class="font-medium">
                                        @if($transport->arrival_time)
                                            {{ $transport->arrival_time->format('Y-m-d H:i') }}
                                        @else
                                            غير محدد
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <span class="text-gray-500">مسجل بواسطة:</span>
                                    <div class="font-medium">
                                        {{ $transport->user ? $transport->user->name : 'غير محدد' }}
                                        <div class="text-xs text-gray-400">{{ $transport->created_at->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end items-center">
                                <div class="flex items-center gap-2">
                                    <button onclick="printLoadingSlip({{ $transport->id }})"
                                            class="text-purple-600 hover:text-purple-900 transition-colors p-2"
                                            title="طباعة سند التحميل">
                                        <i class="ri-printer-line"></i>
                                    </button>
                                    <button onclick="viewTransport({{ $transport->id }})"
                                            class="text-green-600 hover:text-green-900 transition-colors p-2"
                                            title="عرض التفاصيل">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <a href="{{ route('transport.edit', $transport) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors p-2"
                                       title="تعديل">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <button onclick="showDeleteModal({{ $transport->id }}, this.getAttribute('data-vehicle'))"
                                            data-vehicle="{{ $transport->vehicle_number }}"
                                            class="text-red-600 hover:text-red-900 transition-colors p-2"
                                            title="حذف">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    <!-- Hidden form for deletion -->
                                    <form id="deleteFormMobile{{ $transport->id }}"
                                          action="{{ route('transport.destroy', $transport) }}"
                                          method="POST"
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <i class="ri-truck-line text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">لا توجد رحلات نقل مسجلة</p>
                                <a href="{{ route('transport.create') }}"
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    إضافة أول رحلة
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Desktop View -->
            <div class="hidden md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    المركبة
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    السائق
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    محطة التحميل
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    محطة التفريغ
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    وقت الوصول
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    مسجل بواسطة
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الإجراءات
                                </th>
                            </tr>
                    </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transports as $transport)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $loop->iteration + ($transports->currentPage() - 1) * $transports->perPage() }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="ri-truck-line text-gray-400 ml-2"></i>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $transport->vehicle_number }}</div>
                                                <div class="text-sm text-gray-500">{{ $transport->vehicle_type }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $transport->driver_name }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $transport->loadingLocation ? $transport->loadingLocation->name : 'غير محدد' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $transport->unloadingLocation ? $transport->unloadingLocation->name : 'غير محدد' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($transport->arrival_time)
                                            <div class="text-sm">{{ $transport->arrival_time->format('Y-m-d') }}</div>
                                            <div class="text-xs text-gray-500">{{ $transport->arrival_time->format('H:i') }}</div>
                                        @else
                                            <span class="text-gray-400">غير محدد</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $transport->user ? $transport->user->name : 'غير محدد' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $transport->created_at->format('Y-m-d') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <button onclick="printLoadingSlip({{ $transport->id }})"
                                                    class="text-purple-600 hover:text-purple-900 transition-colors"
                                                    title="طباعة سند التحميل">
                                                <i class="ri-printer-line"></i>
                                            </button>
                                            <button onclick="viewTransport({{ $transport->id }})"
                                                    class="text-green-600 hover:text-green-900 transition-colors"
                                                    title="عرض التفاصيل">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <a href="{{ route('transport.edit', $transport) }}"
                                               class="text-blue-600 hover:text-blue-900 transition-colors"
                                               title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button onclick="showDeleteModal({{ $transport->id }}, this.getAttribute('data-vehicle'))"
                                                    data-vehicle="{{ $transport->vehicle_number }}"
                                                    class="text-red-600 hover:text-red-900 transition-colors"
                                                    title="حذف">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <!-- Hidden form for deletion -->
                                            <form id="deleteForm{{ $transport->id }}"
                                                  action="{{ route('transport.destroy', $transport) }}"
                                                  method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="ri-truck-line text-4xl text-gray-300 mb-2"></i>
                                            <p class="text-gray-500">لا توجد رحلات نقل مسجلة</p>
                                            <a href="{{ route('transport.create') }}"
                                               class="mt-2 text-blue-600 hover:text-blue-800">
                                                إضافة أول رحلة
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($transports->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transports->links() }}
            </div>
        @endif
    </div>

    <!-- Transport Details Modal -->
    <div id="transportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">تفاصيل الرحلة</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                <div id="transportDetails" class="space-y-4">
                    <!-- Transport details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="ri-delete-bin-line text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">تأكيد الحذف</h3>
                <p class="text-sm text-gray-500 mb-6" id="deleteMessage">
                    هل أنت متأكد من حذف هذه الرحلة؟ لا يمكن التراجع عن هذا الإجراء.
                </p>
                <div class="flex justify-center gap-3">
                    <button onclick="closeDeleteModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button onclick="confirmDelete()"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="ri-delete-bin-line mr-2"></i>
                        حذف
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4" id="notificationIcon">
                    <i class="text-xl" id="notificationIconClass"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2" id="notificationTitle">تنبيه</h3>
                <p class="text-sm text-gray-500 mb-6" id="notificationMessage">
                    رسالة التنبيه
                </p>
                <div class="flex justify-center">
                    <button onclick="closeNotificationModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        موافق
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Transport JavaScript Module -->
<script src="{{ asset('js/transport.js') }}"></script>

<!-- Simple Inline Script for Testing -->
<script>
// Immediate test on page load
console.log('✅ Blade template script loaded');

// Check if functions are available
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Quick function check from blade template:');
    console.log('- showNotificationModal:', typeof window.showNotificationModal);
    console.log('- viewTransport:', typeof window.viewTransport);
    console.log('- showDeleteModal:', typeof window.showDeleteModal);
    console.log('- printLoadingSlip:', typeof window.printLoadingSlip);

    if (typeof window.showNotificationModal !== 'function') {
        console.error('❌ Functions not loaded! Fallback needed.');
        alert('وظائف JavaScript لم يتم تحميلها بشكل صحيح!');
    } else {
        console.log('✅ All functions loaded successfully!');
    }
});
</script>
@endsection
