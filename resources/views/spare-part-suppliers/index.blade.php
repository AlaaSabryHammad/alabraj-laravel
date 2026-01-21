@extends('layouts.app')

@section('title', 'موردو قطع الغيار')

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
                <span class="text-blue-600 font-medium">موردو قطع الغيار</span>
            </div>
            <div class="flex items-center space-x-reverse space-x-4">
                <a href="{{ route('settings.index') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line ml-2"></i>
                    العودة
                </a>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">موردو قطع الغيار</h1>
            </div>
            <p class="text-gray-600 mt-2">إدارة موردي قطع الغيار والقطع المصنعة</p>
        </div>
        <div class="hidden md:flex items-center justify-center">
            <div
                class="w-24 h-24 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="ri-store-3-line text-white text-4xl"></i>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
            <i class="ri-check-circle-line text-green-600 text-2xl"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
            <i class="ri-close-circle-line text-red-600 text-2xl"></i>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Add Button and Filters -->
    <div class="flex items-center justify-between">
        <div></div>
        <a href="{{ route('spare-part-suppliers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors font-medium">
            <i class="ri-add-line"></i>
            إضافة مورد جديد
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث عن المورد..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="ri-search-line"></i>
                    </button>
                </form>
            </div>
            <div>
                <select name="status" onchange="document.location = '?status=' + this.value + (document.querySelector('[name=search]').value ? '&search=' + document.querySelector('[name=search]').value : '');" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">جميع الحالات</option>
                    <option value="نشط" {{ request('status') === 'نشط' ? 'selected' : '' }}>نشط</option>
                    <option value="غير نشط" {{ request('status') === 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <div class="text-sm text-gray-600 flex items-center">
                إجمالي الموردين: <span class="font-semibold">{{ $suppliers->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الاسم</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">اسم الشركة</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الهاتف</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الحالة</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                            {{ $supplier->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $supplier->company_name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $supplier->email ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $supplier->phone ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($supplier->status === 'نشط')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="w-2 h-2 bg-gray-500 rounded-full ml-2"></span>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('spare-part-suppliers.show', $supplier) }}" class="text-blue-600 hover:text-blue-900 transition-colors" title="عرض">
                                    <i class="ri-eye-line text-lg"></i>
                                </a>
                                <a href="{{ route('spare-part-suppliers.edit', $supplier) }}" class="text-yellow-600 hover:text-yellow-900 transition-colors" title="تعديل">
                                    <i class="ri-edit-line text-lg"></i>
                                </a>
                                <button onclick="deleteSupplier({{ $supplier->id }}, '{{ $supplier->name }}')" class="text-red-600 hover:text-red-900 transition-colors" title="حذف">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <i class="ri-store-3-line text-4xl text-gray-300"></i>
                                <p class="text-lg">لا توجد موردين</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $suppliers->links() }}
    </div>
</div>

<!-- Delete Supplier Modal -->
<div id="delete-supplier-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="ri-delete-bin-line text-red-600 text-xl"></i>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">حذف المورد</h3>
                <p class="text-gray-600 text-center mb-6">هل أنت متأكد من حذف هذا المورد؟ لا يمكن التراجع عن هذا الإجراء.</p>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center">
                            <i class="ri-truck-line text-cyan-600"></i>
                        </div>
                        <div class="mr-3">
                            <div class="text-sm font-medium text-gray-900" id="delete-supplier-name">اسم المورد</div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteSupplierModal()"
                        class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-colors">
                        إلغاء
                    </button>
                    <button type="button" onclick="confirmDeleteSupplier()" id="confirm-delete-btn"
                        class="flex-1 px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200">
                        حذف
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteSupplier(id, name = '') {
        window.supplierToDelete = { id, name };
        document.getElementById('delete-supplier-name').textContent = name || 'مورد غير محدد';
        document.getElementById('delete-supplier-modal').classList.remove('hidden');
    }

    function closeDeleteSupplierModal() {
        document.getElementById('delete-supplier-modal').classList.add('hidden');
        window.supplierToDelete = null;
    }

    function confirmDeleteSupplier() {
        if (!window.supplierToDelete) return;

        const id = window.supplierToDelete.id;
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        if (!csrfToken) {
            alert('لم يتم العثور على رمز CSRF');
            return;
        }

        const confirmBtn = document.getElementById('confirm-delete-btn');
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = 'جاري الحذف...';
        confirmBtn.disabled = true;

        fetch(`/spare-part-suppliers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeDeleteSupplierModal();
                location.reload();
            } else {
                alert('حدث خطأ أثناء الحذف');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال');
        })
        .finally(() => {
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = false;
        });
    }

    // Handle modal close on background click
    document.getElementById('delete-supplier-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteSupplierModal();
    });
</script>
@endsection
