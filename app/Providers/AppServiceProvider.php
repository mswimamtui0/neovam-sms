<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\EmergencyComposer;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ===== Rate limiters =====
        RateLimiter::for("login", function (Request $request) {
            return Limit::perMinute(5)->by($request->input("email") . "|" . $request->ip());
        });

        RateLimiter::for("api", function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // ===== Share emergency counts with every view =====
        try {
            View::composer("*", EmergencyComposer::class);
        } catch (\Throwable $e) {
            // Safe fail
        }

        // ===== SQLite foreign keys ON =====
        try {
            if (DB::connection()->getDriverName() === "sqlite") {
                DB::statement("PRAGMA foreign_keys = ON;");
            }
        } catch (\Throwable $e) {
            // Safe fail
        }
    }
}