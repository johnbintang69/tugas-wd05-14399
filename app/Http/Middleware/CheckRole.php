<?php
// app/Http/Middleware/CheckRole.php (UPDATED)
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
        
        // Cek role admin
        if ($role == 'admin' && !$user->isAdmin()) {
            if ($user->isDokter()) {
                return redirect()->route('dokter.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman admin!');
            } else {
                return redirect()->route('pasien.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman admin!');
            }
        }
        
        // Cek role dokter
        if ($role == 'dokter' && !$user->isDokter()) {
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman dokter!');
            } else {
                return redirect()->route('pasien.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman dokter!');
            }
        }
        
        // Cek role pasien
        if ($role == 'pasien' && !$user->isPasien()) {
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman pasien!');
            } else {
                return redirect()->route('dokter.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman pasien!');
            }
        }

        return $next($request);
    }
}