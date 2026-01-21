@extends('layouts.app')

@section('title', 'كيانات الإيرادات - الإعدادات')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumb and Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 space-x-reverse mb-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="ri-home-line"></i>
                </a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                    الإعدادات
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-blue-600 font-medium">كيانات الإيرادات</span>
            </div>
            <div class="flex items-center space-x-reverse space-x-4">
                <a href="{{ route('settings.index') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة
                </a>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">كيانات الإيرادات</h1>
            </div>
            <p class="text-gray-600 mt-2">إدارة الكيانات المختلفة المرتبطة بالإيرادات</p>
        </div>
        <div class="hidden md:flex items-center justify-center">
            <div class="w-24 h-24 bg-gradient-to-r from-emerald-500 via-green-600 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="ri-building-line text-white text-4xl"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="ri-building-4-line text-white text-3xl"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-white mb-2">{{ $entities->total() }}</div>
            <div class="text-emerald-100 font-medium">إجمالي الكيانات</div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="ri-checkbox-circle-line text-white text-3xl"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-white mb-2">{{ $entities->where('status', 'active')->count() }}</div>
            <div class="text-green-100 font-medium">الكيانات النشطة</div>
        </div>

        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="ri-file-list-3-line text-white text-3xl"></i>
                </div>
            </div>
            <div class="text-4xl font-bold text-white mb-2">{{ $entities->currentPage() }}</div>
            <div class="text-teal-100 font-medium">الصفحة الحالية</div>
        </div>
    </div>

    <!-- Add Button -->
    <div class="flex items-center justify-between">
        <div></div>
        <button onclick="openRevenueEntityModal()"
                class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
            <i class="ri-add-line text-xl"></i>
            إضافة كيان إيراد جديد
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if ($entities->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">اسم الكيان</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">نوع الإيراد</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">الحالة</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">التاريخ</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entities as $entity)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <i class="ri-building-line text-emerald-600"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $entity->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $entity->revenueType?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($entity->status === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
                                        نشط
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                        <span class="w-2 h-2 bg-gray-500 rounded-full ml-2"></span>
                                        غير نشط
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                {{ $entity->created_at->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2 space-x-reverse">
                                    <button onclick="editRevenueEntity({{ $entity->id }}, '{{ $entity->name }}', '{{ $entity->type }}', {{ $entity->revenue_type_id ?? 'null' }}, '{{ $entity->status }}')"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="تعديل">
                                        <i class="ri-edit-line text-lg"></i>
                                    </button>
                                    <button onclick="deleteRevenueEntity({{ $entity->id }}, '{{ $entity->name }}')"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="حذف">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            @if ($entities->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $entities->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-16 px-6">
                <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mb-6">
                    <i class="ri-building-line text-emerald-600 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">لا توجد كيانات إيرادات</h3>
                <p class="text-gray-600 mb-6 text-center max-w-md">ابدأ بإضافة كيان إيراد جديد لإدارة الكيانات المختلفة</p>
                <button onclick="openRevenueEntityModal()"
                        class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-8 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
                    <i class="ri-add-line text-xl"></i>
                    إضافة كيان إيراد
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="revenueEntityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4 animate-slide-up">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">إضافة كيان إيراد جديد</h2>
                <button onclick="closeRevenueEntityModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="revenueEntityForm" method="POST" action="{{ route('settings.revenue-entities.store') }}" class="p-6 space-y-6">
                @csrf
                <input type="hidden" id="revenueEntityId" name="id" value="">
                <input type="hidden" id="revenueEntityMethod" name="_method" value="POST">

                <div>
                    <label for="revenueEntityName" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="ri-building-2-line ml-1 text-emerald-600"></i>
                        اسم الكيان <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="revenueEntityName"
                           name="name"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                           placeholder="أدخل اسم الكيان">
                </div>

                <div>
                    <label for="revenueEntityType" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="ri-building-line ml-1 text-emerald-600"></i>
                        نوع الجهة <span class="text-red-500">*</span>
                    </label>
                    <select id="revenueEntityType"
                            name="type"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                        <option value="">-- اختر نوع الجهة --</option>
                        <option value="government">جهة حكومية</option>
                        <option value="company">شركة</option>
                        <option value="client">عميل</option>
                        <option value="individual">فرد</option>
                    </select>
                </div>

                <div>
                    <label for="revenueEntityTypeId" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="ri-price-tag-3-line ml-1 text-emerald-600"></i>
                        نوع الإيراد <span class="text-red-500">*</span>
                    </label>
                    <select id="revenueEntityTypeId"
                            name="revenue_type_id"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                        <option value="">-- اختر نوع الإيراد --</option>
                        @forelse ($revenueTypes ?? [] as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @empty
                            <option value="" disabled>لا توجد أنواع إيرادات نشطة</option>
                        @endforelse
                    </select>
                </div>

                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                    <input type="checkbox"
                           id="revenueEntityActive"
                           checked
                           onchange="document.getElementById('revenueEntityStatus').value = this.checked ? 'active' : 'inactive'"
                           class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                    <label for="revenueEntityActive" class="text-sm font-semibold text-gray-700 cursor-pointer">
                        <i class="ri-checkbox-circle-line ml-1 text-emerald-600"></i>
                        الكيان نشط
                    </label>
                </div>
                <input type="hidden" id="revenueEntityStatus" name="status" value="active">
                <input type="hidden" name="contact_person" value="">
                <input type="hidden" name="phone" value="">
                <input type="hidden" name="email" value="">
                <input type="hidden" name="address" value="">
                <input type="hidden" name="tax_number" value="">
                <input type="hidden" name="commercial_record" value="">

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button"
                            onclick="closeRevenueEntityModal()"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl font-medium hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-lg">
                        <i class="ri-save-line ml-1"></i>
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteRevenueEntityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 animate-slide-up">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="ri-delete-bin-line text-red-600 text-3xl"></i>
                </div>

                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">حذف كيان الإيراد</h3>
                <p class="text-gray-600 text-center mb-6">
                    هل أنت متأكد من حذف كيان الإيراد
                    <strong id="deleteRevenueEntityName" class="text-gray-900"></strong>؟
                    <br>
                    <span class="text-red-600 font-semibold text-sm">لا يمكن التراجع عن هذا الإجراء</span>
                </p>

                <div class="flex gap-3">
                    <button type="button"
                            onclick="closeDeleteRevenueEntityModal()"
                            class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                        إلغاء
                    </button>
                    <button type="button"
                            onclick="confirmDeleteRevenueEntity()"
                            class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg">
                        <i class="ri-delete-bin-line ml-1"></i>
                        حذف
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes slide-up {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-slide-up {
        animation: slide-up 0.3s ease-out;
    }
</style>

<script>
    let currentRevenueEntityId = null;

    function openRevenueEntityModal() {
        clearRevenueEntityForm();
        document.getElementById('modalTitle').textContent = 'إضافة كيان إيراد جديد';
        document.getElementById('revenueEntityMethod').value = 'POST';
        document.getElementById('revenueEntityForm').action = '{{ route('settings.revenue-entities.store') }}';
        document.getElementById('revenueEntityModal').classList.remove('hidden');
    }

    function closeRevenueEntityModal() {
        document.getElementById('revenueEntityModal').classList.add('hidden');
        clearRevenueEntityForm();
    }

    function clearRevenueEntityForm() {
        document.getElementById('revenueEntityForm').reset();
        document.getElementById('revenueEntityId').value = '';
        document.getElementById('revenueEntityType').value = '';
        document.getElementById('revenueEntityTypeId').value = '';
        document.getElementById('revenueEntityActive').checked = true;
        document.getElementById('revenueEntityStatus').value = 'active';
    }

    function editRevenueEntity(id, name, type, typeId, status) {
        currentRevenueEntityId = id;
        document.getElementById('modalTitle').textContent = 'تعديل كيان الإيراد';
        document.getElementById('revenueEntityId').value = id;
        document.getElementById('revenueEntityName').value = name;
        if (type) {
            document.getElementById('revenueEntityType').value = type;
        }
        if (typeId) {
            document.getElementById('revenueEntityTypeId').value = typeId;
        }
        const isActive = status === 'active';
        document.getElementById('revenueEntityActive').checked = isActive;
        document.getElementById('revenueEntityStatus').value = status || 'active';
        document.getElementById('revenueEntityMethod').value = 'PUT';
        document.getElementById('revenueEntityForm').action = `/settings/revenue-entities/${id}`;
        document.getElementById('revenueEntityModal').classList.remove('hidden');
    }

    function deleteRevenueEntity(id, name) {
        currentRevenueEntityId = id;
        document.getElementById('deleteRevenueEntityName').textContent = name;
        document.getElementById('deleteRevenueEntityModal').classList.remove('hidden');
    }

    function closeDeleteRevenueEntityModal() {
        document.getElementById('deleteRevenueEntityModal').classList.add('hidden');
    }

    function confirmDeleteRevenueEntity() {
        if (!currentRevenueEntityId) return;

        fetch(`/settings/revenue-entities/${currentRevenueEntityId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    closeDeleteRevenueEntityModal();
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء الحذف');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال');
            });
    }

    // Close modal when clicking outside
    document.getElementById('revenueEntityModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeRevenueEntityModal();
    });

    document.getElementById('deleteRevenueEntityModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteRevenueEntityModal();
    });
</script>
@endsection
