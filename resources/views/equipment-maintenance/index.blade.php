@extends('layouts.app')

@section('title', 'إدارة صيانة المعدات')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إدارة صيانة المعدات</h1>
                <p class="text-gray-600 mt-1">تسجيل ومتابعة عمليات صيانة المعدات الداخلية والخارجية</p>
            </div>
            <a href="{{ route('equipment-maintenance.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-add-line"></i>
                تسجيل صيانة جديدة
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <!-- البحث -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="اسم المعدة، مركز الصيانة، رقم الفاتورة..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- نوع الصيانة -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الصيانة</label>
                    <select name="maintenance_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">جميع الأنواع</option>
                        <option value="internal" {{ request('maintenance_type') == 'internal' ? 'selected' : '' }}>داخلية
                        </option>
                        <option value="external" {{ request('maintenance_type') == 'external' ? 'selected' : '' }}>خارجية
                        </option>
                    </select>
                </div>

                <!-- الحالة -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">جميع الحالات</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                    </select>
                </div>

                <!-- المعدة -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المعدة</label>
                    <select name="equipment_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">جميع المعدات</option>
                        @foreach ($equipment as $eq)
                            <option value="{{ $eq->id }}" {{ request('equipment_id') == $eq->id ? 'selected' : '' }}>
                                {{ $eq->name }} - {{ $eq->model }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- أزرار العمل -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-search-line"></i>
                        بحث
                    </button>
                    <a href="{{ route('equipment-maintenance.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-refresh-line"></i>
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                @if ($maintenances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المعدة</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        تاريخ الصيانة</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        نوع الصيانة</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الحالة</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        التكلفة</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المسؤول</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($maintenances as $maintenance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div
                                                        class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center">
                                                        <i class="ri-tools-line text-orange-600"></i>
                                                    </div>
                                                </div>
                                                <div class="mr-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $maintenance->equipment->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $maintenance->equipment->model }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $maintenance->maintenance_date->format('Y/m/d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $maintenance->maintenance_type === 'internal'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-purple-100 text-purple-800' }}">
                                                <i
                                                    class="ri-{{ $maintenance->maintenance_type === 'internal' ? 'home' : 'building' }}-line ml-1"></i>
                                                {{ $maintenance->maintenance_type_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $maintenance->status === 'completed'
                                            ? 'bg-green-100 text-green-800'
                                            : ($maintenance->status === 'in_progress'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800') }}">
                                                {{ $maintenance->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if ($maintenance->maintenance_type === 'external' && $maintenance->external_cost)
                                                {{ number_format($maintenance->external_cost, 2) }} ر.س
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $maintenance->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('equipment-maintenance.show', $maintenance) }}"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('equipment-maintenance.edit', $maintenance) }}"
                                                    class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                @if ($maintenance->status === 'in_progress')
                                                    <!-- Button to open modal instead of direct submit -->
                                                    <button type="button" class="text-green-600 hover:text-green-900"
                                                        onclick="openCompleteModal({{ $maintenance->id }}, '{{ $maintenance->equipment->name }}', '{{ $maintenance->equipment->model ?? '' }}')">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                @endif
                                                <form method="POST"
                                                    action="{{ route('equipment-maintenance.destroy', $maintenance) }}"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $maintenances->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <i class="ri-tools-line text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد عمليات صيانة</h3>
                        <p class="text-gray-600 mb-6">لم يتم العثور على أي عمليات صيانة بالمعايير المحددة</p>
                        <a href="{{ route('equipment-maintenance.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center gap-2">
                            <i class="ri-add-line"></i>
                            تسجيل صيانة جديدة
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Complete Maintenance Modal -->
    <div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full shadow-xl" dir="rtl">
                <form id="completeForm" method="POST">
                    @csrf
                    @method('PATCH')

                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="ri-check-circle-line text-green-600 ml-2"></i>
                                تسجيل اكتمال الصيانة
                            </h3>
                            <button type="button"
                                onclick="document.getElementById('completeModal').classList.add('hidden')"
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
                                    <p id="equipmentInfo" class="text-blue-700 text-sm mt-1">
                                        <!-- Equipment info will be filled by JavaScript -->
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
                        <button type="button" onclick="document.getElementById('completeModal').classList.add('hidden')"
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
        // Function to open the complete modal
        function openCompleteModal(maintenanceId, equipmentName, equipmentModel) {
            // Set the form action URL
            var form = document.getElementById('completeForm');
            form.action = '/equipment-maintenance/' + maintenanceId + '/complete';

            // Set equipment info in the modal
            var equipmentInfo = document.getElementById('equipmentInfo');
            if (equipmentModel) {
                equipmentInfo.textContent = equipmentName + ' - ' + equipmentModel;
            } else {
                equipmentInfo.textContent = equipmentName;
            }

            // Show the modal
            document.getElementById('completeModal').classList.remove('hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('completeModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @if (session('success'))
        <div class="fixed bottom-4 left-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="ri-check-circle-line text-xl ml-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <script>
            setTimeout(function() {
                document.querySelector('.fixed.bottom-4').style.display = 'none';
            }, 5000);
        </script>
    @endif
@endsection
