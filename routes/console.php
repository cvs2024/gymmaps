<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('gymmap:import-kvk-sports --pages=5 --per-page=100')
    ->dailyAt('02:15')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('gymmap:import-osm-sports')
    ->dailyAt('03:15')
    ->withoutOverlapping()
    ->onOneServer();
