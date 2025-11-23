@extends('layouts.app')

@section('title', 'إدارة المواد - الإعدادات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-row-reverse items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة المواد</h1>
                <p class="text-gray-600">إدارة شاملة لمخزون المواد والمعدات</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="openAddMaterialModal()"
                   class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
                    <i class="ri-add-line ml-2"></i>
                    إضافة مادة جديدة
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
        <div class="flex items-center">
            <i class="ri-check-circle-line text-green-600 ml-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Filters and Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('settings.materials') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="ابحث في المواد..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>نفذ المخزون</option>
                        <option value="discontinued" {{ request('status') == 'discontinued' ? 'selected' : '' }}>متوقف</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="ri-search-line ml-1"></i>
                        بحث
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">إجمالي المواد</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $materials->total() ?? 0 }}</h3>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl">
                    <i class="ri-box-3-line text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">متوفرة</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $allMaterials->where('status', 'active')->count() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl">
                    <i class="ri-check-line text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium mb-1">مخزون منخفض</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $allMaterials->filter(function($m) { return $m->isLowStock(); })->count() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-3 rounded-xl">
                    <i class="ri-alert-line text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium mb-1">نفذ المخزون</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $allMaterials->where('current_stock', 0)->count() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-3 rounded-xl">
                    <i class="ri-close-line text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">قائمة المواد</h3>
        </div>

        @if($materials->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المادة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المخزون</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوحدة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المورد</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($materials as $material)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="ri-box-3-line text-blue-600"></i>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                                    @if($material->brand)
                                    <div class="text-sm text-gray-500">{{ $material->brand }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $material->getCategoryNameAttribute() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <span class="font-medium">{{ number_format($material->current_stock) }}</span>
                                @if($material->isLowStock())
                                    <i class="ri-error-warning-line text-red-500 mr-1" title="مخزون منخفض"></i>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $material->unit ?: $material->unit_of_measure }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $material->supplier_name ?: 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($material->unit_price)
                                {{ number_format($material->unit_price, 2) }} ريال
                            @else
                                غير محدد
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $material->status == 'active' ? 'bg-green-100 text-green-800' :
                                   ($material->status == 'out_of_stock' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $material->getStatusTextAttribute() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="javascript:editMaterial('{{ $material->id }}', '{{ $material->name }}', '{{ $material->unit }}')"
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <form action="{{ route('settings.materials.destroy', $material) }}" method="POST" class="inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
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
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $materials->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="ri-box-3-line text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مواد مسجلة</h3>
            <p class="text-gray-500 mb-6">ابدأ بإضافة المادة الأولى للمخزون</p>
            <button onclick="openAddMaterialModal()"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                إضافة مادة جديدة
            </button>
        </div>
        @endif
    </div>

    <!-- Global Functions for Modal -->
    <script>
        const existingMaterials = {!! json_encode(\App\Models\Material::pluck('name')->toArray()) !!};

        function openAddMaterialModal() {
            document.getElementById('add-material-modal').classList.remove('hidden');
            document.getElementById('modal-title').textContent = 'إضافة مادة جديدة';
            document.getElementById('submit-text').textContent = 'حفظ المادة';
            document.getElementById('add-material-form').action = '{{ route('settings.materials.store') }}';
            document.getElementById('form-method').value = '';
            clearMaterialForm();
            clearMaterialErrors();
        }

        function closeAddMaterialModal() {
            document.getElementById('add-material-modal').classList.add('hidden');
            clearMaterialForm();
            clearMaterialErrors();
        }

        function clearMaterialForm() {
            document.getElementById('material-id').value = '';
            document.getElementById('material-name').value = '';
            document.getElementById('material-unit').value = '';
            document.getElementById('material-category').value = '';
            document.getElementById('material-status').value = 'active';
            document.getElementById('material-description').value = '';
        }

        function clearMaterialErrors() {
            document.querySelectorAll('[id$="-error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.add('border-gray-300');
            });
        }

        function editMaterial(id, name, unit) {
            document.getElementById('material-id').value = id;
            document.getElementById('material-name').value = name;
            document.getElementById('material-unit').value = unit;
            document.getElementById('modal-title').textContent = 'تعديل المادة';
            document.getElementById('submit-text').textContent = 'تحديث المادة';
            document.getElementById('add-material-form').action = '/settings/materials/' + id;
            document.getElementById('form-method').value = 'PUT';
            clearMaterialErrors();
            document.getElementById('add-material-modal').classList.remove('hidden');
        }
    </script>

    <!-- Add Material Modal -->
    <div id="add-material-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
                    <h3 class="text-lg font-semibold text-gray-900" id="modal-title">إضافة مادة جديدة</h3>
                    <button onclick="closeAddMaterialModal()"
                        class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <!-- Form -->
                <form id="add-material-form" method="POST" action="{{ route('settings.materials.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" id="material-id" name="material_id">
                    <input type="hidden" id="form-method" name="_method" value="">

                    <!-- Name Field -->
                    <div>
                        <label for="material-name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المادة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="material-name" name="name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="أدخل اسم المادة الفريد" required>
                        <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Unit Dropdown -->
                    <div>
                        <label for="material-unit" class="block text-sm font-medium text-gray-700 mb-2">
                            وحدة القياس <span class="text-red-500">*</span>
                        </label>
                        <select id="material-unit" name="unit"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            required>
                            <option value="">اختر وحدة القياس</option>
                            @foreach(\App\Models\MaterialUnit::all() as $unit)
                                <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        <div id="unit-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="material-category" class="block text-sm font-medium text-gray-700 mb-2">
                            الفئة <span class="text-red-500">*</span>
                        </label>
                        <select id="material-category" name="category"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            required>
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
                        <label for="material-status" class="block text-sm font-medium text-gray-700 mb-2">
                            الحالة <span class="text-red-500">*</span>
                        </label>
                        <select id="material-status" name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            required>
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
                        <label for="material-description" class="block text-sm font-medium text-gray-700 mb-2">
                            الوصف
                        </label>
                        <textarea id="material-description" name="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="أدخل وصف المادة (اختياري)"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeAddMaterialModal()"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit" id="submit-btn"
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                            <span id="submit-text">حفظ المادة</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Form submission
document.getElementById('add-material-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const name = document.getElementById('material-name').value.trim();
    const unit = document.getElementById('material-unit').value;
    const category = document.getElementById('material-category').value;
    const status = document.getElementById('material-status').value;
    const materialId = document.getElementById('material-id').value;

    clearMaterialErrors();
    let isValid = true;

    // Validate name is not empty
    if (!name) {
        document.getElementById('name-error').textContent = 'اسم المادة مطلوب';
        document.getElementById('name-error').classList.remove('hidden');
        document.getElementById('material-name').classList.add('border-red-500');
        isValid = false;
    }
    // Validate name is unique (for new materials only)
    else if (!materialId && existingMaterials.includes(name)) {
        document.getElementById('name-error').textContent = 'هذا الاسم موجود بالفعل، يرجى اختيار اسم مختلف';
        document.getElementById('name-error').classList.remove('hidden');
        document.getElementById('material-name').classList.add('border-red-500');
        isValid = false;
    }

    if (!unit) {
        document.getElementById('unit-error').textContent = 'وحدة القياس مطلوبة';
        document.getElementById('unit-error').classList.remove('hidden');
        document.getElementById('material-unit').classList.add('border-red-500');
        isValid = false;
    }

    if (!category) {
        document.getElementById('category-error').textContent = 'الفئة مطلوبة';
        document.getElementById('category-error').classList.remove('hidden');
        document.getElementById('material-category').classList.add('border-red-500');
        isValid = false;
    }

    if (!status) {
        document.getElementById('status-error').textContent = 'الحالة مطلوبة';
        document.getElementById('status-error').classList.remove('hidden');
        document.getElementById('material-status').classList.add('border-red-500');
        isValid = false;
    }

    if (!isValid) return;

    // Submit form via AJAX
    const formData = new FormData(this);
    const submitBtn = document.getElementById('submit-btn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'جاري الحفظ...';

    fetch(this.action, {
        method: this.getAttribute('method') === 'POST' ? 'POST' : 'POST',
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
            for (const [field, messages] of Object.entries(data.errors)) {
                const errorEl = document.getElementById(field + '-error');
                const inputEl = document.getElementById('material-' + field);
                if (errorEl && inputEl) {
                    errorEl.textContent = messages[0];
                    errorEl.classList.remove('hidden');
                    inputEl.classList.add('border-red-500');
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الحفظ');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

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
