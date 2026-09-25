<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use App\Models\AcademicMeeting;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\ExamTimetable;
use App\Models\Result;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use App\Models\SyllabusCoverage;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
 /* ============ DASHBOARD ============ */
 public function dashboard()
 {
 $totalStudents = Student::where("status", "active")->count();
 $totalTeachers = Staff::whereIn("staff_type", ["Teacher","Head of Department","Academic Master"])->count();
 $totalSubjects = Subject::where("is_active", true)->count();
 $publishedExams = Exam::where("published", true)->count();
 $pendingExams = Exam::where("published", false)->count();

 // Syllabus coverage summary
 $coverage = SyllabusCoverage::all();
 $avgCoverage = $coverage->count() ? round($coverage->avg(fn($c) => $c->percent()), 1) : 0;

 // Results summary — average marks
 $avgMarks = Result::avg("marks");

 return view("academic.dashboard", compact(
 "totalStudents","totalTeachers","totalSubjects",
 "publishedExams","pendingExams","avgCoverage","avgMarks"
 ));
 }

 /* ============ SYLLABUS COVERAGE ============ */
 public function syllabus()
 {
 $coverage = SyllabusCoverage::with(["classroom","subject","staff"])->latest()->paginate(30);
 return view("academic.syllabus.index", compact("coverage"));
 }

 public function createSyllabus()
 {
 $classes = ClassRoom::orderBy("name")->get();
 $subjects = Subject::where("is_active", true)->orderBy("name")->get();
 $teachers = Staff::where("status", "active")->orderBy("first_name")->get();
 return view("academic.syllabus.create", compact("classes","subjects","teachers"));
 }

 public function storeSyllabus(Request $request)
 {
 $data = $request->validate([
 "classroom_id" => "required|exists:classrooms,id",
 "subject_id" => "required|exists:subjects,id",
 "staff_id" => "nullable|exists:staff,id",
 "term" => "required|string|max:50",
 "year" => "required|integer",
 "planned_topics" => "required|integer|min:0",
 "covered_topics" => "required|integer|min:0",
 "notes" => "nullable|string",
 ]);

 SyllabusCoverage::create($data);

 return redirect()->route("academic.syllabus")->with("success", "Syllabus coverage recorded.");
 }

 /* ============ TIMETABLE ============ */
 public function timetable()
 {
 $assignments = SubjectAssignment::with(["staff","classroom","subject"])->latest()->paginate(30);
 return view("academic.timetable.index", compact("assignments"));
 }

 public function createTimetable()
 {
 $teachers = Staff::where("status", "active")->orderBy("first_name")->get();
 $classes = ClassRoom::orderBy("name")->get();
 $subjects = Subject::where("is_active", true)->orderBy("name")->get();
 return view("academic.timetable.create", compact("teachers","classes","subjects"));
 }

 public function storeTimetable(Request $request)
 {
 $data = $request->validate([
 "staff_id" => "required|exists:staff,id",
 "classroom_id" => "required|exists:classrooms,id",
 "subject_id" => "required|exists:subjects,id",
 "periods_per_week" => "required|integer|min:1|max:20",
 ]);

 SubjectAssignment::updateOrCreate(
 [
 "staff_id" => $data["staff_id"],
 "classroom_id" => $data["classroom_id"],
 "subject_id" => $data["subject_id"],
 ],
 ["periods_per_week" => $data["periods_per_week"]]
 );

 return redirect()->route("academic.timetable")->with("success", "Assignment saved.");
 }

 /* ============ EXAMS ============ */
 public function exams()
 {
 $exams = Exam::with("classroom")->latest()->paginate(20);
 return view("academic.exams.index", compact("exams"));
 }

 public function createExam()
 {
 $classes = ClassRoom::orderBy("name")->get();
 return view("academic.exams.create", compact("classes"));
 }

 public function storeExam(Request $request)
 {
 $data = $request->validate([
 "name" => "required|string|max:100",
 "term" => "required|string|max:50",
 "level" => "required|string",
 "classroom_id" => "nullable|exists:classrooms,id",
 "start_date" => "nullable|date",
 ]);

 $data["school_id"] = \App\Models\School::first()?->id;
 $data["published"] = false;

 Exam::create($data);

 return redirect()->route("academic.exams")->with("success", "Exam created.");
 }

 public function examTimetable(Exam $exam)
 {
 $entries = ExamTimetable::with("subject")->where("exam_id", $exam->id)->orderBy("exam_date")->get();
 $subjects = Subject::where("is_active", true)->orderBy("name")->get();
 return view("academic.exams.timetable", compact("exam","entries","subjects"));
 }

 public function storeExamTimetable(Request $request, Exam $exam)
 {
 $data = $request->validate([
 "subject_id" => "required|exists:subjects,id",
 "exam_date" => "required|date",
 "start_time" => "required",
 "end_time" => "required",
 "venue" => "nullable|string|max:100",
 "invigilator" => "nullable|string|max:150",
 ]);

 $data["exam_id"] = $exam->id;
 ExamTimetable::create($data);

 return back()->with("success", "Timetable entry added.");
 }

 /* ============ RESULTS ============ */
 public function results()
 {
 $results = Result::with(["student","exam"])->latest()->paginate(30);
 return view("academic.results.index", compact("results"));
 }

 /* ============ TEACHERS & ASSIGNMENTS ============ */
 public function teachers()
 {
 $teachers = Staff::whereIn("staff_type", [
 "Teacher","Head of Department","Academic Master","Lab Technician"
 ])->with("subjects")->paginate(20);
 return view("academic.teachers.index", compact("teachers"));
 }

 /* ============ STUDENT PERFORMANCE ============ */
 public function studentPerformance()
 {
 $students = Student::with(["classroom","results"])->where("status", "active")->paginate(30);
 return view("academic.performance.index", compact("students"));
 }

 /* ============ CALENDAR ============ */
 public function calendar()
 {
 $events = AcademicCalendar::orderBy("year","desc")->orderBy("term_start","desc")->paginate(20);
 return view("academic.calendar.index", compact("events"));
 }

 public function createCalendar()
 {
 return view("academic.calendar.create");
 }

 public function storeCalendar(Request $request)
 {
 $data = $request->validate([
 "term" => "required|string|max:50",
 "year" => "required|integer",
 "term_start" => "required|date",
 "term_end" => "required|date|after:term_start",
 "exam_start" => "nullable|date",
 "exam_end" => "nullable|date",
 "notes" => "nullable|string",
 ]);

 AcademicCalendar::create($data);

 return redirect()->route("academic.calendar")->with("success", "Academic calendar updated.");
 }

 /* ============ MEETINGS ============ */
 public function meetings()
 {
 $meetings = AcademicMeeting::latest()->paginate(20);
 return view("academic.meetings.index", compact("meetings"));
 }

 public function createMeeting()
 {
 return view("academic.meetings.create");
 }

 public function storeMeeting(Request $request)
 {
 $data = $request->validate([
 "title" => "required|string|max:150",
 "meeting_type" => "required|in:subject,department,board,parent",
 "meeting_date" => "required|date",
 "meeting_time" => "nullable",
 "venue" => "nullable|string|max:100",
 "agenda" => "nullable|string",
 ]);

 $data["status"] = "planned";
 AcademicMeeting::create($data);

 return redirect()->route("academic.meetings")->with("success", "Meeting created.");
 }

 /* ============ REPORTS ============ */
 public function reports()
 {
 $totalSubjects = Subject::count();
 $totalClasses = ClassRoom::count();
 $totalExams = Exam::count();
 $totalResults = Result::count();
 $avgMarks = Result::avg("marks");

 // Per-class averages
 $classAvg = ClassRoom::withCount("students")->get();

 return view("academic.reports.index", compact(
 "totalSubjects","totalClasses","totalExams","totalResults","avgMarks","classAvg"
 ));
 }

 /* ============ NATIONAL EXAMS ============ */
 public function nationalExams()
 {
 return view("academic.national.index");
 }
}