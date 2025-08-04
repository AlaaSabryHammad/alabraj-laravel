<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير السرية - {{ $employee->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: white;
        }

        /* طباعة */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .page-break {
                page-break-before: always;
            }

            .no-print {
                display: none !important;
            }
        }

        /* العلامة المائية */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            z-index: -1;
            width: 300px;
            height: 300px;
            pointer-events: none;
        }

        .watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* تحسين الطباعة */
        .report-card {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        /* العلامة المائية المكررة */
        .watermark-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
            background-image: url('{{ asset("assets/logo.png") }}');
            background-repeat: repeat;
            background-size: 200px 200px;
            opacity: 0.05;
            transform: rotate(-45deg);
            background-position: 0 0, 100px 100px;
        }
    </style>
</head>
<body class="bg-white">
    <!-- العلامة المائية -->
    <div class="watermark-pattern"></div>

    <!-- زر الطباعة -->
    <div class="no-print fixed top-4 right-4 z-50">
        <button onclick="window.print()"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
            <i class="ri-printer-line"></i>
            طباعة
        </button>
    </div>

    <div class="max-w-4xl mx-auto p-8">
        <!-- رأس التقرير -->
        <div class="text-center mb-8 border-b-2 border-red-600 pb-6">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('assets/logo.png') }}" alt="شعار الشركة" class="h-16 w-auto">
            </div>
            <h1 class="text-3xl font-bold text-red-600 mb-2">شركة الأبراج للمقاولات العامة</h1>
            <h2 class="text-xl font-semibold text-gray-800 mb-1">التقارير السرية للموظف</h2>
            <div class="text-sm text-gray-600">
                <p>تاريخ الطباعة: {{ now()->format('Y/m/d') }} | الوقت: {{ now()->format('H:i') }}</p>
            </div>
        </div>

        <!-- معلومات الموظف -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8 border">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="ri-user-line text-blue-600"></i>
                بيانات الموظف
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- صورة الموظف -->
                <div class="flex justify-center md:justify-start">
                    @if($employee->photo)
                        <img src="{{ asset('storage/' . $employee->photo) }}"
                             alt="صورة {{ $employee->name }}"
                             class="w-32 h-32 rounded-lg object-cover border-2 border-gray-300">
                    @else
                        <div class="w-32 h-32 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-3xl">{{ mb_substr($employee->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <!-- بيانات الموظف -->
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-600">الاسم:</span>
                            <p class="font-semibold text-gray-900">{{ $employee->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">رقم الموظف:</span>
                            <p class="font-semibold text-gray-900">{{ $employee->id }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-600">المنصب:</span>
                            <p class="font-semibold text-gray-900">{{ $employee->position }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">القسم:</span>
                            <p class="font-semibold text-gray-900">{{ $employee->department }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-600">الهوية الوطنية:</span>
                            <p class="font-semibold text-gray-900">{{ $employee->national_id ?? 'غير محدد' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">تاريخ التوظيف:</span>
                            <p class="font-semibold text-gray-900">{{ $employee->hire_date ? $employee->hire_date->format('Y/m/d') : 'غير محدد' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول التقارير السرية -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-red-600 text-white px-6 py-4">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <i class="ri-file-lock-line"></i>
                    التقارير السرية ({{ $reports->count() }} تقرير)
                </h3>
            </div>

            @if($reports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 border-b">#</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 border-b">محتوى التقرير</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 border-b">كاتب التقرير</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 border-b">التاريخ والوقت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $index => $report)
                                <tr class="report-card {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-4 py-4 text-sm text-gray-900 border-b">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900 border-b leading-relaxed">
                                        <div class="max-w-md">
                                            {{ $report->report_content }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 border-b">
                                        <div class="font-medium">{{ $report->reporter->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $report->reporter->email }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 border-b">
                                        <div class="font-medium">{{ $report->created_at->format('Y/m/d') }}</div>
                                        <div class="text-xs text-gray-500">{{ $report->created_at->format('H:i:s') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-gray-500">
                    <i class="ri-file-text-line text-4xl mb-2"></i>
                    <p>لا توجد تقارير سرية لهذا الموظف</p>
                </div>
            @endif
        </div>

        <!-- تذييل التقرير -->
        <div class="mt-8 pt-6 border-t border-gray-300">
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div class="text-right">
                    <p class="font-semibold mb-2">توقيع المسؤول:</p>
                    <div class="border-b border-gray-400 mt-4 mb-2 h-8"></div>
                    <p>الاسم: ________________</p>
                    <p class="text-xs text-gray-500 mt-1">التاريخ: ________________</p>
                </div>

                <div class="text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 border-2 border-gray-300 rounded mb-2 flex items-center justify-center">
                            <span class="text-xs text-gray-500">ختم الشركة</span>
                        </div>
                        <p class="font-semibold text-xs">ختم وتوقيع الإدارة</p>
                    </div>
                </div>

                <div class="text-left">
                    <p class="font-semibold mb-2">ملاحظات إضافية:</p>
                    <div class="border border-gray-300 h-16 p-2">
                        <div class="text-xs text-gray-400">
                            _________________________<br>
                            _________________________<br>
                            _________________________
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                <p class="text-xs text-gray-500">
                    <span class="font-semibold text-red-600">سري للغاية</span> - هذا التقرير سري ومخصص للاستخدام الداخلي فقط
                    <br>
                    شركة الأبراج للمقاولات العامة | المملكة العربية السعودية | تاريخ الإصدار: {{ now()->format('Y/m/d H:i') }}
                </p>
            </div>
        </div>
    </div>

    <script>
        // طباعة تلقائية عند فتح الصفحة (اختياري)
        // window.onload = function() {
        //     window.print();
        // }

        // تحسين الطباعة
        window.addEventListener('beforeprint', function() {
            document.body.style.background = 'white';
        });
    </script>
</body>
</html>
