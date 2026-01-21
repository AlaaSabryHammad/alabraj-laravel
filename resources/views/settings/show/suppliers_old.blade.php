@extends('layouts.app')

@section('title', 'موردي قطع الغيار - الإعدادات')

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
                    <a href="{{ route('settings.index') }}"
                        class="text-gray-600 hover:text-blue-600 transition-colors">الإعدادات</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-blue-600 font-medium">الموردون</span>
                </div>
                <div class="flex items-center space-x-reverse space-x-4">
                    <a href="{{ route('settings.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="ri-arrow-right-line ml-2"></i>
                        العودة
                    </a>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">موردي قطع الغيار</h1>
                </div>
                <p class="text-gray-600 mt-2">إدارة الموردين والشركات المزودة للقطع والمواد</p>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div
                    class="w-24 h-24 bg-gradient-to-r from-cyan-500 via-cyan-600 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-truck-line text-white text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-cyan-600">{{ $suppliers->total() }}</div>
                    <div class="text-gray-600 mt-2">إجمالي الموردين</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $suppliers->where('is_active', true)->count() }}</div>
                    <div class="text-gray-600 mt-2">موردين فعّالين</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $suppliers->where('is_active', false)->count() }}</div>
                    <div class="text-gray-600 mt-2">موردين غير فعّالين</div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Header with Add Button -->
            <div class="flex items-center justify-between mb-6 p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">قائمة الموردين</h2>
                <a href="{{ route('suppliers.create') }}"
                    class="bg-gradient-to-r from-cyan-600 to-cyan-700 text-white px-6 py-3 rounded-xl font-medium hover:from-cyan-700 hover:to-cyan-800 transition-all duration-200 flex items-center">
                    <i class="ri-add-line ml-2"></i>
                    <span>إضافة مورد جديد</span>
                </a>
            </div>

            <!-- Table -->
            @if ($suppliers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الاسم</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">البريد الإلكتروني</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الهاتف</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الحالة</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">التاريخ</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($suppliers as $supplier)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-truck-line text-cyan-600"></i>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $supplier->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="text-sm">{{ $supplier->email ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600 text-sm">
                                        {{ $supplier->phone ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($supplier->is_active)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
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
                                    <td class="px-6 py-4 text-center text-gray-600 text-sm">
                                        {{ $supplier->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2 space-x-reverse">
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="تعديل">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <a href="{{ route('suppliers.show', $supplier->id) }}"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                title="عرض">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                                style="display: inline;"
                                                onclick="return confirm('هل تريد حذف هذا المورد؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="حذف">
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
                @if ($suppliers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $suppliers->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <div class="w-20 h-20 bg-cyan-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-truck-line text-cyan-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">لا توجد موردين حالياً</h3>
                    <p class="text-gray-600 mb-6 text-center max-w-md">ابدأ بإضافة مورد جديد لإدارة الموردين والشركات</p>
                    <a href="{{ route('suppliers.create') }}"
                        class="bg-gradient-to-r from-cyan-600 to-cyan-700 text-white px-8 py-3 rounded-xl font-medium hover:from-cyan-700 hover:to-cyan-800 transition-all duration-200 flex items-center">
                        <i class="ri-add-line ml-2"></i>
                        <span>إضافة مورد</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
