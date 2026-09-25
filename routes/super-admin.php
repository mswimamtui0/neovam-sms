<?php

use App\Http\Controllers\SuperAdmin\SchoolController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "super_admin"])
    ->prefix("super-admin")
    ->name("super-admin.")
    ->group(function () {
        Route::get("/",                        [SchoolController::class, "dashboard"])->name("dashboard");
        Route::get("/schools",                 [SchoolController::class, "index"])->name("schools.index");
        Route::get("/schools/compare",         [SchoolController::class, "compare"])->name("schools.compare");
        Route::get("/schools/create",          [SchoolController::class, "create"])->name("schools.create");
        Route::post("/schools",                [SchoolController::class, "store"])->name("schools.store");
        Route::get("/schools/{school}",        [SchoolController::class, "show"])->name("schools.show");
        Route::get("/schools/{school}/edit",   [SchoolController::class, "edit"])->name("schools.edit");
        Route::put("/schools/{school}",        [SchoolController::class, "update"])->name("schools.update");
        Route::delete("/schools/{school}",     [SchoolController::class, "destroy"])->name("schools.destroy");
        Route::post("/schools/{school}/topup", [SchoolController::class, "topupSms"])->name("schools.topup");
        Route::post("/schools/{school}/users/{user}/reset-password", [SchoolController::class, "resetAdmin"])->name("schools.reset-password");
    });