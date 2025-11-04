<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:process-price-drops')
    ->dailyAt('14:00')
    ->withoutOverlapping();

Schedule::command('app:execute-web-scraping')
    ->dailyAt('02:00')
    ->withoutOverlapping();