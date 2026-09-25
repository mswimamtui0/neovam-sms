<?php

namespace App\Services\Reports;

use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Incident;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Result;
use App\Models\SmsLog;
use App\Models\Staff;
use App\Models\Student;
use Carbon\Carbon;

class AnalyticsService
{
 /**
 * Master overview stats — used on main dashboard.
 */
 public static function overview(?Carbon $from = null, ?Carbon $to = null): array
 {
 $from = $from ?? now()->startOfMonth();
 $to = $to ?? now()->endOfMonth();

 return [
 "students" => Student::withoutGlobalScopes()->where("status","active")->count(),
 "students_new" => Student::withoutGlobalScopes()->whereBetween("created_at",[$from,$to])->count(),
 "staff" => Staff::where("status","active")->count(),
 "classes" => ClassRoom::count(),
 "exams" => Exam::count(),
 "exams_published"=> Exam::where("published", true)->count(),
 "results" => Result::count(),
 "incidents" => Incident::whereBetween("created_at",[$from,$to])->count(),
 "sms_sent" => SmsLog::whereBetween("created_at",[$from,$to])->count(),
 "sms_units" => SmsLog::whereBetween("created_at",[$from,$to])->sum("units"),
 "sms_cost" => SmsLog::whereBetween("created_at",[$from,$to])->sum("cost"),
 "invoices" => Invoice::whereBetween("created_at",[$from,$to])->count(),
 "invoiced_total" => (float) Invoice::whereBetween("created_at",[$from,$to])->sum("amount"),
 "collected" => (float) Payment::whereBetween("payment_date",[$from,$to])->sum("amount"),
 "outstanding" => (float) Invoice::whereBetween("created_at",[$from,$to])->sum("balance"),
 ];
 }

 /**
 * Academic analytics.
 */
 public static function academic(): array
 {
 $levels = ["nursery","kg","pre_unit","primary","secondary","alevel"];
 $byLevel = [];

 foreach ($levels as $level) {
 $students = Student::withoutGlobalScopes()->where("level", $level)->where("status","active")->count();
 $avg = Result::whereHas("student", function ($q) use ($level) {
 $q->where("level", $level);
 })->avg("marks");

 $byLevel[$level] = [
 "students" => $students,
 "avg_marks" => $avg ? round($avg, 1) : 0,
 ];
 }

 // Grade distribution
 $grades = Result::selectRaw("grade, COUNT(*) as count")
 ->whereNotNull("grade")
 ->groupBy("grade")
 ->pluck("count", "grade")->toArray();

 // Subject averages
 $subjects = Result::selectRaw("subject, AVG(marks) as avg_marks, COUNT(*) as count")
 ->groupBy("subject")
 ->orderByDesc("avg_marks")
 ->limit(15)
 ->get()
 ->map(fn($r) => [
 "subject" => $r->subject,
 "avg_marks" => round((float) $r->avg_marks, 1),
 "count" => (int) $r->count,
 ])->toArray();

 // Top 10 students overall
 $topStudents = Result::selectRaw("student_id, AVG(marks) as avg, COUNT(*) as subjects")
 ->groupBy("student_id")
 ->orderByDesc("avg")
 ->limit(10)
 ->with("student")
 ->get()
 ->map(fn($r) => [
 "student" => $r->student,
 "avg" => round((float) $r->avg, 1),
 "subjects" => (int) $r->subjects,
 ])->toArray();

 return [
 "by_level" => $byLevel,
 "grades" => $grades,
 "subjects" => $subjects,
 "top_students" => $topStudents,
 ];
 }

 /**
 * Attendance analytics.
 */
 public static function attendance(?Carbon $from = null, ?Carbon $to = null): array
 {
 $from = $from ?? now()->startOfMonth();
 $to = $to ?? now()->endOfMonth();

 $query = Attendance::whereBetween("date", [$from, $to]);

 $total = (clone $query)->count();
 $present = (clone $query)->where("status","present")->count();
 $absent = (clone $query)->where("status","absent")->count();
 $late = (clone $query)->where("status","late")->count();

 // Per class breakdown
 $byClass = ClassRoom::orderBy("name")->get()->map(function ($c) use ($from, $to) {
 $records = Attendance::where("classroom_id", $c->id)
 ->whereBetween("date", [$from, $to])->get();

 $total = $records->count();
 $present = $records->where("status","present")->count();

 return [
 "class" => $c,
 "total" => $total,
 "present" => $present,
 "absent" => $records->where("status","absent")->count(),
 "late" => $records->where("status","late")->count(),
 "rate" => $total > 0 ? round(($present / $total) * 100, 1) : 0,
 ];
 })->toArray();

 // Daily attendance rate
 $byDay = Attendance::whereBetween("date", [$from, $to])
 ->selectRaw("date, COUNT(*) as total, SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
 ->groupBy("date")
 ->orderBy("date")
 ->get()
 ->map(fn($r) => [
 "date" => $r->date,
 "total" => (int) $r->total,
 "present" => (int) $r->present,
 "rate" => $r->total > 0 ? round(($r->present / $r->total) * 100, 1) : 0,
 ])->toArray();

 return [
 "total" => $total,
 "present" => $present,
 "absent" => $absent,
 "late" => $late,
 "rate" => $total > 0 ? round(($present / $total) * 100, 1) : 0,
 "by_class" => $byClass,
 "by_day" => $byDay,
 ];
 }

 /**
 * Financial analytics.
 */
 public static function financial(?Carbon $from = null, ?Carbon $to = null): array
 {
 $from = $from ?? now()->startOfYear();
 $to = $to ?? now()->endOfYear();

 $invoiced = Invoice::whereBetween("created_at", [$from, $to])->sum("amount");
 $collected = Payment::whereBetween("payment_date", [$from, $to])->sum("amount");

 // Monthly income
 $monthly = Payment::whereBetween("payment_date", [$from, $to])
 ->selectRaw("strftime('%Y-%m', payment_date) as month, SUM(amount) as total, COUNT(*) as count")
 ->groupBy("month")
 ->orderBy("month")
 ->get()
 ->map(fn($r) => [
 "month" => $r->month,
 "total" => (float) $r->total,
 "count" => (int) $r->count,
 ])->toArray();

 // Payment methods
 $methods = Payment::whereBetween("payment_date", [$from, $to])
 ->selectRaw("method, COUNT(*) as count, SUM(amount) as total")
 ->groupBy("method")
 ->get()
 ->map(fn($r) => [
 "method" => $r->method,
 "count" => (int) $r->count,
 "total" => (float) $r->total,
 ])->toArray();

 return [
 "invoiced" => (float) $invoiced,
 "collected" => (float) $collected,
 "rate" => $invoiced > 0 ? round(($collected / $invoiced) * 100, 1) : 0,
 "monthly" => $monthly,
 "methods" => $methods,
 ];
 }

 /**
 * SMS analytics.
 */
 public static function sms(?Carbon $from = null, ?Carbon $to = null): array
 {
 $from = $from ?? now()->startOfMonth();
 $to = $to ?? now()->endOfMonth();

 $query = SmsLog::whereBetween("created_at", [$from, $to]);

 $byTrigger = (clone $query)
 ->selectRaw("trigger, COUNT(*) as count, SUM(units) as units")
 ->groupBy("trigger")
 ->orderByDesc("count")
 ->get()
 ->map(fn($r) => [
 "trigger" => $r->trigger,
 "count" => (int) $r->count,
 "units" => (int) $r->units,
 ])->toArray();

 $byStatus = (clone $query)
 ->selectRaw("status, COUNT(*) as count")
 ->groupBy("status")
 ->pluck("count", "status")->toArray();

 $byDelivery = (clone $query)
 ->selectRaw("delivery_status, COUNT(*) as count")
 ->groupBy("delivery_status")
 ->pluck("count", "delivery_status")->toArray();

 $daily = (clone $query)
 ->selectRaw("DATE(created_at) as day, COUNT(*) as count, SUM(units) as units")
 ->groupBy("day")
 ->orderBy("day")
 ->get()
 ->map(fn($r) => [
 "day" => $r->day,
 "count" => (int) $r->count,
 "units" => (int) $r->units,
 ])->toArray();

 return [
 "total" => (clone $query)->count(),
 "sent" => (clone $query)->where("status","sent")->count(),
 "failed" => (clone $query)->where("status","failed")->count(),
 "delivered" => (clone $query)->where("delivery_status","delivered")->count(),
 "units" => (int) (clone $query)->sum("units"),
 "cost" => (float) (clone $query)->sum("cost"),
 "by_trigger" => $byTrigger,
 "by_status" => $byStatus,
 "by_delivery" => $byDelivery,
 "daily" => $daily,
 ];
 }

 /**
 * Staff analytics.
 */
 public static function staff(): array
 {
 $byDepartment = Staff::selectRaw("department, COUNT(*) as count")
 ->where("status","active")
 ->groupBy("department")
 ->orderByDesc("count")
 ->get()
 ->map(fn($r) => [
 "department" => $r->department,
 "count" => (int) $r->count,
 ])->toArray();

 $byType = Staff::selectRaw("staff_type, COUNT(*) as count")
 ->where("status","active")
 ->whereNotNull("staff_type")
 ->groupBy("staff_type")
 ->orderByDesc("count")
 ->get()
 ->map(fn($r) => [
 "staff_type" => $r->staff_type,
 "count" => (int) $r->count,
 ])->toArray();

 return [
 "total" => Staff::where("status","active")->count(),
 "teachers" => Staff::whereIn("staff_type",["Teacher","Head of Department","Academic Master"])->where("status","active")->count(),
 "support" => Staff::whereIn("staff_type",["Secretary","Librarian","Nurse","Cleaner","Security Guard","Cook"])->where("status","active")->count(),
 "by_department" => $byDepartment,
 "by_type" => $byType,
 ];
 }
}