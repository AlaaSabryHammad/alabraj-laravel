@extends('layouts.app')

@section('title', 'إضافة مراسلة ' . ($type === 'incoming' ? 'واردة' : 'صادرة'))

@section('content')
    <div class="p-6" dir="rtl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    إضافة مراسلة {{ $type === 'incoming' ? 'واردة' : 'صادرة' }}
                </h1>
                <p class="text-gray-600">
                    {{ $type === 'incoming' ? 'تسجيل مراسلة واردة جديدة' : 'تسجيل مراسلة صادرة جديدة' }}
                </p>
            </div>
            <a href="{{ route('correspondences.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="ri-arrow-right-line"></i>
                العودة
            </a>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('correspondences.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Reference Number (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            الرقم المرجعي (تلقائي)
                        </label>
                        <input type="text" value="{{ $referenceNumber }}" readonly
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                    </div>

                    <!-- External Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $type === 'incoming' ? 'رقم الوارد' : 'رقم الصادر' }}
                        </label>
                        <input type="text" name="external_number" value="{{ old('external_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل {{ $type === 'incoming' ? 'رقم الوارد' : 'رقم الصادر' }}">
                    </div>

                    <!-- Subject -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            موضوع المراسلة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل موضوع المراسلة">
                    </div>

                    @if($type === 'incoming')
                        <!-- From Entity (For Incoming) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                جهة الإصدار <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="from_entity" value="{{ old('from_entity') }}" required
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
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
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
                            <input type="text" name="to_entity" value="{{ old('to_entity') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="أدخل الجهة المرسل إليها">
                        </div>
                    @endif

                    <!-- Correspondence Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ المراسلة <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="correspondence_date" value="{{ old('correspondence_date', now()->format('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            درجة الأهمية <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>متوسطة</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>عالية</option>
                            <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>عاجل</option>
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
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                        {{ (old('project_id', $selectedProjectId ?? '') == $project->id) ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            رفع ملف المراسلة
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                             id="file-upload-area">
                            <input type="file" name="file" id="correspondence_file" class="hidden"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                   onchange="handleFileSelect(this)">
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
                                  placeholder="أدخل أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t">
                    <a href="{{ route('correspondences.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-save-line"></i>
                        حفظ المراسلة
                    </button>
                </div>
            </div>
        </form>
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
