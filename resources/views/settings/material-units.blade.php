@extends('layouts.app')

@section('title', 'وحدات المواد - الإعدادات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">وحدات المواد</h1>
                <p class="text-gray-600">إدارة وحدات القياس المستخدمة في المواد</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="ri-scales-line text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 space-x-reverse px-6" aria-label="Tabs">
                <a href="{{ route('settings.equipment-types') }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="ri-tools-line ml-2"></i>
                    أنواع المعدات
                </a>

                <a href="{{ route('settings.location-types') }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="ri-map-pin-line ml-2"></i>
                    أنواع المواقع
                </a>

                <a href="{{ route('suppliers.index') }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="ri-truck-line ml-2"></i>
                    إدارة الموردين
                </a>

                <a href="{{ route('settings.material-units') }}"
                   class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="ri-scales-line ml-2"></i>
                    وحدات المواد
                </a>
            </nav>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="m-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
            <div class="flex items-center">
                <i class="ri-check-circle-line text-green-600 ml-2"></i>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="m-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
            <div class="flex items-center">
                <i class="ri-error-warning-line text-red-600 ml-2"></i>
                {{ session('error') }}
            </div>
        </div>
        @endif

        <!-- Content -->
        <div class="p-6">
            <!-- Add New Unit Form -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إضافة وحدة جديدة</h3>
                <form action="{{ route('settings.material-units.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الوحدة</label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               placeholder="مثل: متر مكعب"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="symbol" class="block text-sm font-medium text-gray-700 mb-2">الرمز</label>
                        <input type="text"
                               name="symbol"
                               id="symbol"
                               value="{{ old('symbol') }}"
                               placeholder="مثل: م³"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('symbol') border-red-300 @enderror">
                        @error('symbol')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                        <select name="type"
                                id="type"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('type') border-red-300 @enderror">
                            <option value="">اختر النوع</option>
                            <option value="volume" {{ old('type') == 'volume' ? 'selected' : '' }}>حجم</option>
                            <option value="weight" {{ old('type') == 'weight' ? 'selected' : '' }}>وزن</option>
                            <option value="length" {{ old('type') == 'length' ? 'selected' : '' }}>طول</option>
                            <option value="area" {{ old('type') == 'area' ? 'selected' : '' }}>مساحة</option>
                            <option value="count" {{ old('type') == 'count' ? 'selected' : '' }}>عدد</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <input type="text"
                               name="description"
                               id="description"
                               value="{{ old('description') }}"
                               placeholder="وصف اختياري"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-300 @enderror">
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="ri-add-line ml-2"></i>
                            إضافة
                        </button>
                    </div>
                </form>
            </div>

            <!-- Units List -->
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">وحدات المواد</h3>
                </div>

                @if($materialUnits->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوحدة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرمز</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($materialUnits->groupBy('type') as $type => $units)
                                    @foreach($units as $unit)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $unit->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $unit->symbol }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @switch($unit->type)
                                                        @case('volume') bg-green-100 text-green-800 @break
                                                        @case('weight') bg-yellow-100 text-yellow-800 @break
                                                        @case('length') bg-purple-100 text-purple-800 @break
                                                        @case('area') bg-pink-100 text-pink-800 @break
                                                        @case('count') bg-gray-100 text-gray-800 @break
                                                        @default bg-gray-100 text-gray-800
                                                    @endswitch">
                                                    {{ $unit->type_label }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $unit->description ?: '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($unit->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        نشط
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        غير نشط
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="editUnit({{ $unit->id }}, '{{ $unit->name }}', '{{ $unit->symbol }}', '{{ $unit->type }}', '{{ $unit->description }}', {{ $unit->is_active ? 'true' : 'false' }})"
                                                        class="text-blue-600 hover:text-blue-900 ml-3">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button onclick="deleteUnit({{ $unit->id }}, '{{ $unit->name }}')"
                                                        class="text-red-600 hover:text-red-900">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-scales-line text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد وحدات مواد</h3>
                        <p class="text-gray-500">ابدأ بإضافة أول وحدة قياس للمواد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">تعديل وحدة المادة</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الوحدة</label>
                        <input type="text" name="name" id="edit_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="edit_symbol" class="block text-sm font-medium text-gray-700 mb-2">الرمز</label>
                        <input type="text" name="symbol" id="edit_symbol" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="edit_type" class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                        <select name="type" id="edit_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="volume">حجم</option>
                            <option value="weight">وزن</option>
                            <option value="length">طول</option>
                            <option value="area">مساحة</option>
                            <option value="count">عدد</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <input type="text" name="description" id="edit_description" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="edit_is_active" class="mr-2 text-sm text-gray-700">نشط</label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 space-x-reverse mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-error-warning-line text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">تأكيد الحذف</h3>
                <p class="text-gray-600 mb-6">هل أنت متأكد من حذف وحدة "<span id="deleteUnitName"></span>"؟</p>
                <div class="flex justify-center space-x-3 space-x-reverse">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        إلغاء
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editUnit(id, name, symbol, type, description, isActive) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_symbol').value = symbol;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_is_active').checked = isActive;
    document.getElementById('editForm').action = `/settings/material-units/${id}`;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function deleteUnit(id, name) {
    document.getElementById('deleteUnitName').textContent = name;
    document.getElementById('deleteForm').action = `/settings/material-units/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection
