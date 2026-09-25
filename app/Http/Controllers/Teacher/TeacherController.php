<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Jobs\SendAbsenceSms;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\DepartmentTask;
use App\Models\DutyRoster;
use App\Models\Incident;
use App\Models\LeaveRequest;
use App\Models\LessonPlan;
use App\Models\ParentContact;
use App\Models\SchemeOfWork;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherReport;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
 protected function staff(): ?Staff
 {
 return Staff::where("user_id", auth()->id())->first();
 }

 protected function assignedClassrooms()
 {
 $staff = $this->staff();
 return $staff ? $staff->assignedClasses() : collect();
 }

 public function dashboard()
 {
 $staff = $this->staff();
 $classes = $this->assignedClassrooms();

 $studentCount = Student::whereIn("classroom_id", $classes->pluck("id"))->count();
 $todayAttendance = Attendance::whereDate("date", now()->toDateString())->count();

 $todayName = now()->format("l"); // Monday..Sunday
 $todayTimetable = $staff
 ? Timetable::with(["classroom","subject"])
 ->where("staff_id", $staff->id)
 ->where("day_of_week", $todayName)
 ->orderBy("start_time")
 ->get()
 : collect();

 $weeklyCount = $staff ? Timetable::where("staff_id", $staff->id)->count() : 0;

 $todayDuty = $staff ? DutyRoster::where("staff_id", $staff->id)->whereDate("duty_date", now()->toDateString())->first() : null;
 $pendingReports = $staff ? TeacherReport::where("staff_id", $staff->id)->where("status","draft")->count() : 0;
 $pendingTasks = $staff ? DepartmentTask::where("staff_id", $staff->id)->where("status","pending")->count() : 0;

 return view("teacher.dashboard", compact(
 "staff","classes","studentCount","todayAttendance",
 "todayName","todayTimetable","weeklyCount",
 "todayDuty","pendingReports","pendingTasks"
 ));
 }

 public function myTimetable()
 {
 $staff = $this->staff();
 $days = Timetable::days();

 $entries = $staff
 ? Timetable::with(["classroom","subject"])
 ->where("staff_id", $staff->id)
 ->orderByRaw("CASE day_of_week
 WHEN 'Monday' THEN 1
 WHEN 'Tuesday' THEN 2
 WHEN 'Wednesday' THEN 3
 WHEN 'Thursday' THEN 4
 WHEN 'Friday' THEN 5
 WHEN 'Saturday' THEN 6
 WHEN 'Sunday' THEN 7 END")
 ->orderBy("start_time")
 ->get()
 ->groupBy("day_of_week")
 : collect();

 return view("teacher.timetable", compact("entries","days","staff"));
 }

 public function classes()
 {
 $classes = $this->assignedClassrooms();
 return view("teacher.classes", compact("classes"));
 }

 public function students()
 {
 $students = Student::with("classroom")->where("status","active")->paginate(30);
 return view("teacher.students", compact("students"));
 }

 public function attendance()
 {
 $classes = $this->assignedClassrooms();
 return view("teacher.attendance", compact("classes"));
 }

 public function markAttendance(Request $request)
 {
 $data = $request->validate([
 "date" => "required|date",
 "statuses" => "required|array",
 "statuses.*" => "in:present,absent,late",
 ]);

 $teacher = auth()->user()->name ?? "Teacher";

 foreach ($data["statuses"] as $studentId => $status) {
 $student = Student::find($studentId);
 if (!$student) continue;

 $attendance = Attendance::updateOrCreate(
 ["student_id" => $student->id, "date" => $data["date"]],
 [
 "classroom_id" => $student->classroom_id,
 "status" => $status,
 "recorded_by" => $teacher,
 "sms_sent" => false,
 ]
 );

 if ($status === "absent" && !$attendance->sms_sent) {
 SendAbsenceSms::dispatch($student->parent_phone, $student->full_name, $data["date"]);
 $attendance->update(["sms_sent" => true]);
 }
 }

 return back()->with("success", "Attendance saved. SMS sent for absentees.");
 }

 public function lessonPlans()
 {
 $staff = $this->staff();
 $plans = $staff ? LessonPlan::where("staff_id", $staff->id)->latest()->paginate(20) : collect();
 return view("teacher.lesson-plans.index", compact("plans"));
 }

 public function createLessonPlan()
 {
 $classes = $this->assignedClassrooms();
 $subjects = Subject::orderBy("name")->get();
 return view("teacher.lesson-plans.create", compact("classes","subjects"));
 }

 public function storeLessonPlan(Request $request)
 {
 $data = $request->validate([
 "classroom_id" => "nullable|exists:classrooms,id",
 "subject_id" => "nullable|exists:subjects,id",
 "date" => "required|date",
 "topic" => "required|string|max:200",
 "objectives" => "nullable|string",
 "activities" => "nullable|string",
 "materials" => "nullable|string",
 ]);

 $data["staff_id"] = $this->staff()?->id;
 $data["status"] = "draft";

 LessonPlan::create($data);
 return redirect()->route("teacher.lesson-plans")->with("success","Lesson plan created.");
 }

 public function schemes()
 {
 $staff = $this->staff();
 $schemes = $staff ? SchemeOfWork::where("staff_id", $staff->id)->latest()->paginate(20) : collect();
 return view("teacher.schemes.index", compact("schemes"));
 }

 public function createScheme()
 {
 $classes = $this->assignedClassrooms();
 $subjects = Subject::orderBy("name")->get();
 return view("teacher.schemes.create", compact("classes","subjects"));
 }

 public function storeScheme(Request $request)
 {
 $data = $request->validate([
 "classroom_id" => "nullable|exists:classrooms,id",
 "subject_id" => "nullable|exists:subjects,id",
 "term" => "required|string|max:50",
 "year" => "required|integer",
 "content" => "required|string",
 ]);

 $data["staff_id"] = $this->staff()?->id;
 $data["status"] = "draft";

 SchemeOfWork::create($data);
 return redirect()->route("teacher.schemes")->with("success","Scheme created.");
 }

 public function dutyRoster()
 {
 $staff = $this->staff();
 $duties = $staff ? DutyRoster::where("staff_id", $staff->id)->orderBy("duty_date","desc")->paginate(30) : collect();
 return view("teacher.duty.index", compact("duties"));
 }

 public function departmentTasks()
 {
 $staff = $this->staff();
 $tasks = $staff ? DepartmentTask::where("staff_id", $staff->id)->latest()->paginate(20) : collect();
 return view("teacher.department.index", compact("tasks"));
 }

 public function parentContacts()
 {
 $staff = $this->staff();
 $contacts = $staff ? ParentContact::with("student")->where("staff_id", $staff->id)->latest()->paginate(20) : collect();
 return view("teacher.parents.index", compact("contacts"));
 }

 public function createParentContact()
 {
 $students = Student::with("classroom")->where("status","active")->get();
 return view("teacher.parents.create", compact("students"));
 }

 public function storeParentContact(Request $request)
 {
 $data = $request->validate([
 "student_id" => "required|exists:students,id",
 "contact_type" => "required|in:call,sms,meeting,visit,note",
 "reason" => "required|string|max:500",
 "outcome" => "nullable|string|max:500",
 ]);

 $data["staff_id"] = $this->staff()?->id;
 ParentContact::create($data);
 return redirect()->route("teacher.parents")->with("success","Contact logged.");
 }

 public function reports()
 {
 $staff = $this->staff();
 $reports = $staff ? TeacherReport::where("staff_id", $staff->id)->latest()->paginate(20) : collect();
 return view("teacher.reports.index", compact("reports"));
 }

 public function createReport() { return view("teacher.reports.create"); }

 public function storeReport(Request $request)
 {
 $data = $request->validate([
 "report_type" => "required|in:daily,weekly,monthly,termly",
 "week_start" => "required|date",
 "week_end" => "required|date|after_or_equal:week_start",
 "summary" => "required|string",
 "challenges" => "nullable|string",
 "next_plan" => "nullable|string",
 "periods_taught" => "nullable|integer|min:0",
 "students_absent" => "nullable|integer|min:0",
 ]);

 $data["staff_id"] = $this->staff()?->id;
 $data["status"] = "submitted";

 TeacherReport::create($data);
 return redirect()->route("teacher.reports")->with("success","Report submitted.");
 }

 public function incidents()
 {
 $incidents = Incident::with("student")->latest()->paginate(20);
 return view("teacher.incidents", compact("incidents"));
 }

 public function leaves()
 {
 $staff = $this->staff();
 $leaves = $staff ? LeaveRequest::where("staff_id", $staff->id)->latest()->paginate(20) : collect();
 return view("teacher.leave.index", compact("leaves"));
 }

 public function createLeave() { return view("teacher.leave.create"); }

 public function storeLeave(Request $request)
 {
 $data = $request->validate([
 "leave_type" => "required|in:casual,sick,annual,maternity,emergency",
 "start_date" => "required|date",
 "end_date" => "required|date|after_or_equal:start_date",
 "reason" => "required|string",
 ]);

 $data["staff_id"] = $this->staff()?->id;
 $data["status"] = "pending";

 LeaveRequest::create($data);
 return redirect()->route("teacher.leave")->with("success","Leave request submitted.");
 }

 public function profile()
 {
 $staff = $this->staff();
 return view("teacher.profile", compact("staff"));
 }
}