<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول :attribute.',
    'accepted_if' => 'يجب قبول :attribute عندما :other يكون :value.',
    'active_url' => ':attribute ليس رابطًا صحيحًا.',
    'after' => 'يجب أن يكون :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون :attribute تاريخًا بعد أو مساوٍ لـ :date.',
    'alpha' => ':attribute يجب أن يحتوي على أحرف فقط.',
    'alpha_dash' => ':attribute يجب أن يحتوي على أحرف وأرقام وشرطات وخطوط سفلية فقط.',
    'alpha_num' => ':attribute يجب أن يحتوي على أحرف وأرقام فقط.',
    'array' => ':attribute يجب أن يكون مصفوفة.',
    'ascii' => ':attribute يجب أن يحتوي على أحرف أبجدية رقمية ورموز أحادية البايت فقط.',
    'before' => 'يجب أن يكون :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون :attribute تاريخًا قبل أو مساوٍ لـ :date.',
    'between' => [
        'array' => ':attribute يجب أن يحتوي على بين :min و :max عنصرًا.',
        'file' => ':attribute يجب أن يكون بين :min و :max كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون بين :min و :max.',
        'string' => ':attribute يجب أن يكون بين :min و :max حرفًا.',
    ],
    'boolean' => ':attribute يجب أن يكون صحيحًا أو خاطئًا.',
    'can' => 'يحتوي حقل :attribute على قيمة غير مصرح بها.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => ':attribute ليس تاريخًا صحيحًا.',
    'date_equals' => 'يجب أن يكون :attribute تاريخًا مساوٍ لـ :date.',
    'date_format' => ':attribute لا يطابق التنسيق :format.',
    'decimal' => ':attribute يجب أن يحتوي على :decimal منازل عشرية.',
    'declined' => 'يجب رفض :attribute.',
    'declined_if' => 'يجب رفض :attribute عندما :other يكون :value.',
    'different' => ':attribute و :other يجب أن يكونا مختلفين.',
    'digits' => ':attribute يجب أن يكون :digits رقمًا.',
    'digits_between' => ':attribute يجب أن يكون بين :min و :max رقمًا.',
    'dimensions' => ':attribute يحتوي على أبعاد صورة غير صحيحة.',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
    'doesnt_end_with' => ':attribute يجب ألا ينتهي بأحد القيم التالية: :values.',
    'doesnt_start_with' => ':attribute يجب ألا يبدأ بأحد القيم التالية: :values.',
    'email' => ':attribute يجب أن يكون عنوان بريد إلكتروني صحيحًا.',
    'ends_with' => ':attribute يجب أن ينتهي بأحد القيم التالية: :values.',
    'enum' => ':attribute المختار غير صحيح.',
    'exists' => ':attribute المختار غير صحيح.',
    'extensions' => ':attribute يجب أن يحتوي على أحد الامتدادات التالية: :values.',
    'file' => ':attribute يجب أن يكون ملفًا.',
    'filled' => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt' => [
        'array' => ':attribute يجب أن يحتوي على أكثر من :value عنصرًا.',
        'file' => ':attribute يجب أن يكون أكبر من :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أكبر من :value.',
        'string' => ':attribute يجب أن يكون أكبر من :value حرفًا.',
    ],
    'gte' => [
        'array' => ':attribute يجب أن يحتوي على :value عنصرًا أو أكثر.',
        'file' => ':attribute يجب أن يكون أكبر من أو مساوٍ لـ :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أكبر من أو مساوٍ لـ :value.',
        'string' => ':attribute يجب أن يكون أكبر من أو مساوٍ لـ :value حرفًا.',
    ],
    'hex_color' => ':attribute يجب أن يكون لونًا سادس عشريًا صحيحًا.',
    'image' => ':attribute يجب أن يكون صورة.',
    'in' => ':attribute المختار غير صحيح.',
    'in_array' => 'حقل :attribute غير موجود في :other.',
    'integer' => ':attribute يجب أن يكون رقمًا صحيحًا.',
    'ip' => ':attribute يجب أن يكون عنوان IP صحيحًا.',
    'ipv4' => ':attribute يجب أن يكون عنوان IPv4 صحيحًا.',
    'ipv6' => ':attribute يجب أن يكون عنوان IPv6 صحيحًا.',
    'json' => ':attribute يجب أن يكون نص JSON صحيحًا.',
    'lowercase' => ':attribute يجب أن يكون بأحرف صغيرة.',
    'lt' => [
        'array' => ':attribute يجب أن يحتوي على أقل من :value عنصرًا.',
        'file' => ':attribute يجب أن يكون أقل من :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أقل من :value.',
        'string' => ':attribute يجب أن يكون أقل من :value حرفًا.',
    ],
    'lte' => [
        'array' => ':attribute يجب ألا يحتوي على أكثر من :value عنصرًا.',
        'file' => ':attribute يجب أن يكون أقل من أو مساوٍ لـ :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أقل من أو مساوٍ لـ :value.',
        'string' => ':attribute يجب أن يكون أقل من أو مساوٍ لـ :value حرفًا.',
    ],
    'mac_address' => ':attribute يجب أن يكون عنوان MAC صحيحًا.',
    'max' => [
        'array' => ':attribute يجب ألا يحتوي على أكثر من :max عنصرًا.',
        'file' => ':attribute يجب ألا يكون أكبر من :max كيلوبايت.',
        'numeric' => ':attribute يجب ألا يكون أكبر من :max.',
        'string' => ':attribute يجب ألا يكون أكبر من :max حرفًا.',
    ],
    'max_digits' => ':attribute يجب ألا يحتوي على أكثر من :max رقمًا.',
    'mimes' => ':attribute يجب أن يكون ملفًا من نوع: :values.',
    'mimetypes' => ':attribute يجب أن يكون ملفًا من نوع: :values.',
    'min' => [
        'array' => ':attribute يجب أن يحتوي على الأقل على :min عنصرًا.',
        'file' => ':attribute يجب أن يكون على الأقل :min كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون على الأقل :min.',
        'string' => ':attribute يجب أن يكون على الأقل :min حرفًا.',
    ],
    'min_digits' => ':attribute يجب أن يحتوي على الأقل على :min رقمًا.',
    'missing' => 'حقل :attribute يجب أن يكون مفقودًا.',
    'missing_if' => 'حقل :attribute يجب أن يكون مفقودًا عندما :other يكون :value.',
    'missing_unless' => 'حقل :attribute يجب أن يكون مفقودًا إلا إذا كان :other هو :value.',
    'missing_with' => 'حقل :attribute يجب أن يكون مفقودًا عند وجود :values.',
    'missing_with_all' => 'حقل :attribute يجب أن يكون مفقودًا عند وجود :values.',
    'multiple_of' => ':attribute يجب أن يكون مضاعفًا لـ :value.',
    'not_in' => ':attribute المختار غير صحيح.',
    'not_regex' => 'تنسيق :attribute غير صحيح.',
    'numeric' => ':attribute يجب أن يكون رقمًا.',
    'password' => [
        'letters' => ':attribute يجب أن يحتوي على حرف واحد على الأقل.',
        'mixed' => ':attribute يجب أن يحتوي على حرف كبير وحرف صغير على الأقل.',
        'numbers' => ':attribute يجب أن يحتوي على رقم واحد على الأقل.',
        'symbols' => ':attribute يجب أن يحتوي على رمز واحد على الأقل.',
        'uncompromised' => ':attribute المعطى ظهر في تسريب بيانات. يرجى اختيار :attribute مختلف.',
    ],
    'present' => 'حقل :attribute يجب أن يكون موجودًا.',
    'present_if' => 'حقل :attribute يجب أن يكون موجودًا عندما :other يكون :value.',
    'present_unless' => 'حقل :attribute يجب أن يكون موجودًا إلا إذا كان :other هو :value.',
    'present_with' => 'حقل :attribute يجب أن يكون موجودًا عند وجود :values.',
    'present_with_all' => 'حقل :attribute يجب أن يكون موجودًا عند وجود :values.',
    'prohibited' => 'حقل :attribute محظور.',
    'prohibited_if' => 'حقل :attribute محظور عندما :other يكون :value.',
    'prohibited_unless' => 'حقل :attribute محظور إلا إذا كان :other في :values.',
    'prohibits' => 'حقل :attribute يمنع وجود :other.',
    'regex' => 'تنسيق :attribute غير صحيح.',
    'required' => 'حقل :attribute مطلوب.',
    'required_array_keys' => 'حقل :attribute يجب أن يحتوي على مفاتيح لـ: :values.',
    'required_if' => 'حقل :attribute مطلوب عندما :other يكون :value.',
    'required_if_accepted' => 'حقل :attribute مطلوب عند قبول :other.',
    'required_unless' => 'حقل :attribute مطلوب إلا إذا كان :other في :values.',
    'required_with' => 'حقل :attribute مطلوب عند وجود :values.',
    'required_with_all' => 'حقل :attribute مطلوب عند وجود :values.',
    'required_without' => 'حقل :attribute مطلوب عند عدم وجود :values.',
    'required_without_all' => 'حقل :attribute مطلوب عند عدم وجود أي من :values.',
    'same' => ':attribute و :other يجب أن يتطابقا.',
    'size' => [
        'array' => ':attribute يجب أن يحتوي على :size عنصرًا.',
        'file' => ':attribute يجب أن يكون :size كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون :size.',
        'string' => ':attribute يجب أن يكون :size حرفًا.',
    ],
    'starts_with' => ':attribute يجب أن يبدأ بأحد القيم التالية: :values.',
    'string' => ':attribute يجب أن يكون نصًا.',
    'timezone' => ':attribute يجب أن يكون منطقة زمنية صحيحة.',
    'unique' => ':attribute مُستخدم من قبل.',
    'uploaded' => 'فشل في رفع :attribute.',
    'uppercase' => ':attribute يجب أن يكون بأحرف كبيرة.',
    'url' => ':attribute يجب أن يكون رابطًا صحيحًا.',
    'ulid' => ':attribute يجب أن يكون ULID صحيحًا.',
    'uuid' => ':attribute يجب أن يكون UUID صحيحًا.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'الاسم',
        'username' => 'اسم المستخدم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'city' => 'المدينة',
        'country' => 'البلد',
        'address' => 'العنوان',
        'phone' => 'الهاتف',
        'mobile' => 'الجوال',
        'age' => 'العمر',
        'sex' => 'الجنس',
        'gender' => 'النوع',
        'day' => 'اليوم',
        'month' => 'الشهر',
        'year' => 'السنة',
        'hour' => 'الساعة',
        'minute' => 'الدقيقة',
        'second' => 'الثانية',
        'title' => 'العنوان',
        'content' => 'المحتوى',
        'description' => 'الوصف',
        'excerpt' => 'المقتطف',
        'date' => 'التاريخ',
        'time' => 'الوقت',
        'available' => 'متاح',
        'size' => 'الحجم',
        'file' => 'الملف',
        'image' => 'الصورة',
        'photo' => 'الصورة',
        'avatar' => 'الصورة الشخصية',
        'terms' => 'الشروط',
        'message' => 'الرسالة',
        'g-recaptcha-response' => 'اختبار الأمان',
        'captcha' => 'اختبار الأمان',
        
        // Employee specific attributes
        'position' => 'المنصب',
        'department' => 'القسم',
        'hire_date' => 'تاريخ التوظيف',
        'salary' => 'الراتب',
        'working_hours' => 'ساعات العمل',
        'national_id' => 'رقم الهوية الوطنية',
        'national_id_expiry_date' => 'تاريخ انتهاء الهوية الوطنية',
        'role' => 'الدور الوظيفي',
        'sponsorship_status' => 'حالة الكفالة',
        'category' => 'الفئة',
        'national_id_photo' => 'صورة الهوية الوطنية',
        'passport_photo' => 'صورة جواز السفر',
        'work_permit_photo' => 'صورة تصريح العمل',
        'passport_number' => 'رقم جواز السفر',
        'passport_issue_date' => 'تاريخ إصدار جواز السفر',
        'passport_expiry_date' => 'تاريخ انتهاء جواز السفر',
        'work_permit_number' => 'رقم تصريح العمل',
        'work_permit_issue_date' => 'تاريخ إصدار تصريح العمل',
        'work_permit_expiry_date' => 'تاريخ انتهاء تصريح العمل',
        'driving_license_issue_date' => 'تاريخ إصدار رخصة القيادة',
        'driving_license_expiry' => 'تاريخ انتهاء رخصة القيادة',
        'driving_license_photo' => 'صورة رخصة القيادة',
        'location_id' => 'الموقع',
        'bank_name' => 'اسم البنك',
        'iban' => 'رقم الآيبان',
        'birth_date' => 'تاريخ الميلاد',
        'nationality' => 'الجنسية',
        'religion' => 'الديانة',
        'rating' => 'التقييم',
        'additional_documents' => 'المستندات الإضافية',
        'additional_documents.*.name' => 'اسم المستند',
        'additional_documents.*.file' => 'ملف المستند',
        'amount' => 'المبلغ',
        'notes' => 'الملاحظات',
        'transaction_date' => 'تاريخ المعاملة',
    ],

];
