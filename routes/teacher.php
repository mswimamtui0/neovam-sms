<?php

use App\Http\Controllers\Teacher\DutyController;
use App\Http\Controllers\Teacher\TeacherController;
use Illuminate\Support\Facades\Route;

// Common teacher routes (all teacher roles)
Route::middleware(["auth", "role:teacher,teacher_on_duty,academic_master,head_of_school"])
 ->prefix("teacher")
 ->name("teacher.")
 ->group(function () {

 Route::get("/", [TeacherController::class, "dashboard"])->name("dashboard");
 Route::get("/timetable", [TeacherController::class, "myTimetable"])->name("timetable");
 Route::get("/classes", [TeacherController::class, "classes"])->name("classes");
 Route::get("/students", [TeacherController::class, "students"])->name("students");
 Route::get("/attendance", [TeacherController::class, "attendance"])->name("attendance");
 Route::post("/attendance", [TeacherController::class, "markAttendance"])->name("attendance.mark");
 Route::get("/lesson-plans", [TeacherController::class, "lessonPlans"])->name("lesson-plans");
 Route::get("/lesson-plans/create", [TeacherController::class, "createLessonPlan"])->name("lesson-plans.create");
 Route::post("/lesson-plans", [TeacherController::class, "storeLessonPlan"])->name("lesson-plans.store");
 Route::get("/schemes", [TeacherController::class, "schemes"])->name("schemes");
 Route::get("/schemes/create", [TeacherController::class, "createScheme"])->name("schemes.create");
 Route::post("/schemes", [TeacherController::class, "storeScheme"])->name("schemes.store");
 Route::get("/duty", [TeacherController::class, "dutyRoster"])->name("duty");
 Route::get("/department", [TeacherController::class, "departmentTasks"])->name("department");
 Route::get("/parents", [TeacherController::class, "parentContacts"])->name("parents");
 Route::get("/parents/create", [TeacherController::class, "createParentContact"])->name("parents.create");
 Route::post("/parents", [TeacherController::class, "storeParentContact"])->name("parents.store");
 Route::get("/reports", [TeacherController::class, "reports"])->name("reports");
 Route::get("/reports/create", [TeacherController::class, "createReport"])->name("reports.create");
 Route::post("/reports", [TeacherController::class, "storeReport"])->name("reports.store");
 Route::get("/incidents", [TeacherController::class, "incidents"])->name("incidents");
 Route::get("/leave", [TeacherController::class, "leaves"])->name("leave");
 Route::get("/leave/create", [TeacherController::class, "createLeave"])->name("leave.create");
 Route::post("/leave", [TeacherController::class, "storeLeave"])->name("leave.store");
 Route::get("/profile", [TeacherController::class, "profile"])->name("profile");
 });

// Teacher on Duty specific routes
Route::middleware(["auth", "role:teacher_on_duty,academic_master,head_of_school"])
 ->prefix("duty")
 ->name("duty.")
 ->group(function () {

 Route::get("/", [DutyController::class, "dashboard"])->name("dashboard");
 Route::get("/schedule", [DutyController::class, "schedule"])->name("schedule");

 // Supervision
 Route::get("/supervision", [DutyController::class, "supervision"])->name("supervision");
 Route::post("/supervision", [DutyController::class, "logSupervision"])->name("supervision.log");

 // Daily reports
 Route::get("/reports", [DutyController::class, "reports"])->name("reports");
 Route::get("/reports/create", [DutyController::class, "createReport"])->name("reports.create");
 Route::post("/reports", [DutyController::class, "storeReport"])->name("reports.store");

 // Handover
 Route::get("/handovers", [DutyController::class, "handovers"])->name("handovers");
 Route::get("/handovers/create", [DutyController::class, "createHandover"])->name("handovers.create");
 Route::post("/handovers", [DutyController::class, "storeHandover"])->name("handovers.store");
 });