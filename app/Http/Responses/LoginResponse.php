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

            if ($user && $user->role !== 'qa') {
                // Karyawan (non-QA) tidak boleh diarahkan ke rute khusus QA atau Export
                if (str_starts_with($path, '/qa') || str_starts_with($path, '/export')) {
                    session()->forget('url.intended');
                }
            } elseif ($user && $user->role === 'qa') {
                // User QA yang login dari rute umum harus diarahkan ke QA Dashboard default
                $genericPaths = ['/', '/login', '/dashboard', '/home', '/beranda'];
                if (in_array($normalizedPath, $genericPaths, true)) {
                    session()->forget('url.intended');
                }
            }
        }

        $target = route('dashboard', absolute: false);

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended($target);
    }
}
