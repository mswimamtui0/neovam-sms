<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepartmentActivity;
use App\Models\School;
use App\Models\Staff;
use Illuminate\Http\Request;

class DepartmentActivityController extends Controller
{
 /**
 * Head dashboard — all departments' activities.
 */
 public function index(Request $request)
 {
 $query = DepartmentActivity::with(["staff","approver"])->latest("activity_date");

 if ($request->filled("department")) {
 $query->where("department", $request->department);
 }
 if ($request->filled("status")) {
 $query->where("status", $request->status);
 }
 if ($request->filled("priority")) {
 $query->where("priority", $request->priority);
 }
 if ($request->filled("from")) {
 $query->whereDate("activity_date", ">=", $request->from);
 }
 if ($request->filled("to")) {
 $query->whereDate("activity_date", "<=", $request->to);
 }

 $activities = $query->paginate(40)->withQueryString();
 $departments = DepartmentActivity::departments();

 // Stats per department
 $byDepartment = DepartmentActivity::selectRaw("department, COUNT(*) as count")
 ->groupBy("department")
 ->orderByDesc("count")
 ->get();

 $counts = [
 "total" => DepartmentActivity::count(),
 "pending" => DepartmentActivity::where("status","pending")->count(),
 "in_progress" => DepartmentActivity::where("status","in_progress")->count(),
 "completed" => DepartmentActivity::where("status","completed")->count(),
 "urgent" => DepartmentActivity::where("priority","urgent")->count(),
 ];

 return view("admin.department-activities.index", compact(
 "activities","departments","byDepartment","counts"
 ));
 }

 /**
 * Create form.
 */
 public function create()
 {
 $departments = DepartmentActivity::departments();
 $staffList = Staff::where("status","active")->orderBy("first_name")->get();

 return view("admin.department-activities.create", compact("departments","staffList"));
 }

 public function store(Request $request)
 {
 $data = $request->validate([
 "department" => "required|string|max:50",
 "staff_id" => "nullable|exists:staff,id",
 "title" => "required|string|max:200",
 "description" => "required|string",
 "activity_type" => "required|in:task,meeting,event,inspection,report,other",
 "activity_date" => "required|date",
 "status" => "required|in:pending,in_progress,completed,cancelled",
 "priority" => "required|in:low,normal,high,urgent",
 "outcome" => "nullable|string",
 "notes" => "nullable|string",
 ]);

 $data["school_id"] = School::first()?->id;

 DepartmentActivity::create($data);

 return redirect()->route("admin.department-activities.index")
 ->with("success","Activity logged.");
 }

 public function show(DepartmentActivity $activity)
 {
 $activity->load(["staff","approver"]);
 return view("admin.department-activities.show", compact("activity"));
 }

 public function edit(DepartmentActivity $activity)
 {
 $departments = DepartmentActivity::departments();
 $staffList = Staff::where("status","active")->orderBy("first_name")->get();
 return view("admin.department-activities.edit", compact("activity","departments","staffList"));
 }

 public function update(Request $request, DepartmentActivity $activity)
 {
 $data = $request->validate([
 "department" => "required|string|max:50",
 "staff_id" => "nullable|exists:staff,id",
 "title" => "required|string|max:200",
 "description" => "required|string",
 "activity_type" => "required|in:task,meeting,event,inspection,report,other",
 "activity_date" => "required|date",
 "status" => "required|in:pending,in_progress,completed,cancelled",
 "priority" => "required|in:low,normal,high,urgent",
 "outcome" => "nullable|string",
 "notes" => "nullable|string",
 ]);

 $activity->update($data);

 return redirect()->route("admin.department-activities.index")->with("success","Activity updated.");
 }

 public function destroy(DepartmentActivity $activity)
 {
 $activity->delete();
 return back()->with("success","Activity removed.");
 }

 /**
 * Head approval / acknowledgement.
 */
 public function approve(DepartmentActivity $activity)
 {
 $activity->update([
 "approved_by" => auth()->id(),
 "approved_at" => now(),
 ]);

 return back()->with("success","Activity approved.");
 }

 /**
 * Head dashboard — summary view by department.
 */
 public function dashboard()
 {
 $departments = DepartmentActivity::departments();
 $byDepartment = [];

 foreach ($departments as $dept) {
 $total = DepartmentActivity::where("department", $dept)->count();
 $pending = DepartmentActivity::where("department", $dept)->where("status","pending")->count();
 $inProgress= DepartmentActivity::where("department", $dept)->where("status","in_progress")->count();
 $completed = DepartmentActivity::where("department", $dept)->where("status","completed")->count();
 $urgent = DepartmentActivity::where("department", $dept)->where("priority","urgent")->count();

 $byDepartment[$dept] = [
 "total" => $total,
 "pending" => $pending,
 "in_progress" => $inProgress,
 "completed" => $completed,
 "urgent" => $urgent,
 ];
 }

 // Recent activities
 $recent = DepartmentActivity::with("staff")->latest("activity_date")->take(10)->get();

 return view("admin.department-activities.dashboard", compact("byDepartment","recent"));
 }
}