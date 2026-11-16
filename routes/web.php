<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;

Route::redirect('/', '/productos');
Route::resource('productos', ProductoController::class)->except(['show']);
Route::resource('categorias', CategoriaController::class)->except(['show']);
Route::resource('clientes', ClienteController::class);