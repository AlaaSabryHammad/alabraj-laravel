<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - شركة الأبراج للمقاولات</title>

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
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    }
                }
            }
        }
    </script>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes glow {
            from { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
            to { box-shadow: 0 0 30px rgba(59, 130, 246, 0.8); }
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
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white opacity-5 rounded-full animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white opacity-5 rounded-full animate-float" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-white opacity-3 rounded-full animate-float" style="animation-delay: -1.5s;"></div>
    </div>

    <!-- Register Container -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Company Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 glass-effect rounded-2xl mb-4 animate-glow">
                <img src="{{ asset('assets/logo.png') }}" alt="شركة الأبراج" class="w-16 h-16 mx-auto">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">شركة الأبراج للمقاولات</h1>
            <p class="text-blue-100">إنشاء حساب جديد في النظام</p>
        </div>

        <!-- Register Form -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white mb-2">إنشاء حساب جديد</h2>
                <p class="text-blue-100">أدخل بياناتك لإنشاء حساب في النظام</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-500/20 border border-red-500/50 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-2 text-red-100">
                        <i class="ri-error-warning-line"></i>
                        <span class="font-medium">خطأ في البيانات</span>
                    </div>
                    <ul class="mt-2 text-sm text-red-200">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-white mb-2">
                        <i class="ri-user-line ml-1"></i>
                        الاسم الكامل
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           required
                           class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent input-glow transition-all duration-300"
                           placeholder="أدخل اسمك الكامل">
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">
                        <i class="ri-mail-line ml-1"></i>
                        البريد الإلكتروني
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           required
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
                        <input type="password"
                               name="password"
                               id="password"
                               required
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent input-glow transition-all duration-300"
                               placeholder="أدخل كلمة المرور">
                        <button type="button"
                                onclick="togglePassword('password')"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-blue-200 hover:text-white transition-colors">
                            <i id="password-icon" class="ri-eye-off-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                        <i class="ri-lock-2-line ml-1"></i>
                        تأكيد كلمة المرور
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               required
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent input-glow transition-all duration-300"
                               placeholder="أعد إدخال كلمة المرور">
                        <button type="button"
                                onclick="togglePassword('password_confirmation')"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-blue-200 hover:text-white transition-colors">
                            <i id="password_confirmation-icon" class="ri-eye-off-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <input type="checkbox"
                           name="terms"
                           required
                           class="mt-1 rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-400 focus:ring-offset-0">
                    <span class="mr-3 text-sm text-blue-100">
                        أوافق على
                        <a href="#" class="text-blue-300 hover:text-white underline">الشروط والأحكام</a>
                        و
                        <a href="#" class="text-blue-300 hover:text-white underline">سياسة الخصوصية</a>
                    </span>
                </div>

                <!-- Register Button -->
                <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="ri-user-add-line"></i>
                    <span>إنشاء حساب جديد</span>
                </button>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-blue-200">
                        لديك حساب بالفعل؟
                        <a href="{{ route('login') }}" class="text-blue-300 hover:text-white font-medium underline transition-colors">
                            تسجيل الدخول
                        </a>
                    </p>
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
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId + '-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'ri-eye-line';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'ri-eye-off-line';
            }
        }

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updatePasswordStrength(strength);
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            return strength;
        }

        function updatePasswordStrength(strength) {
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
            const texts = ['ضعيف جداً', 'ضعيف', 'متوسط', 'قوي', 'قوي جداً'];

            // Create or update strength indicator
            let indicator = document.getElementById('password-strength');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'password-strength';
                indicator.className = 'mt-2 text-sm';
                document.getElementById('password').parentNode.appendChild(indicator);
            }

            if (strength > 0) {
                indicator.innerHTML = `
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-white/20 rounded-full h-2">
                            <div class="${colors[strength-1]} h-2 rounded-full transition-all duration-300" style="width: ${strength * 20}%"></div>
                        </div>
                        <span class="text-blue-200">${texts[strength-1]}</span>
                    </div>
                `;
            } else {
                indicator.innerHTML = '';
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
