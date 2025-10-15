<?php

use App\Http\Controllers\ScrapingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\PriceHistoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/v1/components/cpus', [ComponentesController::class, 'getCpus']);
Route::get('/v1/components/graphic-cards', [ComponentesController::class, 'getGraphicsCards']);
Route::get('/v1/components/motherboards', [ComponentesController::class, 'getMotherboards']);
Route::get('/v1/components/power-supplies', [ComponentesController::class, 'getPowerSupplies']);
Route::get('/v1/components/rams', [ComponentesController::class, 'getRams']);
Route::get('/v1/components/storage-devices', [ComponentesController::class, 'getStorageDevices']);

Route::get('/v1/price-history/', [PriceHistoryController::class, 'getMonthlyData'])->name('price-history.monthly');