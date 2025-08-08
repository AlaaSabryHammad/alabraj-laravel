@extends('layouts.app')

@section('title', 'تفاصيل الشاحنة الداخلية: ' . $internalTruck->plate_number)

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

            /* Truck cards visibility */
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
                <a href="{{ route('internal-trucks.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $internalTruck->plate_number }}</h1>
                    <p class="text-gray-600 mt-1">تفاصيل الشاحنة الداخلية</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="printDetailedReport()"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-printer-line"></i>
                    طباعة التقرير
                </button>
                <a href="{{ route('internal-trucks.edit', $internalTruck) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-edit-line"></i>
                    تعديل
                </a>
                <form action="{{ route('internal-trucks.destroy', $internalTruck) }}" method="POST" class="inline"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذه الشاحنة الداخلية؟')">
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
                <h1 class="text-3xl font-bold text-blue-600 mb-2">تفاصيل الشاحنة الداخلية</h1>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $internalTruck->plate_number }}</h2>
                <div class="flex justify-between items-center text-sm text-gray-600 mt-4">
                    <div>
                        <p class="font-semibold">شركة أبراج للمقاولات العامة</p>
                        <p>إدارة الشاحنات الداخلية</p>
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
                        @if ($internalTruck->status === 'متاح') bg-green-500
                        @elseif($internalTruck->status === 'قيد الاستخدام') bg-blue-500
                        @elseif($internalTruck->status === 'تحت الصيانة') bg-yellow-500
                        @else bg-red-500 @endif">
                        </div>
                        <span class="font-medium text-gray-900">
                            @if ($internalTruck->status === 'متاح')
                                متاحة
                            @elseif($internalTruck->status === 'قيد الاستخدام')
                                قيد الاستخدام
                            @elseif($internalTruck->status === 'تحت الصيانة')
                                تحت الصيانة
                            @else
                                خارج الخدمة
                            @endif
                        </span>
                    </div>
                    <span class="text-sm text-gray-500">
                        آخر تحديث: {{ $internalTruck->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Truck Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Purchase Price -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="ri-money-dollar-circle-line text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">سعر الشراء</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($internalTruck->purchase_price, 0) }}
                        </p>
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
                        <p class="text-lg font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($internalTruck->purchase_date)->format('d/m/Y') }}</p>
                        <p class="text-gray-500 text-xs">
                            {{ \Carbon\Carbon::parse($internalTruck->purchase_date)->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Load Capacity -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <i class="ri-truck-line text-orange-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">سعة التحميل</p>
                        <p class="text-lg font-bold text-gray-900">
                            @if ($internalTruck->load_capacity)
                                {{ number_format($internalTruck->load_capacity, 1) }}
                            @else
                                غير محدد
                            @endif
                        </p>
                        <p class="text-gray-500 text-xs">طن</p>
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
                        <p class="text-gray-600 text-sm">عمر الشاحنة</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ round(\Carbon\Carbon::parse($internalTruck->purchase_date)->diffInMonths(now())) }}
                        </p>
                        <p class="text-gray-500 text-xs">شهر</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Truck Images -->
        @if ($internalTruck->images && count($internalTruck->images) > 0)
            <div class="bg-white rounded-xl shadow-sm border mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">صور الشاحنة</h2>
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ count($internalTruck->images) }} صورة
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($internalTruck->images as $index => $imagePath)
                            <div class="relative group cursor-pointer"
                                onclick="openImageModal('{{ asset('storage/' . $imagePath) }}', '{{ $internalTruck->plate_number }} - صورة {{ $index + 1 }}')">
                                <img src="{{ asset('storage/' . $imagePath) }}"
                                    class="w-full h-32 object-cover rounded-lg border border-gray-200 transition-transform group-hover:scale-105"
                                    alt="صورة الشاحنة {{ $index + 1 }}"
                                    onerror="this.src='/assets/images/truck-placeholder.svg'">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30
                                transition-all rounded-lg flex items-center justify-center">
                                    <i class="ri-eye-line text-white opacity-0 group-hover:opacity-100 text-2xl"></i>
                                </div>
                                <div
                                    class="absolute bottom-2 left-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">صور الشاحنة</h2>
                </div>
                <div class="p-6">
                    <div class="text-center py-8">
                        <i class="ri-image-line text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg mb-4">لم يتم رفع صور للشاحنة بعد</p>
                        <a href="{{ route('internal-trucks.edit', $internalTruck) }}"
                            class="inline-flex items-center px-4 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors">
                            <i class="ri-add-line ml-2"></i>
                            إضافة صور
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Truck Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">معلومات الشاحنة</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">رقم اللوحة</h3>
                                <p class="text-gray-900 font-medium">{{ $internalTruck->plate_number }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">العلامة التجارية</h3>
                                <p class="text-gray-900">{{ $internalTruck->brand }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">الموديل</h3>
                                <p class="text-gray-900">{{ $internalTruck->model ?? 'غير محدد' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">سنة الصنع</h3>
                                <p class="text-gray-900">{{ $internalTruck->year ?? 'غير محدد' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">اللون</h3>
                                <p class="text-gray-900">{{ $internalTruck->color ?? 'غير محدد' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">نوع الوقود</h3>
                                <p class="text-gray-900">
                                    @if ($internalTruck->fuel_type === 'بنزين')
                                        البنزين
                                    @elseif($internalTruck->fuel_type === 'ديزل')
                                        الديزل
                                    @elseif($internalTruck->fuel_type === 'هجين')
                                        هجين
                                    @else
                                        غير محدد
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Engine & Chassis Numbers -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($internalTruck->engine_number)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">رقم المحرك</h3>
                                    <div class="bg-gray-50 rounded-lg p-3 font-mono text-sm">
                                        {{ $internalTruck->engine_number }}
                                    </div>
                                </div>
                            @endif

                            @if ($internalTruck->chassis_number)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">رقم الهيكل</h3>
                                    <div class="bg-gray-50 rounded-lg p-3 font-mono text-sm">
                                        {{ $internalTruck->chassis_number }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Driver -->
                        @if ($internalTruck->driver)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">السائق المسؤول</h3>
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center gap-3">
                                        <i class="ri-user-line text-blue-600"></i>
                                        <span class="text-blue-900 font-medium">{{ $internalTruck->driver->name }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Description -->
                        @if ($internalTruck->description)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-2">الوصف</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-700 leading-relaxed">{{ $internalTruck->description }}</p>
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
                        <a href="{{ route('internal-trucks.edit', $internalTruck) }}"
                            class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg transition-colors flex items-center gap-3">
                            <i class="ri-edit-line"></i>
                            <span>تعديل بيانات الشاحنة</span>
                        </a>

                        <button onclick="printDetailedReport()"
                            class="w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-lg transition-colors flex items-center gap-3">
                            <i class="ri-printer-line"></i>
                            <span>طباعة التقرير</span>
                        </button>

                        <button onclick="exportTruck()"
                            class="w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-3 rounded-lg transition-colors flex items-center gap-3">
                            <i class="ri-download-line"></i>
                            <span>تصدير البيانات</span>
                        </button>
                    </div>
                </div>

                <!-- Truck Statistics -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">إحصائيات</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">تاريخ الإضافة</span>
                            <span
                                class="font-medium text-gray-900">{{ $internalTruck->created_at->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">آخر تحديث</span>
                            <span
                                class="font-medium text-gray-900">{{ $internalTruck->updated_at->diffForHumans() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">العمر بالأشهر</span>
                            <span
                                class="font-medium text-gray-900">{{ round(\Carbon\Carbon::parse($internalTruck->purchase_date)->diffInMonths(now())) }}</span>
                        </div>

                        @if ($internalTruck->last_maintenance)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">آخر صيانة</span>
                                <span
                                    class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($internalTruck->last_maintenance)->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        @if ($internalTruck->warranty_expiry)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">انتهاء الضمان</span>
                                <span
                                    class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($internalTruck->warranty_expiry)->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        @if ($internalTruck->license_expiry)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">انتهاء الرخصة</span>
                                <span
                                    class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($internalTruck->license_expiry)->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        @if ($internalTruck->insurance_expiry)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">انتهاء التأمين</span>
                                <span
                                    class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($internalTruck->insurance_expiry)->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">قيمة الإهلاك (تقديري)</span>
                            <span class="font-medium text-gray-900">
                                @php
                                    $months = \Carbon\Carbon::parse($internalTruck->purchase_date)->diffInMonths(now());
                                    $depreciation = max(
                                        0,
                                        $internalTruck->purchase_price -
                                            ($internalTruck->purchase_price * 0.1 * $months) / 12,
                                    );
                                @endphp
                                {{ number_format($depreciation, 0) }} ر.س
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Schedule -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">جدول الصيانة</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if ($internalTruck->last_maintenance)
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <i class="ri-tools-line text-green-600"></i>
                                    <span class="text-sm text-green-900">آخر صيانة</span>
                                </div>
                                <span class="text-sm font-medium text-green-900">
                                    {{ \Carbon\Carbon::parse($internalTruck->last_maintenance)->format('d/m/Y') }}
                                </span>
                            </div>
                        @endif

                        @if ($internalTruck->license_expiry)
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <i class="ri-file-text-line text-blue-600"></i>
                                    <span class="text-sm text-blue-900">تجديد الرخصة</span>
                                </div>
                                <span class="text-sm font-medium text-blue-900">
                                    {{ \Carbon\Carbon::parse($internalTruck->license_expiry)->format('d/m/Y') }}
                                </span>
                            </div>
                        @endif

                        @if ($internalTruck->insurance_expiry)
                            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <i class="ri-shield-line text-orange-600"></i>
                                    <span class="text-sm text-orange-900">تجديد التأمين</span>
                                </div>
                                <span class="text-sm font-medium text-orange-900">
                                    {{ \Carbon\Carbon::parse($internalTruck->insurance_expiry)->format('d/m/Y') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center"
        onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-full p-4">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <i class="ri-close-line text-2xl"></i>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
            <div id="modalCaption" class="text-white text-center mt-4"></div>
        </div>
    </div>

    <script>
        function openImageModal(imageSrc, caption) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalCaption').textContent = caption;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function printDetailedReport() {
            window.print();
        }

        function exportTruck() {
            // Implementation for exporting truck data
            alert('تصدير البيانات قيد التطوير');
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection
