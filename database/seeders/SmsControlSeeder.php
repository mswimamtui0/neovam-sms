<?php

namespace Database\Seeders;

use App\Models\SmsControlSetting;
use Illuminate\Database\Seeder;

class SmsControlSeeder extends Seeder
{
 public function run(): void
 {
 $settings = [
 ["master_switch", "Automatic SMS — ALL", "1", "boolean", "Master switch for all automatic SMS"],
 ["test_mode", "Test Mode", "0", "boolean", "Log SMS but don't actually send"],
 ["schedule_enabled", "Time Schedule", "0", "boolean", "Only send SMS during scheduled window"],
 ["schedule_start", "Schedule Start Time", "07:00", "string", "Start time for SMS sending"],
 ["schedule_end", "Schedule End Time", "20:00", "string", "End time for SMS sending"],
 ["schedule_days", "Schedule Days", '["1","2","3","4","5"]', "json", "Days of week (1=Mon, 7=Sun)"],
 ["rate_limit_hour", "Rate Limit Per Hour", "200", "integer", "Max SMS per hour"],
 ["rate_limit_day", "Rate Limit Per Day", "2000", "integer", "Max SMS per day"],
 ["default_language", "Default Language", "en", "string", "en | sw | both"],
 ];

 foreach ($settings as $s) {
 SmsControlSetting::updateOrCreate(
 ["key" => $s[0]],
 [
 "label" => $s[1],
 "value" => $s[2],
 "type" => $s[3],
 "description" => $s[4],
 ]
 );
 }

 $this->command->info("Seeded " . count($settings) . " SMS control settings.");
 }
}