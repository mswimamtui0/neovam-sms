<?php

use App\Http\Controllers\Student\StudentPortalController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "role:student"])
 ->prefix("student")
 ->name("student.")
 ->group(function () {
 Route::get("/", [StudentPortalController::class, "dashboard"])->name("dashboard");
 Route::get("/results", [StudentPortalController::class, "results"])->name("results");
 Route::get("/attendance", [StudentPortalController::class, "attendance"])->name("attendance");
 });