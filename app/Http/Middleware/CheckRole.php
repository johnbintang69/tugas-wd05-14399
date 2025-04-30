<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (Auth::guest()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($role == 'dokter' && !$user->isDokter()) {
            return redirect()->route('pasien.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman dokter!');
        }
        
        if ($role == 'pasien' && !$user->isPasien()) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman pasien!');
        }

        return $next($request);
    }
}