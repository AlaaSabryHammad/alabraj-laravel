@extends('layouts.app')

@section('title', 'إدارة المستودعات')

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إدارة المستودعات</h1>
                <p class="text-gray-600">عرض وإدارة جميع المستودعات وقطع الغيار</p>
            </div>
            <div class="flex gap-3">
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي المستودعات</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $warehouses->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ri-store-3-line text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الأصناف</p>
                        <p class="text-2xl font-bold text-green-600">{{ $warehouses->sum('inventories_count') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ri-box-3-line text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">المستودعات النشطة</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $warehouses->where('status', 'نشط')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="ri-checkbox-circle-line text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouses Grid -->
        @if($warehouses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($warehouses as $warehouse)
                    <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="ri-store-3-line text-2xl text-orange-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $warehouse->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $warehouse->locationType->name ?? 'مستودع' }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                    @if($warehouse->status === 'نشط')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif">
                                    {{ $warehouse->status ?? 'نشط' }}
                                </span>
                            </div>

                            <!-- Info -->
                            <div class="space-y-2 mb-4">
                                @if($warehouse->city)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="ri-map-pin-line"></i>
                                        <span>{{ $warehouse->city }}{{ $warehouse->region ? ' - ' . $warehouse->region : '' }}</span>
                                    </div>
                                @endif
                                @if($warehouse->address)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="ri-road-map-line"></i>
                                        <span>{{ Str::limit($warehouse->address, 40) }}</span>
                                    </div>
                                @endif
                                @if($warehouse->manager_name)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="ri-user-line"></i>
                                        <span>المسؤول: {{ $warehouse->manager_name }}</span>
                                    </div>
                                @endif
                                @if($warehouse->contact_phone)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="ri-phone-line"></i>
                                        <span>{{ $warehouse->contact_phone }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="ri-box-3-line"></i>
                                    <span>{{ $warehouse->inventories_count }} صنف مخزون</span>
                                </div>
                                @if($warehouse->area_size)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="ri-ruler-line"></i>
                                        <span>المساحة: {{ number_format($warehouse->area_size) }} م²</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 pt-4 border-t">
                                <a href="{{ route('warehouses.show', $warehouse) }}"
                                   class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors text-center text-sm font-medium">
                                    <i class="ri-eye-line mr-1"></i>
                                    عرض المخزون
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-store-3-line text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مستودعات</h3>
                <p class="text-gray-600 mb-4">لم يتم العثور على أي مستودعات في النظام</p>
                <a href="{{ route('locations.create') }}"
                   class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="ri-add-line"></i>
                    إضافة مستودع جديد
                </a>
            </div>
        @endif

        <!-- Spare Parts Table Section -->
        <div class="mt-12">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">إجمالي قطع الغيار في النظام</h2>
                        <p class="text-sm text-gray-600">عرض جميع قطع الغيار المتاحة في جميع المستودعات</p>
                    </div>
                </div>

                <!-- Spare Parts Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                        <p class="text-sm font-medium text-blue-700 mb-1">إجمالي الأصناف</p>
                        <p class="text-2xl font-bold text-blue-900">
                            {{ \App\Models\SparePart::where('is_active', true)->count() }}
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                        <p class="text-sm font-medium text-green-700 mb-1">إجمالي الكمية المخزنة</p>
                        <p class="text-2xl font-bold text-green-900">
                            {{ \App\Models\WarehouseInventory::sum('current_stock') }}
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
                        <p class="text-sm font-medium text-orange-700 mb-1">القطع التالفة</p>
                        <p class="text-2xl font-bold text-orange-900">
                            {{ \App\Models\WarehouseInventory::sum('damaged_stock') }}
                        </p>
                    </div>
                </div>

                <!-- Spare Parts Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">#</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">اسم القطعة</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الكود</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الفئة</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الكمية المتاحة</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الكمية المحفوظة</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">القطع التالفة</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">القيمة الإجمالية</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\SparePart::where('is_active', true)->orderBy('name')->get() as $index => $part)
                                @php
                                    $inventory = \App\Models\WarehouseInventory::where('spare_part_id', $part->id)->first();
                                    $totalStock = \App\Models\WarehouseInventory::where('spare_part_id', $part->id)->sum('current_stock');
                                    $totalReserved = \App\Models\WarehouseInventory::where('spare_part_id', $part->id)->sum('reserved_stock');
                                    $totalDamaged = \App\Models\WarehouseInventory::where('spare_part_id', $part->id)->sum('damaged_stock');
                                    $totalValue = \App\Models\WarehouseInventory::where('spare_part_id', $part->id)->sum('total_value');
                                @endphp
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-600">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $part->name }}</div>
                                        @if($part->description)
                                            <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($part->description, 30) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded text-xs font-mono text-gray-700">
                                            {{ $part->code }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        @if($part->sparePartType)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                                {{ $part->sparePartType->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-blue-600">{{ $totalStock }}</span>
                                            @if($totalStock <= ($part->minimum_stock ?? 10))
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-medium">
                                                    <i class="ri-alert-line"></i>
                                                    منخفض
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $totalReserved }}</td>
                                    <td class="px-4 py-3">
                                        @if($totalDamaged > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-medium">
                                                <i class="ri-alert-fill"></i>
                                                {{ $totalDamaged }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-gray-900">
                                            {{ number_format($totalValue, 2) }} ريال
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                            <i class="ri-check-circle-line"></i>
                                            نشط
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                        <i class="ri-inbox-line text-3xl mb-2 block"></i>
                                        لا توجد قطع غيار مسجلة في النظام
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Spare Parts Management Section -->
        <div class="mt-12">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">إدارة أسماء قطع الغيار</h2>
                        <p class="text-sm text-gray-600">عرض وإدارة جميع قطع الغيار في النظام</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="openAddSparePartModal()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-add-line"></i>
                            إضافة قطعة غيار
                        </button>
                        <button onclick="openAddSparePartTypeModal()"
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ri-add-line"></i>
                            إضافة فئة قطع الغيار
                        </button>
                    </div>
                </div>

                <!-- Spare Parts Management Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">#</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">اسم القطعة</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الكود</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الفئة</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الوصف</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الحد الأدنى</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">النشاط</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\SparePart::orderBy('name')->get() as $index => $part)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-600">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $part->name }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded text-xs font-mono text-gray-700">
                                            {{ $part->code }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($part->sparePartType)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                                {{ $part->sparePartType->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($part->description)
                                            <span class="text-gray-600 text-xs">{{ Str::limit($part->description, 40) }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-gray-900">{{ $part->minimum_stock ?? 0 }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($part->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                                <i class="ri-check-circle-line"></i>
                                                نشط
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                                <i class="ri-close-circle-line"></i>
                                                غير نشط
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <button onclick="editSparePart({{ $part->id }}, '{{ $part->name }}', '{{ $part->code }}', '{{ $part->description ?? '' }}', {{ $part->minimum_stock ?? 0 }}, {{ $part->is_active ? 'true' : 'false' }})"
                                                    class="text-blue-600 hover:text-blue-800 transition-colors"
                                                    title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <form action="{{ route('spare-parts.destroy', $part->id) }}" method="POST" style="display: inline;"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه القطعة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="حذف">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        <i class="ri-inbox-line text-3xl mb-2 block"></i>
                                        لا توجد قطع غيار مسجلة في النظام
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal إضافة نوع قطعة غيار -->
    <div id="addSparePartTypeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">إضافة نوع قطعة غيار جديد</h3>
                <button onclick="closeAddSparePartTypeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form action="{{ route('spare-part-types.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم نوع القطعة</label>
                        <input type="text" name="name" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="مثال: مكابح، محرك، إطارات...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea name="description" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="وصف مختصر لنوع القطعة..."></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                        حفظ النوع
                    </button>
                    <button type="button" onclick="closeAddSparePartTypeModal()" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal إضافة/تعديل قطعة غيار -->
    <div id="addSparePartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900" id="sparePartModalTitle">إضافة قطعة غيار جديدة</h3>
                <button onclick="closeAddSparePartModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form id="sparePartForm" method="POST" action="{{ route('spare-parts.store') }}">
                @csrf
                <input type="hidden" id="sparePartId" name="id">
                <input type="hidden" id="sparePartMethod" name="_method" value="POST">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم القطعة *</label>
                        <input type="text" id="sparePartName" name="name" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="اسم قطعة الغيار"
                               oninput="generateSparePartCode()">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الكود (يتم التوليد تلقائياً)</label>
                        <input type="text" id="sparePartCode" name="code" required readonly
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="سيتم التوليد تلقائياً">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                        <select name="spare_part_type_id" id="sparePartType"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">اختر الفئة</option>
                            @foreach(\App\Models\SparePartType::all() as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الحد الأدنى للمخزون</label>
                        <input type="number" id="sparePartMinStock" name="minimum_stock" value="0" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="الحد الأدنى">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea id="sparePartDescription" name="description" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="وصف القطعة..."></textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="sparePartActive" name="is_active" value="1" checked
                               class="rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
                        <label for="sparePartActive" class="text-sm font-medium text-gray-700">نشط</label>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors font-medium">
                        حفظ
                    </button>
                    <button type="button" onclick="closeAddSparePartModal()"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddSparePartTypeModal() {
            document.getElementById('addSparePartTypeModal').classList.remove('hidden');
        }

        function closeAddSparePartTypeModal() {
            document.getElementById('addSparePartTypeModal').classList.add('hidden');
        }

        function openAddSparePartModal() {
            document.getElementById('sparePartModalTitle').textContent = 'إضافة قطعة غيار جديدة';
            document.getElementById('sparePartForm').action = '{{ route("spare-parts.store") }}';
            document.getElementById('sparePartMethod').value = 'POST';
            document.getElementById('sparePartId').value = '';
            document.getElementById('sparePartForm').reset();
            document.getElementById('sparePartActive').checked = true;
            document.getElementById('addSparePartModal').classList.remove('hidden');
        }

        function closeAddSparePartModal() {
            document.getElementById('addSparePartModal').classList.add('hidden');
        }

        function editSparePart(id, name, code, description, minimumStock, isActive) {
            document.getElementById('sparePartModalTitle').textContent = 'تعديل قطعة غيار';
            document.getElementById('sparePartForm').action = `{{ route('spare-parts.update', ':id') }}`.replace(':id', id);
            document.getElementById('sparePartMethod').value = 'PUT';
            document.getElementById('sparePartId').value = id;
            document.getElementById('sparePartName').value = name;
            document.getElementById('sparePartCode').value = code;
            document.getElementById('sparePartDescription').value = description;
            document.getElementById('sparePartMinStock').value = minimumStock;
            document.getElementById('sparePartActive').checked = isActive;
            document.getElementById('addSparePartModal').classList.remove('hidden');
        }

        function generateSparePartCode() {
            const nameInput = document.getElementById('sparePartName');
            const codeInput = document.getElementById('sparePartCode');
            const name = nameInput.value.trim();

            if (!name) {
                codeInput.value = '';
                return;
            }

            // Generate code: SP-YYYYMMDD-XXXXX
            const timestamp = new Date();
            const year = timestamp.getFullYear();
            const month = String(timestamp.getMonth() + 1).padStart(2, '0');
            const day = String(timestamp.getDate()).padStart(2, '0');
            const dateString = `${year}${month}${day}`;

            // Get first 3 letters from name (or less if shorter)
            const namePrefix = name.substring(0, 3).toUpperCase().replace(/[^A-Z]/g, '');

            // Generate random 5-digit number
            const randomNum = String(Math.floor(Math.random() * 90000) + 10000);

            // Combine to create code
            const generatedCode = `SP-${dateString}-${namePrefix}${randomNum}`;

            codeInput.value = generatedCode;
        }
    </script>
@endsection
