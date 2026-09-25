<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
 public function register(): void
 {
 //
 }

 public function boot(): void
 {
 // SQLite foreign keys
 if (DB::connection()->getDriverName() === 'sqlite') {
 DB::statement('PRAGMA foreign_keys = ON;');
 }

 // Rate limiters
 RateLimiter::for('login', function (Request $request) {
 return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip());
 });

 RateLimiter::for('sms-send', function (Request $request) {
 return Limit::perHour(100)->by($request->user()?->id ?: $request->ip());
 });

 RateLimiter::for('sms-bulk', function (Request $request) {
 return Limit::perDay(1000)->by($request->user()?->school_id ?: $request->ip());
 });

 RateLimiter::for('api', function (Request $request) {
 return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
 });
 }
}