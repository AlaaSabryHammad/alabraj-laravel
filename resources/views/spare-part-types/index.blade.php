@extends('layouts.app')

@section('title', 'أنواع قطع الغيار - شركة الأبراج للمقاولات')

@section('content')
<div class="space-y-6" dir="rtl">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">أنواع قطع الغيار</h1>
                <p class="text-gray-600">إدارة شاملة لأنواع قطع الغيار والمكونات</p>
            </div>
            <a href="{{ route('spare-part-types.create') }}"
               class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center">
                <i class="ri-add-line ml-2"></i>
                إضافة نوع جديد
            </a>
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">إجمالي الأنواع</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $sparePartTypes->total() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl">
                    <i class="ri-box-line text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">الأنواع النشطة</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $sparePartTypes->where('is_active', true)->count() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl">
                    <i class="ri-check-double-line text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium mb-1">إجمالي القطع</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $sparePartTypes->sum(function($t) { return $t->spareParts->count(); }) }}</h3>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-3 rounded-xl">
                    <i class="ri-tools-line text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">قائمة الأنواع</h3>
        </div>

        @if ($sparePartTypes->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">عدد القطع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($sparePartTypes as $type)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-100">
                                    <i class="ri-box-line text-blue-600"></i>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $type->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $type->category_label }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600">{{ Str::limit($type->description ?? 'لا يوجد وصف', 50) }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if ($type->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="ri-check-line ml-1"></i>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="ri-close-line ml-1"></i>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">
                                {{ $type->spareParts->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 space-x-reverse justify-end">
                                <a href="{{ route('spare-part-types.edit', $type) }}"
                                   class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                    <i class="ri-edit-line text-lg"></i>
                                </a>
                                @if ($type->spareParts->count() == 0)
                                    <form method="POST"
                                          action="{{ route('spare-part-types.destroy', $type) }}"
                                          class="inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا النوع؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="ri-delete-bin-line text-lg"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed" title="لا يمكن الحذف - يوجد قطع مرتبطة">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $sparePartTypes->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="ri-box-line text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد أنواع قطع غيار</h3>
            <p class="text-gray-500 mb-6">ابدأ بإضافة أول نوع من قطع الغيار</p>
            <a href="{{ route('spare-part-types.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center">
                <i class="ri-add-line ml-2"></i>
                إضافة النوع الأول
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
