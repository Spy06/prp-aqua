<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

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

    // Role QA — Dashboard, Rekap, Master Data
    Route::middleware(['role:qa'])->group(function () {
        Route::view('/qa/dashboard', 'dashboard')->name('qa.dashboard');
        Route::view('/qa/rekap', 'pages.qa.rekap')->name('qa.rekap');
        Route::view('/qa/master/karyawan', 'pages.qa.master-karyawan')->name('qa.master.karyawan');
        Route::view('/qa/master/departemen', 'pages.qa.master-departemen')->name('qa.master.departemen');
        Route::view('/qa/master/klausul', 'pages.qa.master-klausul')->name('qa.master.klausul');
        Route::view('/qa/master/akun', 'pages.qa.master-akun')->name('qa.master.akun');
    });

    // Export routes — hanya QA (dicek di ExportController::requireQa())
    Route::middleware(['role:qa'])->group(function () {
        Route::get('/export/excel', [\App\Http\Controllers\ExportController::class, 'excel'])->name('export.excel');
        Route::get('/export/pdf/temuan/{temuan}', [\App\Http\Controllers\ExportController::class, 'pdfTemuan'])->name('export.pdf.temuan');
        Route::get('/export/pdf/rekap', [\App\Http\Controllers\ExportController::class, 'pdfRekap'])->name('export.pdf.rekap');
    });

    // Temuan detail (accessible by pelapor_id, pic_id or role qa via Policy)
    // Policy ditegakkan di DAUD level: middleware route + AuthorizesRequests di Livewire mount()
    Route::get('/temuan/{temuan}', function (\App\Models\Temuan $temuan) {
        return view('pages.temuan-detail', ['temuan' => $temuan]);
    })->name('temuan.detail')->middleware('can:view,temuan');

    // Route untuk test job queue WA (Hanya untuk Hari 2)
    Route::get('/test-wa', function () {
        \App\Jobs\SendWhatsAppDummy::dispatch();
        return "Job SendWhatsAppDummy telah dikirim ke Queue!";
    });

});

require __DIR__.'/settings.php';
