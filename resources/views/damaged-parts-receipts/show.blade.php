@extends('layouts.app')

@section('title', 'تفاصيل استلام القطعة التالفة')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('damaged-parts-receipts.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="ri-arrow-right-line text-2xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <i class="ri-alert-line text-red-600"></i>
                    تفاصيل الاستلام
                </h1>
                <p class="text-gray-600">{{ $damagedPartsReceipt->receipt_number }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            <a href="{{ route('damaged-parts-receipts.edit', $damagedPartsReceipt) }}"
                class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-edit-line"></i>
                تعديل
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6 flex items-center gap-4">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg"
            :class="{
                'bg-blue-100 text-blue-700': '{{ $damagedPartsReceipt->processing_status }}' === 'received',
                'bg-cyan-100 text-cyan-700': '{{ $damagedPartsReceipt->processing_status }}' === 'under_evaluation',
                'bg-yellow-100 text-yellow-700': '{{ $damagedPartsReceipt->processing_status }}' === 'approved_repair' || '{{ $damagedPartsReceipt->processing_status }}' === 'approved_replace',
                'bg-red-100 text-red-700': '{{ $damagedPartsReceipt->processing_status }}' === 'disposed',
                'bg-green-100 text-green-700': '{{ $damagedPartsReceipt->processing_status }}' === 'returned_fixed',
            }">
            <i class="ri-checkbox-circle-line"></i>
            <span class="font-medium">{{ $damagedPartsReceipt->processing_status_text }}</span>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg"
            :class="{
                'bg-green-100 text-green-700': '{{ $damagedPartsReceipt->damage_condition }}' === 'repairable',
                'bg-red-100 text-red-700': '{{ $damagedPartsReceipt->damage_condition }}' === 'non_repairable',
                'bg-yellow-100 text-yellow-700': '{{ $damagedPartsReceipt->damage_condition }}' === 'replacement_needed',
                'bg-blue-100 text-blue-700': '{{ $damagedPartsReceipt->damage_condition }}' === 'for_evaluation',
            }">
            <i class="ri-alert-fill"></i>
            <span class="font-medium">{{ $damagedPartsReceipt->damage_condition_text }}</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="space-y-6">
        <!-- معلومات الاستلام الأساسية -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-0">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="ri-info-line text-blue-600 text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">معلومات الاستلام الأساسية</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">رقم الإيصال</p>
                        <p class="text-lg font-bold text-blue-600">{{ $damagedPartsReceipt->receipt_number }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">تاريخ الاستلام</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $damagedPartsReceipt->receipt_date->format('Y-m-d') }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">وقت الاستلام</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $damagedPartsReceipt->receipt_time }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">موظف الاستلام</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $damagedPartsReceipt->receivedByEmployee->name }}</p>
                        <p class="text-xs text-gray-600">{{ $damagedPartsReceipt->receivedByEmployee->position }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات المشروع والمعدة -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-0">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                        <i class="ri-building-line text-orange-600 text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">معلومات المشروع والمعدة</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">المشروع</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $damagedPartsReceipt->project->name }}</p>
                        @if ($damagedPartsReceipt->project->description)
                            <p class="text-xs text-gray-600 mt-2">{{ $damagedPartsReceipt->project->description }}</p>
                        @endif
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">المعدة</p>
                        @if ($damagedPartsReceipt->equipment)
                            <p class="text-lg font-semibold text-gray-900">{{ $damagedPartsReceipt->equipment->name }}</p>
                            <p class="text-xs text-gray-600 mt-2">{{ $damagedPartsReceipt->equipment->serial_number }}</p>
                        @else
                            <p class="text-gray-600 italic">غير محددة</p>
                        @endif
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">موظف الإرسال</p>
                        @if ($damagedPartsReceipt->sentByEmployee)
                            <p class="text-lg font-semibold text-gray-900">{{ $damagedPartsReceipt->sentByEmployee->name }}</p>
                            <p class="text-xs text-gray-600 mt-2">{{ $damagedPartsReceipt->sentByEmployee->position }}</p>
                        @else
                            <p class="text-gray-600 italic">غير محدد</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات قطعة الغيار -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-0">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="ri-settings-line text-purple-600 text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">معلومات قطعة الغيار</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 md:col-span-2">
                        <p class="text-sm text-gray-600 mb-2">قطعة الغيار</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $damagedPartsReceipt->sparePart->name }}</p>
                        <p class="text-xs text-gray-600 mt-2">رقم القطعة: {{ $damagedPartsReceipt->sparePart->part_number }}</p>
                    </div>

                    <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <p class="text-sm text-gray-600 mb-2">الكمية المستلمة</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $damagedPartsReceipt->quantity_received }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- حالة التلف والمعالجة -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-0">
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                        <i class="ri-alert-fill text-red-600 text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">تقييم حالة التلف والمعالجة</h2>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Update Status Form -->
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm font-medium text-blue-900 mb-3">تحديث حالة المعالجة</p>
                    <form method="POST"
                        action="{{ route('damaged-parts-receipts.update-status', $damagedPartsReceipt) }}"
                        class="flex flex-col sm:flex-row gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="processing_status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="received"
                                {{ $damagedPartsReceipt->processing_status == 'received' ? 'selected' : '' }}>
                                تم الاستلام</option>
                            <option value="under_evaluation"
                                {{ $damagedPartsReceipt->processing_status == 'under_evaluation' ? 'selected' : '' }}>
                                تحت التقييم</option>
                            <option value="approved_repair"
                                {{ $damagedPartsReceipt->processing_status == 'approved_repair' ? 'selected' : '' }}>
                                موافقة على الإصلاح</option>
                            <option value="approved_replace"
                                {{ $damagedPartsReceipt->processing_status == 'approved_replace' ? 'selected' : '' }}>
                                موافقة على الاستبدال</option>
                            <option value="disposed"
                                {{ $damagedPartsReceipt->processing_status == 'disposed' ? 'selected' : '' }}>
                                تم التخلص منها</option>
                            <option value="returned_fixed"
                                {{ $damagedPartsReceipt->processing_status == 'returned_fixed' ? 'selected' : '' }}>
                                تم إرجاعها بعد الإصلاح</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap">
                            <i class="ri-check-line"></i>
                            تحديث
                        </button>
                    </form>
                </div>

                <!-- Condition and Status Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-lg border-2 border-red-200 bg-red-50">
                        <p class="text-sm text-red-700 font-medium mb-2">حالة التلف</p>
                        <p class="text-lg font-bold text-red-600">{{ $damagedPartsReceipt->damage_condition_text }}</p>
                    </div>

                    <div class="p-4 rounded-lg border-2 border-blue-200 bg-blue-50">
                        <p class="text-sm text-blue-700 font-medium mb-2">حالة المعالجة</p>
                        <p class="text-lg font-bold text-blue-600">{{ $damagedPartsReceipt->processing_status_text }}</p>
                    </div>

                    <div class="p-4 rounded-lg border-2 border-gray-200 bg-gray-50">
                        <p class="text-sm text-gray-700 font-medium mb-2">تاريخ الاستقبال</p>
                        <p class="text-lg font-bold text-gray-900">{{ $damagedPartsReceipt->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- الأوصاف والملاحظات -->
        @if (
            $damagedPartsReceipt->damage_description ||
                $damagedPartsReceipt->damage_cause ||
                $damagedPartsReceipt->technician_notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3 mb-0">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <i class="ri-file-text-line text-indigo-600 text-lg"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">الأوصاف والملاحظات</h2>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if ($damagedPartsReceipt->damage_description)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-3">وصف التلف</p>
                                <p class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 text-sm leading-relaxed">
                                    {{ $damagedPartsReceipt->damage_description }}
                                </p>
                            </div>
                        @endif

                        @if ($damagedPartsReceipt->damage_cause)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-3">سبب التلف</p>
                                <p class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 text-sm leading-relaxed">
                                    {{ $damagedPartsReceipt->damage_cause }}
                                </p>
                            </div>
                        @endif

                        @if ($damagedPartsReceipt->technician_notes)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-3">ملاحظات الفني</p>
                                <p class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 text-sm leading-relaxed">
                                    {{ $damagedPartsReceipt->technician_notes }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- معلومات التخزين -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-0">
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <i class="ri-store-2-line text-yellow-600 text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">معلومات التخزين</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">المخزن</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $damagedPartsReceipt->warehouse->name }}</p>
                        <p class="text-xs text-gray-600 mt-2">كود: {{ $damagedPartsReceipt->warehouse->code }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">موقع التخزين</p>
                        @if ($damagedPartsReceipt->storage_location)
                            <p class="text-lg font-semibold text-gray-900">{{ $damagedPartsReceipt->storage_location }}</p>
                        @else
                            <p class="text-gray-600 italic">غير محدد</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- التكاليف -->
        @if ($damagedPartsReceipt->estimated_repair_cost || $damagedPartsReceipt->replacement_cost)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3 mb-0">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class="ri-money-dollar-circle-line text-green-600 text-lg"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">التكاليف المقدرة</h2>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if ($damagedPartsReceipt->estimated_repair_cost)
                            <div class="p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border-2 border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-green-700 font-medium mb-2">التكلفة المقدرة للإصلاح</p>
                                        <p class="text-3xl font-bold text-green-600">
                                            {{ number_format($damagedPartsReceipt->estimated_repair_cost, 2) }}
                                            <span class="text-lg">ر.س</span>
                                        </p>
                                    </div>
                                    <i class="ri-tools-line text-4xl text-green-300"></i>
                                </div>
                            </div>
                        @endif

                        @if ($damagedPartsReceipt->replacement_cost)
                            <div class="p-6 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg border-2 border-yellow-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-yellow-700 font-medium mb-2">تكلفة الاستبدال</p>
                                        <p class="text-3xl font-bold text-yellow-600">
                                            {{ number_format($damagedPartsReceipt->replacement_cost, 2) }}
                                            <span class="text-lg">ر.س</span>
                                        </p>
                                    </div>
                                    <i class="ri-exchange-line text-4xl text-yellow-300"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- التواريخ المهمة -->
        @if (
            $damagedPartsReceipt->evaluation_date ||
                $damagedPartsReceipt->decision_date ||
                $damagedPartsReceipt->completion_date)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3 mb-0">
                        <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center">
                            <i class="ri-calendar-line text-pink-600 text-lg"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">التواريخ المهمة</h2>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if ($damagedPartsReceipt->evaluation_date)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">تاريخ التقييم</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ $damagedPartsReceipt->evaluation_date->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif

                        @if ($damagedPartsReceipt->decision_date)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">تاريخ اتخاذ القرار</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ $damagedPartsReceipt->decision_date->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif

                        @if ($damagedPartsReceipt->completion_date)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">تاريخ إكمال المعالجة</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ $damagedPartsReceipt->completion_date->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- المرفقات -->
        @if ($damagedPartsReceipt->photos || $damagedPartsReceipt->documents)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3 mb-0">
                        <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center">
                            <i class="ri-attachment-2 text-cyan-600 text-lg"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">المرفقات</h2>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- الصور -->
                        @if ($damagedPartsReceipt->photos)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-4">الصور</p>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach ($damagedPartsReceipt->photos as $photo)
                                        <div class="aspect-square overflow-hidden rounded-lg border-2 border-gray-200 hover:border-blue-400 transition-colors cursor-pointer group"
                                            onclick="showImageModal('{{ Storage::url($photo) }}')">
                                            <img src="{{ Storage::url($photo) }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-200"
                                                alt="صورة القطعة التالفة">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- المستندات -->
                        @if ($damagedPartsReceipt->documents)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-4">المستندات</p>
                                <div class="space-y-2">
                                    @foreach ($damagedPartsReceipt->documents as $document)
                                        <a href="{{ Storage::url($document['path']) }}"
                                            class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-blue-50 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors group"
                                            target="_blank">
                                            <i class="ri-file-pdf-line text-lg text-red-500"></i>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $document['original_name'] }}
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    {{ formatBytes($document['size']) }}
                                                </p>
                                            </div>
                                            <i class="ri-download-cloud-line text-gray-400 group-hover:text-blue-600"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- معلومات النظام -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-0">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                        <i class="ri-settings-3-line text-gray-600 text-lg"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">معلومات النظام</h2>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">تاريخ الإنشاء</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $damagedPartsReceipt->created_at->format('d/m/Y H:i:s') }}
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">آخر تحديث</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $damagedPartsReceipt->updated_at->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
    onclick="event.target === this && closeImageModal()">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl max-h-[90vh] overflow-auto">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
            <h3 class="text-lg font-bold text-gray-900">صورة القطعة التالفة</h3>
            <button onclick="closeImageModal()" class="text-gray-600 hover:text-gray-900">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div class="p-6 text-center">
            <img id="modalImage" src="" class="max-w-full h-auto rounded-lg" alt="صورة القطعة">
        </div>
    </div>
</div>

@endsection

@php
    function formatBytes($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
@endphp

@push('scripts')
    <script>
        function showImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endpush
