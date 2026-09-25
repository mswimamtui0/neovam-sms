<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Result;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Http\Request;

class ParentController extends Controller
{
 /* ============ DASHBOARD ============ */
 public function dashboard()
 {
 $children = Student::with("classroom")->where("status","active")->get();

 $announcements = Announcement::where("publish_date","<=",now()->toDateString())
 ->where(function ($q) {
 $q->whereNull("expiry_date")->orWhere("expiry_date",">=",now()->toDateString());
 })
 ->whereIn("audience", ["all","parents"])
 ->orderByDesc("is_pinned")
 ->orderByDesc("publish_date")
 ->take(5)->get();

 // Aggregate stats
 $totalFees = Invoice::whereIn("student_id", $children->pluck("id"))->sum("amount");
 $totalPaid = Invoice::whereIn("student_id", $children->pluck("id"))->sum("amount_paid");
 $totalBalance = Invoice::whereIn("student_id", $children->pluck("id"))->sum("balance");
 $totalPromotions = Promotion::whereIn("student_id", $children->pluck("id"))->count();

 return view("parent.dashboard", compact(
 "children","announcements",
 "totalFees","totalPaid","totalBalance","totalPromotions"
 ));
 }

 /* ============ CHILD DETAILS ============ */
 public function child(Student $student)
 {
 // ParentStudentScope ensures parent only sees own children
 $student->load(["classroom","attendances","results.exam","incidents"]);

 $promotions = Promotion::where("student_id", $student->id)
 ->with(["fromClassroom","toClassroom"])
 ->orderBy("promoted_at","desc")
 ->get();

 $invoices = Invoice::where("student_id", $student->id)->with("payments")->latest()->get();
 $payments = Payment::where("student_id", $student->id)->latest()->take(10)->get();

 $timetable = Timetable::with(["classroom","subject"])
 ->where("classroom_id", $student->classroom_id)
 ->orderBy("start_time")->get()
 ->groupBy("day_of_week");

 return view("parent.child", compact(
 "student","promotions","invoices","payments","timetable"
 ));
 }

 /* ============ RESULTS ============ */
 public function results()
 {
 $children = Student::with(["results.exam"])->get();
 return view("parent.results", compact("children"));
 }

 /* ============ ATTENDANCE ============ */
 public function attendance()
 {
 $children = Student::with(["attendances"])->get();

 $summary = [];
 foreach ($children as $child) {
 $total = $child->attendances->count();
 $present = $child->attendances->where("status","present")->count();
 $absent = $child->attendances->where("status","absent")->count();
 $late = $child->attendances->where("status","late")->count();
 $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;

 $summary[$child->id] = compact("total","present","absent","late","rate");
 }

 return view("parent.attendance", compact("children","summary"));
 }

 /* ============ PROMOTIONS ============ */
 public function promotions()
 {
 $children = Student::pluck("id")->toArray();

 $promotions = Promotion::with(["student","fromClassroom","toClassroom"])
 ->whereIn("student_id", $children)
 ->orderBy("promoted_at","desc")
 ->paginate(20);

 return view("parent.promotions", compact("promotions"));
 }

 /* ============ FEES ============ */
 public function fees()
 {
 $children = Student::pluck("id")->toArray();

 $invoices = Invoice::with("student")
 ->whereIn("student_id", $children)
 ->latest()
 ->paginate(20);

 $totals = [
 "billed" => Invoice::whereIn("student_id", $children)->sum("amount"),
 "paid" => Invoice::whereIn("student_id", $children)->sum("amount_paid"),
 "balance" => Invoice::whereIn("student_id", $children)->sum("balance"),
 ];

 return view("parent.fees", compact("invoices","totals"));
 }

 /* ============ PAYMENTS ============ */
 public function payments()
 {
 $children = Student::pluck("id")->toArray();

 $payments = Payment::with("student")
 ->whereIn("student_id", $children)
 ->latest()
 ->paginate(20);

 return view("parent.payments", compact("payments"));
 }

 /* ============ TIMETABLE ============ */
 public function timetable()
 {
 $children = Student::with("classroom")->get();

 $timetables = [];
 foreach ($children as $child) {
 $timetables[$child->id] = Timetable::with(["subject","staff"])
 ->where("classroom_id", $child->classroom_id)
 ->orderByRaw("CASE day_of_week
 WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3
 WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6
 WHEN 'Sunday' THEN 7 END")
 ->orderBy("start_time")
 ->get()
 ->groupBy("day_of_week");
 }

 return view("parent.timetable", compact("children","timetables"));
 }

 /* ============ INCIDENTS ============ */
 public function incidents()
 {
 $children = Student::pluck("id")->toArray();
 $incidents = \App\Models\Incident::with("student")->whereIn("student_id", $children)->latest()->paginate(20);
 return view("parent.incidents", compact("incidents"));
 }

 /* ============ ANNOUNCEMENTS ============ */
 public function announcements()
 {
 $announcements = Announcement::where("publish_date","<=",now()->toDateString())
 ->where(function ($q) {
 $q->whereNull("expiry_date")->orWhere("expiry_date",">=",now()->toDateString());
 })
 ->whereIn("audience", ["all","parents"])
 ->orderByDesc("is_pinned")
 ->orderByDesc("publish_date")
 ->paginate(15);

 return view("parent.announcements", compact("announcements"));
 }
}