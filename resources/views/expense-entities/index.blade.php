@extends('layouts.app')

@section('title', 'إدارة جهات الصرف')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">إدارة جهات الصرف</h1>
            <p class="text-gray-600 mt-1">إدارة الموردين والمقاولين وجهات الصرف الأخرى</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('expense-entities.create') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-add-line"></i>
                إضافة جهة جديدة
            </a>
            <a href="{{ route('settings.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-arrow-right-line"></i>
                العودة للإعدادات
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي الجهات</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $expenseEntities->total() }}</p>
                </div>
                <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ri-building-line text-xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">الموردين</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $expenseEntities->where('type', 'supplier')->count() }}</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-truck-line text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">المقاولين</p>
                    <p class="text-2xl font-bold text-green-600">{{ $expenseEntities->where('type', 'contractor')->count() }}</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-hammer-line text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">الجهات النشطة</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $expenseEntities->where('status', 'active')->count() }}</p>
                </div>
                <div class="h-12 w-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="ri-check-line text-xl text-emerald-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Entities Table -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">قائمة جهات الصرف</h2>
        </div>

        @if($expenseEntities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الجهة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                النوع
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الشخص المسؤول
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                معلومات الاتصال
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($expenseEntities as $entity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center ml-3">
                                            <i class="ri-building-line text-purple-600"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $entity->name }}</div>
                                            @if($entity->commercial_record)
                                                <div class="text-xs text-gray-500">س.ت: {{ $entity->commercial_record }}</div>
                                            @endif
                                            @if($entity->tax_number)
                                                <div class="text-xs text-gray-500">ر.ض: {{ $entity->tax_number }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($entity->type === 'supplier') bg-blue-100 text-blue-800
                                        @elseif($entity->type === 'contractor') bg-green-100 text-green-800
                                        @elseif($entity->type === 'government') bg-red-100 text-red-800
                                        @elseif($entity->type === 'bank') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $entity->type_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entity->contact_person ?? 'غير محدد' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($entity->phone)
                                            <div><i class="ri-phone-line ml-1"></i>{{ $entity->phone }}</div>
                                        @endif
                                        @if($entity->email)
                                            <div><i class="ri-mail-line ml-1"></i>{{ $entity->email }}</div>
                                        @endif
                                        @if(!$entity->phone && !$entity->email)
                                            <span class="text-gray-500">غير متوفر</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($entity->status === 'active') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($entity->status === 'active') نشط @else غير نشط @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('expense-entities.show', $entity) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('expense-entities.edit', $entity) }}" 
                                           class="text-green-600 hover:text-green-900 transition-colors">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('expense-entities.destroy', $entity) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الجهة؟\n\nتحذير: لا يمكن التراجع عن هذا الإجراء!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors">
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

            @if($expenseEntities->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $expenseEntities->links() }}
                </div>
            @endif
        @else
            <div class="p-6">
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-4">
                        <i class="ri-building-line text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد جهات صرف</h3>
                    <p class="text-gray-600 mb-4">ابدأ بإضافة أول جهة صرف لإدارة الموردين والمقاولين</p>
                    <a href="{{ route('expense-entities.create') }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors inline-flex items-center gap-2">
                        <i class="ri-add-line"></i>
                        إضافة جهة جديدة
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection