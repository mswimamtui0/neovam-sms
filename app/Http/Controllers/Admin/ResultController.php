<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendResultSms;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Student;
use App\Services\Academic\ResultService;
use Illuminate\Http\Request;

class ResultController extends Controller
{
 public function index()
 {
 $exams = Exam::with("classroom")->latest()->paginate(20);
 return view("admin.results.index", compact("exams"));
 }

 public function create()
 {
 $exams = Exam::all();
 $students = Student::where("status", "active")->get();
 return view("admin.results.create", compact("exams", "students"));
 }

 public function store(Request $request)
 {
 $data = $request->validate([
 "exam_id" => "required|exists:exams,id",
 "student_id" => "required|exists:students,id",
 "subject" => "required|string|max:100",
 "marks" => "required|integer|min:0|max:100",
 ]);

 $data["grade"] = ResultService::grade($data["marks"]);
 Result::updateOrCreate(
 [
 "exam_id" => $data["exam_id"],
 "student_id" => $data["student_id"],
 "subject" => $data["subject"],
 ],
 ["marks" => $data["marks"], "grade" => $data["grade"]]
 );

 return back()->with("success", "Result recorded.");
 }

 /**
 * Publish an exam: compute positions, send SMS to parents.
 */
 public function publish(Exam $exam)
 {
 // Process results (compute averages, positions)
 $processed = ResultService::process($exam);

 if (!$processed["success"]) {
 return back()->with("error", $processed["message"]);
 }

 // Mark exam as published
 $exam->update([
 "published" => true,
 "published_at" => now(),
 "published_by" => auth()->id(),
 ]);

 // Send SMS to each parent
 $studentIds = Result::where("exam_id", $exam->id)
 ->distinct("student_id")
 ->pluck("student_id");

 $sent = 0;
 foreach ($studentIds as $sid) {
 $student = Student::find($sid);
 if (!$student) continue;

 $summary = ResultService::smsSummary($sid, $exam->id);

 SendResultSms::dispatch(
 $student->parent_phone,
 $student->full_name,
 $exam->name,
 $summary
 );
 $sent++;
 }

 return back()->with(
 "success",
 "Exam published. {$sent} parents notified via SMS."
 );
 }

 /**
 * View a class result sheet.
 */
 public function show(Exam $exam)
 {
 $exam->load("classroom");

 $students = Result::where("exam_id", $exam->id)
 ->distinct("student_id")
 ->with("student")
 ->get()
 ->groupBy("student_id");

 return view("admin.results.show", compact("exam","students"));
 }
}