<?php

use Illuminate\Support\Facades\Route;

// Halaman utama default sebelum login: Portal Pemilihan Sistem Informasi
Route::get('/', function () {
    if (auth()->check()) {
        $system = session('login_system', 'sivera');
        $user   = auth()->user();
        if ($system === 'bosq') {
            return $user->role === 'qa'
                ? redirect()->route('bosq.qa.dashboard')
                : redirect()->route('bosq.beranda');
        }
        // SIVERA default
        return $user->role === 'qa'
            ? redirect()->route('qa.dashboard')
            : redirect()->route('beranda');
    }
    return view('portal');
})->name('portal');

// Pendaftaran publik DINONAKTIFKAN — akun hanya dibuat oleh QA (admin master)
// Rute /register secara eksplisit mengembalikan 404
Route::get('/register', fn () => abort(404));
Route::post('/register', fn () => abort(404));

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        $system = session('login_system', 'sivera');
        $user   = request()->user();
        if ($system === 'bosq') {
            return $user->role === 'qa'
                ? redirect()->route('bosq.qa.dashboard')
                : redirect()->route('bosq.beranda');
        }
        // SIVERA default
        return $user->role === 'qa'
            ? redirect()->route('qa.dashboard')
            : redirect()->route('beranda');
    })->name('dashboard');

    Route::redirect('/home', '/dashboard')->name('home');

    // Role Karyawan & QA (Halaman Beranda & Lapor Temuan SIVERA)
    Route::middleware(['role:karyawan,qa'])->group(function () {
        Route::view('/beranda', 'pages.beranda')->name('beranda');
    });

    // Role QA — Dashboard, Rekap, Master Data SIVERA
    Route::middleware(['role:qa'])->group(function () {
        Route::view('/qa/dashboard', 'dashboard')->name('qa.dashboard');
        Route::view('/qa/daftar-temuan', 'pages.qa.daftar-temuan')->name('qa.daftar-temuan');
        Route::view('/qa/rekap', 'pages.qa.rekap')->name('qa.rekap');
        Route::view('/qa/master/karyawan', 'pages.qa.master-karyawan')->name('qa.master.karyawan');
        Route::view('/qa/master/departemen', 'pages.qa.master-departemen')->name('qa.master.departemen');
        Route::view('/qa/master/klausul', 'pages.qa.master-klausul')->name('qa.master.klausul');
        Route::view('/qa/master/akun', 'pages.qa.master-akun')->name('qa.master.akun');
    });

    // Export routes SIVERA — hanya QA
    Route::middleware(['role:qa'])->group(function () {
        Route::get('/export/excel', [\App\Http\Controllers\ExportController::class, 'excel'])->name('export.excel');
        Route::get('/export/pdf/temuan/{temuan}', [\App\Http\Controllers\ExportController::class, 'pdfTemuan'])->name('export.pdf.temuan');
        Route::get('/export/pdf/rekap', [\App\Http\Controllers\ExportController::class, 'pdfRekap'])->name('export.pdf.rekap');
    });

    // Temuan detail SIVERA
    Route::get('/temuan/{temuan}', function (\App\Models\Temuan $temuan) {
        return view('pages.temuan-detail', ['temuan' => $temuan]);
    })->name('temuan.detail')->middleware('can:view,temuan');

    // ── BOS'Q Routes ──
    Route::prefix('bosq')->name('bosq.')->group(function () {
        Route::middleware(['role:karyawan,qa'])->group(function () {
            Route::view('/beranda', 'pages.bosq.beranda')->name('beranda');
        });

        Route::middleware(['role:qa'])->prefix('qa')->name('qa.')->group(function () {
            Route::get('/dashboard', fn() => view('pages.bosq.qa-placeholder', ['title' => 'Dashboard QA BOS\'Q', 'desc' => 'Dashboard analisis observasi BOS\'Q (Fitur Hari 3)']))->name('dashboard');
            Route::get('/rekap', fn() => view('pages.bosq.qa-placeholder', ['title' => 'Rekap Kepatuhan BOS\'Q', 'desc' => 'Rekapitulasi kepatuhan observasi BOS\'Q (Fitur Hari 3)']))->name('rekap');
            Route::get('/master/line', fn() => view('pages.bosq.qa-placeholder', ['title' => 'Master Line BOS\'Q', 'desc' => 'Manajemen data Master Line BOS\'Q (Fitur Hari 3)']))->name('master.line');
            Route::get('/master/subarea', fn() => view('pages.bosq.qa-placeholder', ['title' => 'Master Sub Area BOS\'Q', 'desc' => 'Manajemen data Master Sub Area BOS\'Q (Fitur Hari 3)']))->name('master.subarea');
            Route::get('/master/elemen', fn() => view('pages.bosq.qa-placeholder', ['title' => 'Master Elemen QFS BOS\'Q', 'desc' => 'Manajemen data Master Elemen QFS BOS\'Q (Fitur Hari 3)']))->name('master.elemen');
        });

        Route::get('/temuan/{bosqTemuan}', function (\App\Models\BosqTemuan $bosqTemuan) {
            return view('pages.bosq.detail-temuan', ['bosqTemuan' => $bosqTemuan]);
        })->name('temuan.detail')->middleware('can:view,bosqTemuan');
    });

});

require __DIR__.'/settings.php';
