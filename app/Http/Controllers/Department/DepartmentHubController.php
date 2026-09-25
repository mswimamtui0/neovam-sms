<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Services\Department\DepartmentRouter;
use App\Models\Staff;

class DepartmentHubController extends Controller
{
 public function index()
 {
 $staff = Staff::where("user_id", auth()->id())->first();
 $cards = DepartmentRouter::cards($staff);

 return view("department.hub-auto", compact("staff","cards"));
 }

 /* ============ REDIRECT STUBS ============ */
 public function dutyRedirect()
 {
 if (auth()->user()->hasRole("teacher_on_duty")) {
 return redirect()->route("duty.dashboard");
 }
 return redirect()->route("teacher.dashboard");
 }

 public function classTeacherRedirect()
 {
 return redirect()->route("class-teacher.dashboard");
 }

 public function financeRedirect()
 {
 return redirect()->route("admin.dashboard");
 }

 public function academicRedirect()
 {
 if (auth()->user()->hasRole("academic_master")) {
 return redirect()->route("academic.dashboard");
 }
 return redirect()->route("teacher.dashboard");
 }

 public function adminRedirect()
 {
 return redirect()->route("admin.dashboard");
 }
}