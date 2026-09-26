<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\Student;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\Log;

class CombinedHubSmsService
{
    public function __construct(protected SmsService $sms) {}

    protected function send(string $phone, string $message, string $trigger): void
    {
        if (!$phone) return;
        try {
            $this->sms->send($phone, $message, $trigger);
        } catch (\Throwable $e) {
            Log::error("Hub SMS failed", [
                "phone"   => $phone,
                "trigger" => $trigger,
                "error"   => $e->getMessage(),
            ]);
        }
    }

    protected function headPhone(): ?string
    {
        return Staff::where("staff_type", "Head of School")->first()?->phone;
    }

    protected function wardenPhone(): ?string
    {
        $warden = Staff::where("department", "LIKE", "%Boarding%")->first();
        return $warden?->phone;
    }

    /* ============ BOARDING HUB ============ */

    public function boardingRollCall($record): void
    {
        $this->send($this->wardenPhone(),
            "Roll call for {$record->dorm_name} complete: {$record->description}",
            "boarding_roll_call_done");

        if (($record->status ?? "") === "incident") {
            $this->send($this->headPhone(),
                "Boarding incident: {$record->dorm_name} — {$record->description}",
                "boarding_night_incident");
        }
    }

    public function boardingNightIncident($record): void
    {
        $this->send($this->headPhone(),
            "Night incident in {$record->dorm_name}: {$record->description}",
            "boarding_night_incident");
    }

    /* ============ SPORTS HUB ============ */

    public function sportsMatchResult($record): void
    {
        if (($record->event_type ?? "") !== "match") return;

        $this->send($this->headPhone(),
            "{$record->sport_name} — {$record->team_name} vs {$record->opponent}: {$record->result}",
            "sports_match_result");
    }

    public function sportsTeamTrip($record): void
    {
        $this->send($this->headPhone(),
            "Team trip: {$record->bus_code} on {$record->route_name} for {$record->driver_name}",
            "sports_team_trip");
    }

    /* ============ TRANSPORT HUB ============ */

    public function transportTripStarted($record): void
    {
        $this->send($this->headPhone(),
            "Bus {$record->bus_code} started trip on {$record->route_name}",
            "transport_trip_started");
    }

    public function transportTripDelayed($record): void
    {
        $this->send($this->headPhone(),
            "Bus {$record->bus_code} delayed on {$record->route_name}",
            "transport_trip_delayed");
    }

    public function transportBusBreakdown($record): void
    {
        $this->send($this->headPhone(),
            "Bus {$record->bus_code} broke down on {$record->route_name}",
            "transport_bus_breakdown");
    }
}