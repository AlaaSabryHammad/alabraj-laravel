<!-- Quick Stats -->
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

    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-blue-100">إجمالي الموردين</p>
                <p class="text-2xl font-bold">{{ $allSuppliers->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="ri-truck-line text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-purple-100">دفع نقدي</p>
                <p class="text-2xl font-bold">{{ $allSuppliers->where('payment_terms', 'نقدي')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="ri-money-dollar-circle-line text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-orange-100">دفع آجل</p>
                <p class="text-2xl font-bold">{{ $allSuppliers->whereIn('payment_terms', ['آجل 30 يوم', 'آجل 60 يوم', 'آجل 90 يوم'])->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="ri-calendar-line text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-gray-50 rounded-xl p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="البحث في الموردين..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">جميع الفئات</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">جميع الحالات</option>
                <option value="نشط" {{ request('status') == 'نشط' ? 'selected' : '' }}>نشط</option>
                <option value="غير نشط" {{ request('status') == 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
                <option value="معلق" {{ request('status') == 'معلق' ? 'selected' : '' }}>معلق</option>
            </select>
        </div>

        <div>
            <select name="payment_terms" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">جميع شروط الدفع</option>
                <option value="نقدي" {{ request('payment_terms') == 'نقدي' ? 'selected' : '' }}>نقدي</option>
                <option value="آجل 30 يوم" {{ request('payment_terms') == 'آجل 30 يوم' ? 'selected' : '' }}>آجل 30 يوم</option>
                <option value="آجل 60 يوم" {{ request('payment_terms') == 'آجل 60 يوم' ? 'selected' : '' }}>آجل 60 يوم</option>
                <option value="آجل 90 يوم" {{ request('payment_terms') == 'آجل 90 يوم' ? 'selected' : '' }}>آجل 90 يوم</option>
            </select>
        </div>

        <div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="ri-search-line ml-1"></i>
                بحث
            </button>
        </div>
    </form>
</div>

<!-- Suppliers Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        المورد
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        الفئة
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        الاتصال
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        شروط الدفع
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
                @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                                @if($supplier->company_name)
                                    <div class="text-sm text-gray-500">{{ $supplier->company_name }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($supplier->category)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $supplier->category }}
                                </span>
                            @else
                                <span class="text-gray-400">غير محدد</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $supplier->phone }}</div>
                            @if($supplier->email)
                                <div class="text-sm text-gray-500">{{ $supplier->email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $supplier->payment_terms == 'نقدي' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $supplier->payment_terms }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $supplier->status == 'نشط' ? 'bg-green-100 text-green-800' :
                                   ($supplier->status == 'معلق' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $supplier->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <a href="{{ route('suppliers.show', $supplier) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المورد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            لا توجد موردين مطابقين للبحث
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($suppliers->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $suppliers->links() }}
        </div>
    @endif
</div>
