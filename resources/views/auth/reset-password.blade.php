<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعيين كلمة مرور جديدة - شركة الأبراج للمقاولات</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cairo': ['Cairo', 'sans-serif'],
                    },
                    animation: {
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    }
                }
            }
        }
    </script>

    <style>
        @keyframes glow {
            from {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
            }

            to {
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.8);
            }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>

<body class="font-cairo gradient-bg min-h-screen flex items-center justify-center p-4">

    <!-- Reset Password Container -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Company Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 glass-effect rounded-2xl mb-4 animate-glow">
                <img src="{{ asset('assets/logo.png') }}" alt="شركة الأبراج" class="w-16 h-16 mx-auto">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">شركة الأبراج للمقاولات</h1>
            <p class="text-blue-100">نظام إدارة المشاريع والموارد</p>
        </div>

        <!-- Reset Password Form -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white mb-2">تعيين كلمة مرور جديدة</h2>
                <p class="text-blue-100">أدخل كلمة المرور الجديدة الخاصة بك</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/50 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-2 text-red-100">
                        <i class="ri-error-warning-line"></i>
                        <span class="font-medium">خطأ في تعيين كلمة المرور</span>
                    </div>
                    <ul class="mt-2 text-sm text-red-200">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <!-- Token Field -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">
                        <i class="ri-mail-line ml-1"></i>
                        البريد الإلكتروني
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        autocomplete="email"
                        class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent input-glow transition-all duration-300"
                        placeholder="example@company.com">
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        <i class="ri-lock-line ml-1"></i>
                        كلمة المرور الجديدة
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent input-glow transition-all duration-300"
                            placeholder="أدخل كلمة المرور الجديدة">
                        <button type="button" onclick="togglePassword('password', 'password-icon')"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-blue-200 hover:text-white transition-colors">
                            <i id="password-icon" class="ri-eye-off-line"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-blue-200">يجب أن تكون كلمة المرور 8 أحرف على الأقل</p>
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                        <i class="ri-lock-line ml-1"></i>
                        تأكيد كلمة المرور
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent input-glow transition-all duration-300"
                            placeholder="أدخل كلمة المرور مرة أخرى">
                        <button type="button"
                            onclick="togglePassword('password_confirmation', 'password-confirmation-icon')"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-blue-200 hover:text-white transition-colors">
                            <i id="password-confirmation-icon" class="ri-eye-off-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="ri-lock-password-line"></i>
                    <span>تعيين كلمة المرور</span>
                </button>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}"
                        class="text-sm text-blue-200 hover:text-white transition-colors flex items-center justify-center gap-1">
                        <i class="ri-arrow-right-line"></i>
                        العودة إلى تسجيل الدخول
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-blue-200 text-sm">
                © 2025 شركة الأبراج للمقاولات. جميع الحقوق محفوظة.
            </p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ri-eye-line';
            } else {
                input.type = 'password';
                icon.className = 'ri-eye-off-line';
            }
        }
    </script>
</body>

</html>
