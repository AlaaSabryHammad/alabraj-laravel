<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'شركة الأبراج للمقاولات - نظام الإدارة')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Enhanced Project Edit Styles -->
    <link href="{{ asset('css/project-edit-enhanced.css') }}" rel="stylesheet">

    <!-- Unified Settings Styles -->
    <link href="{{ asset('css/unified-settings.css') }}" rel="stylesheet">

    <!-- Custom CSS for RTL -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');

        body {
            font-family: 'Tajawal', sans-serif;
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sidebar-item {
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            transform: translateX(-5px);
        }

        .chart-container {
            direction: ltr;
        }

        /* منع تمدد المحتوى تحت القائمة الجانبية */
        body {
            display: flex;
            flex-direction: row-reverse;
        }

        .main-content-wrapper {
            flex: 1;
            overflow-x: auto;
            padding: 1.5rem;
        }

        /* منع تمدد الجداول */
        table {
            table-layout: auto;
            width: 100%;
        }

        /* ضمان عدم تجاوز الحاويات */
        div[class*="overflow-x-auto"] {
            max-width: 100%;
            min-width: 0;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .main-content-wrapper {
                margin-right: 0;
                margin-bottom: 0;
            }
        }

        /* Print Styles - تحسينات شاملة للطباعة */
        @media print {

            /* إعدادات الصفحة */
            @page {
                size: A4;
                margin: 15mm 10mm;
                color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            /* إخفاء القائمة الجانبية والعناصر غير الضرورية */
            .fixed.right-0.top-0.h-full.w-64,
            nav,
            .sidebar,
            [class*="sidebar"],
            .no-print,
            button:not(.print-include),
            .bg-gradient-to-br {
                display: none !important;
            }

            /* إعدادات الخط والنصوص */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                font-family: 'Tajawal', 'Arial', sans-serif !important;
                font-size: 11pt !important;
                line-height: 1.4 !important;
                color: #000 !important;
                background: white !important;
                background-image: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* تعديل التخطيط الرئيسي */
            .mr-64 {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }

            .flex-1.mr-64 {
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .max-w-7xl {
                max-width: 100% !important;
                margin: 0 !important;
            }

            .mx-auto {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* تحسين المساحات والهوامش */
            .p-6 {
                padding: 8pt !important;
            }

            .p-4 {
                padding: 6pt !important;
            }

            .p-3 {
                padding: 4pt !important;
            }

            .mb-6 {
                margin-bottom: 12pt !important;
            }

            .mb-4 {
                margin-bottom: 8pt !important;
            }

            .mb-3 {
                margin-bottom: 6pt !important;
            }

            .mb-2 {
                margin-bottom: 4pt !important;
            }

            /* إزالة الحدود المدورة والظلال */
            .rounded-xl,
            .rounded-lg,
            .rounded {
                border-radius: 0 !important;
            }

            .shadow-sm,
            .shadow,
            .shadow-lg {
                box-shadow: none !important;
            }

            /* تحسين الخلفيات */
            .bg-white,
            .bg-gray-50,
            .bg-gray-100 {
                background: white !important;
                border: 1px solid #ddd !important;
            }

            /* تحسين ألوان النصوص */
            .text-gray-900,
            .text-gray-800,
            .text-gray-700 {
                color: #000 !important;
                font-weight: normal !important;
            }

            .text-gray-600,
            .text-gray-500 {
                color: #333 !important;
            }

            .text-blue-600 {
                color: #1e40af !important;
                font-weight: 600 !important;
            }

            .text-green-600 {
                color: #059669 !important;
            }

            .text-red-600 {
                color: #dc2626 !important;
            }

            .text-purple-600 {
                color: #7c3aed !important;
            }

            .text-orange-600 {
                color: #ea580c !important;
            }

            /* تحسين العناوين */
            h1,
            .text-3xl {
                font-size: 18pt !important;
                font-weight: bold !important;
                color: #000 !important;
                margin-bottom: 8pt !important;
                page-break-after: avoid;
            }

            h2,
            .text-2xl {
                font-size: 16pt !important;
                font-weight: bold !important;
                color: #000 !important;
                margin-bottom: 6pt !important;
                page-break-after: avoid;
            }

            h3,
            .text-lg {
                font-size: 14pt !important;
                font-weight: 600 !important;
                color: #000 !important;
                margin-bottom: 4pt !important;
                page-break-after: avoid;
            }

            h4,
            .text-base {
                font-size: 12pt !important;
                font-weight: 600 !important;
                color: #000 !important;
            }

            .text-sm {
                font-size: 10pt !important;
            }

            .text-xs {
                font-size: 9pt !important;
            }

            /* تحسين الحدود */
            .border,
            .border-gray-200,
            .border-gray-300 {
                border: 1px solid #ccc !important;
            }

            .border-b {
                border-bottom: 1px solid #ccc !important;
            }

            .border-t {
                border-top: 1px solid #ccc !important;
            }

            /* تحسين الجداول والشبكات */
            .grid {
                display: block !important;
            }

            .grid-cols-1,
            .grid-cols-2,
            .grid-cols-3 {
                display: block !important;
            }

            .grid>div {
                display: block !important;
                margin-bottom: 6pt !important;
                page-break-inside: avoid;
            }

            /* تحسين الصور */
            img {
                max-width: 100% !important;
                height: auto !important;
                page-break-inside: avoid;
                border: 1px solid #ddd !important;
            }

            .w-24,
            .h-24 {
                width: 60pt !important;
                height: 60pt !important;
            }

            .w-32,
            .h-32 {
                width: 80pt !important;
                height: 80pt !important;
            }

            /* تحسين الشارات والعلامات */
            .bg-green-100 {
                background: #f0f9ff !important;
                border: 1px solid #059669 !important;
                color: #059669 !important;
                padding: 2pt 4pt !important;
            }

            .bg-red-100 {
                background: #fef2f2 !important;
                border: 1px solid #dc2626 !important;
                color: #dc2626 !important;
                padding: 2pt 4pt !important;
            }

            .bg-orange-100 {
                background: #fff7ed !important;
                border: 1px solid #ea580c !important;
                color: #ea580c !important;
                padding: 2pt 4pt !important;
            }

            /* منع تقطيع المحتوى */
            .page-break-avoid {
                page-break-inside: avoid;
            }

            .page-break-before {
                page-break-before: always;
            }

            .page-break-after {
                page-break-after: always;
            }

            /* تحسين المساحات بين العناصر */
            .space-y-6>*+* {
                margin-top: 12pt !important;
            }

            .space-y-4>*+* {
                margin-top: 8pt !important;
            }

            .space-y-2>*+* {
                margin-top: 4pt !important;
            }

            .gap-6 {
                gap: 12pt !important;
            }

            .gap-4 {
                gap: 8pt !important;
            }

            .gap-3 {
                gap: 6pt !important;
            }

            /* إظهار عناصر الطباعة فقط */
            .print-only {
                display: block !important;
            }

            /* تحسين النماذج */
            input,
            select,
            textarea {
                border: 1px solid #ccc !important;
                background: white !important;
                padding: 2pt !important;
                font-size: 10pt !important;
            }

            /* تحسين الأزرار المطبوعة */
            .print-include {
                background: #f5f5f5 !important;
                border: 1px solid #ccc !important;
                color: #000 !important;
                padding: 4pt 8pt !important;
                font-size: 10pt !important;
            }

            /* تحسين الفواصل */
            hr {
                border: none !important;
                border-top: 1px solid #ccc !important;
                margin: 8pt 0 !important;
            }

            /* إخفاء روابط التنقل */
            a[href^="http"]:after {
                content: none !important;
            }

            /* تحسين العرض للشاشات الصغيرة في الطباعة */
            .lg\:grid-cols-2,
            .lg\:grid-cols-3,
            .md\:grid-cols-2,
            .md\:grid-cols-3 {
                display: block !important;
            }

            /* تحسين التباعد */
            .flex {
                display: block !important;
            }

            .flex-1 {
                width: 100% !important;
            }

            /* رأس وتذييل احترافي */
            .print-header {
                border-bottom: 2px solid #1e40af !important;
                margin-bottom: 12pt !important;
                padding-bottom: 8pt !important;
            }

            .print-footer {
                border-top: 1px solid #ccc !important;
                margin-top: 12pt !important;
                padding-top: 8pt !important;
                text-align: center !important;
                font-size: 9pt !important;
                color: #666 !important;
            }

            /* CSS خاص لطباعة بطاقة الموظف */
            .employee-card {
                display: block !important;
                visibility: visible !important;
                background: white !important;
                border: 2px solid #000 !important;
                margin: 0 !important;
                padding: 0 !important;
                page-break-inside: avoid !important;
            }

            .employee-section {
                display: block !important;
                visibility: visible !important;
                padding: 10pt !important;
                border-bottom: 1px solid #ccc !important;
                page-break-inside: avoid !important;
            }

            .employee-documents {
                display: grid !important;
                grid-template-columns: 1fr 1fr 1fr !important;
                gap: 8pt !important;
                visibility: visible !important;
            }

            .document-card {
                display: block !important;
                visibility: visible !important;
                background: #f8f9fa !important;
                border: 1px solid #000 !important;
                padding: 6pt !important;
            }

            /* تأكيد ظهور جميع العناصر */
            body.print-mode * {
                display: block !important;
                visibility: visible !important;
            }

            body.print-mode .no-print {
                display: none !important;
            }

            body.print-mode .print-only {
                display: block !important;
            }
        }
    </style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('styles')
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50 rtl" dir="rtl">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content with Sidebar Space -->
    <div class="main-content-wrapper" style="min-height: 100vh;">
        <div style="width: 100%;">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>

</html>
