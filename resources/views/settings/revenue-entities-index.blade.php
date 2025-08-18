@extends('layouts.app')

@section('title', 'جهات الإيرادات')

@section('content')
    <div class="p-6" dir="rtl">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">جهات الإيرادات</h1>
                <p class="text-gray-600 mt-1">إدارة جهات مصدر الإيرادات</p>
            </div>
            <a href="{{ route('settings.revenue-entities.create') }}"
                class="ml-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="ri-add-line"></i>
                إضافة جهة جديدة
            </a>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">#</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">اسم الجهة</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">نوع الجهة</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">رقم الجوال</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">الحالة</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($entities as $entity)
                        <tr>
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 font-semibold">{{ $entity->name }}</td>
                            <td class="px-4 py-2">{{ $entity->type_text }}</td>
                            <td class="px-4 py-2">{{ $entity->phone }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 rounded-full text-xs {{ $entity->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $entity->status == 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                <a href="{{ route('settings.revenue-entities.edit', $entity) }}"
                                    class="text-blue-600 hover:text-blue-800" title="تعديل"><i
                                        class="ri-edit-2-line"></i></a>
                                <form action="{{ route('settings.revenue-entities.destroy', $entity) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من حذف الجهة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="حذف"><i
                                            class="ri-delete-bin-6-line"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $entities->links() }}
            </div>
        </div>
    </div>
@endsection
