@extends('layouts.app')

@section('title', $sparePartSupplier->name)

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('spare-part-suppliers.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="ri-arrow-right-line text-2xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <i class="ri-store-3-line text-orange-600"></i>
                    {{ $sparePartSupplier->name }}
                </h1>
                <p class="text-gray-600">{{ $sparePartSupplier->company_name ?? 'لا توجد شركة محددة' }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('spare-part-suppliers.edit', $sparePartSupplier) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="ri-edit-line"></i>
                تعديل
            </a>
            <form method="POST" action="{{ route('spare-part-suppliers.destroy', $sparePartSupplier) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المورد؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="ri-delete-bin-line"></i>
                    حذف
                </button>
            </form>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        @if($sparePartSupplier->status === 'نشط')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
                نشط
            </span>
        @else
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                <span class="w-2 h-2 bg-gray-500 rounded-full ml-2"></span>
                غير نشط
            </span>
        @endif
    </div>

    <!-- Information Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-information-line text-blue-600"></i>
                المعلومات الأساسية
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">اسم المورد</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">اسم الشركة</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->company_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">رقم الضريبة</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->tax_number ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">رقم السجل التجاري</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->cr_number ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-phone-line text-green-600"></i>
                معلومات الاتصال
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">البريد الإلكتروني</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">رقم الهاتف</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">رقم هاتف بديل</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->phone_2 ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">شخص المراجعة</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->contact_person ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">رقم هاتف شخص المراجعة</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->contact_person_phone ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Address and Financial Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Address Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-map-pin-line text-red-600"></i>
                معلومات العنوان
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">العنوان</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->address ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">المدينة</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->city ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">الدولة</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->country ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Financial Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-bank-card-line text-purple-600"></i>
                المعلومات المالية
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">حد الائتمان</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->credit_limit ? number_format($sparePartSupplier->credit_limit, 2) : '-' }} ريال</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">شروط الدفع</p>
                    <p class="text-gray-900 font-medium">{{ $sparePartSupplier->payment_terms ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if($sparePartSupplier->notes)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-file-text-line text-yellow-600"></i>
                ملاحظات
            </h3>
            <p class="text-gray-900">{{ $sparePartSupplier->notes }}</p>
        </div>
    @endif

    <!-- Spare Parts -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="ri-tools-line text-orange-600"></i>
            قطع الغيار ({{ $sparePartSupplier->spareParts->count() }})
        </h3>

        @if($sparePartSupplier->spareParts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الاسم</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الكود</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">السعر</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sparePartSupplier->spareParts as $sparePart)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $sparePart->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $sparePart->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($sparePart->unit_price, 2) }} ريال</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($sparePart->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">نشط</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">غير نشط</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="ri-tools-line text-4xl text-gray-300 mb-4 block"></i>
                <p>لا توجد قطع غيار مرتبطة بهذا المورد</p>
            </div>
        @endif
    </div>
</div>
@endsection
