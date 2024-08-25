<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\HitungProdukController;
use App\Http\Controllers\MasterProductController;
use App\Http\Controllers\ProdukBundlingController;
use App\Http\Controllers\HitungPengunjungController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Authentication
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout']);


//Master Produk
Route::middleware('admin.auth')->group(function () {
    Route::post('master-products', [MasterProductController::class, 'store'])->name('master-products.store');
    Route::put('master-products/{master_product}', [MasterProductController::class, 'update'])->name('master-products.update');    
    Route::delete('master-products/{master_product}', [MasterProductController::class, 'destroy'])->name('master-products.destroy');
    Route::get('count-products', [HitungProdukController::class, 'countProducts']);
});
    Route::get('master-products', [MasterProductController::class, 'index'])->name('master-products.index');
    Route::get('master-products/{master_product}', [MasterProductController::class, 'show'])->name('master-products.show');

//Produk Bundlings
Route::middleware('admin.auth')->group(function () {
    Route::post('produk-bundlings', [ProdukBundlingController::class, 'store'])->name('produk-bundlings.store');
    Route::put('produk-bundlings/{produk-bundling}', [ProdukBundlingController::class, 'update'])->name('produk-bundlings.update');    
    Route::delete('produk-bundlings/{produk-bundling}', [ProdukBundlingController::class, 'destroy'])->name('produk-bundlings.destroy');
});
    Route::get('produk-bundlings', [ProdukBundlingController::class, 'index'])->name('produk-bundlings.index');
    Route::get('produk-bundlings/{produk_bundling}', [ProdukBundlingController::class, 'show'])->name('produk-bundlings.show');

//kategoris
Route::middleware('admin.auth')->group(function () {
    Route::post('kategoris', [KategoriController::class, 'store'])->name('kategoris.store');
    Route::put('kategoris/{kategori}', [KategoriController::class, 'update'])->name('kategoris.update');    
    Route::delete('kategoris/{kategori}', [KategoriController::class, 'destroy'])->name('kategoris.destroy');
});
    Route::get('kategoris', [KategoriController::class, 'index'])->name('kategoris.index');
    Route::get('kategoris/{kategori}', [KategoriController::class, 'show'])->name('kategoris.show');

//Hitung Pengunjung
Route::post('/track-visitor', [HitungPengunjungController::class, 'trackVisitor']);
Route::get('/visitor-stats', [HitungPengunjungController::class, 'getVisitorStatsApi']);