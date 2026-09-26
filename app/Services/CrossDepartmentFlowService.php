<?php

namespace App\Services;

use App\Models\DepartmentMirror;
use App\Models\School;

class CrossDepartmentFlowService
{
    /**
     * Mirror a record from one department to another (read-only shared view).
     */
    public static function mirror(string $sourceTable, int $sourceId, string $targetDepartment, string $summary): void
    {
        DepartmentMirror::create([
            "school_id"         => School::first()?->id,
            "source_table"      => $sourceTable,
            "source_id"         => $sourceId,
            "target_department" => $targetDepartment,
            "summary"           => $summary,
            "read_only"         => true,
        ]);
    }

    /**
     * Record a domain event that auto-flows to related departments.
     * Called from department controllers' afterCreate() hooks.
     */
    public static function flow(string $source, $record): void
    {
        switch ($source) {

            // ============ HEALTH ============
            case "health":
                // Injury from sport → Sports department
                if (($record->type ?? "") === "injury") {
                    self::mirror("health_records", $record->id, "sports",
                        "Injury logged for {$record->student_name}: {$record->symptoms}");
                }
                // Serious / emergency → Discipline department
                if (in_array($record->severity ?? "", ["high","critical"], true)) {
                    self::mirror("health_records", $record->id, "discipline",
                        "Health incident ({$record->severity}) for {$record->student_name}");
                }
                break;

            // ============ DISCIPLINE ============
            case "discipline":
                // Any discipline case → Class Teacher is notified (via mirror)
                self::mirror("discipline_cases", $record->id, "class_teacher",
                    "Discipline case for {$record->student_name} ({$record->category}): {$record->offence}");
                // Serious → Health (possible injury)
                if (in_array($record->category ?? "", ["serious"], true)) {
                    self::mirror("discipline_cases", $record->id, "health",
                        "Serious incident involving {$record->student_name}");
                }
                break;

            // ============ SPORTS ============
            case "sports":
                if (($record->event_type ?? "") === "injury") {
                    self::mirror("sports_records", $record->id, "health",
                        "Sports injury during {$record->sport_name} — {$record->team_name}");
                }
                break;

            // ============ FEEDING ============
            case "feeding":
                if (!empty($record->allergy_notes)) {
                    self::mirror("feeding_logs", $record->id, "health",
                        "Allergy note for meal on {$record->meal_date}: {$record->allergy_notes}");
                }
                break;

            // ============ LIBRARY ============
            case "library":
                if (($record->status ?? "") === "overdue") {
                    self::mirror("library_loans", $record->id, "class_teacher",
                        "Book overdue: {$record->book_title} — {$record->student_name}");
                }
                break;

            // ============ BOARDING ============
            case "boarding":
                if (($record->type ?? "") === "incident") {
                    self::mirror("boarding_logs", $record->id, "discipline",
                        "Boarding incident in {$record->dorm_name}: {$record->description}");
                }
                break;

            // ============ SECURITY ============
            case "security":
                if (($record->type ?? "") === "incident") {
                    self::mirror("security_logs", $record->id, "administration",
                        "Security incident: {$record->description}");
                }
                break;

            // ============ FINANCE ============
            case "finance":
                if (($record->type ?? "") === "payment" && ($record->status ?? "") === "paid") {
                    self::mirror("finance_records", $record->id, "administration",
                        "Payment received: {$record->amount} {$record->currency} from {$record->student_name}");
                }
                break;
        }
    }
}