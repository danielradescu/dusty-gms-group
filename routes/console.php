<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('run:every-minute')->everyMinute();
