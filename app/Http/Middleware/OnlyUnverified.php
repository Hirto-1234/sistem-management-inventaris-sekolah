<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlyUnverified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(session()->all());

        if (!session()->has('pending_verification')) {
            return redirect()->route('login');
        }

        $pengguna = session('pending_verification');

        if ($pengguna['email_verified_at'] !== null) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
