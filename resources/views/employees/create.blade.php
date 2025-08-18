@extends('layouts.app')

@section('title', 'إضافة موظف جديد - شركة الأبراج للمقاولات')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">إضافة موظف جديد</h1>
                    <p class="text-gray-600">أدخل بيانات الموظف الجديد في النموذج أدناه</p>
                </div>
                <a href="{{ route('employees.index') }}"
                    class="bg-gray-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-600 transition-all duration-200 flex items-center">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>

        <!-- رسائل التحذير والنجاح -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="ri-error-warning-line text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">يوجد أخطاء في البيانات المدخلة</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-3">
                            <div class="text-xs text-red-600">
                                <strong>تأكد من:</strong>
                                <ul class="list-disc pl-5 mt-1 space-y-1">
                                    <li>ملء جميع الحقول المطلوبة (المميزة بعلامة *)</li>
                                    <li>صحة تنسيق البريد الإلكتروني</li>
                                    <li>عدم تكرار رقم الهوية أو البريد الإلكتروني</li>
                                    <li>أن تكون أحجام الصور أقل من 2MB</li>
                                    <li>صحة تواريخ انتهاء الوثائق</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="ri-check-line text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">تم بنجاح!</h3>
                        <p class="mt-1 text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="ri-error-warning-line text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">خطأ!</h3>
                        <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- تحذيرات هامة -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="ri-information-line text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">معلومات مهمة</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>الحقول المميزة بعلامة (*) مطلوبة ولا يمكن تركها فارغة</li>
                            <li>رقم الهوية الوطنية يجب أن يكون فريداً ولم يتم استخدامه من قبل</li>
                            <li>البريد الإلكتروني سيتم استخدامه لتسجيل الدخول إلى النظام</li>
                            <li>كلمة المرور الافتراضية ستكون نفس رقم الهوية الوطنية</li>
                            <li>حجم الصور يجب أن يكون أقل من 2MB (باستثناء الصورة الشخصية: 5MB)</li>
                            <li>تأكد من صحة تواريخ انتهاء الوثائق لتجنب التحذيرات المستقبلية</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">المعلومات الشخصية</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل
                                *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="national_id" class="block text-sm font-medium text-gray-700 mb-2">رقم الهوية
                                *</label>
                            <input type="text" id="national_id" name="national_id" value="{{ old('national_id') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('national_id') border-red-500 @enderror"
                                required>
                            @error('national_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="national_id_expiry" class="block text-sm font-medium text-gray-700 mb-2">تاريخ
                                انتهاء الهوية</label>
                            <input type="date" id="national_id_expiry" name="national_id_expiry"
                                value="{{ old('national_id_expiry') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('national_id_expiry') border-red-500 @enderror">
                            @error('national_id_expiry')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني
                                *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                required>
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ
                                الميلاد</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">الجنسية *</label>
                            <select id="nationality" name="nationality"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nationality') border-red-500 @enderror"
                                required>
                                <option value="">اختر الجنسية</option>
                                <option value="سعودي" {{ old('nationality') == 'سعودي' ? 'selected' : '' }}>سعودي
                                </option>
                                <option value="مصري" {{ old('nationality') == 'مصري' ? 'selected' : '' }}>مصري</option>
                                <option value="سوداني" {{ old('nationality') == 'سوداني' ? 'selected' : '' }}>سوداني
                                </option>
                                <option value="يمني" {{ old('nationality') == 'يمني' ? 'selected' : '' }}>يمني</option>
                                <option value="سوري" {{ old('nationality') == 'سوري' ? 'selected' : '' }}>سوري</option>
                                <option value="أردني" {{ old('nationality') == 'أردني' ? 'selected' : '' }}>أردني
                                </option>
                                <option value="لبناني" {{ old('nationality') == 'لبناني' ? 'selected' : '' }}>لبناني
                                </option>
                                <option value="فلسطيني" {{ old('nationality') == 'فلسطيني' ? 'selected' : '' }}>فلسطيني
                                </option>
                                <option value="باكستاني" {{ old('nationality') == 'باكستاني' ? 'selected' : '' }}>باكستاني
                                </option>
                                <option value="هندي" {{ old('nationality') == 'هندي' ? 'selected' : '' }}>هندي</option>
                                <option value="بنغلاديشي" {{ old('nationality') == 'بنغلاديشي' ? 'selected' : '' }}>
                                    بنغلاديشي</option>
                                <option value="فلبيني" {{ old('nationality') == 'فلبيني' ? 'selected' : '' }}>فلبيني
                                </option>
                                <option value="إثيوبي" {{ old('nationality') == 'إثيوبي' ? 'selected' : '' }}>إثيوبي
                                </option>
                                <option value="أخرى" {{ old('nationality') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('nationality')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">الديانة</label>
                            <select id="religion" name="religion"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('religion') border-red-500 @enderror">
                                <option value="">اختر الديانة</option>
                                <option value="الإسلام" {{ old('religion') == 'الإسلام' ? 'selected' : '' }}>الإسلام
                                </option>
                                <option value="المسيحية" {{ old('religion') == 'المسيحية' ? 'selected' : '' }}>المسيحية
                                </option>
                                <option value="اليهودية" {{ old('religion') == 'اليهودية' ? 'selected' : '' }}>اليهودية
                                </option>
                                <option value="الهندوسية" {{ old('religion') == 'الهندوسية' ? 'selected' : '' }}>الهندوسية
                                </option>
                                <option value="البوذية" {{ old('religion') == 'البوذية' ? 'selected' : '' }}>البوذية
                                </option>
                                <option value="السيخية" {{ old('religion') == 'السيخية' ? 'selected' : '' }}>السيخية
                                </option>
                                <option value="أخرى" {{ old('religion') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('religion')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="medical_insurance_status"
                                class="block text-sm font-medium text-gray-700 mb-2">حالة التأمين الطبي</label>
                            <select id="medical_insurance_status" name="medical_insurance_status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('medical_insurance_status') border-red-500 @enderror">
                                <option value="">اختر حالة التأمين</option>
                                <option value="مشمول" {{ old('medical_insurance_status') == 'مشمول' ? 'selected' : '' }}>
                                    مشمول</option>
                                <option value="غير مشمول"
                                    {{ old('medical_insurance_status') == 'غير مشمول' ? 'selected' : '' }}>غير مشمول
                                </option>
                            </select>
                            @error('medical_insurance_status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الإقامة
                                *</label>
                            <select id="location_type" name="location_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location_type') border-red-500 @enderror"
                                required>
                                <option value="">اختر نوع الإقامة</option>
                                <option value="داخل المملكة"
                                    {{ old('location_type') == 'داخل المملكة' ? 'selected' : '' }}>داخل المملكة</option>
                                <option value="خارج المملكة"
                                    {{ old('location_type') == 'خارج المملكة' ? 'selected' : '' }}>خارج المملكة</option>
                            </select>
                            @error('location_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Personal Photo -->
                    <div class="mt-6">
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">الصورة الشخصية</label>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden"
                                id="photo_preview">
                                <i class="ri-user-line text-3xl text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="photo" name="photo" accept="image/*"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('photo') border-red-500 @enderror"
                                    onchange="previewImage('photo', 'photo_preview')">
                                <p class="text-sm text-gray-500 mt-1">JPG, PNG حتى 2MB</p>
                                @error('photo')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- National ID Photo -->
                    <div class="mt-6">
                        <label for="national_id_photo" class="block text-sm font-medium text-gray-700 mb-2">صورة الهوية
                            الوطنية</label>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="w-32 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden"
                                id="national_id_preview">
                                <i class="ri-id-card-line text-2xl text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="national_id_photo" name="national_id_photo" accept="image/*"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('national_id_photo') border-red-500 @enderror"
                                    onchange="previewImage('national_id_photo', 'national_id_preview')">
                                <p class="text-sm text-gray-500 mt-1">JPG, PNG حتى 2MB</p>
                                @error('national_id_photo')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Passport Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات جواز السفر</h3>

                    <!-- تحذير جواز السفر -->
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="ri-passport-line text-green-600 text-sm mt-0.5"></i>
                            <div class="text-xs text-green-800">
                                <strong>ملاحظات جواز السفر:</strong>
                                <ul class="list-disc pl-4 mt-1 space-y-1">
                                    <li>تأكد من صحة رقم جواز السفر وتواريخ الإصدار والانتهاء</li>
                                    <li>الصورة يجب أن تكون واضحة وتظهر جميع البيانات</li>
                                    <li>سيتم تنبيهك قبل 30 يوماً من انتهاء صلاحية الجواز</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="passport_number" class="block text-sm font-medium text-gray-700 mb-2">رقم جواز
                                السفر</label>
                            <input type="text" id="passport_number" name="passport_number"
                                value="{{ old('passport_number') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('passport_number') border-red-500 @enderror">
                            @error('passport_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passport_issue_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ
                                إصدار جواز السفر</label>
                            <input type="date" id="passport_issue_date" name="passport_issue_date"
                                value="{{ old('passport_issue_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('passport_issue_date') border-red-500 @enderror">
                            @error('passport_issue_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passport_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ
                                انتهاء جواز السفر</label>
                            <input type="date" id="passport_expiry_date" name="passport_expiry_date"
                                value="{{ old('passport_expiry_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('passport_expiry_date') border-red-500 @enderror">
                            @error('passport_expiry_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Passport Photo -->
                    <div class="mt-6">
                        <label for="passport_photo" class="block text-sm font-medium text-gray-700 mb-2">صورة جواز
                            السفر</label>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="w-32 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden"
                                id="passport_preview">
                                <i class="ri-passport-line text-2xl text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="passport_photo" name="passport_photo" accept="image/*"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('passport_photo') border-red-500 @enderror"
                                    onchange="previewImage('passport_photo', 'passport_preview')">
                                <p class="text-sm text-gray-500 mt-1">JPG, PNG حتى 2MB</p>
                                @error('passport_photo')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Permit Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات بطاقة التشغيل</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="work_permit_number" class="block text-sm font-medium text-gray-700 mb-2">رقم بطاقة
                                التشغيل</label>
                            <input type="text" id="work_permit_number" name="work_permit_number"
                                value="{{ old('work_permit_number') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('work_permit_number') border-red-500 @enderror">
                            @error('work_permit_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="work_permit_issue_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ
                                إصدار بطاقة التشغيل</label>
                            <input type="date" id="work_permit_issue_date" name="work_permit_issue_date"
                                value="{{ old('work_permit_issue_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('work_permit_issue_date') border-red-500 @enderror">
                            @error('work_permit_issue_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="work_permit_expiry_date"
                                class="block text-sm font-medium text-gray-700 mb-2">تاريخ انتهاء بطاقة التشغيل</label>
                            <input type="date" id="work_permit_expiry_date" name="work_permit_expiry_date"
                                value="{{ old('work_permit_expiry_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('work_permit_expiry_date') border-red-500 @enderror">
                            @error('work_permit_expiry_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Work Permit Photo -->
                    <div class="mt-6">
                        <label for="work_permit_photo" class="block text-sm font-medium text-gray-700 mb-2">صورة بطاقة
                            التشغيل</label>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="w-32 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden"
                                id="work_permit_preview">
                                <i class="ri-briefcase-line text-2xl text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="work_permit_photo" name="work_permit_photo" accept="image/*"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('work_permit_photo') border-red-500 @enderror"
                                    onchange="previewImage('work_permit_photo', 'work_permit_preview')">
                                <p class="text-sm text-gray-500 mt-1">JPG, PNG حتى 2MB</p>
                                @error('work_permit_photo')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Driving License Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات رخصة القيادة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="driving_license_issue_date"
                                class="block text-sm font-medium text-gray-700 mb-2">تاريخ إصدار رخصة القيادة</label>
                            <input type="date" id="driving_license_issue_date" name="driving_license_issue_date"
                                value="{{ old('driving_license_issue_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('driving_license_issue_date') border-red-500 @enderror">
                            @error('driving_license_issue_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="driving_license_expiry" class="block text-sm font-medium text-gray-700 mb-2">تاريخ
                                انتهاء رخصة القيادة</label>
                            <input type="date" id="driving_license_expiry" name="driving_license_expiry"
                                value="{{ old('driving_license_expiry') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('driving_license_expiry') border-red-500 @enderror">
                            @error('driving_license_expiry')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Driving License Photo -->
                    <div class="mt-6">
                        <label for="driving_license_photo" class="block text-sm font-medium text-gray-700 mb-2">صورة رخصة
                            القيادة</label>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="w-32 h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden"
                                id="driving_license_preview">
                                <i class="ri-car-line text-2xl text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="driving_license_photo" name="driving_license_photo"
                                    accept="image/*"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('driving_license_photo') border-red-500 @enderror"
                                    onchange="previewImage('driving_license_photo', 'driving_license_preview')">
                                <p class="text-sm text-gray-500 mt-1">JPG, PNG حتى 2MB</p>
                                @error('driving_license_photo')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الوظيفة</h3>

                    <!-- تحذير معلومات الوظيفة -->
                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="ri-alert-line text-yellow-600 text-sm mt-0.5"></i>
                            <div class="text-xs text-yellow-800">
                                <strong>تنبيه:</strong> معلومات الوظيفة ستؤثر على صلاحيات الموظف في النظام. تأكد من اختيار
                                المنصب والقسم والصلاحية بدقة.
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">المنصب *</label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('position') border-red-500 @enderror"
                                required>
                            @error('position')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">القسم *</label>
                            <select id="department" name="department"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department') border-red-500 @enderror"
                                required>
                                <option value="">اختر القسم</option>
                                <option value="الإدارة" {{ old('department') == 'الإدارة' ? 'selected' : '' }}>الإدارة
                                </option>
                                <option value="المالية" {{ old('department') == 'المالية' ? 'selected' : '' }}>المالية
                                </option>
                                <option value="الهندسة" {{ old('department') == 'الهندسة' ? 'selected' : '' }}>الهندسة
                                </option>
                                <option value="الموارد البشرية"
                                    {{ old('department') == 'الموارد البشرية' ? 'selected' : '' }}>الموارد البشرية</option>
                                <option value="الصيانة" {{ old('department') == 'الصيانة' ? 'selected' : '' }}>الصيانة
                                </option>
                                <option value="المشاريع" {{ old('department') == 'المشاريع' ? 'selected' : '' }}>المشاريع
                                </option>
                                <option value="النقليات" {{ old('department') == 'النقليات' ? 'selected' : '' }}>النقليات
                                </option>
                            </select>
                            @error('department')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ التوظيف
                                *</label>
                            <input type="date" id="hire_date" name="hire_date" value="{{ old('hire_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('hire_date') border-red-500 @enderror"
                                required>
                            @error('hire_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">الراتب (ريال سعودي)
                                *</label>
                            <input type="number" id="salary" name="salary" value="{{ old('salary') }}"
                                min="0" step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('salary') border-red-500 @enderror"
                                required>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="ri-information-line"></i>
                                أدخل الراتب الأساسي فقط. البدلات والحوافز يمكن إضافتها لاحقاً في قسم الرواتب.
                            </p>
                            @error('salary')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="working_hours" class="block text-sm font-medium text-gray-700 mb-2">عدد ساعات
                                العمل اليومية *</label>
                            <input type="number" id="working_hours" name="working_hours"
                                value="{{ old('working_hours', 8) }}" min="1" max="24" step="0.5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('working_hours') border-red-500 @enderror"
                                required>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="ri-time-line"></i>
                                عدد الساعات التي يعمل بها الموظف يومياً (من 1 إلى 24 ساعة)
                            </p>
                            @error('working_hours')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">الموقع</label>
                            <select id="location_id" name="location_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location_id') border-red-500 @enderror">
                                <option value="">اختر الموقع</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }} - {{ $location->city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location_assignment_date"
                                class="block text-sm font-medium text-gray-700 mb-2">تاريخ التعيين في الموقع</label>
                            <input type="date" id="location_assignment_date" name="location_assignment_date"
                                value="{{ old('location_assignment_date') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location_assignment_date') border-red-500 @enderror">
                            @error('location_assignment_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Role/Permission Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">دور النظام والكفالة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">الدور في النظام
                                *</label>
                            <select id="role" name="role"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror"
                                required>
                                <option value="">اختر الدور</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">سيحدد هذا الدور الصلاحيات المتاحة للموظف في النظام</p>
                        </div>

                        <div>
                            <label for="sponsorship_status" class="block text-sm font-medium text-gray-700 mb-2">الكفالة
                                *</label>
                            <select id="sponsorship_status" name="sponsorship_status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sponsorship_status') border-red-500 @enderror"
                                required>
                                <option value="">اختر الكفالة</option>
                                <option value="شركة الأبراج للمقاولات المحدودة"
                                    {{ old('sponsorship_status') == 'شركة الأبراج للمقاولات المحدودة' ? 'selected' : '' }}>
                                    شركة الأبراج للمقاولات المحدودة</option>
                                <option value="فرع1 شركة الأبراج للمقاولات المحدودة"
                                    {{ old('sponsorship_status') == 'فرع1 شركة الأبراج للمقاولات المحدودة' ? 'selected' : '' }}>
                                    فرع1 شركة الأبراج للمقاولات المحدودة</option>
                                <option value="فرع2 شركة الأبراج للمقاولات المحدودة"
                                    {{ old('sponsorship_status') == 'فرع2 شركة الأبراج للمقاولات المحدودة' ? 'selected' : '' }}>
                                    فرع2 شركة الأبراج للمقاولات المحدودة</option>
                                <option value="مؤسسة فريق التعمير للمقاولات"
                                    {{ old('sponsorship_status') == 'مؤسسة فريق التعمير للمقاولات' ? 'selected' : '' }}>
                                    مؤسسة فريق التعمير للمقاولات</option>
                                <option value="فرع مؤسسة فريق التعمير للنقل"
                                    {{ old('sponsorship_status') == 'فرع مؤسسة فريق التعمير للنقل' ? 'selected' : '' }}>
                                    فرع مؤسسة فريق التعمير للنقل</option>
                                <option value="مؤسسة الزفاف الذهبي"
                                    {{ old('sponsorship_status') == 'مؤسسة الزفاف الذهبي' ? 'selected' : '' }}>مؤسسة
                                    الزفاف الذهبي</option>
                                <option value="مؤسسة عنوان الكادي"
                                    {{ old('sponsorship_status') == 'مؤسسة عنوان الكادي' ? 'selected' : '' }}>مؤسسة عنوان
                                    الكادي</option>
                                <option value="عمالة منزلية"
                                    {{ old('sponsorship_status') == 'عمالة منزلية' ? 'selected' : '' }}>عمالة منزلية
                                </option>
                                <option value="عمالة كفالة خارجية تحت التجربة"
                                    {{ old('sponsorship_status') == 'عمالة كفالة خارجية تحت التجربة' ? 'selected' : '' }}>
                                    عمالة كفالة خارجية تحت التجربة</option>
                                <option value="أخرى" {{ old('sponsorship_status') == 'أخرى' ? 'selected' : '' }}>أخرى
                                </option>
                            </select>
                            @error('sponsorship_status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">الفئة *</label>
                            <select id="category" name="category"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror"
                                required>
                                <option value="">اختر الفئة</option>
                                <option value="A+" {{ old('category') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A" {{ old('category') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('category') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('category') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('category') == 'D' ? 'selected' : '' }}>D</option>
                                <option value="E" {{ old('category') == 'E' ? 'selected' : '' }}>E</option>
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Documents -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">الوثائق الإضافية (اختيارية)</h3>

                    <!-- تحذير الوثائق -->
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="ri-information-line text-blue-600 text-sm mt-0.5"></i>
                            <div class="text-xs text-blue-800">
                                <strong>معلومات مهمة حول الوثائق الإضافية:</strong>
                                <ul class="list-disc pl-4 mt-1 space-y-1">
                                    <li><strong>هذا القسم اختياري تماماً</strong> - يمكنك تخطيه والعودة لإضافة الوثائق
                                        لاحقاً</li>
                                    <li>يمكن ترك حقول الوثائق فارغة دون أي مشكلة</li>
                                    <li>الوثائق الإضافية مفيدة لحفظ شهادات الخبرة، الدورات التدريبية، إلخ</li>
                                    <li>إذا قررت إضافة وثائق، تأكد من وضوح جودة الصور المرفقة</li>
                                    <li>الحد الأقصى لحجم كل وثيقة هو 5MB</li>
                                    <li>الصيغ المدعومة: JPG, PNG, PDF, DOC, DOCX</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6">
                        <p class="text-sm text-gray-600 mb-4">
                            <i class="ri-file-add-line text-blue-500 ml-1"></i>
                            يمكنك إرفاق وثائق إضافية مع تحديد اسم كل وثيقة (اختياري - يمكن تركه فارغاً)
                        </p>

                        <div id="additional-documents-container" class="space-y-4">
                            <!-- Document 1 -->
                            <div class="document-row bg-white p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-medium text-gray-700">وثيقة إضافية #1</span>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">اختيارية</span>
                                        <button type="button" onclick="removeDocumentRow(this)"
                                            class="text-red-600 hover:text-red-800 text-xs">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم الوثيقة
                                            (اختياري)</label>
                                        <input type="text" name="additional_documents[0][name]"
                                            placeholder="مثل: شهادة خبرة، دورة تدريبية، إلخ (يمكن تركه فارغاً)"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">ملف الوثيقة
                                            (اختياري)</label>
                                        <div class="relative">
                                            <input type="file" name="additional_documents[0][file]"
                                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <small class="text-gray-500 text-xs">الصيغ المدعومة: PDF, JPG, PNG, DOC, DOCX
                                                (حد أقصى 5MB) - اختياري</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add More Documents Button -->
                        <div class="mt-4 text-center">
                            <button type="button" onclick="addDocumentRow()"
                                class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-all duration-200 flex items-center mx-auto">
                                <i class="ri-add-line ml-2"></i>
                                إضافة وثيقة أخرى (اختيارية)
                            </button>
                            <p class="text-xs text-gray-500 mt-2">يمكنك تخطي هذا القسم بالكامل إذا لم تكن تريد إضافة وثائق
                                إضافية</p>
                        </div>
                    </div>
                </div>

                <!-- Employee Rating Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-star-line text-yellow-500"></i>
                        تقييم الموظف
                    </h3>
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-3">تقييم الأداء (من 1 إلى
                            5 نجوم)</label>
                        <div class="flex items-center gap-2">
                            <div class="star-rating" data-rating="0">
                                <input type="hidden" name="rating" id="rating" value="{{ old('rating', '') }}">
                                <div class="flex gap-1">
                                    <span class="star" data-value="1">
                                        <i
                                            class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                    <span class="star" data-value="2">
                                        <i
                                            class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                    <span class="star" data-value="3">
                                        <i
                                            class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                    <span class="star" data-value="4">
                                        <i
                                            class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                    <span class="star" data-value="5">
                                        <i
                                            class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="rating-text text-sm text-gray-600 mr-3">لم يتم التقييم بعد</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">انقر على النجوم لتحديد تقييم الموظف (اختياري)</p>
                        @error('rating')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bank Account Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الحساب البنكي</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">اسم البنك</label>
                            <select id="bank_name" name="bank_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bank_name') border-red-500 @enderror">
                                <option value="">اختر البنك</option>
                                <option value="البنك الأهلي السعودي"
                                    {{ old('bank_name') == 'البنك الأهلي السعودي' ? 'selected' : '' }}>البنك الأهلي
                                    السعودي</option>
                                <option value="بنك الرياض" {{ old('bank_name') == 'بنك الرياض' ? 'selected' : '' }}>بنك
                                    الرياض</option>
                                <option value="البنك السعودي للاستثمار"
                                    {{ old('bank_name') == 'البنك السعودي للاستثمار' ? 'selected' : '' }}>البنك السعودي
                                    للاستثمار</option>
                                <option value="البنك السعودي الفرنسي"
                                    {{ old('bank_name') == 'البنك السعودي الفرنسي' ? 'selected' : '' }}>البنك السعودي
                                    الفرنسي</option>
                                <option value="البنك السعودي البريطاني (ساب)"
                                    {{ old('bank_name') == 'البنك السعودي البريطاني (ساب)' ? 'selected' : '' }}>البنك
                                    السعودي البريطاني (ساب)</option>
                                <option value="بنك سامبا" {{ old('bank_name') == 'بنك سامبا' ? 'selected' : '' }}>بنك
                                    سامبا</option>
                                <option value="البنك الأول" {{ old('bank_name') == 'البنك الأول' ? 'selected' : '' }}>
                                    البنك الأول</option>
                                <option value="مصرف الإنماء" {{ old('bank_name') == 'مصرف الإنماء' ? 'selected' : '' }}>
                                    مصرف الإنماء</option>
                                <option value="مصرف الراجحي" {{ old('bank_name') == 'مصرف الراجحي' ? 'selected' : '' }}>
                                    مصرف الراجحي</option>
                                <option value="بنك الجزيرة" {{ old('bank_name') == 'بنك الجزيرة' ? 'selected' : '' }}>بنك
                                    الجزيرة</option>
                                <option value="بنك البلاد" {{ old('bank_name') == 'بنك البلاد' ? 'selected' : '' }}>بنك
                                    البلاد</option>
                                <option value="البنك العربي الوطني"
                                    {{ old('bank_name') == 'البنك العربي الوطني' ? 'selected' : '' }}>البنك العربي الوطني
                                </option>
                                <option value="مصرف آسيا" {{ old('bank_name') == 'مصرف آسيا' ? 'selected' : '' }}>مصرف
                                    آسيا</option>
                                <option value="بنك الخليج الدولي"
                                    {{ old('bank_name') == 'بنك الخليج الدولي' ? 'selected' : '' }}>بنك الخليج الدولي
                                </option>
                                <option value="بنك اتش اس بي سي السعودية"
                                    {{ old('bank_name') == 'بنك اتش اس بي سي السعودية' ? 'selected' : '' }}>بنك اتش اس بي
                                    سي السعودية</option>
                                <option value="البنك الأهلي المصري"
                                    {{ old('bank_name') == 'البنك الأهلي المصري' ? 'selected' : '' }}>البنك الأهلي المصري
                                </option>
                                <option value="بنك دويتشه السعودية"
                                    {{ old('bank_name') == 'بنك دويتشه السعودية' ? 'selected' : '' }}>بنك دويتشه السعودية
                                </option>
                                <option value="بنك الاتحاد" {{ old('bank_name') == 'بنك الاتحاد' ? 'selected' : '' }}>بنك
                                    الاتحاد</option>
                                <option value="بنك مؤسسة النقد العربي السعودي"
                                    {{ old('bank_name') == 'بنك مؤسسة النقد العربي السعودي' ? 'selected' : '' }}>بنك مؤسسة
                                    النقد العربي السعودي</option>
                                <option value="بنك التنمية الاجتماعية"
                                    {{ old('bank_name') == 'بنك التنمية الاجتماعية' ? 'selected' : '' }}>بنك التنمية
                                    الاجتماعية</option>
                                <option value="أخرى" {{ old('bank_name') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('bank_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="iban" class="block text-sm font-medium text-gray-700 mb-2">رقم الآيبان
                                (IBAN)</label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">SA</span>
                                <input type="text" id="iban" name="iban" value="{{ old('iban') }}"
                                    placeholder="0000000000000000000000" maxlength="22"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('iban') border-red-500 @enderror"
                                    oninput="validateIban(this)">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">أدخل 22 رقم فقط (بدون SA)</p>
                            @error('iban')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-200">
                    <a href="{{ route('employees.index') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200">
                        إلغاء
                    </a>
                    <button type="button" onclick="showDataPreview()"
                        class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-3 rounded-xl font-medium hover:from-gray-700 hover:to-gray-800 transition-all duration-200 flex items-center">
                        <i class="ri-eye-line ml-2"></i>
                        معاينة البيانات
                    </button>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
                        <i class="ri-save-line ml-2"></i>
                        حفظ الموظف
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.innerHTML =
                        `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Validate file size
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (this.files[0] && this.files[0].size > maxSize) {
                    alert('حجم الملف كبير جداً. الحد الأقصى 2MB');
                    this.value = '';
                    // Clear preview
                    const previewId = this.getAttribute('onchange').match(/'([^']+)'/g)[1].replace(/'/g,
                        '');
                    const iconClass = this.id.includes('photo') && !this.id.includes('national') && !this.id
                        .includes('passport') && !this.id.includes('work_permit') && !this.id.includes(
                            'driving_license') ? 'user' :
                        this.id.includes('national') ? 'id-card' :
                        this.id.includes('passport') ? 'passport' :
                        this.id.includes('work_permit') ? 'briefcase' :
                        this.id.includes('driving_license') ? 'car' : 'user';
                    document.getElementById(previewId).innerHTML =
                        `<i class="ri-${iconClass}-line text-2xl text-gray-400"></i>`;
                }
            });
        });

        // IBAN validation function
        function validateIban(input) {
            // Remove any non-numeric characters
            let value = input.value.replace(/\D/g, '');

            // Limit to 22 digits
            if (value.length > 22) {
                value = value.substring(0, 22);
            }

            // Update the input value
            input.value = value;

            // Visual feedback
            if (value.length === 22) {
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
            } else if (value.length > 0) {
                input.classList.remove('border-green-500');
                input.classList.add('border-yellow-500');
            } else {
                input.classList.remove('border-green-500', 'border-yellow-500');
            }
        }

        // Additional documents management
        let documentCount = 1;

        function addDocumentRow() {
            const container = document.getElementById('additional-documents-container');
            const newRow = document.createElement('div');
            newRow.className = 'document-row bg-white p-4 rounded-lg border border-gray-200';
            newRow.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-700">وثيقة إضافية #${documentCount + 1}</span>
            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">اختيارية</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">اسم الوثيقة (اختياري)</label>
                <input type="text"
                       name="additional_documents[${documentCount}][name]"
                       placeholder="مثل: شهادة خبرة، دورة تدريبية، إلخ (يمكن تركه فارغاً)"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ملف الوثيقة (اختياري)</label>
                <div class="relative">
                    <input type="file"
                           name="additional_documents[${documentCount}][file]"
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <small class="text-gray-500 text-xs">الصيغ المدعومة: PDF, JPG, PNG, DOC, DOCX (حد أقصى 5MB) - اختياري</small>
                </div>
            </div>
        </div>
        <div class="mt-2 flex justify-end">
            <button type="button"
                    onclick="removeDocumentRow(this)"
                    class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm font-medium hover:bg-red-200 transition-all duration-200 flex items-center">
                <i class="ri-delete-bin-line ml-1"></i>
                حذف
            </button>
        </div>
    `;

            container.appendChild(newRow);
            documentCount++;
        }

        function removeDocumentRow(button) {
            const row = button.closest('.document-row');
            row.remove();
        }

        // Additional documents file validation
        document.addEventListener('change', function(e) {
            if (e.target.name && e.target.name.includes('additional_documents') && e.target.type === 'file') {
                const maxSize = 5 * 1024 * 1024; // 5MB for additional documents
                if (e.target.files[0] && e.target.files[0].size > maxSize) {
                    alert('حجم الملف كبير جداً. الحد الأقصى 5MB');
                    e.target.value = '';
                }
            }
        });

        // Star Rating System
        document.addEventListener('DOMContentLoaded', function() {
            const starRating = document.querySelector('.star-rating');
            const stars = starRating.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating');
            const ratingText = document.querySelector('.rating-text');

            const ratingTexts = {
                0: 'لم يتم التقييم بعد',
                1: 'ضعيف جداً',
                2: 'ضعيف',
                3: 'متوسط',
                4: 'جيد',
                5: 'ممتاز'
            };

            // Initialize rating if there's an old value
            const currentRating = parseInt(ratingInput.value) || 0;
            if (currentRating > 0) {
                updateStars(currentRating);
                ratingText.textContent = ratingTexts[currentRating];
            }

            stars.forEach((star, index) => {
                const value = parseInt(star.dataset.value);

                // Click event
                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.value);
                    ratingInput.value = rating;
                    updateStars(rating);
                    ratingText.textContent = ratingTexts[rating];
                });

                // Hover events
                star.addEventListener('mouseenter', function() {
                    const hoverRating = parseInt(this.dataset.value);
                    updateStars(hoverRating, true);
                    ratingText.textContent = ratingTexts[hoverRating];
                });
            });

            // Reset on mouse leave
            starRating.addEventListener('mouseleave', function() {
                const currentRating = parseInt(ratingInput.value) || 0;
                updateStars(currentRating);
                ratingText.textContent = ratingTexts[currentRating];
            });

            function updateStars(rating, isHover = false) {
                stars.forEach((star, index) => {
                    const starIcon = star.querySelector('i');
                    const starValue = parseInt(star.dataset.value);

                    if (starValue <= rating) {
                        starIcon.className =
                            'ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-yellow-400';
                    } else {
                        starIcon.className =
                            'ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400';
                    }
                });
            }
        });

        // نظام التحذيرات المتقدم
        document.addEventListener('DOMContentLoaded', function() {
            // التحقق من البيانات المطلوبة
            const requiredFields = [{
                    id: 'name',
                    name: 'الاسم الكامل'
                },
                {
                    id: 'national_id',
                    name: 'رقم الهوية'
                },
                {
                    id: 'email',
                    name: 'البريد الإلكتروني'
                },
                {
                    id: 'phone',
                    name: 'رقم الهاتف'
                },
                {
                    id: 'nationality',
                    name: 'الجنسية'
                },
                {
                    id: 'position',
                    name: 'المنصب'
                },
                {
                    id: 'department',
                    name: 'القسم'
                },
                {
                    id: 'hire_date',
                    name: 'تاريخ التوظيف'
                },
                {
                    id: 'salary',
                    name: 'الراتب'
                },
                {
                    id: 'role',
                    name: 'الصلاحية'
                },
                {
                    id: 'status',
                    name: 'الحالة'
                },
                {
                    id: 'category',
                    name: 'الفئة'
                },
                {
                    id: 'sponsorship_status',
                    name: 'الكفالة'
                }
            ];

            // إنشاء حاوية التحذيرات
            function createWarningContainer() {
                let warningContainer = document.getElementById('validation-warnings');
                if (!warningContainer) {
                    warningContainer = document.createElement('div');
                    warningContainer.id = 'validation-warnings';
                    warningContainer.className = 'hidden fixed top-4 right-4 z-50 max-w-md';
                    document.body.appendChild(warningContainer);
                }
                return warningContainer;
            }

            // إظهار تحذيرات البيانات الناقصة
            function showMissingFieldsWarning(missingFields) {
                const warningContainer = createWarningContainer();
                const missingFieldsList = missingFields.map(field => `<li>${field.name}</li>`).join('');

                warningContainer.innerHTML = `
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-lg">
                <div class="flex items-start">
                    <i class="ri-error-warning-line text-red-400 text-xl ml-2"></i>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">حقول مطلوبة لم يتم ملؤها</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5">
                                ${missingFieldsList}
                            </ul>
                            <p class="text-xs mt-2 text-red-600">ملاحظة: الوثائق الإضافية اختيارية ولا تحتاج لملئها</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.parentElement.parentElement.classList.add('hidden')"
                                class="mt-2 text-xs text-red-600 hover:text-red-800 flex items-center gap-1">
                            <i class="ri-close-line"></i>
                            إغلاق
                        </button>
                    </div>
                </div>
            </div>
        `;
                warningContainer.classList.remove('hidden');

                // إخفاء التحذير تلقائياً بعد 8 ثواني
                setTimeout(() => {
                    warningContainer.classList.add('hidden');
                }, 8000);
            }

            // التحقق من صحة البريد الإلكتروني
            function validateEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // التحقق من رقم الهوية السعودية
            function validateSaudiId(id) {
                const idRegex = /^[12]\d{9}$/;
                return idRegex.test(id);
            }

            // التحقق من رقم الهاتف
            function validatePhone(phone) {
                const phoneRegex = /^(05|01|02|03|04|07|09)\d{8}$/;
                return phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''));
            }

            // التحقق من التواريخ
            function validateDates() {
                const today = new Date();
                const warnings = [];

                // تحقق من تاريخ انتهاء الهوية
                const nationalIdExpiry = document.getElementById('national_id_expiry');
                if (nationalIdExpiry && nationalIdExpiry.value) {
                    const expiryDate = new Date(nationalIdExpiry.value);
                    const daysDiff = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));

                    if (daysDiff < 0) {
                        warnings.push('تاريخ انتهاء الهوية الوطنية قد انتهى');
                    } else if (daysDiff <= 30) {
                        warnings.push('تاريخ انتهاء الهوية الوطنية قريب (خلال 30 يوماً)');
                    }
                }

                // تحقق من تاريخ انتهاء الجواز
                const passportExpiry = document.getElementById('passport_expiry_date');
                if (passportExpiry && passportExpiry.value) {
                    const expiryDate = new Date(passportExpiry.value);
                    const daysDiff = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));

                    if (daysDiff < 0) {
                        warnings.push('تاريخ انتهاء جواز السفر قد انتهى');
                    } else if (daysDiff <= 30) {
                        warnings.push('تاريخ انتهاء جواز السفر قريب (خلال 30 يوماً)');
                    }
                }

                return warnings;
            }

            // التحقق عند إرسال النموذج
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const missingFields = [];
                    let hasErrors = false;

                    // فحص الحقول المطلوبة
                    requiredFields.forEach(field => {
                        const element = document.getElementById(field.id);
                        if (element && !element.value.trim()) {
                            missingFields.push(field);
                            element.classList.add('border-red-500');
                            hasErrors = true;
                        } else if (element) {
                            element.classList.remove('border-red-500');
                        }
                    });

                    // فحص صحة البريد الإلكتروني
                    const emailField = document.getElementById('email');
                    if (emailField && emailField.value.trim() && !validateEmail(emailField.value.trim())) {
                        hasErrors = true;
                        emailField.classList.add('border-red-500');
                        showValidationError('البريد الإلكتروني غير صحيح');
                    }

                    // فحص رقم الهوية
                    const nationalIdField = document.getElementById('national_id');
                    if (nationalIdField && nationalIdField.value.trim() && !validateSaudiId(nationalIdField
                            .value.trim())) {
                        hasErrors = true;
                        nationalIdField.classList.add('border-red-500');
                        showValidationError(
                            'رقم الهوية غير صحيح (يجب أن يبدأ بـ 1 أو 2 ويحتوي على 10 أرقام)');
                    }

                    // فحص التواريخ
                    const dateWarnings = validateDates();
                    if (dateWarnings.length > 0) {
                        showValidationWarning('تحذيرات التواريخ', dateWarnings);
                    }

                    // إظهار الحقول المفقودة
                    if (missingFields.length > 0) {
                        e.preventDefault();
                        showMissingFieldsWarning(missingFields);

                        // التمرير إلى أول حقل مفقود
                        const firstMissingField = document.getElementById(missingFields[0].id);
                        if (firstMissingField) {
                            firstMissingField.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            firstMissingField.focus();
                        }
                    }

                    if (hasErrors) {
                        e.preventDefault();
                    }
                });
            }

            // إظهار رسائل الخطأ
            function showValidationError(message) {
                const warningContainer = createWarningContainer();
                warningContainer.innerHTML = `
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-lg">
                <div class="flex items-start">
                    <i class="ri-error-warning-line text-red-400 text-xl ml-2"></i>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">خطأ في البيانات</h3>
                        <p class="mt-1 text-sm text-red-700">${message}</p>
                        <button onclick="this.parentElement.parentElement.parentElement.classList.add('hidden')"
                                class="mt-2 text-xs text-red-600 hover:text-red-800 flex items-center gap-1">
                            <i class="ri-close-line"></i>
                            إغلاق
                        </button>
                    </div>
                </div>
            </div>
        `;
                warningContainer.classList.remove('hidden');

                setTimeout(() => {
                    warningContainer.classList.add('hidden');
                }, 5000);
            }

            // إظهار تحذيرات عامة
            function showValidationWarning(title, warnings) {
                const warningContainer = createWarningContainer();
                const warningsList = warnings.map(warning => `<li>${warning}</li>`).join('');

                warningContainer.innerHTML = `
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-lg">
                <div class="flex items-start">
                    <i class="ri-alert-line text-yellow-400 text-xl ml-2"></i>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">${title}</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc pl-5">
                                ${warningsList}
                            </ul>
                        </div>
                        <button onclick="this.parentElement.parentElement.parentElement.classList.add('hidden')"
                                class="mt-2 text-xs text-yellow-600 hover:text-yellow-800 flex items-center gap-1">
                            <i class="ri-close-line"></i>
                            إغلاق
                        </button>
                    </div>
                </div>
            </div>
        `;
                warningContainer.classList.remove('hidden');

                setTimeout(() => {
                    warningContainer.classList.add('hidden');
                }, 6000);
            }

            // التحقق الفوري من الحقول
            requiredFields.forEach(field => {
                const element = document.getElementById(field.id);
                if (element) {
                    element.addEventListener('blur', function() {
                        if (!this.value.trim()) {
                            this.classList.add('border-yellow-300');
                            this.classList.add('bg-yellow-50');
                        } else {
                            this.classList.remove('border-yellow-300');
                            this.classList.remove('bg-yellow-50');
                            this.classList.remove('border-red-500');
                        }
                    });

                    element.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.classList.remove('border-yellow-300');
                            this.classList.remove('bg-yellow-50');
                            this.classList.remove('border-red-500');
                        }
                    });
                }
            });
        });

        // وظيفة معاينة البيانات
        function showDataPreview() {
            const formData = new FormData(document.querySelector('form'));
            const data = {};

            for (let [key, value] of formData.entries()) {
                if (key !== '_token' && !key.includes('[file]')) {
                    data[key] = value;
                }
            }

            const previewHTML = `
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-4xl w-full max-h-90vh overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">معاينة بيانات الموظف</h3>
                        <button onclick="closeDataPreview()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-2xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- المعلومات الشخصية -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="ri-user-line text-blue-600"></i>
                            المعلومات الشخصية
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">الاسم الكامل</label>
                                <p class="text-sm font-medium text-gray-900">${data.name || 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">رقم الهوية</label>
                                <p class="text-sm font-medium text-gray-900">${data.national_id || 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">البريد الإلكتروني</label>
                                <p class="text-sm font-medium text-gray-900">${data.email || 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">رقم الهاتف</label>
                                <p class="text-sm font-medium text-gray-900">${data.phone || 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">الجنسية</label>
                                <p class="text-sm font-medium text-gray-900">${data.nationality || 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">تاريخ الميلاد</label>
                                <p class="text-sm font-medium text-gray-900">${data.birth_date || 'غير محدد'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات الوظيفة -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="ri-briefcase-line text-green-600"></i>
                            معلومات الوظيفة
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">المنصب</label>
                                <p class="text-sm font-medium text-gray-900">${data.position || 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">القسم</label>
                                <p class="text-sm font-medium text-gray-900">${data.department || 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">الراتب</label>
                                <p class="text-sm font-medium text-gray-900">${data.salary ? parseFloat(data.salary).toLocaleString('ar-SA') + ' ريال' : 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">تاريخ التوظيف</label>
                                <p class="text-sm font-medium text-gray-900">${data.hire_date || 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">الصلاحية</label>
                                <p class="text-sm font-medium text-gray-900">${data.role || 'غير محدد'}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <label class="block text-xs font-medium text-gray-600">الحالة</label>
                                <p class="text-sm font-medium text-gray-900">${data.status || 'غير محدد'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات الوثائق -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="ri-file-line text-purple-600"></i>
                            معلومات الوثائق
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${data.passport_number ? `
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <label class="block text-xs font-medium text-gray-600">رقم جواز السفر</label>
                                            <p class="text-sm font-medium text-gray-900">${data.passport_number}</p>
                                            ${data.passport_expiry_date ? `<p class="text-xs text-gray-500">ينتهي: ${data.passport_expiry_date}</p>` : ''}
                                        </div>
                                    ` : ''}
                            ${data.work_permit_number ? `
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <label class="block text-xs font-medium text-gray-600">رقم تصريح العمل</label>
                                            <p class="text-sm font-medium text-gray-900">${data.work_permit_number}</p>
                                            ${data.work_permit_expiry_date ? `<p class="text-xs text-gray-500">ينتهي: ${data.work_permit_expiry_date}</p>` : ''}
                                        </div>
                                    ` : ''}
                            ${data.driving_license_number ? `
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <label class="block text-xs font-medium text-gray-600">رقم رخصة القيادة</label>
                                            <p class="text-sm font-medium text-gray-900">${data.driving_license_number}</p>
                                            ${data.driving_license_expiry ? `<p class="text-xs text-gray-500">ينتهي: ${data.driving_license_expiry}</p>` : ''}
                                        </div>
                                    ` : ''}
                        </div>
                    </div>

                    <!-- تحذيرات -->
                    <div id="preview-warnings" class="space-y-3">
                        <!-- سيتم إضافة التحذيرات هنا -->
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3 space-x-reverse">
                    <button onclick="closeDataPreview()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        إغلاق
                    </button>
                    <button onclick="closeDataPreview(); document.querySelector('form').submit();"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        حفظ الموظف
                    </button>
                </div>
            </div>
        </div>
    `;

            // إنشاء العنصر وإضافته للصفحة
            const previewElement = document.createElement('div');
            previewElement.id = 'data-preview-modal';
            previewElement.innerHTML = previewHTML;
            document.body.appendChild(previewElement);

            // إضافة التحذيرات
            const warningsContainer = document.getElementById('preview-warnings');
            const warnings = [];

            // فحص الحقول المطلوبة
            const requiredFields = [{
                    field: 'name',
                    name: 'الاسم الكامل'
                },
                {
                    field: 'national_id',
                    name: 'رقم الهوية'
                },
                {
                    field: 'email',
                    name: 'البريد الإلكتروني'
                },
                {
                    field: 'phone',
                    name: 'رقم الهاتف'
                },
                {
                    field: 'position',
                    name: 'المنصب'
                },
                {
                    field: 'department',
                    name: 'القسم'
                },
                {
                    field: 'salary',
                    name: 'الراتب'
                }
            ];

            const missingFields = requiredFields.filter(field => !data[field.field] || !data[field.field].trim());

            if (missingFields.length > 0) {
                warnings.push({
                    type: 'error',
                    title: 'حقول مطلوبة مفقودة',
                    items: missingFields.map(field => field.name)
                });
            }

            // فحص التواريخ
            const today = new Date();
            const dateWarnings = [];

            if (data.passport_expiry_date) {
                const expiryDate = new Date(data.passport_expiry_date);
                const daysDiff = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));

                if (daysDiff < 0) {
                    dateWarnings.push('جواز السفر منتهي الصلاحية');
                } else if (daysDiff <= 30) {
                    dateWarnings.push('جواز السفر ينتهي خلال 30 يوماً');
                }
            }

            if (dateWarnings.length > 0) {
                warnings.push({
                    type: 'warning',
                    title: 'تحذيرات التواريخ',
                    items: dateWarnings
                });
            }

            // عرض التحذيرات
            warnings.forEach(warning => {
                const warningClass = warning.type === 'error' ? 'border-red-400 bg-red-50 text-red-800' :
                    'border-yellow-400 bg-yellow-50 text-yellow-800';
                const iconClass = warning.type === 'error' ? 'ri-error-warning-line text-red-600' :
                    'ri-alert-line text-yellow-600';

                warningsContainer.innerHTML += `
            <div class="border-l-4 ${warningClass} p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="${iconClass} text-xl ml-2"></i>
                    <div>
                        <h5 class="font-medium">${warning.title}</h5>
                        <ul class="list-disc pl-5 mt-1 text-sm">
                            ${warning.items.map(item => `<li>${item}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            </div>
        `;
            });

            if (warnings.length === 0) {
                warningsContainer.innerHTML = `
            <div class="border-l-4 border-green-400 bg-green-50 text-green-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="ri-check-line text-green-600 text-xl ml-2"></i>
                    <span class="font-medium">جميع البيانات مكتملة وصحيحة</span>
                </div>
            </div>
        `;
            }
        }

        function closeDataPreview() {
            const modal = document.getElementById('data-preview-modal');
            if (modal) {
                modal.remove();
            }
        }
    </script>
@endsection
