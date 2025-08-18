<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log request details for warehouse spare part creation
        if ($request->is('warehouses/*/store-spare-part')) {
            Log::info('Spare Part Creation Request', [
                'url' => $request->url(),
                'method' => $request->method(),
                'data' => $request->all(),
                'headers' => $request->headers->all(),
                'user' => Auth::id(),
            ]);
        }

        $response = $next($request);

        // Log response for warehouse spare part creation
        if ($request->is('warehouses/*/store-spare-part')) {
            Log::info('Spare Part Creation Response', [
                'status' => $response->getStatusCode(),
                'redirect' => $response->headers->get('Location'),
            ]);
        }

        return $response;
    }
}
