@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">شاحنات النقل الداخلي</h1>
                <p class="text-gray-600">إدارة شاحنات النقل الداخلي المسجلة في النظام</p>
            </div>
            <a href="{{ route('internal-trucks.create') }}"
                class="bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition-colors duration-200">
                + إضافة شاحنة نقل داخلي
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if ($trucks->isEmpty())
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="ri-truck-line text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد شاحنات داخلية</h3>
                    <p class="text-gray-500 mb-6">ابدأ بإضافة أول شاحنة داخلية إلى النظام</p>
                    <a href="{{ route('internal-trucks.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors duration-200">
                        <i class="ri-add-line ml-2"></i>
                        إضافة شاحنة داخلية
                    </a>
                </div>
            @else
                <!-- Search and Filter Bar -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <i
                                    class="ri-search-line absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="searchInput" placeholder="البحث في الشاحنات..."
                                    class="pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                            </div>
                            <select id="statusFilter"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                <option value="">جميع الحالات</option>
                                <option value="متاح">متاحة</option>
                                <option value="قيد الاستخدام">قيد الاستخدام</option>
                                <option value="تحت الصيانة">تحت الصيانة</option>
                                <option value="غير متاح">خارج الخدمة</option>
                            </select>
                        </div>
                        <div class="text-sm text-gray-600">
                            إجمالي الشاحنات: <span class="font-semibold">{{ $trucks->total() }}</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-hashtag text-gray-400"></i>
                                        رقم اللوحة
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-car-line text-gray-400"></i>
                                        العلامة والموديل
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-palette-line text-gray-400"></i>
                                        اللون
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-scales-3-line text-gray-400"></i>
                                        الحمولة
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-signal-tower-line text-gray-400"></i>
                                        الحالة
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-settings-3-line text-gray-400"></i>
                                        الإجراءات
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($trucks as $truck)
                                <tr class="hover:bg-gray-50 transition-colors duration-150"
                                    data-status="{{ $truck->status }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-truck-line text-emerald-600"></i>
                                            </div>
                                            <div class="mr-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $truck->plate_number }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $truck->created_at->format('Y/m/d') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $truck->brand }}</div>
                                        <div class="text-sm text-gray-500">{{ $truck->model ?? 'غير محدد' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $truck->color ?? 'غير محدد' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $truck->load_capacity ? number_format($truck->load_capacity, 1) . ' طن' : 'غير محدد' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($truck->status == 'متاح')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1"></div>
                                                    متاحة
                                                </span>
                                            @elseif($truck->status == 'قيد الاستخدام')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-1"></div>
                                                    قيد الاستخدام
                                                </span>
                                            @elseif($truck->status == 'تحت الصيانة')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <div class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1"></div>
                                                    تحت الصيانة
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <div class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1"></div>
                                                    خارج الخدمة
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('internal-trucks.show', $truck) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm transition-colors duration-200 group">
                                                <i
                                                    class="ri-eye-line text-xs ml-1 group-hover:scale-110 transition-transform"></i>
                                                عرض
                                            </a>
                                            <a href="{{ route('internal-trucks.edit', $truck) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-lg text-sm transition-colors duration-200 group">
                                                <i
                                                    class="ri-edit-line text-xs ml-1 group-hover:scale-110 transition-transform"></i>
                                                تعديل
                                            </a>
                                            <button
                                                onclick="showDeleteModal('{{ $truck->id }}', '{{ $truck->plate_number }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm transition-colors duration-200 group">
                                                <i
                                                    class="ri-delete-bin-line text-xs ml-1 group-hover:scale-110 transition-transform"></i>
                                                حذف
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($trucks->hasPages())
                    <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                        {{ $trucks->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- قسم المعدات غير المربوطة -->
        @if ($unlinkedTruckEquipments->isNotEmpty())
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <div class="flex items-center">
                        <i class="ri-tools-line text-blue-600 text-xl ml-2"></i>
                        <h3 class="text-lg font-semibold text-blue-900">معدات شاحنات غير مربوطة</h3>
                        <span class="mr-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $unlinkedTruckEquipments->count() }}
                        </span>
                    </div>
                    <p class="text-blue-700 text-sm mt-1">هذه المعدات من فئة "شاحنات" ولا تملك شاحنة داخلية مرتبطة</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    المعدة
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    النوع
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الحالة
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    السائق
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($unlinkedTruckEquipments as $equipment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $equipment->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $equipment->serial_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $equipment->type }}</div>
                                        <div class="text-sm text-gray-500">{{ $equipment->manufacturer }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($equipment->status == 'available') bg-green-100 text-green-800
                                            @elseif($equipment->status == 'in_use') bg-blue-100 text-blue-800
                                            @elseif($equipment->status == 'maintenance') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if ($equipment->status == 'available')
                                                متاح
                                            @elseif($equipment->status == 'in_use')
                                                قيد الاستخدام
                                            @elseif($equipment->status == 'maintenance')
                                                صيانة
                                            @else
                                                خارج الخدمة
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($equipment->driver)
                                            <div class="text-sm text-gray-900">{{ $equipment->driver->name }}</div>
                                        @else
                                            <span class="text-gray-400 text-sm">غير محدد</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('internal-trucks.link-equipment') }}" method="POST"
                                            onsubmit="return confirm('هل تريد تحويل هذه المعدة إلى شاحنة داخلية؟')"
                                            class="inline">
                                            @csrf
                                            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                                            <button type="submit"
                                                class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-3 py-1 rounded transition-colors">
                                                <i class="ri-link ml-1"></i>
                                                تحويل لشاحنة
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="ri-error-warning-line text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">تأكيد حذف الشاحنة الداخلية</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        هل أنت متأكد من حذف الشاحنة الداخلية رقم <span id="truckPlateNumber"
                            class="font-bold text-gray-900"></span>؟
                    </p>
                    <p class="text-sm text-red-600 mt-2">
                        <i class="ri-warning-line"></i>
                        لا يمكن التراجع عن هذا الإجراء!
                    </p>
                </div>
                <div class="flex items-center justify-center gap-4 mt-4">
                    <button onclick="hideDeleteModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                        إلغاء
                    </button>
                    <button onclick="confirmDelete()"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                        <i class="ri-delete-bin-line ml-1"></i>
                        حذف نهائياً
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for deletion -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let truckToDelete = null;

        function showDeleteModal(truckId, plateNumber) {
            truckToDelete = truckId;
            document.getElementById('truckPlateNumber').textContent = plateNumber;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            truckToDelete = null;
        }

        function confirmDelete() {
            if (truckToDelete) {
                const form = document.getElementById('deleteForm');
                form.action = `/internal-trucks/${truckToDelete}`;
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });

        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tableRows = document.querySelectorAll('tbody tr');

            function filterTable() {
                const searchTerm = searchInput?.value.toLowerCase() || '';
                const statusValue = statusFilter?.value || '';

                if (tableRows) {
                    tableRows.forEach(row => {
                        const plateNumber = row.querySelector('td:first-child .text-sm.font-medium')
                            ?.textContent.toLowerCase() || '';
                        const brand = row.querySelector('td:nth-child(2) .text-sm.font-medium')?.textContent
                            .toLowerCase() || '';
                        const model = row.querySelector('td:nth-child(2) .text-sm.text-gray-500')
                            ?.textContent.toLowerCase() || '';
                        const color = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                        const status = row.getAttribute('data-status') || '';

                        const matchesSearch = plateNumber.includes(searchTerm) ||
                            brand.includes(searchTerm) ||
                            model.includes(searchTerm) ||
                            color.includes(searchTerm);
                        const matchesStatus = statusValue === '' || status === statusValue;

                        if (matchesSearch && matchesStatus) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterTable);
            }

            if (statusFilter) {
                statusFilter.addEventListener('change', filterTable);
            }
        });
    </script>
@endsection
