@extends('layouts.app')

@section('title', 'إضافة مورد جديد')

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('spare-part-suppliers.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="ri-arrow-right-line text-2xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <i class="ri-add-circle-line text-blue-600"></i>
                إضافة مورد قطع غيار جديد
            </h1>
            <p class="text-gray-600">إدخال بيانات المورد الجديد</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <form action="{{ route('spare-part-suppliers.store') }}" method="POST">
            @csrf

            @include('spare-part-suppliers._form')

            <!-- Buttons -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('spare-part-suppliers.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                    <i class="ri-check-line"></i>
                    حفظ المورد
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
