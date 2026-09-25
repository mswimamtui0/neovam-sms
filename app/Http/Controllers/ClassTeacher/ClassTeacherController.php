<?php

namespace App\Http\Controllers\ClassTeacher;

use App\Http\Controllers\Controller;
use App\Jobs\SendAbsenceSms;
use App\Models\Attendance;
use App\Models\ClassDisciplineLog;
use App\Models\ClassMeetingNote;
use App\Models\ClassRoom;
use App\Models\ParentContact;
use App\Models\Result;
use App\Models\Staff;
use App\Models\Student;
use App\Models\WelfareNote;
use Illuminate\Http\Request;

class ClassTeacherController extends Controller
{
 protected function staff(): ?Staff
 {
 return Staff::where("user_id", auth()->id())->first();
 }

 protected function myClass(): ?ClassRoom
 {
 $staff = $this->staff();
 if (!$staff) return null;
 return ClassRoom::where("class_teacher_id", $staff->id)->first();
 }

 /* ============ DASHBOARD ============ */
 public function dashboard()
 {
 $staff = $this->staff();
 $class = $this->myClass();

 if (!$class) {
 return view("class-teacher.no-class");
 }

 $students = Student::where("classroom_id", $class->id)->where("status", "active")->get();
 $studentCount = $students->count();
 $todayAttendance = Attendance::where("classroom_id", $class->id)
 ->whereDate("date", now()->toDateString())
 ->get();

 $presentToday = $todayAttendance->where("status", "present")->count();
 $absentToday = $todayAttendance->where("status", "absent")->count();
 $disciplineCount = ClassDisciplineLog::whereIn("student_id", $students->pluck("id"))->count();
 $welfareCount = WelfareNote::whereIn("student_id", $students->pluck("id"))->count();

 return view("class-teacher.dashboard", compact(
 "staff","class","students","studentCount",
 "presentToday","absentToday",
 "disciplineCount","welfareCount"
 ));
 }

 /* ============ STUDENTS ============ */
 public function students()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $students = Student::where("classroom_id", $class->id)
 ->where("status", "active")
 ->paginate(30);

 return view("class-teacher.students.index", compact("class","students"));
 }

 public function showStudent(Student $student)
 {
 $class = $this->myClass();
 if (!$class || $student->classroom_id !== $class->id) {
 abort(403, "Not your student.");
 }

 $student->load(["attendances","results.exam","incidents"]);
 $discipline = ClassDisciplineLog::where("student_id", $student->id)->latest()->get();
 $welfare = WelfareNote::where("student_id", $student->id)->latest()->get();

 return view("class-teacher.students.show", compact("student","discipline","welfare"));
 }

 /* ============ ATTENDANCE ============ */
 public function attendance()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $students = Student::where("classroom_id", $class->id)->where("status","active")->get();
 return view("class-teacher.attendance.index", compact("class","students"));
 }

 public function markAttendance(Request $request)
 {
 $class = $this->myClass();
 if (!$class) return back()->with("error","No class assigned.");

 $data = $request->validate([
 "date" => "required|date",
 "statuses" => "required|array",
 "statuses.*" => "in:present,absent,late",
 ]);

 $teacher = auth()->user()->name ?? "Class Teacher";

 foreach ($data["statuses"] as $studentId => $status) {
 $student = Student::find($studentId);
 if (!$student || $student->classroom_id !== $class->id) continue;

 $attendance = Attendance::updateOrCreate(
 ["student_id" => $student->id, "date" => $data["date"]],
 [
 "classroom_id" => $class->id,
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

 return back()->with("success","Attendance saved.");
 }

 /* ============ DISCIPLINE ============ */
 public function discipline()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $studentIds = Student::where("classroom_id", $class->id)->pluck("id");
 $logs = ClassDisciplineLog::with("student")->whereIn("student_id", $studentIds)->latest()->paginate(20);

 return view("class-teacher.discipline.index", compact("class","logs"));
 }

 public function createDiscipline()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $students = Student::where("classroom_id", $class->id)->where("status","active")->get();
 return view("class-teacher.discipline.create", compact("class","students"));
 }

 public function storeDiscipline(Request $request)
 {
 $class = $this->myClass();
 if (!$class) return back()->with("error","No class assigned.");

 $data = $request->validate([
 "student_id" => "required|exists:students,id",
 "type" => "required|in:warning,detention,suspension,praise",
 "reason" => "required|string",
 "action_taken" => "nullable|string",
 "log_date" => "required|date",
 ]);

 $data["staff_id"] = $this->staff()?->id;
 ClassDisciplineLog::create($data);

 return redirect()->route("class-teacher.discipline")->with("success","Discipline log added.");
 }

 /* ============ PERFORMANCE ============ */
 public function performance()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $students = Student::with(["results.exam"])->where("classroom_id", $class->id)->get();
 return view("class-teacher.performance.index", compact("class","students"));
 }

 /* ============ PARENT CONTACTS ============ */
 public function parents()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $staff = $this->staff();
 $contacts = ParentContact::with("student")->where("staff_id", $staff->id)->latest()->paginate(20);

 return view("class-teacher.parents.index", compact("class","contacts"));
 }

 public function createParentContact()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $students = Student::where("classroom_id", $class->id)->get();
 return view("class-teacher.parents.create", compact("class","students"));
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

 return redirect()->route("class-teacher.parents")->with("success","Contact logged.");
 }

 /* ============ WELFARE ============ */
 public function welfare()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $studentIds = Student::where("classroom_id", $class->id)->pluck("id");
 $notes = WelfareNote::with("student")->whereIn("student_id", $studentIds)->latest()->paginate(20);

 return view("class-teacher.welfare.index", compact("class","notes"));
 }

 public function createWelfare()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $students = Student::where("classroom_id", $class->id)->get();
 return view("class-teacher.welfare.create", compact("class","students"));
 }

 public function storeWelfare(Request $request)
 {
 $data = $request->validate([
 "student_id" => "required|exists:students,id",
 "type" => "required|in:health,hygiene,uniform,food,other",
 "observation" => "required|string",
 "action" => "nullable|string",
 "note_date" => "required|date",
 ]);

 $data["staff_id"] = $this->staff()?->id;
 WelfareNote::create($data);

 return redirect()->route("class-teacher.welfare")->with("success","Welfare note added.");
 }

 /* ============ MEETINGS ============ */
 public function meetings()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 $meetings = ClassMeetingNote::where("classroom_id", $class->id)->latest()->paginate(20);
 return view("class-teacher.meetings.index", compact("class","meetings"));
 }

 public function createMeeting()
 {
 $class = $this->myClass();
 if (!$class) return view("class-teacher.no-class");

 return view("class-teacher.meetings.create", compact("class"));
 }

 public function storeMeeting(Request $request)
 {
 $class = $this->myClass();
 if (!$class) return back()->with("error","No class assigned.");

 $data = $request->validate([
 "meeting_date" => "required|date",
 "topic" => "required|string|max:200",
 "notes" => "required|string",
 "decisions" => "nullable|string",
 ]);

 $data["classroom_id"] = $class->id;
 $data["staff_id"] = $this->staff()?->id;

 ClassMeetingNote::create($data);

 return redirect()->route("class-teacher.meetings")->with("success","Meeting recorded.");
 }
}