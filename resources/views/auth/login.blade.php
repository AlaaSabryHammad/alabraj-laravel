<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - شركة الأبراج للمقاولات</title>

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

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Company Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 glass-effect rounded-2xl mb-4 animate-glow">
                <img src="{{ asset('assets/logo.png') }}" alt="شركة الأبراج" class="w-16 h-16 mx-auto">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">شركة الأبراج للمقاولات</h1>
            <p class="text-blue-100">نظام إدارة المشاريع والموارد</p>
        </div>

        <!-- Login Form -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white mb-2">تسجيل الدخول</h2>
                <p class="text-blue-100">أدخل بياناتك للوصول إلى لوحة التحكم</p>

                <!-- Admin Only Notice -->
                <div class="bg-yellow-500/20 border border-yellow-500/50 rounded-lg p-3 mt-4">
                    <div class="flex items-center justify-center gap-2 text-yellow-100">
                        <i class="ri-admin-line"></i>
                        <span class="text-sm font-medium">مخصص للمستخدمين الرئيسيين فقط</span>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/50 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-2 text-red-100">
                        <i class="ri-error-warning-line"></i>
                        <span class="font-medium">خطأ في تسجيل الدخول</span>
                    </div>
                    <ul class="mt-2 text-sm text-red-200">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

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
                        كلمة المرور
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent input-glow transition-all duration-300"
                            placeholder="أدخل كلمة المرور">
                        <button type="button" onclick="togglePassword()"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-blue-200 hover:text-white transition-colors">
                            <i id="password-icon" class="ri-eye-off-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember"
                            class="rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-400 focus:ring-offset-0">
                        <span class="mr-2 text-sm text-blue-100">تذكرني</span>
                    </label>
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-blue-200 hover:text-white transition-colors">
                        نسيت كلمة المرور؟
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="ri-login-circle-line"></i>
                    <span>تسجيل الدخول</span>
                </button>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-transparent text-blue-200">أو</span>
                    </div>
                </div>

                <!-- Quick Access -->
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="demoLogin('admin')"
                        class="py-3 px-4 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg border border-white/20 hover:border-white/40 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="ri-admin-line"></i>
                        <span class="text-sm">مدير</span>
                    </button>
                    <button type="button" onclick="demoLogin('user')"
                        class="py-3 px-4 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg border border-white/20 hover:border-white/40 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="ri-user-line"></i>
                        <span class="text-sm">موظف</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-blue-200 text-sm">
                © 2025 شركة الأبراج للمقاولات. جميع الحقوق محفوظة.
            </p>
            <div class="flex justify-center gap-4 mt-4">
                <a href="#" class="text-blue-200 hover:text-white transition-colors">
                    <i class="ri-phone-line"></i>
                </a>
                <a href="#" class="text-blue-200 hover:text-white transition-colors">
                    <i class="ri-mail-line"></i>
                </a>
                <a href="#" class="text-blue-200 hover:text-white transition-colors">
                    <i class="ri-global-line"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'ri-eye-line';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'ri-eye-off-line';
            }
        }

        // Demo login function
        function demoLogin(role) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            if (role === 'admin') {
                emailInput.value = 'admin@abraj.com';
                passwordInput.value = 'admin123';
            } else {
                emailInput.value = 'user@abraj.com';
                passwordInput.value = 'user123';
            }
        }

        // Add floating animation to form elements
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach((input, index) => {
                input.style.animationDelay = `${index * 0.1}s`;
                input.classList.add('animate-fade-in');
            });
        });

        // Add fade-in animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fade-in {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fade-in 0.6s ease-out forwards;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>
