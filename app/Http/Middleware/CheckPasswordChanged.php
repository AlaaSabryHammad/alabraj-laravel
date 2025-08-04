<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Skip check for change password routes
        if ($request->routeIs('change-password') || $request->routeIs('change-password.update')) {
            return $next($request);
        }

        // Check if user still has default password (national_id)
        if ($user && $user->employee) {
            $employee = $user->employee;
            if ($employee->national_id && Hash::check($employee->national_id, $user->password)) {
                return redirect()->route('change-password')->with('must_change_password', true);
            }
        }

        return $next($request);
    }
}
