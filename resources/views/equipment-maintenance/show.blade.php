@extends('layouts.app')

@section('title', 'تفاصيل الصيانة')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('equipment-maintenance.index') }}"
                    class="text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">تفاصيل الصيانة</h1>
                    <p class="text-gray-600 mt-1">عرض تفاصيل عملية صيانة المعدة</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if ($equipmentMaintenance->status === 'in_progress')
                    <button type="button"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
                        onclick="openCompleteModal()">
                        <i class="ri-check-line"></i>
                        تسجيل الاكتمال
                    </button>
                @endif
                <a href="{{ route('equipment-maintenance.edit', $equipmentMaintenance) }}"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-edit-line"></i>
                    تعديل
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="ri-check-circle-line text-green-600 text-xl ml-2"></i>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- المعلومات الرئيسية -->
            <div class="lg:col-span-2">
                <!-- معلومات المعدة -->
                <div class="bg-white rounded-xl shadow-sm border mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="ri-tools-line text-orange-600 ml-2"></i>
                                معلومات المعدة
                            </h3>
                            <a href="{{ route('equipment.show', $equipmentMaintenance->equipment) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                                <i class="ri-external-link-line"></i>
                                عرض المعدة
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">اسم المعدة:</span>
                                <p class="text-gray-900 font-medium">{{ $equipmentMaintenance->equipment->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">الموديل:</span>
                                <p class="text-gray-900">{{ $equipmentMaintenance->equipment->model ?? 'غير محدد' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">الرقم التسلسلي:</span>
                                <p class="text-gray-900">{{ $equipmentMaintenance->equipment->serial_number ?? 'غير محدد' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">الحالة الحالية:</span>
                                <p class="text-gray-900">{{ $equipmentMaintenance->equipment->status ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تفاصيل الصيانة -->
                <div class="bg-white rounded-xl shadow-sm border mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="ri-settings-2-line text-blue-600 ml-2"></i>
                            تفاصيل الصيانة
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">تاريخ دخول الصيانة:</span>
                                <p class="text-gray-900 font-medium">
                                    {{ $equipmentMaintenance->maintenance_date->format('Y/m/d') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">نوع الصيانة:</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $equipmentMaintenance->maintenance_type === 'internal'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-purple-100 text-purple-800' }}">
                                    <i
                                        class="ri-{{ $equipmentMaintenance->maintenance_type === 'internal' ? 'home' : 'building' }}-line ml-1"></i>
                                    {{ $equipmentMaintenance->maintenance_type_text }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">الحالة:</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $equipmentMaintenance->status === 'completed'
                                    ? 'bg-green-100 text-green-800'
                                    : ($equipmentMaintenance->status === 'in_progress'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-red-100 text-red-800') }}">
                                    {{ $equipmentMaintenance->status_text }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">المسؤول عن التسجيل:</span>
                                <p class="text-gray-900">{{ $equipmentMaintenance->user->name }}</p>
                            </div>
                        </div>

                        @if ($equipmentMaintenance->expected_completion_date)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">تاريخ الإنجاز المتوقع:</span>
                                    <p class="text-gray-900">
                                        {{ $equipmentMaintenance->expected_completion_date->format('Y/m/d') }}</p>
                                </div>
                                @if ($equipmentMaintenance->actual_completion_date)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">تاريخ الإنجاز الفعلي:</span>
                                        <p class="text-gray-900 font-medium">
                                            {{ $equipmentMaintenance->actual_completion_date->format('Y/m/d') }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- تفاصيل الصيانة الخارجية -->
                @if ($equipmentMaintenance->maintenance_type === 'external')
                    <div class="bg-white rounded-xl shadow-sm border mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="ri-building-line text-purple-600 ml-2"></i>
                                تفاصيل الصيانة الخارجية
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($equipmentMaintenance->external_cost)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">تكلفة الصيانة:</span>
                                        <p class="text-gray-900 font-bold text-lg">
                                            {{ number_format($equipmentMaintenance->external_cost, 2) }} ر.س</p>
                                    </div>
                                @endif
                                @if ($equipmentMaintenance->maintenance_center)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">مركز الصيانة:</span>
                                        <p class="text-gray-900 font-medium">
                                            {{ $equipmentMaintenance->maintenance_center }}</p>
                                    </div>
                                @endif
                                @if ($equipmentMaintenance->invoice_number)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">رقم الفاتورة:</span>
                                        <p class="text-gray-900">{{ $equipmentMaintenance->invoice_number }}</p>
                                    </div>
                                @endif
                                @if ($equipmentMaintenance->invoice_image)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">صورة الفاتورة:</span>
                                        <a href="{{ Storage::url($equipmentMaintenance->invoice_image) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                            <i class="ri-file-image-line"></i>
                                            عرض الفاتورة
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- الوصف والملاحظات -->
                @if ($equipmentMaintenance->description || $equipmentMaintenance->notes)
                    <div class="bg-white rounded-xl shadow-sm border">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="ri-file-text-line text-gray-600 ml-2"></i>
                                تفاصيل إضافية
                            </h3>

                            @if ($equipmentMaintenance->description)
                                <div class="mb-4">
                                    <span class="text-sm font-medium text-gray-500">وصف الأعطال أو الصيانة:</span>
                                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 whitespace-pre-line">
                                            {{ $equipmentMaintenance->description }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($equipmentMaintenance->notes)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">الملاحظات:</span>
                                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 whitespace-pre-line">{{ $equipmentMaintenance->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- الشريط الجانبي -->
            <div>
                <!-- ملخص سريع -->
                <div class="bg-white rounded-xl shadow-sm border mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">ملخص سريع</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">رقم المعدة:</span>
                                <span class="font-medium text-gray-900">#{{ $equipmentMaintenance->equipment->id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">تاريخ التسجيل:</span>
                                <span
                                    class="font-medium text-gray-900">{{ $equipmentMaintenance->created_at->format('Y/m/d') }}</span>
                            </div>
                            @if ($equipmentMaintenance->maintenance_type === 'external' && $equipmentMaintenance->external_cost)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">التكلفة الإجمالية:</span>
                                    <span
                                        class="font-bold text-green-600">{{ number_format($equipmentMaintenance->external_cost, 2) }}
                                        ر.س</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- الإجراءات السريعة -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">الإجراءات</h3>

                        <div class="space-y-3">
                            <a href="{{ route('equipment-maintenance.edit', $equipmentMaintenance) }}"
                                class="w-full bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-edit-line"></i>
                                تعديل البيانات
                            </a>

                            @if ($equipmentMaintenance->status === 'in_progress')
                                <button type="button"
                                    class="w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
                                    onclick="openCompleteModal()">
                                    <i class="ri-check-line"></i>
                                    تسجيل الاكتمال
                                </button>
                            @endif

                            <a href="{{ route('equipment.show', $equipmentMaintenance->equipment) }}"
                                class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-tools-line"></i>
                                عرض المعدة
                            </a>

                            <form method="POST"
                                action="{{ route('equipment-maintenance.destroy', $equipmentMaintenance) }}"
                                class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-50 hover:bg-red-100 text-red-700 px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                    <i class="ri-delete-bin-line"></i>
                                    حذف السجل
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Maintenance Modal -->
    <div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full shadow-xl" dir="rtl">
                <form method="POST" action="{{ route('equipment-maintenance.complete', $equipmentMaintenance) }}">
                    @csrf
                    @method('PATCH')

                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="ri-check-circle-line text-green-600 ml-2"></i>
                                تسجيل اكتمال الصيانة
                            </h3>
                            <button type="button" onclick="closeCompleteModal()"
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                                <i class="ri-information-line text-blue-600 text-xl ml-3"></i>
                                <div>
                                    <h4 class="font-medium text-blue-900">معلومات الصيانة</h4>
                                    <p class="text-blue-700 text-sm mt-1">
                                        {{ $equipmentMaintenance->equipment->name }}
                                        @if ($equipmentMaintenance->equipment->model)
                                            - {{ $equipmentMaintenance->equipment->model }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="actual_completion_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="ri-calendar-check-line text-green-600 ml-1"></i>
                                تاريخ الاكتمال الفعلي
                            </label>
                            <input type="date" id="actual_completion_date" name="actual_completion_date"
                                value="{{ now()->format('Y-m-d') }}" required
                                class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div class="mb-4">
                            <label for="completion_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="ri-sticky-note-line text-gray-600 ml-1"></i>
                                ملاحظات الاكتمال (اختياري)
                            </label>
                            <textarea id="completion_notes" name="completion_notes" rows="3"
                                placeholder="أضف أي ملاحظات حول اكتمال الصيانة..."
                                class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <i class="ri-alert-line text-yellow-600 text-lg ml-2 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-yellow-800">
                                        <strong>تنبيه:</strong> بعد تسجيل الاكتمال سيتم تحديث حالة الصيانة إلى "مكتملة" ولن
                                        يمكن التراجع عن هذا الإجراء.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" onclick="closeCompleteModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-check-line"></i>
                            تأكيد الاكتمال
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCompleteModal() {
            document.getElementById('completeModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCompleteModal() {
            document.getElementById('completeModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('completeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCompleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCompleteModal();
            }
        });
    </script>
@endsection
