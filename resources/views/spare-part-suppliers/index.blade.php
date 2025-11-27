@extends('layouts.app')

@section('title', 'موردو قطع الغيار')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <i class="ri-store-3-line text-orange-600"></i>
                موردو قطع الغيار
            </h1>
            <p class="text-gray-600">إدارة موردي قطع الغيار والقطع المصنعة</p>
        </div>
        <a href="{{ route('spare-part-suppliers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
            <i class="ri-add-line"></i>
            إضافة مورد جديد
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-center gap-3">
            <i class="ri-check-circle-line text-green-600 text-2xl"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-center gap-3">
            <i class="ri-close-circle-line text-red-600 text-2xl"></i>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
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
                                <form method="POST" action="{{ route('spare-part-suppliers.destroy', $supplier) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المورد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="حذف">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </form>
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
    <div class="mt-6">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
