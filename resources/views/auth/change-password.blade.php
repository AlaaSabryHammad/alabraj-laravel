@extends('layouts.app')

@section('title', 'تغيير كلمة المرور - شركة الأبراج للمقاولات')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-20 w-20 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full flex items-center justify-center shadow-lg">
                <i class="ri-lock-password-line text-3xl text-white"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                تغيير كلمة المرور
            </h2>
            @if(session('must_change_password'))
            <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="ri-alert-line text-yellow-600 text-lg ml-2"></i>
                    <p class="text-sm text-yellow-800">
                        <strong>مطلوب:</strong> يجب عليك تغيير كلمة المرور الافتراضية قبل المتابعة.
                    </p>
                </div>
            </div>
            @endif
            <p class="mt-2 text-center text-sm text-gray-600">
                أدخل كلمة المرور الحالية والجديدة
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('change-password.update') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        كلمة المرور الحالية
                    </label>
                    <div class="relative">
                        <input id="current_password"
                               name="current_password"
                               type="password"
                               required
                               class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('current_password') border-red-500 @enderror"
                               placeholder="أدخل كلمة المرور الحالية">
                        <i class="ri-lock-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        كلمة المرور الجديدة
                    </label>
                    <div class="relative">
                        <input id="password"
                               name="password"
                               type="password"
                               required
                               class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                               placeholder="أدخل كلمة المرور الجديدة (8 أحرف على الأقل)">
                        <i class="ri-key-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        تأكيد كلمة المرور الجديدة
                    </label>
                    <div class="relative">
                        <input id="password_confirmation"
                               name="password_confirmation"
                               type="password"
                               required
                               class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="أعد إدخال كلمة المرور الجديدة">
                        <i class="ri-key-2-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="ri-save-line text-white group-hover:text-blue-100" aria-hidden="true"></i>
                    </span>
                    تغيير كلمة المرور
                </button>
            </div>

            <!-- Password Requirements -->
            <div class="mt-4 bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">متطلبات كلمة المرور:</h4>
                <ul class="text-xs text-gray-600 space-y-1">
                    <li class="flex items-center">
                        <i class="ri-checkbox-circle-line text-green-500 ml-2"></i>
                        8 أحرف على الأقل
                    </li>
                    <li class="flex items-center">
                        <i class="ri-checkbox-circle-line text-green-500 ml-2"></i>
                        مختلفة عن كلمة المرور الحالية
                    </li>
                    <li class="flex items-center">
                        <i class="ri-checkbox-circle-line text-green-500 ml-2"></i>
                        يُنصح باستخدام أحرف وأرقام ورموز
                    </li>
                </ul>
            </div>

            <!-- Logout Option -->
            <div class="text-center mt-4">
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-blue-200 hover:text-white transition-colors">
                        <i class="ri-logout-box-line ml-1"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>

<script>
// Password strength indicator
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('password_confirmation');

function checkPasswordMatch() {
    if (confirmInput.value && passwordInput.value !== confirmInput.value) {
        confirmInput.setCustomValidity('كلمات المرور غير متطابقة');
    } else {
        confirmInput.setCustomValidity('');
    }
}

passwordInput.addEventListener('input', checkPasswordMatch);
confirmInput.addEventListener('input', checkPasswordMatch);
</script>
@endsection
