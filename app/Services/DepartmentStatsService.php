<?php

namespace App\Services;

use App\Models\AdministrationLog;
use App\Models\BoardingLog;
use App\Models\CounselingSession;
use App\Models\DisciplineCase;
use App\Models\DutyLog;
use App\Models\EnvironmentLog;
use App\Models\FeedingLog;
use App\Models\FinanceRecord;
use App\Models\HealthRecord;
use App\Models\LibraryLoan;
use App\Models\SecurityLog;
use App\Models\SportsRecord;

class DepartmentStatsService
{
    /**
     * Return live counts for a department code.
     * Used by the Hub page — no icons, plain numbers.
     */
    public static function forCode(string $code): array
    {
        return match ($code) {
            "health" => self::health(),
            "transport" => self::transport(),
            "hr" => self::hr(),
            "music" => self::music(),
            "drama" => self::drama(),
            "agriculture" => self::agriculture(),
            "chaplaincy" => self::chaplaincy(),
            "alumni" => self::alumni(),
            "special_needs" => self::specialNeeds(),
            "counselling" => self::counselling(),
            "procurement" => self::procurement(),
            "records" => self::records(),
            "uniform" => self::uniform(),
            "laundry" => self::laundry(),
            "stores" => self::stores(),
            "maintenance" => self::maintenance(),
            "front_office" => self::frontOffice(),
            "ict" => self::ict(),
            "discipline" => self::discipline(),
            "sports" => self::sports(),
            "duty" => self::duty(),
            "boarding" => self::boarding(),
            "feeding" => self::feeding(),
            "library" => self::library(),
            "guidance" => self::guidance(),
            "environment" => self::environment(),
            "security" => self::security(),
            "finance" => self::finance(),
            "administration" => self::administration(),
            default => [],
        };
    }

    private static function health(): array
    {
        return [
            "Total records"    => HealthRecord::count(),
            "Open cases"       => HealthRecord::where("status", "open")->count(),
            "Critical / High"  => HealthRecord::whereIn("severity", ["high","critical"])->count(),
            "Today"            => HealthRecord::whereDate("created_at", today())->count(),
        ];
    }

    private static function discipline(): array
    {
        return [
            "Total cases"    => DisciplineCase::count(),
            "Open"           => DisciplineCase::where("status", "open")->count(),
            "Serious"        => DisciplineCase::where("category", "serious")->count(),
            "Today"          => DisciplineCase::whereDate("created_at", today())->count(),
        ];
    }

    private static function sports(): array
    {
        return [
            "Total records"   => SportsRecord::count(),
            "Matches"         => SportsRecord::where("event_type", "match")->count(),
            "Trainings"       => SportsRecord::where("event_type", "training")->count(),
            "Injuries"        => SportsRecord::where("event_type", "injury")->count(),
        ];
    }

    private static function duty(): array
    {
        return [
            "Total duties"    => DutyLog::count(),
            "Scheduled"       => DutyLog::where("status", "scheduled")->count(),
            "Done"            => DutyLog::where("status", "done")->count(),
            "Missed"          => DutyLog::where("status", "missed")->count(),
        ];
    }

    private static function boarding(): array
    {
        return [
            "Total logs"      => BoardingLog::count(),
            "Incidents"       => BoardingLog::where("type", "incident")->count(),
            "Open"            => BoardingLog::where("status", "open")->count(),
            "Today"           => BoardingLog::whereDate("created_at", today())->count(),
        ];
    }

    private static function feeding(): array
    {
        return [
            "Total logs"      => FeedingLog::count(),
            "Planned"         => FeedingLog::where("status", "planned")->count(),
            "Served"          => FeedingLog::where("status", "served")->count(),
            "Allergy notes"   => FeedingLog::whereNotNull("allergy_notes")->count(),
        ];
    }

    private static function library(): array
    {
        return [
            "Total loans"     => LibraryLoan::count(),
            "Borrowed"        => LibraryLoan::where("status", "borrowed")->count(),
            "Overdue"         => LibraryLoan::where("status", "overdue")->count(),
            "Returned"        => LibraryLoan::where("status", "returned")->count(),
        ];
    }

    private static function guidance(): array
    {
        return [
            "Total sessions"  => CounselingSession::count(),
            "Open"            => CounselingSession::where("status", "open")->count(),
            "Closed"          => CounselingSession::where("status", "closed")->count(),
            "Today"           => CounselingSession::whereDate("created_at", today())->count(),
        ];
    }

    private static function environment(): array
    {
        return [
            "Total logs"      => EnvironmentLog::count(),
            "Open"            => EnvironmentLog::where("status", "open")->count(),
            "Resolved"        => EnvironmentLog::where("status", "resolved")->count(),
            "Low ratings"     => EnvironmentLog::where("rating", "<=", 2)->count(),
        ];
    }

    private static function security(): array
    {
        return [
            "Total logs"      => SecurityLog::count(),
            "Visitors"        => SecurityLog::where("type", "visitor")->count(),
            "Incidents"       => SecurityLog::where("type", "incident")->count(),
            "Today"           => SecurityLog::whereDate("created_at", today())->count(),
        ];
    }

    private static function finance(): array
    {
        return [
            "Total records"   => FinanceRecord::count(),
            "Payments"        => FinanceRecord::where("type", "payment")->count(),
            "Invoices"        => FinanceRecord::where("type", "invoice")->count(),
            "Pending"         => FinanceRecord::where("status", "pending")->count(),
        ];
    }

    private static function administration(): array
    {
        return [
            "Total records"   => AdministrationLog::count(),
            "Drafts"          => AdministrationLog::where("status", "draft")->count(),
            "Published"       => AdministrationLog::where("status", "published")->count(),
            "Letters"         => AdministrationLog::where("type", "letter")->count(),
        ];
    }
    private static function transport(): array
    {
        return [
            "Total trips"     => \App\Models\TransportTrip::count(),
            "Today"           => \App\Models\TransportTrip::whereDate("trip_date", today())->count(),
            "In progress"     => \App\Models\TransportTrip::where("status", "in_progress")->count(),
            "Completed"       => \App\Models\TransportTrip::where("status", "completed")->count(),
        ];
    }

    private static function stores(): array
    {
        return [
            "Total records"   => \App\Models\StoreRecord::count(),
            "Stock in"        => \App\Models\StoreRecord::where("type", "in")->count(),
            "Stock out"       => \App\Models\StoreRecord::where("type", "out")->count(),
            "Pending"         => \App\Models\StoreRecord::where("status", "pending")->count(),
        ];
    }

    private static function maintenance(): array
    {
        return [
            "Total jobs"      => \App\Models\MaintenanceRecord::count(),
            "Open"            => \App\Models\MaintenanceRecord::where("status", "open")->count(),
            "In progress"     => \App\Models\MaintenanceRecord::where("status", "in_progress")->count(),
            "Completed"       => \App\Models\MaintenanceRecord::where("status", "completed")->count(),
        ];
    }

    private static function frontOffice(): array
    {
        return [
            "Total records"   => \App\Models\FrontOfficeRecord::count(),
            "Visitors"        => \App\Models\FrontOfficeRecord::where("type", "visitor")->count(),
            "Open"            => \App\Models\FrontOfficeRecord::where("status", "open")->count(),
            "Today"           => \App\Models\FrontOfficeRecord::whereDate("created_at", today())->count(),
        ];
    }

    private static function ict(): array
    {
        return [
            "Total tickets"   => \App\Models\IctRecord::count(),
            "Open"            => \App\Models\IctRecord::where("status", "open")->count(),
            "In progress"     => \App\Models\IctRecord::where("status", "in_progress")->count(),
            "Resolved"        => \App\Models\IctRecord::where("status", "resolved")->count(),
        ];
    }
    private static function hr(): array
    {
        return [
            "Total records"  => \App\Models\HrRecord::count(),
            "Leave"          => \App\Models\HrRecord::where("type", "leave")->count(),
            "Approved"       => \App\Models\HrRecord::where("status", "approved")->count(),
            "Open"           => \App\Models\HrRecord::where("status", "open")->count(),
        ];
    }

    private static function procurement(): array
    {
        return [
            "Total records"  => \App\Models\ProcurementRecord::count(),
            "Pending"        => \App\Models\ProcurementRecord::where("status", "pending")->count(),
            "Ordered"        => \App\Models\ProcurementRecord::where("status", "ordered")->count(),
            "Received"       => \App\Models\ProcurementRecord::where("status", "received")->count(),
        ];
    }

    private static function records(): array
    {
        return [
            "Total entries"  => \App\Models\RecordsEntry::count(),
            "Active"         => \App\Models\RecordsEntry::where("status", "active")->count(),
            "Archived"       => \App\Models\RecordsEntry::where("status", "archived")->count(),
            "Certificates"   => \App\Models\RecordsEntry::where("type", "certificate")->count(),
        ];
    }

    private static function uniform(): array
    {
        return [
            "Total orders"   => \App\Models\UniformRecord::count(),
            "Pending"        => \App\Models\UniformRecord::where("status", "pending")->count(),
            "Ready"          => \App\Models\UniformRecord::where("status", "ready")->count(),
            "Delivered"      => \App\Models\UniformRecord::where("status", "delivered")->count(),
        ];
    }

    private static function laundry(): array
    {
        return [
            "Total records"  => \App\Models\LaundryRecord::count(),
            "Pending"        => \App\Models\LaundryRecord::where("status", "pending")->count(),
            "In progress"    => \App\Models\LaundryRecord::where("status", "in_progress")->count(),
            "Returned"       => \App\Models\LaundryRecord::where("status", "returned")->count(),
        ];
    }
    private static function music(): array
    {
        return [
            "Total records"   => \App\Models\MusicRecord::count(),
            "Rehearsals"      => \App\Models\MusicRecord::where("type", "rehearsal")->count(),
            "Performances"    => \App\Models\MusicRecord::where("type", "performance")->count(),
            "Scheduled"       => \App\Models\MusicRecord::where("status", "scheduled")->count(),
        ];
    }

    private static function drama(): array
    {
        return [
            "Total records"   => \App\Models\DramaRecord::count(),
            "Rehearsals"      => \App\Models\DramaRecord::where("type", "rehearsal")->count(),
            "Performances"    => \App\Models\DramaRecord::where("type", "performance")->count(),
            "Scheduled"       => \App\Models\DramaRecord::where("status", "scheduled")->count(),
        ];
    }

    private static function agriculture(): array
    {
        return [
            "Total records"   => \App\Models\AgricultureRecord::count(),
            "Planned"         => \App\Models\AgricultureRecord::where("status", "planned")->count(),
            "In progress"     => \App\Models\AgricultureRecord::where("status", "in_progress")->count(),
            "Done"            => \App\Models\AgricultureRecord::where("status", "done")->count(),
        ];
    }

    private static function chaplaincy(): array
    {
        return [
            "Total records"   => \App\Models\ChaplaincyRecord::count(),
            "Services"        => \App\Models\ChaplaincyRecord::where("type", "service")->count(),
            "Visits"          => \App\Models\ChaplaincyRecord::where("type", "visit")->count(),
            "Scheduled"       => \App\Models\ChaplaincyRecord::where("status", "scheduled")->count(),
        ];
    }

    private static function alumni(): array
    {
        return [
            "Total records"   => \App\Models\AlumniRecord::count(),
            "Events"          => \App\Models\AlumniRecord::where("type", "event")->count(),
            "Donations"       => \App\Models\AlumniRecord::where("type", "donation")->count(),
            "Reunions"        => \App\Models\AlumniRecord::where("type", "reunion")->count(),
        ];
    }

    private static function specialNeeds(): array
    {
        return [
            "Total records"   => \App\Models\SpecialNeedsRecord::count(),
            "Active"          => \App\Models\SpecialNeedsRecord::where("status", "active")->count(),
            "Monitoring"      => \App\Models\SpecialNeedsRecord::where("status", "monitoring")->count(),
            "Closed"          => \App\Models\SpecialNeedsRecord::where("status", "closed")->count(),
        ];
    }

    private static function counselling(): array
    {
        return [
            "Total sessions"  => \App\Models\PsychosocialRecord::count(),
            "Open"            => \App\Models\PsychosocialRecord::where("status", "open")->count(),
            "Monitoring"      => \App\Models\PsychosocialRecord::where("status", "monitoring")->count(),
            "Closed"          => \App\Models\PsychosocialRecord::where("status", "closed")->count(),
        ];
    }
}