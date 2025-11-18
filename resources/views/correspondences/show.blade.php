@extends('layouts.app')

@section('title', 'تفاصيل المراسلة - ' . $correspondence->reference_number)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    تفاصيل المراسلة {{ $correspondence->type === 'incoming' ? 'الواردة' : 'الصادرة' }}
                </h1>
                <p class="text-gray-600">
                    رقم المرجع: {{ $correspondence->reference_number }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="openReplyModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-reply-line"></i>
                    إضافة رد
                </button>
                <a href="{{ route('correspondences.edit', $correspondence) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-edit-line"></i>
                    تحرير
                </a>
                <a href="{{ route('correspondences.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-arrow-right-line"></i>
                    العودة
                </a>
            </div>
        </div>

        <!-- Status Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex items-center gap-2">
                <i class="ri-check-line text-green-600"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex items-center gap-2">
                <i class="ri-error-warning-line text-red-600"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-file-text-line text-blue-600"></i>
                        تفاصيل المراسلة
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Reference Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                الرقم المرجعي
                            </label>
                            <p class="text-lg font-semibold text-blue-600">
                                {{ $correspondence->reference_number }}
                            </p>
                        </div>

                        <!-- External Number -->
                        @if ($correspondence->external_number)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $correspondence->type === 'incoming' ? 'رقم الوارد' : 'رقم الصادر' }}
                                </label>
                                <p class="text-gray-900">
                                    {{ $correspondence->external_number }}
                                </p>
                            </div>
                        @endif

                        <!-- Subject -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                موضوع المراسلة
                            </label>
                            <p class="text-gray-900 text-lg">
                                {{ $correspondence->subject }}
                            </p>
                        </div>

                        @if ($correspondence->type === 'incoming')
                            <!-- From Entity (For Incoming) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    جهة الإصدار
                                </label>
                                <p class="text-gray-900">
                                    {{ $correspondence->from_entity }}
                                </p>
                            </div>

                            <!-- Assigned To (For Incoming) -->
                            @if ($correspondence->assignedEmployee)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        المسؤول الموجه إليه
                                    </label>
                                    <p class="text-gray-900 flex items-center gap-2">
                                        <i class="ri-user-line text-blue-600"></i>
                                        {{ $correspondence->assignedEmployee->name }}
                                    </p>
                                </div>
                            @endif
                        @else
                            <!-- To Entity (For Outgoing) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    الجهة المرسل إليها
                                </label>
                                <p class="text-gray-900">
                                    {{ $correspondence->to_entity }}
                                </p>
                            </div>
                        @endif

                        <!-- Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                تاريخ المراسلة
                            </label>
                            <p class="text-gray-900 flex items-center gap-2">
                                <i class="ri-calendar-line text-green-600"></i>
                                {{ $correspondence->correspondence_date->format('Y/m/d') }}
                            </p>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                درجة الأهمية
                            </label>
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                @if ($correspondence->priority === 'urgent') bg-red-100 text-red-800
                                @elseif($correspondence->priority === 'high')
                                    bg-orange-100 text-orange-800
                                @elseif($correspondence->priority === 'medium')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-green-100 text-green-800 @endif">
                                @if ($correspondence->priority === 'urgent')
                                    <i class="ri-alarm-warning-line"></i>
                                @elseif($correspondence->priority === 'high')
                                    <i class="ri-error-warning-line"></i>
                                @elseif($correspondence->priority === 'medium')
                                    <i class="ri-information-line"></i>
                                @else
                                    <i class="ri-checkbox-circle-line"></i>
                                @endif
                                {{ $correspondence->priority_display }}
                            </span>
                        </div>

                        <!-- Project -->
                        @if ($correspondence->project)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    المشروع المرتبط
                                </label>
                                <p class="text-gray-900 flex items-center gap-2">
                                    <i class="ri-folder-line text-purple-600"></i>
                                    {{ $correspondence->project->name }}
                                </p>
                            </div>
                        @endif

                        <!-- Notes -->
                        @if ($correspondence->notes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    ملاحظات
                                </label>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $correspondence->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Replies Section -->
                @if ($correspondence->replies && $correspondence->replies->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border p-6 mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-reply-line text-green-600"></i>
                            الردود ({{ $correspondence->replies->count() }})
                        </h2>

                        <div class="space-y-4">
                            @foreach ($correspondence->replies as $reply)
                                <div
                                    class="border-r-4 border-green-200 pr-4 pb-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-medium text-gray-900">{{ $reply->user->name }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $reply->created_at->format('Y/m/d H:i') }}</span>

                                        <!-- Reply Type Badge -->
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if (($reply->reply_type ?? 'reply') === 'forward') bg-blue-100 text-blue-800
                                            @else
                                                bg-gray-100 text-gray-800 @endif">
                                            <i
                                                class="ri-{{ ($reply->reply_type ?? 'reply') === 'forward' ? 'share-forward-line' : 'reply-line' }}"></i>
                                            {{ $reply->reply_type_display ?? 'رد' }}
                                        </span>

                                        <!-- Status Badge -->
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if ($reply->status === 'sent') bg-green-100 text-green-800
                                            @else
                                                bg-yellow-100 text-yellow-800 @endif">
                                            <i
                                                class="ri-{{ $reply->status === 'sent' ? 'check-double-line' : 'draft-line' }}"></i>
                                            {{ $reply->status_display }}
                                        </span>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg mb-2">
                                        <p class="text-gray-900 whitespace-pre-wrap">{{ $reply->reply_content }}</p>
                                    </div>
                                    @if ($reply->file_path)
                                        <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg mt-2">
                                            <div class="flex items-center gap-2 text-sm">
                                                @php
                                                    $fileExtension = strtolower(
                                                        pathinfo($reply->file_name, PATHINFO_EXTENSION),
                                                    );
                                                    $fileIcon = match ($fileExtension) {
                                                        'pdf' => 'ri-file-pdf-line text-red-600',
                                                        'doc', 'docx' => 'ri-file-word-line text-blue-600',
                                                        'xls', 'xlsx' => 'ri-file-excel-line text-green-600',
                                                        'jpg', 'jpeg', 'png', 'gif' => 'ri-image-line text-purple-600',
                                                        'zip', 'rar' => 'ri-file-zip-line text-yellow-600',
                                                        default => 'ri-file-line text-gray-600',
                                                    };
                                                @endphp
                                                <i class="{{ $fileIcon }}"></i>
                                                <span class="text-blue-800 font-medium">{{ $reply->file_name }}</span>
                                                <span
                                                    class="text-gray-600 text-xs">({{ $reply->file_size_display }})</span>
                                                @if ($reply->status === 'sent')
                                                    <span class="text-green-600 text-xs">(تم الإرسال)</span>
                                                @else
                                                    <span class="text-yellow-600 text-xs">(مسودة)</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <!-- View/Preview Icon -->
                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'pdf']))
                                                    <button
                                                        onclick="previewFile('{{ asset('storage/' . $reply->file_path) }}', '{{ $reply->file_name }}')"
                                                        class="text-blue-600 hover:text-blue-800 p-1 rounded transition-colors"
                                                        title="معاينة الملف">
                                                        <i class="ri-eye-line text-lg"></i>
                                                    </button>
                                                @endif
                                                <!-- Download Icon -->
                                                <a href="{{ route('correspondences.replies.download', $reply->id) }}"
                                                    class="text-green-600 hover:text-green-800 p-1 rounded transition-colors"
                                                    title="تحميل الملف">
                                                    <i class="ri-download-line text-lg"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- File Attachment -->
                @if ($correspondence->file_path)
                    <div class="bg-white rounded-xl shadow-sm border p-6 mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-attachment-line text-blue-600"></i>
                            ملف مرفق
                        </h2>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i class="ri-file-line text-2xl text-blue-600"></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $correspondence->file_name }}</p>
                                    <p class="text-sm text-gray-600">
                                        حجم الملف: {{ $correspondence->file_size_display }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('correspondences.download', $correspondence) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="ri-download-line"></i>
                                تحميل
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-information-line text-blue-600"></i>
                        معلومات إضافية
                    </h3>

                    <div class="space-y-4">
                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                نوع المراسلة
                            </label>
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                @if ($correspondence->type === 'incoming') bg-green-100 text-green-800
                                @else
                                    bg-blue-100 text-blue-800 @endif">
                                @if ($correspondence->type === 'incoming')
                                    <i class="ri-inbox-line"></i>
                                    واردة
                                @else
                                    <i class="ri-send-plane-line"></i>
                                    صادرة
                                @endif
                            </span>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                حالة المراسلة
                            </label>
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                @if ($correspondence->status === 'replied') bg-green-100 text-green-800
                                @elseif($correspondence->status === 'in_progress')
                                    bg-yellow-100 text-yellow-800
                                @elseif($correspondence->status === 'closed')
                                    bg-gray-100 text-gray-800
                                @else
                                    bg-red-100 text-red-800 @endif">
                                @if ($correspondence->status === 'replied')
                                    <i class="ri-check-double-line"></i>
                                    تم الرد
                                @elseif($correspondence->status === 'in_progress')
                                    <i class="ri-time-line"></i>
                                    قيد المعالجة
                                @elseif($correspondence->status === 'closed')
                                    <i class="ri-lock-line"></i>
                                    مغلق
                                @else
                                    <i class="ri-time-line"></i>
                                    في الانتظار
                                @endif
                            </span>
                        </div>

                        @if ($correspondence->replied_at)
                            <!-- Replied At -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    تاريخ الرد
                                </label>
                                <p class="text-gray-900 text-sm flex items-center gap-2">
                                    <i class="ri-calendar-check-line text-green-600"></i>
                                    {{ $correspondence->replied_at->format('Y/m/d H:i') }}
                                </p>
                            </div>
                        @endif

                        <!-- Created By -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                تم الإدخال بواسطة
                            </label>
                            <p class="text-gray-900 flex items-center gap-2">
                                <i class="ri-user-line text-gray-600"></i>
                                {{ $correspondence->user->name }}
                            </p>
                        </div>

                        <!-- Created Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                تاريخ الإدخال
                            </label>
                            <p class="text-gray-900 text-sm">
                                {{ $correspondence->created_at->format('Y/m/d H:i') }}
                            </p>
                        </div>

                        <!-- Last Modified -->
                        @if ($correspondence->updated_at != $correspondence->created_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    آخر تحديث
                                </label>
                                <p class="text-gray-900 text-sm">
                                    {{ $correspondence->updated_at->format('Y/m/d H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-settings-line text-blue-600"></i>
                        إجراءات
                    </h3>

                    <div class="space-y-3">
                        <a href="{{ route('correspondences.edit', $correspondence) }}"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="ri-edit-line"></i>
                            تحرير المراسلة
                        </a>

                        @if ($correspondence->file_path)
                            <a href="{{ route('correspondences.download', $correspondence) }}"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="ri-download-line"></i>
                                تحميل الملف
                            </a>
                        @endif

                        <button onclick="printCorrespondence()"
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="ri-printer-line"></i>
                            طباعة
                        </button>

                        <form method="POST" action="{{ route('correspondences.destroy', $correspondence) }}"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذه المراسلة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="ri-delete-bin-line"></i>
                                حذف المراسلة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printCorrespondence() {
            window.print();
        }

        // File preview function
        function previewFile(fileUrl, fileName) {
            const fileExtension = fileName.split('.').pop().toLowerCase();
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.style.direction = 'rtl';

            let content = '';

            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                // Image preview
                content = `
                    <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto m-4">
                        <div class="flex items-center justify-between p-4 border-b">
                            <h3 class="text-lg font-semibold">${fileName}</h3>
                            <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <img src="${fileUrl}" alt="${fileName}" class="max-w-full h-auto">
                        </div>
                    </div>
                `;
            } else if (fileExtension === 'pdf') {
                // PDF preview
                content = `
                    <div class="bg-white rounded-lg w-full max-w-6xl h-full max-h-full overflow-hidden m-4 flex flex-col">
                        <div class="flex items-center justify-between p-4 border-b">
                            <h3 class="text-lg font-semibold">${fileName}</h3>
                            <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                        <div class="flex-1">
                            <iframe src="${fileUrl}" class="w-full h-full border-0"></iframe>
                        </div>
                    </div>
                `;
            }

            modal.innerHTML = content;
            modal.onclick = function(e) {
                if (e.target === modal) {
                    closePreview();
                }
            };

            document.body.appendChild(modal);
            window.currentPreviewModal = modal;
        }

        function closePreview() {
            if (window.currentPreviewModal) {
                document.body.removeChild(window.currentPreviewModal);
                window.currentPreviewModal = null;
            }
        }

        // Close preview with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.currentPreviewModal) {
                closePreview();
            }
        });

        // Print styles
        const printStyles = `
            <style media="print">
                body * { visibility: hidden; }
                .print-content, .print-content * { visibility: visible; }
                .print-content { position: absolute; left: 0; top: 0; width: 100%; }
                .no-print { display: none !important; }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', printStyles);
    </script>

    <!-- Reply Modal -->
    <div id="replyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 max-h-full overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">إضافة رد على المراسلة</h3>
                    <button type="button" onclick="closeReplyModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <form id="replyForm" action="{{ route('correspondences.reply', $correspondence) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-4">
                        <!-- Reply On Behalf Option -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                نوع الرد <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" name="reply_type" value="reply" class="mr-3" checked
                                        onchange="updateReplyPlaceholder()">
                                    <span class="text-sm font-medium">رد من الموظف</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="reply_type" value="forward" class="mr-3"
                                        onchange="updateReplyPlaceholder()">
                                    <span class="text-sm font-medium">
                                        إعادة توجيه إلى
                                        @if ($correspondence->type === 'incoming')
                                            <span
                                                class="text-blue-600 font-semibold">{{ $correspondence->from_entity }}</span>
                                        @else
                                            <span
                                                class="text-blue-600 font-semibold">{{ $correspondence->to_entity }}</span>
                                        @endif
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Reply Content -->
                        <div>
                            <label for="reply_content" class="block text-sm font-medium text-gray-700 mb-2">
                                محتوى الرد <span class="text-red-500">*</span>
                            </label>
                            <textarea id="reply_content" name="reply_content" rows="6"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="اكتب محتوى الرد هنا..." required></textarea>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label for="reply_file" class="block text-sm font-medium text-gray-700 mb-2">
                                ملف مرفق (اختياري)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors"
                                id="replyFileUploadArea">
                                <input type="file" id="reply_file" name="file"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" class="hidden"
                                    onchange="handleReplyFileSelect(this)">
                                <div id="replyFileDropZone">
                                    <i class="ri-upload-cloud-line text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-600 mb-2">اسحب الملف هنا أو اضغط للاختيار</p>
                                    <p class="text-xs text-gray-500">الحد الأقصى: 10 ميجابايت - الصيغ المدعومة: PDF, DOC,
                                        DOCX, XLS, XLSX, JPG, PNG</p>
                                </div>
                                <div id="replyFileInfo" class="hidden">
                                    <i class="ri-file-line text-2xl text-blue-600 mb-2"></i>
                                    <p id="replyFileName" class="text-gray-900 font-medium"></p>
                                    <p id="replyFileSize" class="text-sm text-gray-600"></p>
                                    <button type="button" onclick="clearReplyFile()"
                                        class="text-red-600 hover:text-red-800 text-sm mt-2">
                                        <i class="ri-delete-bin-line mr-1"></i>حذف الملف
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                حالة الرد <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="draft" class="mr-2" checked>
                                    <span class="text-sm">مسودة</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="sent" class="mr-2">
                                    <span class="text-sm">إرسال فوري</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <button type="button" onclick="closeReplyModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="ri-send-plane-line ml-2"></i>
                            حفظ الرد
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReplyModal() {
            document.getElementById('replyModal').classList.remove('hidden');
            document.getElementById('replyModal').classList.add('flex');
            document.getElementById('reply_content').focus();
            updateReplyPlaceholder(); // Update placeholder on modal open
        }

        function closeReplyModal() {
            document.getElementById('replyModal').classList.add('hidden');
            document.getElementById('replyModal').classList.remove('flex');
            document.getElementById('replyForm').reset();
            clearReplyFile();
            // Reset to reply type
            document.querySelector('input[name="reply_type"][value="reply"]').checked = true;
            updateReplyPlaceholder();
        }

        function updateReplyPlaceholder() {
            const replyType = document.querySelector('input[name="reply_type"]:checked').value;
            const replyContent = document.getElementById('reply_content');

            if (replyType === 'forward') {
                @if ($correspondence->type === 'incoming')
                    replyContent.placeholder = 'نص إعادة التوجيه إلى {{ $correspondence->from_entity }}...';
                @else
                    replyContent.placeholder = 'نص إعادة التوجيه إلى {{ $correspondence->to_entity }}...';
                @endif
            } else {
                replyContent.placeholder = 'اكتب محتوى الرد هنا...';
            }
        }

        // Close modal when clicking outside
        document.getElementById('replyModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReplyModal();
            }
        });

        // File upload handling for reply
        function handleReplyFileSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);

                // Check file size limit
                if (file.size > 10 * 1024 * 1024) {
                    alert('حجم الملف كبير جداً. الحد الأقصى هو 10 ميجابايت.');
                    input.value = '';
                    return;
                }

                document.getElementById('replyFileName').textContent = file.name;
                document.getElementById('replyFileSize').textContent = `حجم الملف: ${fileSize} ميجابايت`;
                document.getElementById('replyFileDropZone').classList.add('hidden');
                document.getElementById('replyFileInfo').classList.remove('hidden');
            }
        }

        function clearReplyFile() {
            document.getElementById('reply_file').value = '';
            document.getElementById('replyFileDropZone').classList.remove('hidden');
            document.getElementById('replyFileInfo').classList.add('hidden');
        }

        // Drag and drop for reply file
        const replyFileUploadArea = document.getElementById('replyFileUploadArea');

        replyFileUploadArea.addEventListener('click', function() {
            document.getElementById('reply_file').click();
        });

        replyFileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-400', 'bg-blue-50');
        });

        replyFileUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');
        });

        replyFileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('reply_file').files = files;
                handleReplyFileSelect(document.getElementById('reply_file'));
            }
        });

        // Handle reply form submission
        document.getElementById('replyForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-line animate-spin ml-2"></i> جاري الحفظ...';
        });
    </script>

@endsection
