<?php

namespace App\Services;

use App\Models\ClassRoom;
use App\Models\Staff;
use App\Models\Student;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\Log;

class DepartmentSmsService
{
    public function __construct(protected SmsService $sms) {}

    /* ============ HELPERS ============ */

    protected function send(string $phone, string $message, string $trigger): void
    {
        if (!$phone) return;

        try {
            $this->sms->send($phone, $message, $trigger);
        } catch (\Throwable $e) {
            Log::error("Dept SMS failed", [
                "phone"   => $phone,
                "trigger" => $trigger,
                "error"   => $e->getMessage(),
            ]);
        }
    }

    protected function parentPhone($studentName): ?string
    {
        if (!$studentName) return null;

        $student = Student::where("first_name", "LIKE", "%{$studentName}%")
            ->orWhere("last_name", "LIKE", "%{$studentName}%")
            ->orWhereRaw("first_name || ' ' || last_name LIKE ?", ["%{$studentName}%"])
            ->first();

        return $student?->parent_phone;
    }

    protected function classTeacherPhone(string $className): ?string
    {
        if (!$className) return null;

        $room = ClassRoom::where("name", $className)->first();
        if (!$room || !$room->class_teacher_id) return null;

        return Staff::find($room->class_teacher_id)?->phone;
    }

    protected function headPhone(): ?string
    {
        $head = Staff::where("staff_type", "Head of School")->first();
        return $head?->phone;
    }

    /* ============ HEALTH ============ */

    public function healthLogged($record): void
    {
        $msg = "Dear Parent, {$record->student_name} was seen at the school health office. Type: {$record->type}. Status: {$record->status}. Contact school for details.";
        $this->send($this->parentPhone($record->student_name), $msg, "health_logged");

        if (($record->severity ?? "") === "critical") {
            $this->send($this->headPhone(),
                "URGENT: {$record->student_name} — critical health emergency. Please respond immediately.",
                "health_emergency");
        }

        $ctPhone = $this->classTeacherPhone($record->class_name);
        if ($ctPhone) {
            $this->send($ctPhone,
                "{$record->student_name} ({$record->class_name}) was seen at Health: {$record->symptoms}",
                "health_class_teacher_alert");
        }
    }

    /* ============ DISCIPLINE ============ */

    public function disciplineLogged($record): void
    {
        $msg = "Dear Parent, a {$record->category} discipline case was logged for {$record->student_name}: {$record->offence}. Action: {$record->action_taken}.";
        $this->send($this->parentPhone($record->student_name), $msg, "discipline_logged");

        if (($record->category ?? "") === "serious") {
            $this->send($this->headPhone(),
                "Serious discipline case: {$record->student_name} — {$record->offence}",
                "discipline_serious");
        }

        $ctPhone = $this->classTeacherPhone($record->class_name);
        if ($ctPhone) {
            $this->send($ctPhone,
                "Discipline case logged for {$record->student_name}: {$record->offence}",
                "discipline_class_teacher_alert");
        }
    }

    /* ============ SPORTS ============ */

    public function sportsLogged($record): void
    {
        if (($record->event_type ?? "") === "injury") {
            $this->send($this->headPhone(),
                "Sports injury: {$record->sport_name} — {$record->notes}",
                "sports_injury");
        }
    }

    /* ============ DUTY ============ */

    public function dutyLogged($record): void
    {
        $staff = Staff::find($record->staff_id);
        if ($staff?->phone) {
            $this->send($staff->phone,
                "Duty logged for {$record->duty_date} ({$record->shift} shift). Status: {$record->status}.",
                "duty_logged");
        }
    }

    /* ============ BOARDING ============ */

    public function boardingLogged($record): void
    {
        if (($record->type ?? "") === "incident") {
            $this->send($this->headPhone(),
                "Boarding incident in {$record->dorm_name}: {$record->description}",
                "boarding_incident");
        }
    }

    /* ============ FEEDING ============ */

    public function feedingLogged($record): void
    {
        if (!empty($record->allergy_notes)) {
            $this->send($this->headPhone(),
                "Feeding allergy note ({$record->meal_date}): {$record->allergy_notes}",
                "feeding_allergy");
        }
    }

    /* ============ LIBRARY ============ */

    public function libraryLogged($record): void
    {
        if (($record->status ?? "") === "overdue") {
            $msg = "Dear Parent, the library book '{$record->book_title}' borrowed by {$record->student_name} is overdue. Please return it.";
            $this->send($this->parentPhone($record->student_name), $msg, "library_overdue");
        }
    }

    /* ============ GUIDANCE (private) ============ */

    public function guidanceLogged($record): void
    {
        // Privacy: only Class Teacher gets notified (no parent, no SMS body detail)
        $ctPhone = $this->classTeacherPhone("");
        // Class teacher may not be known from a name; skip if unknown
        if ($ctPhone) {
            $this->send($ctPhone,
                "A guidance session for {$record->student_name} was logged.",
                "guidance_notice");
        }
    }

    /* ============ ENVIRONMENT ============ */

    public function environmentLogged($record): void
    {
        if (($record->rating ?? 5) <= 2 && ($record->type ?? "") === "inspection") {
            $this->send($this->headPhone(),
                "Low cleanliness rating ({$record->rating}/5) in {$record->area}",
                "environment_low_rating");
        }
    }

    /* ============ SECURITY ============ */

    public function securityLogged($record): void
    {
        if (($record->type ?? "") === "incident") {
            $this->send($this->headPhone(),
                "Security incident: {$record->description}",
                "security_incident");
        }
    }

    /* ============ FINANCE ============ */

    public function financeLogged($record): void
    {
        if (($record->type ?? "") === "payment" && ($record->status ?? "") === "paid") {
            $msg = "Dear Parent, we received your payment of {$record->amount} {$record->currency} for {$record->student_name}. Thank you.";
            $this->send($this->parentPhone($record->student_name), $msg, "finance_payment");
        }

        if (($record->type ?? "") === "invoice") {
            $msg = "Dear Parent, an invoice of {$record->amount} {$record->currency} has been issued for {$record->student_name}. Due soon.";
            $this->send($this->parentPhone($record->student_name), $msg, "finance_invoice");
        }
    }

    /* ============ ADMINISTRATION ============ */

    public function administrationLogged($record): void
    {
        if (($record->status ?? "") === "published" && ($record->audience ?? "") === "staff") {
            foreach (Staff::whereNotNull("phone")->get() as $s) {
                $this->send($s->phone,
                    "New circular: {$record->title}",
                    "admin_circular");
            }
        }
    }
}