<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\Student;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\Log;

class EmergencySmsService
{
    public function __construct(protected SmsService $sms) {}

    /**
     * Send the 3 fixed SMS: Headmaster + Parent + Health Teacher.
     * Each gets a message tailored to their role.
     */
    public function dispatchAll($emergency): array
    {
        $result = [
            "head"           => false,
            "parent"         => false,
            "health_teacher" => false,
        ];

        // 1. Headmaster
        $headPhone = $this->headPhone();
        if ($headPhone) {
            $msg = $this->headMessage($emergency);
            $result["head"] = $this->send($headPhone, $msg, "emergency_headmaster");
            if ($result["head"]) {
                $emergency->head_notified    = true;
                $emergency->head_notified_at = now();
            }
        }

        // 2. Parent
        $parentPhone = $this->parentPhone($emergency->student_name);
        if ($parentPhone) {
            $msg = $this->parentMessage($emergency);
            $result["parent"] = $this->send($parentPhone, $msg, "emergency_parent");
            if ($result["parent"]) {
                $emergency->parent_notified    = true;
                $emergency->parent_notified_at = now();
            }
        }

        // 3. Health Teacher (first staff member in Health dept, excluding the reporter)
        $healthPhone = $this->healthTeacherPhone($emergency);
        if ($healthPhone) {
            $msg = $this->healthMessage($emergency);
            $result["health_teacher"] = $this->send($healthPhone, $msg, "emergency_health_teacher");
            if ($result["health_teacher"]) {
                $emergency->health_teacher_notified    = true;
                $emergency->health_teacher_notified_at = now();
            }
        }

        $emergency->save();

        return $result;
    }

    /* ============ MESSAGE BUILDERS ============ */

    protected function headMessage($e): string
    {
        $label = $this->severityLabel($e->severity);
        return "EMERGENCY ALERT - NEOVAM\n" .
               "Student: {$e->student_name} ({$e->class_name})\n" .
               "Time: " . now()->format("Y-m-d H:i") . "\n" .
               "Severity: {$label}\n" .
               "Issue: " . ($e->description ?? $e->type) . "\n" .
               "Action: " . ($e->action_taken ?? "Being attended") . "\n" .
               "Reported by: {$e->recorded_by}";
    }

    protected function parentMessage($e): string
    {
        $isUrgent = in_array($e->severity, ["critical","urgent"], true);
        $prefix   = $isUrgent ? "URGENT - " : "";

        return "{$prefix}Dear Parent, your child {$e->student_name} ({$e->class_name}) " .
               ($isUrgent ? "has had an emergency at school today. " : "was seen at the school health office today. ") .
               "Issue: " . ($e->description ?? $e->type) . ". " .
               "Action: " . ($e->action_taken ?? "Being attended") . ". " .
               "Please call the school: " . $this->schoolPhone();
    }

    protected function healthMessage($e): string
    {
        return "EMERGENCY - NEOVAM\n" .
               "Student: {$e->student_name} ({$e->class_name})\n" .
               "Issue: " . ($e->description ?? $e->type) . "\n" .
               "Location: " . ($e->location ?? "Sick bay") . "\n" .
               "Reported by: {$e->recorded_by}\n" .
               "Please assist or follow up.";
    }

    /* ============ RESOLUTION MESSAGES ============ */

    public function resolveSms($emergency): void
    {
        // Notify parent on resolution
        $phone = $this->parentPhone($emergency->student_name);
        if ($phone) {
            $msg = "Dear Parent, your child {$emergency->student_name} is stable. " .
                   ($emergency->follow_up ?? "Recovered and returned to class.") . " Thank you.";
            $this->send($phone, $msg, "emergency_resolved_parent");
        }

        // Notify head on resolution
        $headPhone = $this->headPhone();
        if ($headPhone) {
            $msg = "Emergency resolved: {$emergency->student_name} ({$emergency->class_name}) " .
                   "at " . now()->format("H:i") . ". Follow-up: " . ($emergency->follow_up ?? "Recovered.");
            $this->send($headPhone, $msg, "emergency_resolved_head");
        }

        // Notify health teacher
        $healthPhone = $this->healthTeacherPhone($emergency);
        if ($healthPhone) {
            $msg = "Emergency case closed: {$emergency->student_name}. Follow-up: " .
                   ($emergency->follow_up ?? "Recovered.");
            $this->send($healthPhone, $msg, "emergency_resolved_health");
        }
    }

    /* ============ HELPERS ============ */

    protected function send(string $phone, string $msg, string $trigger): bool
    {
        if (!$phone) return false;
        try {
            $this->sms->send($phone, $msg, $trigger);
            return true;
        } catch (\Throwable $e) {
            Log::error("Emergency SMS failed", [
                "phone"   => $phone,
                "trigger" => $trigger,
                "error"   => $e->getMessage(),
            ]);
            return false;
        }
    }

    protected function headPhone(): ?string
    {
        $head = Staff::whereIn("staff_type", ["Head of School","Headmaster","Head Teacher"])
            ->whereNotNull("phone")->first();
        return $head?->phone;
    }

    protected function parentPhone(?string $studentName): ?string
    {
        if (!$studentName) return null;

        $student = Student::whereRaw("first_name || ' ' || last_name LIKE ?", ["%{$studentName}%"])
            ->orWhere("first_name", "LIKE", "%{$studentName}%")
            ->orWhere("last_name", "LIKE", "%{$studentName}%")
            ->first();

        if ($student && $student->parent_phone) return $student->parent_phone;

        return null;
    }

    protected function healthTeacherPhone($emergency): ?string
    {
        // Pick a staff in Health dept who is not the reporter
        $query = Staff::where(function ($q) {
                $q->where("department", "LIKE", "%Health%")
                  ->orWhereHas("primaryDepartment", function ($p) {
                      $p->where("code", "health");
                  });
            })
            ->whereNotNull("phone");

        if ($emergency->staff_id) {
            $query->where("id", "!=", $emergency->staff_id);
        }

        return $query->first()?->phone;
    }

    protected function schoolPhone(): string
    {
        return \App\Models\School::first()?->phone ?? "(school office)";
    }

    protected function severityLabel(string $s): string
    {
        return match ($s) {
            "critical" => "CRITICAL",
            "urgent"   => "URGENT",
            "normal"   => "NORMAL",
            "info"     => "INFO",
            default    => strtoupper($s),
        };
    }
}