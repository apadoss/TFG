<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\AIConsultantController;

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