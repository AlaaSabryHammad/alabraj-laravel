@extends('layouts.app')

@section('title', 'البروفايل الشخصي')

@push('styles')
    <style>
        .profile-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><path d="M0,100 C150,20 350,80 500,40 C650,0 850,60 1000,20 L1000,100 Z"/></svg>');
            background-size: cover;
        }

        .profile-avatar {
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .info-card {
            transition: all 0.3s ease;
            background: white;
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: rotate(45deg);
        }

        .section-header {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .section-header::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, #3b82f6, #1d4ed8);
            border-radius: 2px;
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 10px;
            height: 10px;
            background: #3b82f6;
            border-radius: 50%;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1rem;
            width: 2px;
            height: calc(100% + 0.5rem);
            background: #e5e7eb;
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .progress-bar {
            background: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(to right, #3b82f6, #1d4ed8);
            transition: width 0.5s ease;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideInUp 0.6s ease-out;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Profile Header -->
        <div class="profile-header text-white">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8 md:space-x-reverse">
                    <div class="profile-avatar">
                        @if ($user->employee && $user->employee->photo)
                            <img src="{{ Storage::url($user->employee->photo) }}" alt="{{ $user->name }}"
                                class="w-32 h-32 rounded-full border-4 border-white shadow-2xl object-cover">
                        @elseif ($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                class="w-32 h-32 rounded-full border-4 border-white shadow-2xl object-cover">
                        @else
                            <div
                                class="w-32 h-32 rounded-full border-4 border-white shadow-2xl bg-white bg-opacity-20 flex items-center justify-center">
                                <i class="fas fa-user text-white text-4xl"></i>
                            </div>
                        @endif
                        <div
                            class="absolute -bottom-2 -right-2 bg-green-500 w-8 h-8 rounded-full border-4 border-white flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 text-center md:text-right">
                        <h1 class="text-4xl font-bold mb-3">{{ $user->name }}</h1>
                        <p class="text-white text-opacity-90 mb-2 text-lg">{{ $user->email }}</p>
                        <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-4">
                            @if ($user->role)
                                <span class="badge badge-info">
                                    <i class="fas fa-user-tie ml-1"></i>
                                    {{ $user->getCurrentRoleDisplayName() }}
                                </span>
                            @endif
                            @if ($user->employee && $user->employee->department)
                                <span class="badge badge-warning">
                                    <i class="fas fa-building ml-1"></i>
                                    {{ $user->employee->department }}
                                </span>
                            @endif
                            <span class="badge badge-success">
                                <i class="fas fa-circle text-green-400 ml-1"></i>
                                نشط
                            </span>
                        </div>
                        @if ($user->employee && $user->employee->position)
                            <p class="text-white text-opacity-80 text-lg">{{ $user->employee->position }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Profile Info -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Personal Information Card -->
                    <div class="info-card rounded-xl shadow-xl p-8 animate-slide-in">
                        <div class="section-header">
                            <h2 class="text-2xl font-bold text-gray-900">المعلومات الشخصية</h2>
                        </div>

                        <form id="profileForm">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">الاسم الكامل</label>
                                    <input type="text" name="name" id="userName" value="{{ $user->name }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                        readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">البريد الإلكتروني</label>
                                    <input type="email" name="email" id="userEmail" value="{{ $user->email }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                        readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">رقم الهاتف</label>
                                    <input type="text" name="phone" id="userPhone"
                                        value="{{ $user->employee->phone ?? ($user->phone ?? 'غير متوفر') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                        readonly>
                                </div>
                                <div>
                                    <input type="file" name="avatar" id="userAvatar" accept="image/*"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                        style="display: none;">
                                </div>
                            </div>

                            <div id="profileButtons" style="display: none;" class="flex space-x-4 space-x-reverse">
                                <button type="button" onclick="saveProfile()"
                                    class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-xl text-sm font-medium hover:from-green-700 hover:to-green-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-save ml-2"></i>
                                    حفظ التغييرات
                                </button>
                                <button type="button" onclick="cancelEdit()"
                                    class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-xl text-sm font-medium hover:from-gray-600 hover:to-gray-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-times ml-2"></i>
                                    إلغاء
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Employee Photos Section -->
                    @if ($user->employee)
                        <div class="info-card rounded-xl shadow-xl p-8 animate-slide-in">
                            <div class="section-header">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                                    <i class="fas fa-images text-blue-600 ml-2"></i>
                                    الصور الشخصية والوثائق
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Personal Photo -->
                                @if ($user->employee->photo)
                                    <div
                                        class="photo-item bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl hover:shadow-lg transition-all duration-300">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-semibold text-gray-700">
                                                <i class="fas fa-user text-blue-600 ml-1"></i>
                                                الصورة الشخصية
                                            </h3>
                                            <button
                                                onclick="viewImage('{{ Storage::url($user->employee->photo) }}', 'الصورة الشخصية')"
                                                class="text-blue-600 hover:text-blue-800 transition-colors">
                                                <i class="fas fa-expand-alt text-sm"></i>
                                            </button>
                                        </div>
                                        <img src="{{ Storage::url($user->employee->photo) }}" alt="الصورة الشخصية"
                                            class="w-full h-32 object-cover rounded-lg shadow-md hover:scale-105 transition-transform cursor-pointer"
                                            onclick="viewImage('{{ Storage::url($user->employee->photo) }}', 'الصورة الشخصية')">
                                    </div>
                                @endif

                                <!-- National ID Photo -->
                                @if ($user->employee->national_id_photo)
                                    <div
                                        class="photo-item bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl hover:shadow-lg transition-all duration-300">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-semibold text-gray-700">
                                                <i class="fas fa-id-card text-green-600 ml-1"></i>
                                                صورة الهوية الوطنية
                                            </h3>
                                            <button
                                                onclick="viewImage('{{ Storage::url($user->employee->national_id_photo) }}', 'صورة الهوية الوطنية')"
                                                class="text-green-600 hover:text-green-800 transition-colors">
                                                <i class="fas fa-expand-alt text-sm"></i>
                                            </button>
                                        </div>
                                        <img src="{{ Storage::url($user->employee->national_id_photo) }}"
                                            alt="صورة الهوية الوطنية"
                                            class="w-full h-32 object-cover rounded-lg shadow-md hover:scale-105 transition-transform cursor-pointer"
                                            onclick="viewImage('{{ Storage::url($user->employee->national_id_photo) }}', 'صورة الهوية الوطنية')">
                                    </div>
                                @endif

                                <!-- Passport Photo -->
                                @if ($user->employee->passport_photo)
                                    <div
                                        class="photo-item bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl hover:shadow-lg transition-all duration-300">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-semibold text-gray-700">
                                                <i class="fas fa-passport text-purple-600 ml-1"></i>
                                                صورة جواز السفر
                                            </h3>
                                            <button
                                                onclick="viewImage('{{ Storage::url($user->employee->passport_photo) }}', 'صورة جواز السفر')"
                                                class="text-purple-600 hover:text-purple-800 transition-colors">
                                                <i class="fas fa-expand-alt text-sm"></i>
                                            </button>
                                        </div>
                                        <img src="{{ Storage::url($user->employee->passport_photo) }}"
                                            alt="صورة جواز السفر"
                                            class="w-full h-32 object-cover rounded-lg shadow-md hover:scale-105 transition-transform cursor-pointer"
                                            onclick="viewImage('{{ Storage::url($user->employee->passport_photo) }}', 'صورة جواز السفر')">
                                    </div>
                                @endif

                                <!-- Work Permit Photo -->
                                @if ($user->employee->work_permit_photo)
                                    <div
                                        class="photo-item bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-xl hover:shadow-lg transition-all duration-300">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-semibold text-gray-700">
                                                <i class="fas fa-file-contract text-orange-600 ml-1"></i>
                                                صورة رخصة العمل
                                            </h3>
                                            <button
                                                onclick="viewImage('{{ Storage::url($user->employee->work_permit_photo) }}', 'صورة رخصة العمل')"
                                                class="text-orange-600 hover:text-orange-800 transition-colors">
                                                <i class="fas fa-expand-alt text-sm"></i>
                                            </button>
                                        </div>
                                        <img src="{{ Storage::url($user->employee->work_permit_photo) }}"
                                            alt="صورة رخصة العمل"
                                            class="w-full h-32 object-cover rounded-lg shadow-md hover:scale-105 transition-transform cursor-pointer"
                                            onclick="viewImage('{{ Storage::url($user->employee->work_permit_photo) }}', 'صورة رخصة العمل')">
                                    </div>
                                @endif

                                <!-- Driving License Photo -->
                                @if ($user->employee->driving_license_photo)
                                    <div
                                        class="photo-item bg-gradient-to-br from-teal-50 to-teal-100 p-4 rounded-xl hover:shadow-lg transition-all duration-300">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-semibold text-gray-700">
                                                <i class="fas fa-car text-teal-600 ml-1"></i>
                                                صورة رخصة القيادة
                                            </h3>
                                            <button
                                                onclick="viewImage('{{ Storage::url($user->employee->driving_license_photo) }}', 'صورة رخصة القيادة')"
                                                class="text-teal-600 hover:text-teal-800 transition-colors">
                                                <i class="fas fa-expand-alt text-sm"></i>
                                            </button>
                                        </div>
                                        <img src="{{ Storage::url($user->employee->driving_license_photo) }}"
                                            alt="صورة رخصة القيادة"
                                            class="w-full h-32 object-cover rounded-lg shadow-md hover:scale-105 transition-transform cursor-pointer"
                                            onclick="viewImage('{{ Storage::url($user->employee->driving_license_photo) }}', 'صورة رخصة القيادة')">
                                    </div>
                                @endif
                            </div>

                            <!-- No Photos Message -->
                            @if (
                                !$user->employee->photo &&
                                    !$user->employee->national_id_photo &&
                                    !$user->employee->passport_photo &&
                                    !$user->employee->work_permit_photo &&
                                    !$user->employee->driving_license_photo)
                                <div class="text-center py-12">
                                    <i class="fas fa-images text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">لا توجد صور شخصية متاحة</p>
                                    <p class="text-gray-400 text-sm mt-2">يمكن إضافة الصور من خلال قسم إدارة الموظفين</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Employee Information Card -->
                    @if ($user->employee)
                        <div class="info-card rounded-xl shadow-xl p-8 animate-slide-in">
                            <div class="section-header">
                                <h2 class="text-2xl font-bold text-gray-900">المعلومات الوظيفية</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-briefcase text-blue-600 ml-2"></i>
                                        <span class="text-sm font-medium text-gray-600">المنصب</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $user->employee->position ?? 'غير محدد' }}</p>
                                </div>
                                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-building text-green-600 ml-2"></i>
                                        <span class="text-sm font-medium text-gray-600">القسم</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $user->employee->department ?? 'غير محدد' }}</p>
                                </div>
                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-calendar-alt text-purple-600 ml-2"></i>
                                        <span class="text-sm font-medium text-gray-600">تاريخ التوظيف</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $user->employee->hire_date ? \Carbon\Carbon::parse($user->employee->hire_date)->format('Y-m-d') : 'غير محدد' }}
                                    </p>
                                </div>
                                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-money-bill-wave text-yellow-600 ml-2"></i>
                                        <span class="text-sm font-medium text-gray-600">الراتب</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $user->employee->salary ? number_format($user->employee->salary) . ' ريال' : 'غير محدد' }}
                                    </p>
                                </div>
                                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-clock text-indigo-600 ml-2"></i>
                                        <span class="text-sm font-medium text-gray-600">ساعات العمل</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $user->employee->working_hours ?? 'غير محدد' }} ساعة/يوم</p>
                                </div>
                                <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-xl">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-id-card text-red-600 ml-2"></i>
                                        <span class="text-sm font-medium text-gray-600">رقم الهوية</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $user->employee->national_id ?? 'غير محدد' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Password Change Card -->
                    <div class="info-card rounded-xl shadow-xl p-8 animate-slide-in">
                        <div class="section-header">
                            <h2 class="text-2xl font-bold text-gray-900">تغيير كلمة المرور</h2>
                        </div>

                        <form id="passwordForm">
                            @csrf
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">كلمة المرور
                                        الحالية</label>
                                    <input type="password" name="current_password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">كلمة المرور
                                        الجديدة</label>
                                    <input type="password" name="new_password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">تأكيد كلمة المرور
                                        الجديدة</label>
                                    <input type="password" name="new_password_confirmation"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                        required>
                                </div>
                            </div>

                            <button type="button" onclick="changePassword()"
                                class="mt-6 bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-3 rounded-xl text-sm font-medium hover:from-orange-700 hover:to-orange-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-key ml-2"></i>
                                تحديث كلمة المرور
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Professional Summary -->
                    <div class="stat-card rounded-xl shadow-xl p-6 relative overflow-hidden animate-slide-in">
                        <div class="relative z-10">
                            <h3 class="text-xl font-bold mb-4">نظرة عامة مهنية</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-white text-opacity-90">سنوات الخبرة</span>
                                    <span class="font-bold text-xl">
                                        @if ($user->employee && $user->employee->hire_date)
                                            {{ round(\Carbon\Carbon::parse($user->employee->hire_date)->diffInYears(now())) }}
                                        @else
                                            0
                                        @endif
                                        سنة
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-white text-opacity-90">نوع التوظيف</span>
                                    <span class="font-bold">دوام كامل</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-white text-opacity-90">الحالة</span>
                                    <span
                                        class="bg-green-400 text-green-900 px-2 py-1 rounded-full text-xs font-bold">نشط</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="info-card rounded-xl shadow-xl p-6 animate-slide-in">
                        <div class="section-header">
                            <h3 class="text-xl font-bold text-gray-900">إحصائيات سريعة</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus text-blue-600 ml-2"></i>
                                    <span class="text-gray-600">تاريخ التسجيل</span>
                                </div>
                                <span class="text-gray-900 font-semibold">{{ $user->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-sync-alt text-green-600 ml-2"></i>
                                    <span class="text-gray-600">آخر تحديث</span>
                                </div>
                                <span class="text-gray-900 font-semibold">{{ $user->updated_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-user-check text-purple-600 ml-2"></i>
                                    <span class="text-gray-600">حالة الحساب</span>
                                </div>
                                <span class="badge badge-success">نشط</span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    @if ($user->employee)
                        <div class="info-card rounded-xl shadow-xl p-6 animate-slide-in">
                            <div class="section-header">
                                <h3 class="text-xl font-bold text-gray-900">المعلومات الشخصية</h3>
                            </div>
                            <div class="space-y-4">
                                @if ($user->employee->nationality)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-flag text-green-600 ml-2"></i>
                                            <span class="text-gray-600">الجنسية</span>
                                        </div>
                                        <span
                                            class="text-gray-900 font-semibold">{{ $user->employee->nationality }}</span>
                                    </div>
                                @endif
                                @if ($user->employee->marital_status)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-heart text-red-600 ml-2"></i>
                                            <span class="text-gray-600">الحالة الاجتماعية</span>
                                        </div>
                                        <span
                                            class="text-gray-900 font-semibold">{{ $user->employee->marital_status }}</span>
                                    </div>
                                @endif
                                @if ($user->employee->birth_date)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-birthday-cake text-purple-600 ml-2"></i>
                                            <span class="text-gray-600">العمر</span>
                                        </div>
                                        <span class="text-gray-900 font-semibold">
                                            {{ \Carbon\Carbon::parse($user->employee->birth_date)->age }} سنة
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Security & Progress -->
                    <div class="info-card rounded-xl shadow-xl p-6 animate-slide-in">
                        <div class="section-header">
                            <h3 class="text-xl font-bold text-gray-900">الأمان والتقدم</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center text-green-700">
                                    <i class="fas fa-check-circle ml-2"></i>
                                    <span class="text-sm font-medium">البريد الإلكتروني مؤكد</span>
                                </div>
                                <i class="fas fa-shield-alt text-green-600"></i>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center text-blue-700">
                                    <i class="fas fa-key ml-2"></i>
                                    <span class="text-sm font-medium">كلمة مرور قوية</span>
                                </div>
                                <i class="fas fa-lock text-blue-600"></i>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                <div class="flex items-center text-purple-700">
                                    <i class="fas fa-user-shield ml-2"></i>
                                    <span class="text-sm font-medium">حساب محمي</span>
                                </div>
                                <i class="fas fa-certificate text-purple-600"></i>
                            </div>

                            <!-- Profile Completion -->
                            <div class="mt-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">اكتمال البروفايل</span>
                                    <span class="text-sm font-bold text-gray-900">
                                        @php
                                            $completion = 0;
                                            if ($user->name) {
                                                $completion += 20;
                                            }
                                            if ($user->email) {
                                                $completion += 20;
                                            }
                                            if (($user->employee && $user->employee->phone) || $user->phone) {
                                                $completion += 20;
                                            }
                                            if ($user->employee && $user->employee->position) {
                                                $completion += 20;
                                            }
                                            if ($user->employee && $user->employee->department) {
                                                $completion += 20;
                                            }
                                        @endphp
                                        {{ $completion }}%
                                    </span>
                                </div>
                                <div class="progress-bar h-3">
                                    <div class="progress-fill h-full rounded-full" style="width: {{ $completion }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Timeline -->
                    <div class="info-card rounded-xl shadow-xl p-6 animate-slide-in">
                        <div class="section-header">
                            <h3 class="text-xl font-bold text-gray-900">النشاط الأخير</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="timeline-item">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">تسجيل الدخول للنظام</span>
                                    <span class="text-xs text-gray-400">اليوم</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">تحديث البروفايل</span>
                                    <span class="text-xs text-gray-400">{{ $user->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">إنشاء الحساب</span>
                                    <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Viewer Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center"
        onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-full p-4" onclick="event.stopPropagation()">
            <div class="relative">
                <button onclick="closeImageModal()"
                    class="absolute top-2 right-2 text-white bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75 transition-all z-10">
                    <i class="fas fa-times text-xl"></i>
                </button>
                <img id="modalImage" src="" alt=""
                    class="max-w-full max-h-screen object-contain rounded-lg shadow-2xl">
            </div>
            <div class="text-center mt-4">
                <h3 id="modalTitle" class="text-white text-lg font-semibold"></h3>
                <div class="mt-2 flex justify-center space-x-4 space-x-reverse">
                    <button onclick="downloadImage()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-download ml-1"></i>
                        تحميل
                    </button>
                    <button onclick="closeImageModal()"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-times ml-1"></i>
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50"></div>

    @push('scripts')
        <script>
            let isEditing = false;
            const originalData = {
                name: '{{ $user->name }}',
                email: '{{ $user->email }}',
                phone: '{{ $user->phone ?? '' }}'
            };

            function editProfile() {
                isEditing = true;

                // Enable inputs
                document.getElementById('userName').readOnly = false;
                document.getElementById('userEmail').readOnly = false;
                document.getElementById('userPhone').readOnly = false;
                document.getElementById('userAvatar').style.display = 'block';

                // Show buttons
                document.getElementById('profileButtons').style.display = 'flex';

                // Add focus styles
                document.querySelectorAll('#profileForm input:not([type="file"])').forEach(input => {
                    input.classList.add('bg-blue-50', 'border-blue-300');
                });
            }

            function cancelEdit() {
                isEditing = false;

                // Restore original data
                document.getElementById('userName').value = originalData.name;
                document.getElementById('userEmail').value = originalData.email;
                document.getElementById('userPhone').value = originalData.phone;

                // Disable inputs
                document.getElementById('userName').readOnly = true;
                document.getElementById('userEmail').readOnly = true;
                document.getElementById('userPhone').readOnly = true;
                document.getElementById('userAvatar').style.display = 'none';

                // Hide buttons
                document.getElementById('profileButtons').style.display = 'none';

                // Remove focus styles
                document.querySelectorAll('#profileForm input:not([type="file"])').forEach(input => {
                    input.classList.remove('bg-blue-50', 'border-blue-300');
                });
            }

            function saveProfile() {
                const formData = new FormData();
                formData.append('name', document.getElementById('userName').value);
                formData.append('email', document.getElementById('userEmail').value);
                formData.append('phone', document.getElementById('userPhone').value);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                const avatarFile = document.getElementById('userAvatar').files[0];
                if (avatarFile) {
                    formData.append('avatar', avatarFile);
                }

                fetch('/profile/update', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('تم تحديث البروفايل بنجاح', 'success');

                            // Update original data
                            originalData.name = data.user.name;
                            originalData.email = data.user.email;
                            originalData.phone = data.user.phone || '';

                            cancelEdit();

                            // Reload page after 2 seconds to show avatar changes
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showAlert(data.message || 'حدث خطأ أثناء التحديث', 'error');
                        }
                    })
                    .catch(error => {
                        showAlert('حدث خطأ أثناء التحديث', 'error');
                    });
            }

            function changePassword() {
                const formData = new FormData(document.getElementById('passwordForm'));

                fetch('/profile/password', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('تم تحديث كلمة المرور بنجاح', 'success');
                            document.getElementById('passwordForm').reset();
                        } else {
                            showAlert(data.message || 'حدث خطأ أثناء تحديث كلمة المرور', 'error');
                        }
                    })
                    .catch(error => {
                        showAlert('حدث خطأ أثناء تحديث كلمة المرور', 'error');
                    });
            }

            function showAlert(message, type) {
                const alertContainer = document.getElementById('alertContainer');
                const alertDiv = document.createElement('div');

                const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

                alertDiv.innerHTML = `
        <div class="${bgColor} text-white px-6 py-3 rounded-lg shadow-lg">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} ml-2"></i>
            ${message}
        </div>
    `;

                alertContainer.appendChild(alertDiv);

                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }

            // Image Modal Functions
            let currentImageSrc = '';

            function viewImage(imageSrc, title) {
                currentImageSrc = imageSrc;
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('modalTitle').textContent = title;
                document.getElementById('imageModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeImageModal() {
                document.getElementById('imageModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function downloadImage() {
                if (currentImageSrc) {
                    const link = document.createElement('a');
                    link.href = currentImageSrc;
                    link.download = document.getElementById('modalTitle').textContent + '.jpg';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeImageModal();
                }
            });
        </script>
    @endpush
@endsection
