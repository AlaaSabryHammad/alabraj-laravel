<div class="bg-white rounded-xl shadow-sm border">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6 text-white rounded-t-xl">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold flex items-center gap-3">
                <i class="ri-building-line text-2xl"></i>
                إدارة جهات الصرف
            </h3>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm opacity-90">إجمالي الجهات</div>
                    <div class="text-2xl font-bold">{{ $entitiesCount }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-90">الجهات النشطة</div>
                    <div class="text-2xl font-bold">{{ $activeEntitiesCount }}</div>
                </div>
                <a href="{{ route('expense-entities.create') }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-add-line"></i>
                    إضافة جهة جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-6">
        @if($expenseEntities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                اسم الجهة
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                النوع
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الشخص المسؤول
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الجوال
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($expenseEntities as $entity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="ri-building-line text-purple-600"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $entity->name }}</div>
                                            @if($entity->commercial_record)
                                                <div class="text-xs text-gray-500">س.ت: {{ $entity->commercial_record }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($entity->type === 'supplier') bg-blue-100 text-blue-800
                                        @elseif($entity->type === 'contractor') bg-green-100 text-green-800
                                        @elseif($entity->type === 'government') bg-red-100 text-red-800
                                        @elseif($entity->type === 'bank') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $entity->type_text }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entity->contact_person ?? 'غير محدد' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entity->phone ?? 'غير متوفر' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($entity->status === 'active') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($entity->status === 'active') نشط @else غير نشط @endif
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('expense-entities.show', $entity) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('expense-entities.edit', $entity) }}" 
                                           class="text-green-600 hover:text-green-900 transition-colors">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('expense-entities.destroy', $entity) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الجهة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors">
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
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-4">
                    <i class="ri-building-line text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد جهات صرف</h3>
                <p class="text-gray-600 mb-4">ابدأ بإضافة أول جهة صرف</p>
                <a href="{{ route('expense-entities.create') }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors inline-flex items-center gap-2">
                    <i class="ri-add-line"></i>
                    إضافة جهة جديدة
                </a>
            </div>
        @endif
    </div>
</div>