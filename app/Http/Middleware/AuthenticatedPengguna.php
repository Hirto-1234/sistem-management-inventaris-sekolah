<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedPengguna
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $role = auth()->user()->role_pengguna;

            // Kembali ke halaman sesuai role pengguna yang sedang login
            switch ($role) {
                case 'peminjam':
                    return redirect()->route('peminjaman.dashboard');
                case 'petugas':
                    return redirect()->route('management.dashboard');
                case 'admin':
                    return redirect()->route('management.dashboard');
                default:
                    return redirect()->route('login');
            }
        }
        return $next($request);
    }
}
