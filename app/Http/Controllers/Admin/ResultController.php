<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendResultSms;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;

class ResultController extends Controller
{
 public function index()
 {
 $exams = Exam::with('classroom')->latest()->paginate(20);
 return view('admin.results.index', compact('exams'));
 }

 public function create()
 {
 $exams = Exam::all();
 $students = Student::where('status', 'active')->get();
 return view('admin.results.create', compact('exams', 'students'));
 }

 public function store(Request $request)
 {
 $data = $request->validate([
 'exam_id' => 'required|exists:exams,id',
 'student_id' => 'required|exists:students,id',
 'subject' => 'required|string|max:100',
 'marks' => 'required|integer|min:0|max:100',
 ]);

 $data['grade'] = $this->grade($data['marks']);
 Result::create($data);

 return back()->with('success', 'Result recorded.');
 }

 public function publish(Exam $exam)
 {
 $exam->update(['published' => true]);

 // Group results by student and send one SMS per parent
 $grouped = Result::with('student')
 ->where('exam_id', $exam->id)
 ->get()
 ->groupBy('student_id');

 foreach ($grouped as $studentId => $results) {
 $student = $results->first()->student;
 if (!$student) continue;

 $average = round($results->avg('marks'), 1);
 $summary = "AVG: {$average}%";

 SendResultSms::dispatch(
 $student->parent_phone,
 $student->full_name,
 $exam->name,
 $summary
 );

 Result::whereIn('id', $results->pluck('id'))->update(['sms_sent' => true]);
 }

 return back()->with('success', 'Exam published. Parents notified via SMS.');
 }

 protected function grade(int $marks): string
 {
 return match (true) {
 $marks >= 75 => 'A',
 $marks >= 65 => 'B',
 $marks >= 50 => 'C',
 $marks >= 40 => 'D',
 $marks >= 30 => 'E',
 default => 'F',
 };
 }
}