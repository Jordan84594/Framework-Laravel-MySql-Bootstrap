<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/', '/productos');
Route::resource('productos', ProductoController::class);
Route::resource('productos', ProductoController::class)->except(['show']);
Route::resource('clientes', ClienteController::class);