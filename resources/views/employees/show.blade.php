    @extends('layouts.app')

    @section('title', 'تفاصيل الموظف: ' . $employee->name)

    @section('content')
        <div class="p-6" dir="rtl">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="ri-check-circle-line text-green-600 text-xl ml-2"></i>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="ri-error-warning-line text-red-600 text-xl ml-2"></i>
                        <span class="text-red-800 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="ri-error-warning-line text-red-600 text-xl ml-2 mt-0.5"></i>
                        <div>
                            <h4 class="text-red-800 font-medium mb-2">يرجى تصحيح الأخطاء التالية:</h4>
                            <ul class="text-red-700 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header (Hidden in Print) -->
            <div class="flex items-center justify-between mb-6 no-print">
                <div class="flex items-center gap-4">
                    <a href="{{ route('employees.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="ri-arrow-right-line text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تفاصيل الموظف</h1>
                        <p class="text-gray-600 mt-1">عرض بيانات الموظف: {{ $employee->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Only show activation/deactivation buttons to general managers -->
                    @if (auth()->user() && auth()->user()->isGeneralManager())
                        @if ($employee->status !== 'active')
                            <form action="{{ route('employees.activate', $employee) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                    <i class="ri-user-check-line"></i>
                                    تفعيل الموظف
                                </button>
                            </form>
                        @else
                            <form action="{{ route('employees.deactivate', $employee) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
                                    onclick="return confirm('هل أنت متأكد من إلغاء تفعيل هذا الموظف؟')">
                                    <i class="ri-user-unfollow-line"></i>
                                    إلغاء تفعيل الموظف
                                </button>
                            </form>
                        @endif
                    @endif
                    <button onclick="openCreditModal()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-add-circle-line"></i>
                        إضافة رصيد دائن
                    </button>
                    <button onclick="openDebitModal()"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-subtract-line"></i>
                        إضافة رصيد مدين
                    </button>
                    @if ($employee->role !== 'مسئول رئيسي')
                        <button onclick="openManagerModal()"
                            class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-user-settings-line"></i>
                            @if ($employee->directManager)
                                تغيير المدير المباشر
                            @else
                                تعيين مدير مباشر
                            @endif
                        </button>
                    @endif
                    <button type="button" onclick="openPasswordModal()"
                        class="flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        <i class="ri-lock-password-line"></i>
                        تغيير كلمة السر
                    </button>
                    <button onclick="openReportModal()"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-file-lock-line"></i>
                        تقرير سري
                    </button>
                    <a href="{{ route('employees.edit', $employee) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-edit-line"></i>
                        تعديل
                    </a>
                    <a href="{{ route('employees.print', $employee) }}" target="_blank"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-printer-line"></i>
                        طباعة
                    </a>
                </div>
            </div>

            <!-- Print Header -->
            <div class="print-only mb-6 print-header">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-blue-600 mb-2">بطاقة بيانات الموظف</h1>
                    <div class="flex justify-between items-center text-sm text-gray-600 mt-4">
                        <div>
                            <p class="font-semibold">شركة أبراج للمقاولات العامة</p>
                            <p>إدارة الموارد البشرية</p>
                        </div>
                        <div class="text-left" dir="ltr">
                            <p>تاريخ الطباعة: {{ now()->format('Y/m/d') }}</p>
                            <p>الوقت: {{ now()->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Information Card -->
            <div class="bg-white rounded-xl shadow-sm border employee-card print-bg-white page-break-avoid">
                <!-- Employee Header Section -->
                <div class="p-6 border-b border-gray-200 employee-section print-header-section">
                    <div class="flex items-center gap-6">
                        <!-- Employee Photo -->
                        <div class="flex-shrink-0">
                            <div
                                class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                                @if ($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="ri-user-line text-3xl text-gray-400"></i>
                                @endif
                            </div>
                        </div>

                        <!-- Employee Basic Info -->
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $employee->name }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700 min-w-0">المسمى الوظيفي:</span>
                                        <span class="text-gray-600">{{ $employee->position ?? 'غير محدد' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700 min-w-0">القسم:</span>
                                        <span class="text-gray-600">{{ $employee->department ?? 'غير محدد' }}</span>
                                    </div>
                                    @if ($employee->location)
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-700 min-w-0">الموقع:</span>
                                            <span class="text-gray-600">{{ $employee->location->name }} -
                                                {{ $employee->location->city }}</span>
                                        </div>
                                    @endif
                                    @if ($employee->location_assignment_date)
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-700 min-w-0">تاريخ التعيين في الموقع:</span>
                                            <span
                                                class="text-gray-600">{{ $employee->location_assignment_date ? \Carbon\Carbon::parse($employee->location_assignment_date)->format('Y/m/d') : 'غير محدد' }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700 min-w-0">رقم الموظف:</span>
                                        <span
                                            class="text-gray-600 font-mono">{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700 min-w-0">الحالة:</span>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if ($employee->status === 'active') bg-green-100 text-green-800
                                    @elseif($employee->status === 'inactive') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                            @if ($employee->status === 'active')
                                                نشط
                                            @elseif($employee->status === 'inactive')
                                                غير نشط
                                            @else
                                                {{ $employee->status }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700 min-w-0">الصلاحية:</span>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if ($employee->role === 'مسئول رئيسي') bg-purple-100 text-purple-800
                                    @elseif($employee->role === 'مهندس') bg-blue-100 text-blue-800
                                    @elseif($employee->role === 'إداري') bg-indigo-100 text-indigo-800
                                    @elseif($employee->role === 'مشرف موقع') bg-orange-100 text-orange-800
                                    @elseif($employee->role === 'عامل') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                            {{ $employee->role ?? 'غير محدد' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700 min-w-0">الكفالة:</span>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $employee->sponsorship ?? 'غير محدد' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700 min-w-0">الفئة:</span>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if ($employee->category === 'A+') bg-red-100 text-red-800
                                    @elseif($employee->category === 'A') bg-orange-100 text-orange-800
                                    @elseif($employee->category === 'B') bg-yellow-100 text-yellow-800
                                    @elseif($employee->category === 'C') bg-blue-100 text-blue-800
                                    @elseif($employee->category === 'D') bg-indigo-100 text-indigo-800
                                    @elseif($employee->category === 'E') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                            {{ $employee->category ?? 'غير محدد' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Status Quick View -->
                <div class="p-6 border-b border-gray-200 employee-section">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-file-shield-line text-green-600"></i>
                        حالة الوثائق المهمة
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- National ID Status -->
                        @if ($employee->national_id_expiry)
                            @php $nationalIdStatus = $employee->getDocumentStatus('national_id_expiry'); @endphp
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">الهوية الوطنية</span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $nationalIdStatus['class'] }}">
                                        {{ $nationalIdStatus['status'] }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600">
                                    ينتهي:
                                    {{ $employee->national_id_expiry ? $employee->national_id_expiry->format('Y/m/d') : 'غير محدد' }}
                                </p>
                            </div>
                        @endif

                        <!-- Passport Status -->
                        @if ($employee->passport_expiry_date)
                            @php $passportStatus = $employee->getDocumentStatus('passport_expiry_date'); @endphp
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">جواز السفر</span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $passportStatus['class'] }}">
                                        {{ $passportStatus['status'] }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600">
                                    ينتهي:
                                    {{ $employee->passport_expiry_date ? $employee->passport_expiry_date->format('Y/m/d') : 'غير محدد' }}
                                </p>
                            </div>
                        @endif

                        <!-- Work Permit Status -->
                        @if ($employee->work_permit_expiry_date)
                            @php $workPermitStatus = $employee->getDocumentStatus('work_permit_expiry_date'); @endphp
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">بطاقة التشغيل</span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $workPermitStatus['class'] }}">
                                        {{ $workPermitStatus['status'] }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600">
                                    ينتهي:
                                    {{ $employee->work_permit_expiry_date ? $employee->work_permit_expiry_date->format('Y/m/d') : 'غير محدد' }}
                                </p>
                            </div>
                        @endif

                        <!-- Driving License Status -->
                        @if ($employee->driving_license_expiry)
                            @php $drivingLicenseStatus = $employee->getDocumentStatus('driving_license_expiry'); @endphp
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">رخصة القيادة</span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $drivingLicenseStatus['class'] }}">
                                        {{ $drivingLicenseStatus['status'] }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600">
                                    ينتهي:
                                    {{ $employee->driving_license_expiry ? $employee->driving_license_expiry->format('Y/m/d') : 'غير محدد' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="p-6 border-b border-gray-200 employee-section">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-user-line text-blue-600"></i>
                        المعلومات الشخصية
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-xs font-medium text-gray-600 mb-1">رقم الهوية الوطنية</label>
                            <p class="text-sm font-medium text-gray-900">{{ $employee->national_id ?? 'غير محدد' }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-xs font-medium text-gray-600 mb-1">البريد الإلكتروني</label>
                            <p class="text-sm font-medium text-gray-900">{{ $employee->email ?? 'غير محدد' }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-xs font-medium text-gray-600 mb-1">رقم الهاتف</label>
                            <p class="text-sm font-medium text-gray-900">{{ $employee->phone ?? 'غير محدد' }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-xs font-medium text-gray-600 mb-1">المنصب</label>
                            <p class="text-sm font-medium text-gray-900">{{ $employee->position ?? 'غير محدد' }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-xs font-medium text-gray-600 mb-1">القسم</label>
                            <p class="text-sm font-medium text-gray-900">{{ $employee->department ?? 'غير محدد' }}</p>
                        </div>
                        @if ($employee->directManager)
                            <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                                <label class="block text-xs font-medium text-blue-600 mb-1">المدير المباشر</label>
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <div class="w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center">
                                        <i class="ri-user-line text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">{{ $employee->directManager->name }}
                                        </p>
                                        <p class="text-xs text-blue-600">{{ $employee->directManager->position }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-xs font-medium text-gray-600 mb-1">تاريخ التوظيف</label>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $employee->hire_date ? $employee->hire_date->format('Y/m/d') : 'غير محدد' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-xs font-medium text-gray-600 mb-1">الراتب الأساسي</label>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $employee->salary ? number_format((float) $employee->salary, 2) . ' ريال' : 'غير محدد' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-xs font-medium text-gray-600 mb-1">ساعات العمل اليومية</label>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $employee->working_hours ? $employee->working_hours . ' ساعة' : '8 ساعات (افتراضي)' }}
                            </p>
                        </div>
                        @if ($employee->hire_date)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600 mb-1">مدة الخدمة</label>
                                <p class="text-sm font-medium text-gray-900">
                                    @if ($employee->hire_date && $employee->hire_date instanceof \Carbon\Carbon)
                                        {{ floor($employee->hire_date->diffInYears()) }} سنة،
                                        {{ floor($employee->hire_date->diffInMonths() % 12) }} شهر
                                    @else
                                        غير محدد
                                    @endif
                                </p>
                            </div>
                        @endif

                        <!-- New Personal Information Fields -->
                        @if ($employee->birth_date)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600 mb-1">تاريخ الميلاد</label>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $employee->birth_date ? $employee->birth_date->format('Y/m/d') : 'غير محدد' }}
                                </p>
                            </div>
                        @endif

                        @if ($employee->nationality)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600 mb-1">الجنسية</label>
                                <p class="text-sm font-medium text-gray-900">{{ $employee->nationality }}</p>
                            </div>
                        @endif

                        @if ($employee->religion)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600 mb-1">الديانة</label>
                                <p class="text-sm font-medium text-gray-900">{{ $employee->religion }}</p>
                            </div>
                        @endif

                        @if ($employee->medical_insurance_status)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600 mb-1">حالة التأمين الطبي</label>
                                <p class="text-sm font-medium text-gray-900">{{ $employee->medical_insurance_status }}</p>
                            </div>
                        @endif

                        @if ($employee->location_type)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600 mb-1">نوع الموقع</label>
                                <p class="text-sm font-medium text-gray-900">{{ $employee->location_type }}</p>
                            </div>
                        @endif

                        @if ($employee->rating)
                            <div class="bg-yellow-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600 mb-1">تقييم الأداء</label>
                                <div class="flex items-center gap-2">
                                    <div class="flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="ri-star-fill text-lg {{ $i <= $employee->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">
                                        @switch($employee->rating)
                                            @case(1)
                                                ضعيف جداً
                                            @break

                                            @case(2)
                                                ضعيف
                                            @break

                                            @case(3)
                                                متوسط
                                            @break

                                            @case(4)
                                                جيد
                                            @break

                                            @case(5)
                                                ممتاز
                                            @break

                                            @default
                                                غير مقيم
                                        @endswitch
                                        ({{ $employee->rating }}/5)
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($employee->address)
                        <div class="mt-4">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600 mb-1">العنوان</label>
                                <p class="text-sm font-medium text-gray-900">{{ $employee->address }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Additional Location & Assignment Info -->
                    @if ($employee->location || $employee->location_assignment_date)
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-800 mb-2">معلومات الموقع والتعيين</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @if ($employee->location)
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <label class="block text-xs font-medium text-blue-600 mb-1">الموقع الحالي</label>
                                        <p class="text-sm font-medium text-blue-900">
                                            {{ $employee->location->name }} - {{ $employee->location->city }}
                                        </p>
                                    </div>
                                @endif
                                @if ($employee->location_assignment_date)
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <label class="block text-xs font-medium text-blue-600 mb-1">تاريخ التعيين في
                                            الموقع</label>
                                        <p class="text-sm font-medium text-blue-900">
                                            {{ $employee->location_assignment_date ? $employee->location_assignment_date->format('Y/m/d') : 'غير محدد' }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- User Account Information Section -->
                @if ($employee->user)
                    <div class="p-6 border-b border-gray-200 employee-section">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-account-circle-line text-green-600"></i>
                            معلومات حساب المستخدم
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-green-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-green-600 mb-1">البريد الإلكتروني
                                    للدخول</label>
                                <p class="text-sm font-medium text-green-900">{{ $employee->user->email }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-green-600 mb-1">كلمة المرور الافتراضية</label>
                                <p class="text-sm font-medium text-green-900">{{ $employee->national_id }}</p>
                                <p class="text-xs text-green-600 mt-1">نفس رقم الهوية الوطنية</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-green-600 mb-1">نوع الحساب</label>
                                <p class="text-sm font-medium text-green-900">
                                    @php
                                        $roleTranslations = [
                                            'employee' => 'موظف',
                                            'supervisor' => 'مشرف موقع',
                                            'engineer' => 'مهندس',
                                            'admin' => 'إداري',
                                            'manager' => 'مسئول رئيسي',
                                        ];
                                    @endphp
                                    {{ $roleTranslations[$employee->user->role] ?? $employee->user->role }}
                                </p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-green-600 mb-1">تاريخ إنشاء الحساب</label>
                                <p class="text-sm font-medium text-green-900">
                                    {{ $employee->user ? $employee->user->created_at->format('Y/m/d H:i') : 'لا يوجد حساب مستخدم' }}
                                </p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-green-600 mb-1">آخر تسجيل دخول</label>
                                <p class="text-sm font-medium text-green-900">
                                    {{ $employee->user && $employee->user->updated_at ? $employee->user->updated_at->format('Y/m/d H:i') : 'لم يسجل دخول بعد' }}
                                </p>
                            </div>

                            <div class="bg-green-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-green-600 mb-1">صلاحية الموظف</label>
                                <p class="text-sm font-medium text-green-900">{{ $employee->role ?? 'غير محدد' }}</p>
                                <p class="text-xs text-green-600 mt-1">متطابقة مع صلاحية النظام</p>
                            </div>
                        </div>

                        <!-- Account Instructions -->
                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center gap-2">
                                <i class="ri-information-line"></i>
                                تعليمات تسجيل الدخول
                            </h4>
                            <div class="space-y-2 text-sm text-blue-700">
                                <p><strong>رابط تسجيل الدخول:</strong> <a href="{{ route('login') }}"
                                        class="underline text-blue-600">{{ url('/login') }}</a></p>
                                <p><strong>البريد الإلكتروني:</strong> {{ $employee->user->email }}</p>
                                <p><strong>كلمة المرور:</strong> {{ $employee->national_id }} (يُنصح بتغييرها لاحقاً)</p>
                                <p class="text-xs"><em>يرجى إبلاغ الموظف بهذه المعلومات لتمكينه من الدخول إلى النظام</em>
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 border-b border-gray-200 employee-section">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-account-circle-line text-red-600"></i>
                            معلومات حساب المستخدم
                        </h3>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 text-red-800 mb-2">
                                <i class="ri-error-warning-line"></i>
                                <h4 class="font-semibold">لا يوجد حساب مستخدم</h4>
                            </div>
                            <p class="text-sm text-red-700 mb-3">لم يتم إنشاء حساب مستخدم لهذا الموظف بعد.</p>
                            <form action="{{ route('employees.create-user-account', $employee) }}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                    إنشاء حساب مستخدم الآن
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Bank Account Information Section -->
                @if ($employee->bank_name || $employee->iban)
                    <div class="p-6 border-b border-gray-200 employee-section">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-bank-card-line text-purple-600"></i>
                            معلومات الحساب البنكي
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if ($employee->bank_name)
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <label class="block text-xs font-medium text-purple-600 mb-1">اسم البنك</label>
                                    <p class="text-sm font-medium text-purple-900">{{ $employee->bank_name }}</p>
                                </div>
                            @endif

                            @if ($employee->iban)
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <label class="block text-xs font-medium text-purple-600 mb-1">رقم الآيبان</label>
                                    <p class="text-sm font-medium text-purple-900 font-mono">SA{{ $employee->iban }}</p>
                                    <button onclick="copyToClipboard('SA{{ $employee->iban }}')"
                                        class="text-xs text-purple-600 hover:text-purple-800 flex items-center gap-1 mt-1">
                                        <i class="ri-file-copy-line"></i>
                                        نسخ
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Additional Documents Section -->
                @if ($employee->additional_documents)
                    <div class="p-6 border-b border-gray-200 employee-section">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-file-add-line text-purple-600"></i>
                            الوثائق الإضافية
                        </h3>

                        @php
                            $additionalDocs = $employee->additional_documents;
                            if (is_string($additionalDocs)) {
                                $additionalDocs = json_decode($additionalDocs, true);
                            }
                        @endphp

                        @if (is_array($additionalDocs) && count($additionalDocs) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($additionalDocs as $doc)
                                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                                <i class="ri-file-line text-white"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-purple-900">
                                                    {{ $doc['name'] ?? 'وثيقة بدون اسم' }}</h4>
                                                @if (isset($doc['uploaded_at']))
                                                    <p class="text-xs text-purple-600">{{ $doc['uploaded_at'] }}</p>
                                                @endif
                                                @if (isset($doc['file_path']))
                                                    <a href="{{ asset('storage/' . $doc['file_path']) }}" target="_blank"
                                                        class="text-xs text-purple-700 hover:text-purple-900 flex items-center gap-1 mt-1">
                                                        <i class="ri-download-line"></i>
                                                        تحميل الملف
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">لا توجد وثائق إضافية مرفقة</p>
                        @endif
                    </div>
                @endif

                <!-- Documents Section -->
                <div class="p-6 employee-section">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-file-text-line text-blue-600"></i>
                        الوثائق والمستندات
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 employee-documents">
                        <!-- National ID Document -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 document-card">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="ri-id-card-line text-blue-600"></i>
                                <span class="text-sm">الهوية الوطنية</span>
                            </h4>
                            @if ($employee->national_id_photo)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $employee->national_id_photo) }}"
                                        alt="صورة الهوية الوطنية"
                                        class="w-full h-32 object-contain rounded border bg-white">
                                </div>
                                <p class="text-xs text-gray-600 text-center">صورة الهوية الوطنية</p>
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded border flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="ri-id-card-line text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-xs text-gray-500">لم يتم رفع صورة الهوية</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Passport Document -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 document-card">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="ri-passport-line text-green-600"></i>
                                <span class="text-sm">جواز السفر</span>
                            </h4>
                            @if ($employee->passport_photo)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $employee->passport_photo) }}" alt="صورة جواز السفر"
                                        class="w-full h-32 object-contain rounded border bg-white">
                                </div>
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded border flex items-center justify-center mb-3">
                                    <div class="text-center">
                                        <i class="ri-passport-line text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-xs text-gray-500">لم يتم رفع صورة الجواز</p>
                                    </div>
                                </div>
                            @endif
                            @if ($employee->passport_number)
                                <div class="space-y-1 text-xs">
                                    <p><span class="font-medium">رقم الجواز:</span> {{ $employee->passport_number }}</p>
                                    @if ($employee->passport_issue_date)
                                        <p><span class="font-medium">الإصدار:</span>
                                            {{ $employee->passport_issue_date ? $employee->passport_issue_date->format('Y/m/d') : 'غير محدد' }}
                                        </p>
                                    @endif
                                    @if ($employee->passport_expiry_date)
                                        @php $daysUntilExpiry = now()->diffInDays($employee->passport_expiry_date, false); @endphp
                                        <p
                                            class="@if ($employee->passport_expiry_date->isPast()) text-red-600 @elseif($daysUntilExpiry >= 0 && $daysUntilExpiry <= 30) text-orange-600 @endif">
                                            <span class="font-medium">الانتهاء:</span>
                                            {{ $employee->passport_expiry_date ? $employee->passport_expiry_date->format('Y/m/d') : 'غير محدد' }}
                                            @if ($employee->passport_expiry_date->isPast())
                                                <span class="font-semibold">(منتهي)</span>
                                            @elseif($daysUntilExpiry >= 0 && $daysUntilExpiry <= 30)
                                                <span class="font-semibold">(ينتهي قريباً)</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Work Permit Document -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 document-card">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="ri-briefcase-line text-purple-600"></i>
                                <span class="text-sm">بطاقة التشغيل</span>
                            </h4>
                            @if ($employee->work_permit_photo)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $employee->work_permit_photo) }}"
                                        alt="صورة بطاقة التشغيل"
                                        class="w-full h-32 object-contain rounded border bg-white">
                                </div>
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded border flex items-center justify-center mb-3">
                                    <div class="text-center">
                                        <i class="ri-briefcase-line text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-xs text-gray-500">لم يتم رفع صورة البطاقة</p>
                                    </div>
                                </div>
                            @endif
                            @if ($employee->work_permit_number)
                                <div class="space-y-1 text-xs">
                                    <p><span class="font-medium">رقم التصريح:</span> {{ $employee->work_permit_number }}
                                    </p>
                                    @if ($employee->work_permit_issue_date)
                                        <p><span class="font-medium">الإصدار:</span>
                                            {{ $employee->work_permit_issue_date ? $employee->work_permit_issue_date->format('Y/m/d') : 'غير محدد' }}
                                        </p>
                                    @endif
                                    @if ($employee->work_permit_expiry_date)
                                        @php $daysUntilExpiry = now()->diffInDays($employee->work_permit_expiry_date, false); @endphp
                                        <p
                                            class="@if ($employee->work_permit_expiry_date->isPast()) text-red-600 @elseif($daysUntilExpiry >= 0 && $daysUntilExpiry <= 30) text-orange-600 @endif">
                                            <span class="font-medium">الانتهاء:</span>
                                            {{ $employee->work_permit_expiry_date ? $employee->work_permit_expiry_date->format('Y/m/d') : 'غير محدد' }}
                                            @if ($employee->work_permit_expiry_date->isPast())
                                                <span class="font-semibold">(منتهي)</span>
                                            @elseif($daysUntilExpiry >= 0 && $daysUntilExpiry <= 30)
                                                <span class="font-semibold">(ينتهي قريباً)</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Driving License Card -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 document-card">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="ri-car-line text-green-600"></i>
                                <span class="text-sm">رخصة القيادة</span>
                            </h4>
                            @if ($employee->driving_license_photo)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $employee->driving_license_photo) }}"
                                        alt="صورة رخصة القيادة"
                                        class="w-full h-32 object-contain rounded border bg-white">
                                </div>
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded border flex items-center justify-center mb-3">
                                    <div class="text-center">
                                        <i class="ri-car-line text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-xs text-gray-500">لم يتم رفع صورة الرخصة</p>
                                    </div>
                                </div>
                            @endif
                            @if ($employee->driving_license_number)
                            @endif
                            @if ($employee->driving_license_issue_date || $employee->driving_license_expiry_date)
                                <div class="space-y-1 text-xs">
                                    @if ($employee->driving_license_issue_date)
                                        <p><span class="font-medium">الإصدار:</span>
                                            {{ $employee->driving_license_issue_date ? $employee->driving_license_issue_date->format('Y/m/d') : 'غير محدد' }}
                                        </p>
                                    @endif
                                    @if ($employee->driving_license_expiry_date)
                                        @php $daysUntilExpiry = now()->diffInDays($employee->driving_license_expiry_date, false); @endphp
                                        <p
                                            class="@if ($employee->driving_license_expiry_date->isPast()) text-red-600 @elseif($daysUntilExpiry >= 0 && $daysUntilExpiry <= 30) text-orange-600 @endif">
                                            <span class="font-medium">الانتهاء:</span>
                                            {{ $employee->driving_license_expiry_date ? $employee->driving_license_expiry_date->format('Y/m/d') : 'غير محدد' }}
                                            @if ($employee->driving_license_expiry_date->isPast())
                                                <span class="font-semibold">(منتهية)</span>
                                            @elseif($daysUntilExpiry >= 0 && $daysUntilExpiry <= 30)
                                                <span class="font-semibold">(تنتهي قريباً)</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Equipment Section - Only show if employee has equipment -->
                @if ($employee->drivenEquipment && $employee->drivenEquipment->count() > 0)
                    <div class="p-6 border-b border-gray-200 employee-section">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-tools-line text-orange-600"></i>
                            المعدات المُعينة
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($employee->drivenEquipment as $equipment)
                                <div
                                    class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4 equipment-card">
                                    <div class="flex items-center gap-4">
                                        <!-- Equipment Image -->
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-16 h-16 rounded-lg overflow-hidden bg-white border-2 border-orange-200 flex items-center justify-center">
                                                @if ($equipment->images && count($equipment->images) > 0)
                                                    <img src="{{ asset('storage/' . $equipment->images[0]) }}"
                                                        alt="{{ $equipment->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="ri-tools-fill text-2xl text-orange-600"></i>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Equipment Details -->
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $equipment->name }}
                                            </h4>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-gray-700">النوع:</span>
                                                    <span
                                                        class="text-gray-600">{{ $equipment->type ?? 'غير محدد' }}</span>
                                                </div>

                                                @if ($equipment->serial_number)
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-medium text-gray-700">الرقم التسلسلي:</span>
                                                        <span
                                                            class="text-gray-600 font-mono text-xs">{{ $equipment->serial_number }}</span>
                                                    </div>
                                                @endif

                                                @if ($equipment->locationDetail)
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-medium text-gray-700">الموقع:</span>
                                                        <span
                                                            class="text-gray-600">{{ $equipment->locationDetail->name }}</span>
                                                    </div>
                                                @endif

                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-gray-700">الحالة:</span>
                                                    @php
                                                        $statusConfig = [
                                                            'available' => [
                                                                'text' => 'متاحة',
                                                                'class' => 'bg-green-100 text-green-800',
                                                            ],
                                                            'in_use' => [
                                                                'text' => 'قيد الاستخدام',
                                                                'class' => 'bg-blue-100 text-blue-800',
                                                            ],
                                                            'maintenance' => [
                                                                'text' => 'في الصيانة',
                                                                'class' => 'bg-yellow-100 text-yellow-800',
                                                            ],
                                                            'out_of_order' => [
                                                                'text' => 'خارج الخدمة',
                                                                'class' => 'bg-red-100 text-red-800',
                                                            ],
                                                        ];
                                                        $status = $statusConfig[$equipment->status] ?? [
                                                            'text' => $equipment->status,
                                                            'class' => 'bg-gray-100 text-gray-800',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-medium {{ $status['class'] }}">
                                                        {{ $status['text'] }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Equipment Actions -->
                                            <div class="mt-3 flex items-center gap-2">
                                                <a href="{{ route('equipment.show', $equipment) }}"
                                                    class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors flex items-center gap-1">
                                                    <i class="ri-eye-line"></i>
                                                    عرض التفاصيل
                                                </a>

                                                @if ($equipment->status === 'available' || $equipment->status === 'in_use')
                                                    <a href="{{ route('equipment.edit', $equipment) }}"
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors flex items-center gap-1">
                                                        <i class="ri-edit-line"></i>
                                                        تعديل
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Equipment Info -->
                                    @if ($equipment->purchase_date || $equipment->model)
                                        <div class="mt-4 pt-3 border-t border-orange-200">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-600">
                                                @if ($equipment->model)
                                                    <div>
                                                        <span class="font-medium">الموديل:</span> {{ $equipment->model }}
                                                    </div>
                                                @endif

                                                @if ($equipment->purchase_date)
                                                    <div>
                                                        <span class="font-medium">تاريخ الشراء:</span>
                                                        {{ $equipment->purchase_date ? \Carbon\Carbon::parse($equipment->purchase_date)->format('Y/m/d') : 'غير محدد' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Equipment Summary -->
                        <div class="mt-6 bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="ri-bar-chart-line text-blue-600"></i>
                                ملخص المعدات
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ $employee->drivenEquipment->count() }}</div>
                                    <div class="text-xs text-gray-600 mt-1">إجمالي المعدات</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ $employee->drivenEquipment->where('status', 'available')->count() }}</div>
                                    <div class="text-xs text-gray-600 mt-1">متاحة</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $employee->drivenEquipment->where('status', 'in_use')->count() }}</div>
                                    <div class="text-xs text-gray-600 mt-1">قيد الاستخدام</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="text-2xl font-bold text-yellow-600">
                                        {{ $employee->drivenEquipment->where('status', 'maintenance')->count() }}</div>
                                    <div class="text-xs text-gray-600 mt-1">في الصيانة</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Print Footer -->
                <div class="print-only print-footer">
                    <div class="grid grid-cols-3 gap-4 text-xs">
                        <div class="text-right">
                            <p class="font-semibold">توقيع المسؤول:</p>
                            <div class="border-b border-gray-400 mt-4 mb-2" style="height: 30px;"></div>
                            <p>الاسم: ________________</p>
                        </div>
                        <div class="text-center">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%23f3f4f6'/%3E%3Ctext x='50' y='55' text-anchor='middle' font-size='12' fill='%23666'%3Eالختم%3C/text%3E%3C/svg%3E"
                                alt="ختم الشركة" class="mx-auto mb-2">
                            <p class="font-semibold">ختم الشركة</p>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold">توقيع الموظف:</p>
                            <div class="border-b border-gray-400 mt-4 mb-2" style="height: 30px;"></div>
                            <p>التاريخ: ________________</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-300 text-center">
                        <p class="text-xs text-gray-500">
                            هذه الوثيقة صادرة من نظام إدارة الموظفين - شركة أبراج للمقاولات العامة
                            <br>
                            العنوان: المملكة العربية السعودية | الهاتف: +966 XX XXX XXXX | البريد الإلكتروني:
                            info@abraj-contracting.com
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم الأرصدة المالية -->
        <div class="grid grid-cols-1 gap-6 no-print mt-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-blue-50 border-b border-blue-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-blue-800 flex items-center gap-2">
                            <i class="ri-money-dollar-circle-line"></i>
                            الأرصدة المالية
                        </h3>
                        <div class="flex items-center gap-3">
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                {{ $balances->count() }} معاملة
                            </span>
                            @php
                                $netBalanceClass =
                                    $netBalance > 0
                                        ? 'bg-green-100 text-green-800'
                                        : ($netBalance < 0
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-gray-100 text-gray-800');
                            @endphp
                            <span class="text-sm font-medium px-3 py-1 rounded-full {{ $netBalanceClass }}">
                                صافي الرصيد: {{ number_format($netBalance, 2) }} ريال
                            </span>
                        </div>
                    </div>
                </div>

                @if ($balances->count() > 0)
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            التاريخ</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            النوع</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            المبلغ</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الملاحظات</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            المسجل بواسطة</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            تاريخ التسجيل</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($balances as $balance)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $balance->transaction_date->format('Y/m/d') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($balance->type === 'credit')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="ri-add-circle-line ml-1"></i>
                                                        دائن (للموظف)
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="ri-subtract-line ml-1"></i>
                                                        مدين (على الموظف)
                                                    </span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $balance->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $balance->type === 'credit' ? '+' : '-' }}{{ number_format($balance->amount, 2) }}
                                                ريال
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                                {{ $balance->notes ?: 'لا توجد ملاحظات' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $balance->creator->name ?? 'غير محدد' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $balance->created_at->format('Y/m/d H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Net Balance Summary -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    <div class="flex items-center gap-4">
                                        <span>إجمالي الدائن: <span
                                                class="font-medium text-green-600">{{ number_format($balances->where('type', 'credit')->sum('amount'), 2) }}
                                                ريال</span></span>
                                        <span>إجمالي المدين: <span
                                                class="font-medium text-red-600">{{ number_format($balances->where('type', 'debit')->sum('amount'), 2) }}
                                                ريال</span></span>
                                    </div>
                                </div>
                                <div
                                    class="text-lg font-bold {{ $netBalance > 0 ? 'text-green-600' : ($netBalance < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                    صافي الرصيد:
                                    @if ($netBalance > 0)
                                        +{{ number_format($netBalance, 2) }} ريال (للموظف)
                                    @elseif($netBalance < 0)
                                        {{ number_format($netBalance, 2) }} ريال (على الموظف)
                                    @else
                                        0.00 ريال (متوازن)
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <i class="ri-money-dollar-circle-line text-4xl mb-2 text-gray-400"></i>
                        <p>لا توجد معاملات مالية مسجلة لهذا الموظف</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- قسم التقارير السرية -->
        <div class="grid grid-cols-1 gap-6 no-print mt-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-red-50 border-b border-red-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-red-800 flex items-center gap-2">
                            <i class="ri-file-lock-line"></i>
                            التقارير السرية
                        </h3>
                        <div class="flex items-center gap-3">
                            <span id="reports-count"
                                class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
                                0 تقرير
                            </span>
                            <button onclick="printSecretReports()"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg transition-colors flex items-center gap-1 text-sm">
                                <i class="ri-printer-line"></i>
                                طباعة التقارير
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div id="reports-container">
                        <div class="text-center py-8 text-gray-500">
                            <i class="ri-file-text-line text-4xl mb-2"></i>
                            <p>لا توجد تقارير حتى الآن</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal تعيين/تغيير المدير المباشر -->
        <div id="managerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        @if ($employee->directManager)
                            تغيير المدير المباشر
                        @else
                            تعيين مدير مباشر
                        @endif
                    </h3>
                    <button onclick="closeManagerModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('employees.assign-manager', $employee) }}" method="POST">
                    @csrf

                    @if ($employee->directManager)
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">المدير الحالي:</p>
                            <p class="font-medium text-gray-900">{{ $employee->directManager->name }}</p>
                            <p class="text-sm text-gray-500">{{ $employee->directManager->position }}</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="direct_manager_id" class="block text-sm font-medium text-gray-700 mb-2">
                            اختر المدير المباشر الجديد
                        </label>

                        @if ($employee->role === 'عامل')
                            <div class="mb-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm text-blue-700">
                                    <i class="ri-information-line ml-1"></i>
                                    العمال يمكن تعيين مشرفي المواقع فقط كمدراء مباشرين
                                </p>
                            </div>
                            <div class="mb-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <p class="text-sm text-yellow-700">
                                    <i class="ri-map-pin-line ml-1"></i>
                                    <strong>تنبيه:</strong> عند تعيين مدير مباشر، سيتم تحديث موقع العمل للعامل تلقائياً
                                    ليصبح نفس موقع عمل المدير المباشر
                                </p>
                            </div>
                        @else
                            <div class="mb-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                <p class="text-sm text-purple-700">
                                    <i class="ri-information-line ml-1"></i>
                                    هذا الموظف يمكن تعيين المسؤولين الرئيسيين فقط كمدراء مباشرين
                                </p>
                            </div>
                        @endif
                        <select name="direct_manager_id" id="direct_manager_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            required>
                            <option value="">اختر المدير</option>
                            @if ($employee->role === 'عامل')
                                {{-- للعمال: عرض مشرفي المواقع فقط --}}
                                @foreach (\App\Models\Employee::where('role', 'مشرف موقع')->where('id', '!=', $employee->id)->get() as $manager)
<option value="{{ $manager->id }}"
                                    {{ old('direct_manager_id', $employee->direct_manager_id) == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }} - {{ $manager->position }}
                            </option>
@endforeach
@else
{{-- لغير العمال: عرض المسؤولين الرئيسيين فقط --}}
                        @foreach (\App\Models\Employee::where('role', 'مسئول رئيسي')->where('id', '!=', $employee->id)->get() as $manager)
                                    <option value="{{ $manager->id }}"
                                        {{ old('direct_manager_id', $employee->direct_manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }} - {{ $manager->position }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="flex items-center space-x-3 space-x-reverse">
                        <button type="submit"
                            class="flex-1 bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                            <i class="ri-save-line ml-1"></i>
                            @if ($employee->directManager)
                                تغيير المدير
                            @else
                                تعيين المدير
                            @endif
                        </button>

                        @if ($employee->directManager)
                            <button type="button" onclick="removeManager()"
                                class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                                <i class="ri-delete-bin-line"></i>
                                إزالة
                            </button>
                        @endif

                        <button type="button" onclick="closeManagerModal()"
                            class="bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition-colors">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal التقرير السري -->
        <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="ri-file-lock-line text-red-600"></i>
                            إضافة تقرير سري
                        </h3>
                        <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>

                    <form id="reportForm">
                        @csrf
                        <div class="mb-4">
                            <label for="report_content" class="block text-sm font-medium text-gray-700 mb-2">
                                محتوى التقرير
                            </label>
                            <textarea id="report_content" name="report_content" rows="5"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                                placeholder="اكتب التقرير السري هنا..." required></textarea>
                            <div class="text-sm text-gray-500 mt-1">
                                الحد الأقصى: 2000 حرف
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeReportModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                إلغاء
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                حفظ التقرير
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- قسم تعيينات المدير -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="ri-user-settings-line text-indigo-600"></i>
                سجل تعيينات المدير
            </h3>

            @if ($employee->managerAssignments && $employee->managerAssignments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="px-4 py-3 text-right font-medium text-gray-700">التاريخ</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-700">نوع العملية</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-700">المدير</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-700">من قام بالتعيين</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-700">ملاحظات</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-700">مدة الخدمة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->managerAssignments as $assignment)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="text-gray-900 font-medium">
                                            {{ $assignment->assigned_at ? $assignment->assigned_at->format('Y-m-d') : '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $assignment->assigned_at ? $assignment->assigned_at->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $assignment->assignment_type === 'تعيين' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $assignment->assignment_type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($assignment->manager)
                                            <div class="flex items-center gap-2">
                                                @if ($assignment->manager->photo)
                                                    <img src="{{ asset('storage/' . $assignment->manager->photo) }}"
                                                        alt="{{ $assignment->manager->name }}"
                                                        class="w-8 h-8 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <i class="ri-user-line text-gray-600"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-gray-900 font-medium">
                                                        {{ $assignment->manager->name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $assignment->manager->position }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($assignment->assignedBy)
                                            <div class="text-gray-900 font-medium">{{ $assignment->assignedBy->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $assignment->assignedBy->email }}</div>
                                        @else
                                            <span class="text-gray-500">النظام</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-gray-700">{{ $assignment->notes ?: '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($assignment->assigned_at && $employee->hire_date)
                                            @php
                                                $serviceDays = floor(
                                                    $assignment->assigned_at->diffInDays($employee->hire_date),
                                                );
                                                $years = floor($serviceDays / 365);
                                                $months = floor(($serviceDays % 365) / 30);
                                                $days = $serviceDays % 30;
                                            @endphp
                                            <div class="text-sm">
                                                @if ($years > 0)
                                                    <span class="font-medium">{{ $years }}</span> سنة
                                                @endif
                                                @if ($months > 0)
                                                    <span class="font-medium">{{ $months }}</span> شهر
                                                @endif
                                                @if ($days > 0)
                                                    <span class="font-medium">{{ $days }}</span> يوم
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                ({{ number_format($serviceDays) }} يوم)
                                            </div>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="ri-file-list-line text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">لا توجد سجلات تعيين مدير</p>
                </div>
            @endif
        </div>

        <!-- Basic Print Styles -->
        <style>
            .print-only {
                display: none;
            }

            .no-print {
                display: block;
            }

            @media print {
                .no-print {
                    display: none !important;
                }

                .print-only {
                    display: block !important;
                }

                /* تأكيد ظهور المحتوى */
                .employee-card,
                .employee-section,
                .employee-documents,
                .document-card,
                .equipment-card {
                    display: block !important;
                    visibility: visible !important;
                }

                .employee-documents {
                    display: grid !important;
                }

                /* تحسين عرض المعدات في الطباعة */
                .equipment-card {
                    page-break-inside: avoid;
                    margin-bottom: 10px;
                    border: 1px solid #ddd !important;
                    background: white !important;
                }

                .equipment-card .bg-gradient-to-r {
                    background: #f8f9fa !important;
                }
            }
        </style>

        <script>
            // تحسين الطباعة
            function enhancePrint() {
                // إضافة class للبودي عند الطباعة
                document.body.classList.add('print-mode');

                // تأكيد ظهور جميع العناصر المطلوبة
                const employeeCard = document.querySelector('.employee-card');
                const sections = document.querySelectorAll('.employee-section');
                const documents = document.querySelector('.employee-documents');
                const equipmentCards = document.querySelectorAll('.equipment-card');

                if (employeeCard) {
                    employeeCard.style.display = 'block';
                    employeeCard.style.visibility = 'visible';
                }

                sections.forEach(section => {
                    section.style.display = 'block';
                    section.style.visibility = 'visible';
                });

                if (documents) {
                    documents.style.display = 'grid';
                    documents.style.visibility = 'visible';
                }

                // تأكيد ظهور بطاقات المعدات
                equipmentCards.forEach(card => {
                    card.style.display = 'block';
                    card.style.visibility = 'visible';
                });
            }

            // استدعاء دالة التحسين عند الطباعة
            window.addEventListener('beforeprint', enhancePrint);

            // استدعاء الدالة مباشرة في حالة الطباعة عبر الكيبورد
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    enhancePrint();
                }
            });

            // دالة الطباعة المحسنة
            function printEmployee() {
                enhancePrint();
                setTimeout(() => {
                    window.print();
                }, 100);
            }

            // دالة نسخ النص إلى الحافظة
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    // إظهار رسالة تأكيد
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    toast.textContent = 'تم نسخ الآيبان بنجاح';
                    document.body.appendChild(toast);

                    // إزالة الرسالة بعد 2 ثانية
                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 2000);
                }).catch(function(err) {
                    console.error('فشل في نسخ النص: ', err);
                });
            }

            // وظائف التقارير السرية
            function openReportModal() {
                document.getElementById('reportModal').classList.remove('hidden');
            }

            function closeReportModal() {
                document.getElementById('reportModal').classList.add('hidden');
                document.getElementById('reportForm').reset();
            }

            // إرسال التقرير
            document.getElementById('reportForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                try {
                    const response = await fetch('{{ route('employees.reports.store', $employee) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        closeReportModal();
                        loadReports();
                        showToast('تم حفظ التقرير بنجاح', 'success');
                    } else {
                        showToast('حدث خطأ في حفظ التقرير', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('حدث خطأ في حفظ التقرير', 'error');
                }
            });

            // تحميل التقارير
            async function loadReports() {
                try {
                    const response = await fetch('{{ route('employees.reports.index', $employee) }}');
                    const reports = await response.json();

                    const container = document.getElementById('reports-container');
                    const countElement = document.getElementById('reports-count');

                    countElement.textContent = `${reports.length} تقرير`;

                    if (reports.length === 0) {
                        container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="ri-file-text-line text-4xl mb-2"></i>
                    <p>لا توجد تقارير حتى الآن</p>
                </div>
            `;
                    } else {
                        container.innerHTML = reports.map(report => `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2 text-red-800">
                            <i class="ri-file-lock-line"></i>
                            <span class="font-medium">تقرير سري</span>
                        </div>
                        <div class="text-xs text-red-600">
                            ${new Date(report.created_at).toLocaleDateString('ar-SA')} - ${new Date(report.created_at).toLocaleTimeString('ar-SA')}
                        </div>
                    </div>
                    <div class="text-gray-800 text-sm leading-relaxed mb-3">
                        ${report.report_content}
                    </div>
                    <div class="text-xs text-gray-600 border-t border-red-200 pt-2">
                        كتب بواسطة: ${report.reporter.name}
                    </div>
                </div>
            `).join('');
                    }
                } catch (error) {
                    console.error('Error loading reports:', error);
                }
            }

            // وظائف إدارة المدير المباشر
            function openManagerModal() {
                document.getElementById('managerModal').classList.remove('hidden');
            }

            function closeManagerModal() {
                document.getElementById('managerModal').classList.add('hidden');
            }

            function removeManager() {
                if (confirm('هل أنت متأكد من إزالة المدير المباشر؟')) {
                    // إنشاء form لإرسال طلب حذف
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('employees.remove-manager', $employee) }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    document.body.appendChild(form);
                    form.submit();
                }
            }

            // دالة إظهار الرسائل
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
                toast.textContent = message;
                document.body.appendChild(toast);

                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 3000);
            }

            // تحميل التقارير عند تحميل الصفحة
            document.addEventListener('DOMContentLoaded', function() {
                loadReports();
            });

            // دالة طباعة التقارير السرية
            function printSecretReports() {
                window.open('{{ route('employees.reports.print', $employee) }}', '_blank');
            }
        </script>

        <!-- Password Change Modal -->
        <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">تغيير كلمة السر</h3>
                        <button type="button" onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>

                    <form id="passwordForm" action="{{ route('employees.change-password', $employee) }}"
                        method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-4">
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    كلمة السر الجديدة <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="new_password" name="new_password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required minlength="8">
                            </div>

                            <div>
                                <label for="new_password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    تأكيد كلمة السر <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required minlength="8">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-6">
                            <button type="button" onclick="closePasswordModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                إلغاء
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                <i class="ri-save-line ml-2"></i>
                                تغيير كلمة السر
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openPasswordModal() {
                document.getElementById('passwordModal').classList.remove('hidden');
                document.getElementById('passwordModal').classList.add('flex');
                document.getElementById('new_password').focus();
            }

            function closePasswordModal() {
                document.getElementById('passwordModal').classList.add('hidden');
                document.getElementById('passwordModal').classList.remove('flex');
                document.getElementById('passwordForm').reset();
            }

            // Close modal when clicking outside
            document.getElementById('passwordModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePasswordModal();
                }
            });

            // Handle password form submission
            document.getElementById('passwordForm').addEventListener('submit', function(e) {
                const password = document.getElementById('new_password').value;
                const confirmation = document.getElementById('new_password_confirmation').value;

                if (password !== confirmation) {
                    e.preventDefault();
                    alert('كلمة السر وتأكيدها غير متطابقتين');
                    return;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    alert('كلمة السر يجب أن تكون 8 أحرف على الأقل');
                    return;
                }

                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ri-loader-line animate-spin ml-2"></i> جاري التغيير...';
            });

            // Credit Modal Functions
            function openCreditModal() {
                document.getElementById('creditModal').classList.remove('hidden');
            }

            function closeCreditModal() {
                document.getElementById('creditModal').classList.add('hidden');
                document.getElementById('creditForm').reset();
            }

            // Debit Modal Functions
            function openDebitModal() {
                document.getElementById('debitModal').classList.remove('hidden');
            }

            function closeDebitModal() {
                document.getElementById('debitModal').classList.add('hidden');
                document.getElementById('debitForm').reset();
            }

            // Form submission handlers
            document.getElementById('creditForm').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ri-loader-line animate-spin ml-2"></i> جاري الإضافة...';
            });

            document.getElementById('debitForm').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ri-loader-line animate-spin ml-2"></i> جاري الإضافة...';
            });
        </script>

        <!-- Credit Modal -->
        <div id="creditModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="ri-add-circle-line text-green-600"></i>
                                إضافة رصيد دائن
                            </h3>
                            <button onclick="closeCreditModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>

                        <form id="creditForm" method="POST"
                            action="{{ route('employees.balance.credit', $employee) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">المبلغ (ريال)</label>
                                    <input type="number" name="amount" step="0.01" min="0.01" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="أدخل المبلغ">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ المعاملة</label>
                                    <input type="date" name="transaction_date" value="{{ now()->format('Y-m-d') }}"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الملاحظات</label>
                                    <textarea name="notes" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="أدخل الملاحظات (اختياري)"></textarea>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeCreditModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                                    إلغاء
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700">
                                    إضافة الرصيد الدائن
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debit Modal -->
        <div id="debitModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="ri-subtract-line text-red-600"></i>
                                إضافة رصيد مدين
                            </h3>
                            <button onclick="closeDebitModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>

                        <form id="debitForm" method="POST" action="{{ route('employees.balance.debit', $employee) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">المبلغ (ريال)</label>
                                    <input type="number" name="amount" step="0.01" min="0.01" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        placeholder="أدخل المبلغ">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ المعاملة</label>
                                    <input type="date" name="transaction_date" value="{{ now()->format('Y-m-d') }}"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الملاحظات</label>
                                    <textarea name="notes" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        placeholder="أدخل الملاحظات (اختياري)"></textarea>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeDebitModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                                    إلغاء
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
                                    إضافة الرصيد المدين
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @endsection
