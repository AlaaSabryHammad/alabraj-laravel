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
                <button onclick="openAddSparePartTypeModal()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-add-line"></i>
                    إضافة نوع قطعة غيار
                </button>
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                        <select name="category" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">اختر الفئة</option>
                            <option value="engine">محرك</option>
                            <option value="transmission">ناقل الحركة</option>
                            <option value="brakes">المكابح</option>
                            <option value="electrical">كهربائي</option>
                            <option value="hydraulic">هيدروليك</option>
                            <option value="cooling">تبريد</option>
                            <option value="filters">فلاتر</option>
                            <option value="tires">إطارات</option>
                            <option value="body">هيكل</option>
                            <option value="other">أخرى</option>
                        </select>
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

    <script>
        function openAddSparePartTypeModal() {
            document.getElementById('addSparePartTypeModal').classList.remove('hidden');
        }

        function closeAddSparePartTypeModal() {
            document.getElementById('addSparePartTypeModal').classList.add('hidden');
        }
    </script>
@endsection
