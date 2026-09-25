<?php

use App\Http\Controllers\Department\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth"])
 ->prefix("department")
 ->name("department.")
 ->group(function () {

 Route::get("/", [\App\Http\Controllers\Department\DepartmentHubController::class, "index"])->name("hub");

 // Health
 Route::get("/health", [DepartmentController::class, "health"])->name("health");
 Route::get("/health/create", [DepartmentController::class, "healthCreate"])->name("health.create");
 Route::post("/health", [DepartmentController::class, "healthStore"])->name("health.store");

 // Discipline
 Route::get("/discipline", [DepartmentController::class, "discipline"])->name("discipline");
 Route::get("/discipline/create", [DepartmentController::class, "disciplineCreate"])->name("discipline.create");
 Route::post("/discipline", [DepartmentController::class, "disciplineStore"])->name("discipline.store");

 // Sports
 Route::get("/sports", [DepartmentController::class, "sports"])->name("sports");
 Route::get("/sports/create", [DepartmentController::class, "sportsCreate"])->name("sports.create");
 Route::post("/sports", [DepartmentController::class, "sportsStore"])->name("sports.store");

 // Boarding
 Route::get("/boarding", [DepartmentController::class, "boarding"])->name("boarding");
 Route::get("/boarding/create", [DepartmentController::class, "boardingCreate"])->name("boarding.create");
 Route::post("/boarding", [DepartmentController::class, "boardingStore"])->name("boarding.store");

 // Feeding
 Route::get("/feeding", [DepartmentController::class, "feeding"])->name("feeding");
 Route::get("/feeding/create", [DepartmentController::class, "feedingCreate"])->name("feeding.create");
 Route::post("/feeding", [DepartmentController::class, "feedingStore"])->name("feeding.store");

 // Library
 Route::get("/library", [DepartmentController::class, "library"])->name("library");
 Route::get("/library/create", [DepartmentController::class, "libraryCreate"])->name("library.create");
 Route::post("/library", [DepartmentController::class, "libraryStore"])->name("library.store");

 // Guidance
 Route::get("/guidance", [DepartmentController::class, "guidance"])->name("guidance");
 Route::get("/guidance/create", [DepartmentController::class, "guidanceCreate"])->name("guidance.create");
 Route::post("/guidance", [DepartmentController::class, "guidanceStore"])->name("guidance.store");

 // Environment
 Route::get("/environment", [DepartmentController::class, "environment"])->name("environment");
 Route::get("/environment/create", [DepartmentController::class, "environmentCreate"])->name("environment.create");
 Route::post("/environment", [DepartmentController::class, "environmentStore"])->name("environment.store");

 // Security
 Route::get("/security", [DepartmentController::class, "security"])->name("security");
 Route::get("/security/create", [DepartmentController::class, "securityCreate"])->name("security.create");
 Route::post("/security", [DepartmentController::class, "securityStore"])->name("security.store");
 
 // Auto-redirect stubs
 Route::get("/duty-redirect", [\App\Http\Controllers\Department\DepartmentHubController::class, "dutyRedirect"])->name("duty-redirect");
 Route::get("/class-teacher-redirect",[\App\Http\Controllers\Department\DepartmentHubController::class, "classTeacherRedirect"])->name("class-teacher-redirect");
 Route::get("/finance-redirect", [\App\Http\Controllers\Department\DepartmentHubController::class, "financeRedirect"])->name("finance-redirect");
 Route::get("/academic-redirect", [\App\Http\Controllers\Department\DepartmentHubController::class, "academicRedirect"])->name("academic-redirect");
 Route::get("/admin-redirect", [\App\Http\Controllers\Department\DepartmentHubController::class, "adminRedirect"])->name("admin-redirect");
});