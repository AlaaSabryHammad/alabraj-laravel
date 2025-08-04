@extends('layouts.app')

@section('title', 'عرض المستند - ' . $document->title)

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('documents.index') }}"
                   class="text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-right-line text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $document->title }}</h1>
                    <p class="text-gray-600">{{ $document->description ?? 'لا يوجد وصف' }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                <button onclick="shareDocument()"
                        class="inline-flex items-center px-4 py-2 border border-purple-300 rounded-md text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 transition-colors">
                    <i class="ri-share-line ml-2"></i>
                    مشاركة
                </button>
                <a href="{{ route('documents.download', $document) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="ri-download-line ml-2"></i>
                    تحميل
                </a>
                <a href="{{ route('documents.edit', $document) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="ri-edit-line ml-2"></i>
                    تعديل
                </a>
            </div>
        </div>
    </div>

    <!-- Document Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Document Viewer -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">عرض المستند</h3>

                @if($document->file_path)
                    @php
                        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        $pdfExtensions = ['pdf'];
                        $fileUrl = asset('storage/' . $document->file_path);
                        $fileExists = file_exists(public_path('storage/' . $document->file_path));
                    @endphp

                    @if(!$fileExists)
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-error-warning-line text-red-500 text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">الملف غير موجود</h3>
                            <p class="text-gray-500 mb-6">لم يتم العثور على الملف في النظام</p>
                            <p class="text-sm text-gray-400">مسار الملف: {{ $document->file_path }}</p>
                        </div>
                    @elseif(in_array(strtolower($extension), $imageExtensions))
                        <!-- Image Preview -->
                        <div class="text-center">
                            <img src="{{ $fileUrl }}"
                                 alt="{{ $document->title }}"
                                 class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display: none;" class="py-12">
                                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ri-image-line text-red-500 text-4xl"></i>
                                </div>
                                <p class="text-gray-500">فشل في تحميل الصورة</p>
                            </div>
                        </div>
                    @elseif(in_array(strtolower($extension), $pdfExtensions))
                        <!-- PDF Preview -->
                        <div class="aspect-w-16 aspect-h-9">
                            <embed src="{{ $fileUrl }}"
                                   type="application/pdf"
                                   class="w-full h-96 rounded-lg border">
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-500 mb-2">إذا لم يتم عرض ملف PDF بشكل صحيح:</p>
                            <a href="{{ $fileUrl }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="ri-external-link-line ml-2"></i>
                                فتح في نافذة جديدة
                            </a>
                        </div>
                    @else
                        <!-- Other File Types -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                @php
                                    $iconMap = [
                                        'doc' => 'ri-file-word-line',
                                        'docx' => 'ri-file-word-line',
                                        'xls' => 'ri-file-excel-line',
                                        'xlsx' => 'ri-file-excel-line',
                                        'ppt' => 'ri-file-ppt-line',
                                        'pptx' => 'ri-file-ppt-line',
                                        'txt' => 'ri-file-text-line',
                                        'default' => 'ri-file-line'
                                    ];
                                    $icon = $iconMap[$extension] ?? $iconMap['default'];
                                @endphp
                                <i class="{{ $icon }} text-gray-400 text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $document->title }}</h3>
                            <p class="text-gray-500 mb-6">هذا النوع من الملفات لا يمكن عرضه مباشرة</p>
                            <a href="{{ route('documents.download', $document) }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="ri-download-line ml-2"></i>
                                تحميل الملف
                            </a>
                        </div>
                    @endif
                @else
                    <!-- No File -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-file-line text-gray-400 text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا يوجد ملف مرفق</h3>
                        <p class="text-gray-500 mb-6">لم يتم رفع أي ملف لهذا المستند</p>
                        <a href="{{ route('documents.edit', $document) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i class="ri-upload-line ml-2"></i>
                            رفع ملف
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Document Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات المستند</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">النوع</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                            {{ $document->type }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">تاريخ الرفع</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $document->created_at->format('Y/m/d H:i') }}</p>
                    </div>

                    @if($document->expiry_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">تاريخ الانتهاء</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $document->expiry_date->format('Y/m/d') }}</p>
                        <div class="mt-2">
                            @if($document->expiry_date->isPast())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="ri-error-warning-line ml-1"></i>
                                    منتهي
                                </span>
                            @elseif($document->expiry_date->diffInDays() <= 30)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="ri-time-line ml-1"></i>
                                    ينتهي قريباً
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="ri-check-line ml-1"></i>
                                    ساري
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500">حجم الملف</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($document->file_path && file_exists(public_path('storage/' . $document->file_path)))
                                {{ number_format(filesize(public_path('storage/' . $document->file_path)) / 1024, 2) }} كيلوبايت
                            @elseif($document->file_size)
                                {{ number_format($document->file_size / 1024, 2) }} كيلوبايت
                            @else
                                غير متوفر
                            @endif
                        </p>
                    </div>

                    @if($document->file_path)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">صيغة الملف</label>
                        <p class="mt-1 text-sm text-gray-900 uppercase">{{ pathinfo($document->file_path, PATHINFO_EXTENSION) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">اسم الملف الأصلي</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $document->original_filename ?? 'غير متوفر' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الإجراءات</h3>
                <div class="space-y-3">
                    <a href="{{ route('documents.download', $document) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <i class="ri-download-line ml-2"></i>
                        تحميل
                    </a>

                    <a href="{{ route('documents.edit', $document) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="ri-edit-line ml-2"></i>
                        تعديل
                    </a>

                    <button type="button"
                            onclick="openDeleteModal()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <i class="ri-delete-bin-line ml-2"></i>
                        حذف
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-purple-100">
                        <i class="ri-share-line text-purple-600 text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <h3 class="text-lg font-semibold text-gray-900">مشاركة المستند</h3>
                        <p class="text-sm text-gray-500">{{ $document->title }}</p>
                    </div>
                </div>
                <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- Share Link -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">رابط المشاركة</label>
                <div class="flex">
                    <input type="text"
                           id="shareLink"
                           readonly
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg bg-gray-50 text-sm">
                    <button id="copyButton"
                            onclick="copyToClipboard()"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-l-lg hover:bg-blue-700 transition-colors">
                        <i class="ri-file-copy-line ml-1"></i>
                        نسخ
                    </button>
                </div>
            </div>

            <!-- Share Options -->
            <div class="space-y-3">
                <h4 class="text-sm font-medium text-gray-700">مشاركة عبر:</h4>

                <button onclick="shareViaEmail()"
                        class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="ri-mail-line ml-2 text-blue-500"></i>
                    البريد الإلكتروني
                </button>

                <button onclick="shareViaWhatsApp()"
                        class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="ri-whatsapp-line ml-2 text-green-500"></i>
                    واتساب
                </button>
            </div>

            <!-- Close Button -->
            <div class="mt-6 flex justify-end">
                <button onclick="closeShareModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3 text-center">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="ri-delete-bin-line text-red-600 text-2xl"></i>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                تأكيد حذف المستند
            </h3>

            <!-- Message -->
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    هل أنت متأكد من حذف المستند "<span class="font-medium text-gray-900">{{ $document->title }}</span>"؟
                </p>
                <p class="text-xs text-red-500">
                    <i class="ri-warning-line ml-1"></i>
                    هذا الإجراء لا يمكن التراجع عنه وسيتم حذف الملف نهائياً
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-center space-x-4 space-x-reverse pt-4">
                <button onclick="closeDeleteModal()"
                        class="px-6 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                    إلغاء
                </button>

                <form id="deleteForm" action="{{ route('documents.destroy', $document) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-6 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <i class="ri-delete-bin-line ml-1"></i>
                        حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeShareModal();
    }
});

// Share document functionality
function shareDocument() {
    document.getElementById('shareModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Update share link
    const shareLink = window.location.href;
    document.getElementById('shareLink').value = shareLink;
}

function closeShareModal() {
    document.getElementById('shareModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function copyToClipboard() {
    const shareLink = document.getElementById('shareLink');
    shareLink.select();
    shareLink.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(shareLink.value).then(function() {
        // Show success message
        const button = document.getElementById('copyButton');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="ri-check-line ml-1"></i> تم النسخ!';
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        button.classList.add('bg-green-600', 'hover:bg-green-700');

        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    });
}

function shareViaEmail() {
    const documentTitle = "{{ $document->title }}";
    const shareLink = window.location.href;
    const subject = encodeURIComponent('مشاركة مستند: ' + documentTitle);
    const body = encodeURIComponent(`مرحباً،\n\nأريد مشاركة هذا المستند معك:\n\nالعنوان: ${documentTitle}\nالرابط: ${shareLink}\n\nمع تحياتي`);

    window.location.href = `mailto:?subject=${subject}&body=${body}`;
}

function shareViaWhatsApp() {
    const documentTitle = "{{ $document->title }}";
    const shareLink = window.location.href;
    const message = encodeURIComponent(`مشاركة مستند: ${documentTitle}\n${shareLink}`);

    window.open(`https://wa.me/?text=${message}`, '_blank');
}

// Close share modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('shareModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeShareModal();
        }
    });
});
</script>
@endpush

@endsection
