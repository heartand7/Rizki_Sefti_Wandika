<?php

use App\Http\Controllers\LandController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandController::class, 'index']);

// Route Hapus Semua (Harus di atas resource agar tidak terbaca sebagai ID)
Route::delete('/lands/destroy-all', [LandController::class, 'destroyAll'])->name('lands.destroyAll');

Route::resource('lands', LandController::class);