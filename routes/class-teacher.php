<?php

use App\Http\Controllers\ClassTeacher\ClassTeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "role:teacher,teacher_on_duty,academic_master,head_of_school"])
 ->prefix("class-teacher")
 ->name("class-teacher.")
 ->group(function () {

 Route::get("/", [ClassTeacherController::class, "dashboard"])->name("dashboard");
 Route::get("/students", [ClassTeacherController::class, "students"])->name("students");
 Route::get("/students/{student}", [ClassTeacherController::class, "showStudent"])->name("students.show");
 Route::get("/attendance", [ClassTeacherController::class, "attendance"])->name("attendance");
 Route::post("/attendance", [ClassTeacherController::class, "markAttendance"])->name("attendance.mark");

 Route::get("/discipline", [ClassTeacherController::class, "discipline"])->name("discipline");
 Route::get("/discipline/create", [ClassTeacherController::class, "createDiscipline"])->name("discipline.create");
 Route::post("/discipline", [ClassTeacherController::class, "storeDiscipline"])->name("discipline.store");

 Route::get("/performance", [ClassTeacherController::class, "performance"])->name("performance");

 Route::get("/parents", [ClassTeacherController::class, "parents"])->name("parents");
 Route::get("/parents/create", [ClassTeacherController::class, "createParentContact"])->name("parents.create");
 Route::post("/parents", [ClassTeacherController::class, "storeParentContact"])->name("parents.store");

 Route::get("/welfare", [ClassTeacherController::class, "welfare"])->name("welfare");
 Route::get("/welfare/create", [ClassTeacherController::class, "createWelfare"])->name("welfare.create");
 Route::post("/welfare", [ClassTeacherController::class, "storeWelfare"])->name("welfare.store");

 Route::get("/meetings", [ClassTeacherController::class, "meetings"])->name("meetings");
 Route::get("/meetings/create", [ClassTeacherController::class, "createMeeting"])->name("meetings.create");
 Route::post("/meetings", [ClassTeacherController::class, "storeMeeting"])->name("meetings.store");
 });