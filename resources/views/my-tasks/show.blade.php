@extends('layouts.app')

@section('title', 'المعاملة - ' . $correspondence->reference_number)

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    معاملة {{ $correspondence->reference_number }}
                </h1>
                <p class="text-gray-600">
                    {{ $correspondence->subject }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('my-tasks.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ri-arrow-right-line"></i>
                    العودة
                </a>
            </div>
        </div>

        <!-- Status Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Original Correspondence -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-mail-line text-blue-600"></i>
                        تفاصيل المراسلة الأصلية
                    </h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">الرقم المرجعي</label>
                                <p class="text-gray-900 font-semibold">{{ $correspondence->reference_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">جهة الإصدار</label>
                                <p class="text-gray-900">{{ $correspondence->from_entity }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الموضوع</label>
                            <p class="text-gray-900 text-lg">{{ $correspondence->subject }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ المراسلة</label>
                                <p class="text-gray-900">{{ $correspondence->correspondence_date->format('Y/m/d') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">درجة الأهمية</label>
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                    @if($correspondence->priority === 'urgent')
                                        bg-red-100 text-red-800
                                    @elseif($correspondence->priority === 'high')
                                        bg-orange-100 text-orange-800
                                    @elseif($correspondence->priority === 'medium')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-green-100 text-green-800
                                    @endif">
                                    {{ $correspondence->priority_display }}
                                </span>
                            </div>
                        </div>

                        @if($correspondence->project)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">المشروع المرتبط</label>
                                <a href="{{ route('projects.show', $correspondence->project) }}"
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $correspondence->project->name }}
                                </a>
                            </div>
                        @endif

                        @if($correspondence->notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $correspondence->notes }}</p>
                                </div>
                            </div>
                        @endif

                        @if($correspondence->file_path)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملف مرفق</label>
                                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                                    <i class="ri-file-line text-blue-600"></i>
                                    <span class="font-medium text-blue-900">{{ $correspondence->file_name }}</span>
                                    <a href="{{ route('correspondences.download', $correspondence) }}"
                                       class="text-blue-600 hover:text-blue-800 mr-auto">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Previous Replies -->
                @if($correspondence->replies->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-reply-line text-green-600"></i>
                            الردود السابقة ({{ $correspondence->replies->count() }})
                        </h2>

                        <div class="space-y-4">
                            @foreach($correspondence->replies as $reply)
                                <div class="border-r-4 border-green-200 pr-4 pb-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-medium text-gray-900">{{ $reply->user->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $reply->created_at->format('Y/m/d H:i') }}</span>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            @if($reply->status === 'sent')
                                                bg-green-100 text-green-800
                                            @else
                                                bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ $reply->status_display }}
                                        </span>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg mb-2">
                                        <p class="text-gray-900 whitespace-pre-wrap">{{ $reply->reply_content }}</p>
                                    </div>
                                    @if($reply->file_path)
                                        <div class="flex items-center gap-2 text-sm text-blue-600">
                                            <i class="ri-attachment-line"></i>
                                            <a href="{{ route('my-tasks.download-reply', $reply) }}"
                                               class="hover:text-blue-800">
                                                {{ $reply->file_name }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Reply Form -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-reply-line text-blue-600"></i>
                        كتابة رد جديد
                    </h2>

                    <form method="POST" action="{{ route('my-tasks.reply', $correspondence) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-4">
                            <!-- Reply Content -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    محتوى الرد <span class="text-red-500">*</span>
                                </label>
                                <textarea name="reply_content" rows="6" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="اكتب ردك هنا...">{{ old('reply_content') }}</textarea>
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    رفع ملف (اختياري)
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors"
                                     id="file-upload-area">
                                    <input type="file" name="file" id="reply_file" class="hidden"
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                           onchange="handleFileSelect(this)">
                                    <div class="cursor-pointer" onclick="document.getElementById('reply_file').click()">
                                        <i class="ri-upload-cloud-2-line text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-600">اضغط لاختيار ملف</p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, XLS, أو صور (الحد الأقصى: 10 ميجابايت)</p>
                                    </div>
                                    <div id="file-info" class="hidden mt-3 p-3 bg-blue-50 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <i class="ri-file-line text-blue-600"></i>
                                            <span id="file-name" class="text-sm font-medium text-blue-900"></span>
                                            <button type="button" onclick="removeFile()"
                                                    class="text-red-600 hover:text-red-800 mr-auto">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t">
                                <div class="flex items-center gap-3">
                                    <button type="submit" name="status" value="draft"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                                        <i class="ri-draft-line"></i>
                                        حفظ كمسودة
                                    </button>
                                    <button type="submit" name="status" value="sent"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                                        <i class="ri-send-plane-line"></i>
                                        إرسال الرد
                                    </button>
                                </div>

                                <a href="{{ route('my-tasks.index') }}"
                                   class="text-gray-600 hover:text-gray-800">
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات المعاملة</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة الحالية</label>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                @if($correspondence->status === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @elseif($correspondence->status === 'in_progress')
                                    bg-blue-100 text-blue-800
                                @elseif($correspondence->status === 'replied')
                                    bg-green-100 text-green-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif">
                                {{ $correspondence->status_display }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">منشئ المراسلة</label>
                            <p class="text-gray-900">{{ $correspondence->user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الإنشاء</label>
                            <p class="text-gray-900 text-sm">{{ $correspondence->created_at->format('Y/m/d H:i') }}</p>
                        </div>

                        @if($correspondence->replied_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ آخر رد</label>
                                <p class="text-gray-900 text-sm">{{ $correspondence->replied_at ? $correspondence->replied_at->format('Y/m/d H:i') : 'لم يتم الرد بعد' }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Status Update -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">تحديث سريع للحالة</h3>

                    <form method="POST" action="{{ route('my-tasks.update-status', $correspondence) }}">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-4">
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pending" {{ $correspondence->status === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                                <option value="in_progress" {{ $correspondence->status === 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                                <option value="replied" {{ $correspondence->status === 'replied' ? 'selected' : '' }}>تم الرد</option>
                                <option value="closed" {{ $correspondence->status === 'closed' ? 'selected' : '' }}>مغلق</option>
                            </select>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                تحديث الحالة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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
            document.getElementById('reply_file').value = '';
            document.getElementById('file-upload-area').querySelector('.cursor-pointer').classList.remove('hidden');
            document.getElementById('file-info').classList.add('hidden');
        }
    </script>
@endsection
