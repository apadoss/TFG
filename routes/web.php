<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\AIConsultantController;
use App\Http\Controllers\ConfiguracionesController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PriceNotificationController;

Route::get('/', function () {
    return view('welcome');
})->name("home");


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name("register");
Route::post('/register', [RegisterController::class, 'register'])->name("register.create");

Route::get('/login', [LoginController::class, 'showLoginForm'])->name("login");
Route::post('/login', [LoginController::class, 'login'])->name("login.authenticate");

Route::post('/logout', [LogoutController::class, 'logout'])->name("logout");

Route::get('/login', function () {
    return view('auth.login');
})->name("login");

Route::get('/componentes/{type}', [ComponentesController::class, 'index'])->name('componentes.index');
Route::get('/componentes/{type}/{id}', [ComponentesController::class, 'view'])->name('componentes.view');
Route::get('/products/{type}/compare/{product1}/{product2?}', [ComponentesController::class, 'compare'])->name('componentes.compare');

Route::get('/asesor-ia', [AIConsultantController::class, 'index'])->name('ai-consultant.index'); 
Route::post('/asesor-ia/message', [AIConsultantController::class, 'sendMessage']);

Route::get('/configuraciones/index', [ConfiguracionesController::class, 'index'])->name('configuraciones.index');
Route::get('/configuraciones/create', [ConfiguracionesController::class, 'create'])->name('configuraciones.create');
Route::post('/configuraciones/store', [ConfiguracionesController::class, 'store'])->name('configuraciones.store');
Route::get('/configuraciones/{id}/comparar/{id2?}', [ConfiguracionesController::class, 'compare'])->name('configuraciones.compare');

Route::middleware(['auth'])->group(function () {
    Route::post('/notifications/toggle', [PriceNotificationController::class, 'toggle'])->name('notifications.toggle');
    Route::post('/notifications/deactivate', [PriceNotificationController::class, 'deactivate'])->name('notifications.deactivate');
    Route::get('/notifications/check', [PriceNotificationController::class, 'check'])->name('notifications.check');
    Route::get('/mis-notificaciones', [PriceNotificationController::class, 'index'])->name('notifications.index');
});