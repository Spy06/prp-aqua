<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();
        $intended = session()->get('url.intended');

        if ($intended) {
            $path = parse_url($intended, PHP_URL_PATH) ?? '';
            $normalizedPath = rtrim($path, '/');
            if ($normalizedPath === '') {
                $normalizedPath = '/';
            }

            $genericPaths = ['/', '/login', '/dashboard', '/home', '/beranda'];

            if ($user && $user->role !== 'qa') {
                // Karyawan tidak boleh ke rute khusus QA atau Export
                if (str_starts_with($path, '/qa') || str_starts_with($path, '/export')) {
                    session()->forget('url.intended');
                }
                // Jika intended adalah path generic, hapus agar redirect ke beranda sistem
                if (in_array($normalizedPath, $genericPaths, true)) {
                    session()->forget('url.intended');
                }
            } elseif ($user && $user->role === 'qa') {
                // QA dari rute umum → hapus intended, redirect ke dashboard sistem
                if (in_array($normalizedPath, $genericPaths, true)) {
                    session()->forget('url.intended');
                }
            }
        }

        // Baca sistem yang dipilih user saat login (dari form field 'system')
        $system = $request->input('system', session('login_system', 'sivera'));

        // Simpan ke session agar layout/menu tahu sistem mana yang aktif
        session(['login_system' => $system]);

        // Tentukan target redirect berdasarkan sistem + role
        if ($system === 'bosq') {
            if ($user && $user->role === 'qa') {
                $target = route('bosq.qa.dashboard', absolute: false);
            } else {
                $target = route('bosq.beranda', absolute: false);
            }
        } else {
            // SIVERA (default)
            if ($user && $user->role === 'qa') {
                $target = route('qa.dashboard', absolute: false);
            } else {
                $target = route('beranda', absolute: false);
            }
        }

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended($target);
    }
}
