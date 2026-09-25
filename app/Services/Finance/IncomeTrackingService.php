<?php

namespace App\Services\Finance;

use App\Models\ClassRoom;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;

class IncomeTrackingService
{
 /**
 * Income summary by level (Primary, Secondary, A-Level, etc.)
 */
 public static function byLevel(?Carbon $from = null, ?Carbon $to = null): array
 {
 $from = $from ?? now()->startOfYear();
 $to = $to ?? now()->endOfYear();

 $levels = ["nursery","kg","pre_unit","primary","secondary","alevel"];
 $result = [];

 foreach ($levels as $level) {
 $studentIds = Student::withoutGlobalScopes()
 ->where("level", $level)
 ->pluck("id");

 if ($studentIds->isEmpty()) {
 $result[$level] = [
 "students" => 0,
 "invoiced" => 0,
 "collected" => 0,
 "outstanding"=> 0,
 "collection_rate" => 0,
 ];
 continue;
 }

 $invoiced = Invoice::whereIn("student_id", $studentIds)
 ->whereBetween("created_at", [$from, $to])
 ->sum("amount");

 $collected = Payment::whereIn("student_id", $studentIds)
 ->whereBetween("payment_date", [$from, $to])
 ->sum("amount");

 $outstanding = Invoice::whereIn("student_id", $studentIds)
 ->whereBetween("created_at", [$from, $to])
 ->sum("balance");

 $studentCount = $studentIds->count();
 $rate = $invoiced > 0 ? round(($collected / $invoiced) * 100, 1) : 0;

 $result[$level] = [
 "students" => $studentCount,
 "invoiced" => (float) $invoiced,
 "collected" => (float) $collected,
 "outstanding" => (float) $outstanding,
 "collection_rate" => $rate,
 ];
 }

 return $result;
 }

 /**
 * Income by class.
 */
 public static function byClass(?Carbon $from = null, ?Carbon $to = null): array
 {
 $from = $from ?? now()->startOfYear();
 $to = $to ?? now()->endOfYear();

 $classrooms = ClassRoom::with("classTeacher")->orderBy("name")->get();
 $result = [];

 foreach ($classrooms as $class) {
 $studentIds = Student::withoutGlobalScopes()
 ->where("classroom_id", $class->id)
 ->pluck("id");

 if ($studentIds->isEmpty()) {
 $result[] = [
 "class" => $class,
 "students" => 0,
 "invoiced" => 0,
 "collected" => 0,
 "outstanding" => 0,
 "rate" => 0,
 ];
 continue;
 }

 $invoiced = Invoice::whereIn("student_id", $studentIds)
 ->whereBetween("created_at", [$from, $to])->sum("amount");

 $collected = Payment::whereIn("student_id", $studentIds)
 ->whereBetween("payment_date", [$from, $to])->sum("amount");

 $outstanding = Invoice::whereIn("student_id", $studentIds)
 ->whereBetween("created_at", [$from, $to])->sum("balance");

 $result[] = [
 "class" => $class,
 "students" => $studentIds->count(),
 "invoiced" => (float) $invoiced,
 "collected" => (float) $collected,
 "outstanding" => (float) $outstanding,
 "rate" => $invoiced > 0 ? round(($collected / $invoiced) * 100, 1) : 0,
 ];
 }

 return $result;
 }

 /**
 * Income by term.
 */
 public static function byTerm(?int $year = null): array
 {
 $year = $year ?? now()->year;

 $terms = Invoice::whereYear("created_at", $year)
 ->distinct("term")->pluck("term");

 $result = [];
 foreach ($terms as $term) {
 $invoiced = Invoice::where("term", $term)->whereYear("created_at", $year)->sum("amount");
 $collected = Payment::whereHas("invoice", function ($q) use ($term, $year) {
 $q->where("term", $term)->whereYear("created_at", $year);
 })->sum("amount");
 $outstanding = Invoice::where("term", $term)->whereYear("created_at", $year)->sum("balance");

 $result[$term] = [
 "invoiced" => (float) $invoiced,
 "collected" => (float) $collected,
 "outstanding" => (float) $outstanding,
 "rate" => $invoiced > 0 ? round(($collected / $invoiced) * 100, 1) : 0,
 ];
 }
 return $result;
 }

 /**
 * Income per student (top payers, top defaulters).
 */
 public static function perStudent(?Carbon $from = null, ?Carbon $to = null, ?string $level = null): array
 {
 $from = $from ?? now()->startOfYear();
 $to = $to ?? now()->endOfYear();

 $students = Student::withoutGlobalScopes()
 ->with("classroom")
 ->where("status", "active")
 ->when($level, fn($q) => $q->where("level", $level))
 ->get();

 $rows = [];
 foreach ($students as $s) {
 $invoiced = Invoice::where("student_id", $s->id)->whereBetween("created_at", [$from, $to])->sum("amount");
 $collected = Payment::where("student_id", $s->id)->whereBetween("payment_date", [$from, $to])->sum("amount");
 $outstanding = Invoice::where("student_id", $s->id)->whereBetween("created_at", [$from, $to])->sum("balance");

 $rows[] = [
 "student" => $s,
 "invoiced" => (float) $invoiced,
 "collected" => (float) $collected,
 "outstanding" => (float) $outstanding,
 "rate" => $invoiced > 0 ? round(($collected / $invoiced) * 100, 1) : 0,
 ];
 }

 return $rows;
 }

 /**
 * Daily income for chart.
 */
 public static function dailyIncome(?Carbon $from = null, ?Carbon $to = null): array
 {
 $from = $from ?? now()->startOfMonth();
 $to = $to ?? now()->endOfMonth();

 return Payment::whereBetween("payment_date", [$from, $to])
 ->selectRaw("DATE(payment_date) as day, SUM(amount) as total, COUNT(*) as count")
 ->groupBy("day")
 ->orderBy("day")
 ->get()
 ->map(fn($row) => [
 "day" => $row->day,
 "total" => (float) $row->total,
 "count" => (int) $row->count,
 ])
 ->toArray();
 }

 /**
 * Overall KPI snapshot.
 */
 public static function summary(?Carbon $from = null, ?Carbon $to = null): array
 {
 $from = $from ?? now()->startOfYear();
 $to = $to ?? now()->endOfYear();

 $invoiced = Invoice::whereBetween("created_at", [$from, $to])->sum("amount");
 $collected = Payment::whereBetween("payment_date", [$from, $to])->sum("amount");
 $outstanding = Invoice::whereBetween("created_at", [$from, $to])->sum("balance");
 $defaulters = Invoice::whereBetween("created_at", [$from, $to])->where("balance", ">", 0)->count();
 $totalInv = Invoice::whereBetween("created_at", [$from, $to])->count();

 return [
 "invoiced" => (float) $invoiced,
 "collected" => (float) $collected,
 "outstanding" => (float) $outstanding,
 "rate" => $invoiced > 0 ? round(($collected / $invoiced) * 100, 1) : 0,
 "defaulters" => $defaulters,
 "total_invoices" => $totalInv,
 "avg_invoice" => $totalInv > 0 ? round($invoiced / $totalInv, 2) : 0,
 ];
 }
}