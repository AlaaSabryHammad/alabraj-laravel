@extends('layouts.app')

@section('title', 'إدارة المواد - الإعدادات')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header with Breadcrumb -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 space-x-reverse mb-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-orange-600 transition-colors">
                    <i class="ri-home-line"></i>
                </a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-orange-600 transition-colors">الإعدادات</a>
                <span class="text-gray-400">/</span>
                <span class="text-orange-600 font-medium">المواد</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">إدارة المواد</h1>
            <p class="text-gray-600">إدارة شاملة لمخزون المواد والمعدات ووحدات القياس</p>
        </div>
        <div class="hidden md:flex items-center justify-center">
            <div class="w-24 h-24 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="ri-box-3-line text-white text-4xl"></i>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-2xl text-green-800">
        <i class="ri-check-circle-fill text-lg text-green-600"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Materials -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-2">إجمالي المواد</p>
                    <p class="text-3xl font-bold text-blue-900">{{ $allMaterials->count() ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-200 rounded-xl flex items-center justify-center">
                    <i class="ri-box-3-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Materials -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-2">متوفرة</p>
                    <p class="text-3xl font-bold text-green-900">{{ $allMaterials->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-200 rounded-xl flex items-center justify-center">
                    <i class="ri-check-double-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium mb-2">مخزون منخفض</p>
                    <p class="text-3xl font-bold text-yellow-900">{{ $allMaterials->filter(function($m) { return $m->isLowStock(); })->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-200 rounded-xl flex items-center justify-center">
                    <i class="ri-alert-line text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium mb-2">نفذ المخزون</p>
                    <p class="text-3xl font-bold text-red-900">{{ $allMaterials->where('current_stock', 0)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-200 rounded-xl flex items-center justify-center">
                    <i class="ri-close-circle-line text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header with Search and Button -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">قائمة المواد</h3>
                <button type="button" onclick="openAddMaterialModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors">
                    <i class="ri-add-line text-lg"></i>
                    <span>إضافة مادة</span>
                </button>
            </div>

            <!-- Search and Filters -->
            <form method="GET" action="{{ route('settings.materials') }}" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <!-- Search Field -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="ابحث عن مادة..."
                            class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-sm">
                        <i class="ri-search-line absolute right-3 top-3 text-gray-400"></i>
                    </div>

                    <!-- Category Filter -->
                    <select name="category" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all bg-white text-sm">
                        <option value="">جميع الفئات</option>
                        <option value="cement" {{ request('category') == 'cement' ? 'selected' : '' }}>أسمنت</option>
                        <option value="steel" {{ request('category') == 'steel' ? 'selected' : '' }}>حديد</option>
                        <option value="aggregate" {{ request('category') == 'aggregate' ? 'selected' : '' }}>خرسانة</option>
                        <option value="tools" {{ request('category') == 'tools' ? 'selected' : '' }}>أدوات</option>
                        <option value="electrical" {{ request('category') == 'electrical' ? 'selected' : '' }}>كهربائية</option>
                        <option value="plumbing" {{ request('category') == 'plumbing' ? 'selected' : '' }}>سباكة</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>

                    <!-- Status Filter -->
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all bg-white text-sm">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>نفذ المخزون</option>
                        <option value="discontinued" {{ request('status') == 'discontinued' ? 'selected' : '' }}>متوقف</option>
                    </select>

                    <!-- Filter Button -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors text-sm flex items-center justify-center gap-2">
                            <i class="ri-search-line"></i>
                            <span>بحث</span>
                        </button>
                        @if(request()->has(['search', 'category', 'status']) && (request('search') || request('category') || request('status')))
                        <a href="{{ route('settings.materials') }}" class="px-4 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors text-sm">
                            <i class="ri-close-line"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        @if($materials->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">المادة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">المخزون</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">الوحدة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">الحالة</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($materials as $material)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                                    <i class="ri-box-3-line text-orange-600 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $material->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-block px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">
                                {{ $material->getCategoryNameAttribute() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-900">{{ number_format($material->current_stock) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $material->unit ?: '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-gray-100 text-gray-800',
                                    'out_of_stock' => 'bg-red-100 text-red-800',
                                    'discontinued' => 'bg-orange-100 text-orange-800',
                                ];
                                $statusClass = $statusClasses[$material->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ $statusClass }}">
                                {{ $material->getStatusTextAttribute() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick="editMaterial({{ $material->id }}, '{{ addslashes($material->name) }}', '{{ addslashes($material->unit ?? '') }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-orange-600 hover:bg-orange-50 transition-colors"
                                    title="تعديل">
                                    <i class="ri-edit-line text-lg"></i>
                                </button>
                                <form action="{{ route('settings.materials.destroy', $material) }}" method="POST" class="inline"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
                                        title="حذف">
                                        <i class="ri-delete-bin-line text-lg"></i>
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
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $materials->appends(request()->query())->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="px-6 py-16 text-center">
            <div class="mx-auto w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                <i class="ri-inbox-line text-orange-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد مواد</h3>
            <p class="text-gray-500 mb-6">ابدأ بإضافة المادة الأولى</p>
            <button type="button" onclick="openAddMaterialModal()"
                class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors">
                <i class="ri-add-line text-lg"></i>
                <span>إضافة مادة جديدة</span>
            </button>
        </div>
        @endif
    </div>
</div>

<!-- ============================================
     MODAL - Add/Edit Material
     ============================================ -->
<div id="add-material-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-gray-900" id="modal-title">إضافة مادة جديدة</h2>
            <button type="button" onclick="closeAddMaterialModal()"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <!-- Modal Form -->
        <form id="add-material-form" method="POST" action="{{ route('settings.materials.store') }}" class="p-6">
            @csrf
            <input type="hidden" id="material-id" name="material_id">
            <input type="hidden" id="form-method" name="_method" value="">

            <div class="space-y-4">
                <!-- Material Name -->
                <div>
                    <label for="material-name" class="block text-sm font-semibold text-gray-700 mb-2">
                        اسم المادة <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="material-name" name="name" required
                        placeholder="أدخل اسم المادة"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                    <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Unit of Measurement -->
                <div>
                    <label for="material-unit" class="block text-sm font-semibold text-gray-700 mb-2">
                        وحدة القياس <span class="text-red-500">*</span>
                    </label>
                    <select id="material-unit" name="unit" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all bg-white">
                        <option value="">اختر وحدة</option>
                        @foreach(\App\Models\MaterialUnit::all() as $unit)
                            <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    <div id="unit-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Category -->
                <div>
                    <label for="material-category" class="block text-sm font-semibold text-gray-700 mb-2">
                        الفئة <span class="text-red-500">*</span>
                    </label>
                    <select id="material-category" name="category" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all bg-white">
                        <option value="">اختر</option>
                        <option value="cement">أسمنت</option>
                        <option value="steel">حديد</option>
                        <option value="aggregate">خرسانة</option>
                        <option value="tools">أدوات</option>
                        <option value="electrical">كهربائية</option>
                        <option value="plumbing">سباكة</option>
                        <option value="other">أخرى</option>
                    </select>
                    <div id="category-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Status -->
                <div>
                    <label for="material-status" class="block text-sm font-semibold text-gray-700 mb-2">
                        الحالة <span class="text-red-500">*</span>
                    </label>
                    <select id="material-status" name="status" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all bg-white">
                        <option value="">اختر</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                        <option value="out_of_stock">نفذ المخزون</option>
                        <option value="discontinued">متوقف</option>
                    </select>
                    <div id="status-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                <button type="button" onclick="closeAddMaterialModal()"
                    class="px-6 py-2 font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    إلغاء
                </button>
                <button type="submit" id="submit-btn"
                    class="px-6 py-2 font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition-colors">
                    <span id="submit-text">حفظ</span>
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
const existingMaterials = {!! json_encode(\App\Models\Material::pluck('name')->toArray()) !!};

function openAddMaterialModal() {
    const modal = document.getElementById('add-material-modal');
    const form = document.getElementById('add-material-form');
    form.reset();
    document.getElementById('material-id').value = '';
    document.getElementById('form-method').value = '';
    document.getElementById('modal-title').textContent = 'إضافة مادة جديدة';
    document.getElementById('submit-text').textContent = 'حفظ';
    form.action = '{{ route('settings.materials.store') }}';
    clearAllErrors();
    modal.classList.remove('hidden');
}

function closeAddMaterialModal() {
    const modal = document.getElementById('add-material-modal');
    modal.classList.add('hidden');
    clearAllErrors();
}

function editMaterial(id, name, unit) {
    const form = document.getElementById('add-material-form');
    const modal = document.getElementById('add-material-modal');
    document.getElementById('material-id').value = id;
    document.getElementById('material-name').value = name;
    document.getElementById('material-unit').value = unit;
    document.getElementById('modal-title').textContent = 'تعديل المادة';
    document.getElementById('submit-text').textContent = 'تحديث';
    form.action = '/settings/materials/' + id;
    document.getElementById('form-method').value = 'PUT';
    clearAllErrors();
    modal.classList.remove('hidden');
}

function clearAllErrors() {
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.querySelectorAll('input, select').forEach(el => {
        if (el.classList.contains('border-red-500')) {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        }
    });
}

document.getElementById('add-material-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const name = document.getElementById('material-name').value.trim();
    const unit = document.getElementById('material-unit').value;
    const matId = document.getElementById('material-id').value;
    clearAllErrors();
    let isValid = true;

    if (!name) {
        setError('name', 'اسم المادة مطلوب');
        isValid = false;
    } else if (!matId && existingMaterials.includes(name)) {
        setError('name', 'هذا الاسم موجود بالفعل');
        isValid = false;
    }

    if (!unit) {
        setError('unit', 'وحدة القياس مطلوبة');
        isValid = false;
    }

    if (!isValid) return;

    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'جاري...';

    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeAddMaterialModal();
            location.reload();
        } else if (data.errors) {
            Object.entries(data.errors).forEach(([field, msgs]) => setError(field, msgs[0]));
        } else {
            alert('حدث خطأ');
        }
    })
    .catch(e => {
        console.error(e);
        alert('خطأ في الاتصال');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

function setError(fieldName, message) {
    const errorEl = document.getElementById(fieldName + '-error');
    const inputEl = document.getElementById('material-' + fieldName);
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }
    if (inputEl) {
        inputEl.classList.add('border-red-500');
        inputEl.classList.remove('border-gray-300');
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('add-material-modal');
        if (modal && !modal.classList.contains('hidden')) {
            closeAddMaterialModal();
        }
    }
});

document.getElementById('add-material-modal').addEventListener('click', function(e) {
    if (e.target === this) closeAddMaterialModal();
});
</script>
@endsection