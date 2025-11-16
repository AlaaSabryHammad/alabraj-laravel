@extends('layouts.app')

@section('title', 'جهات الصرف - الإعدادات')

@section('content')
    <div class="space-y-6">
        <!-- Header with Breadcrumb and Back Button -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-2 space-x-reverse mb-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="ri-home-line"></i>
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('settings.index') }}"
                        class="text-gray-600 hover:text-purple-600 transition-colors">الإعدادات</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-purple-600 font-medium">جهات الصرف</span>
                </div>
                <div class="flex items-center space-x-reverse space-x-4">
                    <a href="{{ route('settings.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">جهات الصرف</h1>
                </div>
                <p class="text-gray-600 mt-2">إدارة شاملة لجهات الصرف والموردين والمقاولين</p>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div
                    class="w-24 h-24 bg-gradient-to-r from-purple-500 via-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-building-2-line text-white text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $entities->total() }}</div>
                    <div class="text-gray-600 mt-2">إجمالي جهات الصرف</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $entities->where('status', 'active')->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">جهات فعّالة</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $entities->where('status', 'inactive')->count() }}
                    </div>
                    <div class="text-gray-600 mt-2">جهات غير فعّالة</div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Header with Add Button -->
            <div class="flex items-center justify-between mb-6 p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">قائمة جهات الصرف</h2>
                <button onclick="openExpenseEntityModal()"
                    class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center">
                    <i class="ri-add-line ml-2"></i>
                    <span>إضافة جهة صرف جديدة</span>
                </button>
            </div>

            <!-- Table -->
            @if ($entities->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الاسم</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">النوع</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">البريد الإلكتروني</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الهاتف</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الحالة</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($entities as $entity)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div
                                                class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-building-2-line text-purple-600"></i>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $entity->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($entity->type === 'supplier')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="ri-shopping-cart-line ml-1"></i>
                                                مورد
                                            </span>
                                        @elseif($entity->type === 'contractor')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                <i class="ri-hammer-line ml-1"></i>
                                                مقاول
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                أخرى
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="text-sm">{{ $entity->email ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="text-sm">{{ $entity->phone ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($entity->status === 'active')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                                <i class="ri-check-line ml-1"></i>
                                                فعّال
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="ri-close-line ml-1"></i>
                                                غير فعّال
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2 space-x-reverse">
                                            <button
                                                onclick="editExpenseEntity({{ $entity->id }}, '{{ $entity->name }}', '{{ $entity->type }}', '{{ $entity->email }}', '{{ $entity->phone }}', '{{ $entity->status }}')"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button
                                                onclick="deleteExpenseEntity({{ $entity->id }}, '{{ $entity->name }}')"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="حذف">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($entities->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $entities->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-building-2-line text-purple-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد جهات صرف حالياً</h3>
                    <p class="text-gray-600 mb-6 text-center max-w-md">ابدأ بإضافة جهة صرف جديدة لإدارة الموردين والمقاولين
                    </p>
                    <button onclick="openExpenseEntityModal()"
                        class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-3 rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center">
                        <i class="ri-add-line ml-2"></i>
                        <span>إضافة جهة صرف جديدة</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="expenseEntityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto"
            style="border-top: 4px solid #a855f7;">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h2 id="modalTitle" class="text-xl font-bold text-gray-900">إضافة جهة صرف جديدة</h2>
                <button onclick="closeExpenseEntityModal()"
                    class="text-gray-400 hover:text-gray-600 text-2xl font-light">&times;</button>
            </div>

            <form id="expenseEntityForm" method="POST" action="{{ route('expense-entities.store') }}"
                class="p-6 space-y-5">
                @csrf
                <input type="hidden" id="expenseEntityId" name="id" value="">
                <input type="hidden" id="expenseEntityMethod" name="_method" value="POST">

                <div>
                    <label for="expenseEntityName" class="block text-sm font-medium text-gray-700 mb-2">
                        اسم الجهة <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="expenseEntityName" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label for="expenseEntityType" class="block text-sm font-medium text-gray-700 mb-2">
                        نوع الجهة <span class="text-red-500">*</span>
                    </label>
                    <select id="expenseEntityType" name="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">-- اختر النوع --</option>
                        <option value="supplier">مورد</option>
                        <option value="contractor">مقاول</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>

                <div>
                    <label for="expenseEntityEmail" class="block text-sm font-medium text-gray-700 mb-2">
                        البريد الإلكتروني
                    </label>
                    <input type="email" id="expenseEntityEmail" name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label for="expenseEntityPhone" class="block text-sm font-medium text-gray-700 mb-2">
                        الهاتف
                    </label>
                    <input type="tel" id="expenseEntityPhone" name="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label for="expenseEntityStatus" class="block text-sm font-medium text-gray-700 mb-2">
                        الحالة <span class="text-red-500">*</span>
                    </label>
                    <select id="expenseEntityStatus" name="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeExpenseEntityModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 font-medium">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteExpenseEntityModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4" style="border-top: 4px solid #dc2626;">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">تأكيد الحذف</h2>
                <button onclick="closeDeleteExpenseEntityModal()"
                    class="text-gray-400 hover:text-gray-600 text-2xl font-light">&times;</button>
            </div>

            <div class="p-6">
                <p class="text-gray-700 mb-2">
                    هل أنت متأكد من حذف جهة الصرف <strong id="deleteExpenseEntityName"></strong>؟
                </p>
                <p class="text-red-600 text-sm font-medium">هذا الإجراء لا يمكن التراجع عنه</p>
            </div>

            <div class="flex gap-3 p-6 border-t border-gray-100">
                <button type="button" onclick="closeDeleteExpenseEntityModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    إلغاء
                </button>
                <button type="button" onclick="confirmDeleteExpenseEntity()"
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                    حذف
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentExpenseEntityId = null;

        function openExpenseEntityModal() {
            clearExpenseEntityForm();
            document.getElementById('modalTitle').textContent = 'إضافة جهة صرف جديدة';
            document.getElementById('expenseEntityMethod').value = 'POST';
            document.getElementById('expenseEntityForm').action = '{{ route('expense-entities.store') }}';
            document.getElementById('expenseEntityModal').style.display = 'flex';
        }

        function closeExpenseEntityModal() {
            document.getElementById('expenseEntityModal').style.display = 'none';
            clearExpenseEntityForm();
        }

        function clearExpenseEntityForm() {
            document.getElementById('expenseEntityForm').reset();
            document.getElementById('expenseEntityId').value = '';
            document.getElementById('expenseEntityStatus').value = 'active';
        }

        function editExpenseEntity(id, name, type, email, phone, status) {
            currentExpenseEntityId = id;
            document.getElementById('modalTitle').textContent = 'تحرير جهة الصرف';
            document.getElementById('expenseEntityId').value = id;
            document.getElementById('expenseEntityName').value = name;
            document.getElementById('expenseEntityType').value = type;
            document.getElementById('expenseEntityEmail').value = email;
            document.getElementById('expenseEntityPhone').value = phone;
            document.getElementById('expenseEntityStatus').value = status;
            document.getElementById('expenseEntityMethod').value = 'PUT';
            document.getElementById('expenseEntityForm').action = `/expense-entities/${id}`;
            document.getElementById('expenseEntityModal').style.display = 'flex';
        }

        function deleteExpenseEntity(id, name) {
            currentExpenseEntityId = id;
            document.getElementById('deleteExpenseEntityName').textContent = name;
            document.getElementById('deleteExpenseEntityModal').style.display = 'flex';
        }

        function closeDeleteExpenseEntityModal() {
            document.getElementById('deleteExpenseEntityModal').style.display = 'none';
        }

        function confirmDeleteExpenseEntity() {
            if (!currentExpenseEntityId) return;

            fetch(`/expense-entities/${currentExpenseEntityId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ في الاتصال');
                });

            closeDeleteExpenseEntityModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('expenseEntityModal');
            let deleteModal = document.getElementById('deleteExpenseEntityModal');

            if (event.target === modal) {
                closeExpenseEntityModal();
            }
            if (event.target === deleteModal) {
                closeDeleteExpenseEntityModal();
            }
        }
    </script>
@endsection
