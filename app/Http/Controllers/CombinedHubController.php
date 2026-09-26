<?php

namespace App\Http\Controllers;

use App\Models\BoardingLog;
use App\Models\ClassRoom;
use App\Models\DisciplineCase;
use App\Models\DutyLog;
use App\Models\FeedingLog;
use App\Models\HealthRecord;
use App\Models\LaundryRecord;
use App\Models\MaintenanceRecord;
use App\Models\SecurityLog;
use App\Models\SportsRecord;
use App\Models\Staff;
use App\Models\StoreRecord;
use App\Models\TransportTrip;
use App\Models\UniformRecord;
use App\Services\DepartmentStatsService;
use Illuminate\Http\Request;

class CombinedHubController extends Controller
{
    /**
     * BOARDING HUB
     * Combines: boarding + feeding + health + discipline + security + laundry + stores + duty
     */
    public function boarding(Request $request)
    {
        $this->assertMember($request, "boarding");

        // ===== Live summary =====
        $summary = [
            "Boarders logged today"   => BoardingLog::whereDate("created_at", today())->count(),
            "Sick boarders today"     => HealthRecord::whereDate("created_at", today())
                ->whereIn("severity", ["medium","high","critical"])->count(),
            "Night incidents"         => BoardingLog::where("type", "incident")
                ->whereDate("created_at", today())->count(),
            "Meals served today"      => FeedingLog::whereDate("meal_date", today())->count(),
            "Laundry items pending"   => LaundryRecord::where("status", "pending")->count(),
            "Visitors logged today"   => SecurityLog::where("type", "visitor")
                ->whereDate("created_at", today())->count(),
        ];

        // ===== Sub-tabs =====
        $tabs = [
            "roll_call"   => [
                "label"   => "Roll Call",
                "records" => BoardingLog::where("type", "roll_call")->latest()->limit(20)->get(),
                "columns" => ["dorm_name","description","status","recorded_by","created_at"],
            ],
            "meals"       => [
                "label"   => "Meals",
                "records" => FeedingLog::latest()->limit(20)->get(),
                "columns" => ["meal_date","meal_type","menu","served_count","status","recorded_by"],
            ],
            "health"      => [
                "label"   => "Health",
                "records" => HealthRecord::latest()->limit(20)->get(),
                "columns" => ["student_name","class_name","type","severity","status","recorded_by"],
            ],
            "discipline"  => [
                "label"   => "Discipline",
                "records" => DisciplineCase::latest()->limit(20)->get(),
                "columns" => ["student_name","class_name","category","offence","status","recorded_by"],
            ],
            "visitors"    => [
                "label"   => "Visitors",
                "records" => SecurityLog::where("type", "visitor")->latest()->limit(20)->get(),
                "columns" => ["visitor_name","visitor_phone","purpose","status","recorded_by","created_at"],
            ],
            "laundry"     => [
                "label"   => "Laundry",
                "records" => LaundryRecord::latest()->limit(20)->get(),
                "columns" => ["dorm_name","item","quantity","status","recorded_by"],
            ],
            "incidents"   => [
                "label"   => "Incidents",
                "records" => BoardingLog::where("type", "incident")->latest()->limit(20)->get(),
                "columns" => ["dorm_name","description","status","recorded_by","created_at"],
            ],
        ];

        $activeTab = $request->query("tab", "roll_call");

        return view("hubs.boarding", compact("summary", "tabs", "activeTab"));
    }

    /**
     * SPORTS HUB
     * Combines: sports + health + transport + uniform
     */
    public function sports(Request $request)
    {
        $this->assertMember($request, "sports");

        $summary = [
            "Matches this week"    => SportsRecord::where("event_type", "match")
                ->whereBetween("created_at", [now()->startOfWeek(), now()->endOfWeek()])->count(),
            "Trainings this week"  => SportsRecord::where("event_type", "training")
                ->whereBetween("created_at", [now()->startOfWeek(), now()->endOfWeek()])->count(),
            "Injuries reported"    => HealthRecord::where("type", "injury")
                ->whereDate("created_at", today())->count(),
            "Team trips today"     => TransportTrip::whereDate("trip_date", today())->count(),
            "Uniform orders"       => UniformRecord::where("status", "pending")->count(),
        ];

        $tabs = [
            "matches"   => [
                "label"   => "Matches",
                "records" => SportsRecord::where("event_type", "match")->latest()->limit(20)->get(),
                "columns" => ["sport_name","team_name","opponent","venue","result","recorded_by"],
            ],
            "trainings" => [
                "label"   => "Trainings",
                "records" => SportsRecord::where("event_type", "training")->latest()->limit(20)->get(),
                "columns" => ["sport_name","team_name","venue","notes","recorded_by"],
            ],
            "injuries"  => [
                "label"   => "Injuries",
                "records" => HealthRecord::where("type", "injury")->latest()->limit(20)->get(),
                "columns" => ["student_name","class_name","symptoms","severity","status","recorded_by"],
            ],
            "trips"     => [
                "label"   => "Team Trips",
                "records" => TransportTrip::latest()->limit(20)->get(),
                "columns" => ["bus_code","route_name","driver_name","trip_date","direction","status"],
            ],
            "uniforms"  => [
                "label"   => "Uniforms",
                "records" => UniformRecord::latest()->limit(20)->get(),
                "columns" => ["student_name","class_name","item","size","status","recorded_by"],
            ],
        ];

        $activeTab = $request->query("tab", "matches");

        return view("hubs.sports", compact("summary", "tabs", "activeTab"));
    }

    /**
     * TRANSPORT HUB
     * Combines: transport + security + maintenance + finance
     */
    public function transport(Request $request)
    {
        $this->assertMember($request, "transport");

        $summary = [
            "Trips today"          => TransportTrip::whereDate("trip_date", today())->count(),
            "In progress"          => TransportTrip::where("status", "in_progress")->count(),
            "Completed today"      => TransportTrip::where("status", "completed")
                ->whereDate("trip_date", today())->count(),
            "Fuel logged today (L)"=> TransportTrip::whereDate("trip_date", today())->sum("fuel_used"),
            "Bus repairs open"     => MaintenanceRecord::where("item", "LIKE", "%bus%")
                ->where("status", "!=", "completed")->count(),
            "Gate passes today"    => SecurityLog::where("type", "gate")
                ->whereDate("created_at", today())->count(),
        ];

        $tabs = [
            "trips"        => [
                "label"   => "Trips",
                "records" => TransportTrip::latest()->limit(20)->get(),
                "columns" => ["bus_code","route_name","driver_name","trip_date","direction","status","recorded_by"],
            ],
            "fuel"         => [
                "label"   => "Fuel Log",
                "records" => TransportTrip::where("fuel_used", ">", 0)->latest()->limit(20)->get(),
                "columns" => ["bus_code","trip_date","fuel_used","driver_name","recorded_by"],
            ],
            "maintenance"  => [
                "label"   => "Bus Maintenance",
                "records" => MaintenanceRecord::where("item", "LIKE", "%bus%")->latest()->limit(20)->get(),
                "columns" => ["location","item","issue","priority","status","recorded_by"],
            ],
            "gate_passes"  => [
                "label"   => "Gate Passes",
                "records" => SecurityLog::where("type", "gate")->latest()->limit(20)->get(),
                "columns" => ["visitor_name","purpose","status","recorded_by","created_at"],
            ],
            "incidents"    => [
                "label"   => "Incidents",
                "records" => SecurityLog::where("type", "incident")->latest()->limit(20)->get(),
                "columns" => ["description","status","recorded_by","created_at"],
            ],
        ];

        $activeTab = $request->query("tab", "trips");

        return view("hubs.transport", compact("summary", "tabs", "activeTab"));
    }

    /* ============ Permissions ============ */

    protected function assertMember(Request $request, string $code): void
    {
        $user = $request->user();
        if (!$user) abort(403);

        if (method_exists($user, "hasRole") && $user->hasRole("admin")) return;

        $staff = Staff::where("user_id", $user->id)->first();
        if (!$staff) abort(403, "Not assigned to any department.");

        $allowed = false;

        if (optional($staff->primaryDepartment)->code === $code) $allowed = true;
        if (!$allowed && in_array($code, $staff->allRoles(), true)) $allowed = true;

        // Combined hubs are accessible to any staff in the related departments
        $related = match ($code) {
            "boarding"  => ["boarding","feeding","health","discipline","security","laundry","stores","duty"],
            "sports"    => ["sports","health","transport","uniform"],
            "transport" => ["transport","security","maintenance","finance"],
            default     => [$code],
        };

        if (!$allowed) {
            foreach ($related as $rel) {
                if (in_array($rel, $staff->allRoles(), true)) { $allowed = true; break; }
                if (optional($staff->primaryDepartment)->code === $rel) { $allowed = true; break; }
            }
        }

        if (!$allowed) abort(403, "You are not a member of the {$code} hub.");
    }
}