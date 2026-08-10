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

// ── Secret Portal Access Khusus IT Admin ──
Route::get('/admin-SiveraBosQ', [\App\Http\Controllers\ItPortalAuthController::class, 'showLoginForm'])->name('it.login.form');
Route::post('/admin-SiveraBosQ', [\App\Http\Controllers\ItPortalAuthController::class, 'login'])->name('it.login.submit');
Route::post('/admin-SiveraBosQ/logout', [\App\Http\Controllers\ItPortalAuthController::class, 'logout'])->name('it.logout');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        $system = session('login_system', 'sivera');
        $user   = request()->user();
        if ($user && $user->role === 'superadmin') {
            return $system === 'bosq'
                ? redirect()->route('bosq.qa.dashboard')
                : redirect()->route('qa.master.akun');
        }
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

    // ── SIVERA Routes ──
    Route::middleware(['system_guard:sivera'])->group(function () {
        // Role Karyawan & QA — Beranda, Dashboard Analytics & Daftar Temuan SIVERA
        Route::middleware(['role:karyawan,qa'])->group(function () {
            Route::get('/beranda', function () {
                if (auth()->user()->isSuperAdmin()) {
                    return redirect()->route('qa.master.akun');
                }
                return view('pages.beranda');
            })->name('beranda');

            Route::get('/qa/dashboard', function () {
                if (auth()->user()->role === 'karyawan' && !auth()->user()->isPicUser()) {
                    return redirect()->route('beranda');
                }
                return view('dashboard');
            })->name('qa.dashboard');

            Route::get('/qa/daftar-temuan', function () {
                if (auth()->user()->role === 'karyawan' && !auth()->user()->isPicUser()) {
                    return redirect()->route('beranda');
                }
                return view('pages.qa.daftar-temuan');
            })->name('qa.daftar-temuan');
        });

        // Role QA Only — Rekap Periode & Master Data SIVERA
        Route::middleware(['role:qa'])->group(function () {
            Route::view('/qa/rekap', 'pages.qa.rekap')->name('qa.rekap');
            Route::view('/qa/master/karyawan', 'pages.qa.master-karyawan')->name('qa.master.karyawan');
            Route::view('/qa/master/departemen', 'pages.qa.master-departemen')->name('qa.master.departemen');
            Route::view('/qa/master/klausul', 'pages.qa.master-klausul')->name('qa.master.klausul');
        });

        // Role QA & Super Admin — SIVERA Operational Master Data
        Route::middleware(['role:qa,superadmin'])->group(function () {
            Route::view('/qa/master/karyawan', 'pages.qa.master-karyawan')->name('qa.master.karyawan');
            Route::view('/qa/master/departemen', 'pages.qa.master-departemen')->name('qa.master.departemen');
            Route::view('/qa/master/klausul', 'pages.qa.master-klausul')->name('qa.master.klausul');
        });

        // Role Super Admin IT Only — Pusat Data Karyawan & Manajemen Akun User
        Route::middleware(['role:superadmin'])->group(function () {
            Route::view('/qa/master/seluruh-karyawan', 'pages.qa.master-seluruh-karyawan')->name('qa.master.seluruh-karyawan');
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
    });

    // ── BOS'Q Routes ──
    Route::middleware(['system_guard:bosq'])->prefix('bosq')->name('bosq.')->group(function () {
        Route::middleware(['role:karyawan,qa'])->group(function () {
            Route::view('/beranda', 'pages.bosq.beranda')->name('beranda');
        });

        Route::middleware(['role:qa'])->prefix('qa')->name('qa.')->group(function () {
            Route::get('/dashboard', \App\Livewire\BosQ\DashboardQA::class)->name('dashboard');
            Route::get('/daftar-observasi', \App\Livewire\BosQ\DaftarObservasiQA::class)->name('daftar-observasi');
            Route::get('/rekap', \App\Livewire\BosQ\RekapKepatuhan::class)->name('rekap');
            Route::get('/master/line', \App\Livewire\BosQ\MasterLine::class)->name('master.line');
            Route::get('/master/subarea', \App\Livewire\BosQ\MasterSubArea::class)->name('master.subarea');
            Route::get('/master/elemen', \App\Livewire\BosQ\MasterElemenQfs::class)->name('master.elemen');
            Route::get('/master/karyawan', \App\Livewire\BosQ\MasterKaryawan::class)->name('master.karyawan');

            // Export routes BOS'Q
            Route::get('/export/csv', [\App\Http\Controllers\BosqExportController::class, 'excel'])->name('export.csv');
            Route::get('/export/pdf/dashboard', [\App\Http\Controllers\BosqExportController::class, 'pdfDashboard'])->name('export.pdf.dashboard');
            Route::get('/export/rekap/csv', [\App\Http\Controllers\BosqExportController::class, 'rekapExcel'])->name('export.rekap.csv');
            Route::get('/export/rekap/pdf', [\App\Http\Controllers\BosqExportController::class, 'pdfRekap'])->name('export.rekap.pdf');
        });

        Route::get('/temuan/{bosqTemuan}', function (\App\Models\BosqTemuan $bosqTemuan) {
            return view('pages.bosq.detail-temuan', ['bosqTemuan' => $bosqTemuan]);
        })->name('temuan.detail')->middleware('can:view,bosqTemuan');
    });

});

require __DIR__.'/settings.php';
