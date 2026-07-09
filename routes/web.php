<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Pendaftaran publik DINONAKTIFKAN — akun hanya dibuat oleh QA (admin master)
// Rute /register secara eksplisit mengembalikan 404
Route::get('/register', fn () => abort(404));
Route::post('/register', fn () => abort(404));

Route::middleware(['auth'])->group(function () {
    
    // Redirect based on role
    Route::get('/dashboard', function () {
        if (request()->user()->role === 'qa') {
            return redirect()->route('qa.dashboard');
        }
        return redirect()->route('beranda');
    })->name('dashboard');

    // Role Karyawan
    Route::middleware(['role:karyawan'])->group(function () {
        Route::view('/beranda', 'pages.beranda')->name('beranda');
    });

    // Role QA
    Route::middleware(['role:qa'])->group(function () {
        Route::view('/qa/dashboard', 'dashboard')->name('qa.dashboard');
    });

    // Temuan detail (accessible by pelapor_id, pic_id or role qa via Policy)
    Route::get('/temuan/{temuan}', function (\App\Models\Temuan $temuan) {
        return "Detail Temuan (Placeholder Hari 2) ID: " . $temuan->id;
    })->name('temuan.detail')->middleware('can:view,temuan');

    // Route untuk test job queue WA (Hanya untuk Hari 2)
    Route::get('/test-wa', function () {
        \App\Jobs\SendWhatsAppDummy::dispatch();
        return "Job SendWhatsAppDummy telah dikirim ke Queue!";
    });

});

require __DIR__.'/settings.php';
