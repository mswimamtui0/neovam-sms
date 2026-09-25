<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\ClassTeacherRoster;
use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentClassMovement;
use App\Models\StaffDepartmentHistory;
use App\Models\YearlyDutyAssignment;
use App\Models\YearlyDutyCalendar;
use Illuminate\Http\Request;

class YearlyPlannerController extends Controller
{
 /* ============ YEARLY DUTY CALENDAR ============ */
 public function dutyIndex(Request $request)
 {
 $year = $request->query("year", date("Y"));
 $calendars = YearlyDutyCalendar::where("year", $year)->latest()->paginate(20);

 return view("admin.planner.duty-index", compact("calendars","year"));
 }

 public function dutyCreate(Request $request)
 {
 $year = $request->query("year", date("Y"));
 return view("admin.planner.duty-create", compact("year"));
 }

 public function dutyStore(Request $request)
 {
 $data = $request->validate([
 "year" => "required|integer|min:2000|max:2100",
 "title" => "required|string|max:150",
 "notes" => "nullable|string",
 ]);

 $data["school_id"] = School::first()?->id;
 $data["created_by"] = auth()->id();
 $data["status"] = "draft";

 $calendar = YearlyDutyCalendar::create($data);

 return redirect()->route("admin.planner.duty.show", $calendar)
 ->with("success","Yearly duty calendar created. Add assignments below.");
 }

 public function dutyShow(YearlyDutyCalendar $calendar, Request $request)
 {
 $query = YearlyDutyAssignment::with("staff")->where("calendar_id", $calendar->id);

 if ($request->filled("month")) {
 $query->whereMonth("duty_date", $request->month);
 }

 $assignments = $query->orderBy("duty_date")->paginate(50);
 $staffList = Staff::where("status","active")->orderBy("first_name")->get();

 return view("admin.planner.duty-show", compact("calendar","assignments","staffList"));
 }

 public function dutyAssign(Request $request, YearlyDutyCalendar $calendar)
 {
 $data = $request->validate([
 "staff_id" => "required|exists:staff,id",
 "duty_date" => "required|date",
 "duty_type" => "required|in:general,gate,assembly,break,lunch,evening,night,exam",
 "shift" => "required|in:day,evening,night",
 "location" => "nullable|string|max:100",
 "notes" => "nullable|string",
 ]);

 $data["calendar_id"] = $calendar->id;
 YearlyDutyAssignment::create($data);

 return back()->with("success","Duty assigned.");
 }

 /**
 * Bulk assign — same teacher, multiple dates.
 */
 public function dutyBulkAssign(Request $request, YearlyDutyCalendar $calendar)
 {
 $data = $request->validate([
 "staff_id" => "required|exists:staff,id",
 "dates" => "required|string", // comma-separated dates
 "duty_type" => "required|in:general,gate,assembly,break,lunch,evening,night,exam",
 "shift" => "required|in:day,evening,night",
 "location" => "nullable|string|max:100",
 ]);

 $dates = array_filter(array_map("trim", explode(",", $data["dates"])));
 $count = 0;

 foreach ($dates as $date) {
 try {
 YearlyDutyAssignment::create([
 "calendar_id" => $calendar->id,
 "staff_id" => $data["staff_id"],
 "duty_date" => $date,
 "duty_type" => $data["duty_type"],
 "shift" => $data["shift"],
 "location" => $data["location"] ?? null,
 ]);
 $count++;
 } catch (\Throwable $e) {
 // skip bad dates
 }
 }

 return back()->with("success","{$count} duty assignments created.");
 }

 public function dutyDestroy(YearlyDutyAssignment $assignment)
 {
 $assignment->delete();
 return back()->with("success","Duty removed.");
 }

 public function dutyActivate(YearlyDutyCalendar $calendar)
 {
 $calendar->update(["status" => "active"]);
 return back()->with("success","Calendar activated.");
 }

 /* ============ CLASS TEACHER YEARLY ROSTER ============ */
 public function classTeacherIndex(Request $request)
 {
 $year = $request->query("year", date("Y"));

 $rosters = ClassTeacherRoster::with(["classroom","staff"])
 ->where("year", $year)
 ->orderBy("classroom_id")
 ->paginate(30);

 $classrooms = ClassRoom::orderBy("name")->get();
 $staffList = Staff::where("status","active")->orderBy("first_name")->get();

 return view("admin.planner.class-teacher-index", compact("rosters","classrooms","staffList","year"));
 }

 public function classTeacherStore(Request $request)
 {
 $data = $request->validate([
 "classroom_id" => "required|exists:classrooms,id",
 "staff_id" => "required|exists:staff,id",
 "year" => "required|integer",
 "term" => "nullable|string|max:50",
 "start_date" => "required|date",
 "end_date" => "nullable|date|after:start_date",
 "notes" => "nullable|string",
 ]);

 $data["school_id"] = School::first()?->id;
 $data["assigned_by"] = auth()->id();
 $data["is_active"] = true;

 // Deactivate any existing roster for the same class/year
 ClassTeacherRoster::where("classroom_id", $data["classroom_id"])
 ->where("year", $data["year"])
 ->update(["is_active" => false]);

 // Assign this teacher also as class_teacher_id on the classroom
 ClassRoom::where("id", $data["classroom_id"])->update(["class_teacher_id" => $data["staff_id"]]);

 ClassTeacherRoster::create($data);

 return back()->with("success","Class teacher assigned for {$data["year"]}.");
 }

 public function classTeacherDestroy(ClassTeacherRoster $roster)
 {
 $roster->update(["is_active" => false]);
 return back()->with("success","Roster deactivated.");
 }

 /* ============ STAFF DEPARTMENT HISTORY ============ */
 public function staffHistory(Staff $staff)
 {
 $history = StaffDepartmentHistory::where("staff_id", $staff->id)
 ->with("changer")
 ->latest("effective_date")
 ->get();

 $staff->load("subjects");

 return view("admin.planner.staff-history", compact("staff","history"));
 }

 public function staffHistoryStore(Request $request, Staff $staff)
 {
 $data = $request->validate([
 "to_roles" => "required|string",
 "to_department" => "required|string|max:100",
 "effective_date" => "required|date",
 "reason" => "nullable|string",
 ]);

 // Record the history
 StaffDepartmentHistory::create([
 "staff_id" => $staff->id,
 "from_roles" => $staff->department_roles,
 "to_roles" => $data["to_roles"],
 "from_department" => $staff->department,
 "to_department" => $data["to_department"],
 "effective_date" => $data["effective_date"],
 "reason" => $data["reason"] ?? null,
 "changed_by" => auth()->id(),
 ]);

 // Update the staff
 $staff->update([
 "department_roles" => $data["to_roles"],
 "department" => $data["to_department"],
 ]);

 return back()->with("success","Department changed and logged.");
 }

 /* ============ STUDENT CLASS MOVEMENTS ============ */
 public function studentMovementIndex(Request $request)
 {
 $query = StudentClassMovement::with(["student","fromClassroom","toClassroom"])->latest("effective_date");

 if ($request->filled("student_id")) {
 $query->where("student_id", $request->student_id);
 }

 $movements = $query->paginate(40);
 return view("admin.planner.movement-index", compact("movements"));
 }

 public function studentMove(Request $request, Student $student)
 {
 $data = $request->validate([
 "to_classroom_id" => "required|exists:classrooms,id",
 "movement_type" => "required|in:promotion,transfer,stream_change,repeat,left",
 "reason" => "nullable|string",
 "effective_date" => "required|date",
 ]);

 $fromClass = $student->classroom;
 $toClass = ClassRoom::find($data["to_classroom_id"]);

 // Record movement
 StudentClassMovement::create([
 "student_id" => $student->id,
 "from_classroom_id" => $fromClass?->id,
 "to_classroom_id" => $toClass->id,
 "from_level" => $student->level,
 "to_level" => $toClass->level,
 "movement_type" => $data["movement_type"],
 "reason" => $data["reason"] ?? null,
 "effective_date" => $data["effective_date"],
 "moved_by" => auth()->id(),
 ]);

 // Update student
 $student->update([
 "classroom_id" => $toClass->id,
 "level" => $toClass->level,
 ]);

 return back()->with("success","{$student->full_name} moved to {$toClass->name}.");
 }
}