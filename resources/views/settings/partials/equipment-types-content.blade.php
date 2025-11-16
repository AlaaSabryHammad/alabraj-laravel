<!-- Equipment Types Content - Modern Unified Style -->
@if ($equipmentTypes->count() > 0)
    <div class="space-y-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">أنواع المعدات</h3>
                <p class="text-gray-600 text-sm mt-1">إدارة أنواع المعدات المختلفة في النظام</p>
            </div>
            <button onclick="openEquipmentTypeModal()"
                class="flex items-center px-6 py-2.5 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-medium text-sm rounded-lg hover:from-orange-700 hover:to-orange-800 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="ri-add-line ml-2"></i>
                <span>إضافة نوع معدة</span>
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-hidden border border-gray-200 rounded-xl">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">النوع</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الوصف</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">عدد المعدات</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الحالة</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($equipmentTypes as $equipmentType)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-r from-orange-100 to-orange-200 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                            <i class="ri-tools-line text-orange-600"></i>
                                        </div>
                                        <div class="font-medium text-gray-900">{{ $equipmentType->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $equipmentType->description ?: '—' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $equipmentType->equipment_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($equipmentType->is_active)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                            <span class="w-2 h-2 ml-2 bg-green-500 rounded-full"></span>
                                            نشط
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-300">
                                            <span class="w-2 h-2 ml-2 bg-gray-400 rounded-full"></span>
                                            معطل
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <button
                                            onclick="editEquipmentType({{ $equipmentType->id }}, '{{ $equipmentType->name }}', '{{ $equipmentType->description }}', {{ $equipmentType->is_active ? 'true' : 'false' }})"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-150"
                                            title="تعديل">
                                            <i class="ri-edit-line text-lg"></i>
                                        </button>
                                        @if ($equipmentType->equipment_count == 0)
                                            <button
                                                onclick="deleteEquipmentType({{ $equipmentType->id }}, '{{ $equipmentType->name }}', '{{ $equipmentType->description }}')"
                                                class="inline-flex items-center justify-center p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
                                                title="حذف">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                        @else
                                            <span
                                                class="inline-flex items-center justify-center p-2 text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed"
                                                title="لا يمكن الحذف - مرتبط بمعدات">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-16 px-6">
        <div
            class="w-24 h-24 bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl flex items-center justify-center mb-6">
            <i class="ri-tools-line text-orange-600 text-4xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد أنواع معدات</h3>
        <p class="text-gray-600 text-center mb-8 max-w-sm">ابدأ بإضافة أول نوع معدات لتنظيم المعدات في النظام</p>
        <button onclick="openEquipmentTypeModal()"
            class="flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-medium rounded-xl hover:from-orange-700 hover:to-orange-800 transition-all duration-200 shadow-sm hover:shadow-md">
            <i class="ri-add-line ml-2"></i>
            <span>إضافة أول نوع معدة</span>
        </button>
    </div>
@endif
إضافة أول نوع معدة
</button>
</div>
@endif
