<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Pendaftaran publik DINONAKTIFKAN — akun hanya dibuat oleh QA (admin master)
// Rute /register secara eksplisit mengembalikan 404
Route::get('/register', fn () => abort(404));
Route::post('/register', fn () => abort(404));

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
