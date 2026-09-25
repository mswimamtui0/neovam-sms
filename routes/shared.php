<?php

use App\Http\Controllers\Shared\StaffSharedController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "role:teacher,teacher_on_duty,academic_master,head_of_school,admin"])
 ->prefix("staff")
 ->name("shared.")
 ->group(function () {
 Route::get("/", [StaffSharedController::class, "dashboard"])->name("dashboard");
 Route::get("/class-teachers", [StaffSharedController::class, "classTeachers"])->name("class-teachers");
 Route::get("/classes", [StaffSharedController::class, "classesOverview"])->name("classes");
 Route::get("/attendance", [StaffSharedController::class, "attendanceOverview"])->name("attendance");
 Route::get("/duty-roster", [StaffSharedController::class, "dutyRoster"])->name("duty-roster");
 Route::get("/announcements", [StaffSharedController::class, "announcements"])->name("announcements");
 Route::get("/directory", [StaffSharedController::class, "directory"])->name("directory");
 
 /* ============ MY ATTENDANCE ============ */
 Route::get("/my-attendance", [\App\Http\Controllers\Shared\StaffAttendanceController::class, "myAttendance"])->name("my-attendance");
 Route::post("/my-attendance/check-in", [\App\Http\Controllers\Shared\StaffAttendanceController::class, "checkIn"])->name("my-attendance.check-in");
 Route::post("/my-attendance/check-out", [\App\Http\Controllers\Shared\StaffAttendanceController::class, "checkOut"])->name("my-attendance.check-out");
});