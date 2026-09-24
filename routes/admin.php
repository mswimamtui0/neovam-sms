<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\CommunicationController;
use App\Http\Controllers\Admin\EmergencyController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\SchoolSettingsController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth", "role:admin,head_of_school"])
    ->prefix("admin")
    ->name("admin.")
    ->group(function () {

    Route::get("/", fn() => view("admin.dashboard"))->name("dashboard");

    Route::prefix("api")->name("api.")->group(function () {
        Route::get("/classrooms-by-level",      [StudentController::class,      "classroomsByLevel"])->name("classrooms.byLevel");
        Route::get("/classes-by-level",         [EmergencyController::class,    "classesByLevel"])->name("classes.byLevel");
        Route::get("/streams-by-class",         [EmergencyController::class,    "streamsByClass"])->name("streams.byClass");
        Route::get("/students-by-class",        [EmergencyController::class,    "studentsByClass"])->name("students.byClass");
        Route::get("/search-students",          [EmergencyController::class,    "searchStudents"])->name("students.search");

        // Communication
        Route::get("/comm-classrooms-by-level", [CommunicationController::class, "classroomsByLevel"])->name("comm.classrooms.byLevel");
        Route::get("/comm-streams-by-class",    [CommunicationController::class, "streamsByClassroom"])->name("comm.streams.byClass");
        Route::post("/preview-recipients",      [CommunicationController::class, "previewRecipients"])->name("preview.recipients");
    });

    Route::middleware(["role:admin"])->group(function () {
        Route::get("/settings/school",  [SchoolSettingsController::class, "edit"])->name("settings.school");
        Route::post("/settings/school", [SchoolSettingsController::class, "update"])->name("settings.school.update");

        Route::resource("subjects", SubjectController::class);
    });

    Route::resource("students", StudentController::class);
    Route::resource("staff", StaffController::class);

    Route::get("/attendance",        [AttendanceController::class, "index"])->name("attendance.index");
    Route::get("/attendance/create", [AttendanceController::class, "create"])->name("attendance.create");
    Route::post("/attendance",       [AttendanceController::class, "store"])->name("attendance.store");

    Route::get("/emergencies",        [EmergencyController::class, "index"])->name("emergencies.index");
    Route::get("/emergencies/create", [EmergencyController::class, "create"])->name("emergencies.create");
    Route::post("/emergencies",       [EmergencyController::class, "store"])->name("emergencies.store");

    Route::get("/results",                 [ResultController::class, "index"])->name("results.index");
    Route::get("/results/create",          [ResultController::class, "create"])->name("results.create");
    Route::post("/results",                [ResultController::class, "store"])->name("results.store");
    Route::post("/results/publish/{exam}", [ResultController::class, "publish"])->name("results.publish");

    Route::get("/communication",        [CommunicationController::class, "index"])->name("communication.index");
    Route::get("/communication/create", [CommunicationController::class, "create"])->name("communication.create");
    Route::post("/communication/send",  [CommunicationController::class, "send"])
        ->middleware("throttle:sms-bulk")
        ->name("communication.send");
    // Duty Rosters
    Route::resource("duty-rosters", \App\Http\Controllers\Admin\DutyRosterController::class);

    // Announcements
    Route::get("/announcements",                    [\App\Http\Controllers\Admin\AnnouncementController::class, "index"])->name("announcements.index");
    Route::get("/announcements/create",             [\App\Http\Controllers\Admin\AnnouncementController::class, "create"])->name("announcements.create");
    Route::post("/announcements",                   [\App\Http\Controllers\Admin\AnnouncementController::class, "store"])->name("announcements.store");
    Route::delete("/announcements/{announcement}",  [\App\Http\Controllers\Admin\AnnouncementController::class, "destroy"])->name("announcements.destroy");
});