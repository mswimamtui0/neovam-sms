<?php

use App\Http\Controllers\Parent\ParentController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "role:parent"])
 ->prefix("parent")
 ->name("parent.")
 ->group(function () {
 Route::get("/", [ParentController::class, "dashboard"])->name("dashboard");
 Route::get("/child/{student}",[ParentController::class, "child"])->name("child");
 Route::get("/results", [ParentController::class, "results"])->name("results");
 Route::get("/attendance", [ParentController::class, "attendance"])->name("attendance");
 Route::get("/promotions", [ParentController::class, "promotions"])->name("promotions");
 Route::get("/fees", [ParentController::class, "fees"])->name("fees");
 Route::get("/payments", [ParentController::class, "payments"])->name("payments");
 Route::get("/timetable", [ParentController::class, "timetable"])->name("timetable");
 Route::get("/incidents", [ParentController::class, "incidents"])->name("incidents");
 Route::get("/announcements", [ParentController::class, "announcements"])->name("announcements");
 // PDF Report Card for own child
 Route::get("/child/{student}/report-card/{exam}",
 [App\Http\Controllers\Parent\ParentController::class, "reportCard"])->name("report-card");
});