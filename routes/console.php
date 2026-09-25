<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command("inspire", function () {
    $this->comment(Inspiring::quote());
})->purpose("Display an inspiring quote");

// ============ SCHEDULED TASKS ============

// Send fee reminders every day at 08:00 AM
Schedule::command("fees:send-reminders")->dailyAt("08:00");
// Create automatic backup every day at 02:00 AM (keep 30 days)
Schedule::command("backup:create --prune=30")->dailyAt("02:00");