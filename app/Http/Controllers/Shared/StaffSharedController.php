<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\DutyRoster;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Http\Request;

class StaffSharedController extends Controller
{
 protected function me(): ?Staff
 {
 return Staff::where("user_id", auth()->id())->first();
 }

 /* ============ SHARED DASHBOARD ============ */
 public function dashboard()
 {
 $staff = $this->me();
 $today = now()->toDateString();

 // Total counts
 $totalStudents = Student::where("status","active")->count();
 $totalStaff = Staff::where("status","active")->count();
 $totalClasses = ClassRoom::count();

 // Today attendance summary
 $todayAttendance = Attendance::whereDate("date", $today)->get();
 $presentCount = $todayAttendance->where("status","present")->count();
 $absentCount = $todayAttendance->where("status","absent")->count();
 $attendanceRate = $todayAttendance->count() > 0
 ? round(($presentCount / $todayAttendance->count()) * 100, 1) : 0;

 // Duty today
 $dutyToday = DutyRoster::with("staff")->whereDate("duty_date", $today)->first();

 // Staff present today
 $staffPresent = StaffAttendance::whereDate("attendance_date", $today)
 ->where("status", "present")
 ->count();

 // Latest announcements
 $announcements = Announcement::where("publish_date","<=",$today)
 ->where(function ($q) use ($today) {
 $q->whereNull("expiry_date")->orWhere("expiry_date",">=",$today);
 })
 ->orderByDesc("is_pinned")
 ->orderByDesc("publish_date")
 ->take(5)
 ->get();

 return view("shared.dashboard", compact(
 "staff","totalStudents","totalStaff","totalClasses",
 "presentCount","absentCount","attendanceRate",
 "dutyToday","staffPresent","announcements"
 ));
 }

 /* ============ CLASS TEACHERS LIST ============ */
 public function classTeachers()
 {
 $classrooms = ClassRoom::with(["classTeacher","students"])
 ->orderBy("name")
 ->get();

 return view("shared.class-teachers", compact("classrooms"));
 }

 /* ============ ALL CLASSES OVERVIEW ============ */
 public function classesOverview()
 {
 $today = now()->toDateString();

 $classrooms = ClassRoom::with("classTeacher")->orderBy("name")->get()->map(function ($c) use ($today) {
 $studentIds = Student::where("classroom_id", $c->id)->pluck("id");
 $total = $studentIds->count();

 $todayAtt = Attendance::whereIn("student_id", $studentIds)
 ->whereDate("date", $today)->get();

 $present = $todayAtt->where("status","present")->count();
 $absent = $todayAtt->where("status","absent")->count();
 $late = $todayAtt->where("status","late")->count();
 $notMarked = $total - ($present + $absent + $late);

 $c->total_students = $total;
 $c->present_today = $present;
 $c->absent_today = $absent;
 $c->late_today = $late;
 $c->not_marked = $notMarked;

 return $c;
 });

 return view("shared.classes-overview", compact("classrooms"));
 }

 /* ============ ATTENDANCE OVERVIEW ============ */
 public function attendanceOverview(Request $request)
 {
 $date = $request->query("date", now()->toDateString());

 $classrooms = ClassRoom::orderBy("name")->get()->map(function ($c) use ($date) {
 $studentIds = Student::where("classroom_id", $c->id)->pluck("id");
 $total = $studentIds->count();
 $records = Attendance::whereIn("student_id", $studentIds)
 ->whereDate("date", $date)->get();

 $c->total = $total;
 $c->present = $records->where("status","present")->count();
 $c->absent = $records->where("status","absent")->count();
 $c->late = $records->where("status","late")->count();
 $c->unmarked = $total - ($c->present + $c->absent + $c->late);
 return $c;
 });

 $totalStudents = $classrooms->sum("total");
 $totalPresent = $classrooms->sum("present");
 $totalAbsent = $classrooms->sum("absent");

 return view("shared.attendance-overview", compact(
 "date","classrooms","totalStudents","totalPresent","totalAbsent"
 ));
 }

 /* ============ PUBLIC DUTY ROSTER ============ */
 public function dutyRoster(Request $request)
 {
 $weekStart = $request->query("week_start", now()->startOfWeek()->toDateString());
 $weekEnd = \Carbon\Carbon::parse($weekStart)->endOfWeek()->toDateString();

 $duties = DutyRoster::with("staff")
 ->whereBetween("duty_date", [$weekStart, $weekEnd])
 ->orderBy("duty_date")
 ->get();

 return view("shared.duty-roster", compact("duties","weekStart","weekEnd"));
 }

 /* ============ ANNOUNCEMENTS ============ */
 public function announcements()
 {
 $today = now()->toDateString();

 $announcements = Announcement::where("publish_date","<=",$today)
 ->where(function ($q) use ($today) {
 $q->whereNull("expiry_date")->orWhere("expiry_date",">=",$today);
 })
 ->orderByDesc("is_pinned")
 ->orderByDesc("publish_date")
 ->paginate(15);

 return view("shared.announcements", compact("announcements"));
 }

 /* ============ STAFF DIRECTORY ============ */
 public function directory()
 {
 $staff = Staff::where("status","active")
 ->orderBy("department")
 ->orderBy("first_name")
 ->paginate(40);

 return view("shared.directory", compact("staff"));
 }
}