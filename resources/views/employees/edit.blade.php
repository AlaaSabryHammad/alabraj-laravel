@extends('layouts.app')

@section('title', 'تعديل الموظف: ' . $employee->name)

@section('content')
<div class="p-6" dir="rtl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.show', $employee) }}"
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تعديل الموظف</h1>
                <p class="text-gray-600 mt-1">تعديل بيانات الموظف: {{ $employee->name }}</p>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-user-line text-blue-600"></i>
                        المعلومات الأساسية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم الموظف <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $employee->name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- National ID -->
                        <div>
                            <label for="national_id" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الهوية الوطنية
                            </label>
                            <input type="text"
                                   id="national_id"
                                   name="national_id"
                                   value="{{ old('national_id', $employee->national_id) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('national_id') border-red-500 @enderror">
                            @error('national_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- National ID Expiry Date -->
                        <div>
                            <label for="national_id_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ انتهاء الهوية
                            </label>
                            <input type="date"
                                   id="national_id_expiry_date"
                                   name="national_id_expiry_date"
                                   value="{{ old('national_id_expiry_date', $employee->national_id_expiry_date?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('national_id_expiry_date') border-red-500 @enderror">
                            @error('national_id_expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $employee->email) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الهاتف
                            </label>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $employee->phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                المسمى الوظيفي
                            </label>
                            <input type="text"
                                   id="position"
                                   name="position"
                                   value="{{ old('position', $employee->position) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('position') border-red-500 @enderror">
                            @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                القسم
                            </label>
                            <input type="text"
                                   id="department"
                                   name="department"
                                   value="{{ old('department', $employee->department) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('department') border-red-500 @enderror">
                            @error('department')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">
                                الموقع
                            </label>
                            <select id="location_id"
                                    name="location_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location_id') border-red-500 @enderror">
                                <option value="">اختر الموقع</option>
                                @if(isset($locations))
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}"
                                                {{ old('location_id', $employee->location_id) == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }} - {{ $location->city }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('location_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location Assignment Date -->
                        <div>
                            <label for="location_assignment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ التعيين في الموقع
                            </label>
                            <input type="date"
                                   id="location_assignment_date"
                                   name="location_assignment_date"
                                   value="{{ old('location_assignment_date', $employee->location_assignment_date?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location_assignment_date') border-red-500 @enderror">
                            @error('location_assignment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hire Date -->
                        <div>
                            <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ التوظيف
                            </label>
                            <input type="date"
                                   id="hire_date"
                                   name="hire_date"
                                   value="{{ old('hire_date', optional($employee->hire_date)->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('hire_date') border-red-500 @enderror">
                            @error('hire_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Salary -->
                        <div>
                            <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">
                                الراتب الأساسي (ريال سعودي)
                            </label>
                            <input type="number"
                                   id="salary"
                                   name="salary"
                                   value="{{ old('salary', $employee->salary) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('salary') border-red-500 @enderror">
                            @error('salary')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                حالة التوظيف
                            </label>
                            <select id="status"
                                    name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                                <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            العنوان
                        </label>
                        <textarea id="address"
                                  name="address"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-file-text-line text-blue-600"></i>
                        الوثائق والصور
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Photo -->
                        <div class="space-y-4">
                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                                    الصورة الشخصية
                                </label>
                                @if($employee->photo)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $employee->photo) }}"
                                             alt="الصورة الحالية"
                                             class="w-24 h-24 object-cover rounded-lg border">
                                        <p class="text-xs text-gray-600 mt-1">الصورة الحالية</p>
                                    </div>
                                @endif
                                <input type="file"
                                       id="photo"
                                       name="photo"
                                       accept="image/*"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('photo') border-red-500 @enderror">
                                @error('photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- National ID Photo -->
                            <div>
                                <label for="national_id_photo" class="block text-sm font-medium text-gray-700 mb-2">
                                    صورة الهوية الوطنية
                                </label>
                                @if($employee->national_id_photo)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $employee->national_id_photo) }}"
                                             alt="صورة الهوية الحالية"
                                             class="w-32 h-20 object-cover rounded border">
                                        <p class="text-xs text-gray-600 mt-1">الصورة الحالية</p>
                                    </div>
                                @endif
                                <input type="file"
                                       id="national_id_photo"
                                       name="national_id_photo"
                                       accept="image/*"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('national_id_photo') border-red-500 @enderror">
                                @error('national_id_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Passport Information -->
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <i class="ri-passport-line text-green-600"></i>
                                    بيانات جواز السفر
                                </h4>

                                <div class="space-y-4">
                                    <div>
                                        <label for="passport_number" class="block text-sm font-medium text-gray-700 mb-2">
                                            رقم جواز السفر
                                        </label>
                                        <input type="text"
                                               id="passport_number"
                                               name="passport_number"
                                               value="{{ old('passport_number', $employee->passport_number) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="passport_issue_date" class="block text-sm font-medium text-gray-700 mb-2">
                                                تاريخ الإصدار
                                            </label>
                                            <input type="date"
                                                   id="passport_issue_date"
                                                   name="passport_issue_date"
                                                   value="{{ old('passport_issue_date', $employee->passport_issue_date?->format('Y-m-d')) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label for="passport_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                                تاريخ الانتهاء
                                            </label>
                                            <input type="date"
                                                   id="passport_expiry_date"
                                                   name="passport_expiry_date"
                                                   value="{{ old('passport_expiry_date', $employee->passport_expiry_date?->format('Y-m-d')) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="passport_photo" class="block text-sm font-medium text-gray-700 mb-2">
                                            صورة جواز السفر
                                        </label>
                                        @if($employee->passport_photo)
                                            <div class="mb-3">
                                                <img src="{{ asset('storage/' . $employee->passport_photo) }}"
                                                     alt="صورة الجواز الحالية"
                                                     class="w-32 h-20 object-cover rounded border">
                                                <p class="text-xs text-gray-600 mt-1">الصورة الحالية</p>
                                            </div>
                                        @endif
                                        <input type="file"
                                               id="passport_photo"
                                               name="passport_photo"
                                               accept="image/*"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Permit Section -->
                    <div class="mt-6">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="ri-briefcase-line text-purple-600"></i>
                                بيانات بطاقة التشغيل / تصريح العمل
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label for="work_permit_number" class="block text-sm font-medium text-gray-700 mb-2">
                                            رقم تصريح العمل
                                        </label>
                                        <input type="text"
                                               id="work_permit_number"
                                               name="work_permit_number"
                                               value="{{ old('work_permit_number', $employee->work_permit_number) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="work_permit_issue_date" class="block text-sm font-medium text-gray-700 mb-2">
                                                تاريخ الإصدار
                                            </label>
                                            <input type="date"
                                                   id="work_permit_issue_date"
                                                   name="work_permit_issue_date"
                                                   value="{{ old('work_permit_issue_date', $employee->work_permit_issue_date?->format('Y-m-d')) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label for="work_permit_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                                تاريخ الانتهاء
                                            </label>
                                            <input type="date"
                                                   id="work_permit_expiry_date"
                                                   name="work_permit_expiry_date"
                                                   value="{{ old('work_permit_expiry_date', $employee->work_permit_expiry_date?->format('Y-m-d')) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="work_permit_photo" class="block text-sm font-medium text-gray-700 mb-2">
                                        صورة بطاقة التشغيل
                                    </label>
                                    @if($employee->work_permit_photo)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $employee->work_permit_photo) }}"
                                                 alt="صورة بطاقة التشغيل الحالية"
                                                 class="w-32 h-20 object-cover rounded border">
                                            <p class="text-xs text-gray-600 mt-1">الصورة الحالية</p>
                                        </div>
                                    @endif
                                    <input type="file"
                                           id="work_permit_photo"
                                           name="work_permit_photo"
                                           accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Driving License Information Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-car-line text-blue-600"></i>
                        معلومات رخصة القيادة
                    </h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="driving_license_issue_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    تاريخ الإصدار
                                </label>
                                <input type="date"
                                       id="driving_license_issue_date"
                                       name="driving_license_issue_date"
                                       value="{{ old('driving_license_issue_date', $employee->driving_license_issue_date?->format('Y-m-d')) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="driving_license_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    تاريخ الانتهاء
                                </label>
                                <input type="date"
                                       id="driving_license_expiry_date"
                                       name="driving_license_expiry_date"
                                       value="{{ old('driving_license_expiry_date', $employee->driving_license_expiry_date?->format('Y-m-d')) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="driving_license_photo" class="block text-sm font-medium text-gray-700 mb-2">
                                    صورة رخصة القيادة
                                </label>
                                @if($employee->driving_license_photo)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $employee->driving_license_photo) }}"
                                             alt="صورة رخصة القيادة الحالية"
                                             class="w-32 h-20 object-cover rounded border">
                                        <p class="text-xs text-gray-600 mt-1">الصورة الحالية</p>
                                    </div>
                                @endif
                                <input type="file"
                                       id="driving_license_photo"
                                       name="driving_license_photo"
                                       accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role/Permission Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-shield-user-line text-blue-600"></i>
                        صلاحيات النظام
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                الصلاحية <span class="text-red-500">*</span>
                            </label>
                            <select id="role"
                                    name="role"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                                    required>
                                <option value="">اختر الصلاحية</option>
                                <option value="عامل" {{ old('role', $employee->role ?? '') == 'عامل' ? 'selected' : '' }}>عامل</option>
                                <option value="مشرف موقع" {{ old('role', $employee->role ?? '') == 'مشرف موقع' ? 'selected' : '' }}>مشرف موقع</option>
                                <option value="مهندس" {{ old('role', $employee->role ?? '') == 'مهندس' ? 'selected' : '' }}>مهندس</option>
                                <option value="إداري" {{ old('role', $employee->role ?? '') == 'إداري' ? 'selected' : '' }}>إداري</option>
                                <option value="مسئول رئيسي" {{ old('role', $employee->role ?? '') == 'مسئول رئيسي' ? 'selected' : '' }}>مسئول رئيسي</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sponsorship" class="block text-sm font-medium text-gray-700 mb-2">
                                الكفالة <span class="text-red-500">*</span>
                            </label>
                            <select id="sponsorship"
                                    name="sponsorship"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sponsorship') border-red-500 @enderror"
                                    required>
                                <option value="">اختر الكفالة</option>
                                <option value="شركة الأبراج للمقاولات المحدودة" {{ old('sponsorship', $employee->sponsorship ?? '') == 'شركة الأبراج للمقاولات المحدودة' ? 'selected' : '' }}>شركة الأبراج للمقاولات المحدودة</option>
                                <option value="فرع1 شركة الأبراج للمقاولات المحدودة" {{ old('sponsorship', $employee->sponsorship ?? '') == 'فرع1 شركة الأبراج للمقاولات المحدودة' ? 'selected' : '' }}>فرع1 شركة الأبراج للمقاولات المحدودة</option>
                                <option value="فرع2 شركة الأبراج للمقاولات المحدودة" {{ old('sponsorship', $employee->sponsorship ?? '') == 'فرع2 شركة الأبراج للمقاولات المحدودة' ? 'selected' : '' }}>فرع2 شركة الأبراج للمقاولات المحدودة</option>
                                <option value="مؤسسة فريق التعمير للمقاولات" {{ old('sponsorship', $employee->sponsorship ?? '') == 'مؤسسة فريق التعمير للمقاولات' ? 'selected' : '' }}>مؤسسة فريق التعمير للمقاولات</option>
                                <option value="فرع مؤسسة فريق التعمير للنقل" {{ old('sponsorship', $employee->sponsorship ?? '') == 'فرع مؤسسة فريق التعمير للنقل' ? 'selected' : '' }}>فرع مؤسسة فريق التعمير للنقل</option>
                                <option value="مؤسسة الزفاف الذهبي" {{ old('sponsorship', $employee->sponsorship ?? '') == 'مؤسسة الزفاف الذهبي' ? 'selected' : '' }}>مؤسسة الزفاف الذهبي</option>
                                <option value="مؤسسة عنوان الكادي" {{ old('sponsorship', $employee->sponsorship ?? '') == 'مؤسسة عنوان الكادي' ? 'selected' : '' }}>مؤسسة عنوان الكادي</option>
                                <option value="عمالة منزلية" {{ old('sponsorship', $employee->sponsorship ?? '') == 'عمالة منزلية' ? 'selected' : '' }}>عمالة منزلية</option>
                                <option value="عمالة كفالة خارجية تحت التجربة" {{ old('sponsorship', $employee->sponsorship ?? '') == 'عمالة كفالة خارجية تحت التجربة' ? 'selected' : '' }}>عمالة كفالة خارجية تحت التجربة</option>
                                <option value="أخرى" {{ old('sponsorship', $employee->sponsorship ?? '') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('sponsorship')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                الفئة <span class="text-red-500">*</span>
                            </label>
                            <select id="category"
                                    name="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror"
                                    required>
                                <option value="">اختر الفئة</option>
                                <option value="A+" {{ old('category', $employee->category ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A" {{ old('category', $employee->category ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('category', $employee->category ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('category', $employee->category ?? '') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('category', $employee->category ?? '') == 'D' ? 'selected' : '' }}>D</option>
                                <option value="E" {{ old('category', $employee->category ?? '') == 'E' ? 'selected' : '' }}>E</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bank Account Information Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-bank-card-line text-green-600"></i>
                        معلومات الحساب البنكي
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bank Name -->
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم البنك
                            </label>
                            <select id="bank_name"
                                    name="bank_name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bank_name') border-red-500 @enderror">
                                <option value="">اختر البنك</option>
                                <option value="البنك الأهلي السعودي" {{ old('bank_name', $employee->bank_name) == 'البنك الأهلي السعودي' ? 'selected' : '' }}>البنك الأهلي السعودي</option>
                                <option value="بنك الرياض" {{ old('bank_name', $employee->bank_name) == 'بنك الرياض' ? 'selected' : '' }}>بنك الرياض</option>
                                <option value="البنك السعودي للاستثمار" {{ old('bank_name', $employee->bank_name) == 'البنك السعودي للاستثمار' ? 'selected' : '' }}>البنك السعودي للاستثمار</option>
                                <option value="البنك السعودي الفرنسي" {{ old('bank_name', $employee->bank_name) == 'البنك السعودي الفرنسي' ? 'selected' : '' }}>البنك السعودي الفرنسي</option>
                                <option value="البنك السعودي البريطاني (ساب)" {{ old('bank_name', $employee->bank_name) == 'البنك السعودي البريطاني (ساب)' ? 'selected' : '' }}>البنك السعودي البريطاني (ساب)</option>
                                <option value="بنك سامبا" {{ old('bank_name', $employee->bank_name) == 'بنك سامبا' ? 'selected' : '' }}>بنك سامبا</option>
                                <option value="البنك الأول" {{ old('bank_name', $employee->bank_name) == 'البنك الأول' ? 'selected' : '' }}>البنك الأول</option>
                                <option value="مصرف الإنماء" {{ old('bank_name', $employee->bank_name) == 'مصرف الإنماء' ? 'selected' : '' }}>مصرف الإنماء</option>
                                <option value="مصرف الراجحي" {{ old('bank_name', $employee->bank_name) == 'مصرف الراجحي' ? 'selected' : '' }}>مصرف الراجحي</option>
                                <option value="بنك الجزيرة" {{ old('bank_name', $employee->bank_name) == 'بنك الجزيرة' ? 'selected' : '' }}>بنك الجزيرة</option>
                                <option value="بنك البلاد" {{ old('bank_name', $employee->bank_name) == 'بنك البلاد' ? 'selected' : '' }}>بنك البلاد</option>
                                <option value="البنك العربي الوطني" {{ old('bank_name', $employee->bank_name) == 'البنك العربي الوطني' ? 'selected' : '' }}>البنك العربي الوطني</option>
                                <option value="مصرف آسيا" {{ old('bank_name', $employee->bank_name) == 'مصرف آسيا' ? 'selected' : '' }}>مصرف آسيا</option>
                                <option value="بنك الخليج الدولي" {{ old('bank_name', $employee->bank_name) == 'بنك الخليج الدولي' ? 'selected' : '' }}>بنك الخليج الدولي</option>
                                <option value="بنك اتش اس بي سي السعودية" {{ old('bank_name', $employee->bank_name) == 'بنك اتش اس بي سي السعودية' ? 'selected' : '' }}>بنك اتش اس بي سي السعودية</option>
                                <option value="البنك الأهلي المصري" {{ old('bank_name', $employee->bank_name) == 'البنك الأهلي المصري' ? 'selected' : '' }}>البنك الأهلي المصري</option>
                                <option value="بنك دويتشه السعودية" {{ old('bank_name', $employee->bank_name) == 'بنك دويتشه السعودية' ? 'selected' : '' }}>بنك دويتشه السعودية</option>
                                <option value="بنك الاتحاد" {{ old('bank_name', $employee->bank_name) == 'بنك الاتحاد' ? 'selected' : '' }}>بنك الاتحاد</option>
                                <option value="بنك مؤسسة النقد العربي السعودي" {{ old('bank_name', $employee->bank_name) == 'بنك مؤسسة النقد العربي السعودي' ? 'selected' : '' }}>بنك مؤسسة النقد العربي السعودي</option>
                                <option value="بنك التنمية الاجتماعية" {{ old('bank_name', $employee->bank_name) == 'بنك التنمية الاجتماعية' ? 'selected' : '' }}>بنك التنمية الاجتماعية</option>
                                <option value="أخرى" {{ old('bank_name', $employee->bank_name) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- IBAN -->
                        <div>
                            <label for="iban" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الآيبان (IBAN)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">SA</span>
                                <input type="text"
                                       id="iban"
                                       name="iban"
                                       value="{{ old('iban', $employee->iban) }}"
                                       placeholder="0000000000000000000000"
                                       maxlength="22"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('iban') border-red-500 @enderror"
                                       oninput="validateIban(this)">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">أدخل 22 رقم فقط (بدون SA)</p>
                            @error('iban')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section (newly added) -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-user-heart-line text-green-600"></i>
                        المعلومات الشخصية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الميلاد
                            </label>
                            <input type="date"
                                   id="birth_date"
                                   name="birth_date"
                                   value="{{ old('birth_date', optional($employee->birth_date)->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nationality -->
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">
                                الجنسية
                            </label>
                            <select id="nationality"
                                    name="nationality"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nationality') border-red-500 @enderror">
                                <option value="">اختر الجنسية</option>
                                <option value="سعودي" {{ old('nationality', $employee->nationality) == 'سعودي' ? 'selected' : '' }}>سعودي</option>
                                <option value="مصري" {{ old('nationality', $employee->nationality) == 'مصري' ? 'selected' : '' }}>مصري</option>
                                <option value="سوداني" {{ old('nationality', $employee->nationality) == 'سوداني' ? 'selected' : '' }}>سوداني</option>
                                <option value="يمني" {{ old('nationality', $employee->nationality) == 'يمني' ? 'selected' : '' }}>يمني</option>
                                <option value="سوري" {{ old('nationality', $employee->nationality) == 'سوري' ? 'selected' : '' }}>سوري</option>
                                <option value="أردني" {{ old('nationality', $employee->nationality) == 'أردني' ? 'selected' : '' }}>أردني</option>
                                <option value="لبناني" {{ old('nationality', $employee->nationality) == 'لبناني' ? 'selected' : '' }}>لبناني</option>
                                <option value="فلسطيني" {{ old('nationality', $employee->nationality) == 'فلسطيني' ? 'selected' : '' }}>فلسطيني</option>
                                <option value="عراقي" {{ old('nationality', $employee->nationality) == 'عراقي' ? 'selected' : '' }}>عراقي</option>
                                <option value="باكستاني" {{ old('nationality', $employee->nationality) == 'باكستاني' ? 'selected' : '' }}>باكستاني</option>
                                <option value="هندي" {{ old('nationality', $employee->nationality) == 'هندي' ? 'selected' : '' }}>هندي</option>
                                <option value="بنغلاديشي" {{ old('nationality', $employee->nationality) == 'بنغلاديشي' ? 'selected' : '' }}>بنغلاديشي</option>
                                <option value="فلبيني" {{ old('nationality', $employee->nationality) == 'فلبيني' ? 'selected' : '' }}>فلبيني</option>
                                <option value="أخرى" {{ old('nationality', $employee->nationality) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('nationality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Religion -->
                        <div>
                            <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">
                                الديانة
                            </label>
                            <select id="religion"
                                    name="religion"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('religion') border-red-500 @enderror">
                                <option value="">اختر الديانة</option>
                                <option value="الإسلام" {{ old('religion', $employee->religion) == 'الإسلام' ? 'selected' : '' }}>الإسلام</option>
                                <option value="المسيحية" {{ old('religion', $employee->religion) == 'المسيحية' ? 'selected' : '' }}>المسيحية</option>
                                <option value="اليهودية" {{ old('religion', $employee->religion) == 'اليهودية' ? 'selected' : '' }}>اليهودية</option>
                                <option value="الهندوسية" {{ old('religion', $employee->religion) == 'الهندوسية' ? 'selected' : '' }}>الهندوسية</option>
                                <option value="البوذية" {{ old('religion', $employee->religion) == 'البوذية' ? 'selected' : '' }}>البوذية</option>
                                <option value="السيخية" {{ old('religion', $employee->religion) == 'السيخية' ? 'selected' : '' }}>السيخية</option>
                                <option value="أخرى" {{ old('religion', $employee->religion) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('religion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Medical Insurance Status -->
                        <div>
                            <label for="medical_insurance_status" class="block text-sm font-medium text-gray-700 mb-2">
                                حالة التأمين الطبي
                            </label>
                            <select id="medical_insurance_status"
                                    name="medical_insurance_status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('medical_insurance_status') border-red-500 @enderror">
                                <option value="">اختر الحالة</option>
                                <option value="مشمول" {{ old('medical_insurance_status', $employee->medical_insurance_status) == 'مشمول' ? 'selected' : '' }}>مشمول</option>
                                <option value="غير مشمول" {{ old('medical_insurance_status', $employee->medical_insurance_status) == 'غير مشمول' ? 'selected' : '' }}>غير مشمول</option>
                            </select>
                            @error('medical_insurance_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location Type -->
                        <div>
                            <label for="location_type" class="block text-sm font-medium text-gray-700 mb-2">
                                الموقع
                            </label>
                            <select id="location_type"
                                    name="location_type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location_type') border-red-500 @enderror">
                                <option value="">اختر الموقع</option>
                                <option value="داخل المملكة" {{ old('location_type', $employee->location_type) == 'داخل المملكة' ? 'selected' : '' }}>داخل المملكة</option>
                                <option value="خارج المملكة" {{ old('location_type', $employee->location_type) == 'خارج المملكة' ? 'selected' : '' }}>خارج المملكة</option>
                            </select>
                            @error('location_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Employee Rating Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-star-line text-yellow-500"></i>
                        تقييم الموظف
                    </h3>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-3">تقييم الأداء (من 1 إلى 5 نجوم)</label>
                        <div class="flex items-center gap-2">
                            <div class="star-rating" data-rating="{{ old('rating', $employee->rating ?? 0) }}">
                                <input type="hidden" name="rating" id="rating" value="{{ old('rating', $employee->rating ?? '') }}">
                                <div class="flex gap-1">
                                    <span class="star" data-value="1">
                                        <i class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                    <span class="star" data-value="2">
                                        <i class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                    <span class="star" data-value="3">
                                        <i class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                    <span class="star" data-value="4">
                                        <i class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                    <span class="star" data-value="5">
                                        <i class="ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="rating-text text-sm text-gray-600 mr-3">لم يتم التقييم بعد</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">انقر على النجوم لتحديد تقييم الموظف (اختياري)</p>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Documents Section (newly added) -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ri-file-add-line text-purple-600"></i>
                        وثائق إضافية
                    </h3>

                    <div id="additionalDocumentsContainer">
                        @if($employee->additional_documents)
                            @php
                                $existingDocs = $employee->additional_documents;
                                if (is_string($existingDocs)) {
                                    $existingDocs = json_decode($existingDocs, true);
                                }
                            @endphp
                            @if(is_array($existingDocs) && count($existingDocs) > 0)
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">الوثائق الحالية:</h4>
                                    <ul class="text-sm text-gray-600">
                                        @foreach($existingDocs as $doc)
                                            <li class="flex items-center gap-2 mb-1">
                                                <i class="ri-file-line text-blue-500"></i>
                                                <span>{{ $doc['name'] ?? 'وثيقة بدون اسم' }}</span>
                                                @if(isset($doc['uploaded_at']))
                                                    <span class="text-xs text-gray-400">({{ $doc['uploaded_at'] }})</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-600">إضافة وثائق جديدة (سيتم استبدال الوثائق الحالية)</p>
                                <button type="button"
                                        onclick="addDocumentRow()"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm flex items-center gap-2">
                                    <i class="ri-add-line"></i>
                                    إضافة وثيقة
                                </button>
                            </div>

                            <div id="documentsRows">
                                <!-- Document rows will be added here by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('employees.show', $employee) }}"
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <i class="ri-save-line"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// IBAN validation function
function validateIban(input) {
    // Remove any non-numeric characters
    let value = input.value.replace(/\D/g, '');

    // Limit to 22 digits
    if (value.length > 22) {
        value = value.substring(0, 22);
    }

    // Update the input value
    input.value = value;

    // Visual feedback
    if (value.length === 22) {
        input.classList.remove('border-red-500');
        input.classList.add('border-green-500');
    } else if (value.length > 0) {
        input.classList.remove('border-green-500');
        input.classList.add('border-yellow-500');
    } else {
        input.classList.remove('border-green-500', 'border-yellow-500');
    }
}

// Additional Documents Management
let documentRowIndex = 0;

function addDocumentRow() {
    const container = document.getElementById('documentsRows');
    const newRow = document.createElement('div');
    newRow.className = 'document-row grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-lg';
    newRow.id = `document-row-${documentRowIndex}`;

    newRow.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">اسم الوثيقة</label>
            <input type="text"
                   name="additional_documents[${documentRowIndex}][name]"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="أدخل اسم الوثيقة"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">رفع الملف</label>
            <input type="file"
                   name="additional_documents[${documentRowIndex}][file]"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                   onchange="validateFileSize(this)"
                   required>
            <p class="text-xs text-gray-500 mt-1">الحد الأقصى: 5 ميجابايت</p>
        </div>

        <div class="flex items-end">
            <button type="button"
                    onclick="removeDocumentRow('document-row-${documentRowIndex}')"
                    class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm flex items-center justify-center gap-2">
                <i class="ri-delete-bin-line"></i>
                إزالة
            </button>
        </div>
    `;

    container.appendChild(newRow);
    documentRowIndex++;
}

function removeDocumentRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
    }
}

function validateFileSize(input) {
    const file = input.files[0];
    if (file) {
        const fileSizeMB = file.size / (1024 * 1024);
        if (fileSizeMB > 5) {
            alert('حجم الملف كبير جداً. الحد الأقصى المسموح هو 5 ميجابايت.');
            input.value = '';
            return false;
        }
    }
    return true;
}

// Star Rating System
document.addEventListener('DOMContentLoaded', function() {
    const starRating = document.querySelector('.star-rating');
    const stars = starRating.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.querySelector('.rating-text');

    const ratingTexts = {
        0: 'لم يتم التقييم بعد',
        1: 'ضعيف جداً',
        2: 'ضعيف',
        3: 'متوسط',
        4: 'جيد',
        5: 'ممتاز'
    };

    // Initialize rating if there's an old value
    const currentRating = parseInt(ratingInput.value) || 0;
    if (currentRating > 0) {
        updateStars(currentRating);
        ratingText.textContent = ratingTexts[currentRating];
    }

    stars.forEach((star, index) => {
        const value = parseInt(star.dataset.value);

        // Click event
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.value);
            ratingInput.value = rating;
            updateStars(rating);
            ratingText.textContent = ratingTexts[rating];
        });

        // Hover events
        star.addEventListener('mouseenter', function() {
            const hoverRating = parseInt(this.dataset.value);
            updateStars(hoverRating, true);
            ratingText.textContent = ratingTexts[hoverRating];
        });
    });

    // Reset on mouse leave
    starRating.addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        updateStars(currentRating);
        ratingText.textContent = ratingTexts[currentRating];
    });

    function updateStars(rating, isHover = false) {
        stars.forEach((star, index) => {
            const starIcon = star.querySelector('i');
            const starValue = parseInt(star.dataset.value);

            if (starValue <= rating) {
                starIcon.className = 'ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-yellow-400';
            } else {
                starIcon.className = 'ri-star-fill text-2xl cursor-pointer transition-colors duration-200 text-gray-300 hover:text-yellow-400';
            }
        });
    }
});
</script>
@endsection
