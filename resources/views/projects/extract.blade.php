@extends('layouts.app')

@section('title', 'مستخلص مشروع - ' . $project->name)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- عرض رسائل النجاح والخطأ -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Modal إضافة البنود -->
        <div id="addItemsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" style="z-index: 1000;">
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl" dir="rtl">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900">إضافة بنود للمشروع</h3>
                            <button type="button" onclick="closeAddItemsModal()" class="text-gray-400 hover:text-gray-500">
                                <i class="ri-close-line text-2xl"></i>
                            </button>
                        </div>
                    </div>

                    <form id="addItemsForm" action="{{ route('projects.items.store', $project) }}" method="POST"
                        class="p-6">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">معدل الضريبة (%)</label>
                            <input type="number" id="modal_tax_rate" name="tax_rate" value="15" step="0.1"
                                min="0" max="100"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div id="itemsContainer" class="space-y-4" style="max-height:45vh; overflow-y:auto;">
                            <!-- نموذج البند الأول -->
                            <div class="item-row space-y-4 pb-6 mb-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-medium text-gray-700">البند <span class="item-number">1</span></h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">اسم البند</label>
                                        <input type="text" name="items[0][name]" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">وحدة القياس</label>
                                        <select name="items[0][unit]" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">اختر وحدة القياس</option>
                                            <option value="م3">م3</option>
                                            <option value="م2">م2</option>
                                            <option value="طن">طن</option>
                                            <option value="لتر">لتر</option>
                                            <option value="عدد">عدد</option>
                                            <option value="م">م</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">الكمية</label>
                                        <input type="number" name="items[0][quantity]" step="0.01" min="0"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            oninput="calculateTotal(this)">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">السعر الإفرادي</label>
                                        <input type="number" name="items[0][unit_price]" step="0.01" min="0"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            oninput="calculateTotal(this)">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">السعر الإجمالي</label>
                                    <input type="number" name="items[0][total_price]" readonly
                                        class="w-full rounded-lg bg-gray-50 border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التكلفة بالضريبة</label>
                                    <input type="number" name="items[0][total_with_tax]" readonly
                                        class="w-full rounded-lg bg-gray-50 border-gray-300">
                                </div>
                            </div><!-- /item-row -->
                        </div>

                        <div class="flex items-center gap-4 mb-6">
                            <button type="button" id="addNewItemBtn"
                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700">
                                <i class="ri-add-line"></i>
                                إضافة بند جديد
                            </button>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                            <button type="button" onclick="closeAddItemsModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                إلغاء
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-save-line"></i>
                                حفظ البنود
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
            @php
                // التأكد من وجود البيانات
                $previousQuantities = $previousQuantities ?? [];
                $previousValues = $previousValues ?? [];
            @endphp
            <form id="extractForm" action="{{ route('projects.extract.store', $project) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
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
                                @php
                                    // التأكد من وجود البيانات
                                    $previousQuantities = $previousQuantities ?? [];
                                    $previousValues = $previousValues ?? [];
                                @endphp
                                @foreach ($project->projectItems as $index => $item)
                                    @php
                                        // الحصول على الكميات والقيم السابقة من الكنترولر
                                        $previousQuantity = $previousQuantities[$index] ?? 0;
                                        $previousPaidValue = $previousValues[$index] ?? 0;

                                        // تعيين كمية المستخلص الحالي وقيمته إلى صفر في البداية
                                        $currentQuantity = 0;
                                        $currentExtractValue = 0;

                                        $totalProjectValue += $item->total_price;
                                        $totalPreviousPaid += $previousPaidValue;
                                        $totalCurrentExtract += $currentExtractValue;
                                    @endphp
                                    <tr class="border-b">
                                        <td class="px-4 py-3 border text-center">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 border">{{ $item->name }}</td>
                                        <td class="px-4 py-3 border text-center">{{ $item->unit }}</td>
                                        <td class="px-4 py-3 border text-center">{{ number_format($item->quantity, 2) }}
                                        </td>
                                        <td class="px-4 py-3 border text-center">{{ number_format($item->unit_price, 2) }}
                                        </td>
                                        <td class="px-4 py-3 border text-center">
                                            {{ number_format($item->total_price, 2) }}
                                        </td>
                                        <td class="px-4 py-3 border text-center bg-gray-50">
                                            {{ number_format($previousQuantity, 2) }}</td>
                                        <td class="px-4 py-3 border text-center bg-gray-50">
                                            {{ number_format($previousPaidValue, 2) }}</td>
                                        <td class="px-4 py-3 border text-center bg-blue-50">
                                            @php
                                                $maxQuantity = max(0, $item->quantity - $previousQuantity);
                                            @endphp
                                            <input type="number" step="0.01" min="0"
                                                max="{{ $maxQuantity }}" value="" placeholder="أدخل الكمية"
                                                class="w-full text-center border-0 bg-transparent focus:ring-2 focus:ring-blue-400 focus:bg-white rounded current-quantity"
                                                data-unit-price="{{ $item->unit_price }}" data-row="{{ $index }}"
                                                oninput="calculateCurrentValue(this)"
                                                title="أدخل كمية المستخلص الحالي (الحد الأقصى: {{ number_format($maxQuantity, 2) }})">
                                            @if ($maxQuantity <= 0)
                                                <small class="text-red-500 block mt-1">تم استخراج كامل الكمية</small>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 border text-center bg-blue-50">
                                            <span class="current-value font-medium text-blue-600 text-lg"
                                                data-row="{{ $index }}">--</span>
                                            <span class="text-xs text-gray-500 block">ر.س</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <!-- Totals Row -->
                            <tfoot class="bg-gray-100 font-bold">
                                <tr>
                                    <td colspan="5" class="px-4 py-3 border text-center">الإجمالي</td>
                                    <td class="px-4 py-3 border text-center">{{ number_format($totalProjectValue, 2) }}
                                    </td>
                                    <td class="px-4 py-3 border text-center">-</td>
                                    <td class="px-4 py-3 border text-center" id="total-previous-paid">
                                        {{ number_format($totalPreviousPaid, 2) }}</td>
                                    <td class="px-4 py-3 border text-center">-</td>
                                    <td class="px-4 py-3 border text-center font-bold text-blue-600"
                                        id="total-current-extract">
                                        --</td>
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
                                            id="summary-previous-paid">{{ number_format($totalPreviousPaid, 2) }}
                                            ر.س</span>
                                    </div>
                                    <div class="flex justify-between border-t pt-2">
                                        <span class="text-gray-900 font-semibold">قيمة المستخلص الحالي:</span>
                                        <span class="font-bold text-blue-600 text-lg" id="summary-current-extract">--
                                            ر.س</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">المتبقي من المشروع:</span>
                                        <span class="font-medium"
                                            id="summary-remaining">{{ number_format($project->budget - $totalPreviousPaid, 2) }}
                                            ر.س</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Progress Summary -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900">نسبة الإنجاز</h3>
                                <div class="space-y-3">
                                    @php
                                        $completedPercentage =
                                            $project->budget > 0 ? ($totalPreviousPaid / $project->budget) * 100 : 0;
                                        $currentPercentage = 0; // سيتم تحديثها بـ JavaScript
                                        $totalPercentage = $completedPercentage;
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
                                            <span class="text-sm font-medium" id="current-percentage">0.0%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full" id="current-progress-bar"
                                                style="width: 0%"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-semibold text-gray-900">إجمالي نسبة الإنجاز</span>
                                            <span class="text-sm font-bold"
                                                id="total-percentage">{{ number_format($totalPercentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div class="bg-green-500 h-3 rounded-full" id="total-progress-bar"
                                                style="width: {{ min($totalPercentage, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amount in Words -->
                        <div class="mt-6 p-4 bg-white rounded-lg border">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">قيمة المستخلص الحالي بالحروف:</h4>
                            <p class="text-gray-800" id="amount-in-words">صفر ريال سعودي</p>
                        </div>

                        <!-- Extract Details and File Upload Section -->
                        <div class="mt-6 p-6 bg-gray-50 rounded-lg border">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل المستخلص</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Extract Information -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="extract_number" class="block text-sm font-medium text-gray-700 mb-2">
                                            رقم المستخلص
                                        </label>
                                        <input type="text" id="extract_number" name="extract_number"
                                            value="{{ $nextExtractNumber ?? '' }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="أدخل رقم المستخلص" required>
                                    </div>

                                    <div>
                                        <label for="extract_description"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            وصف المستخلص
                                        </label>
                                        <textarea id="extract_description" name="extract_description" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="وصف تفصيلي للمستخلص"></textarea>
                                    </div>

                                    <div>
                                        <label for="extract_date" class="block text-sm font-medium text-gray-700 mb-2">
                                            تاريخ المستخلص
                                        </label>
                                        <input type="date" id="extract_date" name="extract_date"
                                            value="{{ now()->format('Y-m-d') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            required>
                                    </div>
                                </div>

                                <!-- File Upload -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="extract_file" class="block text-sm font-medium text-gray-700 mb-2">
                                            رفع ملف المستخلص
                                        </label>
                                        <div
                                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                            <input type="file" id="extract_file" name="extract_file"
                                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" class="hidden"
                                                onchange="handleFileSelect(this)">
                                            <div id="file-upload-area"
                                                onclick="document.getElementById('extract_file').click();"
                                                class="cursor-pointer">
                                                <i class="ri-upload-cloud-2-line text-4xl text-gray-400 mb-2"></i>
                                                <p class="text-sm text-gray-600">اضغط لاختيار ملف أو اسحب الملف هنا</p>
                                                <p class="text-xs text-gray-500 mt-1">PDF, DOC, XLS, أو صور (الحد الأقصى:
                                                    10 ميجابايت)</p>
                                            </div>
                                            <div id="file-info" class="hidden mt-3 p-3 bg-blue-50 rounded-lg">
                                                <div class="flex items-center gap-2">
                                                    <i class="ri-file-line text-blue-600"></i>
                                                    <span id="file-name" class="text-sm font-medium text-blue-900"></span>
                                                    <button type="button" onclick="removeFile()"
                                                        class="text-red-600 hover:text-red-800 ml-auto">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Extract Status -->
                                    <div>
                                        <label for="extract_status" class="block text-sm font-medium text-gray-700 mb-2">
                                            حالة المستخلص
                                        </label>
                                        <select id="extract_status" name="extract_status"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="draft">مسودة</option>
                                            <option value="submitted">مُقدم</option>
                                            <option value="approved">موافق عليه</option>
                                            <option value="paid">مدفوع</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs for extract data -->
                            <input type="hidden" id="extract_total" name="extract_total" value="0">
                            <input type="hidden" id="extract_items" name="extract_items" value="">
                            <input type="hidden" id="extract_tax_rate" name="extract_tax_rate" value="15">

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="ri-information-line"></i>
                                    <span>سيتم حفظ جميع البيانات المدخلة</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="window.close()"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                                        <i class="ri-close-line"></i>
                                        إلغاء
                                    </button>
                                    <button type="submit" id="saveExtractBtn"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                        <i class="ri-save-line"></i>
                                        <span>حفظ المستخلص</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        @else
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="text-center">
                    <i class="ri-file-list-3-line text-4xl mb-4 text-gray-400"></i>
                    <p class="text-gray-500 mb-4">لا توجد بنود لهذا المشروع</p>
                    <button type="button" onclick="openAddItemsModal()"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="ri-add-line"></i>
                        إضافة بنود للمشروع
                    </button>
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
            const maxQuantity = parseFloat(input.getAttribute('max')) || 0;

            // التحقق من عدم تجاوز الحد الأقصى
            if (quantity > maxQuantity) {
                input.value = maxQuantity;
                alert(`لا يمكن أن تكون الكمية أكبر من ${maxQuantity.toFixed(2)}`);
                return;
            }

            const currentValue = quantity * unitPrice;

            // تحديث قيمة المستخلص الحالي للصف
            const valueElement = document.querySelector(`[data-row="${row}"].current-value`);
            if (quantity > 0) {
                valueElement.innerHTML = `${currentValue.toFixed(2)} <span class="text-xs text-gray-500 block">ر.س</span>`;
                valueElement.classList.add('text-blue-600');
                valueElement.classList.remove('text-gray-400');

                // إضافة تأثير بصري للتحديث
                valueElement.classList.add('updated');
                setTimeout(() => valueElement.classList.remove('updated'), 600);
            } else {
                valueElement.innerHTML = '--';
                valueElement.classList.add('text-gray-400');
                valueElement.classList.remove('text-blue-600');
            }

            // إعادة حساب الإجماليات
            calculateTotals();
        }

        function calculateTotals() {
            let totalCurrentExtract = 0;

            // حساب إجمالي المستخلص الحالي
            document.querySelectorAll('.current-value').forEach(element => {
                const text = element.textContent || element.innerText;
                const value = parseFloat(text.replace(/[^\d.]/g, '')) || 0;
                totalCurrentExtract += value;
            });

            // تحديث الإجماليات في الجدول
            if (totalCurrentExtract > 0) {
                document.getElementById('total-current-extract').textContent = totalCurrentExtract.toFixed(2);
                document.getElementById('summary-current-extract').textContent = totalCurrentExtract.toFixed(2) + ' ر.س';
            } else {
                document.getElementById('total-current-extract').textContent = '--';
                document.getElementById('summary-current-extract').textContent = '-- ر.س';
            }

            // حساب المتبقي
            const projectBudget = {{ $project->budget }};
            const totalPreviousPaid = parseFloat(document.getElementById('total-previous-paid').textContent) || 0;
            const remaining = projectBudget - totalPreviousPaid - totalCurrentExtract;

            document.getElementById('summary-remaining').textContent = remaining.toFixed(2) + ' ر.س';

            // تحديث النسب المئوية
            updateProgressBars(totalCurrentExtract, projectBudget, totalPreviousPaid);

            // تحديث المبلغ بالحروف
            if (totalCurrentExtract > 0) {
                document.getElementById('amount-in-words').textContent = numberToArabicWords(totalCurrentExtract);
            } else {
                document.getElementById('amount-in-words').textContent = 'صفر ريال سعودي';
            }

            // تحديث البيانات المخفية للنموذج
            updateHiddenFormData(totalCurrentExtract);
        }

        function updateHiddenFormData(totalExtract) {
            // تحديث إجمالي المستخلص
            document.getElementById('extract_total').value = totalExtract.toFixed(2);

            // جمع بيانات البنود
            const extractItems = [];
            document.querySelectorAll('.current-quantity').forEach((input, index) => {
                const quantity = parseFloat(input.value) || 0;
                if (quantity > 0) {
                    const unitPrice = parseFloat(input.dataset.unitPrice);
                    // لا توجد حقول total_with_tax في جدول المستخلص؛ نستخدم القيمة بدون ضريبة هنا
                    extractItems.push({
                        item_index: index,
                        quantity: quantity,
                        unit_price: unitPrice,
                        total_value: quantity * unitPrice
                    });
                }
            });

            document.getElementById('extract_items').value = JSON.stringify(extractItems);
            // also set tax rate field
            const modalTaxRateEl = document.getElementById('modal_tax_rate');
            if (modalTaxRateEl) {
                const hiddenTaxInput = document.getElementById('extract_tax_rate');
                if (hiddenTaxInput) hiddenTaxInput.value = modalTaxRateEl.value;
            }
        }

        function updateProgressBars(currentExtract, projectBudget, previousPaid) {
            const previousPercentage = (previousPaid / projectBudget) * 100;
            const currentPercentage = (currentExtract / projectBudget) * 100;
            const totalPercentage = previousPercentage + currentPercentage;

            // تحديث نسبة المستخلص الحالي
            document.getElementById('current-percentage').textContent = currentPercentage.toFixed(1) + '%';
            document.getElementById('current-progress-bar').style.width = currentPercentage + '%';

            // تحديث إجمالي نسبة الإنجاز
            document.getElementById('total-percentage').textContent = totalPercentage.toFixed(1) + '%';
            document.getElementById('total-progress-bar').style.width = Math.min(totalPercentage, 100) + '%';
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

        // File handling functions
        function handleFileSelect(input) {
            const file = input.files[0];
            if (file) {
                // Check file size (10MB limit)
                if (file.size > 10 * 1024 * 1024) {
                    alert('حجم الملف كبير جداً. الحد الأقصى هو 10 ميجابايت.');
                    input.value = '';
                    return;
                }

                // Show file info
                document.getElementById('file-upload-area').classList.add('hidden');
                document.getElementById('file-info').classList.remove('hidden');
                document.getElementById('file-name').textContent = file.name;
            }
        }

        function removeFile() {
            document.getElementById('extract_file').value = '';
            document.getElementById('file-upload-area').classList.remove('hidden');
            document.getElementById('file-info').classList.add('hidden');
        }

        // Form submission handling (guarded)
        const extractFormEl = document.getElementById('extractForm');
        if (extractFormEl) {
            extractFormEl.addEventListener('submit', function(e) {
                const saveBtn = document.getElementById('saveExtractBtn');
                const totalExtract = parseFloat(document.getElementById('extract_total').value) || 0;
                const extractNumber = document.getElementById('extract_number').value.trim();

                if (!extractNumber) {
                    e.preventDefault();
                    alert('يرجى إدخال رقم المستخلص');
                    document.getElementById('extract_number').focus();
                    return;
                }

                if (totalExtract <= 0) {
                    e.preventDefault();
                    alert('يرجى إدخال كميات المستخلص قبل الحفظ');
                    return;
                }

                // Show loading state
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="ri-loader-2-line animate-spin"></i> جاري الحفظ...';
            });
        }

        // Drag and drop functionality (guarded)
        const fileUploadArea = document.getElementById('file-upload-area');
        if (fileUploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                fileUploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                fileUploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                fileUploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                fileUploadArea.classList.add('bg-blue-50', 'border-blue-300');
            }

            function unhighlight(e) {
                fileUploadArea.classList.remove('bg-blue-50', 'border-blue-300');
            }

            fileUploadArea.addEventListener('drop', handleDrop, false);
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                document.getElementById('extract_file').files = files;
                handleFileSelect(document.getElementById('extract_file'));
            }
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
        }
        
        /* Custom styles for better UX */
        .current-quantity {
            transition: all 0.3s ease;
        }
        
        .current-quantity:focus {
            transform: scale(1.05);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .current-value {
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .current-value.updated {
            animation: highlight 0.6s ease-in-out;
        }
        
        @keyframes highlight {
            0% { background-color: rgba(34, 197, 94, 0.2); }
            100% { background-color: transparent; }
        }
        
        .bg-blue-50:hover {
            background-color: rgba(239, 246, 255, 0.8);
        }`;

        const styleSheet = document.createElement("style");
        styleSheet.type = "text/css";
        styleSheet.innerText = printStyles;
        document.head.appendChild(styleSheet);

        // Modal Functions
        function openAddItemsModal() {
            document.getElementById('addItemsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAddItemsModal() {
            document.getElementById('addItemsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Items Management
        function addNewItem() {
            const container = document.getElementById('itemsContainer');
            const newIndex = container.querySelectorAll('.item-row').length;

            const itemTemplate = `
                <div class="item-row space-y-4 pb-6 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h4 class="font-medium text-gray-700">البند <span class="item-number">${newIndex + 1}</span></h4>
                        <button type="button" onclick="removeItem(this)" class="text-red-500 hover:text-red-700">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">اسم البند</label>
                            <input type="text" name="items[${newIndex}][name]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">وحدة القياس</label>
                            <select name="items[${newIndex}][unit]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">اختر وحدة القياس</option>
                                <option value="م3">م3</option>
                                <option value="م2">م2</option>
                                <option value="طن">طن</option>
                                <option value="لتر">لتر</option>
                                <option value="عدد">عدد</option>
                                <option value="م">م</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الكمية</label>
                            <input type="number" name="items[${newIndex}][quantity]" step="0.01" min="0" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                oninput="calculateTotal(this)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">السعر الإفرادي</label>
                            <input type="number" name="items[${newIndex}][unit_price]" step="0.01" min="0" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                oninput="calculateTotal(this)">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">السعر الإجمالي</label>
                        <input type="number" name="items[${newIndex}][total_price]" readonly
                            class="w-full rounded-lg bg-gray-50 border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">التكلفة بالضريبة</label>
                        <input type="number" name="items[${newIndex}][total_with_tax]" readonly
                            class="w-full rounded-lg bg-gray-50 border-gray-300">
                    </div>
                </div>
            `;

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = itemTemplate;
            container.appendChild(tempDiv.firstElementChild);

            // تحديث أرقام البنود
            updateItemNumbers();
        }

        function removeItem(button) {
            const itemRow = button.closest('.item-row');
            itemRow.remove();
            updateItemNumbers();
        }

        function updateItemNumbers() {
            document.querySelectorAll('.item-number').forEach((span, index) => {
                span.textContent = index + 1;
            });
        }

        function calculateTotal(input) {
            const row = input.closest('.item-row');
            const quantity = parseFloat(row.querySelector('input[name$="[quantity]"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('input[name$="[unit_price]"]').value) || 0;
            const totalInput = row.querySelector('input[name$="[total_price]"]');
            const totalWithTaxInput = row.querySelector('input[name$="[total_with_tax]"]');
            const modalTaxRateEl = document.getElementById('modal_tax_rate');
            const taxRate = modalTaxRateEl ? (parseFloat(modalTaxRateEl.value) || 0) : 0;
            totalInput.value = (quantity * unitPrice).toFixed(2);

            // Calculate total including tax
            if (totalWithTaxInput) {
                const subtotal = quantity * unitPrice;
                const totalWithTax = subtotal + (subtotal * (taxRate / 100));
                totalWithTaxInput.value = totalWithTax.toFixed(2);
            }
        }

        // Recalculate all items when tax rate changes
        document.addEventListener('DOMContentLoaded', function() {
            const taxRateInput = document.getElementById('modal_tax_rate');
            if (taxRateInput) {
                taxRateInput.addEventListener('input', function() {
                    document.querySelectorAll('#itemsContainer .item-row').forEach(row => {
                        const qtyInput = row.querySelector('input[name$="[quantity]"]');
                        if (qtyInput) {
                            calculateTotal(qtyInput);
                        }
                    });
                });
            }
        });

        // تحسين UX
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddItemsModal();
            }
        });

        // Ensure bindings after DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('addNewItemBtn');
            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    addNewItem();
                });
            }

            // Bind addItemsForm submission if form exists
            const addItemsForm = document.getElementById('addItemsForm');
            if (addItemsForm) {
                addItemsForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const submitButton = this.querySelector('button[type="submit"]');
                    submitButton.disabled = true;
                    submitButton.innerHTML =
                        '<i class="ri-loader-2-line animate-spin"></i> جاري الحفظ...';

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: new FormData(this),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            // إغلاق Modal وتحديث الصفحة
                            closeAddItemsModal();
                            window.location.reload();
                        } else {
                            throw new Error(result.message || 'حدث خطأ أثناء حفظ البنود');
                        }
                    } catch (error) {
                        alert(error.message);
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<i class="ri-save-line"></i> حفظ البنود';
                    }
                });
            }
        });
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
