<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckManagerRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user has manager role
        if (!$user || $user->role !== 'manager') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'عذراً، يُسمح بالدخول للمستخدمين الرئيسيين فقط.',
            ]);
        }

        return $next($request);
    }
}
