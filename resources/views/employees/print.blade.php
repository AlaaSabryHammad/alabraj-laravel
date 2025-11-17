<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة الموظف - {{ $employee->name }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Tajawal Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

    <!-- Simple QR Code Generator (Alternative) -->
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: white !important;
            color: #000 !important;
        }

        /* Print Optimizations */
        @media print {
            @page {
                size: A4;
                margin: 5mm;
                color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 11pt !important;
                line-height: 1.4 !important;
            }

            .print-break {
                page-break-before: always;
            }

            .avoid-break {
                page-break-inside: avoid;
            }

            .no-print {
                display: none !important;
            }

            .bg-gradient-to-br {
                background: #1e40af !important;
            }

            .shadow-lg, .shadow-sm, .shadow-md {
                box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
            }

            /* Remove all margins and padding for compact printing */
            .employee-card {
                border-radius: 0 !important;
                margin: 0 !important;
            }

            .rounded-t-xl, .rounded-b-xl {
                border-radius: 0 !important;
            }

            /* Enhanced image printing */
            .w-32 {
                width: 8rem !important;
                height: 8rem !important;
            }

            .w-32 img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                -webkit-print-color-adjust: exact !important;
            }
        }

        /* Custom Styles */
        .employee-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Remove gaps between sections */
        .section-compact {
            margin-bottom: 0 !important;
            margin-top: 0 !important;
        }

        .section-compact + .section-compact {
            border-top: 1px solid #f3f4f6;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .document-status-valid {
            color: #059669;
        }

        .document-status-warning {
            color: #d97706;
        }

        .document-status-expired {
            color: #dc2626;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 6rem;
            color: rgba(59, 130, 246, 0.05);
            z-index: -1;
            font-weight: 900;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-white min-h-screen">
    <!-- Watermark -->
    <div class="watermark">شركة الأبراج</div>

    <div class="max-w-4xl mx-auto p-0" style="background-color: white !important;">
        <!-- Content will render here -->
        <!-- Print Header -->
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-purple-700 text-white p-3 rounded-t-xl employee-card avoid-break">
            <div class="text-center mb-3">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-full mb-2">
                    <i class="ri-building-line text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold mb-1">شركة الأبراج للمقاولات المحدودة</h1>
                <p class="text-blue-100 text-sm">Al-Abraj Contracting Company Limited</p>
                <div class="mt-2 inline-block bg-white bg-opacity-15 px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold">بطاقة تعريف الموظف - Employee ID Card</span>
                </div>
            </div>

            <div class="flex justify-between items-center text-xs border-t border-blue-400 pt-2">
                <div class="flex items-center gap-1">
                    <i class="ri-calendar-line"></i>
                    <span>{{ now()->format('Y/m/d') }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <i class="ri-time-line"></i>
                    <span>{{ now()->format('H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Employee Profile Section -->
        <div class="bg-white p-3 border-l-4 border-blue-600 avoid-break section-compact">
            <div class="flex items-start gap-6">
                <!-- Photo Section -->
                <div class="flex-shrink-0">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-xl overflow-hidden border-3 border-gray-200 bg-gray-100 flex items-center justify-center shadow-md">
                            @if($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                     alt="{{ $employee->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <i class="ri-user-line text-4xl text-gray-400"></i>
                            @endif
                        </div>
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                            #{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>

                <!-- Employee Details -->
                <div class="flex-1 min-w-0">
                    <!-- Name and Status Row -->
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $employee->name }}</h2>
                            <p class="text-lg text-blue-600 font-semibold">{{ $employee->position ?? 'موظف' }}</p>
                        </div>
                        <span class="status-badge {{ $employee->status === 'active' ? 'status-active' : 'status-inactive' }} flex-shrink-0">
                            <i class="ri-checkbox-circle-line mr-1"></i>
                            {{ $employee->status === 'active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>

                    <!-- Compact Info Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center gap-1 text-gray-600 text-xs mb-1">
                                <i class="ri-building-4-line text-sm"></i>
                                <span>القسم</span>
                            </div>
                            <div class="font-semibold text-gray-900 text-sm truncate">{{ $employee->department ?? 'غير محدد' }}</div>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center gap-1 text-gray-600 text-xs mb-1">
                                <i class="ri-map-pin-line text-sm"></i>
                                <span>الموقع</span>
                            </div>
                            <div class="font-semibold text-gray-900 text-sm truncate">{{ $employee->location ? $employee->location->name : 'غير محدد' }}</div>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center gap-1 text-gray-600 text-xs mb-1">
                                <i class="ri-shield-user-line text-sm"></i>
                                <span>الصلاحية</span>
                            </div>
                            <div class="font-semibold text-gray-900 text-sm truncate">{{ $employee->role ?? 'غير محدد' }}</div>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center gap-1 text-gray-600 text-xs mb-1">
                                <i class="ri-calendar-check-line text-sm"></i>
                                <span>التوظيف</span>
                            </div>
                            <div class="font-semibold text-blue-600 text-sm">{{ $employee->hire_date ? $employee->hire_date->format('Y/m/d') : 'غير محدد' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="bg-white p-3 avoid-break section-compact">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="ri-user-line text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">المعلومات الشخصية</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">رقم الهوية</label>
                    <div class="text-sm font-semibold text-gray-900">{{ $employee->national_id ?? 'غير محدد' }}</div>
                </div>

                @if($employee->national_id_expiry_date)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">انتهاء الهوية</label>
                    <div class="text-sm font-semibold {{ $employee->isDateExpired('national_id_expiry_date') ? 'document-status-expired' : ($employee->getDaysUntilExpiry('national_id_expiry_date') && $employee->getDaysUntilExpiry('national_id_expiry_date') <= 90 ? 'document-status-warning' : 'document-status-valid') }}">
                        {{ $employee->national_id_expiry_date ? $employee->national_id_expiry_date->format('Y/m/d') : 'غير محدد' }}
                        @if($employee->isDateExpired('national_id_expiry_date'))
                            <i class="ri-error-warning-line mr-1 text-xs"></i>
                        @elseif($employee->getDaysUntilExpiry('national_id_expiry_date') && $employee->getDaysUntilExpiry('national_id_expiry_date') <= 90)
                            <i class="ri-alarm-warning-line mr-1 text-xs"></i>
                        @endif
                    </div>
                </div>
                @endif

                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">الهاتف</label>
                    <div class="text-sm font-semibold text-gray-900 flex items-center gap-1">
                        <i class="ri-phone-line text-blue-600 text-xs"></i>
                        <span class="truncate">{{ $employee->phone ?? 'غير محدد' }}</span>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">البريد</label>
                    <div class="text-sm font-semibold text-gray-900 flex items-center gap-1">
                        <i class="ri-mail-line text-blue-600 text-xs"></i>
                        <span class="truncate">{{ $employee->email ?? 'غير محدد' }}</span>
                    </div>
                </div>

                @if($employee->hire_date)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">مدة الخدمة</label>
                    <div class="text-sm font-semibold text-green-600">
                        {{ round($employee->hire_date->diffInYears()) }} س، {{ round($employee->hire_date->diffInMonths() % 12) }}ش
                    </div>
                </div>
                @endif

                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">الفئة</label>
                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $employee->category ?? 'غير محدد' }}</div>
                </div>

                @if($employee->address)
                <div class="space-y-1 md:col-span-2">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">العنوان</label>
                    <div class="text-sm font-semibold text-gray-900 flex items-center gap-1">
                        <i class="ri-map-pin-line text-blue-600 text-xs"></i>
                        <span class="truncate">{{ $employee->address }}</span>
                    </div>
                </div>
                @endif

                <div class="space-y-1 md:col-span-2">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">الكفالة</label>
                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $employee->sponsorship ?? 'غير محدد' }}</div>
                </div>

                <!-- New Personal Information Fields -->
                @if($employee->birth_date)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">تاريخ الميلاد</label>
                    <div class="text-sm font-semibold text-gray-900">
                        {{ optional($employee->birth_date)->format('Y/m/d') ?? $employee->birth_date }}
                    </div>
                </div>
                @endif

                @if($employee->nationality)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">الجنسية</label>
                    <div class="text-sm font-semibold text-gray-900">{{ $employee->nationality }}</div>
                </div>
                @endif

                @if($employee->religion)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">الديانة</label>
                    <div class="text-sm font-semibold text-gray-900">{{ $employee->religion }}</div>
                </div>
                @endif

                @if($employee->medical_insurance_status)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">التأمين الطبي</label>
                    <div class="text-sm font-semibold text-gray-900">{{ $employee->medical_insurance_status }}</div>
                </div>
                @endif

                @if($employee->location_type)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">نوع الموقع</label>
                    <div class="text-sm font-semibold text-gray-900">{{ $employee->location_type }}</div>
                </div>
                @endif

                @if($employee->rating)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">تقييم الأداء</label>
                    <div class="flex items-center gap-2">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $employee->rating ? '★' : '☆' }}" style="color: {{ $i <= $employee->rating ? '#fbbf24' : '#d1d5db' }}; font-size: 16px; margin-right: 2px;"></span>
                            @endfor
                        </div>
                        <span class="text-sm font-semibold text-yellow-600">
                            @switch($employee->rating)
                                @case(1) ضعيف جداً @break
                                @case(2) ضعيف @break
                                @case(3) متوسط @break
                                @case(4) جيد @break
                                @case(5) ممتاز @break
                                @default غير مقيم
                            @endswitch
                            ({{ $employee->rating }}/5)
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Additional Documents Section -->
        @if($employee->additional_documents)
        <div class="bg-white p-3 avoid-break section-compact">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                    <i class="ri-file-add-line text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">الوثائق الإضافية</h3>
            </div>

            @php
                $additionalDocs = $employee->additional_documents;
                if (is_string($additionalDocs)) {
                    $additionalDocs = json_decode($additionalDocs, true);
                }
            @endphp

            @if(is_array($additionalDocs) && count($additionalDocs) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($additionalDocs as $doc)
                        <div class="bg-purple-50 border border-purple-200 p-3 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="ri-file-line text-purple-600 text-lg"></i>
                                <label class="text-sm font-medium text-purple-700 uppercase tracking-wide">{{ $doc['name'] ?? 'وثيقة' }}</label>
                            </div>
                            <div class="text-sm font-semibold text-purple-800 flex items-center gap-1">
                                <span class="bg-purple-100 px-2 py-1 rounded-full text-xs">مرفق</span>
                                @if(isset($doc['uploaded_at']))
                                    <span class="text-xs text-purple-500">({{ $doc['uploaded_at'] }})</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <i class="ri-file-add-line text-4xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-500">لا توجد وثائق إضافية</p>
                </div>
            @endif
        </div>
        @endif

        <!-- Financial Information Section -->
        <div class="bg-white p-3 avoid-break section-compact">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                    <i class="ri-money-dollar-circle-line text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">المعلومات المالية والوظيفية</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">الراتب الأساسي</label>
                    <div class="text-lg font-bold text-green-600">
                        {{ $employee->salary ? number_format((float)$employee->salary, 0) . ' ريال' : 'غير محدد' }}
                    </div>
                </div>

                @if($employee->location_assignment_date)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">تعيين الموقع</label>
                    <div class="text-sm font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($employee->location_assignment_date)->format('Y/m/d') }}
                    </div>
                </div>
                @endif

                @if($employee->location && $employee->location->city)
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">المدينة</label>
                    <div class="text-sm font-semibold text-gray-900 flex items-center gap-1">
                        <i class="ri-building-line text-blue-600 text-xs"></i>
                        <span class="truncate">{{ $employee->location->city }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Bank Account Information Section -->
        @if($employee->bank_name || $employee->iban)
        <div class="bg-white p-3 avoid-break section-compact">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                    <i class="ri-bank-card-line text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">معلومات الحساب البنكي</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @if($employee->bank_name)
                <div class="bg-purple-50 border border-purple-200 p-2 rounded-lg">
                    <label class="block text-xs font-medium text-purple-600 mb-1">اسم البنك</label>
                    <div class="text-sm font-semibold text-purple-900">{{ $employee->bank_name }}</div>
                </div>
                @endif

                @if($employee->iban)
                <div class="bg-purple-50 border border-purple-200 p-2 rounded-lg">
                    <label class="block text-xs font-medium text-purple-600 mb-1">رقم الآيبان</label>
                    <div class="text-sm font-semibold text-purple-900 font-mono">SA{{ $employee->iban }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Equipment Section -->
        @if($employee->drivenEquipment && $employee->drivenEquipment->count() > 0)
        <div class="bg-white p-3 avoid-break section-compact">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center">
                    <i class="ri-truck-line text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">المعدات والمركبات المُعينة ({{ $employee->drivenEquipment->count() }})</h3>
            </div>

            <div class="space-y-2">
                @foreach($employee->drivenEquipment as $equipment)
                <div class="bg-orange-50 border border-orange-200 p-2 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-tools-line text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-bold text-gray-900 text-xs">{{ $equipment->name }}</h4>
                                <span class="text-xs font-medium px-2 py-1 rounded
                                    @if($equipment->status === 'available') bg-green-100 text-green-700
                                    @elseif($equipment->status === 'in_use') bg-blue-100 text-blue-700
                                    @elseif($equipment->status === 'maintenance') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    @if($equipment->status === 'available') متاحة
                                    @elseif($equipment->status === 'in_use') قيد الاستخدام
                                    @elseif($equipment->status === 'maintenance') في الصيانة
                                    @else خارج الخدمة @endif
                                </span>
                            </div>

                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                @if($equipment->type)
                                <span>{{ $equipment->type }}</span>
                                @endif
                                @if($equipment->serial_number)
                                <span>•</span>
                                <span>{{ $equipment->serial_number }}</span>
                                @endif
                                @if($equipment->locationDetail)
                                <span>•</span>
                                <span>{{ $equipment->locationDetail->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Documents Gallery Section -->
        <div class="bg-white p-3 avoid-break section-compact">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="ri-image-line text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">الوثائق والمستندات المرفقة</h3>
            </div>

            <div class="space-y-2">
                <!-- National ID -->
                <div class="bg-gray-50 border border-gray-300 rounded-lg p-6">
                    <div class="flex items-center gap-8">
                        <div class="w-40 h-40 rounded-lg overflow-hidden bg-white shadow-lg flex-shrink-0">
                            @if($employee->national_id_photo)
                                <img src="{{ asset('storage/' . $employee->national_id_photo) }}"
                                     alt="الهوية الوطنية"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <i class="ri-id-card-line text-6xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-900 text-xl">الهوية الوطنية</h4>
                                <span class="inline-block px-5 py-2 rounded-full text-base font-semibold
                                    {{ $employee->national_id_photo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $employee->national_id_photo ? 'متوفرة' : 'غير متوفرة' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-lg text-gray-600">
                                @if($employee->national_id)
                                    <span class="font-medium">رقم: {{ $employee->national_id }}</span>
                                @endif
                                @if($employee->national_id_expiry_date)
                                    <span>•</span>
                                    <span class="font-medium">تنتهي: {{ $employee->national_id_expiry_date ? $employee->national_id_expiry_date->format('Y/m/d') : 'غير محدد' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Passport -->
                <div class="bg-gray-50 border border-gray-300 rounded-lg p-6">
                    <div class="flex items-center gap-8">
                        <div class="w-40 h-40 rounded-lg overflow-hidden bg-white shadow-lg flex-shrink-0">
                            @if($employee->passport_photo)
                                <img src="{{ asset('storage/' . $employee->passport_photo) }}"
                                     alt="جواز السفر"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <i class="ri-passport-line text-6xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-900 text-xl">جواز السفر</h4>
                                <span class="inline-block px-5 py-2 rounded-full text-base font-semibold
                                    {{ $employee->passport_photo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $employee->passport_photo ? 'متوفر' : 'غير متوفر' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-lg text-gray-600">
                                @if($employee->passport_number)
                                    <span class="font-medium">رقم: {{ $employee->passport_number }}</span>
                                @endif
                                @if($employee->passport_expiry_date)
                                    <span>•</span>
                                    <span class="font-medium">ينتهي: {{ $employee->passport_expiry_date ? $employee->passport_expiry_date->format('Y/m/d') : 'غير محدد' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Permit -->
                <div class="bg-gray-50 border border-gray-300 rounded-lg p-6">
                    <div class="flex items-center gap-8">
                        <div class="w-40 h-40 rounded-lg overflow-hidden bg-white shadow-lg flex-shrink-0">
                            @if($employee->work_permit_photo)
                                <img src="{{ asset('storage/' . $employee->work_permit_photo) }}"
                                     alt="بطاقة التشغيل"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <i class="ri-briefcase-line text-6xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-900 text-xl">بطاقة التشغيل</h4>
                                <span class="inline-block px-5 py-2 rounded-full text-base font-semibold
                                    {{ $employee->work_permit_photo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $employee->work_permit_photo ? 'متوفرة' : 'غير متوفرة' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-lg text-gray-600">
                                @if($employee->work_permit_number)
                                    <span class="font-medium">رقم: {{ $employee->work_permit_number }}</span>
                                @endif
                                @if($employee->work_permit_expiry_date)
                                    <span>•</span>
                                    <span class="font-medium">تنتهي: {{ $employee->work_permit_expiry_date ? $employee->work_permit_expiry_date->format('Y/m/d') : 'غير محدد' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Driving License -->
                @if($employee->driving_license_photo || $employee->driving_license_expiry_date)
                <div class="bg-gray-50 border border-gray-300 rounded-lg p-6">
                    <div class="flex items-center gap-8">
                        <div class="w-40 h-40 rounded-lg overflow-hidden bg-white shadow-lg flex-shrink-0">
                            @if($employee->driving_license_photo)
                                <img src="{{ asset('storage/' . $employee->driving_license_photo) }}"
                                     alt="رخصة القيادة"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <i class="ri-roadster-line text-6xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-900 text-xl">رخصة القيادة</h4>
                                <span class="inline-block px-5 py-2 rounded-full text-base font-semibold
                                    {{ $employee->driving_license_photo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $employee->driving_license_photo ? 'متوفرة' : 'غير متوفرة' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-lg text-gray-600">
                                @if($employee->driving_license_expiry_date)
                                    <span class="font-medium">تنتهي: {{ $employee->driving_license_expiry_date ? $employee->driving_license_expiry_date->format('Y/m/d') : 'غير محدد' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Professional Footer -->
        <div class="bg-white border border-gray-300 p-4 rounded-b-xl avoid-break shadow-sm">
            <!-- Signatures Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Manager Signature -->
                <div class="text-center">
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <i class="ri-admin-line text-blue-600"></i>
                        <span class="font-semibold text-gray-800">توقيع المسؤول</span>
                    </div>
                    <div class="h-12 border-2 border-dashed border-blue-300 rounded-lg mb-2 bg-blue-50"></div>
                    <div class="text-sm text-gray-600">
                        <div>الاسم: ___________________</div>
                        <div>التاريخ: ___________________</div>
                    </div>
                </div>

                <!-- Company Stamp -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto border-4 border-blue-600 rounded-full flex flex-col items-center justify-center bg-blue-50">
                        <div class="text-xs font-semibold mb-1 text-blue-800">ختم الشركة</div>
                        <div class="text-2xl font-bold text-blue-600">أ</div>
                    </div>
                    <div class="text-xs text-gray-600 mt-2">Company Seal</div>
                </div>

                <!-- Employee Signature -->
                <div class="text-center">
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <i class="ri-user-line text-green-600"></i>
                        <span class="font-semibold text-gray-800">توقيع الموظف</span>
                    </div>
                    <div class="h-12 border-2 border-dashed border-green-300 rounded-lg mb-2 bg-green-50"></div>
                    <div class="text-sm text-gray-600">
                        <div>التاريخ: ___________________</div>
                        <div>أقر بصحة البيانات أعلاه</div>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="border-t border-gray-200 pt-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <!-- Company Details -->
                    <div class="text-center md:text-right">
                        <h4 class="text-lg font-bold mb-1 text-blue-800">شركة الأبراج للمقاولات المحدودة</h4>
                        <p class="text-sm text-gray-600">Al-Abraj Contracting Company Limited</p>
                    </div>

                    <!-- Contact Information -->
                    <div class="text-center space-y-1 text-sm">
                        <div class="flex items-center justify-center gap-2">
                            <i class="ri-map-pin-line text-red-600"></i>
                            <span class="text-gray-700">المملكة العربية السعودية</span>
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <i class="ri-phone-line text-green-600"></i>
                            <span class="text-gray-700">+966 XX XXX XXXX</span>
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <i class="ri-mail-line text-blue-600"></i>
                            <span class="text-gray-700">info@abraj-contracting.com</span>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="text-center md:text-left">
                        <div class="w-20 h-20 mx-auto md:mx-0 md:mr-auto rounded-lg flex items-center justify-center bg-white border border-gray-300 relative">
                            <div id="qr-code-container" class="w-full h-full flex items-center justify-center"></div>
                            <div id="qr-fallback" class="flex items-center justify-center w-full h-full absolute inset-0">
                                <i class="ri-qr-code-line text-3xl text-gray-600"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">QR للتحميل</p>
                    </div>
                </div>

                <!-- Disclaimer -->
                <div class="text-center mt-3 pt-2 border-t border-gray-200">
                    <div class="flex items-center justify-center gap-2 text-sm text-gray-600">
                        <i class="ri-shield-check-line text-orange-600"></i>
                        <span>وثيقة رسمية صادرة من نظام إدارة الموظفين | تم إنشاؤها في {{ now()->format('Y/m/d H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto Print Script -->
    <script>
        // Generate QR Code for PDF download
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, attempting to generate QR code...');

            const container = document.getElementById('qr-code-container');
            const fallback = document.getElementById('qr-fallback');

            if (!container) {
                console.error('QR container not found');
                return;
            }

            // Create PDF download URL
            const pdfUrl = '{{ route("employees.download-pdf", $employee->id) }}';
            console.log('PDF URL:', pdfUrl);

            // Clear container
            container.innerHTML = '';

            // Try multiple QR generation methods
            let qrGenerated = false;

            // Method 1: Try with QRCode.js library (davidshimjs)
            setTimeout(function() {
                if (!qrGenerated && typeof QRCode !== 'undefined' && QRCode.prototype) {
                    try {
                        console.log('Attempting QR generation with QRCode.js...');
                        new QRCode(container, {
                            text: pdfUrl,
                            width: 80,
                            height: 80,
                            colorDark: "#1f2937",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.M
                        });

                        // Check if QR was generated
                        setTimeout(function() {
                            if (container.children.length > 0) {
                                console.log('QR Code generated successfully with QRCode.js');
                                qrGenerated = true;
                                if (fallback) fallback.style.display = 'none';
                            }
                        }, 100);
                    } catch (error) {
                        console.error('Error with QRCode.js:', error);
                    }
                }
            }, 100);

            // Method 2: Try with qrcode library (npm)
            setTimeout(function() {
                if (!qrGenerated && typeof QRCode !== 'undefined' && QRCode.toCanvas) {
                    try {
                        console.log('Attempting QR generation with qrcode library...');
                        const canvas = document.createElement('canvas');
                        canvas.width = 80;
                        canvas.height = 80;

                        QRCode.toCanvas(canvas, pdfUrl, {
                            width: 80,
                            height: 80,
                            margin: 1,
                            color: {
                                dark: '#1f2937',
                                light: '#ffffff'
                            },
                            errorCorrectionLevel: 'M'
                        }, function (error) {
                            if (error) {
                                console.error('Error with qrcode library:', error);
                            } else {
                                console.log('QR Code generated successfully with qrcode library');
                                container.appendChild(canvas);
                                qrGenerated = true;
                                if (fallback) fallback.style.display = 'none';
                            }
                        });
                    } catch (error) {
                        console.error('Error with qrcode library:', error);
                    }
                }
            }, 200);

            // Method 3: Online QR Generator as fallback
            setTimeout(function() {
                if (!qrGenerated) {
                    console.log('Using online QR generator as fallback...');
                    const img = document.createElement('img');
                    img.src = `https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=${encodeURIComponent(pdfUrl)}`;
                    img.width = 80;
                    img.height = 80;
                    img.style.display = 'block';
                    img.onload = function() {
                        container.appendChild(img);
                        qrGenerated = true;
                        if (fallback) fallback.style.display = 'none';
                        console.log('QR Code generated successfully with online service');
                    };
                    img.onerror = function() {
                        console.error('Failed to load QR code from online service');
                        showFallback();
                    };
                }
            }, 500);

            // Final fallback
            setTimeout(function() {
                if (!qrGenerated) {
                    console.log('All QR generation methods failed, showing fallback');
                    showFallback();
                }
            }, 2000);

            function showFallback() {
                if (fallback) {
                    fallback.style.display = 'flex';
                    fallback.innerHTML = '<i class="ri-qr-code-line text-3xl text-gray-600"></i>';
                }
            }
        });

        @if(isset($isPdfDownload) && $isPdfDownload)
            // If this is a PDF download request, trigger print immediately
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                    // After printing, close the window if it was opened for download
                    setTimeout(function() {
                        if (window.opener) {
                            window.close();
                        }
                    }, 1000);
                }, 500);
            });
        @else
            // Normal print behavior - trigger after page fully loads
            window.addEventListener('load', function() {
                console.log('Page fully loaded, triggering print...');
                // Ensure all images and resources are loaded
                setTimeout(function() {
                    window.print();
                }, 800);
            });
            
            // Fallback: if page doesn't load after 5 seconds, still print
            setTimeout(function() {
                if (document.readyState !== 'complete') {
                    console.log('Timeout: forcing print after 5 seconds');
                    window.print();
                }
            }, 5000);
        @endif

        // Hide non-printable elements and optimize for print
        if (window.matchMedia) {
            var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (mql.matches) {
                    document.body.style.backgroundColor = 'white';
                }
            });
        }
    </script>
</body>
</html>
