@extends('layouts.app')

@section('title', 'تفاصيل المعدة: ' . $equipment->name)

@section('head')
    <style>
        @media print {

            /* Hide non-essential elements */
            .no-print {
                display: none !important;
            }

            /* Force content visibility */
            body,
            * {
                visibility: visible !important;
            }

            /* Page setup */
            @page {
                size: A4;
                margin: 1cm;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 12px !important;
                line-height: 1.4;
                color: #000 !important;
                background: white !important;
                print-color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
            }

            /* Main container */
            .p-6 {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                height: auto !important;
            }

            /* Card styles for print */
            .bg-white,
            .bg-gray-50,
            .rounded-xl,
            .shadow-sm,
            .border {
                background: white !important;
                border: 1px solid #ddd !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                margin-bottom: 10px !important;
                display: block !important;
                visibility: visible !important;
            }

            /* Text colors for print */
            .text-gray-900,
            .text-gray-700,
            .text-gray-600,
            .text-gray-500 {
                color: #000 !important;
            }

            .text-blue-600 {
                color: #1e40af !important;
            }

            .text-green-600 {
                color: #059669 !important;
            }

            .text-purple-600 {
                color: #7c3aed !important;
            }

            .text-red-600 {
                color: #dc2626 !important;
            }

            .text-orange-600 {
                color: #ea580c !important;
            }

            /* Grid and layout fixes */
            .grid {
                display: grid !important;
            }

            .grid-cols-1 {
                grid-template-columns: 1fr !important;
            }

            .grid-cols-2 {
                grid-template-columns: 1fr 1fr !important;
            }

            .grid-cols-3 {
                grid-template-columns: 1fr 1fr 1fr !important;
            }

            .grid-cols-4 {
                grid-template-columns: 1fr 1fr 1fr 1fr !important;
            }

            /* Flex layouts */
            .flex {
                display: flex !important;
            }

            .items-center {
                align-items: center !important;
            }

            .gap-3,
            .gap-4,
            .gap-6 {
                gap: 1rem !important;
            }

            /* Image sizing for print */
            img {
                max-width: 100% !important;
                height: auto !important;
                page-break-inside: avoid;
                display: block !important;
            }

            /* Spacing */
            .p-6 {
                padding: 1rem !important;
            }

            .p-4 {
                padding: 0.75rem !important;
            }

            .p-3 {
                padding: 0.5rem !important;
            }

            .mb-6 {
                margin-bottom: 1rem !important;
            }

            .mb-4 {
                margin-bottom: 0.75rem !important;
            }

            .mb-3 {
                margin-bottom: 0.5rem !important;
            }

            /* Font sizes for print */
            .text-3xl {
                font-size: 24px !important;
                font-weight: bold !important;
            }

            .text-2xl {
                font-size: 18px !important;
                font-weight: bold !important;
            }

            .text-lg {
                font-size: 14px !important;
                font-weight: 600 !important;
            }

            .text-sm {
                font-size: 11px !important;
            }

            .text-xs {
                font-size: 10px !important;
            }

            /* Font weights */
            .font-bold {
                font-weight: bold !important;
            }

            .font-semibold {
                font-weight: 600 !important;
            }

            .font-medium {
                font-weight: 500 !important;
            }

            /* Status badges for print */
            .bg-green-100 {
                background: #f0f9ff !important;
                border: 1px solid #059669 !important;
            }

            .bg-red-100 {
                background: #fef2f2 !important;
                border: 1px solid #dc2626 !important;
            }

            .bg-gray-100 {
                background: #f9fafb !important;
                border: 1px solid #6b7280 !important;
            }

            .bg-blue-100 {
                background: #eff6ff !important;
                border: 1px solid #3b82f6 !important;
            }

            .bg-purple-100 {
                background: #f3e8ff !important;
                border: 1px solid #8b5cf6 !important;
            }

            .bg-yellow-100 {
                background: #fefce8 !important;
                border: 1px solid #eab308 !important;
            }

            .bg-orange-100 {
                background: #fff7ed !important;
                border: 1px solid #f97316 !important;
            }

            /* Force all containers to be visible */
            div,
            section,
            article,
            main {
                display: block !important;
                visibility: visible !important;
            }

            /* Print header visibility */
            .hidden.print\\:block {
                display: block !important;
                visibility: visible !important;
            }

            /* Equipment cards visibility */
            .bg-white.rounded-xl.shadow-sm.border {
                background: white !important;
                border: 2px solid #000 !important;
                visibility: visible !important;
                display: block !important;
                page-break-inside: avoid !important;
            }

            .print-break {
                page-break-before: always;
            }
        }
    </style>
@endsection

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6 no-print">
            <div class="flex items-center gap-4">
                <a href="{{ route('equipment.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $equipment->name }}</h1>
                    <p class="text-gray-600 mt-1">تفاصيل المعدة</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="openFuelConsumptionModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-gas-station-line"></i>
                    إضافة استهلاك محروقات
                </button>

                @php
                    $isTruck = ($equipment->category === 'شاحنات' || $equipment->category === 'شاحنة') ||
                               (strpos($equipment->name, 'شاحنة') !== false);
                @endphp

                @if($isTruck)
                    @if($equipment->truck_id)
                        <button type="button" onclick="openUnlinkModal()"
                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-unlink-line"></i>
                            إلغاء الربط بشاحنات النقل الداخلي
                        </button>
                    @else
                        <button type="button" onclick="openLinkModal()"
                            class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-link-line"></i>
                            ربط بشاحنات النقل الداخلي
                        </button>
                    @endif
                @endif

                <a href="{{ route('equipment.report', $equipment) }}" target="_blank"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-file-text-line"></i>
                    تقرير تفصيلي
                </a>
                <a href="{{ route('equipment.edit', $equipment) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-edit-line"></i>
                    تعديل
                </a>
                <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" class="inline"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذه المعدة؟')">
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

        <!-- Print Header (Only visible when printing) -->
        <div class="hidden print:block mb-6" style="display: none;">
            <div class="text-center border-b-2 border-blue-600 pb-4 mb-6">
                <h1 class="text-3xl font-bold text-blue-600 mb-2">تفاصيل المعدة</h1>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $equipment->name }}</h2>
                <div class="flex justify-between items-center text-sm text-gray-600 mt-4">
                    <div>
                        <p class="font-semibold">شركة أبراج للمقاولات العامة</p>
                        <p>إدارة المعدات والآليات</p>
                    </div>
                    <div class="text-left" dir="ltr">
                        <p>تاريخ الطباعة: {{ now()->format('Y/m/d') }}</p>
                        <p>الوقت: {{ now()->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-3 h-3 rounded-full
                        @if ($equipment->status === 'available') bg-green-500
                        @elseif($equipment->status === 'in_use') bg-blue-500
                        @elseif($equipment->status === 'maintenance') bg-yellow-500
                        @else bg-red-500 @endif">
                        </div>
                        <span class="font-medium text-gray-900">
                            @if ($equipment->status === 'available')
                                متاحة
                            @elseif($equipment->status === 'in_use')
                                قيد الاستخدام
                            @elseif($equipment->status === 'maintenance')
                                تحت الصيانة
                            @else
                                خارج الخدمة
                            @endif
                        </span>
                    </div>
                    <span class="text-sm text-gray-500">
                        آخر تحديث: {{ $equipment->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Equipment Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Purchase Price -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="ri-money-dollar-circle-line text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">سعر الشراء</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($equipment->purchase_price, 0) }}</p>
                        <p class="text-gray-500 text-xs">ريال سعودي</p>
                    </div>
                </div>
            </div>

            <!-- Purchase Date -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="ri-calendar-line text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">تاريخ الشراء</p>
                        <p class="text-lg font-bold text-gray-900">{{ $equipment->purchase_date->format('d/m/Y') }}</p>
                        <p class="text-gray-500 text-xs">{{ $equipment->purchase_date->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Warranty -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    @php
                        $warrantyExpired = $equipment->warranty_expiry
                            ? \Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($equipment->warranty_expiry))
                            : true;
                    @endphp
                    <div class="bg-{{ $warrantyExpired ? 'red' : 'orange' }}-100 p-3 rounded-xl">
                        <i class="ri-shield-line text-{{ $warrantyExpired ? 'red' : 'orange' }}-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">الضمان</p>
                        <p class="text-lg font-bold {{ $warrantyExpired ? 'text-red-600' : 'text-gray-900' }}">
                            @if ($equipment->warranty_expiry)
                                {{ $equipment->warranty_expiry->format('d/m/Y') }}
                            @else
                                غير محدد
                            @endif
                        </p>
                        <p class="text-gray-500 text-xs">
                            @if ($equipment->warranty_expiry)
                                {{ $warrantyExpired ? 'منتهي الصلاحية' : 'ساري' }}
                            @else
                                غير محدد
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Age -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <i class="ri-time-line text-purple-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">عمر المعدة</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ round($equipment->purchase_date->diffInMonths(now())) }}
                        </p>
                        <p class="text-gray-500 text-xs">شهر</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment Images -->
        @if ($equipment->images && count($equipment->images) > 0)
            <div class="bg-white rounded-xl shadow-sm border mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">صور المعدة</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($equipment->images as $index => $imagePath)
                            <div class="relative group cursor-pointer"
                                onclick="openImageModal('{{ asset('storage/' . $imagePath) }}', '{{ $equipment->name }} - صورة {{ $index + 1 }}')">
                                <img src="{{ asset('storage/' . $imagePath) }}"
                                    class="w-full h-32 object-cover rounded-lg border border-gray-200 transition-transform group-hover:scale-105"
                                    alt="صورة المعدة {{ $index + 1 }}">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30
                                transition-all rounded-lg flex items-center justify-center">
                                    <i class="ri-eye-line text-white opacity-0 group-hover:opacity-100 text-2xl"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Equipment Files -->
        @if ($equipment->files && $equipment->files->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">ملفات المعدة</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($equipment->files as $file)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="flex-shrink-0">
                                                @if (str_contains($file->file_type, 'pdf'))
                                                    <i class="ri-file-pdf-line text-red-600 text-2xl"></i>
                                                @elseif(str_contains($file->file_type, 'word') || str_contains($file->file_type, 'document'))
                                                    <i class="ri-file-word-line text-blue-600 text-2xl"></i>
                                                @elseif(str_contains($file->file_type, 'image'))
                                                    <i class="ri-image-line text-green-600 text-2xl"></i>
                                                @else
                                                    <i class="ri-file-line text-gray-600 text-2xl"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900">{{ $file->file_name }}</h3>
                                                <p class="text-xs text-gray-500">{{ $file->original_name }}</p>
                                            </div>
                                        </div>

                                        <div class="space-y-1 text-xs text-gray-600">
                                            @if ($file->expiry_date)
                                                <div class="flex items-center gap-2">
                                                    <i class="ri-calendar-line"></i>
                                                    <span>انتهاء الصلاحية: {{ $file->expiry_date->format('d/m/Y') }}</span>
                                                    @if ($file->expiry_date->isPast())
                                                        <span
                                                            class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">منتهي
                                                            الصلاحية</span>
                                                    @elseif($file->expiry_date->diffInDays() <= 30)
                                                        <span
                                                            class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">ينتهي
                                                            قريباً</span>
                                                    @endif
                                                </div>
                                            @endif

                                            @if ($file->description)
                                                <div class="flex items-start gap-2">
                                                    <i class="ri-information-line mt-0.5"></i>
                                                    <span>{{ $file->description }}</span>
                                                </div>
                                            @endif

                                            <div class="flex items-center gap-2">
                                                <i class="ri-file-info-line"></i>
                                                <span>{{ number_format($file->file_size / 1024, 1) }} كيلوبايت</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-xs transition-colors flex items-center gap-1">
                                            <i class="ri-eye-line"></i>
                                            عرض
                                        </a>
                                        <a href="{{ route('equipment.file.download', $file) }}"
                                            class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-lg text-xs transition-colors flex items-center gap-1">
                                            <i class="ri-download-line"></i>
                                            تحميل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Equipment Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">معلومات المعدة</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">رقم اللوحة</h3>
                                <p class="text-gray-900 font-medium">{{ $equipment->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">النوع</h3>
                                <p class="text-gray-900">{{ $equipment->type }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">الشركة المصنعة</h3>
                                <p class="text-gray-900">{{ $equipment->manufacturer ?? 'غير محدد' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">الموديل</h3>
                                <p class="text-gray-900">{{ $equipment->model ?? 'غير محدد' }}</p>
                            </div>
                        </div>

                        <!-- Serial Number -->
                        @if ($equipment->serial_number)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">الرقم التسلسلي</h3>
                                <div class="bg-gray-50 rounded-lg p-3 font-mono text-sm">
                                    {{ $equipment->serial_number }}
                                </div>
                            </div>
                        @endif

                        <!-- Location -->
                        @if ($equipment->locationDetail || $equipment->location)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">الموقع</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    @if ($equipment->locationDetail)
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="ri-building-line text-green-600"></i>
                                            <span
                                                class="text-green-900 font-medium">{{ $equipment->locationDetail->name }}</span>
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">موقع
                                                منظم</span>
                                        </div>
                                        @if ($equipment->locationDetail->address)
                                            <div class="text-sm text-gray-600 mr-6">
                                                {{ $equipment->locationDetail->address }}</div>
                                        @endif
                                    @endif
                                    @if ($equipment->location)
                                        <div
                                            class="flex items-center gap-3 {{ $equipment->locationDetail ? 'mt-3 pt-3 border-t border-gray-200' : '' }}">
                                            <i class="ri-map-pin-line text-gray-600"></i>
                                            <span class="text-gray-900">{{ $equipment->location }}</span>
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">موقع
                                                نصي</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Driver -->
                        @if ($equipment->driver)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">السائق المسؤول</h3>
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center gap-3">
                                        <i class="ri-user-line text-blue-600"></i>
                                        <span class="text-blue-900 font-medium">{{ $equipment->driver->name }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Description -->
                        @if ($equipment->description)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">الوصف</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-700 leading-relaxed">{{ $equipment->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">إجراءات سريعة</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('equipment.edit', $equipment) }}"
                            class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg transition-colors flex items-center gap-3">
                            <i class="ri-edit-line"></i>
                            <span>تعديل بيانات المعدة</span>
                        </a>

                        <button onclick="printDetailedReport()"
                            class="w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-lg transition-colors flex items-center gap-3">
                            <i class="ri-printer-line"></i>
                            <span>طباعة التقرير</span>
                        </button>

                        <button onclick="exportEquipment()"
                            class="w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-3 rounded-lg transition-colors flex items-center gap-3">
                            <i class="ri-download-line"></i>
                            <span>تصدير البيانات</span>
                        </button>
                    </div>
                </div>

                <!-- Equipment Statistics -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">إحصائيات</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">تاريخ الإضافة</span>
                            <span class="font-medium text-gray-900">{{ $equipment->created_at->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">آخر تحديث</span>
                            <span class="font-medium text-gray-900">{{ $equipment->updated_at->diffForHumans() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">العمر بالأشهر</span>
                            <span
                                class="font-medium text-gray-900">{{ round($equipment->purchase_date->diffInMonths(now())) }}</span>
                        </div>

                        @if ($equipment->last_maintenance)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">آخر صيانة</span>
                                <span
                                    class="font-medium text-gray-900">{{ $equipment->last_maintenance->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">قيمة الإهلاك (تقديري)</span>
                            <span class="font-medium text-gray-900">
                                @php
                                    $months = $equipment->purchase_date->diffInMonths(now());
                                    $depreciation = max(
                                        0,
                                        $equipment->purchase_price - ($equipment->purchase_price * 0.1 * $months) / 12,
                                    );
                                @endphp
                                {{ number_format($depreciation, 0) }} ر.س
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Driver & Movement Management Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Driver Management -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">إدارة السائق</h3>
                    <button onclick="showChangeDriverModal()"
                        class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-user-settings-line"></i>
                        تغيير السائق
                    </button>
                </div>

                <!-- Current Driver -->
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">السائق الحالي</h4>
                    @if ($equipment->driver)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="ri-user-line text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-blue-900">{{ $equipment->driver->name }}</div>
                                    <div class="text-sm text-blue-700">{{ $equipment->driver->position ?? 'سائق' }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center text-gray-500">
                            <i class="ri-user-unfollow-line text-2xl mb-2"></i>
                            <p>لا يوجد سائق محدد</p>
                        </div>
                    @endif
                </div>

                <!-- Driver History -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">تاريخ السائقين</h4>
                    <div id="driverHistoryContainer" class="space-y-2 max-h-48 overflow-y-auto">
                        <div class="text-center py-4">
                            <i class="ri-loader-4-line text-gray-400 text-xl animate-spin"></i>
                            <p class="text-gray-500 mt-2">جاري تحميل التاريخ...</p>
                        </div>
                    </div>
                    <button onclick="loadDriverHistory()" class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                        <i class="ri-refresh-line"></i> تحديث
                    </button>
                </div>
            </div>

            <!-- Movement Management -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">إدارة التحركات</h3>
                    <button onclick="showMoveEquipmentModal()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-map-pin-add-line"></i>
                        نقل المعدة
                    </button>
                </div>

                <!-- Current Location -->
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">الموقع الحالي</h4>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="ri-map-pin-line text-green-600"></i>
                            </div>
                            <div>
                                @if ($equipment->locationDetail)
                                    <div class="font-medium text-green-900">{{ $equipment->locationDetail->name }}</div>
                                    <div class="text-sm text-green-700">موقع منظم</div>
                                @elseif($equipment->location)
                                    <div class="font-medium text-green-900">{{ $equipment->location }}</div>
                                    <div class="text-sm text-green-700">موقع نصي</div>
                                @else
                                    <div class="font-medium text-gray-500">غير محدد</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Movement History -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">تاريخ التحركات</h4>
                    <div id="movementHistoryContainer" class="space-y-2 max-h-48 overflow-y-auto">
                        <div class="text-center py-4">
                            <i class="ri-loader-4-line text-gray-400 text-xl animate-spin"></i>
                            <p class="text-gray-500 mt-2">جاري تحميل التاريخ...</p>
                        </div>
                    </div>
                    <button onclick="loadMovementHistory()" class="mt-2 text-green-600 hover:text-green-800 text-sm">
                        <i class="ri-refresh-line"></i> تحديث
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Driver Modal -->
    <div id="changeDriverModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50"
        onclick="hideChangeDriverModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">تغيير السائق</h3>
                    <button onclick="hideChangeDriverModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('equipment.change-driver', $equipment) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="new_driver_id" class="block text-sm font-medium text-gray-700 mb-2">السائق
                            الجديد</label>
                        <select id="new_driver_id" name="new_driver_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">إزالة السائق</option>
                            @foreach ($employees ?? [] as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ $equipment->driver_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="assigned_at" class="block text-sm font-medium text-gray-700 mb-2">تاريخ
                            التعيين</label>
                        <input type="datetime-local" id="assigned_at" name="assigned_at"
                            value="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            required>
                    </div>

                    <div>
                        <label for="assignment_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات
                            التعيين</label>
                        <textarea id="assignment_notes" name="assignment_notes" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            placeholder="ملاحظات حول تعيين السائق الجديد"></textarea>
                    </div>

                    @if ($equipment->driver)
                        <div>
                            <label for="release_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات تسليم
                                السائق السابق</label>
                            <textarea id="release_notes" name="release_notes" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                placeholder="ملاحظات حول انتهاء تعيين السائق السابق"></textarea>
                        </div>
                    @endif

                    <div class="flex gap-3 pt-4">
                        <button type="submit"
                            class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                            حفظ التغييرات
                        </button>
                        <button type="button" onclick="hideChangeDriverModal()"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Move Equipment Modal -->
    <div id="moveEquipmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50"
        onclick="hideMoveEquipmentModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">نقل المعدة</h3>
                    <button onclick="hideMoveEquipmentModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('equipment.move', $equipment) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="new_location_id" class="block text-sm font-medium text-gray-700 mb-2">الموقع
                            الجديد</label>
                        <select id="new_location_id" name="new_location_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">اختر موقع منظم</option>
                            @foreach ($locations ?? [] as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="moved_at" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النقل</label>
                        <input type="datetime-local" id="moved_at" name="moved_at"
                            value="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>

                    <div>
                        <label for="movement_type" class="block text-sm font-medium text-gray-700 mb-2">نوع التحرك</label>
                        <select id="movement_type" name="movement_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>
                            <option value="location_change">تغيير موقع</option>
                            <option value="maintenance">صيانة</option>
                            <option value="disposal">إتلاف</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>

                    <div>
                        <label for="movement_reason" class="block text-sm font-medium text-gray-700 mb-2">سبب
                            النقل</label>
                        <textarea id="movement_reason" name="movement_reason" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="اذكر سبب نقل المعدة"></textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات إضافية</label>
                        <textarea id="notes" name="notes" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="ملاحظات حول عملية النقل"></textarea>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                            نقل المعدة
                        </button>
                        <button type="button" onclick="hideMoveEquipmentModal()"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center">
        <div class="relative max-w-4xl max-h-full mx-4">
            <img id="modalImage" class="max-w-full max-h-full object-contain rounded-lg" alt="">
            <div class="absolute top-4 right-4">
                <button onclick="closeImageModal()"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-full p-2 transition-all">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <div class="absolute bottom-4 left-4 right-4 text-center">
                <p id="modalImageTitle" class="text-white bg-black bg-opacity-50 rounded-lg px-4 py-2"></p>
            </div>
        </div>
    </div>

    <script>
        // Image modal functions
        function openImageModal(imageSrc, title) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalImageTitle');

            modalImage.src = imageSrc;
            modalTitle.textContent = title;
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Prevent body scrolling
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Restore body scrolling
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Export equipment function
        function exportEquipment() {
            const equipmentData = {
                name: '{{ $equipment->name }}',
                type: '{{ $equipment->type }}',
                manufacturer: '{{ $equipment->manufacturer }}',
                model: '{{ $equipment->model }}',
                serial_number: '{{ $equipment->serial_number }}',
                purchase_price: {{ $equipment->purchase_price }},
                purchase_date: '{{ $equipment->purchase_date }}',
                warranty_expiry: '{{ $equipment->warranty_expiry }}',
                status: '{{ $equipment->status }}',
                location: '{{ $equipment->location }}',
                description: '{{ $equipment->description }}'
            };

            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(equipmentData, null, 2));
            const downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download", "equipment_{{ $equipment->id }}_{{ $equipment->name }}.json");
            document.body.appendChild(downloadAnchorNode);
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
        }

        // Print styles
        const printStyles = `
    @media print {
        body * { visibility: hidden; }
        .print-section, .print-section * { visibility: visible; }
        .print-section { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
`;

        const styleSheet = document.createElement("style");
        styleSheet.type = "text/css";
        styleSheet.innerText = printStyles;
        document.head.appendChild(styleSheet);

        // Add print class to main content
        document.querySelector('.p-6').classList.add('print-section');
        // Add no-print class to action buttons
        document.querySelectorAll('button, .bg-blue-600, .bg-red-600').forEach(el => {
            if (el.textContent.includes('تعديل') || el.textContent.includes('حذف')) {
                el.classList.add('no-print');
            }
        });

        // Driver and Movement Management Functions
        function showChangeDriverModal() {
            document.getElementById('changeDriverModal').classList.remove('hidden');
        }

        function hideChangeDriverModal() {
            document.getElementById('changeDriverModal').classList.add('hidden');
        }

        function showMoveEquipmentModal() {
            document.getElementById('moveEquipmentModal').classList.remove('hidden');
        }

        function hideMoveEquipmentModal() {
            document.getElementById('moveEquipmentModal').classList.add('hidden');
        }

        function loadDriverHistory() {
            const container = document.getElementById('driverHistoryContainer');
            container.innerHTML = `
        <div class="text-center py-4">
            <i class="ri-loader-4-line text-gray-400 text-xl animate-spin"></i>
            <p class="text-gray-500 mt-2">جاري تحميل التاريخ...</p>
        </div>
    `;

            fetch(`/equipment/{{ $equipment->id }}/driver-history`)
                .then(response => response.json())
                .then(data => {
                    if (data.data.length === 0) {
                        container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="ri-user-unfollow-line text-2xl mb-2"></i>
                        <p>لا يوجد تاريخ للسائقين</p>
                    </div>
                `;
                        return;
                    }

                    container.innerHTML = data.data.map(item => `
                <div class="border border-gray-200 rounded-lg p-3 ${item.status === 'active' ? 'bg-green-50 border-green-200' : 'bg-gray-50'}">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-medium text-gray-900">
                            ${item.driver ? item.driver.name : 'سائق محذوف'}
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full ${
                            item.status === 'active' ? 'bg-green-100 text-green-800' :
                            item.status === 'completed' ? 'bg-blue-100 text-blue-800' :
                            'bg-red-100 text-red-800'
                        }">
                            ${item.status === 'active' ? 'نشط' : item.status === 'completed' ? 'مكتمل' : 'منتهي'}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div>من: ${new Date(item.assigned_at).toLocaleString('ar-SA')}</div>
                        ${item.released_at ? `<div>إلى: ${new Date(item.released_at).toLocaleString('ar-SA')}</div>` : ''}
                        ${item.assignment_notes ? `<div class="mt-1 text-gray-500">${item.assignment_notes}</div>` : ''}
                    </div>
                </div>
            `).join('');
                })
                .catch(error => {
                    console.error('Error loading driver history:', error);
                    container.innerHTML = `
                <div class="text-center py-4 text-red-500">
                    <i class="ri-error-warning-line text-xl"></i>
                    <p class="mt-2">خطأ في تحميل البيانات</p>
                </div>
            `;
                });
        }

        function loadMovementHistory() {
            const container = document.getElementById('movementHistoryContainer');
            container.innerHTML = `
        <div class="text-center py-4">
            <i class="ri-loader-4-line text-gray-400 text-xl animate-spin"></i>
            <p class="text-gray-500 mt-2">جاري تحميل التاريخ...</p>
        </div>
    `;

            fetch(`/equipment/{{ $equipment->id }}/movement-history`)
                .then(response => response.json())
                .then(data => {
                    if (data.data.length === 0) {
                        container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="ri-map-pin-line text-2xl mb-2"></i>
                        <p>لا يوجد تاريخ للتحركات</p>
                    </div>
                `;
                        return;
                    }

                    container.innerHTML = data.data.map(item => `
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-medium text-gray-900">
                            ${getMovementTypeText(item.movement_type)}
                        </div>
                        <div class="text-sm text-gray-500">
                            ${new Date(item.moved_at).toLocaleString('ar-SA')}
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <span>من:</span>
                            <span class="text-red-600">${item.from_location_name || 'غير محدد'}</span>
                            <i class="ri-arrow-left-line text-gray-400"></i>
                            <span>إلى:</span>
                            <span class="text-green-600">${item.to_location_name || 'غير محدد'}</span>
                        </div>
                        ${item.movement_reason ? `<div class="mt-1 text-gray-500">${item.movement_reason}</div>` : ''}
                        ${item.notes ? `<div class="mt-1 text-gray-400 text-xs">${item.notes}</div>` : ''}
                    </div>
                </div>
            `).join('');
                })
                .catch(error => {
                    console.error('Error loading movement history:', error);
                    container.innerHTML = `
                <div class="text-center py-4 text-red-500">
                    <i class="ri-error-warning-line text-xl"></i>
                    <p class="mt-2">خطأ في تحميل البيانات</p>
                </div>
            `;
                });
        }

        function getMovementTypeText(type) {
            const types = {
                'location_change': 'تغيير موقع',
                'initial_assignment': 'تعيين أولي',
                'maintenance': 'صيانة',
                'disposal': 'إتلاف',
                'other': 'أخرى'
            };
            return types[type] || type;
        }

        // Load history on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDriverHistory();
            loadMovementHistory();
        });

        // Print Detailed Report Function (Same as detailed report page)
        function printDetailedReport() {
            // Open the detailed report page and print it
            var reportWindow = window.open('{{ route('equipment.report', $equipment) }}', '_blank');

            reportWindow.onload = function() {
                reportWindow.print();
            };
        }

        // Print Report Function
        function printReport() {
            // Create print window
            var printWindow = window.open('', '_blank');

            var printContent = `
    <!DOCTYPE html>
    <html dir="rtl" lang="ar">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>تقرير المعدة: {{ $equipment->name }}</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700&display=swap');

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Cairo', Arial, sans-serif;
                direction: rtl;
                line-height: 1.6;
                color: #333;
                background: white;
                padding: 20px;
            }

            .header {
                text-align: center;
                border-bottom: 3px solid #3b82f6;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }

            .company-name {
                font-size: 24px;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 5px;
            }

            .report-title {
                font-size: 20px;
                font-weight: 600;
                color: #3b82f6;
                margin-bottom: 10px;
            }

            .print-date {
                font-size: 14px;
                color: #6b7280;
            }

            .section {
                margin-bottom: 25px;
                break-inside: avoid;
            }

            .section-title {
                font-size: 18px;
                font-weight: 600;
                color: #1f2937;
                border-bottom: 2px solid #e5e7eb;
                padding-bottom: 8px;
                margin-bottom: 15px;
            }

            .info-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                margin-bottom: 20px;
            }

            .info-item {
                background: #f9fafb;
                padding: 12px;
                border-radius: 8px;
                border: 1px solid #e5e7eb;
            }

            .info-label {
                font-weight: 600;
                color: #374151;
                margin-bottom: 5px;
            }

            .info-value {
                color: #6b7280;
                font-size: 14px;
            }

            .status-badge {
                display: inline-block;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
            }

            .status-available { background: #dcfce7; color: #16a34a; }
            .status-in_use { background: #dbeafe; color: #2563eb; }
            .status-maintenance { background: #fef3c7; color: #d97706; }
            .status-out_of_service { background: #fee2e2; color: #dc2626; }

            .table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
            }

            .table th, .table td {
                border: 1px solid #e5e7eb;
                padding: 8px 12px;
                text-align: right;
            }

            .table th {
                background: #f3f4f6;
                font-weight: 600;
                color: #374151;
            }

            .table tr:nth-child(even) {
                background: #f9fafb;
            }

            @media print {
                body { padding: 0; }
                .section { page-break-inside: avoid; }
            }

            .current-driver {
                background: #eff6ff;
                border: 1px solid #3b82f6;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 15px;
            }

            .current-driver-title {
                font-weight: 600;
                color: #1e40af;
                margin-bottom: 8px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="company-name">شركة أبراج</div>
            <div class="report-title">تقرير المعدة: {{ $equipment->name }}</div>
            <div class="print-date">تاريخ الطباعة: ${new Date().toLocaleDateString('ar-SA')}</div>
        </div>

        <div class="section">
            <div class="section-title">معلومات المعدة الأساسية</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">رقم اللوحة</div>
                    <div class="info-value">{{ $equipment->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">النوع</div>
                    <div class="info-value">{{ $equipment->type }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الطراز</div>
                    <div class="info-value">{{ $equipment->model ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الرقم التسلسلي</div>
                    <div class="info-value">{{ $equipment->serial_number ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الحالة</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $equipment->status }}">
                            @if ($equipment->status === 'available') متاحة
                            @elseif ($equipment->status === 'in_use') قيد الاستخدام
                            @elseif ($equipment->status === 'maintenance') تحت الصيانة
                            @else خارج الخدمة
                            @endif
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">الوصف</div>
                    <div class="info-value">{{ $equipment->description ?? 'لا يوجد وصف' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">المعلومات المالية</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">سعر الشراء</div>
                    <div class="info-value">{{ number_format((float) $equipment->purchase_price, 0) }} ريال سعودي</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ الشراء</div>
                    <div class="info-value">{{ $equipment->purchase_date->format('d/m/Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ انتهاء الضمان</div>
                    <div class="info-value">{{ $equipment->warranty_expiry ? $equipment->warranty_expiry->format('d/m/Y') : 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">المورد</div>
                    <div class="info-value">{{ $equipment->supplier ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">معلومات الموقع</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">الموقع الحالي</div>
                    <div class="info-value">{{ $equipment->locationDetail ? $equipment->locationDetail->name : 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الموقع النصي</div>
                    <div class="info-value">{{ $equipment->location ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>

        @if ($equipment->driver)
        <div class="section">
            <div class="section-title">السائق الحالي</div>
            <div class="current-driver">
                <div class="current-driver-title">{{ $equipment->driver->name }}</div>
                <div class="info-value">المنصب: {{ $equipment->driver->position ?? 'سائق' }}</div>
                <div class="info-value">الهاتف: {{ $equipment->driver->phone ?? 'غير محدد' }}</div>
                <div class="info-value">البريد الإلكتروني: {{ $equipment->driver->email ?? 'غير محدد' }}</div>
            </div>
        </div>
        @endif

        @if ($equipment->files && $equipment->files->count() > 0)
        <div class="section">
            <div class="section-title">الملفات المرفقة</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>اسم الملف</th>
                        <th>النوع</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipment->files as $file)
                    <tr>
                        <td>{{ $file->original_name }}</td>
                        <td>{{ $file->file_type }}</td>
                        <td>{{ $file->expiry_date ? \Carbon\Carbon::parse($file->expiry_date)->format('d/m/Y') : 'غير محدد' }}</td>
                        <td>
                            @if ($file->expiry_date && \Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($file->expiry_date)))
                                <span class="status-badge status-out_of_service">منتهي الصلاحية</span>
                            @else
                                <span class="status-badge status-available">ساري</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="section">
            <div class="section-title">معلومات إضافية</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">تاريخ الإنشاء</div>
                    <div class="info-value">{{ $equipment->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">آخر تحديث</div>
                    <div class="info-value">{{ $equipment->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </body>
    </html>
    `;

            printWindow.document.write(printContent);
            printWindow.document.close();

            // Wait for content to load then print
            printWindow.onload = function() {
                printWindow.print();
            };
        }

        // Enhanced Equipment Print Function
        function printEquipmentDetails() {
            // Hide all other content and show only equipment details
            const elementsToHide = document.querySelectorAll('.no-print');
            elementsToHide.forEach(el => el.style.display = 'none');

            // Show print header
            const printHeader = document.querySelector('.hidden.print\\:block');
            if (printHeader) {
                printHeader.style.display = 'block';
                printHeader.style.visibility = 'visible';
            }

            // Ensure equipment cards are visible
            const equipmentCards = document.querySelectorAll('.bg-white.rounded-xl.shadow-sm.border');
            equipmentCards.forEach(card => {
                card.style.display = 'block';
                card.style.visibility = 'visible';
            });

            // Ensure all sections are visible
            const sections = document.querySelectorAll('.p-6, .mb-6');
            sections.forEach(section => {
                section.style.display = 'block';
                section.style.visibility = 'visible';
            });

            // Print
            window.print();

            // Restore original display after printing
            setTimeout(() => {
                elementsToHide.forEach(el => el.style.display = '');
                if (printHeader) {
                    printHeader.style.display = 'none';
                }
            }, 1000);
        }

        // Alternative enhanced print function with custom window
        function enhancedEquipmentPrint() {
            const printContent = document.querySelector('.p-6').innerHTML;
            const printWindow = window.open('', '_blank');

            printWindow.document.write(`
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>تفاصيل المعدة - {{ $equipment->name }}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    direction: rtl;
                    margin: 0;
                    padding: 20px;
                    background: white;
                    color: black;
                }
                .no-print { display: none !important; }
                .bg-white, .bg-gray-50 { background: white !important; border: 1px solid #ddd; }
                .text-gray-900, .text-gray-700, .text-gray-600 { color: black !important; }
                .grid { display: grid; gap: 1rem; }
                .grid-cols-2 { grid-template-columns: 1fr 1fr; }
                .grid-cols-3 { grid-template-columns: 1fr 1fr 1fr; }
                .grid-cols-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
                .p-6 { padding: 1rem; }
                .p-4 { padding: 0.75rem; }
                .p-3 { padding: 0.5rem; }
                .mb-6 { margin-bottom: 1rem; }
                .mb-4 { margin-bottom: 0.75rem; }
                .mb-3 { margin-bottom: 0.5rem; }
                .border { border: 1px solid #ddd; }
                .rounded-xl { border-radius: 8px; }
                .font-bold { font-weight: bold; }
                .font-semibold { font-weight: 600; }
                .text-sm { font-size: 12px; }
                .text-xs { font-size: 10px; }
                .text-lg { font-size: 16px; }
                .text-2xl { font-size: 20px; }
                img { max-width: 100%; height: auto; }
                .flex { display: flex; gap: 1rem; align-items: center; }
                .gap-4 { gap: 1rem; }
                .gap-6 { gap: 1.5rem; }
            </style>
        </head>
        <body>
            <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #3b82f6; padding-bottom: 20px;">
                <h1 style="color: #3b82f6; margin-bottom: 10px;">تفاصيل المعدة</h1>
                <h2 style="color: #1f2937;">{{ $equipment->name }}</h2>
                <p style="color: #6b7280;">تاريخ الطباعة: ${new Date().toLocaleDateString('ar-SA')}</p>
            </div>
            ${printContent}
        </body>
        </html>
    `);

            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        }

        // Fuel Consumption Management
        function openFuelConsumptionModal() {
            document.getElementById('fuelConsumptionModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeFuelConsumptionModal() {
            document.getElementById('fuelConsumptionModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Reload data after modal closes
            loadFuelConsumptions();
            loadFuelConsumptionSummary();
        } // Load fuel consumption data
        function loadFuelConsumptions() {
            fetch(`/equipment-fuel-consumption/{{ $equipment->id }}/consumptions`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP Error: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const tbody = document.getElementById('fuelConsumptionsTableBody');
                    tbody.innerHTML = '';

                    if (!data || data.length === 0) {
                        tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="ri-gas-station-line text-4xl mb-2"></i>
                            <p>لا توجد سجلات استهلاك محروقات</p>
                        </td>
                    </tr>
                `;
                        return;
                    }

                    data.forEach(consumption => {
                        const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="flex flex-col">
                                <span class="font-medium">${consumption.consumption_date_formatted || consumption.consumption_date}</span>
                                <span class="text-xs text-gray-500">تم التسجيل: ${consumption.created_at_formatted || 'غير محدد'}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getFuelTypeColor(consumption.fuel_type)}">
                                ${getFuelTypeText(consumption.fuel_type)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            ${consumption.quantity} لتر
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            ${consumption.notes || '-'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${consumption.user.name}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${consumption.approval_status_color || 'bg-gray-100 text-gray-800'}">
                                ${consumption.approval_status_text || consumption.approval_status}
                            </span>
                            ${consumption.approved_by ? `<div class="text-xs text-gray-500 mt-1">بواسطة: ${consumption.approved_by}</div>` : ''}
                            ${consumption.approval_notes ? `<div class="text-xs text-gray-500 mt-1" title="${consumption.approval_notes}">ملاحظات الموافقة</div>` : ''}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center gap-2">
                                ${consumption.approval_status === 'pending' && {{ $equipment->driver->user_id ?? 0 }} == {{ Auth::id() ?? 0 }} ?
                                    `<button onclick="approveFuelConsumption(${consumption.id}, '${consumption.quantity}', '${getFuelTypeText(consumption.fuel_type)}', '${consumption.user.name}')"
                                                            class="text-green-600 hover:text-green-900" title="اعتماد">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                    <button onclick="rejectFuelConsumption(${consumption.id}, '${consumption.quantity}', '${getFuelTypeText(consumption.fuel_type)}', '${consumption.user.name}')"
                                                            class="text-red-600 hover:text-red-900" title="رفض">
                                                        <i class="ri-close-line"></i>
                                                    </button>` : ''
                                }
                                <button onclick="deleteFuelConsumption(${consumption.id})"
                                        class="text-red-600 hover:text-red-900" title="حذف">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error('Error loading fuel consumptions:', error);
                    const tbody = document.getElementById('fuelConsumptionsTableBody');
                    tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-red-500">
                        <i class="ri-error-warning-line text-4xl mb-2"></i>
                        <p>خطأ في تحميل البيانات: ${error.message}</p>
                    </td>
                </tr>
            `;
                });
        }

        function loadFuelConsumptionSummary() {
            fetch(`/equipment-fuel-consumption/{{ $equipment->id }}/summary`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP Error: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const tbody = document.getElementById('fuelSummaryTableBody');
                    tbody.innerHTML = '';

                    if (!data || data.length === 0) {
                        tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                `;
                        return;
                    }

                    data.forEach(summary => {
                        const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getFuelTypeColor(summary.fuel_type)}">
                                ${getFuelTypeText(summary.fuel_type)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${summary.total_quantity} لتر
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${summary.count} مرة
                        </td>
                    </tr>
                `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error('Error loading fuel consumption summary:', error);
                });
        }

        function getFuelTypeText(fuelType) {
            const types = {
                'diesel': 'ديزل',
                'engine_oil': 'زيت ماكينة',
                'hydraulic_oil': 'زيت هيدروليك',
                'radiator_water': 'ماء ردياتير'
            };
            return types[fuelType] || fuelType;
        }

        function getFuelTypeColor(fuelType) {
            const colors = {
                'diesel': 'bg-blue-100 text-blue-800',
                'engine_oil': 'bg-yellow-100 text-yellow-800',
                'hydraulic_oil': 'bg-purple-100 text-purple-800',
                'radiator_water': 'bg-green-100 text-green-800'
            };
            return colors[fuelType] || 'bg-gray-100 text-gray-800';
        }

        function deleteFuelConsumption(id) {
            currentConsumptionId = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            currentConsumptionId = null;
        }

        // Handle delete form submission
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentConsumptionId) return;

            fetch(`/equipment-fuel-consumption/${currentConsumptionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    closeDeleteModal();
                    loadFuelConsumptions();
                    loadFuelConsumptionSummary();
                } else {
                    alert('حدث خطأ أثناء حذف السجل');
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حذف السجل');
            });
        });

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadFuelConsumptions();
            loadFuelConsumptionSummary();
        });
    </script>

    <!-- Fuel Consumption Modal -->
    <div id="fuelConsumptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-lg w-full shadow-xl" dir="rtl">
                <form method="POST" action="{{ route('equipment-fuel-consumption.store') }}">
                    @csrf
                    <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="ri-gas-station-line text-green-600 ml-2"></i>
                                إضافة استهلاك محروقات
                            </h3>
                            <button type="button" onclick="closeFuelConsumptionModal()"
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4">
                        <div class="space-y-5">
                            <!-- نوع المحروقات -->
                            <div class="space-y-2">
                                <label for="fuel_type"
                                    class="block text-sm font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="ri-gas-station-line text-green-600"></i>
                                    نوع المحروقات
                                </label>
                                <div class="relative">
                                    <select id="fuel_type" name="fuel_type" required
                                        class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm hover:border-green-400 appearance-none bg-white">
                                        <option value="">اختر نوع المحروقات...</option>
                                        <option value="diesel">ديزل</option>
                                        <option value="engine_oil">زيت ماكينة</option>
                                        <option value="hydraulic_oil">زيت هيدروليك</option>
                                        <option value="radiator_water">ماء ردياتير</option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="ri-arrow-down-s-line text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <i class="ri-information-line text-gray-400"></i>
                                    حدد نوع المحروقات المستهلكة
                                </p>
                            </div>

                            <!-- الكمية -->
                            <div class="space-y-2">
                                <label for="quantity"
                                    class="block text-sm font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="ri-speed-line text-blue-600"></i>
                                    الكمية (باللتر)
                                </label>
                                <div class="relative">
                                    <input type="number" id="quantity" name="quantity" step="0.01" min="0.01"
                                        required placeholder="0.00"
                                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm hover:border-green-400">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 font-medium">لتر</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <i class="ri-information-line text-gray-400"></i>
                                    أدخل الكمية المستهلكة باللتر
                                </p>
                            </div>

                            <!-- تاريخ الاستهلاك -->
                            <div class="space-y-2">
                                <label for="consumption_date"
                                    class="block text-sm font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="ri-calendar-line text-purple-600"></i>
                                    تاريخ الاستهلاك
                                </label>
                                <div class="relative">
                                    <input type="date" id="consumption_date" name="consumption_date" required
                                        value="{{ now()->format('Y-m-d') }}"
                                        class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm hover:border-green-400 appearance-none">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="ri-calendar-2-line text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <i class="ri-information-line text-gray-400"></i>
                                    حدد تاريخ استهلاك المحروقات
                                </p>
                            </div>

                            <!-- ملاحظات -->
                            <div class="space-y-2">
                                <label for="notes"
                                    class="block text-sm font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="ri-edit-line text-orange-600"></i>
                                    ملاحظات (اختياري)
                                </label>
                                <div class="relative">
                                    <textarea id="notes" name="notes" rows="4" placeholder="أضف أي ملاحظات إضافية..."
                                        class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm hover:border-green-400 resize-none"></textarea>
                                    <div class="absolute top-3 left-3 pointer-events-none">
                                        <i class="ri-edit-2-line text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <i class="ri-information-line text-gray-400"></i>
                                    يمكنك إضافة تفاصيل إضافية حول الاستهلاك
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" onclick="closeFuelConsumptionModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                            <i class="ri-save-line"></i>
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Fuel Consumption Section -->
    <div class="mt-8 no-print">
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="ri-gas-station-line text-green-600 ml-2"></i>
                    سجل استهلاك المحروقات
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                التاريخ والوقت</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع
                                المحروقات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الكمية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الملاحظات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المسجل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                حالة الموافقة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="fuelConsumptionsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data loaded via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Fuel Consumption Summary -->
    <div class="mt-6 no-print">
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="ri-bar-chart-line text-blue-600 ml-2"></i>
                    ملخص استهلاك المحروقات
                    <span class="text-sm font-normal text-green-600 mr-2">(المعتمد فقط)</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع
                                المحروقات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                إجمالي الكمية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد
                                المرات</th>
                        </tr>
                    </thead>
                    <tbody id="fuelSummaryTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data loaded via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Equipment Maintenance Report -->
    <div class="mt-6 no-print">
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="ri-tools-line text-orange-600 ml-2"></i>
                    تقرير الصيانة
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع
                                الصيانة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                التكلفة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الوصف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($equipment->maintenances()->orderBy('maintenance_date', 'desc')->get() as $maintenance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $maintenance->maintenance_date->format('Y/m/d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $maintenance->maintenance_type === 'internal'
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-purple-100 text-purple-800' }}">
                                        <i
                                            class="ri-{{ $maintenance->maintenance_type === 'internal' ? 'home' : 'building' }}-line ml-1"></i>
                                        {{ $maintenance->maintenance_type_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $maintenance->status === 'completed'
                                        ? 'bg-green-100 text-green-800'
                                        : ($maintenance->status === 'in_progress'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-800') }}">
                                        {{ $maintenance->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($maintenance->external_cost)
                                        {{ number_format($maintenance->external_cost, 2) }} ر.س
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ Str::limit($maintenance->description, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route('equipment-maintenance.show', $maintenance) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="ri-tools-line text-4xl mb-2"></i>
                                    <p>لا توجد سجلات صيانة</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Link Equipment Modal -->
    <div id="linkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-lg bg-white"
            dir="rtl">
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">ربط بشاحنات النقل الداخلي</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="closeLinkModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="linkForm" method="POST" action="{{ route('internal-trucks.linkEquipment') }}" class="mt-4">
                @csrf
                <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-start">
                        <i class="ri-information-line text-blue-600 text-lg ml-2 mt-0.5"></i>
                        <div>
                            <p class="text-blue-800 font-medium">معلومات المعدة</p>
                            <p class="text-blue-700 text-sm mt-1">{{ $equipment->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="linkFuelType" class="block text-sm font-medium text-gray-700 mb-2">
                        نوع المحروقات (اختياري)
                    </label>
                    <select id="linkFuelType" name="fuel_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="">اختر نوع المحروقات</option>
                        <option value="gasoline">بنزين</option>
                        <option value="diesel">ديزل</option>
                        <option value="electric">كهربائي</option>
                        <option value="hybrid">هجين</option>
                    </select>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <div class="flex items-start">
                        <i class="ri-alert-line text-yellow-600 text-lg ml-2 mt-0.5"></i>
                        <p class="text-sm text-yellow-800">
                            <strong>تنبيه:</strong> سيتم إنشاء شاحنة نقل داخلية جديدة وربط هذه المعدة بها.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t">
                    <button type="button" onclick="closeLinkModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg transition-colors flex items-center">
                        <i class="ri-link-line ml-1"></i>
                        ربط الآن
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unlink Equipment Modal -->
    <div id="unlinkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-lg bg-white"
            dir="rtl">
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">إلغاء الربط من شاحنات النقل الداخلي</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="closeUnlinkModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="unlinkForm" method="POST" action="{{ route('internal-trucks.unlinkEquipment') }}" class="mt-4">
                @csrf
                <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-start">
                        <i class="ri-information-line text-blue-600 text-lg ml-2 mt-0.5"></i>
                        <div>
                            <p class="text-blue-800 font-medium">معلومات المعدة</p>
                            <p class="text-blue-700 text-sm mt-1">{{ $equipment->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                    <div class="flex items-start">
                        <i class="ri-alert-line text-red-600 text-lg ml-2 mt-0.5"></i>
                        <p class="text-sm text-red-800">
                            <strong>تحذير:</strong> سيتم إلغاء ربط هذه المعدة وحذف شاحنة النقل الداخلية المرتبطة بها.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t">
                    <button type="button" onclick="closeUnlinkModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors flex items-center">
                        <i class="ri-unlink-line ml-1"></i>
                        إلغاء الربط
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-lg bg-white"
            dir="rtl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">اعتماد استهلاك المحروقات</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="closeApprovalModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="approvalForm" method="POST" class="mt-4">
                @csrf
                @method('PATCH')

                <!-- Consumption Info -->
                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center text-blue-800">
                        <i class="ri-information-line ml-2"></i>
                        <span class="font-medium">معلومات الاستهلاك</span>
                    </div>
                    <div id="consumptionInfo" class="mt-2 text-sm text-blue-700">
                        <!-- سيتم ملء هذا القسم بالـ JavaScript -->
                    </div>
                </div>

                <!-- Approval Notes -->
                <div class="mb-4">
                    <label for="approvalNotes" class="block text-sm font-medium text-gray-700 mb-2">
                        ملاحظات الموافقة (اختيارية)
                    </label>
                    <textarea id="approvalNotes" name="approval_notes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="إضافة أي ملاحظات حول الموافقة..."></textarea>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t">
                    <button type="button" onclick="closeApprovalModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center">
                        <i class="ri-check-line ml-1"></i>
                        اعتماد الاستهلاك
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-lg bg-white"
            dir="rtl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-red-600 flex items-center">
                    <i class="ri-close-circle-line ml-2"></i>
                    رفض استهلاك المحروقات
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="closeRejectionModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="rejectionForm" method="POST" class="mt-4">
                @csrf
                @method('PATCH')

                <!-- Consumption Info -->
                <div class="mb-4 p-4 bg-red-50 rounded-lg">
                    <div class="flex items-center text-red-800">
                        <i class="ri-warning-line ml-2"></i>
                        <span class="font-medium">معلومات الاستهلاك</span>
                    </div>
                    <div id="rejectionConsumptionInfo" class="mt-2 text-sm text-red-700">
                        <!-- سيتم ملء هذا القسم بالـ JavaScript -->
                    </div>
                </div>

                <!-- Rejection Notes (Required) -->
                <div class="mb-4">
                    <label for="rejectionNotes" class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="text-red-600">*</span> سبب الرفض (مطلوب)
                    </label>
                    <textarea id="rejectionNotes" name="approval_notes" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="اشرح السبب من رفضك لهذا الاستهلاك..." required></textarea>
                    <p class="text-xs text-gray-500 mt-1">يجب تقديم سبب واضح للرفض</p>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t">
                    <button type="button" onclick="closeRejectionModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center">
                        <i class="ri-close-circle-line ml-1"></i>
                        تأكيد الرفض
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-lg bg-white"
            dir="rtl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-red-600 flex items-center">
                    <i class="ri-delete-bin-line ml-2"></i>
                    حذف استهلاك المحروقات
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="closeDeleteModal()">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="deleteForm" method="POST" class="mt-4">
                <!-- Warning Message -->
                <div class="mb-4 p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex items-start gap-3">
                        <i class="ri-alert-line text-red-600 text-xl mt-0.5 flex-shrink-0"></i>
                        <div>
                            <h4 class="font-semibold text-red-900 mb-2">تحذير من الحذف الدائم</h4>
                            <p class="text-sm text-red-800">هل أنت متأكد من رغبتك في حذف هذا السجل؟</p>
                            <p class="text-xs text-red-700 mt-2">هذا الإجراء <span class="font-bold">لا يمكن التراجع عنه</span> ولن تتمكن من استرجاع البيانات بعد الحذف.</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center">
                        <i class="ri-delete-bin-line ml-1"></i>
                        تأكيد الحذف
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Approval Functions -->
    <script>
        let currentConsumptionId = null;
        let currentConsumptionData = null;

        // Approve fuel consumption with modal
        function approveFuelConsumption(consumptionId, quantity = '', fuelType = '', addedBy = '') {
            currentConsumptionId = consumptionId;
            currentConsumptionData = {
                quantity,
                fuelType,
                addedBy
            };

            // Fill consumption info in modal
            const consumptionInfo = document.getElementById('consumptionInfo');
            if (quantity) {
                consumptionInfo.innerHTML = `
                    <div><strong>الكمية:</strong> ${quantity} لتر</div>
                    <div><strong>نوع المحروقات:</strong> ${fuelType}</div>
                    <div><strong>أضيفت بواسطة:</strong> ${addedBy}</div>
                `;
            } else {
                consumptionInfo.innerHTML = '<div>جاري تحميل البيانات...</div>';
            }

            // Show modal
            document.getElementById('approvalModal').classList.remove('hidden');
        }

        function closeApprovalModal() {
            document.getElementById('approvalModal').classList.add('hidden');
            document.getElementById('approvalNotes').value = '';
            currentConsumptionId = null;
            currentConsumptionData = null;
        }

        // Handle approval form submission
        document.getElementById('approvalForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentConsumptionId) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/equipment-fuel-consumption/${currentConsumptionId}/approve`;
            form.style.display = 'none';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';
            form.appendChild(methodField);

            const notes = document.getElementById('approvalNotes').value;
            if (notes.trim()) {
                const notesField = document.createElement('input');
                notesField.type = 'hidden';
                notesField.name = 'approval_notes';
                notesField.value = notes;
                form.appendChild(notesField);
            }

            // Close modal
            closeApprovalModal();

            document.body.appendChild(form);
            form.submit();
        });

        // Close modal when clicking outside
        document.getElementById('approvalModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApprovalModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('approvalModal').classList.contains('hidden')) {
                closeApprovalModal();
            }
        });

        // Reject fuel consumption with modal
        function rejectFuelConsumption(consumptionId, quantity = '', fuelType = '', addedBy = '') {
            currentConsumptionId = consumptionId;
            currentConsumptionData = {
                quantity,
                fuelType,
                addedBy
            };

            // Clear previous notes
            document.getElementById('rejectionNotes').value = '';

            // Display consumption info
            let infoHtml = '<ul class="list-disc list-inside space-y-1">';
            if (addedBy) infoHtml += `<li><strong>المسجل:</strong> ${addedBy}</li>`;
            if (fuelType) infoHtml += `<li><strong>نوع المحروقات:</strong> ${fuelType}</li>`;
            if (quantity) infoHtml += `<li><strong>الكمية:</strong> ${parseFloat(quantity).toFixed(2)} لتر</li>`;
            infoHtml += '</ul>';
            document.getElementById('rejectionConsumptionInfo').innerHTML = infoHtml;

            // Set form action
            document.getElementById('rejectionForm').action = `/equipment-fuel-consumption/${consumptionId}/reject`;

            // Show modal
            document.getElementById('rejectionModal').classList.remove('hidden');
        }

        // Close rejection modal
        function closeRejectionModal() {
            document.getElementById('rejectionModal').classList.add('hidden');
            document.getElementById('rejectionForm').reset();
            currentConsumptionId = null;
        }

        // Handle rejection form submission
        document.getElementById('rejectionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const notes = document.getElementById('rejectionNotes').value.trim();

            if (!notes) {
                alert('يجب تقديم سبب للرفض');
                return;
            }

            this.submit();
        });

        // Link equipment modal functions
        function openLinkModal() {
            document.getElementById('linkModal').classList.remove('hidden');
        }

        function closeLinkModal() {
            document.getElementById('linkModal').classList.add('hidden');
        }

        function submitLinkForm() {
            const form = document.getElementById('linkForm');
            form.submit();
        }

        // Unlink equipment modal functions
        function openUnlinkModal() {
            document.getElementById('unlinkModal').classList.remove('hidden');
        }

        function closeUnlinkModal() {
            document.getElementById('unlinkModal').classList.add('hidden');
        }

        function submitUnlinkForm() {
            const form = document.getElementById('unlinkForm');
            form.submit();
        }

        // Close modals when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const linkModal = document.getElementById('linkModal');
            const unlinkModal = document.getElementById('unlinkModal');

            if (linkModal) {
                linkModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeLinkModal();
                    }
                });
            }

            if (unlinkModal) {
                unlinkModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeUnlinkModal();
                    }
                });
            }
        });
    </script>
    </script>
@endsection
