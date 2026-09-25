<?php

namespace Database\Seeders;

use App\Models\SmsTriggerSetting;
use Illuminate\Database\Seeder;

class SmsTriggerSeeder extends Seeder
{
 public function run(): void
 {
 $triggers = [
 /* ============ ATTENDANCE ============ */
 ["absence", "Attendance", "Student absent", true, false],
 ["absence_repeat", "Attendance", "Student absent 3+ days in a row", true, false],
 ["staff_absence", "Attendance", "Staff member absent", true, false],

 /* ============ ACADEMIC ============ */
 ["result", "Academic", "Exam results published", true, false],
 ["promotion", "Academic", "Student promoted to next class", true, false],
 ["graduation", "Academic", "Student graduated", true, false],
 ["exam_schedule_change","Academic", "Exam date/time changed", true, false],
 ["timetable_change", "Academic", "Timetable updated", true, false],

 /* ============ ADMISSION ============ */
 ["welcome", "Admission", "New student admitted", true, false],
 ["student_moved_class","Admission", "Student moved to another class", true, false],
 ["student_transferred_out","Admission", "Student transferred out", true, false],

 /* ============ FINANCE ============ */
 ["payment", "Finance", "Payment received", true, false],
 ["fee_reminder", "Finance", "Fee reminder (before/on/after due)", true, false],
 ["fee_overdue", "Finance", "Fee overdue final notice", true, false],
 ["invoice_created", "Finance", "New invoice issued", true, false],
 ["salary_processed", "Finance", "Salary processed", true, false],

 /* ============ EMERGENCY ============ */
 ["emergency", "Emergency", "Emergency incident", true, true], // CRITICAL
 ["health_incident", "Emergency", "Health incident reported", true, false],

 /* ============ DISCIPLINE ============ */
 ["discipline_warning","Discipline", "Discipline warning", true, false],
 ["discipline_praise", "Discipline", "Good behavior praise", true, false],
 ["suspension", "Discipline", "Student suspended", true, false],

 /* ============ STAFF ============ */
 ["department_change", "Staff", "Staff department changed", true, false],
 ["staff_transfer", "Staff", "Staff transferred", true, false],
 ["role_change", "Staff", "Staff role changed", true, false],
 ["class_teacher_change","Staff", "Class teacher assigned or changed", true, false],
 ["leave_approved", "Staff", "Leave request approved", true, false],
 ["leave_rejected", "Staff", "Leave request rejected", true, false],

 /* ============ DUTY ============ */
 ["duty_reminder", "Duty", "Duty reminder (1 day before)", true, false],
 ["duty_assigned", "Duty", "New duty assignment", true, false],
 ["duty_missed", "Duty", "Duty missed - notified to head", true, false],

 /* ============ SCHOOL-WIDE ============ */
 ["announcement", "School", "General announcement", true, false],
 ["parent_meeting", "School", "Parent meeting", true, false],
 ["school_closure", "School", "School closed (emergency)", true, true], // CRITICAL

 /* ============ LIBRARY ============ */
 ["library_overdue", "Library", "Library book overdue", true, false],

 /* ============ SECURITY ============ */
 ["login_alert", "Security", "Login from new device", true, false],
 ["password_reset", "Security", "Password reset", true, true], // CRITICAL

 /* ============ PERFORMANCE ============ */
 ["performance_review","Performance","Performance review completed", true, false],
 ];

 foreach ($triggers as $t) {
 SmsTriggerSetting::updateOrCreate(
 ["trigger_key" => $t[0]],
 [
 "label" => $t[1] . " — " . $t[2],
 "category" => $t[1],
 "description" => $t[2],
 "is_enabled" => $t[3],
 "is_critical" => $t[4],
 ]
 );
 }

 $this->command->info("Seeded " . count($triggers) . " SMS triggers.");
 }
}