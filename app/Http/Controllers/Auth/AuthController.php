<?php
// app/Http/Controllers/Auth/AuthController.php (UPDATED)
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang, Administrator!');
            } elseif ($user->isDokter()) {
                return redirect()->route('dokter.dashboard')
                    ->with('success', 'Selamat datang, Dr. ' . $user->nama . '!');
            } else {
                return redirect()->route('pasien.dashboard')
                    ->with('success', 'Selamat datang, ' . $user->nama . '!');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:150',
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|string|size:16|unique:pasien,no_ktp',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            // 1. Buat pasien baru dengan auto-generate No RM
            $pasien = Pasien::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_ktp' => $request->no_ktp,
                'no_hp' => $request->no_hp,
            ]);

            // 2. Buat user account untuk pasien
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien',
                'entity_id' => $pasien->id,
            ]);

            DB::commit();

            // Auto login setelah registrasi
            Auth::login($user);

            return redirect()->route('pasien.dashboard')
                ->with('success', "Registrasi berhasil! No Rekam Medis Anda: {$pasien->no_rm}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('register')
                ->with('error', 'Registrasi gagal: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        $userName = Auth::user()->nama ?? 'User';
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sampai jumpa, ' . $userName . '!');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
}