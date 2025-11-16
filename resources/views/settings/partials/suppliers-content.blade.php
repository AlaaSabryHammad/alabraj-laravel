<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-xl font-bold text-gray-900">الموردين</h3>
        <p class="text-gray-600 text-sm mt-1">إدارة الموردين والشركات المورّدة</p>
    </div>
    <button onclick="openSupplierModal()"
        class="flex items-center px-6 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium text-sm rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-sm hover:shadow-md">
        <i class="ri-add-line ml-2"></i>
        <span>إضافة مورد</span>
    </button>
</div>
<div>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث في الموردين..."
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>

<div>
    <select name="category"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">جميع الفئات</option>
        @foreach ($categories as $category)
            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                {{ $category }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <select name="status"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">جميع الحالات</option>
        <option value="نشط" {{ request('status') == 'نشط' ? 'selected' : '' }}>نشط</option>
        <option value="غير نشط" {{ request('status') == 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
        <option value="معلق" {{ request('status') == 'معلق' ? 'selected' : '' }}>معلق</option>
    </select>
</div>

<div>
    <select name="payment_terms"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">جميع شروط الدفع</option>
        <option value="نقدي" {{ request('payment_terms') == 'نقدي' ? 'selected' : '' }}>نقدي</option>
        <option value="آجل 30 يوم" {{ request('payment_terms') == 'آجل 30 يوم' ? 'selected' : '' }}>آجل 30 يوم</option>
        <option value="آجل 60 يوم" {{ request('payment_terms') == 'آجل 60 يوم' ? 'selected' : '' }}>آجل 60 يوم</option>
        <option value="آجل 90 يوم" {{ request('payment_terms') == 'آجل 90 يوم' ? 'selected' : '' }}>آجل 90 يوم</option>
    </select>
</div>

<div>
    <button type="submit"
        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        <i class="ri-search-line ml-1"></i>
        بحث
    </button>
</div>
</form>
</div>

<!-- Suppliers Table -->
<div class="overflow-hidden border border-gray-200 rounded-xl">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">المورد</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الفئة</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الاتصال</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">شروط الدفع</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الحالة</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-purple-100 to-purple-200 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                    <i class="ri-store-2-line text-purple-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $supplier->name }}</div>
                                    @if ($supplier->company_name)
                                        <div class="text-xs text-gray-500">{{ $supplier->company_name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if ($supplier->category)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $supplier->category }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $supplier->phone ?: '—' }}</div>
                            @if ($supplier->email)
                                <div class="text-xs text-gray-500">{{ $supplier->email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $supplier->payment_terms == 'نقدي' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200' }}">
                                {{ $supplier->payment_terms }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $supplier->status == 'نشط'
                                    ? 'bg-green-50 text-green-700 border border-green-200'
                                    : ($supplier->status == 'معلق'
                                        ? 'bg-yellow-50 text-yellow-700 border border-yellow-200'
                                        : 'bg-red-50 text-red-700 border border-red-200') }}">
                                {{ $supplier->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <a href="{{ route('suppliers.show', $supplier) }}"
                                    class="inline-flex items-center justify-center p-2 text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-150"
                                    title="عرض">
                                    <i class="ri-eye-line text-lg"></i>
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                    class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-150"
                                    title="تعديل">
                                    <i class="ri-edit-line text-lg"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                    class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المورد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
                                        title="حذف">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl flex items-center justify-center mb-6">
                                    <i class="ri-store-2-line text-purple-600 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">لا توجد موردين</h3>
                                <p class="text-gray-600 text-sm mb-8">ابدأ بإضافة أول مورد في النظام</p>
                                <button onclick="openSupplierModal()"
                                    class="flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="ri-add-line ml-2"></i>
                                    <span>إضافة أول مورد</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($suppliers->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $suppliers->links() }}
        </div>
    @endif
</div>
