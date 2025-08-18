<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckEmployeeStatus
{
    /**
     * Handle an incoming request.
     * التحقق من أن الموظف نشط أثناء استخدام النظام
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // التحقق من أن المستخدم مسجل دخول
        if (Auth::check()) {
            $user = Auth::user();
            $employee = $user->employee;

            // إذا كان لديه موظف مرتبط وحالته غير نشطة
            if ($employee && $employee->status === 'inactive') {
                // تسجيل خروج فوري
                Auth::logout();

                // إبطال الجلسة
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // إعادة توجيه لصفحة تسجيل الدخول مع رسالة
                return redirect()->route('login')->withErrors([
                    'email' => 'تم إلغاء تفعيل حسابك. تم تسجيل خروجك من النظام. يرجى التواصل مع الإدارة.',
                ]);
            }
        }

        return $next($request);
    }
}
