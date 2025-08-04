@extends('layouts.app')

@section('title', 'إدارة الموردين - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة الموردين</h1>
                <p class="text-gray-600">إدارة معلومات الموردين وشروط التعامل معهم</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                <a href="{{ route('suppliers.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-all">
                    <i class="ri-add-line ml-2"></i>
                    إضافة مورد جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-100">موردين نشطين</p>
                            <p class="text-2xl font-bold">{{ $allSuppliers->where('status', 'نشط')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="ri-check-line text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-100">موردين معلقين</p>
                            <p class="text-2xl font-bold">{{ $allSuppliers->where('status', 'معلق')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="ri-pause-line text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100">دفع نقدي</p>
                            <p class="text-2xl font-bold">{{ $allSuppliers->where('payment_terms', 'نقدي')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="ri-money-dollar-circle-line text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-100">إجمالي الموردين</p>
                            <p class="text-2xl font-bold">{{ $allSuppliers->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="ri-truck-line text-xl"></i>
                        </div>
                    </div>
                </div>
                            </div>
            </div>

            <!-- Search and Filter Form -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <form method="GET" action="{{ route('suppliers.index') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                            <div class="relative">
                                <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                                       id="search" name="search" value="{{ request('search') }}"
                                       placeholder="ابحث في الاسم، الشركة، البريد، الهاتف...">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">الفئة</label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    id="category" name="category">
                                <option value="">جميع الفئات</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    id="status" name="status">
                                <option value="">جميع الحالات</option>
                                <option value="نشط" {{ request('status') == 'نشط' ? 'selected' : '' }}>نشط</option>
                                <option value="غير نشط" {{ request('status') == 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
                                <option value="معلق" {{ request('status') == 'معلق' ? 'selected' : '' }}>معلق</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <div class="flex space-x-2 space-x-reverse w-full">
                                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    <i class="ri-search-line ml-1"></i>
                                    فلترة
                                </button>
                                <a href="{{ route('suppliers.index') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium text-center">
                                    <i class="ri-close-line ml-1"></i>
                                    مسح
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                    <div class="flex items-center">
                        <i class="ri-check-line text-green-600 ml-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Suppliers Table -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المورد</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">معلومات الشركة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">التواصل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">شروط الدفع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($suppliers as $supplier)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                                    <i class="ri-truck-line text-white"></i>
                                                </div>
                                            </div>
                                            <div class="mr-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                                                @if($supplier->contact_person)
                                                    <div class="text-sm text-gray-500">{{ $supplier->contact_person }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <div class="text-sm text-gray-900">{{ $supplier->company_name ?? '-' }}</div>
                                        @if($supplier->cr_number)
                                            <div class="text-sm text-gray-500">س.ت: {{ $supplier->cr_number }}</div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                        @if($supplier->email)
                                            <div class="text-sm text-blue-600 hover:text-blue-800">
                                                <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                            </div>
                                        @endif
                                        <div class="text-sm text-gray-900">
                                            <a href="tel:{{ $supplier->phone }}" class="hover:text-blue-600">{{ $supplier->phone }}</a>
                                        </div>
                                        @if($supplier->phone_2)
                                            <div class="text-sm text-gray-500">
                                                <a href="tel:{{ $supplier->phone_2 }}" class="hover:text-blue-600">{{ $supplier->phone_2 }}</a>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($supplier->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $supplier->category }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($supplier->payment_terms == 'نقدي') bg-green-100 text-green-800
                                            @elseif($supplier->payment_terms == 'آجل 30 يوم') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ $supplier->payment_terms }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($supplier->status == 'نشط') bg-green-100 text-green-800
                                            @elseif($supplier->status == 'معلق') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $supplier->status }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <a href="{{ route('suppliers.show', $supplier) }}"
                                               class="text-blue-600 hover:text-blue-900 p-1 rounded-lg hover:bg-blue-50 transition-colors"
                                               title="عرض">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('suppliers.edit', $supplier) }}"
                                               class="text-yellow-600 hover:text-yellow-900 p-1 rounded-lg hover:bg-yellow-50 transition-colors"
                                               title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button type="button"
                                                    class="text-red-600 hover:text-red-900 p-1 rounded-lg hover:bg-red-50 transition-colors"
                                                    title="حذف"
                                                    onclick="confirmDelete({{ $supplier->id }})">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-{{ $supplier->id }}"
                                              action="{{ route('suppliers.destroy', $supplier) }}"
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="ri-truck-line text-gray-400 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد موردين</h3>
                                            <p class="text-gray-500 mb-4">ابدأ بإضافة موردين جدد للنظام</p>
                                            <a href="{{ route('suppliers.create') }}"
                                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                                <i class="ri-add-line ml-2"></i>
                                                إضافة مورد جديد
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($suppliers->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex justify-center">
                            {{ $suppliers->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف هذا المورد نهائياً ولا يمكن التراجع عن هذا الإجراء',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف المورد',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

// Auto-submit form on filter changes
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('#category, #status');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Show success messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'تم بنجاح!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
