<?php

namespace App\Services;

use App\Models\Emergency;

class EmergencyStatsService
{
    /**
     * Overall counts for the current day.
     */
    public static function today(): array
    {
        return [
            "today"    => Emergency::whereDate("created_at", today())->count(),
            "critical" => Emergency::where("severity", "critical")
                            ->whereDate("created_at", today())->count(),
            "urgent"   => Emergency::where("severity", "urgent")
                            ->whereDate("created_at", today())->count(),
            "open"     => Emergency::whereIn("status", ["open","monitoring"])->count(),
        ];
    }

    /**
     * Count of open emergencies (used for badge).
     */
    public static function openCount(): int
    {
        return Emergency::whereIn("status", ["open","monitoring"])->count();
    }

    /**
     * Count of unresolved critical emergencies (used for red badge).
     */
    public static function criticalOpenCount(): int
    {
        return Emergency::where("severity", "critical")
            ->whereIn("status", ["open","monitoring"])->count();
    }

    /**
     * Latest N emergencies.
     */
    public static function latest(int $limit = 5)
    {
        return Emergency::latest()->limit($limit)->get();
    }
}