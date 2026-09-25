<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\BoardingLog;
use App\Models\DisciplineRecord;
use App\Models\EnvironmentLog;
use App\Models\FeedingLog;
use App\Models\GuidanceSession;
use App\Models\HealthRecord;
use App\Models\LibraryBook;
use App\Models\LibraryLoan;
use App\Models\SecurityLog;
use App\Models\SportsMatch;
use App\Models\SportsTeam;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
 protected function me(): ?Staff
 {
 return Staff::where("user_id", auth()->id())->first();
 }

 /* ============ DEPARTMENT HUB ============ */
 public function hub()
 {
 $staff = $this->me();
 return view("department.hub", compact("staff"));
 }

 /* ============ HEALTH ============ */
 public function health()
 {
 $records = HealthRecord::with(["student","staff"])->latest("record_date")->paginate(30);

 $stats = [
 "total" => HealthRecord::count(),
 "today" => HealthRecord::whereDate("record_date", now()->toDateString())->count(),
 "severe" => HealthRecord::where("severity", "severe")->count(),
 "referred" => HealthRecord::where("referred_hospital", true)->count(),
 ];

 return view("department.health.index", compact("records","stats"));
 }

 public function healthCreate()
 {
 $students = Student::with("classroom")->where("status","active")->get();
 return view("department.health.create", compact("students"));
 }

 public function healthStore(Request $request)
 {
 $data = $request->validate([
 "student_id" => "required|exists:students,id",
 "record_type" => "required|in:illness,injury,first_aid,hygiene,referral,checkup",
 "title" => "required|string|max:200",
 "description" => "required|string",
 "treatment" => "nullable|string",
 "severity" => "required|in:mild,moderate,severe",
 "referred_hospital" => "nullable|boolean",
 "parent_notified" => "nullable|boolean",
 "record_date" => "required|date",
 ]);

 $data["staff_id"] = $this->me()?->id;
 $data["referred_hospital"] = $request->boolean("referred_hospital");
 $data["parent_notified"] = $request->boolean("parent_notified");

 HealthRecord::create($data);

 return redirect()->route("department.health")->with("success","Health record saved.");
 }

 /* ============ DISCIPLINE ============ */
 public function discipline()
 {
 $records = DisciplineRecord::with(["student","staff"])->latest("incident_date")->paginate(30);

 $stats = [
 "total" => DisciplineRecord::count(),
 "today" => DisciplineRecord::whereDate("incident_date", now()->toDateString())->count(),
 "warnings" => DisciplineRecord::where("action_taken","warning")->count(),
 "praise" => DisciplineRecord::where("action_taken","praise")->count(),
 ];

 return view("department.discipline.index", compact("records","stats"));
 }

 public function disciplineCreate()
 {
 $students = Student::with("classroom")->where("status","active")->get();
 return view("department.discipline.create", compact("students"));
 }

 public function disciplineStore(Request $request)
 {
 $data = $request->validate([
 "student_id" => "required|exists:students,id",
 "offense" => "required|string|max:200",
 "description" => "required|string",
 "action_taken" => "required|in:warning,detention,suspension,parent_meeting,counseling,praise",
 "notes" => "nullable|string",
 "parent_notified" => "nullable|boolean",
 "incident_date" => "required|date",
 ]);

 $data["staff_id"] = $this->me()?->id;
 $data["parent_notified"] = $request->boolean("parent_notified");

 DisciplineRecord::create($data);

 return redirect()->route("department.discipline")->with("success","Discipline record saved.");
 }

 /* ============ SPORTS ============ */
 public function sports()
 {
 $teams = SportsTeam::with("coach")->withCount("matches")->latest()->paginate(20);
 $matches = SportsMatch::with("team")->latest("match_date")->take(10)->get();

 return view("department.sports.index", compact("teams","matches"));
 }

 public function sportsCreate()
 {
 return view("department.sports.create");
 }

 public function sportsStore(Request $request)
 {
 $data = $request->validate([
 "name" => "required|string|max:100",
 "sport" => "required|string|max:100",
 "age_group" => "nullable|string|max:50",
 "notes" => "nullable|string",
 ]);

 $data["coach_id"] = $this->me()?->id;
 SportsTeam::create($data);

 return redirect()->route("department.sports")->with("success","Team created.");
 }

 /* ============ BOARDING ============ */
 public function boarding()
 {
 $logs = BoardingLog::with("staff")->latest("log_date")->paginate(30);

 return view("department.boarding.index", compact("logs"));
 }

 public function boardingCreate()
 {
 return view("department.boarding.create");
 }

 public function boardingStore(Request $request)
 {
 $data = $request->validate([
 "log_type" => "required|in:wake_up,roll_call,night_check,cleanliness,prep,incident",
 "dormitory" => "nullable|string|max:100",
 "description" => "required|string",
 "log_date" => "required|date",
 "log_time" => "nullable",
 "students_present" => "nullable|integer|min:0",
 "students_absent" => "nullable|integer|min:0",
 "notes" => "nullable|string",
 ]);

 $data["staff_id"] = $this->me()?->id;
 BoardingLog::create($data);

 return redirect()->route("department.boarding")->with("success","Boarding log saved.");
 }

 /* ============ FEEDING ============ */
 public function feeding()
 {
 $logs = FeedingLog::with("staff")->latest("log_date")->paginate(30);
 return view("department.feeding.index", compact("logs"));
 }

 public function feedingCreate()
 {
 return view("department.feeding.create");
 }

 public function feedingStore(Request $request)
 {
 $data = $request->validate([
 "meal" => "required|in:breakfast,lunch,dinner,snack",
 "menu" => "nullable|string|max:255",
 "students_served" => "nullable|integer|min:0",
 "hygiene_rating" => "nullable|in:excellent,good,fair,poor",
 "notes" => "nullable|string",
 "log_date" => "required|date",
 ]);

 $data["staff_id"] = $this->me()?->id;
 FeedingLog::create($data);

 return redirect()->route("department.feeding")->with("success","Feeding log saved.");
 }

 /* ============ LIBRARY ============ */
 public function library()
 {
 $books = LibraryBook::withCount("loans")->latest()->paginate(30);
 $activeLoans = LibraryLoan::with(["book","student"])->where("status","borrowed")->count();

 return view("department.library.index", compact("books","activeLoans"));
 }

 public function libraryCreate()
 {
 return view("department.library.create");
 }

 public function libraryStore(Request $request)
 {
 $data = $request->validate([
 "title" => "required|string|max:200",
 "author" => "nullable|string|max:150",
 "isbn" => "nullable|string|max:50",
 "category" => "nullable|string|max:100",
 "total_copies" => "required|integer|min:1",
 ]);

 $data["available_copies"] = $data["total_copies"];
 LibraryBook::create($data);

 return redirect()->route("department.library")->with("success","Book added.");
 }

 /* ============ GUIDANCE ============ */
 public function guidance()
 {
 $sessions = GuidanceSession::with(["student","staff"])->latest("session_date")->paginate(30);

 $stats = [
 "total" => GuidanceSession::count(),
 "today" => GuidanceSession::whereDate("session_date", now()->toDateString())->count(),
 "follow_up" => GuidanceSession::where("follow_up_needed", true)->count(),
 ];

 return view("department.guidance.index", compact("sessions","stats"));
 }

 public function guidanceCreate()
 {
 $students = Student::with("classroom")->where("status","active")->get();
 return view("department.guidance.create", compact("students"));
 }

 public function guidanceStore(Request $request)
 {
 $data = $request->validate([
 "student_id" => "required|exists:students,id",
 "session_type" => "required|in:personal,academic,career,family",
 "topic" => "required|string|max:200",
 "notes" => "required|string",
 "action_plan" => "nullable|string",
 "follow_up_needed" => "nullable|boolean",
 "session_date" => "required|date",
 ]);

 $data["staff_id"] = $this->me()?->id;
 $data["follow_up_needed"] = $request->boolean("follow_up_needed");

 GuidanceSession::create($data);

 return redirect()->route("department.guidance")->with("success","Counseling session saved.");
 }

 /* ============ ENVIRONMENT ============ */
 public function environment()
 {
 $logs = EnvironmentLog::with("staff")->latest("log_date")->paginate(30);
 return view("department.environment.index", compact("logs"));
 }

 public function environmentCreate()
 {
 return view("department.environment.create");
 }

 public function environmentStore(Request $request)
 {
 $data = $request->validate([
 "log_type" => "required|in:cleanliness,tree_planting,waste,beautification",
 "area" => "nullable|string|max:100",
 "rating" => "nullable|in:excellent,good,fair,poor",
 "description" => "required|string",
 "action_taken" => "nullable|string",
 "log_date" => "required|date",
 ]);

 $data["staff_id"] = $this->me()?->id;
 EnvironmentLog::create($data);

 return redirect()->route("department.environment")->with("success","Environment log saved.");
 }

 /* ============ SECURITY ============ */
 public function security()
 {
 $logs = SecurityLog::with("staff")->latest("log_date")->paginate(30);
 return view("department.security.index", compact("logs"));
 }

 public function securityCreate()
 {
 return view("department.security.create");
 }

 public function securityStore(Request $request)
 {
 $data = $request->validate([
 "log_type" => "required|in:patrol,gate_duty,drill,visitor,incident",
 "location" => "nullable|string|max:150",
 "description" => "required|string",
 "status" => "required|in:normal,alert,resolved",
 "log_date" => "required|date",
 "log_time" => "nullable",
 ]);

 $data["staff_id"] = $this->me()?->id;
 SecurityLog::create($data);

 return redirect()->route("department.security")->with("success","Security log saved.");
 }
}