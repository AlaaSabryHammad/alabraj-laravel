@extends('layouts.app')

@section('title', 'إدارة المواد - الإعدادات')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumb -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 space-x-reverse mb-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="ri-home-line"></i>
                </a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-blue-600 transition-colors">الإعدادات</a>
                <span class="text-gray-400">/</span>
                <span class="text-blue-600 font-medium">المواد</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">إدارة المواد</h1>
            <p class="text-gray-600">إدارة شاملة لمخزون المواد والمعدات ووحدات القياس</p>
        </div>
        <div class="hidden md:flex items-center justify-center">
            <div class="w-24 h-24 bg-gradient-to-r from-purple-500 via-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">إجمالي المواد</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $allMaterials->count() ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-box-3-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">متوفرة</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $allMaterials->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-check-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">مخزون منخفض</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $allMaterials->filter(function($m) { return $m->isLowStock(); })->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ri-alert-line text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">نفذ المخزون</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $allMaterials->where('current_stock', 0)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="ri-close-line text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('settings.materials') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="ابحث عن مادة..."
                            class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <i class="ri-search-line absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                    <select name="category" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white">
                        <option value="">جميع الفئات</option>
                        <option value="cement" {{ request('category') == 'cement' ? 'selected' : '' }}>أسمنت</option>
                        <option value="steel" {{ request('category') == 'steel' ? 'selected' : '' }}>حديد</option>
                        <option value="aggregate" {{ request('category') == 'aggregate' ? 'selected' : '' }}>خرسانة</option>
                        <option value="tools" {{ request('category') == 'tools' ? 'selected' : '' }}>أدوات</option>
                        <option value="electrical" {{ request('category') == 'electrical' ? 'selected' : '' }}>كهربائية</option>
                        <option value="plumbing" {{ request('category') == 'plumbing' ? 'selected' : '' }}>سباكة</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>نفذ المخزون</option>
                        <option value="discontinued" {{ request('status') == 'discontinued' ? 'selected' : '' }}>متوقف</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors flex items-center justify-center gap-2">
                        <i class="ri-search-line text-lg"></i>
                        <span>بحث</span>
                    </button>
                    <button type="button" onclick="openAddMaterialModal()" class="px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2" title="إضافة مادة جديدة">
                        <i class="ri-add-line text-lg"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Materials Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">قائمة المواد</h3>
        </div>

        @if($materials->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">المادة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">المخزون</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">الوحدة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">المورد</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">السعر</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">الحالة</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($materials as $material)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <i class="ri-box-3-line text-purple-600 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $material->name }}</p>
                                    @if($material->brand)
                                    <p class="text-xs text-gray-500">{{ $material->brand }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-block px-3 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                {{ $material->getCategoryNameAttribute() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">{{ number_format($material->current_stock) }}</span>
                                @if($material->isLowStock())
                                <i class="ri-error-warning-line text-red-500 text-lg" title="مخزون منخفض"></i>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $material->unit ?: 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $material->supplier_name ?: 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($material->unit_price)
                            {{ number_format($material->unit_price, 2) }} ريال
                            @else
                            غير محدد
                            @endif
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
                            <span class="inline-block px-3 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                {{ $material->getStatusTextAttribute() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick="editMaterial({{ $material->id }}, '{{ addslashes($material->name) }}', '{{ addslashes($material->unit ?? '') }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-purple-600 hover:bg-purple-50 transition-colors"
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
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="ri-inbox-line text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد مواد مسجلة</h3>
            <p class="text-gray-500 mb-6">ابدأ بإضافة المادة الأولى لبدء إدارة المخزون</p>
            <button type="button" onclick="openAddMaterialModal()"
                class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 text-white font-medium rounded-xl hover:bg-purple-700 transition-colors">
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
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
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

            <div class="space-y-5">
                <!-- Material Name -->
                <div>
                    <label for="material-name" class="block text-sm font-semibold text-gray-700 mb-2">
                        اسم المادة <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="material-name" name="name" required
                        placeholder="أدخل اسم المادة الفريد"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Unit of Measurement -->
                <div>
                    <label for="material-unit" class="block text-sm font-semibold text-gray-700 mb-2">
                        وحدة القياس <span class="text-red-500">*</span>
                    </label>
                    <select id="material-unit" name="unit" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white">
                        <option value="">اختر وحدة القياس</option>
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
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white">
                        <option value="">اختر الفئة</option>
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
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white">
                        <option value="">اختر الحالة</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                        <option value="out_of_stock">نفذ المخزون</option>
                        <option value="discontinued">متوقف</option>
                    </select>
                    <div id="status-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Description -->
                <div>
                    <label for="material-description" class="block text-sm font-semibold text-gray-700 mb-2">
                        الوصف <span class="text-gray-500">(اختياري)</span>
                    </label>
                    <textarea id="material-description" name="description" rows="3"
                        placeholder="أدخل وصف المادة..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                <button type="button" onclick="closeAddMaterialModal()"
                    class="px-6 py-2.5 font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    إلغاء
                </button>
                <button type="submit" id="submit-btn"
                    class="px-6 py-2.5 font-medium text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200">
                    <span id="submit-text">حفظ المادة</span>
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
// Global material list for validation
const existingMaterials = {!! json_encode(\App\Models\Material::pluck('name')->toArray()) !!};
const materialId = document.getElementById('material-id');

// ============================================
// Modal Control Functions
// ============================================

function openAddMaterialModal() {
    const modal = document.getElementById('add-material-modal');
    const form = document.getElementById('add-material-form');

    // Reset form
    form.reset();
    document.getElementById('material-id').value = '';
    document.getElementById('form-method').value = '';
    document.getElementById('modal-title').textContent = 'إضافة مادة جديدة';
    document.getElementById('submit-text').textContent = 'حفظ المادة';
    form.action = '{{ route('settings.materials.store') }}';

    // Clear errors
    clearAllErrors();

    // Show modal
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

    // Set form data
    document.getElementById('material-id').value = id;
    document.getElementById('material-name').value = name;
    document.getElementById('material-unit').value = unit;
    document.getElementById('modal-title').textContent = 'تعديل المادة';
    document.getElementById('submit-text').textContent = 'تحديث المادة';
    form.action = '/settings/materials/' + id;
    document.getElementById('form-method').value = 'PUT';

    // Clear errors
    clearAllErrors();

    // Show modal
    modal.classList.remove('hidden');
}

function clearAllErrors() {
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.querySelectorAll('input, select, textarea').forEach(el => {
        if (el.classList.contains('border-red-500')) {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        }
    });
}

// ============================================
// Form Submission and Validation
// ============================================

document.getElementById('add-material-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const name = document.getElementById('material-name').value.trim();
    const unit = document.getElementById('material-unit').value;
    const category = document.getElementById('material-category').value;
    const status = document.getElementById('material-status').value;
    const matId = document.getElementById('material-id').value;

    clearAllErrors();
    let isValid = true;

    // Validate name
    if (!name) {
        setError('name', 'اسم المادة مطلوب');
        isValid = false;
    } else if (!matId && existingMaterials.includes(name)) {
        setError('name', 'هذا الاسم موجود بالفعل، اختر اسم مختلف');
        isValid = false;
    }

    // Validate unit
    if (!unit) {
        setError('unit', 'وحدة القياس مطلوبة');
        isValid = false;
    }

    // Validate category
    if (!category) {
        setError('category', 'الفئة مطلوبة');
        isValid = false;
    }

    // Validate status
    if (!status) {
        setError('status', 'الحالة مطلوبة');
        isValid = false;
    }

    if (!isValid) return;

    // Submit via AJAX
    const submitBtn = document.getElementById('submit-btn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'جاري الحفظ...';

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddMaterialModal();
            location.reload();
        } else if (data.errors) {
            Object.entries(data.errors).forEach(([field, messages]) => {
                setError(field, messages[0]);
            });
        } else {
            alert('حدث خطأ في الحفظ، حاول مجددا');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال بالخادم');
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

// ============================================
// Modal Close Events
// ============================================

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('add-material-modal');
        if (modal && !modal.classList.contains('hidden')) {
            closeAddMaterialModal();
        }
    }
});

// Close modal when clicking outside
document.getElementById('add-material-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddMaterialModal();
    }
});
</script>
@endsection