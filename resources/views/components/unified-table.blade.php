<!-- Unified Modern Table Component -->
<div class="space-y-6">
    <!-- Header Section with Add Button -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $title ?? 'الجدول' }}</h2>
            <p class="text-gray-600 mt-1">{{ $description ?? '' }}</p>
        </div>
        @if (isset($addButtonText) && isset($addButtonAction))
            <button onclick="{{ $addButtonAction }}"
                class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm hover:shadow-md">
                <i class="ri-add-line ml-2"></i>
                <span>{{ $addButtonText }}</span>
            </button>
        @endif
    </div>

    <!-- Table or Empty State -->
    @if (isset($items) && $items->count() > 0)
        <div class="overflow-hidden border border-gray-200 rounded-xl">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            {{ $slot }}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {{ $body ?? '' }}
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination if available -->
        @if (method_exists($items, 'links'))
            <div class="flex justify-center mt-6">
                {{ $items->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div
            class="flex flex-col items-center justify-center py-16 px-6 bg-gradient-to-b from-gray-50 to-white rounded-xl border-2 border-dashed border-gray-200">
            <div
                class="w-20 h-20 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                <i class="ri-inbox-line text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $emptyStateTitle ?? 'لا توجد بيانات' }}</h3>
            <p class="text-gray-500 text-center mb-6 max-w-md">{{ $emptyStateMessage ?? 'ابدأ بإضافة عنصر جديد' }}</p>
            @if (isset($addButtonText) && isset($addButtonAction))
                <button onclick="{{ $addButtonAction }}"
                    class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                    <i class="ri-add-line ml-2"></i>
                    <span>{{ $addButtonText }}</span>
                </button>
            @endif
        </div>
    @endif
</div>
