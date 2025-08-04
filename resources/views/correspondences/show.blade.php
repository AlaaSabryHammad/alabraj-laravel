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
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
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
                        @if($correspondence->external_number)
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

                        @if($correspondence->type === 'incoming')
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
                            @if($correspondence->assignedEmployee)
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
                                @if($correspondence->priority === 'urgent')
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
                        @if($correspondence->project)
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
                        @if($correspondence->notes)
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
                @if($correspondence->replies && $correspondence->replies->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border p-6 mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ri-reply-line text-green-600"></i>
                            الردود ({{ $correspondence->replies->count() }})
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
                                            <i class="ri-{{ $reply->status === 'sent' ? 'check-double-line' : 'draft-line' }}"></i>
                                            {{ $reply->status_display }}
                                        </span>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg mb-2">
                                        <p class="text-gray-900 whitespace-pre-wrap">{{ $reply->reply_content }}</p>
                                    </div>
                                    @if($reply->file_path)
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="ri-attachment-line text-blue-600"></i>
                                            <span class="text-blue-600">{{ $reply->file_name }}</span>
                                            @if($reply->status === 'sent')
                                                <span class="text-green-600">(تم الإرسال)</span>
                                            @else
                                                <span class="text-yellow-600">(مسودة)</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- File Attachment -->
                @if($correspondence->file_path)
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
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                @if($correspondence->type === 'incoming')
                                    bg-green-100 text-green-800
                                @else
                                    bg-blue-100 text-blue-800
                                @endif">
                                @if($correspondence->type === 'incoming')
                                    <i class="ri-inbox-line"></i>
                                    واردة
                                @else
                                    <i class="ri-send-plane-line"></i>
                                    صادرة
                                @endif
                            </span>
                        </div>

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
                        @if($correspondence->updated_at != $correspondence->created_at)
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

                        @if($correspondence->file_path)
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
@endsection
