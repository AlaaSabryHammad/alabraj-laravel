@extends('layouts.app')

@section('title', 'إدارة المستندات - شركة الأبراج للمقاولات')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة المستندات</h1>
                <p class="text-gray-600">إدارة وتنظيم جميع مستندات الشركة والمشاريع</p>
            </div>
            <a href="{{ route('documents.create') }}"
               class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center">
                <i class="ri-file-add-line ml-2"></i>
                إضافة مستند جديد
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
        <div class="flex items-center">
            <i class="ri-check-circle-line text-green-600 ml-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Document Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">إجمالي المستندات</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $documents->total() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-xl">
                    <i class="ri-file-list-fill text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">هذا الشهر</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $documents->filter(function($doc) { return $doc->created_at->isCurrentMonth(); })->count() }}</h3>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl">
                    <i class="ri-calendar-check-fill text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium mb-1">تنتهي قريباً</p>
                    <h3 class="text-2xl font-bold text-gray-900">3</h3>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-3 rounded-xl">
                    <i class="ri-alarm-warning-fill text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium mb-1">أنواع المستندات</p>
                    <h3 class="text-2xl font-bold text-gray-900">8</h3>
                </div>
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-3 rounded-xl">
                    <i class="ri-folder-open-fill text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">قائمة المستندات</h2>
                <div class="flex space-x-3 space-x-reverse">
                    <select id="typeFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">جميع الأنواع</option>
                        <option value="عقود">عقود</option>
                        <option value="فواتير">فواتير</option>
                        <option value="تراخيص">تراخيص</option>
                        <option value="شهادات">شهادات</option>
                    </select>
                    <div class="relative">
                        <input type="text"
                               id="searchInput"
                               placeholder="البحث في المستندات..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <i class="ri-search-line absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        @if($documents->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستند</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الرفع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الانتهاء</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($documents as $document)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                                    @php
                                        $iconMap = [
                                            'pdf' => 'ri-file-pdf-line',
                                            'doc' => 'ri-file-word-line',
                                            'docx' => 'ri-file-word-line',
                                            'xls' => 'ri-file-excel-line',
                                            'xlsx' => 'ri-file-excel-line',
                                            'jpg' => 'ri-image-line',
                                            'jpeg' => 'ri-image-line',
                                            'png' => 'ri-image-line',
                                            'default' => 'ri-file-line'
                                        ];
                                        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                        $icon = $iconMap[$extension] ?? $iconMap['default'];
                                    @endphp
                                    <i class="{{ $icon }} text-white text-sm"></i>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $document->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $document->description ? Str::limit($document->description, 50) : 'لا يوجد وصف' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $document->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->created_at->format('Y/m/d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $document->expiry_date ? $document->expiry_date->format('Y/m/d') : 'لا ينتهي' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($document->expiry_date)
                                @php
                                    $daysLeft = now()->diffInDays($document->expiry_date, false);
                                @endphp
                                @if($document->expiry_date->isPast())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="ri-error-warning-line ml-1"></i>
                                        منتهي
                                    </span>
                                @elseif($daysLeft <= 30 && $daysLeft >= 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="ri-time-line ml-1"></i>
                                        ينتهي قريباً ({{ floor($daysLeft) }} يوم)
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="ri-check-line ml-1"></i>
                                        ساري
                                    </span>
                                @endif
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="ri-infinity-line ml-1"></i>
                                    دائم
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('documents.show', $document) }}"
                                   class="text-blue-600 hover:text-blue-900 transition-colors" title="عرض">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('documents.download', $document) }}"
                                   class="text-green-600 hover:text-green-900 transition-colors" title="تحميل">
                                    <i class="ri-download-line"></i>
                                </a>
                                <a href="{{ route('documents.edit', $document) }}"
                                   class="text-indigo-600 hover:text-indigo-900 transition-colors" title="تعديل">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button type="button" onclick="showDeleteModal({{ $document->id }}, '{{ $document->title }}', '{{ $document->type }}', '{{ $document->created_at->format('Y/m/d') }}', '{{ $document->expiry_date ? $document->expiry_date->format('Y/m/d') : 'لا ينتهي' }}')" class="text-red-600 hover:text-red-900 transition-colors" title="حذف">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $documents->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="ri-folder-open-line text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مستندات</h3>
            <p class="text-gray-500 mb-6">ابدأ برفع مستند جديد لإدارة مستندات الشركة</p>
            <a href="{{ route('documents.create') }}"
               class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all duration-200 inline-flex items-center">
                <i class="ri-file-add-line ml-2"></i>
                إضافة مستند جديد
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="ri-delete-bin-line text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">حذف المستند</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    هل أنت متأكد من حذف هذا المستند؟ هذا الإجراء لا يمكن التراجع عنه.
                </p>

                <!-- Document Information -->
                <div class="p-3 bg-gray-50 rounded-lg text-right">
                    <div class="text-xs text-gray-600 space-y-1">
                        <div class="flex justify-between">
                            <span>عنوان المستند:</span>
                            <span id="documentTitle" class="font-medium text-gray-900"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>النوع:</span>
                            <span id="documentType" class="font-medium text-gray-900"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>تاريخ الرفع:</span>
                            <span id="documentCreated" class="font-medium text-gray-900"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>تاريخ الانتهاء:</span>
                            <span id="documentExpiry" class="font-medium text-gray-900"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3 justify-center">
                        <button type="button"
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            حذف المستند
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterRows() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedType = typeFilter.value.toLowerCase();

        tableRows.forEach(function(row) {
            const title = row.querySelector('td:first-child .text-sm.font-medium.text-gray-900')?.textContent.toLowerCase() || '';
            const description = row.querySelector('td:first-child .text-sm.text-gray-500')?.textContent.toLowerCase() || '';
            const type = row.querySelector('td:nth-child(2) span')?.textContent.toLowerCase() || '';

            const matchesSearch = !searchTerm ||
                                title.includes(searchTerm) ||
                                description.includes(searchTerm) ||
                                type.includes(searchTerm);

            const matchesType = !selectedType || type.includes(selectedType);

            const isVisible = matchesSearch && matchesType;
            row.style.display = isVisible ? '' : 'none';
        });

        // Show/hide "no results" message
        const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
        const tbody = document.querySelector('tbody');

        // Remove existing "no results" row
        const existingNoResults = tbody.querySelector('.no-results-row');
        if (existingNoResults) {
            existingNoResults.remove();
        }

        if (visibleRows.length === 0 && (searchTerm || selectedType)) {
            const noResultsRow = document.createElement('tr');
            noResultsRow.className = 'no-results-row';
            noResultsRow.innerHTML = `
                <td colspan="6" class="px-6 py-12 text-center">
                    <i class="ri-search-line text-4xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500">لم يتم العثور على نتائج للفلترة المحددة</p>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
    }

    searchInput.addEventListener('input', filterRows);
    typeFilter.addEventListener('change', filterRows);
});

// Modal functions
function showDeleteModal(documentId, title, type, created, expiry) {
    // Set document information in modal
    document.getElementById('documentTitle').textContent = title;
    document.getElementById('documentType').textContent = type;
    document.getElementById('documentCreated').textContent = created;
    document.getElementById('documentExpiry').textContent = expiry;

    // Set form action
    document.getElementById('deleteForm').action = `/documents/${documentId}`;

    // Show modal
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeDeleteModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush

@endsection
