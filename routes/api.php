<?php

use App\Http\Controllers\ScrapingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/v1/scraping/pccomponentes', [ScrapingController::class, 'pcComponentesScraper']);
Route::get('/v1/scraping/coolmod', [ScrapingController::class, 'coolModScraper']);
Route::get('/v1/scraping/amazon', [ScrapingController::class, 'amazonScraper']);
Route::get('/v1/scraping/neobyte', [ScrapingController::class, 'neoByteScraper']);