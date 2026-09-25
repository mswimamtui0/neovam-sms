<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentPortalController extends Controller
{
 protected function me(): ?Student
 {
 return Student::withoutGlobalScopes()
 ->where("user_id", auth()->id())
 ->where("can_login", true)
 ->first();
 }

 public function dashboard()
 {
 $student = $this->me();
 if (!$student) {
 return view("student.no-profile");
 }
 return view("student.dashboard", compact("student"));
 }

 public function results()
 {
 $student = $this->me();
 if (!$student) return redirect()->route("student.dashboard");
 $student->load("results.exam");
 return view("student.results", compact("student"));
 }

 public function attendance()
 {
 $student = $this->me();
 if (!$student) return redirect()->route("student.dashboard");
 $student->load("attendances");
 return view("student.attendance", compact("student"));
 }
}