<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\LaptopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| UTS PBO - CRUD Inventaris Laptop (Sistem Jual Beli Laptop)
|--------------------------------------------------------------------------
| Urutan penting: URI statis (/laptop/create, /laptop/reset) ditulis
| SEBELUM URI dinamis (/laptop/{kode}) agar kata "create" tidak tertangkap
| sebagai parameter {kode}.
*/

Route::redirect('/', '/laptop');

// 1. Daftar laptop
Route::get('/laptop', [LaptopController::class, 'index'])
    ->name('laptop.index');

// 2. Form tambah laptop (statis, sebelum /{kode})
Route::get('/laptop/create', [LaptopController::class, 'create'])
    ->name('laptop.create');

// 3. Simpan laptop baru
Route::post('/laptop', [LaptopController::class, 'store'])
    ->name('laptop.store');

// Fitur tambahan: kembalikan data session ke data contoh awal (statis, sebelum /{kode})
Route::post('/laptop/reset', [LaptopController::class, 'reset'])
    ->name('laptop.reset');

// 4. Detail laptop
Route::get('/laptop/{kode}', [LaptopController::class, 'show'])
    ->name('laptop.show');

// 5. Form edit laptop
Route::get('/laptop/{kode}/edit', [LaptopController::class, 'edit'])
    ->name('laptop.edit');

// 6. Perbarui laptop (PUT/PATCH)
Route::match(['put', 'patch'], '/laptop/{kode}', [LaptopController::class, 'update'])
    ->name('laptop.update');

// 7. Hapus laptop
Route::delete('/laptop/{kode}', [LaptopController::class, 'destroy'])
    ->name('laptop.destroy');

/*
|--------------------------------------------------------------------------
| Praktikum Pertemuan 7: Modul Buku
|--------------------------------------------------------------------------
*/
Route::get('/buku', [BukuController::class, 'index'])
    ->name('buku.index');

Route::get('/buku/{id}', [BukuController::class, 'show'])
    ->name('buku.show');
