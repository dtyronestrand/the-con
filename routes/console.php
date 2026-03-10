<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('sync:pull')->everyMinute()->withoutOverlapping();
Schedule::command('tasks:clear-today-due-dates')->dailyAt('23:59');
Schedule::command('tasks:clear-done')->dailyAt('00:00');
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
