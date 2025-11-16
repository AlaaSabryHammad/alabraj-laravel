<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-900">جهات الإيرادات</h3>
            <p class="text-gray-600 text-sm mt-1">إدارة جهات مصدر الإيرادات والزبائن</p>
        </div>
        <button onclick="window.location.href='{{ route('settings.revenue-entities.create') }}'"
            class="flex items-center px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-medium text-sm rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-sm hover:shadow-md">
            <i class="ri-add-line ml-2"></i>
            <span>إضافة جهة جديدة</span>
        </button>
    </div>

    <!-- Revenue Entities Table -->
    <div class="overflow-hidden border border-gray-200 rounded-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">اسم الجهة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">النوع</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الشخص المسؤول</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الاتصال</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="revenue-entities-table-body">
                    @forelse(\App\Models\RevenueEntity::orderBy('name')->get() as $entity)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-emerald-100 to-emerald-200 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                        <i class="ri-building-4-line text-emerald-600"></i>
                                    </div>
                                    <div class="font-medium text-gray-900">{{ $entity->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($entity->type === 'government') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($entity->type === 'company') bg-green-50 text-green-700 border border-green-200
                                    @elseif($entity->type === 'individual') bg-yellow-50 text-yellow-700 border border-yellow-200
                                    @else bg-gray-50 text-gray-700 border border-gray-200 @endif">
                                    {{ $entity->type_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $entity->contact_person ?: '—' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    {{ $entity->phone ?: '—' }}
                                    @if ($entity->email)
                                        <div class="text-xs text-gray-500">{{ $entity->email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($entity->status === 'active') bg-green-50 text-green-700 border border-green-200
                                    @else bg-red-50 text-red-700 border border-red-200 @endif">
                                    {{ $entity->status === 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <a href="{{ route('settings.revenue-entities.edit', $entity) }}"
                                        class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-150"
                                        title="تعديل">
                                        <i class="ri-edit-line text-lg"></i>
                                    </a>
                                    <button onclick="deleteRevenueEntity({{ $entity->id }})"
                                        class="inline-flex items-center justify-center p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
                                        title="حذف">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                                        <i class="ri-building-4-line text-emerald-600 text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">لا توجد جهات إيرادات</h3>
                                    <p class="text-gray-600 text-sm mb-8">ابدأ بإضافة أول جهة إيراد في النظام</p>
                                    <button
                                        onclick="window.location.href='{{ route('settings.revenue-entities.create') }}'"
                                        class="flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="ri-add-line ml-2"></i>
                                        <span>إضافة أول جهة</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function deleteRevenueEntity(id) {
        if (confirm('هل أنت متأكد من حذف هذه الجهة؟')) {
            fetch(`/settings/revenue-entities/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // إعادة تحميل المحتوى
                        showSettingsSection('revenue-entities');
                        // إظهار رسالة نجاح
                        showNotification('تم حذف الجهة بنجاح', 'success');
                    } else {
                        showNotification('حدث خطأ أثناء حذف الجهة', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('حدث خطأ أثناء حذف الجهة', 'error');
                });
        }
    }

    function showNotification(message, type) {
        // إنشاء عنصر الإشعار
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // إخفاء الإشعار بعد 3 ثوان
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
</script>
