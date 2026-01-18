<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestOrAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika request adalah GET, izinkan guest
        if ($request->isMethod('get')) {
            return $next($request);
        }

        // Untuk request non-GET (POST, PUT, DELETE), periksa auth
        if (!auth()->check()) {
            return redirect('login')->with('error', 'Anda harus login untuk melakukan aksi ini.');
        }

        return $next($request);
    }
}
