<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\TeacherReport;
use Illuminate\Http\Request;

class PerformanceReviewController extends Controller
{
 /**
 * List all reports pending review + stats.
 */
 public function index(Request $request)
 {
 $query = TeacherReport::with(["staff","reviewer"])->latest();

 if ($request->filled("review_status")) {
 $query->where("review_status", $request->review_status);
 }
 if ($request->filled("staff_id")) {
 $query->where("staff_id", $request->staff_id);
 }
 if ($request->filled("report_type")) {
 $query->where("report_type", $request->report_type);
 }

 $reports = $query->paginate(30);

 $counts = [
 "pending" => TeacherReport::where("review_status", "pending")->count(),
 "under_review" => TeacherReport::where("review_status", "under_review")->count(),
 "approved" => TeacherReport::where("review_status", "approved")->count(),
 "rejected" => TeacherReport::where("review_status", "rejected")->count(),
 "needs_revision" => TeacherReport::where("review_status", "needs_revision")->count(),
 ];

 $staffList = Staff::where("status", "active")->orderBy("first_name")->get();

 return view("admin.performance.index", compact("reports","counts","staffList"));
 }

 /**
 * Show a single report with full details.
 */
 public function show(TeacherReport $report)
 {
 $report->load(["staff","reviewer"]);

 // Mark as under review on first view
 if ($report->review_status === "pending") {
 $report->update(["review_status" => "under_review"]);
 }

 return view("admin.performance.show", compact("report"));
 }

 /**
 * Approve with rating.
 */
 public function approve(Request $request, TeacherReport $report)
 {
 $data = $request->validate([
 "rating" => "required|integer|min:1|max:5",
 "head_comment" => "nullable|string|max:1000",
 ]);

 $report->approve($data["rating"], $data["head_comment"] ?? null);

 return back()->with("success", "Report approved with {$data["rating"]}-star rating.");
 }

 /**
 * Reject with comment.
 */
 public function reject(Request $request, TeacherReport $report)
 {
 $data = $request->validate([
 "head_comment" => "required|string|max:1000",
 ]);

 $report->reject($data["head_comment"]);

 return back()->with("success", "Report rejected.");
 }

 /**
 * Request revision.
 */
 public function requestRevision(Request $request, TeacherReport $report)
 {
 $data = $request->validate([
 "head_comment" => "required|string|max:1000",
 ]);

 $report->requestRevision($data["head_comment"]);

 return back()->with("success", "Revision requested.");
 }

 /**
 * Performance dashboard — per-teacher stats.
 */
 public function dashboard(Request $request)
 {
 $month = $request->query("month", now()->month);
 $year = $request->query("year", now()->year);

 $staff = Staff::where("status", "active")
 ->whereIn("staff_type", ["Teacher","Head of Department","Academic Master","Teacher on Duty"])
 ->orderBy("first_name")
 ->get();

 $data = $staff->map(function ($s) use ($month, $year) {
 $reports = TeacherReport::where("staff_id", $s->id)
 ->whereMonth("created_at", $month)
 ->whereYear("created_at", $year)
 ->get();

 return [
 "staff" => $s,
 "total_reports" => $reports->count(),
 "approved" => $reports->where("review_status","approved")->count(),
 "pending" => $reports->where("review_status","pending")->count(),
 "avg_rating" => $reports->whereNotNull("rating")->count() > 0
 ? round($reports->whereNotNull("rating")->avg("rating"), 1) : null,
 "total_periods" => $reports->sum("periods_taught"),
 "total_absent" => $reports->sum("students_absent"),
 ];
 });

 return view("admin.performance.dashboard", compact("data","month","year"));
 }
}