<?php

use App\Http\Controllers\ScrapingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/v1/scraping/pccomponentes', [ScrapingController::class, 'pcComponentesScraper']);
Route::get('/v1/scraping/coolmod', [ScrapingController::class, 'coolModScraper']);
Route::get('/v1/scraping/amazon', [ScrapingController::class, 'amazonScraper']);
Route::get('/v1/scraping/neobyte', [ScrapingController::class, 'neoByteScraper']);

Route::get('/v1/components/cpus', [ComponentesController::class, 'getCpus']);
Route::get('/v1/components/graphic-cards', [ComponentesController::class, 'getGraphicsCards']);
Route::get('/v1/components/motherboards', [ComponentesController::class, 'getMotherboards']);
Route::get('/v1/components/power-supplies', [ComponentesController::class, 'getPowerSupplies']);
Route::get('/v1/components/rams', [ComponentesController::class, 'getRams']);
Route::get('/v1/components/storage-devices', [ComponentesController::class, 'getStorageDevices']);