@if($equipmentTypes->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-right py-3 px-4 font-medium text-gray-700">اسم النوع</th>
                    <th class="text-right py-3 px-4 font-medium text-gray-700">الوصف</th>
                    <th class="text-right py-3 px-4 font-medium text-gray-700">عدد المعدات</th>
                    <th class="text-right py-3 px-4 font-medium text-gray-700">الحالة</th>
                    <th class="text-right py-3 px-4 font-medium text-gray-700">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($equipmentTypes as $equipmentType)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-100 to-orange-200 rounded-xl flex items-center justify-center ml-3">
                                    <i class="ri-tools-line text-orange-600"></i>
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $equipmentType->name }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm text-gray-600">
                                {{ $equipmentType->description ?: 'لا يوجد وصف' }}
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $equipmentType->equipment_count }} معدة
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            @if($equipmentType->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 ml-2 bg-green-400 rounded-full"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="w-2 h-2 ml-2 bg-gray-400 rounded-full"></span>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <button onclick="editEquipmentType({{ $equipmentType->id }}, '{{ $equipmentType->name }}', '{{ $equipmentType->description }}', {{ $equipmentType->is_active ? 'true' : 'false' }})"
                                        class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                                        title="تعديل">
                                    <i class="ri-edit-line"></i>
                                </button>
                                @if($equipmentType->equipment_count == 0)
                                    <button onclick="deleteEquipmentType({{ $equipmentType->id }}, '{{ $equipmentType->name }}', '{{ $equipmentType->description }}')"
                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                            title="حذف">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                @else
                                    <span class="p-2 text-gray-400" title="لا يمكن حذف النوع لأنه مرتبط بمعدات">
                                        <i class="ri-delete-bin-line"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-16">
        <div class="w-20 h-20 bg-gradient-to-r from-orange-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="ri-tools-line text-orange-600 text-3xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد أنواع معدات</h3>
        <p class="text-gray-500 mb-6">ابدأ بإضافة أول نوع معدات في النظام</p>
        <button onclick="openEquipmentTypeModal()"
                class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-3 rounded-xl font-medium hover:from-orange-700 hover:to-orange-800 transition-all duration-200 flex items-center mx-auto">
            <i class="ri-add-line ml-2"></i>
            إضافة أول نوع معدة
        </button>
    </div>
@endif
