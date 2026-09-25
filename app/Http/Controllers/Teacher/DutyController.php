<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\DutyReport;
use App\Models\DutyRoster;
use App\Models\HandoverNote;
use App\Models\Incident;
use App\Models\Staff;
use App\Models\SupervisionLog;
use Illuminate\Http\Request;

class DutyController extends Controller
{
 protected function staff(): ?Staff
 {
 return Staff::where("user_id", auth()->id())->first();
 }

 /* ============ DASHBOARD ============ */
 public function dashboard()
 {
 $staff = $this->staff();
 if (!$staff) {
 return view("duty.no-profile");
 }

 $today = now()->toDateString();

 $todayDuty = DutyRoster::where("staff_id", $staff->id)
 ->whereDate("duty_date", $today)
 ->first();

 $upcomingDuties = DutyRoster::where("staff_id", $staff->id)
 ->whereDate("duty_date", ">=", $today)
 ->orderBy("duty_date")
 ->take(7)
 ->get();

 $supervisionToday = SupervisionLog::where("staff_id", $staff->id)
 ->whereDate("log_date", $today)
 ->get();

 $supervisionAreas = ["assembly","break","lunch","evening","night","gate","exam"];
 $doneAreas = $supervisionToday->pluck("area")->toArray();

 $incidentsToday = Incident::whereDate("created_at", $today)->count();

 $pendingReports = DutyReport::where("staff_id", $staff->id)
 ->where("status", "draft")
 ->count();

 return view("duty.dashboard", compact(
 "staff","todayDuty","upcomingDuties",
 "supervisionToday","supervisionAreas","doneAreas",
 "incidentsToday","pendingReports"
 ));
 }

 /* ============ SCHEDULE ============ */
 public function schedule()
 {
 $staff = $this->staff();
 $duties = $staff
 ? DutyRoster::where("staff_id", $staff->id)
 ->orderBy("duty_date","desc")
 ->paginate(30)
 : collect();

 return view("duty.schedule", compact("duties"));
 }

 /* ============ SUPERVISION CHECKLIST ============ */
 public function supervision()
 {
 $staff = $this->staff();
 $logs = $staff
 ? SupervisionLog::where("staff_id", $staff->id)
 ->orderBy("log_date","desc")
 ->paginate(30)
 : collect();

 return view("duty.supervision.index", compact("logs"));
 }

 public function logSupervision(Request $request)
 {
 $data = $request->validate([
 "area" => "required|in:assembly,break,lunch,evening,night,gate,exam",
 "status" => "required|in:done,missed,issue",
 "notes" => "nullable|string|max:500",
 ]);

 $staff = $this->staff();
 if (!$staff) return back()->with("error","No staff record found.");

 SupervisionLog::create([
 "staff_id" => $staff->id,
 "log_date" => now()->toDateString(),
 "area" => $data["area"],
 "status" => $data["status"],
 "notes" => $data["notes"] ?? null,
 ]);

 return back()->with("success","Supervision logged.");
 }

 /* ============ DAILY REPORT ============ */
 public function reports()
 {
 $staff = $this->staff();
 $reports = $staff
 ? DutyReport::where("staff_id", $staff->id)
 ->orderBy("report_date","desc")
 ->paginate(20)
 : collect();

 return view("duty.reports.index", compact("reports"));
 }

 public function createReport()
 {
 return view("duty.reports.create");
 }

 public function storeReport(Request $request)
 {
 $data = $request->validate([
 "report_type" => "required|in:daily,weekly",
 "report_date" => "required|date",
 "morning_notes" => "nullable|string",
 "break_notes" => "nullable|string",
 "lunch_notes" => "nullable|string",
 "evening_notes" => "nullable|string",
 "night_notes" => "nullable|string",
 "incidents_count" => "nullable|integer|min:0",
 "summary" => "required|string",
 "handover" => "nullable|string",
 ]);

 $staff = $this->staff();
 if (!$staff) return back()->with("error","No staff record found.");

 $data["staff_id"] = $staff->id;
 $data["status"] = "submitted";

 DutyReport::create($data);

 return redirect()->route("duty.reports")->with("success","Duty report submitted.");
 }

 /* ============ HANDOVER ============ */
 public function handovers()
 {
 $staff = $this->staff();

 $from = $staff ? HandoverNote::with("toStaff")
 ->where("from_staff_id", $staff->id)
 ->orderBy("handover_date","desc")
 ->paginate(10, ["*"], "from_page") : collect();

 $to = $staff ? HandoverNote::with("fromStaff")
 ->where("to_staff_id", $staff->id)
 ->orderBy("handover_date","desc")
 ->paginate(10, ["*"], "to_page") : collect();

 return view("duty.handovers.index", compact("from","to"));
 }

 public function createHandover()
 {
 $teachers = Staff::where("status","active")->where("id","!=", $this->staff()?->id)->orderBy("first_name")->get();
 return view("duty.handovers.create", compact("teachers"));
 }

 public function storeHandover(Request $request)
 {
 $data = $request->validate([
 "to_staff_id" => "nullable|exists:staff,id",
 "handover_date" => "required|date",
 "notes" => "required|string",
 ]);

 $staff = $this->staff();
 if (!$staff) return back()->with("error","No staff record found.");

 $data["from_staff_id"] = $staff->id;

 HandoverNote::create($data);

 return redirect()->route("duty.handovers")->with("success","Handover note created.");
 }
}