<?php

use App\Http\Controllers\Parent\ParentController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "role:parent"])
    ->prefix("parent")
    ->name("parent.")
    ->group(function () {
        Route::get("/",               [ParentController::class, "dashboard"])->name("dashboard");
        Route::get("/child/{student}",[ParentController::class, "child"])->name("child");
        Route::get("/results",        [ParentController::class, "results"])->name("results");
        Route::get("/attendance",     [ParentController::class, "attendance"])->name("attendance");
        Route::get("/incidents",      [ParentController::class, "incidents"])->name("incidents");
    });