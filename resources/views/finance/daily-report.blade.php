@extends('layouts.app')

@section('title', 'التقرير اليومي')

@push('styles')
    <style>
        @media print {

            /* Basic Print Settings */
            @page {
                size: A4;
                margin: 1cm;
            }

            body {
                print-color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Hide Non-Printable Elements */
            .no-print,
            .print-hidden {
                display: none !important;
            }

            /* Show Print-Only Elements */
            .print-only,
            .print\:block {
                display: block !important;
            }

            /* Container Modifications */
            .print-container {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Table Modifications */
            table {
                page-break-inside: auto !important;
                border-collapse: collapse !important;
            }

            tr {
                page-break-inside: avoid !important;
            }

            thead {
                display: table-header-group !important;
            }

            /* Border and Color Fixes */
            .print-border {
                border: 1px solid #000 !important;
            }

            .print-text-black {
                color: #000 !important;
            }

            /* Backgrounds and Colors */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Fix for Background Colors */
            .bg-gray-50,
            .bg-gray-100,
            .bg-white {
                background-color: white !important;
                background: white !important;
            }

            /* Text Colors for Print */
            .text-green-600,
            .text-red-600,
            .text-blue-600 {
                color: black !important;
            }

            /* Spacing Fixes */
            .print-mb {
                margin-bottom: 1rem !important;
            }

            /* Report Specific Styles */
            .daily-report-header {
                text-align: center !important;
                margin-bottom: 2rem !important;
                padding-bottom: 1rem !important;
                border-bottom: 2px solid #000 !important;
            }

            .daily-report-summary {
                margin: 2rem 0 !important;
                border: 1px solid #000 !important;
                page-break-inside: avoid !important;
            }

            .daily-report-summary td {
                padding: 1rem !important;
                border: 1px solid #000 !important;
                text-align: center !important;
            }

            .daily-report-table {
                width: 100% !important;
                margin: 2rem 0 !important;
                border-collapse: collapse !important;
                border: 1px solid #000 !important;
            }

            .daily-report-table th,
            .daily-report-table td {
                padding: 0.5rem !important;
                border: 1px solid #000 !important;
                text-align: right !important;
            }

            .daily-report-table thead th {
                background-color: #f3f4f6 !important;
                font-weight: bold !important;
            }

            .daily-report-footer {
                margin-top: 2rem !important;
                padding-top: 1rem !important;
                border-top: 2px solid #000 !important;
                page-break-inside: avoid !important;
            }

            /* Remove unnecessary visual elements */
            .shadow-sm,
            .shadow,
            .shadow-md,
            .shadow-lg {
                box-shadow: none !important;
            }

            .rounded,
            .rounded-lg,
            .rounded-xl {
                border-radius: 0 !important;
            }

            /* Ensure visibility of content */
            .print-content {
                display: block !important;
                page-break-inside: avoid !important;
                visibility: visible !important;
            }

            /* Image specific print styles */
            .print-image {
                display: block !important;
                visibility: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                filter: brightness(100%) contrast(100%) !important;
            }

            /* Grid to Table conversion for better print layout */
            .grid {
                display: table !important;
                width: 100% !important;
            }

            .grid>div {
                display: table-cell !important;
                padding: 1rem !important;
            }
        }

        /* Print Preview Styles */
        .print-preview {
            background: white;
            padding: 1rem;
            min-height: 29.7cm;
            width: 100%;
            max-width: 100%;
            margin: 0;
        }
    </style>
@endpush

@push('scripts')
    @if (request()->has('print'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add print mode class to body
                document.body.classList.add('print-mode');

                // Remove sidebar margin
                document.querySelector('.flex-1').classList.remove('mr-64');

                // Wait for images to load
                Promise.all(Array.from(document.images).map(img => {
                    if (img.complete) return Promise.resolve(img.naturalHeight !== 0);
                    return new Promise(resolve => {
                        img.addEventListener('load', () => resolve(true));
                        img.addEventListener('error', () => resolve(false));
                    });
                })).then(() => {
                    // Additional delay to ensure proper rendering
                    setTimeout(function() {
                        window.print();
                    }, 1000);
                });
            });

            // Reset after print
            window.onafterprint = function() {
                document.body.classList.remove('print-mode');
                document.querySelector('.flex-1').classList.add('mr-64');
            };
        </script>
    @endif
@endpush

@section('content')
    <div class="print-preview p-4 print-container" dir="rtl">
        <!-- Print Header -->
        <div class="daily-report-header print-only">
            <div class="print-content border-b pb-4">
                <img src="{{ asset('assets/logo.png') }}" alt="شعار الشركة" class="h-20 mx-auto mb-4 print-image">
                <h1 class="text-2xl font-bold text-gray-900 print-text-black">شركة الأبراج للمقاولات المحدودة</h1>
                <p class="text-lg print-text-black mt-2">التقرير اليومي للمعاملات المالية</p>
                <div class="mt-4 text-sm">
                    <p class="print-text-black">تاريخ التقرير: {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</p>
                    <p class="print-text-black">تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Screen Header (non-printable) -->
        <div class="no-print flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">التقرير اليومي للمعاملات المالية</h1>
                <p class="text-gray-600 mt-1">شركة الأبراج للمقاولات المحدودة - تقرير شامل بالرصيد المرحل والحركة اليومية
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('finance.daily-report', ['date' => $date, 'print' => true]) }}" target="_blank"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-printer-line"></i>
                    طباعة التقرير
                </a>
                <a href="{{ route('finance.all-transactions') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-arrow-left-line"></i>
                    العودة
                </a>
            </div>
        </div>

        <!-- اختيار التاريخ (non-printable) -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6 no-print">
            <form method="GET" action="{{ route('finance.daily-report') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ التقرير</label>
                    <input type="date" name="date" value="{{ $date }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-refresh-line"></i>
                    تحديث التقرير
                </button>
            </form>
        </div>

        <!-- ملخص الأرصدة -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 print:grid-cols-2 print:gap-4">
            <div class="bg-white rounded-xl shadow-sm p-6 border print:border print:shadow-none print-mb">
                <div class="text-center">
                    <div
                        class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3 print:bg-gray-100">
                        <i class="ri-history-line text-xl text-blue-600 print:text-gray-600"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الرصيد المرحل</p>
                    <p
                        class="text-xl font-bold {{ $carriedBalance >= 0 ? 'text-blue-600' : 'text-red-600' }} print:text-black">
                        {{ number_format($carriedBalance, 2) }} ر.س
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border print:border print:shadow-none print-mb">
                <div class="text-center">
                    <div
                        class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3 print:bg-gray-100">
                        <i class="ri-arrow-up-line text-xl text-green-600 print:text-gray-600"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إيرادات اليوم</p>
                    <p class="text-xl font-bold text-green-600 print:text-black">{{ number_format($dayRevenue, 2) }} ر.س</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border print:border print:shadow-none print-mb">
                <div class="text-center">
                    <div
                        class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-3 print:bg-gray-100">
                        <i class="ri-arrow-down-line text-xl text-red-600 print:text-gray-600"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">مصروفات اليوم</p>
                    <p class="text-xl font-bold text-red-600 print:text-black">{{ number_format($dayExpense, 2) }} ر.س</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border print:border print:shadow-none print-mb">
                <div class="text-center">
                    <div
                        class="h-12 w-12 {{ $finalBalance >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center mx-auto mb-3 print:bg-gray-100">
                        <i
                            class="ri-wallet-line text-xl {{ $finalBalance >= 0 ? 'text-green-600' : 'text-red-600' }} print:text-gray-600"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الرصيد النهائي</p>
                    <p
                        class="text-xl font-bold {{ $finalBalance >= 0 ? 'text-green-600' : 'text-red-600' }} print:text-black">
                        {{ number_format($finalBalance, 2) }} ر.س
                    </p>
                </div>
            </div>
        </div>

        <!-- معادلة الرصيد -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">معادلة حساب الرصيد</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-center text-lg">
                    <span class="text-blue-600 font-bold">{{ number_format($carriedBalance, 2) }}</span>
                    <span class="text-gray-600 mx-2">+</span>
                    <span class="text-green-600 font-bold">{{ number_format($dayRevenue, 2) }}</span>
                    <span class="text-gray-600 mx-2">-</span>
                    <span class="text-red-600 font-bold">{{ number_format($dayExpense, 2) }}</span>
                    <span class="text-gray-600 mx-2">=</span>
                    <span class="text-gray-900 font-bold">{{ number_format($finalBalance, 2) }} ر.س</span>
                </div>
                <div class="text-center text-sm text-gray-600 mt-2">
                    الرصيد المرحل + إيرادات اليوم - مصروفات اليوم = الرصيد النهائي
                </div>
            </div>
        </div>

        <!-- تفاصيل معاملات اليوم -->
        <div class="bg-white rounded-xl shadow-sm border print:border print:shadow-none">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">تفاصيل معاملات يوم
                    {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</h3>
            </div>

            @if (count($transactions) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full print:text-sm">
                        <thead class="bg-gray-50 print:bg-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider print:text-black print:font-bold">
                                    رقم السند
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider print:text-black print:font-bold">
                                    النوع
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider print:text-black print:font-bold">
                                    الوصف
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider print:text-black print:font-bold">
                                    المبلغ
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider print:text-black print:font-bold">
                                    الحالة
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transactions as $transaction)
                                <tr class="print:border-b">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction['number'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium print:font-bold
                                            @if ($transaction['type'] == 'سند قبض') bg-green-100 text-green-800 print:bg-white print:text-black
                                            @elseif($transaction['type'] == 'سند صرف') bg-red-100 text-red-800 print:bg-white print:text-black
                                            @else bg-blue-100 text-blue-800 print:bg-white print:text-black @endif">
                                            {{ $transaction['type'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $transaction['description'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium {{ $transaction['is_income'] ? 'text-green-600' : 'text-red-600' }} print:text-black">
                                            {{ $transaction['is_income'] ? '+' : '-' }}{{ number_format($transaction['amount'], 2) }}
                                            ر.س
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 print:bg-white print:text-black">
                                            {{ $transaction['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ملخص المجاميع -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 print:bg-white print:border-t-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm print:grid-cols-3">
                        <div class="flex justify-between">
                            <span class="font-medium">مجموع الإيرادات:</span>
                            <span class="text-green-600 font-bold print:text-black">+{{ number_format($dayRevenue, 2) }}
                                ر.س</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">مجموع المصروفات:</span>
                            <span class="text-red-600 font-bold print:text-black">-{{ number_format($dayExpense, 2) }}
                                ر.س</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">صافي الحركة:</span>
                            <span
                                class="font-bold {{ $dayNet >= 0 ? 'text-green-600' : 'text-red-600' }} print:text-black">
                                {{ $dayNet >= 0 ? '+' : '' }}{{ number_format($dayNet, 2) }} ر.س
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="ri-calendar-line text-4xl text-gray-300 mb-4 print:hidden"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد معاملات في هذا اليوم</h3>
                    <p class="text-gray-600">لم يتم تسجيل أي معاملات مالية في تاريخ
                        {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</p>
                </div>
            @endif

            <!-- Print Footer -->
            <div class="hidden print:block border-t-2 mt-8 pt-8 text-sm">
                <div class="grid grid-cols-3 gap-8 text-center">
                    <div>
                        <p class="font-bold mb-16">المدير المالي</p>
                        <div class="border-t border-gray-400 w-48 mx-auto"></div>
                    </div>
                    <div>
                        <p class="font-bold mb-16">المحاسب</p>
                        <div class="border-t border-gray-400 w-48 mx-auto"></div>
                    </div>
                    <div>
                        <p class="font-bold mb-16">أمين الصندوق</p>
                        <div class="border-t border-gray-400 w-48 mx-auto"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات التقرير -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <strong>تاريخ التقرير:</strong> {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}
                </div>
                <div>
                    <strong>تاريخ الطباعة:</strong> {{ now()->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 12px;
            }

            .bg-white {
                background: white !important;
            }
        }
    </style>
@endsection
