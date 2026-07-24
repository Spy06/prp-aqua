<?php

use Illuminate\Support\Facades\Route;

// Halaman utama default sebelum login: Portal Pemilihan Sistem Informasi
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'qa') {
            return redirect()->route('qa.dashboard');
        }
        return redirect()->route('beranda');
    }
    return view('portal');
})->name('portal');

// Pendaftaran publik DINONAKTIFKAN — akun hanya dibuat oleh QA (admin master)
// Rute /register secara eksplisit mengembalikan 404
Route::get('/register', fn () => abort(404));
Route::post('/register', fn () => abort(404));

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        if (request()->user()->role === 'qa') {
            return redirect()->route('qa.dashboard');
        }
        return redirect()->route('beranda');
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

    // ── Skeleton Routing BOS'Q (Hari 1) ──
    Route::prefix('bosq')->name('bosq.')->group(function () {
        Route::middleware(['role:karyawan,qa'])->group(function () {
            Route::get('/beranda', function () {
                return response('BOSQ Beranda Placeholder');
            })->name('beranda');
        });

        Route::middleware(['role:qa'])->prefix('qa')->name('qa.')->group(function () {
            Route::get('/dashboard', fn() => response('BOSQ QA Dashboard Placeholder'))->name('dashboard');
            Route::get('/rekap', fn() => response('BOSQ QA Rekap Placeholder'))->name('rekap');
            Route::get('/master/line', fn() => response('BOSQ Master Line Placeholder'))->name('master.line');
            Route::get('/master/subarea', fn() => response('BOSQ Master SubArea Placeholder'))->name('master.subarea');
            Route::get('/master/elemen', fn() => response('BOSQ Master Elemen Placeholder'))->name('master.elemen');
        });

        Route::get('/temuan/{bosqTemuan}', function (\App\Models\BosqTemuan $bosqTemuan) {
            return response('BOSQ Temuan Detail Placeholder #' . $bosqTemuan->id);
        })->name('temuan.detail')->middleware('can:view,bosqTemuan');
    });

});

require __DIR__.'/settings.php';
