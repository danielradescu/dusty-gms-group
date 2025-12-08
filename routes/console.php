<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('run:every-minute')->everyMinute();
Schedule::command('magic-links:cleanup')->daily();
Schedule::command('app:reset-playable-days')->dailyAt('23:59');
Schedule::command('app:clean-old-join-requests')->dailyAt('00:20');
