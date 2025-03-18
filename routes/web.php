<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\AIConsultantController;
use App\Http\Controllers\ConfiguracionesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba', function () {
    return view('prueba');
})->name("prueba");

Route::get('/componentes/{type}', [ComponentesController::class, 'index'])->name('componentes.index');
Route::get('/componentes/{type}/{id}', [ComponentesController::class, 'view'])->name('componentes.view');

Route::get('/asesor-ia', [AIConsultantController::class, 'index'])->name('ai-consultant.index'); 
Route::post('/asesor-ia/message', [AIConsultantController::class, 'sendMessage']);

Route::get('/configuraciones/index', [ConfiguracionesController::class, 'index'])->name('configuraciones.index');
Route::get('/configuraciones/create', [ConfiguracionesController::class, 'create'])->name('configuraciones.create');
Route::post('/configuraciones/store', [ConfiguracionesController::class, 'store'])->name('configuraciones.store');