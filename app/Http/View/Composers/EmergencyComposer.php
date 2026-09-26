<?php

namespace App\Http\View\Composers;

use App\Services\EmergencyStatsService;
use Illuminate\View\View;

class EmergencyComposer
{
    public function compose(View $view): void
    {
        // Only compute if user is authenticated (avoids queries on login page)
        if (!auth()->check()) {
            $view->with("emergencyOpenCount", 0);
            $view->with("emergencyCriticalCount", 0);
            return;
        }

        try {
            $view->with("emergencyOpenCount", EmergencyStatsService::openCount());
            $view->with("emergencyCriticalCount", EmergencyStatsService::criticalOpenCount());
        } catch (\Throwable $e) {
            $view->with("emergencyOpenCount", 0);
            $view->with("emergencyCriticalCount", 0);
        }
    }
}