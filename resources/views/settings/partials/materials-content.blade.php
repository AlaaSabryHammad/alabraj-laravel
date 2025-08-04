<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <!-- Total Materials -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-blue-100">إجمالي المواد</p>
                <p class="text-3xl font-bold">{{ $materials->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="ri-box-3-line text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Materials by م3 -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-green-100">مواد (م3)</p>
                <p class="text-3xl font-bold">{{ $materials->where('unit_of_measure', 'م3')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="ri-cube-line text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Materials by طن/لتر -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-purple-100">مواد (طن/لتر)</p>
                <p class="text-3xl font-bold">{{ $materials->whereIn('unit_of_measure', ['طن', 'لتر'])->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="ri-scales-3-line text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="bg-gray-50 rounded-xl p-4 mb-6">
    <form id="materials-filter-form" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="البحث في أسماء المواد..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <!-- Unit Filter -->
        <div>
            <select name="unit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">جميع الوحدات</option>
                <option value="م3" {{ request('unit') == 'م3' ? 'selected' : '' }}>م3</option>
                <option value="طن" {{ request('unit') == 'طن' ? 'selected' : '' }}>طن</option>
                <option value="لتر" {{ request('unit') == 'لتر' ? 'selected' : '' }}>لتر</option>
            </select>
        </div>

        <!-- Filter Button -->
        <div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="ri-search-line ml-1"></i>
                بحث
            </button>
        </div>
    </form>
</div>

<!-- Materials Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        #
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        اسم المادة
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        وحدة القياس
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        تاريخ الإضافة
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        الإجراءات
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($materials as $index => $material)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $materials->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="ri-box-3-line text-blue-600"></i>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $material->category }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                {{ $material->unit_of_measure == 'م3' ? 'bg-green-100 text-green-800' :
                                   ($material->unit_of_measure == 'طن' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ $material->unit_of_measure }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $material->created_at->format('Y/m/d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <button onclick="editMaterial({{ $material->id }}, '{{ $material->name }}', '{{ $material->unit_of_measure }}')"
                                        class="text-indigo-600 hover:text-indigo-900 p-1 rounded transition-colors"
                                        title="تعديل">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button onclick="deleteMaterial({{ $material->id }}, '{{ $material->name }}', '{{ $material->unit_of_measure }}')"
                                        class="text-red-600 hover:text-red-900 p-1 rounded transition-colors"
                                        title="حذف">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="ri-box-3-line text-4xl mb-2"></i>
                                <p class="text-lg font-medium">لا توجد مواد</p>
                                <p class="text-sm">ابدأ بإضافة المادة الأولى</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($materials->hasPages())
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
            const url = '{{ route("settings.materials.content") }}' + (params ? '?' + params : '');
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
            const url = '{{ route("settings.materials.content") }}?' + params.toString();
            loadMaterialsContent(url);
        });
    }
});
</script>
</script>
