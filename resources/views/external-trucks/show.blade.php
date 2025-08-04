@extends('layouts.app')

@section('title', 'تفاصيل الشاحنة - ' . $externalTruck->plate_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تفاصيل الشاحنة - {{ $externalTruck->plate_number }}</h1>
                <p class="text-gray-600">عرض شامل لبيانات الشاحنة والسائق</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('external-trucks.edit', $externalTruck) }}"
                   class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-3 rounded-xl font-medium hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 flex items-center">
                    <i class="ri-edit-line ml-2"></i>
                    تعديل البيانات
                </a>
                <a href="{{ route('external-trucks.index') }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة إلى القائمة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-600 to-cyan-700 p-6">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-truck-fill text-white text-2xl"></i>
                        </div>
                        <div class="mr-4 text-white">
                            <h2 class="text-2xl font-bold">{{ $externalTruck->plate_number }}</h2>
                            @if($externalTruck->loading_type)
                                <p class="text-cyan-100">{{ $externalTruck->loading_type_text }}</p>
                            @else
                                <p class="text-cyan-100">شاحنة خارجية</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($externalTruck->loading_type)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">نوع التحميل</h3>
                            <div class="flex items-center">
                                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $externalTruck->loading_type === 'box' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $externalTruck->loading_type_text }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($externalTruck->capacity)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">السعة</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $externalTruck->capacity_with_unit }}</p>
                        </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">الحالة</h3>
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-red-100 text-red-800',
                                    'maintenance' => 'bg-yellow-100 text-yellow-800'
                                ];
                            @endphp
                            <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$externalTruck->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $externalTruck->status_text }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">تاريخ الإضافة</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $externalTruck->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier Information -->
            @if($externalTruck->supplier)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="ri-building-line text-orange-600 ml-2"></i>
                        بيانات المورد
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">اسم المورد</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $externalTruck->supplier->name }}</p>
                        </div>

                        @if($externalTruck->supplier->phone)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">هاتف المورد</h3>
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <p class="text-lg font-semibold text-gray-900 dir-ltr">{{ $externalTruck->supplier->phone }}</p>
                                <a href="tel:{{ $externalTruck->supplier->phone }}"
                                   class="text-orange-600 hover:text-orange-800"
                                   title="اتصال">
                                    <i class="ri-phone-line"></i>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($externalTruck->supplier->email)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">البريد الإلكتروني</h3>
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <p class="text-lg font-semibold text-gray-900 dir-ltr">{{ $externalTruck->supplier->email }}</p>
                                <a href="mailto:{{ $externalTruck->supplier->email }}"
                                   class="text-orange-600 hover:text-orange-800"
                                   title="إرسال إيميل">
                                    <i class="ri-mail-line"></i>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($externalTruck->supplier->address)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">العنوان</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $externalTruck->supplier->address }}</p>
                        </div>
                        @endif

                        @if($externalTruck->contract_start_date || $externalTruck->contract_end_date)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">فترة العقد</h3>
                            <div class="text-lg font-semibold text-gray-900">
                                @if($externalTruck->contract_start_date && $externalTruck->contract_end_date)
                                    من {{ \Carbon\Carbon::parse($externalTruck->contract_start_date)->format('Y/m/d') }}
                                    إلى {{ \Carbon\Carbon::parse($externalTruck->contract_end_date)->format('Y/m/d') }}
                                @elseif($externalTruck->contract_start_date)
                                    من {{ \Carbon\Carbon::parse($externalTruck->contract_start_date)->format('Y/m/d') }}
                                @elseif($externalTruck->contract_end_date)
                                    حتى {{ \Carbon\Carbon::parse($externalTruck->contract_end_date)->format('Y/m/d') }}
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($externalTruck->contract_value)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">قيمة العقد</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($externalTruck->contract_value) }} ريال</p>
                        </div>
                        @endif
                    </div>

                    @if($externalTruck->contract_notes)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">ملاحظات العقد</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $externalTruck->contract_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Driver Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="ri-user-line text-cyan-600 ml-2"></i>
                        بيانات السائق
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">اسم السائق</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $externalTruck->driver_name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">رقم الجوال</h3>
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <p class="text-lg font-semibold text-gray-900 dir-ltr">{{ $externalTruck->driver_phone }}</p>
                                <a href="tel:{{ $externalTruck->driver_phone }}"
                                   class="text-cyan-600 hover:text-cyan-800"
                                   title="اتصال">
                                    <i class="ri-phone-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($externalTruck->notes)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="ri-sticky-note-line text-cyan-600 ml-2"></i>
                        الملاحظات
                    </h2>
                </div>

                <div class="p-6">
                    <p class="text-gray-700 leading-relaxed">{{ $externalTruck->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Side Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900">الإجراءات السريعة</h2>
                </div>

                <div class="p-6 space-y-3">
                    <a href="{{ route('external-trucks.edit', $externalTruck) }}"
                       class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-4 py-3 rounded-lg font-medium hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 flex items-center justify-center">
                        <i class="ri-edit-line ml-2"></i>
                        تعديل البيانات
                    </a>

                    @if($externalTruck->supplier && $externalTruck->supplier->phone)
                    <a href="tel:{{ $externalTruck->supplier->phone }}"
                       class="w-full bg-gradient-to-r from-orange-600 to-orange-700 text-white px-4 py-3 rounded-lg font-medium hover:from-orange-700 hover:to-orange-800 transition-all duration-200 flex items-center justify-center">
                        <i class="ri-building-line ml-2"></i>
                        اتصال بالمورد
                    </a>
                    @endif

                    <a href="tel:{{ $externalTruck->driver_phone }}"
                       class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-3 rounded-lg font-medium hover:from-green-700 hover:to-green-800 transition-all duration-200 flex items-center justify-center">
                        <i class="ri-phone-line ml-2"></i>
                        اتصال بالسائق
                    </a>

                    <form action="{{ route('external-trucks.destroy', $externalTruck) }}"
                          method="POST"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الشاحنة؟ سيتم حذف جميع البيانات والصور المرتبطة بها.')"
                          class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-lg font-medium hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center justify-center">
                            <i class="ri-delete-bin-line ml-2"></i>
                            حذف الشاحنة
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900">إحصائيات سريعة</h2>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">تاريخ الإضافة</span>
                        <span class="font-semibold text-gray-900">{{ $externalTruck->created_at->format('Y/m/d') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">آخر تحديث</span>
                        <span class="font-semibold text-gray-900">{{ $externalTruck->updated_at->format('Y/m/d') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">عدد الصور</span>
                        <span class="font-semibold text-gray-900">{{ count($externalTruck->photos ?? []) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photos Gallery -->
    @if($externalTruck->photos && count($externalTruck->photos) > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="ri-image-line text-cyan-600 ml-2"></i>
                صور الشاحنة
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($externalTruck->photo_urls as $index => $photoUrl)
                <div class="relative group">
                    <img src="{{ $photoUrl }}"
                         alt="صورة الشاحنة {{ $index + 1 }}"
                         class="w-full h-32 object-cover rounded-lg shadow-sm group-hover:shadow-md transition-shadow duration-200 cursor-pointer"
                         onclick="openPhotoModal('{{ $photoUrl }}', 'صورة الشاحنة {{ $index + 1 }}')">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center">
                        <i class="ri-eye-line text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" style="display: none;">
        <div class="max-w-4xl max-h-full relative" onclick="event.stopPropagation()">
            <img id="modalPhoto" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg shadow-lg">
        </div>
        <button onclick="closePhotoModal()" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
            <i class="ri-close-line"></i>
        </button>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function openPhotoModal(src, alt) {
        console.log('Opening photo modal with src:', src);
        const modal = document.getElementById('photoModal');
        const modalPhoto = document.getElementById('modalPhoto');

        if (!modal || !modalPhoto) {
            console.error('Modal elements not found');
            return;
        }

        // Set image source and alt text
        modalPhoto.src = src;
        modalPhoto.alt = alt;

        // Show modal with proper display settings
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';

        // Prevent body scrolling
        document.body.style.overflow = 'hidden';

        console.log('Modal opened successfully');

        // Add click listener to modal background to close
        modal.onclick = function(e) {
            if (e.target === modal) {
                closePhotoModal();
            }
        };
    }

    function closePhotoModal() {
        console.log('Closing photo modal');
        const modal = document.getElementById('photoModal');

        if (!modal) {
            console.error('Modal element not found');
            return;
        }

        // Hide modal
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';

        // Restore body scrolling
        document.body.style.overflow = 'auto';

        // Remove click listener
        modal.onclick = null;

        console.log('Modal closed successfully');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePhotoModal();
        }
    });

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, modal functionality initialized');
    });
</script>
@endpush
@endsection
