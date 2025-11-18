@extends('layouts.app')

@section('title', 'تحرير المراسلة - ' . $correspondence->reference_number)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    تحرير مراسلة {{ $correspondence->type === 'incoming' ? 'واردة' : 'صادرة' }}
                </h1>
                <p class="text-gray-600">
                    الرقم المرجعي: {{ $correspondence->reference_number }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('correspondences.show', $correspondence) }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-eye-line"></i>
                    عرض
                </a>
                <a href="{{ route('correspondences.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-arrow-right-line"></i>
                    العودة
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('correspondences.update', $correspondence) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Reference Number (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            الرقم المرجعي
                        </label>
                        <input type="text" value="{{ $correspondence->reference_number }}" readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                    </div>

                    <!-- Type Display (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            نوع المراسلة
                        </label>
                        <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
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
                    </div>

                    <!-- External Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $correspondence->type === 'incoming' ? 'رقم الوارد' : 'رقم الصادر' }}
                        </label>
                        <input type="text" name="external_number"
                            value="{{ old('external_number', $correspondence->external_number) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="أدخل {{ $correspondence->type === 'incoming' ? 'رقم الوارد' : 'رقم الصادر' }}">
                    </div>

                    <!-- Subject -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            موضوع المراسلة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject', $correspondence->subject) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="أدخل موضوع المراسلة">
                    </div>

                    @if ($correspondence->type === 'incoming')
                        <!-- From Entity (For Incoming) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                جهة الإصدار <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="from_entity"
                                value="{{ old('from_entity', $correspondence->from_entity) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="أدخل جهة الإصدار">
                        </div>

                        <!-- Assigned To (For Incoming) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                المسؤول الموجه إليه <span class="text-red-500">*</span>
                            </label>
                            <select name="assigned_to" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">اختر المسؤول</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('assigned_to', $correspondence->assigned_to) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <!-- To Entity (For Outgoing) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                الجهة المرسل إليها <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="to_entity"
                                value="{{ old('to_entity', $correspondence->to_entity) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="أدخل الجهة المرسل إليها">
                        </div>
                    @endif

                    <!-- Correspondence Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ المراسلة <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="correspondence_date"
                            value="{{ old('correspondence_date', $correspondence->correspondence_date->format('Y-m-d')) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            درجة الأهمية <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="low"
                                {{ old('priority', $correspondence->priority) === 'low' ? 'selected' : '' }}>منخفضة
                            </option>
                            <option value="medium"
                                {{ old('priority', $correspondence->priority) === 'medium' ? 'selected' : '' }}>متوسطة
                            </option>
                            <option value="high"
                                {{ old('priority', $correspondence->priority) === 'high' ? 'selected' : '' }}>عالية
                            </option>
                            <option value="urgent"
                                {{ old('priority', $correspondence->priority) === 'urgent' ? 'selected' : '' }}>عاجل
                            </option>
                        </select>
                    </div>

                    <!-- Project (Optional) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            المشروع (اختياري)
                        </label>
                        <select name="project_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">لا يوجد مشروع محدد</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', $correspondence->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Current File Display -->
                    @if ($correspondence->file_path)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                الملف الحالي
                            </label>
                            <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i class="ri-file-line text-blue-600"></i>
                                    <div>
                                        <p class="font-medium text-blue-900">{{ $correspondence->file_name }}</p>
                                        <p class="text-sm text-blue-700">{{ $correspondence->file_size_display }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('correspondences.download', $correspondence) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i class="ri-download-line"></i>
                                    </a>
                                    <label class="cursor-pointer text-green-600 hover:text-green-800">
                                        <i class="ri-refresh-line"></i>
                                        <input type="checkbox" name="replace_file" class="hidden"
                                            onchange="toggleFileUpload(this)">
                                    </label>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                اضغط على <i class="ri-refresh-line"></i> لاستبدال الملف الحالي
                            </p>
                        </div>
                    @endif

                    <!-- File Upload -->
                    <div class="md:col-span-2" id="file-upload-section"
                        style="{{ $correspondence->file_path ? 'display: none;' : '' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $correspondence->file_path ? 'ملف جديد (استبدال)' : 'رفع ملف المراسلة' }}
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                            id="file-upload-area">
                            <input type="file" name="file" id="correspondence_file" class="hidden"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" onchange="handleFileSelect(this)">
                            <div class="cursor-pointer" onclick="document.getElementById('correspondence_file').click()">
                                <i class="ri-upload-cloud-2-line text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600">اضغط لاختيار ملف أو اسحب الملف هنا</p>
                                <p class="text-xs text-gray-500 mt-1">PDF, DOC, XLS, أو صور (الحد الأقصى: 10 ميجابايت)</p>
                            </div>
                            <div id="file-info" class="hidden mt-3 p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <i class="ri-file-line text-blue-600"></i>
                                    <span id="file-name" class="text-sm font-medium text-blue-900"></span>
                                    <button type="button" onclick="removeFile()"
                                        class="text-red-600 hover:text-red-800 ml-auto">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات
                        </label>
                        <textarea name="notes" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="أدخل أي ملاحظات إضافية">{{ old('notes', $correspondence->notes) }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t">
                    <a href="{{ route('correspondences.show', $correspondence) }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-save-line"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>

        <!-- Current Replies Section -->
        @if ($correspondence->replies && $correspondence->replies->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ri-reply-line text-green-600"></i>
                    الردود الحالية ({{ $correspondence->replies->count() }})
                </h2>

                <div class="space-y-4">
                    @foreach ($correspondence->replies as $reply)
                        <div
                            class="border-r-4 border-green-200 pr-4 pb-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-medium text-gray-900">{{ $reply->user->name }}</span>
                                <span class="text-xs text-gray-500">{{ $reply->created_at->format('Y/m/d H:i') }}</span>

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
                                    {{ $reply->status === 'sent' ? 'مرسل' : 'مسودة' }}
                                </span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg mb-2">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $reply->reply_content }}</p>
                            </div>
                            @if ($reply->file_path)
                                <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
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
                                        <span class="text-gray-600 text-xs">({{ $reply->file_size_display }})</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'pdf']))
                                            <button
                                                onclick="previewFile('{{ asset('storage/' . $reply->file_path) }}', '{{ $reply->file_name }}')"
                                                class="text-blue-600 hover:text-blue-800 p-1 rounded transition-colors"
                                                title="معاينة الملف">
                                                <i class="ri-eye-line text-lg"></i>
                                            </button>
                                        @endif
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
    </div>

    <script>
        function toggleFileUpload(checkbox) {
            const fileUploadSection = document.getElementById('file-upload-section');
            if (checkbox.checked) {
                fileUploadSection.style.display = 'block';
            } else {
                fileUploadSection.style.display = 'none';
                removeFile();
            }
        }

        function handleFileSelect(input) {
            const file = input.files[0];
            if (file) {
                // Check file size (10MB limit)
                if (file.size > 10 * 1024 * 1024) {
                    alert('حجم الملف كبير جداً. الحد الأقصى هو 10 ميجابايت.');
                    input.value = '';
                    return;
                }

                // Show file info
                document.getElementById('file-upload-area').querySelector('.cursor-pointer').classList.add('hidden');
                document.getElementById('file-info').classList.remove('hidden');
                document.getElementById('file-name').textContent = file.name;
            }
        }

        function removeFile() {
            document.getElementById('correspondence_file').value = '';
            document.getElementById('file-upload-area').querySelector('.cursor-pointer').classList.remove('hidden');
            document.getElementById('file-info').classList.add('hidden');
        }

        // Drag and drop functionality
        const fileUploadArea = document.getElementById('file-upload-area');

        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-400', 'bg-blue-50');
        });

        fileUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');
        });

        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('correspondence_file').files = files;
                handleFileSelect(document.getElementById('correspondence_file'));
            }
        });
    </script>

@endsection
