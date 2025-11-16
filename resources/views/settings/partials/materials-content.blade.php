<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-xl font-bold text-gray-900">المواد</h3>
        <p class="text-gray-600 text-sm mt-1">إدارة أنواع المواد والوحدات</p>
    </div>
    <button onclick="openMaterialModal()"
        class="flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium text-sm rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm hover:shadow-md">
        <i class="ri-add-line ml-2"></i>
        <span>إضافة مادة</span>
    </button>
</div>

<!-- Materials Table -->
<div class="overflow-hidden border border-gray-200 rounded-xl">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">اسم المادة</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الفئة</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">وحدة القياس</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">تاريخ الإضافة</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($materials as $material)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-blue-100 to-blue-200 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                    <i class="ri-box-3-line text-blue-600"></i>
                                </div>
                                <div class="font-medium text-gray-900">{{ $material->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600">{{ $material->category ?: '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $material->unit_of_measure == 'م3'
                                    ? 'bg-green-50 text-green-700 border border-green-200'
                                    : ($material->unit_of_measure == 'طن'
                                        ? 'bg-blue-50 text-blue-700 border border-blue-200'
                                        : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                                {{ $material->unit_of_measure }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600">{{ $material->created_at->format('Y/m/d') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <button
                                    onclick="editMaterial({{ $material->id }}, '{{ $material->name }}', '{{ $material->unit_of_measure }}')"
                                    class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-150"
                                    title="تعديل">
                                    <i class="ri-edit-line text-lg"></i>
                                </button>
                                <button
                                    onclick="deleteMaterial({{ $material->id }}, '{{ $material->name }}', '{{ $material->unit_of_measure }}')"
                                    class="inline-flex items-center justify-center p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150"
                                    title="حذف">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl flex items-center justify-center mb-6">
                                    <i class="ri-box-3-line text-blue-600 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">لا توجد مواد</h3>
                                <p class="text-gray-600 text-sm mb-8">ابدأ بإضافة أول مادة في النظام</p>
                                <button onclick="openMaterialModal()"
                                    class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="ri-add-line ml-2"></i>
                                    <span>إضافة أول مادة</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($materials->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex justify-center" id="materials-pagination">
                {{ $materials->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

<script>
    // Handle pagination and forms to use AJAX
    document.addEventListener('DOMContentLoaded', function() {
        // Handle pagination links immediately
        handlePaginationLinks();

        // Handle filter form submission
        const filterForm = document.getElementById('materials-filter-form');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const params = new URLSearchParams(formData).toString();
                const url = '{{ route('settings.materials.content') }}' + (params ? '?' + params : '');
                loadMaterialsContent(url);
            });
        }
    });

    function handlePaginationLinks() {
        // Handle pagination links with event delegation
        // This function is kept for compatibility but the global handler should take precedence
        console.log('Materials pagination handler initialized');
    }

    function loadMaterialsContent(url) {
        // Show loading state
        const dynamicContent = document.getElementById('section-content');
        if (dynamicContent) {
            // Add loading indicator
            dynamicContent.style.opacity = '0.5';

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    // Update the dynamic content
                    dynamicContent.innerHTML = html;
                    dynamicContent.style.opacity = '1';

                    // Re-run the DOMContentLoaded code for new content
                    const event = new Event('DOMContentLoaded');
                    document.dispatchEvent(event);
                })
                .catch(error => {
                    console.error('Error loading materials content:', error);
                    dynamicContent.style.opacity = '1';
                });
        }
    }

    // Initialize when content loads
    document.addEventListener('DOMContentLoaded', function() {
        handlePaginationLinks();

        // Handle filter form submission
        const filterForm = document.getElementById('materials-filter-form');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const params = new URLSearchParams(formData);
                const url = '{{ route('settings.materials.content') }}?' + params.toString();
                loadMaterialsContent(url);
            });
        }
    });
</script>
</script>
