<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba', function () {
    return view('prueba');
})->name("prueba");

Route::get('/componentes/{type}', [ComponentesController::class, 'index'])->name('componentes.index');
Route::get('/componentes/{type}/{id}', [ComponentesController::class, 'view'])->name('componentes.view');