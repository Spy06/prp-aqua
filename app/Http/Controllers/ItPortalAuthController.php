<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ItPortalAuthController extends Controller
{
    /**
     * Tampilkan halaman login Portal Khusus IT.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('qa.master.akun');
            }
            return redirect()->route('beranda');
        }

        return view('pages.auth.it-login');
    }

    /**
     * Proses autentikasi login khusus Super Admin IT.
     */
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'password' => 'required|string',
            'secret_pin' => 'required|string',
        ], [
            'nik.required' => 'NIK / Username IT wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'secret_pin.required' => 'PIN Keamanan IT wajib diisi.',
        ]);

        // Cari user berdasarkan NIK atau Name
        $user = User::where('nik', $request->nik)
            ->orWhere('name', $request->nik)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nik' => 'Kredensial Super Admin IT tidak valid.',
            ]);
        }

        // Pastikan role benar-benar superadmin
        if ($user->role !== 'superadmin') {
            throw ValidationException::withMessages([
                'nik' => 'Akun ini bukan akun Super Admin IT. Akses ditolak.',
            ]);
        }

        // Verifikasi PIN Keamanan IT
        // Prioritas: it_pin di DB → IT_ADMIN_PIN di .env → tolak login (tidak ada hardcoded fallback)
        $expectedPin = $user->it_pin ?: env('IT_ADMIN_PIN');
        if (!$expectedPin) {
            throw ValidationException::withMessages([
                'secret_pin' => 'Konfigurasi PIN Keamanan IT belum diatur. Hubungi administrator sistem.',
            ]);
        }
        if ($request->secret_pin !== $expectedPin) {
            throw ValidationException::withMessages([
                'secret_pin' => 'PIN Keamanan IT salah. Akses ditolak.',
            ]);
        }

        // Log in user
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();
        session(['login_system' => 'sivera']);

        return redirect()->intended(route('qa.master.akun'));
    }

    /**
     * Logout Super Admin IT.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('it.login.form')->with('success', 'Berhasil keluar dari Portal IT Admin.');
    }
}
