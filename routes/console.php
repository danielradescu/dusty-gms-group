<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('run:every-minute')->everyMinute();
Schedule::command('magic-links:cleanup')->dailyAt('00:33');
Schedule::command('app:reset-playable-days')->dailyAt('23:59');
Schedule::command('app:clean-old-join-requests')->dailyAt('00:20');

// 🕓 Schedule BGG weekly sync (Thursday night), preferable before day starts so that he can do a refresh the next day if needed
Schedule::command('bgg:weekly-sync')
    ->weeklyOn(4, '22:00')
    ->withoutOverlapping()
    ->onOneServer();
