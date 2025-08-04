@extends('layouts.app')

@section('title', 'مستخلص مشروع - ' . $project->name)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">مستخلص مشروع</h1>
                    <p class="text-gray-600">{{ $project->name }}</p>
                    <p class="text-sm text-gray-500">رقم المشروع: {{ $project->project_number ?? 'غير محدد' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-printer-line"></i>
                        طباعة
                    </button>
                    <button onclick="window.close()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-close-line"></i>
                        إغلاق
                    </button>
                </div>
            </div>

            <!-- Project Info Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">العميل:</span>
                    <span class="text-gray-900">{{ $project->client_name ?? 'غير محدد' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">مدير المشروع:</span>
                    <span
                        class="text-gray-900">{{ $project->projectManager->name ?? ($project->project_manager ?? 'غير محدد') }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">تاريخ المستخلص:</span>
                    <span class="text-gray-900">{{ now()->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Extract Table -->
        @if ($project->projectItems && $project->projectItems->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">جدول المستخلص</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right border">م</th>
                                <th class="px-4 py-3 text-right border">اسم البند</th>
                                <th class="px-4 py-3 text-center border">الوحدة</th>
                                <th class="px-4 py-3 text-center border">الكمية الإجمالية</th>
                                <th class="px-4 py-3 text-center border">السعر الإفرادي</th>
                                <th class="px-4 py-3 text-center border">السعر الإجمالي</th>
                                <th class="px-4 py-3 text-center border">كمية المستخلصات السابقة</th>
                                <th class="px-4 py-3 text-center border">قيمة ما تم صرفه</th>
                                <th class="px-4 py-3 text-center border">كمية المستخلص الحالي</th>
                                <th class="px-4 py-3 text-center border">قيمة المستخلص الحالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalProjectValue = 0;
                                $totalPreviousPaid = 0;
                                $totalCurrentExtract = 0;
                            @endphp
                            @foreach ($project->projectItems as $index => $item)
                                @php
                                    // هذه القيم يمكن أن تأتي من قاعدة بيانات المستخلصات السابقة
                                    // للتوضيح، سأضع قيم افتراضية
                                    $previousQuantity = $item->quantity * 0.6; // 60% من الكمية منفذة سابقاً
                                    $previousPaidValue = $previousQuantity * $item->unit_price;
                                    $currentQuantity = $item->quantity * 0.3; // 30% من الكمية في المستخلص الحالي
                                    $currentExtractValue = $currentQuantity * $item->unit_price;

                                    $totalProjectValue += $item->total_price;
                                    $totalPreviousPaid += $previousPaidValue;
                                    $totalCurrentExtract += $currentExtractValue;
                                @endphp
                                <tr class="border-b">
                                    <td class="px-4 py-3 border text-center">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 border">{{ $item->name }}</td>
                                    <td class="px-4 py-3 border text-center">{{ $item->unit }}</td>
                                    <td class="px-4 py-3 border text-center">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="px-4 py-3 border text-center">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-3 border text-center">{{ number_format($item->total_price, 2) }}
                                    </td>
                                    <td class="px-4 py-3 border text-center bg-gray-50">
                                        {{ number_format($previousQuantity, 2) }}</td>
                                    <td class="px-4 py-3 border text-center bg-gray-50">
                                        {{ number_format($previousPaidValue, 2) }}</td>
                                    <td class="px-4 py-3 border text-center bg-blue-50">
                                        <input type="number" step="0.01"
                                            value="{{ number_format($currentQuantity, 2) }}"
                                            class="w-full text-center border-0 bg-transparent focus:ring-0 current-quantity"
                                            data-unit-price="{{ $item->unit_price }}" data-row="{{ $index }}"
                                            onchange="calculateCurrentValue(this)">
                                    </td>
                                    <td class="px-4 py-3 border text-center bg-blue-50">
                                        <span class="current-value"
                                            data-row="{{ $index }}">{{ number_format($currentExtractValue, 2) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <!-- Totals Row -->
                        <tfoot class="bg-gray-100 font-bold">
                            <tr>
                                <td colspan="5" class="px-4 py-3 border text-center">الإجمالي</td>
                                <td class="px-4 py-3 border text-center">{{ number_format($totalProjectValue, 2) }}</td>
                                <td class="px-4 py-3 border text-center">-</td>
                                <td class="px-4 py-3 border text-center" id="total-previous-paid">
                                    {{ number_format($totalPreviousPaid, 2) }}</td>
                                <td class="px-4 py-3 border text-center">-</td>
                                <td class="px-4 py-3 border text-center" id="total-current-extract">
                                    {{ number_format($totalCurrentExtract, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Summary Section -->
                <div class="p-6 bg-gray-50 border-t">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: Financial Summary -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">ملخص مالي</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-700">إجمالي قيمة المشروع:</span>
                                    <span class="font-medium">{{ number_format($project->budget, 2) }} ر.س</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-700">قيمة ما تم صرفه سابقاً:</span>
                                    <span class="font-medium"
                                        id="summary-previous-paid">{{ number_format($totalPreviousPaid, 2) }} ر.س</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-gray-900 font-semibold">قيمة المستخلص الحالي:</span>
                                    <span class="font-bold text-blue-600"
                                        id="summary-current-extract">{{ number_format($totalCurrentExtract, 2) }}
                                        ر.س</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-700">المتبقي من المشروع:</span>
                                    <span class="font-medium"
                                        id="summary-remaining">{{ number_format($project->budget - $totalPreviousPaid - $totalCurrentExtract, 2) }}
                                        ر.س</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Progress Summary -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">نسبة الإنجاز</h3>
                            <div class="space-y-3">
                                @php
                                    $completedPercentage = ($totalPreviousPaid / $project->budget) * 100;
                                    $currentPercentage = ($totalCurrentExtract / $project->budget) * 100;
                                    $totalPercentage = $completedPercentage + $currentPercentage;
                                @endphp

                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm text-gray-700">نسبة الإنجاز السابقة</span>
                                        <span
                                            class="text-sm font-medium">{{ number_format($completedPercentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gray-400 h-2 rounded-full"
                                            style="width: {{ $completedPercentage }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm text-gray-700">نسبة المستخلص الحالي</span>
                                        <span
                                            class="text-sm font-medium">{{ number_format($currentPercentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full"
                                            style="width: {{ $currentPercentage }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-semibold text-gray-900">إجمالي نسبة الإنجاز</span>
                                        <span class="text-sm font-bold">{{ number_format($totalPercentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-green-500 h-3 rounded-full"
                                            style="width: {{ min($totalPercentage, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount in Words -->
                    <div class="mt-6 p-4 bg-white rounded-lg border">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">قيمة المستخلص الحالي بالحروف:</h4>
                        <p class="text-gray-800" id="amount-in-words">{{ numberToArabicWords($totalCurrentExtract) }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="text-center text-gray-500">
                    <i class="ri-file-list-3-line text-4xl mb-4"></i>
                    <p>لا توجد بنود لهذا المشروع</p>
                </div>
            </div>
        @endif
    </div>

    <script>
        // حساب قيمة المستخلص الحالي عند تغيير الكمية
        function calculateCurrentValue(input) {
            const row = input.dataset.row;
            const unitPrice = parseFloat(input.dataset.unitPrice);
            const quantity = parseFloat(input.value) || 0;
            const currentValue = quantity * unitPrice;

            // تحديث قيمة المستخلص الحالي للصف
            document.querySelector(`[data-row="${row}"].current-value`).textContent = currentValue.toFixed(2);

            // إعادة حساب الإجماليات
            calculateTotals();
        }

        function calculateTotals() {
            let totalCurrentExtract = 0;

            // حساب إجمالي المستخلص الحالي
            document.querySelectorAll('.current-value').forEach(element => {
                totalCurrentExtract += parseFloat(element.textContent) || 0;
            });

            // تحديث الإجماليات في الجدول
            document.getElementById('total-current-extract').textContent = totalCurrentExtract.toFixed(2);
            document.getElementById('summary-current-extract').textContent = totalCurrentExtract.toFixed(2) + ' ر.س';

            // حساب المتبقي
            const projectBudget = {{ $project->budget }};
            const totalPreviousPaid = parseFloat(document.getElementById('total-previous-paid').textContent) || 0;
            const remaining = projectBudget - totalPreviousPaid - totalCurrentExtract;

            document.getElementById('summary-remaining').textContent = remaining.toFixed(2) + ' ر.س';

            // تحديث المبلغ بالحروف
            document.getElementById('amount-in-words').textContent = numberToArabicWords(totalCurrentExtract);
        }

        // دالة تحويل الأرقام إلى كلمات عربية
        function numberToArabicWords(number) {
            if (number === 0) return 'صفر ريال سعودي';

            const ones = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة'];
            const tens = ['', '', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];
            const teens = ['عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر', 'سبعة عشر',
                'ثمانية عشر', 'تسعة عشر'
            ];
            const hundreds = ['', 'مائة', 'مائتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة',
                'تسعمائة'
            ];
            const scales = ['', 'ألف', 'مليون', 'مليار'];

            function convertGroup(num) {
                let result = '';
                const h = Math.floor(num / 100);
                const t = Math.floor((num % 100) / 10);
                const o = num % 10;

                if (h > 0) {
                    result += hundreds[h];
                    if (t > 0 || o > 0) result += ' ';
                }

                if (t === 1) {
                    result += teens[o];
                } else {
                    if (t > 0) {
                        result += tens[t];
                        if (o > 0) result += ' ';
                    }
                    if (o > 0 && t !== 1) {
                        result += ones[o];
                    }
                }

                return result;
            }

            const integerPart = Math.floor(number);
            const decimalPart = Math.round((number - integerPart) * 100);

            let result = '';
            let scaleIndex = 0;
            let tempNum = integerPart;

            if (tempNum === 0) {
                result = 'صفر';
            } else {
                const groups = [];
                while (tempNum > 0) {
                    groups.push(tempNum % 1000);
                    tempNum = Math.floor(tempNum / 1000);
                }

                for (let i = groups.length - 1; i >= 0; i--) {
                    if (groups[i] > 0) {
                        if (result) result += ' ';
                        result += convertGroup(groups[i]);
                        if (i > 0) {
                            result += ' ' + scales[i];
                        }
                    }
                }
            }

            result += ' ريال سعودي';

            if (decimalPart > 0) {
                result += ' و ' + convertGroup(decimalPart) + ' هللة';
            }

            return result;
        }

        // Print styles
        const printStyles = `
        @media print {
            body { font-size: 12px !important; }
            .p-6 { padding: 1rem !important; }
            .no-print { display: none !important; }
            table { page-break-inside: avoid; }
            tr { page-break-inside: avoid; }
            th, td { padding: 4px !important; font-size: 11px !important; }
            .bg-white { background-color: white !important; }
            .border { border: 1px solid #000 !important; }
            button { display: none !important; }
        }`;

        const styleSheet = document.createElement("style");
        styleSheet.type = "text/css";
        styleSheet.innerText = printStyles;
        document.head.appendChild(styleSheet);
    </script>
@endsection

@php
    function numberToArabicWords($number)
    {
        if ($number === 0) {
            return 'صفر ريال سعودي';
        }

        $ones = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة'];
        $tens = ['', '', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];
        $teens = [
            'عشرة',
            'أحد عشر',
            'اثنا عشر',
            'ثلاثة عشر',
            'أربعة عشر',
            'خمسة عشر',
            'ستة عشر',
            'سبعة عشر',
            'ثمانية عشر',
            'تسعة عشر',
        ];
        $hundreds = [
            '',
            'مائة',
            'مائتان',
            'ثلاثمائة',
            'أربعمائة',
            'خمسمائة',
            'ستمائة',
            'سبعمائة',
            'ثمانمائة',
            'تسعمائة',
        ];
        $scales = ['', 'ألف', 'مليون', 'مليار'];

        $integerPart = intval($number);
        $decimalPart = round(($number - $integerPart) * 100);

        $result = '';
        $tempNum = $integerPart;

        if ($tempNum === 0) {
            $result = 'صفر';
        } else {
            $groups = [];
            while ($tempNum > 0) {
                $groups[] = $tempNum % 1000;
                $tempNum = intval($tempNum / 1000);
            }

            for ($i = count($groups) - 1; $i >= 0; $i--) {
                if ($groups[$i] > 0) {
                    if ($result) {
                        $result .= ' ';
                    }

                    $num = $groups[$i];
                    $h = intval($num / 100);
                    $t = intval(($num % 100) / 10);
                    $o = $num % 10;

                    if ($h > 0) {
                        $result .= $hundreds[$h];
                        if ($t > 0 || $o > 0) {
                            $result .= ' ';
                        }
                    }

                    if ($t === 1) {
                        $result .= $teens[$o];
                    } else {
                        if ($t > 0) {
                            $result .= $tens[$t];
                            if ($o > 0) {
                                $result .= ' ';
                            }
                        }
                        if ($o > 0 && $t !== 1) {
                            $result .= $ones[$o];
                        }
                    }

                    if ($i > 0) {
                        $result .= ' ' . $scales[$i];
                    }
                }
            }
        }

        $result .= ' ريال سعودي';

        if ($decimalPart > 0) {
            $result .= ' و ' . $decimalPart . ' هللة';
        }

        return $result;
    }
@endphp
