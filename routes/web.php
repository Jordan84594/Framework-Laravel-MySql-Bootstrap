<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;

Route::redirect('/', '/productos');
Route::resource('productos', ProductoController::class)->except(['show']);
Route::resource('categorias', CategoriaController::class)->except(['show']);
Route::resource('clientes', ClienteController::class);
Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
Route::get('/ventas/buscar', [VentaController::class, 'buscar'])->name('ventas.buscar');
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');